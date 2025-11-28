<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar la revisión de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = [
            [
                'name' => 'Administrador',
                'description' => 'Administrador del sistema con acceso completo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Auditor',
                'description' => 'Usuario con permisos para auditar restaurantes',
                'created_at' => now(),
                'updated_at' => now()
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
