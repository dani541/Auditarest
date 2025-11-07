<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
    public function create()
    {
        $restaurantes = Restaurant::orderBy('name')->get();
        $roles = \App\Models\Role::all();
        return view('admin.createUser', compact('restaurantes', 'roles'));
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
     * Muestra la lista de usuarios
     */
    public function index()
    {
        $usuarios = User::with('restaurant')->orderBy('name')->get();
        return view('admin.users.index', compact('usuarios'));
    }
    
    /**
     * Muestra el formulario de creación de usuario
     */
}