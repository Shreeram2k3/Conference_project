<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OrganizerMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([AdminMiddleware::class])->group(function () {
    // Routes only accessible by Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::get('/admin/events', [AdminController::class, 'events']);
    Route::get('/admin/registrations', [AdminController::class, 'registrations']);
});

Route::middleware([OrganizerMiddleware::class])->group(function () {
    // Routes only accessible by Organizer
    Route::get('/organizer/dashboard', [OrganizerController::class, 'index']);
    Route::get('/organizer/events', [OrganizerController::class, 'events']);
    Route::get('/organizer/registrations', [OrganizerController::class, 'registrations']);
});

require __DIR__.'/auth.php';
