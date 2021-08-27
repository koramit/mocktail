<?php

use App\Http\Controllers\APIs\ReferCasesController;
use App\Http\Controllers\LinkUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
