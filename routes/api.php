<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//Апи роуты. по стандарту - префикс api. далее идёт 2 группы роутов с добавлением префикса
//{id} - передаём id сущности в параметрах роута. Остальное берём из Request в контроллерах
Route::group(['prefix'=>'user'],function (){
        Route::get('/create','UsersController@userCreate')->name('user.create');
        Route::get('/list','UsersController@userList')->name('user.list');
        Route::get('/{id}/edit','UsersController@userEdit')->name('user.edit');
        Route::get('/{id}/delete','UsersController@userDelete')->name('user.delete');
        Route::get('/{id}/join','UsersController@userJoin')->name('user.join');
        Route::get('/{id}/list','UsersController@userJoinedList')->name('user.group.list');
});

Route::group(['prefix'=>'group'],function (){
    Route::get('/create','GroupController@groupCreate')->name('group.create');
    Route::get('/list','GroupController@groupList')->name('group.list');
    Route::get('/{id}/edit','GroupController@groupEdit')->name('group.edit');
    Route::get('/{id}/delete','GroupController@groupDelete')->name('group.delete');
    Route::get('/{id}/list','GroupController@groupUsersList')->name('group.users.list');
    Route::get('/{id}/exclude','GroupController@groupUserExclude')->name('group.user.exclude');
});

