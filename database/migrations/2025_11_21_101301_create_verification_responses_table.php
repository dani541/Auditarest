<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_responses', function (Blueprint $table) {
            $table->id();
            
            // Relación con la auditoría
            $table->foreignId('audit_id')
                  ->constrained('audits')
                  ->onDelete('cascade');
            
            // Relación con el ítem de verificación
            $table->foreignId('verification_item_id')
                  ->constrained('verification_items')
                  ->onDelete('cascade');
            
            // Valores de respuesta
            $table->enum('status', ['C', 'IC', 'NA'])->nullable(); // C = Cumple, IC = Incumple, NA = No aplica
            $table->text('corrective_measure')->nullable(); // Medida correctora o comentario
            $table->decimal('temperature', 8, 2)->nullable(); // Para ítems que requieren temperatura
            
            $table->timestamps();
            
            // Asegurar que no haya respuestas duplicadas para el mismo ítem en la misma auditoría
            $table->unique(['audit_id', 'verification_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_responses');
    }
};
