<?php

use App\Http\Controllers\AdmissionsController;
use App\Http\Controllers\APIs\Front\PatientAPIController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ReferCasesController;
use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::route('login'); //Inertia\Inertia::render('Welcome');
});

Route::get('/prototypes/{page}', function ($page) {
    return Inertia\Inertia::render('Prototypes/'.$page);
});

// Page
Route::get('/terms-and-policies', [PagesController::class, 'terms'])->name('terms-and-policies');

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
Route::middleware('auth')->get('/home', [PagesController::class, 'home'])->name('home');
Route::middleware('auth')->get('/users', [PagesController::class, 'users']);

// refer case
Route::middleware('auth')->get('/refer-cases', [ReferCasesController::class, 'index'])->name('refer-cases');
Route::middleware('auth')->post('/refer-cases', [ReferCasesController::class, 'store']);
Route::middleware('auth')->post('/refer-cases/{note}', [ReferCasesController::class, 'update']);
Route::middleware('auth')->delete('/refer-cases/{case}', [ReferCasesController::class, 'destroy']);

// form
Route::middleware('auth')->get('/forms/{note:slug}/edit', [NotesController::class, 'edit']);
Route::middleware('auth')->patch('/forms/{note}', [NotesController::class, 'update']);

// Upload
Route::middleware('auth')->post('/uploads', [UploadsController::class, 'store']);
Route::middleware('auth')->get('/uploads/{path}', [UploadsController::class, 'show']);

//admission case
Route::middleware('auth')->post('/admissions', [AdmissionsController::class, 'store']);

// front api
Route::middleware('auth')->post('/front-api/patient-rencently-admission', [PatientAPIController::class, 'recentlyAdmission']);
Route::middleware('auth')->post('/front-api/patient', [PatientAPIController::class, 'patient']);

// report
Route::middleware('auth')->get('/reports/{note:slug}', [NotesController::class, 'show']);
