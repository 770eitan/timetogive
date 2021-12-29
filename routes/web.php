<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CharityTickerController;
use Illuminate\Support\Facades\Mail;
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

Route::get('/', [HomeController::class, "index"])->name('home');
Route::post('/create-tick', [CharityTickerController::class, "store"])->name('createTick');
Route::get('/thankyou/{charity_code}', [HomeController::class, "thankyou"])->name('thankyou');
Route::get('/verify/{token}', [HomeController::class, "verify"])->name('verify');
Route::get('/charity/{charity_code}', [HomeController::class, "charityDetails"])->name('charity');
Route::get('/charity-search', [HomeController::class, "charitySearch"])->name('charitySearch');

Route::get('/mailable', function () {
  $user = App\Models\User::whereHas('charity_ticker')->find(15);

  //return new App\Mail\UserPassword($user,'sasasass');

  Mail::to('ranjeetsingh.bnl@gmail.com')->send(new App\Mail\EmailVerify($user));
});