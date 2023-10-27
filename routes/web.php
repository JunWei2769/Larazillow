<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index']);
Route::get('/hello', [IndexController::class, 'show'])
    ->middleware('auth');       // will redirect the unauthorized user to the login page automatically once they click on the specific link

Route::resource('listing', ListingController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])        // only these methods are applied with middleware('auth')
    ->middleware('auth');       // will redirect the unauthorized user to the login page automatically once they click on the specific link

Route::resource('listing', ListingController::class)
    ->except(['create', 'store', 'edit', 'update', 'destroy']);     // all methods are not applied with middleware('auth') except these methods stated

Route::get('login', [AuthController::class, 'create'])
    ->name('login');
Route::post('login', [AuthController::class, 'store'])
    ->name('login.store');
Route::delete('logout', [AuthController::class, 'destroy'])
    ->name('logout');

Route::resource('user-account', UserAccountController::class)
    ->only(['create', 'store']);