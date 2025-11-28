<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\AuditHygiene;
use App\Models\AuditInfrastructure;
use App\Models\AuditMachinery;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener o crear un usuario auditor
        $auditor = User::firstOrCreate(
            ['email' => 'auditor@example.com'],
            [
                'name' => 'Auditor Ejemplo',
                'password' => bcrypt('password'),
                'role_id' => 2, // ID del rol de auditor
            ]
        );

        // Obtener todos los restaurantes
        $restaurants = Restaurant::all();
        
        if ($restaurants->isEmpty()) {
            $this->command->warn('No hay restaurantes disponibles. Por favor, ejecuta el RestaurantSeeder primero.');
            return;
        }

        // Datos de ejemplo para las auditorías
        $audits = [
            [
                'date' => now()->subDays(30),
                'auditor' => $auditor->name,
                'supervisor' => 'Supervisor Principal',
                'general_notes' => 'Auditoría de rutina mensual',
                'is_completed' => true,
                'total_score' => 85.5,
            ],
            [
                'date' => now()->subDays(15),
                'auditor' => $auditor->name,
                'supervisor' => 'Supervisor de Calidad',
                'general_notes' => 'Seguimiento de incidencias previas',
                'is_completed' => true,
                'total_score' => 92.0,
            ],
            [
                'date' => now()->subDays(7),
                'auditor' => $auditor->name,
                'supervisor' => 'Jefe de Área',
                'general_notes' => 'Auditoría sorpresa',
                'is_completed' => false,
                'total_score' => null,
            ],
        ];

        // Datos de ejemplo para infraestructura
        $infrastructureData = [
            'floor_condition' => true,
            'floor_notes' => 'Suelo en buen estado',
            'walls_condition' => true,
            'walls_notes' => 'Paredes limpias',
            'windows_condition' => false,
            'windows_notes' => 'Ventanas sucias',
            'doors_condition' => true,
            'doors_notes' => 'Puertas funcionando correctamente',
            'ceiling_condition' => true,
            'ceiling_notes' => 'Techo en buen estado',
            'lighting_condition' => true,
            'lighting_notes' => 'Buen nivel de iluminación',
            'countertops_condition' => false,
            'countertops_notes' => 'Encimera con grietas',
            'work_tables_condition' => true,
            'work_tables_notes' => 'Mesas limpias y ordenadas',
            'additional_notes' => 'Auditoría de infraestructura completada'
        ];

        // Datos de ejemplo para maquinaria
        $machineryData = [
            'stove_condition' => true,
            'stove_notes' => 'Hornillas funcionando correctamente',
            'oven_condition' => true,
            'oven_notes' => 'Horno calibrado',
            'fryer_condition' => false,
            'fryer_notes' => 'Freidora necesita mantenimiento',
            'refrigerator_condition' => true,
            'refrigerator_notes' => 'Temperatura óptima',
            'freezer_condition' => true,
            'freezer_notes' => 'Funcionando correctamente',
            'microwave_condition' => true,
            'microwave_notes' => 'Funcionando correctamente',
            'dishwasher_condition' => false,
            'dishwasher_notes' => 'Requiere mantenimiento',
            'maintenance_up_to_date' => true,
            'last_maintenance_date' => now()->subMonth(),
            'maintenance_notes' => 'Último mantenimiento realizado el mes pasado'
            // Let the model calculate these:
            // 'total_score' => 5,
            // 'percentage' => 71.43
        ];

        // Datos de ejemplo para higiene
        $hygieneData = [
            'uniforms_condition' => true,
            'uniforms_notes' => 'Personal con uniforme limpio',
            'hand_washing_condition' => true,
            'hand_washing_notes' => 'Lavado de manos adecuado',
            'food_handling_condition' => false,
            'food_handling_notes' => 'Se observó manipulación inadecuada de alimentos',
            'cleaning_supplies_condition' => true,
            'cleaning_supplies_notes' => 'Productos de limpieza bien almacenados',
        ];

        // Crear las auditorías
        foreach ($audits as $auditData) {
            // Seleccionar un restaurante aleatorio
            $restaurant = $restaurants->random();
            
            // Crear la auditoría
            $audit = Audit::create([
                'restaurant_id' => $restaurant->id,
                'auditor' => $auditData['auditor'],
                'date' => $auditData['date'],
                'supervisor' => $auditData['supervisor'],
                'general_notes' => $auditData['general_notes'],
                'is_completed' => $auditData['is_completed'],
                'total_score' => $auditData['total_score'],
            ]);

            // Crear registros relacionados
            $audit->infrastructure()->create($infrastructureData);
            $audit->machinery()->create($machineryData);
            $audit->hygiene()->create($hygieneData);

            $this->command->info("Auditoría creada para {$restaurant->name} - {$auditData['date']->format('Y-m-d')}");
        }
    }
}