<?php

use App\Http\Controllers\APIs\ReferCasesController;
use App\Http\Controllers\LinkUserController;
use App\Models\Ability;
use App\Models\Admission;
use App\Models\Center;
use App\Models\Note;
use App\Models\Patient;
use App\Models\ReferCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/link-user', LinkUserController::class);

Route::post('/refer-cases', [ReferCasesController::class, 'store'])
     ->middleware('auth:sanctum');


// dump data
Route::middleware('dumpGuard')->group(function () {
    // ability role user
    Route::get('users', function () {
        return User::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });
    Route::get('abilities', function () {
        return Ability::where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });
    Route::get('roles', function () {
        return Role::where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });
    Route::get('ability-role', function () {
        return Role::with('abilities')
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });
    Route::get('role-user', function () {
        return User::withTrashed()
                    ->with('roles')
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // center
    Route::get('centers', function () {
        return Center::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // patient
    Route::get('patients', function () {
        return Patient::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // admission
    Route::get('admissions', function () {
        return Admission::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // note
    Route::get('notes', function () {
        return Note::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // referCase
    Route::get('refer-cases', function () {
        return ReferCase::withTrashed()
                    ->where('id', '>=', request()->startId)
                    ->limit(request()->limit)
                    ->orderBy('id')
                    ->get();
    });

    // uploade files
    Route::get('uploaded-files', function () {
        return Storage::allFiles('uploads');
    });

    // download files
    Route::get('download-file/{path}', function ($path) {
        return Storage::response('uploads/'.$path);
    });
});
