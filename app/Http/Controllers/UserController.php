<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
    public function index()
    {
        try {
            // Cargar usuarios con su rol, restaurante asignado y contar restaurantes auditados
            $users = User::with(['role', 'restaurant'])
                ->withCount(['auditedRestaurants as audited_restaurants_count'])
                ->latest()
                ->paginate(10);

            return view('admin.users.index', compact('users'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar la lista de usuarios: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $restaurantes = Restaurant::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        
        // Debug: Check if roles are being loaded
        if ($roles->isEmpty()) {
            // If no roles found, create default roles (only for development)
            $roles = collect([
                (object)['id' => 1, 'name' => 'Administrador'],
                (object)['id' => 2, 'name' => 'Auditor'],
                (object)['id' => 3, 'name' => 'Usuario']
            ]);
        }
        
        return view('admin.createUser', [
            'restaurantes' => $restaurantes,
            'roles' => $roles
        ]);
    }


    /**
     * Almacena un nuevo usuario en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id'
        ]);

        // Crear el usuario
        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'role_id' => $datos['role_id'],
            'restaurant_id' => $datos['restaurant_id'] ?? null
        ]);

        // Redirigir a la lista de usuarios con mensaje de éxito
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente');
    }


    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try {
            $user = User::with(['role', 'restaurant'])->findOrFail($id);
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = \App\Models\Role::all();
            $restaurants = \App\Models\Restaurant::orderBy('name')->get();
            return view('admin.users.edit', compact('user', 'roles', 'restaurants'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            // Prevent deleting the currently logged-in user
            if (auth()->id() == $id) {
                return redirect()->back()
                    ->with('error', 'No puedes eliminar tu propio usuario');
            }

            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario eliminado correctamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}