<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{

    Schema::table('audits', function (Blueprint $table) {

        if (Schema::hasColumn('audits', 'scheduled_date')) {
            $table->renameColumn('scheduled_date', 'date');
        }
        
        
        $columns = [
            'auditor' => 'string',
            'supervisor' => 'string',
            'responsable' => 'string',
            'incidencias_comentarios' => 'text',
        ];

        foreach ($columns as $column => $type) {
            if (!Schema::hasColumn('audits', $column)) {
                $columnMethod = $type === 'text' ? 'text' : $type;
                $table->{$columnMethod}($column)->nullable($column !== 'auditor');
            }
        }
        
 
        if (Schema::hasColumn('audits', 'category_id')) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
        }
        
     
        if (!Schema::hasColumn('audits', 'auditor_id')) {
            $table->foreignId('auditor_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->onDelete('set null');
        }
    });

    // We're not creating the verification_responses table here anymore
    // as it already exists from a previous migration
}
};