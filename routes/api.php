<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Received HG
Route::get('/{event:slug}/delegate/{uuid}', [App\Http\Controllers\Api\ReceivedHGController::class, 'show']);
Route::post('/{event:slug}/delegate/hg/{uuid}', [App\Http\Controllers\Api\ReceivedHGController::class, 'store']);
Route::post('/{event:slug}/login', [App\Http\Controllers\Api\LoginController::class, 'index']);
Route::get('/{event:slug}/registrations', [App\Http\Controllers\Api\RegistrationController::class, 'index']);
