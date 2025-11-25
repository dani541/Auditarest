<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_hygienes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            
            // Higiene personal
            $table->boolean('uniforms_condition')->default(false);
            $table->text('uniforms_notes')->nullable();
            $table->boolean('hand_washing_condition')->default(false);
            $table->text('hand_washing_notes')->nullable();
            $table->boolean('hygiene_kits_condition')->default(false);
            $table->text('hygiene_kits_notes')->nullable();
            
            // Manipulación de alimentos
            $table->boolean('food_handling_condition')->default(false);
            $table->text('food_handling_notes')->nullable();
            $table->boolean('gloves_usage')->default(false);
            $table->text('gloves_notes')->nullable();
            $table->boolean('hair_restraint_usage')->default(false);
            $table->text('hair_restraint_notes')->nullable();
            
            // Limpieza y desinfección
            $table->boolean('cleaning_supplies_condition')->default(false);
            $table->text('cleaning_supplies_notes')->nullable();
            $table->boolean('sanitization_procedures')->default(false);
            $table->text('sanitization_notes')->nullable();
            
            // Almacenamiento
            $table->boolean('food_storage_condition')->default(false);
            $table->text('food_storage_notes')->nullable();
            $table->boolean('chemical_storage_condition')->default(false);
            $table->text('chemical_storage_notes')->nullable();
            
            // Puntuación
            $table->decimal('total_score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_hygienes');
    }
};