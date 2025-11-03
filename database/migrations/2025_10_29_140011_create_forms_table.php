<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->text('question'); // La pregunta de la auditoría
            $table->string('type')->default('boolean'); // Tipo de respuesta: boolean, text, select
            
            // Clave foránea (FK) a la tabla 'categories'
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade'); // Si se elimina la categoría, se eliminan las preguntas

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
