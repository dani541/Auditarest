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
            // Add date column if it doesn't exist
            if (!Schema::hasColumn('audits', 'date')) {
                $table->date('date')->after('id')->comment('Fecha de realización de la auditoría');
            }
            

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

            $columnsToDrop = ['date', 'auditor', 'supervisor', 'responsable', 'incidencias_comentarios'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('audits', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

};
