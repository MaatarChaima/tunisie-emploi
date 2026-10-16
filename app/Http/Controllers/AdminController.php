<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobOffer;
use App\Models\Application;
use App\Models\Report;
use App\Models\Category;
use App\Services\DashboardStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = new DashboardStats($request->query('periode'));

        return view('admin.dashboard', [
            'period'   => $stats->period,
            'periods'  => DashboardStats::PERIODS,
            'todo'     => $stats->todo($request->user()),
            'tiles'    => $stats->tiles(),
            'activity' => $stats->activity(),
            'byCategory' => $stats->byCategory(),
            'byCity'   => $stats->byCity(),
            'byStatus' => $stats->byStatus(),
            'recent'   => $stats->recentActivity(),
        ]);
    }

    public function moderateOffers(\Illuminate\Http\Request $request)
    {
        auth()->user()->update(['offers_seen_at' => now()]);

        $sort   = in_array($request->get('sort'), ['title', 'company', 'date']) ? $request->get('sort') : 'date';
        $dir    = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $status = in_array($request->get('status'), ['pending_validation', 'open', 'refused', 'closed']) ? $request->get('status') : '';

        $query = JobOffer::with(['company', 'offerCategory']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($sort === 'company') {
            $query->join('users as co', 'job_offers.user_id', '=', 'co.id')
                  ->orderBy('co.name', $dir)
                  ->select('job_offers.*');
        } elseif ($sort === 'title') {
            $query->orderBy('title', $dir);
        } else {
            $query->orderBy('job_offers.created_at', $dir);
        }

        $offers        = $query->paginate(15)->withQueryString();
        $pendingCount  = JobOffer::where('status', 'pending_validation')->count();

        return view('admin.offers.moderation', compact('offers', 'sort', 'dir', 'status', 'pendingCount'));
    }

    public function updateOfferStatus(Request $request, JobOffer $jobOffer)
    {
        $request->validate([
            'status' => 'required|in:open,refused',
        ]);

        $jobOffer->update(['status' => $request->status]);

        if ($request->status === 'open') {
            // Notify all candidates when an offer is validated
            $candidates = User::where('role', User::ROLE_CANDIDATE)->get();
            \Illuminate\Support\Facades\Notification::send($candidates, new \App\Notifications\NewJobOffer($jobOffer));
        }

        return back()->with('success', 'Le statut de l\'offre a été mis à jour.');
    }
    // Dans AdminController.php

public function showOffer(JobOffer $jobOffer)
{
    // On charge l'entreprise et la catégorie pour avoir toutes les infos
    $jobOffer->load(['company', 'offerCategory']);
    
    return view('admin.offers.show', compact('jobOffer'));
}
    public function moderateProfiles(Request $request)
    {
        auth()->user()->update(['users_seen_at' => now()]);

        $sort      = $request->input('sort', 'date');
        $dir       = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $role      = $request->input('role');
        $validated = $request->input('validated'); // '1', '0', or null

        $query = User::withCount(['jobOffers', 'applications']);

        if ($role === 'company') {
            $query->where('role', User::ROLE_COMPANY);
        } elseif ($role === 'candidate') {
            $query->where('role', User::ROLE_CANDIDATE);
        }

        if ($validated === '1') {
            $query->where('is_validated', true);
        } elseif ($validated === '0') {
            $query->where('is_validated', false);
        }

        if ($sort === 'name') {
            $query->orderBy('name', $dir);
        } else {
            $query->orderBy('created_at', $dir);
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.moderation', compact('users', 'sort', 'dir', 'role', 'validated'));
    }

    public function showUser(User $user)
    {
        $user->loadCount(['jobOffers', 'applications']);
        $recentOffers = $user->isCompany()
            ? $user->jobOffers()->with('offerCategory')->withCount('applications')->latest()->limit(5)->get()
            : collect();
        $recentApplications = $user->isCandidate()
            ? $user->applications()->with('jobOffer.company')->latest()->limit(5)->get()
            : collect();
        return view('admin.users.show', compact('user', 'recentOffers', 'recentApplications'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        if ($request->has('is_validated')) {
            $user->update(['is_validated' => $request->is_validated]);
        }

        if ($request->has('is_blocked')) {
            $user->update(['is_blocked' => $request->is_blocked]);
        }

        return back()->with('success', 'Le statut de l\'utilisateur a été mis à jour.');
    }

    public function viewReports(Request $request)
    {
        $sort = $request->input('sort', 'date');
        $dir  = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = Report::with(['user', 'reportedUser', 'treatedBy']);

        // Filtre de traitement : ?statut=non-traites | traites
        $statut = in_array($request->input('statut'), ['non-traites', 'traites'], true) ? $request->input('statut') : null;
        if ($statut === 'non-traites') {
            $query->whereNull('reports.treated_at');
        } elseif ($statut === 'traites') {
            $query->whereNotNull('reports.treated_at');
        }

        if ($sort === 'reporter') {
            $query->join('users as reporter', 'reports.user_id', '=', 'reporter.id')
                  ->orderBy('reporter.name', $dir)
                  ->select('reports.*');
        } elseif ($sort === 'reported') {
            $query->join('users as reported', 'reports.reported_user_id', '=', 'reported.id')
                  ->orderBy('reported.name', $dir)
                  ->select('reports.*');
        } else {
            $query->orderBy('reports.created_at', $dir);
        }

        $reports      = $query->paginate(15)->withQueryString();
        $totalReports = Report::count();
        $untreatedCount = Report::untreated()->count();
        $blockedCount = Report::whereHas('reportedUser', fn($u) => $u->where('is_blocked', true))->count();

        auth()->user()->update(['reports_seen_at' => now()]);

        return view('admin.reports.index', compact('reports', 'totalReports', 'untreatedCount', 'blockedCount', 'sort', 'dir', 'statut'));
    }

    public function showReport(Report $report)
    {
        $report->load(['user', 'reportedUser']);
        return view('admin.reports.show', compact('report'));
    }

    /** Marquer un signalement comme traité, ou le rouvrir. */
    public function toggleReportTreated(Report $report)
    {
        if ($report->isTreated()) {
            $report->update(['treated_at' => null, 'treated_by' => null]);

            return back()->with('success', 'Signalement remis dans les signalements à traiter.');
        }

        $report->update(['treated_at' => now(), 'treated_by' => auth()->id()]);

        return back()->with('success', 'Signalement marqué comme traité.');
    }

    public function exportStats(Request $request)
    {
        $stats = new DashboardStats($request->query('periode'));
        $rows  = $stats->csvRows($request->user());

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8 pour Excel
            foreach ($rows as $row) {
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        $filename = 'statistiques_' . $stats->period . '_' . now()->format('Y-m-d') . '.csv';

        return Response::stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }
}
