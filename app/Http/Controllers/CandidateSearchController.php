<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateSearchController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isCompany()) abort(403);

        $query = User::where('role', User::ROLE_CANDIDATE);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('education_level')) {
            // Égalité exacte : « Bac » ne ramène plus Bac+3 ni Bac+5
            $query->where('education_level', $request->education_level);
        }

        if ($request->filled('experience_years')) {
            $query->where('experience_years', '>=', $request->experience_years);
        }

        // ── CORRECTION : category_id filtrait via une sous-query incorrecte ──
        // On filtre maintenant directement sur le champ 'domain' du candidat
        if ($request->filled('category_id')) {
            $categoryName = Category::find($request->category_id)?->name;
            if ($categoryName) {
                $query->where('domain', 'like', "%{$categoryName}%");
            }
        }

        $candidates = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Nombres de candidats affichés dans les listes de filtres (affichage seulement)
        $all = User::where('role', User::ROLE_CANDIDATE)->get(['city', 'domain', 'education_level']);
        $cityCounts   = $all->whereNotNull('city')->countBy('city');
        $levelCounts  = $all->whereNotNull('education_level')->countBy('education_level');
        $domainCounts = $categories->mapWithKeys(fn ($c) => [
            $c->id => $all->filter(fn ($u) => $u->domain && mb_stripos($u->domain, $c->name) !== false)->count(),
        ]);

        return view('candidates.index', [
            'candidates'   => $candidates,
            'categories'   => $categories,
            'cityCounts'   => $cityCounts,
            'levelCounts'  => $levelCounts,
            'domainCounts' => $domainCounts,
            'filters'    => $request->only(['search', 'city', 'category_id', 'education_level', 'experience_years']),
        ]);
    }

    public function show(User $candidate)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isCompany() || !$candidate->isCandidate()) abort(403);

        return view('candidates.show', compact('candidate'));
    }

    public function downloadCv(User $candidate)
    {
        /** @var User $user */
        $user = Auth::user();

        if (
            !$user->isCompany() &&
            !$user->isAdmin() &&
            $user->id !== $candidate->id
        ) {
            abort(403, 'Accès non autorisé.');
        }

        // ── CORRECTION : normaliser le chemin avant de vérifier ──
        $cvPath = $this->normalizePath($candidate->cv_path);

        if (!$cvPath || !Storage::disk('public')->exists($cvPath)) {
            return back()->with('error', 'Le fichier CV est introuvable.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->download($cvPath);
    }

    private function normalizePath(?string $path): ?string
    {
        if (!$path) return null;
        $path = preg_replace('#^storage/app/public/#', '', $path);
        $path = preg_replace('#^public/#', '', $path);
        return $path;
    }
}
