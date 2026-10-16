<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $savedJobs = $user->savedJobs()->with('jobOffer.company')->latest()->paginate(10)->withQueryString();
        return view('saved-jobs.index', compact('savedJobs'));
    }

    public function toggle(JobOffer $jobOffer)
    {
        /** @var User $user */
        $user = Auth::user();

        $saved = SavedJob::where('user_id', $user->id)
            ->where('job_offer_id', $jobOffer->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $status = 'removed';
            $message = 'Offre retirée des favoris.';
        } else {
            SavedJob::create([
                'user_id' => $user->id,
                'job_offer_id' => $jobOffer->id,
            ]);
            $status = 'added';
            $message = 'Offre ajoutée aux favoris.';
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'is_saved' => $status === 'added'
            ]);
        }

        return back()->with('success', $message);
    }
}
