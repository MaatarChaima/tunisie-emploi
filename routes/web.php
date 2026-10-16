<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CandidateSearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('job-offers.index');
});
Route::get('/job-offers', [JobOfferController::class, 'index'])->name('job-offers.index');

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->isCompany()) {
        return redirect()->route('job-offers.my-offers');
    }
    return redirect()->route('job-offers.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/cv/download', [ProfileController::class, 'downloadCv'])->name('profile.cv.download');

    // Job Offers
    Route::middleware('check_validation')->group(function () {
        Route::get('/job-offers/create', [JobOfferController::class, 'create'])->name('job-offers.create');
        Route::post('/job-offers', [JobOfferController::class, 'store'])->name('job-offers.store');
    });

    
    Route::get('/my-offers', [JobOfferController::class, 'myOffers'])->name('job-offers.my-offers')->middleware('company');
    
    
    Route::middleware('check_validation')->group(function () {
        Route::get('/job-offers/{jobOffer}/edit', [JobOfferController::class, 'edit'])->name('job-offers.edit');
        Route::patch('/job-offers/{jobOffer}', [JobOfferController::class, 'update'])->name('job-offers.update');
        Route::post('/job-offers/{jobOffer}/archive', [JobOfferController::class, 'archive'])->name('job-offers.archive');
        Route::post('/job-offers/{jobOffer}/unarchive', [JobOfferController::class, 'unarchive'])->name('job-offers.unarchive');
        Route::post('/job-offers/{jobOffer}/apply', [ApplicationController::class, 'store'])->name('applications.store');
        Route::patch('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    });

    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}/download-cv', [ApplicationController::class, 'downloadCv'])->name('applications.download-cv');
    Route::get('/applications/{application}/download-cover-letter', [ApplicationController::class, 'downloadCoverLetter'])->name('applications.download-cover-letter');

    // Saved Jobs
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('saved-jobs.index');
    Route::post('/job-offers/{jobOffer}/toggle-save', [SavedJobController::class, 'toggle'])->name('saved-jobs.toggle');

    // Reports
    Route::get('/report/{user}', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/report/{user}', [ReportController::class, 'store'])->name('reports.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Candidate Search (Company only)
    Route::get('/candidates', [CandidateSearchController::class, 'index'])->name('candidates.index');
    Route::get('/candidates/{candidate}', [CandidateSearchController::class, 'show'])->name('candidates.show');
    Route::get('/candidates/{candidate}/download-cv', [CandidateSearchController::class, 'downloadCv'])->name('applications.download-cv-candidate');

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Moderation
        Route::get('/offers', [AdminController::class, 'moderateOffers'])->name('offers.moderation');
        Route::get('/offers/{jobOffer}', [AdminController::class, 'showOffer'])->name('offers.show');
        Route::patch('/offers/{jobOffer}/status', [AdminController::class, 'updateOfferStatus'])->name('offers.status');
        
        Route::get('/users', [AdminController::class, 'moderateProfiles'])->name('users.moderation');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');

        Route::get('/reports', [AdminController::class, 'viewReports'])->name('reports.index');
        Route::get('/reports/{report}', [AdminController::class, 'showReport'])->name('reports.show');
        Route::patch('/reports/{report}/treated', [AdminController::class, 'toggleReportTreated'])->name('reports.treated');
        
        // Stats
        Route::get('/stats/export', [AdminController::class, 'exportStats'])->name('stats.export');
    });
    Route::get('/cv/{user}', [ApplicationController::class, 'showCv'])->name('cv.show');
});
Route::get('/job-offers/{jobOffer}', [JobOfferController::class, 'show'])->name('job-offers.show');


require __DIR__.'/auth.php';
