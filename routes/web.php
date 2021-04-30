<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserPreferencesController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::route('login'); //Inertia\Inertia::render('Welcome');
});

Route::get('/preferences', function () {
    return Inertia\Inertia::render('Welcome');
})->name('preferences');

Route::get('/prototypes/{page}', function ($page) {
    return Inertia\Inertia::render('Prototypes/'.$page);
});

// register
Route::middleware('guest')->get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::middleware('guest')->post('/register', [RegisteredUserController::class, 'store']);

// login
Route::middleware('guest')->get('/login', [AuthenticatedSessionController::class, 'index'])->name('login');
// Route::middleware('guest')->get('/login/{provider}', [AuthenticatedSessionController::class, 'create']);
// Route::middleware('guest')->get('/login/{provider}/callback', [AuthenticatedSessionController::class, 'store']);
Route::middleware('guest')->post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// User
Route::middleware('auth')->get('/preferences', UserPreferencesController::class, )->name('preferences');
