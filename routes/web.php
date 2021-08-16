<?php

//use Illuminate\Support\Facades\Route;

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
    return view('contenido/contenido');
});

Route::get('/secretarias', 'SecretariaController@index');
Route::post('/secretarias/registrar', 'SecretariaController@store');
Route::put('/secretarias/actualizar', 'SecretariaController@update');
Route::put('/secretarias/desactivar', 'SecretariaController@desactivar');
Route::put('/secretarias/activar', 'SecretariaController@activar');

Route::get('/usuario', 'UsuarioController@index');
Route::post('/usuario/registrar', 'UsuarioController@store');
Route::put('/usuario/actualizar', 'UsuarioController@update');