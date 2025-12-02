<?php

namespace Database\Seeders;

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
        // Seed roles and users
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Only seed test data in local environment
        if (app()->environment('local')) {
            $this->call([
                TestDataSeeder::class,
                AuditTypeSeeders::class,
            ]);
        }
    }
}