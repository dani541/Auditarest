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
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();
            $table->string('file_path'); // Ruta donde se guarda el archivo (ej: storage/app/public/audits/img.jpg)
            $table->string('file_type'); // Tipo de archivo (ej: image/jpeg, application/pdf)
            $table->text('description')->nullable();

            // FK: Auditoría a la que pertenece esta evidencia
            $table->foreignId('audit_id')
                  ->constrained('audits')
                  ->onDelete('cascade'); // Si se elimina la auditoría, se eliminan las evidencias
            
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};
