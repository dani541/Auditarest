<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = [
            [
                'name' => 'Restaurante Central',
                'address' => 'Calle Principal 123',
                'city' => 'Ciudad de México',
                'contact_phone' => '555-123-4567',
                'contact_email' => 'info@restaurantecentral.com',
            ],
            [
                'name' => 'La Cocina de María',
                'address' => 'Avenida Juárez 456',
                'city' => 'Guadalajara',
                'contact_phone' => '333-987-6543',
                'contact_email' => 'contacto@lacocinamaria.com',
            ],
            [
                'name' => 'El Rincón del Sabor',
                'address' => 'Paseo de la Reforma 789',
                'city' => 'Monterrey',
                'contact_phone' => '818-555-1234',
                'contact_email' => 'info@elrincondelsabor.com',
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::firstOrCreate(
                ['name' => $restaurant['name']],
                $restaurant
            );
        }

        $this->command->info('¡Restaurantes creados exitosamente!');
    }
}