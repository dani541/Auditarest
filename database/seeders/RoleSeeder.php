<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'administrador',
                'description' => 'Acceso completo al sistema con todos los privilegios'
            ],
            [
                'name' => 'auditor',
                'description' => 'Acceso para realizar auditorías y generar informes'
            ],
            [
                'name' => 'usuario',
                'description' => 'Acceso básico al sistema con permisos limitados'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
