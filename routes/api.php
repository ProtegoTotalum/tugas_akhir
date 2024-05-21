<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;

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
Route::post('password/forget', [AuthController::class, 'sendResetLink']);
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('api.password.reset');

Route::get('email/verify/{id}', [EmailVerificationController::class, 'verify'])->name('verification.verify'); // Make sure to keep this as your route name
Route::get('email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');

//UserController
Route::get('getuser/{id}', 'App\Http\Controllers\UserController@show');
Route::put('updateuser/{id}', 'App\Http\Controllers\UserController@update');
Route::post('changepas/{id}', 'App\Http\Controllers\UserController@changePas');
Route::get('changerole/{id}', [App\Http\Controllers\UserController::class, 'changeRole']);
Route::get('deaktivasiakun/{id}', 'App\Http\Controllers\UserController@deaktivasiAkun');

//PenyakitController
Route::get('getpenyakit', 'App\Http\Controllers\PenyakitController@index');
Route::get('getpenyakit/{id}', 'App\Http\Controllers\PenyakitController@show');
Route::put('updatepenyakit/{id}', 'App\Http\Controllers\PenyakitController@update');

//DiagnosaController
Route::post('storePertanyaan/{id}', 'App\Http\Controllers\DiagnosaController@storePertanyaan');
Route::get('diagnosa/{id}', 'App\Http\Controllers\DiagnosaController@diagnosa');


Route::get('searchobat/{search}', [App\Http\Controllers\ObatController::class, 'searchObat']);
Route::get('searchbahanmakanan/{search}', [App\Http\Controllers\BahanMakananController::class, 'searchBahanMakanan']);


//All
Route::apiResource('/geolokasi', App\Http\Controllers\GeolokasiController::class);
Route::apiResource('/obat', App\Http\Controllers\ObatController::class);
Route::apiResource('/user', App\Http\Controllers\UserController::class);
Route::apiResource('/penyakit', App\Http\Controllers\PenyakitController::class);
Route::apiResource('/gejala', App\Http\Controllers\GejalaController::class);
Route::apiResource('/jadwalmakan', App\Http\Controllers\JadwalMakanController::class);
Route::apiResource('/bahanmakanan', App\Http\Controllers\BahanMakananController::class);
