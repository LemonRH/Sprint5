<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Middleware\PassportAuthMiddleware;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController; // Asegúrate de importar GameController si aún no lo has hecho

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas de autenticación
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(PassportAuthMiddleware::class);
Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(PassportAuthMiddleware::class);

// Rutas protegidas que requieren autenticación con Passport y middleware de roles
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/players', [PlayerController::class, 'store']); // Crear jugador
    Route::put('/players/{id}', [PlayerController::class, 'update']); // Modificar el nombre del jugador
    Route::delete('/players/{id}/games', [PlayerController::class, 'destroyGames']); // Eliminar las tiradas del jugador
    Route::get('/players', [PlayerController::class, 'index']); // Listado de todos los jugadores con su porcentaje medio de éxitos
    Route::get('/players/{id}/games', [GameController::class, 'index']); // Listado de jugadas por un jugador
    Route::post('/players/{id}/games', [GameController::class, 'store']); // Ruta para tirar dados de un jugador
    Route::get('/players/ranking', [PlayerController::class, 'ranking']); // Ranking medio de todos los jugadores
    Route::get('/players/ranking/loser', [PlayerController::class, 'getLoser']); // Jugador con peor porcentaje de éxito
    Route::get('/players/ranking/winner', [PlayerController::class, 'getWinner']); // Jugador con mejor porcentaje de éxito
});

// Ruta protegida que requiere autenticación con Passport y middleware de usuario
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Ruta para la emisión de tokens de acceso con Passport
Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
