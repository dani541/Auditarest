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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->date('scheduled_date');
            $table->enum('status', ['pendiente', 'en_curso', 'completada', 'vencida'])->default('pendiente');
            
            // FK: Auditor asignado (usuario_id)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('Auditor asignado a esta auditoría');

            // FK: Restaurante auditado
            $table->foreignId('restaurant_id')
                  ->constrained('restaurants')
                  ->onDelete('cascade');

            // FK: Categoría de la auditoría (higiene, calidad, etc.)
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
