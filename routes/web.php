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


Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('/home/connections', [App\Http\Controllers\HomeController::class, 'getConnections'])->name('home.connections');
    Route::post('/home/connect', [App\Http\Controllers\HomeController::class, 'connect'])->name('home.connect');
    Route::post('/home/remove-request', [App\Http\Controllers\HomeController::class, 'removeRequest'])->name('home.remove_equest');
    Route::post('/home/accept-request', [App\Http\Controllers\HomeController::class, 'acceptRequest'])->name('home.accept_equest');
    Route::post('/home/remove-connection', [App\Http\Controllers\HomeController::class, 'removeConnection'])->name('home.remove_connection');
});


