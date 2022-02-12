<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/coming', [App\Http\Controllers\ComingController::class, 'index']);
Route::get('/providers', [App\Http\Controllers\ProviderController::class, 'index']);
Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index']);
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);

