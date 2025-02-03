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
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// this is the home page(guest view)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::middleware([AdminMiddleware::class])->group(function () {
//     // Routes only accessible by Admin
//     Route::get('/admin/dashboard', [AdminController::class, 'index']);
//     Route::get('/admin/events', [AdminController::class, 'events']);
//     Route::get('/admin/registrations', [AdminController::class, 'registrations']);
// });

// Route::middleware([OrganizerMiddleware::class])->group(function () {
//     // Routes only accessible by Organizer
//     Route::get('/organizer/dashboard', [OrganizerController::class, 'index']);
//     Route::get('/organizer/events', [OrganizerController::class, 'events']);
//     Route::get('/organizer/registrations', [OrganizerController::class, 'registrations']);
// });

    // User Routes (Authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');  // **User dashboard**
    Route::get('/events', [EventController::class, 'index'])->name('events.index');  // **Browse events**
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');  // **View event**
    Route::post('/events/{event}/register', [UserController::class, 'register'])->name('events.register');  // **Register for event**
});

// Admin Routes (Accessible only by Admin)
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');  // **Admin dashboard**
    Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events.index');  // **View all events**
    Route::get('/admin/events/create', [EventController::class, 'create'])->name('admin.events.create');  // **Create event**
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');  // **Store new event**
    Route::get('/admin/events/{event}', [AdminController::class, 'showEvent'])->name('admin.events.show');  // **View specific event**
    Route::delete('/admin/events/{event}', [AdminController::class, 'destroyEvent'])->name('admin.events.destroy');  // **Delete event**
    Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');  // **Edit event**
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update');  // **Update event**
});

// Organizer Routes (Accessible only by Organizer)
Route::middleware([OrganizerMiddleware::class])->group(function () {
    Route::get('/organizer/dashboard', [OrganizerController::class, 'index'])->name('organizer.dashboard');  // **Organizer dashboard**
    Route::get('/organizer/events/create', [EventController::class, 'create'])->name('organizer.events.create');  // **Create event**
    Route::post('/organizer/events', [EventController::class, 'store'])->name('organizer.events.store');  // **Store event**
    Route::get('/organizer/events/{event}/registrations', [OrganizerController::class, 'showRegistrations'])->name('organizer.events.registrations');  // **View registrations**
    Route::get('/organizer/events/{event}/edit', [EventController::class, 'edit'])->name('organizer.events.edit');  // **Edit event**
    Route::put('/organizer/events/{event}', [EventController::class, 'update'])->name('organizer.events.update');  // **Update event**
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->middleware('auth')->name('logout');

Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');


require __DIR__.'/auth.php';
