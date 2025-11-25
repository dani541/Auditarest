<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_machineries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            
            // Maquinaria
            $table->boolean('stove_condition')->default(false);
            $table->text('stove_notes')->nullable();
            $table->boolean('oven_condition')->default(false);
            $table->text('oven_notes')->nullable();
            $table->boolean('fryer_condition')->default(false);
            $table->text('fryer_notes')->nullable();
            $table->boolean('refrigerator_condition')->default(false);
            $table->text('refrigerator_notes')->nullable();
            
            // Equipos adicionales
            $table->boolean('freezer_condition')->default(false);
            $table->text('freezer_notes')->nullable();
            $table->boolean('microwave_condition')->default(false);
            $table->text('microwave_notes')->nullable();
            $table->boolean('dishwasher_condition')->default(false);
            $table->text('dishwasher_notes')->nullable();
            
            // Mantenimiento
            $table->boolean('maintenance_up_to_date')->default(false);
            $table->date('last_maintenance_date')->nullable();
            $table->text('maintenance_notes')->nullable();
            
            // Puntuación
            $table->decimal('total_score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_machineries');
    }
};