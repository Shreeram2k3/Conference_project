<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\CommitteeMemberController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest View (Home Page)
Route::get('/', function () {
    return view('welcome');
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');

    // Events Browsing
    Route::get('/events', [EventController::class, 'index'])->name('events.index'); 
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show'); 

    // Event Registration (Floating Form Submission)
    Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('events.register');
    
    Route::get('/download/sample/{id}', [EventController::class, 'downloadSample'])
    ->name('download.sample');
});

// Admin Routes (with AdminMiddleware)
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
   
    Route::get('/admin/registration', [EventController::class, 'registration'])->name('admin.registration');
    // Route::put('/registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/registrations/{id}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
    Route::put('/admin/registrations/{id}', [RegistrationController::class, 'update'])->name('admin.registrations.update');
    Route::get('/admin/export', [RegistrationController::class, 'showExportedRegistrations'])->name('admin.export');
    Route::get('/admin/export/pdf', [RegistrationController::class, 'export'])->name('admin.export.pdf');

    
    

    


    



    // Event Management
    Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events.index');
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::delete('/admin/registration/{registration}', [RegistrationController::class, 'destroy'])->name('admin.registrations.destroy');
    Route::delete('/committee-members/{id}', [CommitteeMemberController::class, 'destroy'])
    ->name('committee-members.destroy');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    Route::post('/events/{event}/committee-members', [EventController::class, 'storeCommitteeMember'])->name('committee-members.store');
    Route::put('/events/{event}/committee-members/{member}', [EventController::class, 'updateCommitteeMember'])->name('committee-members.update');
    Route::delete('/events/{event}/committee-members/{member}', [EventController::class, 'destroyCommitteeMember'])->name('committee-members.destroy');



    

});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->middleware('auth')->name('logout');

// Timelines
Route::post('/events/{event}/timelines', [TimelineController::class, 'store'])->name('timelines.store');
Route::put('/timelines/{timeline}', [TimelineController::class, 'update'])->name('timelines.update');
Route::delete('/timelines/{timeline}', [TimelineController::class, 'destroy'])->name('timelines.destroy');





// Auth Routes
require __DIR__.'/auth.php';
