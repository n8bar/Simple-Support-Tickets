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


//Route::get('/', function () {
//    //return view('home');
//});
//Route::get('/', '\App\Http\Controllers\HomeController@__invoke');
//Route::get('/', [App\Http\Controllers\HomeController::class, '__invoke']);
Route::get('/', '\App\Http\Controllers\HomeController');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/adminer', '\App\Http\Controllers\AdminerController@index');
Route::get('/adminer', '\App\Http\Controllers\AdminerController@index');

