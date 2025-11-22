<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Models\User;

// Rutas de autenticación
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Ruta de prueba directa
Route::get('/test-users', function() {
    try {
        $users = User::with(['role', 'restaurant'])
            ->withCount(['auditedRestaurants as audited_restaurants_count'])
            ->latest()
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Ruta principal
Route::get('/', function () {
    return view('landing');
});


// Grupo de rutas para la gestión de restaurantes con prefijo /admin
// Nota: Se ha eliminado el middleware de autenticación para permitir acceso sin login
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

// Rutas de Auditorías
// Rutas de Auditorías
Route::prefix('audits')->name('audits.')->group(function () {
    Route::get('/', [AuditController::class, 'index'])->name('index');
    Route::get('/select-restaurant', [AuditController::class, 'selectRestaurant'])->name('select-restaurant');
    Route::get('/restaurant/{restaurant}/create', [AuditController::class, 'create'])->name('create');
    Route::post('/restaurant/{restaurant}', [AuditController::class, 'store'])->name('store');
    Route::get('/{audit}', [AuditController::class, 'show'])->name('show');
    Route::get('/{audit}/edit', [AuditController::class, 'edit'])->name('edit');
    Route::put('/{audit}', [AuditController::class, 'update'])->name('update');
    Route::delete('/{audit}', [AuditController::class, 'destroy'])->name('destroy');
});

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





    // 👥 User Management
    // Ruta directa para listar usuarios
    Route::get('/users', function() {
        try {
            $users = \App\Models\User::with(['role', 'restaurant'])
                ->withCount(['auditedRestaurants as audited_restaurants_count'])
                ->latest()
                ->paginate(10);
                
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    })->name('users.index');

    // 🏗️ Mostrar formulario de creación
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    // 💾 Guardar nuevo usuario
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    // 🔍 Mostrar un usuario específico
    // IMPORTANTE: Esta ruta debe ir DESPUÉS de /users para evitar conflictos
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

    // ✏️ Mostrar formulario de edición
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    // ♻️ Actualizar usuario
    Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
        
    // ❌ Eliminar usuario
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    // Rutas protegidas
    Route::middleware('auth')->group(function () {
        // Ruta de admin
        Route::get('/dashboard', function() {
            if (auth()->user()->role !== 'admin') {
                abort(403);
            }
            return view('admin.dashboard');
        })->name('dashboard');

        // Ruta de auditor
        Route::get('/auditor/dashboard', function() {
            return view('auditor.dashboard');
        })->name('auditor.dashboard');
    });
}); // Cierre del grupo de rutas 'admin'
