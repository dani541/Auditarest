<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('audit_infrastructure', function (Blueprint $table) {
        $table->id();
        $table->foreignId('audit_id')->constrained()->onDelete('cascade');
        
        // Campos booleanos
        $fields = [
            'floor', 'walls', 'windows', 'doors', 'ceiling', 
            'lighting', 'countertops', 'work_tables'
        ];
        
        foreach ($fields as $field) {
            $table->boolean($field . '_condition')->default(false);
            $table->text($field . '_notes')->nullable();
            $table->integer($field . '_score')->default(0);
        }
        
        $table->text('additional_notes')->nullable();
        $table->integer('total_score')->default(0);
        $table->decimal('percentage', 5, 2)->default(0);
        $table->timestamps();
        
        // Índices
        $table->index('audit_id');
    });
}

public function down()
{
    Schema::dropIfExists('audit_infrastructure');
}
};
