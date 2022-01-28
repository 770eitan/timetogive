<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CharityTickerController;
use App\Http\Controllers\Auth\MyConfirmPasswordController;

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

Route::get('/confirm/{charity_code}', [MyConfirmPasswordController::class, "showConfirmForm"])->name('showConfirmForm')->middleware('auth');
Route::post('/confirm/{charity_code}', [MyConfirmPasswordController::class, "confirm"])->name('stopCharity')->middleware('auth');

Route::get('/check-timer/{charity_code}',[CharityTickerController::class,'checkTimerExpire'])->middleware('auth');

// Route::get('/mailable', function () {
//     $user = App\Models\User::whereHas('charity_ticker')->find(15);

//     //return new App\Mail\UserPassword($user,'sasasass');

//     //Mail::to('ranjeetsingh.bnl@gmail.com')->send(new App\Mail\EmailVerify($user));

//     App\Events\VerifyEmailEvent::dispatch($user);
// });
Auth::routes([
    'login'    => false,
    'logout'   => false,
    'register' => false,
    'reset'    => false,   // for resetting passwords
    'confirm'  => true,  // for additional password confirmations
    'verify'   => false,  // for email verification
]);


Route::get('/ba441206-52aa-4dfc-9ab3-901b1ca741ce', function(){
    return response()->noContent();
});