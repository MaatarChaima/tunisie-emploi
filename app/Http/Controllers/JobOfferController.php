<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Category;
use App\Models\User;
use App\Notifications\NewJobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class JobOfferController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('job-offers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'expiration_date'  => 'required|date|after:today',
            'location'         => 'required|string|max:255',
            'type'             => 'required|in:full-time,part-time,remote',
            'category_id'      => 'required|exists:categories,id',
            'education_level'  => 'required|string',
            'experience_years' => 'required|integer|min:0|max:40',
            'salary'           => 'nullable|numeric|min:0',
            'languages'        => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:255',
            'vacancies'        => 'required|integer|min:1',
        ]);

        JobOffer::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'requirements'     => $request->requirements,
            'expiration_date'  => $request->expiration_date,
            'location'         => $request->location,
            'type'             => $request->type,
            'education_level'  => $request->education_level,
            'experience_years' => $request->experience_years,
            'salary'           => $request->salary,
            'languages'        => $request->languages,
            'keywords'         => $request->keywords,
            'vacancies'        => $request->vacancies,
            'status'           => 'pending_validation',
        ]);

        return redirect()->route('job-offers.my-offers')
            ->with('success', 'Votre offre est en attente de modération par un administrateur.');
    }

    public function edit(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('job-offers.edit', compact('jobOffer', 'categories'));
    }

    /**
     * FIX : La validation utilisait 'category' au lieu de 'category_id'
     *       et $request->all() exposait des champs non validés.
     *       On utilise uniquement les champs validés.
     */
    public function update(Request $request, JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'expiration_date'  => 'required|date|after_or_equal:today',
            'location'         => 'required|string|max:255',
            'type'             => 'required|in:full-time,part-time,remote',
            'category_id'      => 'required|exists:categories,id',  // FIX : category → category_id
            'education_level'  => 'required|string',
            'experience_years' => 'required|integer|min:0|max:40',
            'salary'           => 'nullable|numeric|min:0',
            'languages'        => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:255',
            'vacancies'        => 'required|integer|min:1',
            'status'           => 'sometimes|in:open,closed,archived',
        ]);

        // Offre en attente ou refusée : le statut n'est pas modifiable par l'entreprise ;
        // une offre refusée modifiée repasse en attente de modération.
        $resubmitted = false;
        if (in_array($jobOffer->status, ['pending_validation', 'refused'], true)) {
            $resubmitted = $jobOffer->status === 'refused';
            $validated['status'] = 'pending_validation';
        }

        $jobOffer->update($validated); // FIX : $request->all() → $validated

        if ($resubmitted) {
            return redirect()->route('job-offers.my-offers')
                ->with('success', 'Offre modifiée et soumise à nouveau à la modération.');
        }

        return redirect()->route('job-offers.my-offers')
            ->with('success', 'Offre mise à jour avec succès.');
    }

    public function archive(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        // Seule une offre ouverte peut être archivée
        if ($jobOffer->status !== 'open') {
            return back()->with('error', 'Seule une offre ouverte peut être archivée.');
        }
        $jobOffer->update(['status' => 'archived']);
        return back()->with('success', 'Offre archivée avec succès.');
    }

    public function unarchive(JobOffer $jobOffer)
    {
        if ($jobOffer->user_id !== Auth::id()) {
            abort(403);
        }
        if ($jobOffer->status !== 'archived') {
            return back()->with('error', 'Seule une offre archivée peut être réactivée.');
        }

        // Réactivation seulement si la date limite n'est pas dépassée
        if ($jobOffer->isExpired()) {
            return back()
                ->with('error', 'Modifiez la date limite pour réactiver cette offre.')
                ->with('error_action', ['url' => route('job-offers.edit', $jobOffer), 'label' => 'Modifier l’offre']);
        }

        $jobOffer->update(['status' => 'open']);
        return back()->with('success', 'Offre réactivée : elle est de nouveau visible par les candidats.');
    }

    public function myOffers(Request $request)
    {
        Auth::user()->update(['my_offers_seen_at' => now()]);

        $baseQuery = JobOffer::where('user_id', Auth::id());

        // Stats across ALL offers (unfiltered)
        $allOffers = (clone $baseQuery)->withCount('applications')->get();
        $summaryStats = [
            // « Active » = ouverte et date limite non dépassée (même règle que le tableau de bord admin)
            'active_count'       => $allOffers->where('status', 'open')
                ->filter(fn ($o) => !$o->expiration_date || \Illuminate\Support\Carbon::parse($o->expiration_date)->gte(today()))
                ->count(),
            'pending_count'      => $allOffers->where('status', 'pending_validation')->count(),
            'archived_count'     => $allOffers->where('status', 'archived')->count(),
            'applications_total' => $allOffers->sum('applications_count'),
        ];

        $sort   = $request->input('sort', 'date');
        $dir    = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $status = $request->input('status');

        $validStatuses = ['open', 'pending_validation', 'refused', 'archived', 'closed'];
        if ($status && in_array($status, $validStatuses)) {
            $baseQuery->where('status', $status);
        }

        $baseQuery->with(['company', 'offerCategory'])->withCount('applications');

        if ($sort === 'title') {
            $baseQuery->orderBy('title', $dir);
        } elseif ($sort === 'applications') {
            $baseQuery->orderBy('applications_count', $dir);
        } else {
            $baseQuery->orderBy('created_at', $dir);
        }

        $jobOffers = $baseQuery->paginate(10)->withQueryString();

        return view('job-offers.my-offers', compact('jobOffers', 'summaryStats', 'sort', 'dir', 'status'));
    }

    public function index(Request $request)
    {
        $query = JobOffer::with(['company', 'offerCategory']); // category chargé pour éviter N+1

        // FIX : Auth::user() peut être null si le visiteur n'est pas connecté
        // index() et show() sont accessibles sans authentification
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            // Admin voit tout (toutes offres, tous statuts)
        } elseif ($user && $user->isCompany()) {
            // Entreprise : offres ouvertes + ses propres offres
            $query->where(function ($q) {
                $q->publiclyVisible()
                  ->orWhere('user_id', Auth::id());
            });
        } else {
            // Visiteur non connecté ou candidat : uniquement les offres ouvertes
            $query->publiclyVisible();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title',    'like', "%{$search}%")
                  ->orWhere('keywords',  'like', "%{$search}%")
                  ->orWhere('location',  'like', "%{$search}%")
                  ->orWhereHas('company',      fn($r) => $r->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('offerCategory', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Tri : critère (sort) et ordre (dir). Offres sans salaire toujours à la fin ; pour la date
        // limite, offres sans date limite puis dates dépassées à la fin, dans les deux ordres.
        [$sort, $dir] = self::sortState($request);
        match ($sort) {
            'salary'   => $query->orderByRaw('salary IS NULL')->orderBy('salary', $dir)->latest(),
            'deadline' => $query->orderByRaw('expiration_date IS NULL')
                                ->orderByRaw('expiration_date < ?', [today()->toDateString()])
                                ->orderBy('expiration_date', $dir)->latest(),
            default    => $query->orderBy('created_at', $dir)->orderBy('id', $dir),
        };

        $jobOffers  = $query->paginate(10)->withQueryString();
        // Nombre d'offres ouvertes par domaine (affiché dans la liste « Domaine »)
        $categories = Category::withCount(['jobOffers as open_offers_count' => fn ($q) => $q->publiclyVisible()])
            ->orderBy('name')->get();

        // Chiffres et suggestions du hero : offres visibles publiquement uniquement
        $public = fn () => JobOffer::publiclyVisible();
        // Les trois compteurs en une seule requête
        $totals = $public()->toBase()->selectRaw(
            "count(*) as offers, count(distinct user_id) as companies, count(distinct case when location <> '' then location end) as cities"
        )->first();
        $heroStats = [
            'offers'    => (int) $totals->offers,
            'companies' => (int) $totals->companies,
            'cities'    => (int) $totals->cities,
        ];
        $popularCategories = Category::withCount(['jobOffers' => fn ($q) => $q->publiclyVisible()])
            ->having('job_offers_count', '>', 0)
            ->orderByDesc('job_offers_count')->take(4)->get();
        $cityCounts = $public()->whereNotNull('location')->where('location', '!=', '')
            ->selectRaw('location, count(*) as total')->groupBy('location')
            ->orderByDesc('total')->pluck('total', 'location');
        $popularCities = $cityCounts->keys()->take(4);
        $cities        = $cityCounts->keys()->sort(fn ($a, $b) => strcoll($a, $b))->values();
        // Nombre d'offres ouvertes par niveau d'études (liste « Niveau d'études »)
        $levelCounts = $public()->whereNotNull('education_level')
            ->selectRaw('education_level, count(*) as total')->groupBy('education_level')->pluck('total', 'education_level');

        return view('job-offers.index', [
            'jobOffers'         => $jobOffers,
            'categories'        => $categories,
            'filters'           => $request->only(['search', 'location', 'category_id', 'education_level', 'type']),
            'sort'              => $sort,
            'dir'               => $dir,
            // Paramètres de tri à conserver dans les liens (omis quand ils valent la valeur par défaut)
            'sortParams'        => array_filter([
                'sort' => $sort !== 'date' ? $sort : null,
                'dir'  => $dir !== self::SORT_DEFAULT_DIR[$sort] ? $dir : null,
            ]),
            'heroStats'         => $heroStats,
            'popularCategories' => $popularCategories,
            'popularCities'     => $popularCities,
            'cities'            => $cities,
            'cityCounts'        => $cityCounts,
            'levelCounts'       => $levelCounts,
        ]);
    }

    /** Ordre par défaut de chaque critère : récentes d'abord, salaire décroissant, date limite la plus proche. */
    public const SORT_DEFAULT_DIR = ['date' => 'desc', 'salary' => 'desc', 'deadline' => 'asc'];

    /**
     * Critère et ordre de tri lus dans la requête. Les anciennes URL restent comprises :
     * ?sort=recent, salary, salary_desc, salary_asc, deadline.
     */
    private static function sortState(Request $request): array
    {
        $legacy = [
            'recent'      => ['date', 'desc'],
            'salary_desc' => ['salary', 'desc'],
            'salary_asc'  => ['salary', 'asc'],
        ];
        $raw  = (string) $request->input('sort', '');
        $sort = array_key_exists($raw, self::SORT_DEFAULT_DIR) ? $raw : ($legacy[$raw][0] ?? 'date');
        $dir  = $request->input('dir');
        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = $legacy[$raw][1] ?? self::SORT_DEFAULT_DIR[$sort];
        }

        return [$sort, $dir];
    }

    public function show(JobOffer $jobOffer)
    {
        // Règles d'accès (JobOffer::isViewableBy) : offre visible pour tous ; sinon entreprise propriétaire,
        // admin, et — pour une offre archivée, clôturée ou expirée — le candidat qui y a postulé (lecture seule).
        if (!$jobOffer->isViewableBy(Auth::user())) {
            abort(404);
        }

        $jobOffer->load(['company', 'offerCategory']);

        // Offres similaires : même catégorie, à défaut même ville (offres visibles uniquement)
        $similarOffers = JobOffer::publiclyVisible()
            ->with('company')
            ->where('id', '!=', $jobOffer->id)
            ->where(fn ($q) => $q->where('category_id', $jobOffer->category_id)->orWhere('location', $jobOffer->location))
            ->orderByRaw('category_id = ? desc', [$jobOffer->category_id])
            ->latest()
            ->take(3)
            ->get();

        return view('job-offers.show', compact('jobOffer', 'similarOffers'));
    }
}
