<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // ── CV (candidat) ──────────────────────────────────────
        if ($request->hasFile('cv')) {
            // Supprimer l'ancien fichier avant d'uploader le nouveau
            if ($user->cv_path) {
                Storage::disk('public')->delete($user->cv_path);
            }
            // store() sur disque 'public' stocke "cvs/fichier.pdf" en base
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        // ── Logo (entreprise) ──────────────────────────────────
        if ($request->hasFile('logo')) {
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Le compte administrateur (unique) ne peut pas être supprimé
        if ($request->user()->isAdmin()) {
            return Redirect::route('profile.edit')->with('error', 'Le compte administrateur ne peut pas être supprimé.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Supprimer les fichiers liés au compte
        if ($user->cv_path) {
            Storage::disk('public')->delete($user->cv_path);
        }
        if ($user->logo_path) {
            Storage::disk('public')->delete($user->logo_path);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function downloadCv(Request $request)
    {
        $user = $request->user();
        if (!$user->cv_path || !Storage::disk('public')->exists($user->cv_path)) {
            return back()->with('error', 'Aucun CV disponible.');
        }
        return Storage::disk('public')->download($user->cv_path, 'CV_' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}
