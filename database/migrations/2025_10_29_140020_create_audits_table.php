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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->after('id');
            $table->string('auditor');
            $table->date('date');
            $table->string('supervisor');
            $table->text('general_notes')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->decimal('total_score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
        });
        
        Schema::dropIfExists('audits');
    }
};
