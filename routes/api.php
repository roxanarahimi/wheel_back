<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('prize', App\Http\Controllers\DailyPrizeController::class);
//GET           /prize                      index   users.index
//POST          /prize                      store   users.store
//GET           /prize/{prize}               show    users.show
//PUT|PATCH     /prize/{user}               update  users.update
//DELETE        /prize/{user}               destroy users.destroy
Route::controller(App\Http\Controllers\DailyPrizeController::class)->group(function () {
    Route::get('/index/prize', 'indexx');
    Route::get('/active/prize/{dailyPrize}', 'activeToggle');
    Route::get('/trys', 'trys');
    Route::get('/winners', 'winners');
});
Route::controller(App\Http\Controllers\UserController::class)->group(function () {
    Route::post('/play', 'play');
    Route::get('/test', 'test');
    Route::post('/register', 'register');
    Route::post('/otp/mobile', 'oneTimePassword');
    Route::post('/mobile/verify', 'verify');
});
