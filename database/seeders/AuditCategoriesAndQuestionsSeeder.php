<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditCategoriesAndQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        'HIGIENE' => [
            'Lavado de manos',
            'Uso de guantes',
            'Limpieza de superficies',
            // ... más preguntas
        ],
        'TEMPERATURA' => [
            'Temperatura de nevera',
            'Temperatura de congelador',
            // ... más preguntas
        ],
        // ... más categorías
    ];

    foreach ($categories as $categoryName => $questions) {
        $category = \App\Models\AuditCategory::create([
            'name' => $categoryName,
            'order' => \App\Models\AuditCategory::count() + 1
        ]);

        foreach ($questions as $index => $question) {
            \App\Models\AuditQuestion::create([
                'category_id' => $category->id,
                'question' => $question,
                'order' => $index + 1
            ]);
        }
    }
}
}
