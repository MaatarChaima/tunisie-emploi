<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Application;
use App\Notifications\ReportUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function create(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->isCandidate()) {
            if (!$user->isCompany()) {
                return back()->with('error', 'Vous ne pouvez signaler que des entreprises.');
            }

            $hasApplied = Application::where('user_id', $currentUser->id)
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $user->id))
                ->exists();

            if (!$hasApplied) {
                return back()->with('error', 'Vous devez avoir postulé à une offre de cette entreprise pour pouvoir la signaler.');
            }
        }

        if ($currentUser->isCompany() && !$user->isCandidate()) {
            return back()->with('error', 'Vous ne pouvez signaler que des candidats.');
        }

        $existingReport = Report::where('user_id', $currentUser->id)
            ->where('reported_user_id', $user->id)
            ->first();

        return view('reports.create', compact('user', 'existingReport'));
    }

    public function store(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->isCandidate()) {
            $hasApplied = Application::where('user_id', $currentUser->id)
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $user->id))
                ->exists();

            if (!$hasApplied) {
                return back()->with('error', 'Action non autorisée.');
            }
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Une modification apporte une information nouvelle : le signalement redevient à traiter
        $report = Report::updateOrCreate(
            ['user_id' => $currentUser->id, 'reported_user_id' => $user->id],
            ['reason' => $request->reason, 'treated_at' => null, 'treated_by' => null]
        );

        if (!$report->wasRecentlyCreated) {
            // Une seule notification par signalement et par admin : on met à jour celle qui existe
            // (contenu, date, de nouveau non lue) au lieu d'en empiler une à chaque modification.
            $notification = new ReportUpdated($report);
            foreach (User::where('role', User::ROLE_ADMIN)->get() as $admin) {
                $existing = $admin->notifications()
                    ->where('type', ReportUpdated::class)
                    ->where('data->report_id', $report->id)
                    ->first();

                if ($existing) {
                    $existing->forceFill([
                        'data'       => $notification->toArray($admin),
                        'read_at'    => null,
                        'created_at' => now(),
                    ])->save();
                } else {
                    $admin->notify($notification);
                }
            }
        }

        $targetLabel = $user->isCompany() ? 'L\'entreprise' : 'Le candidat';
        $action = $report->wasRecentlyCreated ? 'signalé(e)' : 'mis à jour';
        return redirect()->route('dashboard')->with('success', $targetLabel . ' a été ' . $action . '.');
    }
}
