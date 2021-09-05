<?php

use App\Http\Controllers\AdmissionNotesController;
use App\Http\Controllers\AdmissionsController;
use App\Http\Controllers\APIs\Front\PatientAPIController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DischargeSummaryNotesController;
use App\Http\Controllers\ExportDOSController;
use App\Http\Controllers\ExportReportsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PrintDefaultAdmissionNote;
use App\Http\Controllers\PrintoutsController;
use App\Http\Controllers\ReferCaseNotesController;
use App\Http\Controllers\ReferCasesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransitCasesController;
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
Route::middleware('auth', 'remember')->get('/refer-cases', [ReferCasesController::class, 'index'])->name('refer-cases');
Route::middleware('auth')->post('/refer-cases', [ReferCasesController::class, 'store']);
Route::middleware('auth')->post('/refer-cases/{note}', [ReferCasesController::class, 'update']);
Route::middleware('auth')->delete('/refer-cases/{case}', [ReferCasesController::class, 'destroy']);
Route::middleware('auth')->get('/refer-cases/{case:slug}/notes', ReferCaseNotesController::class)->name('case-notes');

// form
Route::middleware('auth')->post('/notes', [NotesController::class, 'store']);
Route::middleware('auth')->get('/forms/{note:slug}/edit', [NotesController::class, 'edit'])->name('note.form');
Route::middleware('auth')->patch('/forms/{note}', [NotesController::class, 'update']);

// Upload
Route::middleware('auth')->post('/uploads', [UploadsController::class, 'store']);
Route::middleware('auth')->get('/uploads/{path}', [UploadsController::class, 'show']);

//admit case
Route::middleware('auth')->post('/admissions', [AdmissionsController::class, 'store']);

// admission note
Route::middleware('auth')->post('/admission-notes/{note}', AdmissionNotesController::class);

// discharge summary
Route::middleware('auth')->post('/discharge-summary-notes/{note}', DischargeSummaryNotesController::class);

// front api
Route::middleware('auth')->post('/front-api/patient-rencently-admission', [PatientAPIController::class, 'recentlyAdmission']);
Route::middleware('auth')->post('/front-api/patient', [PatientAPIController::class, 'patient']);

// Export report
Route::middleware('auth')->get('/reports/refer-cases', ExportReportsController::class);
Route::middleware('auth', 'can:export_hi_dos')->get('/reports/refer-cases-hi-dos', ExportDOSController::class);

// report
Route::middleware('auth')->get('/reports/{case:slug}', ReportsController::class);

// printout
Route::middleware('auth')->get('/printouts/{note:slug}', PrintoutsController::class);
Route::middleware('auth')->get('/print-default-admission-note/{case:slug}', PrintDefaultAdmissionNote::class);

// transit cases
Route::middleware('auth', 'can:transit')->get('/transit-cases', [TransitCasesController::class, 'index']);
Route::middleware('auth', 'can:transit')->post('/transit-cases/{case:slug}', [TransitCasesController::class, 'update']);

// soon
Route::middleware('auth')->get('/soon', [PagesController::class, 'soon']);
