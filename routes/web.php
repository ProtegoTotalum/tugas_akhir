<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Display the password reset form
// Route::get('password/reset', function () {
//     return view('auth.reset-password');
// })->name('password.reset');

// // Handle the password reset form submission
// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/reset-password-success', function () {
    return view('auth/password-reset-success');
});
