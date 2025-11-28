<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            
            // Boolean conditions and notes
            $table->boolean('floor_condition')->default(false);
            $table->text('floor_notes')->nullable();
            $table->boolean('walls_condition')->default(false);
            $table->text('walls_notes')->nullable();
            $table->boolean('windows_condition')->default(false);
            $table->text('windows_notes')->nullable();
            $table->boolean('doors_condition')->default(false);
            $table->text('doors_notes')->nullable();
            $table->boolean('ceiling_condition')->default(false);
            $table->text('ceiling_notes')->nullable();
            $table->boolean('lighting_condition')->default(false);
            $table->text('lighting_notes')->nullable();
            $table->boolean('countertops_condition')->default(false);
            $table->text('countertops_notes')->nullable();
            $table->boolean('work_tables_condition')->default(false);
            $table->text('work_tables_notes')->nullable();
            
            // Additional fields
            $table->text('additional_notes')->nullable();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_infrastructures');
    }
};