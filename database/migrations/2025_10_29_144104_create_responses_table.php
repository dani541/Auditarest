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
        Schema::create('responses', function (Blueprint $table) {
 $table->id();
            $table->text('answer_value'); // Valor de la respuesta (ej: 'true', 'false', texto, valor)
            
            // FK: Auditoría a la que pertenece esta respuesta
            $table->foreignId('audit_id')
                  ->constrained('audits')
                  ->onDelete('cascade'); // Si se elimina la auditoría, se eliminan las respuestas

            // FK: Pregunta del formulario respondida
            $table->foreignId('form_id')
                  ->constrained('forms')
                  ->onDelete('restrict');
            
            $table->timestamps();

            // Evitar doble respuesta a la misma pregunta en la misma auditoría
            $table->unique(['audit_id', 'form_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
