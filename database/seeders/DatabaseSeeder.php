<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call([
            RoleSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // ID del rol administrador
        ]);

        // Create an auditor user if it doesn't exist
        $auditor = User::firstOrCreate(
            ['email' => 'auditor@example.com'],
            [
                'name' => 'Auditor Principal',
                'password' => bcrypt('password'),
                'role_id' => 2, // ID del rol auditor
            ]
        );

        // Set role_id directly since we're not using Spatie's roles
        $auditor->role_id = 2; // 2 should be the ID of the auditor role
        $auditor->save();

        // Seed other data
        $this->call([
            // Add other seeders if needed
            AuditCategoriesAndQuestionsSeeder::class,
            RestaurantSeeder::class, // Make sure you have this seeder
            AuditSeeder::class,      // Our new seeder
        ]);
    }
}
