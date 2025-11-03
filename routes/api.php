<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;

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

// Rutas para Restaurantes
Route::prefix('restaurants')->group(function () {
    // Obtener todos los restaurantes
    Route::get('/', [RestaurantController::class, 'index']);
    
    // Crear un nuevo restaurante
    Route::post('/', [RestaurantController::class, 'store']);
    
    // Obtener un restaurante específico
    Route::get('/{id}', [RestaurantController::class, 'show']);
    
    // Actualizar un restaurante
    Route::put('/{id}', [RestaurantController::class, 'update']);
    
    // Eliminar un restaurante
    Route::delete('/{id}', [RestaurantController::class, 'destroy']);
    
    // Obtener auditorías de un restaurante
    Route::get('/{id}/audits', [RestaurantController::class, 'getAudits']);
});
