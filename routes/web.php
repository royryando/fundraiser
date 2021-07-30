<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DuitkuController;

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

Route::get('login', function() {
    return redirect()->route('auth.login');
})->name('login');
Route::get('/', [HomeController::class, 'home'])->name('index.home');
Route::get('browse-fundraisers', [HomeController::class, 'browse'])->name('index.browse');
Route::get('{code}', [HomeController::class, 'view'])->name('index.view');
Route::post('callback/payment', [DuitkuController::class, 'paymentCallback']);
Route::get('callback/return', [DuitkuController::class, 'returnCallback']);
Route::group(['middleware' => ['auth']], function() {
    Route::post('{code}/donate', [HomeController::class, 'donate'])->name('index.post-donate');
});
Route::group(['prefix' => 'account', 'middleware' => ['auth']], function() {
    Route::get('dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('create-fundraiser', [AccountController::class, 'createFundraiser'])->name('account.create-fundraiser');
    Route::get('my-campaigns', [AccountController::class, 'myCampaigns'])->name('account.my-campaigns');
    Route::get('my-donations', [AccountController::class, 'myDonations'])->name('account.my-donations');
});
Route::prefix('auth')->group(function() {
    Route::get('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('auth.post-login');
    Route::get('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('register', [AuthController::class, 'postRegister'])->name('auth.post-register');
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('verification', [AuthController::class, 'verification'])->name('auth.verification');
});
