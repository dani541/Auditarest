<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('landing');
});

// Grupo de rutas para la gestión de restaurantes con prefijo /admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Ruta para crear usuario
    Route::get('/dashboard', function () {
        return view('admin.createUser');
    })->name('dashboard');

    // 📋 Mostrar lista de restaurantes
    Route::get('/restaurants', [RestaurantController::class, 'index'])
        ->name('restaurants.index');

    // 🏗️ Mostrar formulario de creación
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])
        ->name('restaurants.create');

    // 💾 Guardar nuevo restaurante
    Route::post('/restaurants', [RestaurantController::class, 'store'])
        ->name('restaurants.store');

    // 🔍 Mostrar un restaurante específico
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])
        ->name('restaurants.show');

    // ✏️ Mostrar formulario de edición
    Route::get('/restaurants/{id}/edit', [RestaurantController::class, 'edit'])
        ->name('restaurants.edit');

    // ♻️ Actualizar restaurante
    Route::match(['put', 'patch'], '/restaurants/{id}', [RestaurantController::class, 'update'])
        ->name('restaurants.update');
        
    // 📄 Generar PDF del restaurante
    Route::get('/restaurants/{id}/pdf', [RestaurantController::class, 'generatePdf'])
        ->name('restaurants.pdf');

    // ❌ Eliminar restaurante
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy'])
        ->name('restaurants.destroy');





    // 👥 Gestión de Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');






});

