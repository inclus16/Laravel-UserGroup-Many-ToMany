<?php

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

Route::get('/enter','AdminController@enterGet')->name('enter.get');
Route::post('/enter/post','AdminController@enterPost')->name('enter.post');
Route::group(['middleware'=>'auth'],function (){
   Route::get('/','AdminController@index')->name('index');
});
