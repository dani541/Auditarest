<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_items', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // INFRAESTRUCTURA, MAQUINARIA, SUPERFICIES_DE_TRABAJO, BUENAS_PRACTICAS
            $table->text('description'); // Descripción del ítem a verificar
            $table->string('type')->default('boolean'); // Tipo de respuesta (boolean, text, number)
            $table->integer('order')->default(0); // Orden de aparición
            $table->timestamps();
        });

        
        $items = [
            // INFRAESTRUCTURA
            ['category' => 'INFRAESTRUCTURA', 'description' => 'MESAS DE TRABAJO SIN GRIETAS NI DESCORCHONES', 'order' => 1],
            ['category' => 'INFRAESTRUCTURA', 'description' => 'TABLAS DE CORTE EN BUEN ESTADO', 'order' => 2],
   
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'LO ESPACIOS SE ENCUENTRAN ORDENADOS', 'order' => 1],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'SEPARACIÓN DE LOS PRODUCTOS', 'order' => 2],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'ELABORADO DE MATERIA PRIMA', 'order' => 3],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'DESCONGELACIÓN EN CONDICIONES HIGIÉNICAS', 'order' => 4],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'DESINFECCIÓN CORRECTA DE LOS PRODUCTOS', 'order' => 5],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'MANIPULADORES LIBRE DE JOYAS O POSTIZOS (UÑAS, EXTENSIONES)', 'order' => 6],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'SE SIGUEN LAS INSTRUCCIONES DE DESINFECCIÓN', 'order' => 7],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'LOS PRODUCTOS DE LIMPIEZA ESTÁN AISLADOS DE LOS ALIMENTOS', 'order' => 8],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'NEVERAS A LA TEMPERATURA CORRECTA (4°C)', 'order' => 9, 'type' => 'temperature'],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'ALIMENTOS A LA TEMPERATURA CORRECTA (4°C) DENTRO DE LAS NEVERAS', 'order' => 10, 'type' => 'temperature'],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'CONGELADORES A LA TEMPERATURA CORRECTA (-18°C)', 'order' => 11, 'type' => 'temperature'],
            ['category' => 'BUENAS_PRACTICAS', 'description' => 'ALIMENTOS A LA TEMPERATURA CORRECTA (-18°C) DENTRO DEL CONGELADOR', 'order' => 12, 'type' => 'temperature'],
        ];

        foreach ($items as $item) {
            DB::table('verification_items')->insert($item);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_items');
    }
};
