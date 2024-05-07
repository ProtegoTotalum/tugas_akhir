<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::post('logout', 'Api\AuthController@logout');

Route::get('email/verify/{id}', [EmailVerificationController::class, 'verify'])->name('verification.verify'); // Make sure to keep this as your route name
Route::get('email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');

Route::get('searchobat/{search}', [App\Http\Controllers\ObatController::class, 'searchObat']);

Route::apiResource('/geolokasi', App\Http\Controllers\GeolokasiController::class);
Route::apiResource('/obat', App\Http\Controllers\ObatController::class);
Route::apiResource('/penyakit', App\Http\Controllers\PenyakitController::class);
