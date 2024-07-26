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
Route::post('adddokter', 'App\Http\Controllers\UserController@addDokter');
Route::get('changerole/{id}', [App\Http\Controllers\UserController::class, 'changeRole']);
Route::get('deaktivasiakun/{id}', 'App\Http\Controllers\UserController@deaktivasiAkun');
Route::get('getdokter/{provinsi_user}/{kota_user}', 'App\Http\Controllers\UserController@getDataDokter');
Route::post('updatefcmtoken', 'App\Http\Controllers\UserController@updateFcmToken');
Route::post('sendnotification', 'App\Http\Controllers\NotificationController@sendNotification');

//WilayahController
Route::get('getprovinsi', 'App\Http\Controllers\WilayahController@getProvinsi');
Route::get('getkabupatenkota/{id}', 'App\Http\Controllers\WilayahController@getKabupatenKota');

//PenyakitController
Route::get('getpenyakit', 'App\Http\Controllers\PenyakitController@index');
Route::get('getpenyakit/{id}', 'App\Http\Controllers\PenyakitController@show');
Route::put('updatepenyakit/{id}', 'App\Http\Controllers\PenyakitController@update');

//PertanyaanController
Route::post('storepertanyaan/{id}', 'App\Http\Controllers\PertanyaanController@storePertanyaan');
Route::get('showpertanyaan/{id_user}/{nomor_diagnosa}', 'App\Http\Controllers\PertanyaanController@showPertanyaan');

//DiagnosaController
Route::get('diagnosa/{id}/{id_user_dokter}', 'App\Http\Controllers\DiagnosaController@diagnosa');
Route::get('showdiagnosauser/{id_user}/{id_diagnosa}', 'App\Http\Controllers\DiagnosaController@showDiagnosaUser');
Route::get('showdiagnosauserall/{id}', 'App\Http\Controllers\DiagnosaController@showDiagnosaUserAll');
Route::get('lastdiagnosa/{id}', 'App\Http\Controllers\DiagnosaController@lastDiagnosa');


//ResultsDiagnosaController
Route::get('showresults/{id_diagnosa}', 'App\Http\Controllers\ResultsDiagnosaController@showResults');

//AnalisaDokterController
Route::get('getanalisabydokter/{id_dokter}', 'App\Http\Controllers\AnalisaDokterController@getAnalisaByDokter');
Route::get('updatereminderanalisa/{id_analisa}', 'App\Http\Controllers\AnalisaDokterController@updateReminderAnalisa');
Route::get('getanalisadetail/{id_analisa}', 'App\Http\Controllers\AnalisaDokterController@getAnalisaDetail');
Route::post('verifikasi/{id_analisa}', 'App\Http\Controllers\AnalisaDokterController@verifikasi');

//BahanMakananController
Route::get('getbahanmakananall', 'App\Http\Controllers\BahanMakananController@index');
Route::get('getbahanmakananbygolongan/{golongan_bahan_makanan}', 'App\Http\Controllers\BahanMakananController@showByGolongan');
Route::get('getbahanmakanandetail/{id}', 'App\Http\Controllers\BahanMakananController@show');
Route::get('deletebahanmakanan/{id}', 'App\Http\Controllers\BahanMakananController@deleteBahanMakanan');
Route::post('storebahanmakanan', 'App\Http\Controllers\BahanMakananController@store');
Route::put('updatebahanmakanan/{id}', 'App\Http\Controllers\BahanMakananController@update');

//JadwalMakanController
Route::post('storejadwalmakan', 'App\Http\Controllers\JadwalMakanController@storeJadwalMakan');
Route::get('getjadwalmakanuserall/{id_user}', 'App\Http\Controllers\JadwalMakanController@getDataJadwalMakanUser');
Route::get('getjadwalmakan/{id}', 'App\Http\Controllers\JadwalMakanController@show');
Route::get('updatestatusjadwal/{id_jadwal}/{new_status}', 'App\Http\Controllers\JadwalMakanController@updateStatusJadwal');
Route::put('updatejadwalmakan/{id}', 'App\Http\Controllers\JadwalMakanController@update');
Route::get('deletejadwalmakan/{id}', 'App\Http\Controllers\JadwalMakanController@delete');

//GejalaController
Route::get('getgejalaall', 'App\Http\Controllers\GejalaController@index');

//ObatController
Route::get('getobatall', 'App\Http\Controllers\ObatController@index');
Route::post('storeobat', 'App\Http\Controllers\ObatController@store');
Route::get('getobatdetail/{id}', 'App\Http\Controllers\ObatController@show');
Route::put('updateobat/{id}', 'App\Http\Controllers\ObatController@update');
Route::get('deleteobat/{id}', 'App\Http\Controllers\ObatController@destroy');

//RekomendasiObatController
Route::get('getrobatallbyanalisa/{id_analisa}', 'App\Http\Controllers\RekomendasiObatController@getDataRObat');
Route::get('getrobatuser/{id_diagnosa}', 'App\Http\Controllers\RekomendasiObatController@getDataRObatByDiagnosa');
Route::post('storerobat/{id_analisa}', 'App\Http\Controllers\RekomendasiObatController@storeRObat');
Route::get('getrobatdetail/{id}', 'App\Http\Controllers\RekomendasiObatController@show');
Route::put('updaterobat/{id}', 'App\Http\Controllers\RekomendasiObatController@update');
Route::get('deleterobat/{id}', 'App\Http\Controllers\RekomendasiObatController@deleteRObat');

//RekomendasiBahanMakananController
Route::get('getrbahanmakananall', 'App\Http\Controllers\RekomendasiBahanMakananController@index');
Route::get('getrbahanmakananbypenyakit/{id_penyakit}', 'App\Http\Controllers\RekomendasiBahanMakananController@getDataRByPenyakit');
Route::post('storerbahanmakanan', 'App\Http\Controllers\RekomendasiBahanMakananController@store');
Route::get('updatetiper/{id_rekomendasi_bahan_makanan}', 'App\Http\Controllers\RekomendasiBahanMakananController@updateTipeRekomendasi');
Route::get('deleterbahanmakanan/{id}', 'App\Http\Controllers\RekomendasiBahanMakananController@deleteRBahanMakanan');

//LaranganBahanMakananController
Route::get('getlbahanmakananall', 'App\Http\Controllers\LaranganBahanMakananController@index');
Route::get('getlbahanmakananbypenyakit/{id_penyakit}', 'App\Http\Controllers\LaranganBahanMakananController@getDataLByPenyakit');
Route::post('storelbahanmakanan', 'App\Http\Controllers\LaranganBahanMakananController@store');
Route::get('updatetipel/{id_larangan_bahan_makanan}', 'App\Http\Controllers\LaranganBahanMakananController@updateTipeLarangan');
Route::get('deletelbahanmakanan/{id}', 'App\Http\Controllers\LaranganBahanMakananController@deleteLBahanMakanan');

//GeolokasiController
Route::get('getgeolokasiall', 'App\Http\Controllers\GeolokasiController@index');
Route::post('storegeolokasi', 'App\Http\Controllers\GeolokasiController@store');
Route::get('getgeolokasidetail/{id}', 'App\Http\Controllers\GeolokasiController@show');
Route::put('updategeolokasi/{id}', 'App\Http\Controllers\GeolokasiController@update');
Route::get('deletegeolokasi/{id}', 'App\Http\Controllers\GeolokasiController@destroy');


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
