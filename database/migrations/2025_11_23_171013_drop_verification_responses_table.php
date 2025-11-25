<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        
        if (Schema::hasTable('verification_responses')) {
            Schema::drop('verification_responses');
        }
    }

    public function down()
    {
      
        Schema::create('verification_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->foreignId('verification_item_id')->constrained()->onDelete('cascade');
            $table->boolean('complies')->default(true);
            $table->text('notes')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
            
            $table->unique(['audit_id', 'verification_item_id']);
        });
    }
};