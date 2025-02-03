<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OrganizerMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Guest View (Home Page)
Route::get('/', function () {
    return view('welcome');
});

// Authenticated User Routes (Common for Users, Admin, and Organizers)
Route::middleware(['auth'])->group(function () {
    // User Dashboard (common for authenticated users)
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    
    // Events Routes (Browsing and registering for events)
    Route::get('/events', [EventController::class, 'index'])->name('events.index'); // Browse events
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show'); // View individual event
    Route::post('/events/{event}/register', [UserController::class, 'register'])->name('events.register'); // Register for an event
});

// Admin Routes (Accessible only by Admin)
Route::middleware([AdminMiddleware::class])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Events Management for Admin
    Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events.index'); // View all events
    Route::get('/admin/events/create', [EventController::class, 'create'])->name('admin.events.create'); // Create event form
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store'); // Store new event
    Route::get('/admin/events/{event}', [AdminController::class, 'showEvent'])->name('admin.events.show'); // View event details
    Route::delete('/admin/events/{event}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy'); // Delete event
    Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit'); // Edit event form
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update'); // Update event details
});

// Organizer Routes (Accessible only by Organizer)
Route::middleware([OrganizerMiddleware::class])->group(function () {
    // Organizer Dashboard
    Route::get('/organizer/dashboard', [OrganizerController::class, 'index'])->name('organizer.dashboard');
    
    // Events Management for Organizer
    Route::get('/organizer/events/create', [EventController::class, 'create'])->name('organizer.events.create'); // Create event form
    Route::post('/organizer/events', [EventController::class, 'store'])->name('organizer.events.store'); // Store new event
    Route::get('/organizer/events/{event}/registrations', [OrganizerController::class, 'showRegistrations'])->name('organizer.events.registrations'); // View event registrations
    Route::get('/organizer/events/{event}/edit', [EventController::class, 'edit'])->name('organizer.events.edit'); // Edit event form
    Route::put('/organizer/events/{event}', [EventController::class, 'update'])->name('organizer.events.update'); // Update event details
});

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->middleware('auth')->name('logout');

// Catch-all Route for Authentication (if necessary)
require __DIR__.'/auth.php';
