<?php

use Illuminate\Support\Facades\Auth;
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
Route::get('/', '\App\Http\Controllers\DashController');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\DashController::class, 'index'])->name('home');
Route::get('/home', '\App\Http\Controllers\DashController')->name('userdash');
Route::post('/home', [\App\Http\Controllers\DashController::class, 'store']);

//Route::get('/adminer', '\App\Http\Controllers\AdminerController@index');
Route::get('/adminer', '\App\Http\Controllers\AdminerController@index');

Route::get('/new-ticket', [\App\Http\Controllers\NewTicketController::class,'index'])->name('newTicket');
Route::post('/new-ticket', [\App\Http\Controllers\NewTicketController::class,'store']);

Route::get('/TicketDetail', [\App\Http\Controllers\TicketDetailController::class,'index'])->name('TicketDetail');
Route::post('/TicketDetail', [\App\Http\Controllers\TicketDetailController::class,'store']);

Route::get('/Reports', [\App\Http\Controllers\ReportsController::class,'index'])->name('Reports');
Route::post('/Reports', [\App\Http\Controllers\ReportsController::class,'data']);

Route::get('/getTechnicians/{s}', '\App\Http\Controllers\TicketDetailController@getTechnicians');
