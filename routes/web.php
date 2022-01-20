<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Calendar;
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

Route::get('/calendar', function () {
    return view('welcome');
})->name("calendar");
Route::get('/',[Calendar::class,'index'])->name("index");
Route::get('/authentication',[Calendar::class,'help'])->name("verifier");
Route::get('/newevent/{date}',function () { return view('new-event');});
Route::post('/create',[Calendar::class,'CreateTask'])->name('create');
Route::post('/delete',[Calendar::class,'drop'])->name('delete');
Route::post('/update',[Calendar::class,'update'])->name('update');