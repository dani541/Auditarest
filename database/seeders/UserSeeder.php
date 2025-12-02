<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Administrador del sistema con acceso total']
        );

        $auditorRole = Role::firstOrCreate(
            ['name' => 'Auditor'],
            ['description' => 'Usuario que realiza auditorías']
        );

        $restauranteRole = Role::firstOrCreate(
            ['name' => 'Restaurante'],
            ['description' => 'Propietario o administrador de restaurante']
        );

        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@auditarest.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'), // Cambiar en producción
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Crear usuario auditor
        User::firstOrCreate(
            ['email' => 'auditor@auditarest.com'],
            [
                'name' => 'Auditor Ejemplo',
                'password' => Hash::make('password'), // Cambiar en producción
                'role_id' => $auditorRole->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Crear usuario restaurante
        User::firstOrCreate(
            ['email' => 'restaurante@auditarest.com'],
            [
                'name' => 'Restaurante Ejemplo',
                'password' => Hash::make('password'), // Cambiar en producción
                'role_id' => $restauranteRole->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
