<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//rutas jugadores
Route::post('/players', 'PlayerController@store'); //crear jugador
Route::put('/players/{id}', 'PlayerController@update'); //modificar el nombre del jugador
Route::delete('/players/{id}/games', 'PlayerController@destroyGames'); //eliminar las tiradas del jugador
Route::get('/players', 'PlayerController@index'); //listado de todos los jugadores con su porcentaje medio de éxitos
Route::get('/players/{id}/games', 'GameController@index'); //listado de jugadas por un jugador

//ruta tirada dados jugadores
Route::post('/players/{id}/games', 'GameController@store');

//rutas para obtener estadísticas de los jugadores
Route::get('/players/ranking', 'PlayerController@ranking'); //ranking medio de todos los jugadores
Route::get('/players/ranking/loser', 'PlayerController@getLoser'); //jugador con peor porcentaje de éxito
Route::get('/players/ranking/winner', 'PlayerController@getWinner'); //jugador con mejor porcentaje de éxito
