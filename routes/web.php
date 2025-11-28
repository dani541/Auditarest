<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ReportController;

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
})->name('landing');

// Rutas de auditorías
Route::prefix('audits')->name('audits.')->group(function () {
    Route::get('/', [AuditController::class, 'index'])->name('index');
    Route::get('/create', [AuditController::class, 'create'])->name('create');
    Route::post('/store', [AuditController::class, 'store'])->name('store');
    Route::get('/{id}', [AuditController::class, 'show'])->name('show');
    Route::get('/{audit}/edit', [AuditController::class, 'edit'])->name('edit');
    Route::put('/{audit}', [AuditController::class, 'update'])->name('update');
    Route::delete('/{audit}', [AuditController::class, 'destroy'])->name('destroy');
    Route::get('/{audit}/pdf', [AuditController::class, 'generatePdf'])->name('pdf');
    Route::get('/{audit}/send-pdf', [AuditController::class, 'sendPdf'])->name('send-pdf');
    Route::get('/{audit}/email', [AuditController::class, 'showEmailForm'])->name('email.form');
    Route::post('/{audit}/send-email', [AuditController::class, 'sendEmail'])->name('email.send');
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

    // Guardar nuevo restaurante
    Route::post('/restaurants', [RestaurantController::class, 'store'])
        ->name('restaurants.store');

    // Mostrar un restaurante específico
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])
        ->name('restaurants.show');

    // Mostrar formulario de edición
    Route::get('/restaurants/{id}/edit', [RestaurantController::class, 'edit'])
        ->name('restaurants.edit');

    // Actualizar restaurante
    Route::match(['put', 'patch'], '/restaurants/{id}', [RestaurantController::class, 'update'])
        ->name('restaurants.update');
        
    // Generar PDF del restaurante
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

    Route::get('/auditor/index', [AuditController::class, 'indexAudi'])
        ->name('admin.auditor.index');
}); 

// Auditor routes
Route::prefix('auditor')->name('auditor.')->group(function () {
    Route::get('/index', [AuditController::class, 'indexAudi'])->name('index');
    Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
});

// Add a direct route for showing audit details
Route::get('audits/{audit}', [AuditController::class, 'show'])->name('audits.show');

// Add the export-pdf route inside the admin group
Route::prefix('admin')->name('admin.')->group(function () {
    // ... other admin routes ...
    
    // Updated route to match the expected URL structure
   
});

// Ruta para mostrar el detalle de la auditoría (ya existe en la línea 40)
Route::get('/audits/{id}', [AuditController::class, 'show'])->name('audits.show');

// Ruta para exportar a PDF (siguiendo el patrón existente)
Route::get('/audits/{audit}/export-pdf', [AuditController::class, 'exportPdf'])
    ->name('audits.export-pdf');



Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
     ->name('admin.users.toggle-status');