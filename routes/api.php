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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Ruta para obtener tokens */
Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::get('books','BookController@getBooks');

/* Rutas de acceso */
Route::resource('roles','Acceso\RolController',['except' => ['create','show']]);
Route::get('roles-obtener','Acceso\RolController@obtener');

Route::resource('permisos','Acceso\PermisoController',['except' => ['create','show']]);
Route::get('padres','Acceso\PermisoController@padre');
Route::get('permisos-titulo','Acceso\PermisoController@titulo');

Route::get('usuario-menu','Acceso\UsuarioRolController@menu');
Route::get('usuario-rol','Acceso\UsuarioRolController@index');

Route::resource('usuarios','Acceso\UsuarioController',['except' => ['create','show']]);
Route::get('usuarios-roles/{id}','Acceso\UsuarioController@roles');
Route::post('usuarios-roles','Acceso\UsuarioController@updateRoles');
