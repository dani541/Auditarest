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
            

            $table->foreignId('audit_id')
                  ->constrained('audits')
                  ->onDelete('cascade');
            

            $table->foreignId('verification_item_id')
                  ->constrained('verification_items')
                  ->onDelete('cascade');
            

            $table->enum('status', ['C', 'IC', 'NA'])->nullable(); 
            $table->text('corrective_measure')->nullable(); 
            $table->decimal('temperature', 8, 2)->nullable(); 
            
            $table->timestamps();
            
           
            $table->unique(['audit_id', 'verification_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_responses');
    }
};
