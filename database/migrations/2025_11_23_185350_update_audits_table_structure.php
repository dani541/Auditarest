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
    Schema::table('audits', function (Blueprint $table) {
        // Primero hacer category_id nullable
        $table->foreignId('category_id')->nullable()->change();
        
        // Verificar si la columna 'date' ya existe antes de intentar crearla
        if (!Schema::hasColumn('audits', 'date')) {
            $table->date('date')->after('id')->comment('Fecha de realización de la auditoría');
        }
        
        // Agregar las columnas faltantes si no existen
        $columnsToAdd = [
            'auditor' => 'string',
            'supervisor' => 'string',
            'responsable' => 'string',
            'incidencias_comentarios' => 'text'
        ];
        
        foreach ($columnsToAdd as $column => $type) {
            if (!Schema::hasColumn('audits', $column)) {
                if ($type === 'text') {
                    $table->text($column)->nullable();
                } else {
                    $table->string($column);
                }
            }
        }
    });
}

public function down()
{
    Schema::table('audits', function (Blueprint $table) {
        // Revertir los cambios
        $columnsToDrop = ['date', 'auditor', 'supervisor', 'responsable', 'incidencias_comentarios'];
        
        foreach ($columnsToDrop as $column) {
            if (Schema::hasColumn('audits', $column)) {
                $table->dropColumn($column);
            }
        }
        
        // Revertir category_id a no nulo
        $table->foreignId('category_id')->nullable(false)->change();
    });
}


};
