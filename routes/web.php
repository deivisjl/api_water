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

Route::get('/', 'HomeController@index');

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');

/* Rutas para administrar tokens */
Route::get('/personal-clients', 'HomeController@personalToken')->name('personal-clients');
Route::get('/tokens-clients', 'HomeController@tokenClients')->name('tokens-clients');
Route::get('/authorized-clients', 'HomeController@authorizedClients')->name('authorized-clients');

Route::group(['middleware' => ['preventBackHistory']], function(){
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');	
});
