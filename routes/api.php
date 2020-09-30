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

/* Rutas de acceso */
Route::resource('roles','Acceso\RolController',['except' => ['create','show']]);
Route::get('roles-obtener','Acceso\RolController@obtener');

Route::resource('permisos','Acceso\PermisoController',['except' => ['create','show']]);
Route::get('padres','Acceso\PermisoController@padre');
Route::get('permisos-titulo','Acceso\PermisoController@titulo');

Route::get('usuario-menu','Acceso\UsuarioRolController@menu');
Route::get('usuario-rol','Acceso\UsuarioRolController@index');

Route::resource('usuarios','Acceso\UsuarioController',['except' => ['create']]);
Route::get('usuario-buscar/{criterio}','Acceso\UsuarioController@buscar');
Route::get('usuarios-roles/{id}','Acceso\UsuarioController@roles');
Route::post('usuarios-roles','Acceso\UsuarioController@updateRoles');

/* Rutas de catalogos */
Route::resource('estado-servicio','Catalogos\EstadoServicioController',['except' => ['create','show']]);
Route::resource('tipo-pago','Catalogos\TipoPagoController',['except' => ['create','show']]);
Route::get('tipo-pago-obtener','Catalogos\TipoPagoController@obtener');

Route::resource('sectores','Catalogos\SectorController',['except' => ['create','show']]);
Route::get('sectores-obtener','Catalogos\SectorController@sectores');

Route::resource('comite','Catalogos\ComiteController');
Route::get('comite-obtener','Catalogos\ComiteController@obtener');

Route::get('meses','Catalogos\MesController@index');
Route::get('anios','Catalogos\AnioController@index');

Route::resource('autorizaciones','Catalogos\AutorizacionController',['except' => ['create','show','destroy']]);
Route::get('autorizaciones-habilitar/{id}','Catalogos\AutorizacionController@habilitar');

/* Rutas de solicitud */
Route::resource('solicitudes','Solicitud\SolicitudController',['except' => ['create','show','destroy']]); 
Route::post('solicitudes-aprobar','Solicitud\SolicitudController@aprobar');
Route::post('solicitudes-rechazar','Solicitud\SolicitudController@rechazar');

/* Rutas de servicios */
Route::resource('servicios','Servicio\ServicioController',['except' => ['create','show','destroy']]); 
Route::get('servicios-detalle/{id}','Servicio\ServicioController@detalle');
Route::get('servicios-usuario/{id}','Servicio\ServicioController@serviciosUsuario');
Route::get('servicio-titular/{id}','Servicio\ServicioController@obtenerUsuarioTitular');
Route::get('servicio-titular-detalle/{id}','Servicio\ServicioController@obtenerUsuarioTitularDetalle');

/* Rutas de pagos */ ;
Route::post('pagos','Pago\PagoController@pagar');
Route::get('pagos-detalle','Pago\PagoController@detalle');

/* Rutas de mi servicio */ 
Route::get('mis-servicios','MiServicio\MiServicioController@index');
Route::get('mis-servicios-detalle','MiServicio\MiServicioController@detalle');