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
        // First, check if the audits table exists
        if (!Schema::hasTable('audits')) {
            // Create the audits table if it doesn't exist
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
        } else {
            // If table exists, make sure all columns are present
            Schema::table('audits', function (Blueprint $table) {
                // Add any missing columns
                if (!Schema::hasColumn('audits', 'restaurant_id')) {
                    $table->foreignId('restaurant_id')->constrained()->after('id');
                }
                if (!Schema::hasColumn('audits', 'auditor')) {
                    $table->string('auditor');
                }
                if (!Schema::hasColumn('audits', 'date')) {
                    $table->date('date');
                }
                if (!Schema::hasColumn('audits', 'supervisor')) {
                    $table->string('supervisor');
                }
                if (!Schema::hasColumn('audits', 'general_notes')) {
                    $table->text('general_notes')->nullable();
                }
                if (!Schema::hasColumn('audits', 'is_completed')) {
                    $table->boolean('is_completed')->default(false);
                }
                if (!Schema::hasColumn('audits', 'total_score')) {
                    $table->decimal('total_score', 5, 2)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the table if it was created by this migration
        if (Schema::hasTable('audits')) {
            Schema::table('audits', function (Blueprint $table) {
                // Drop foreign key constraints first
                if (Schema::hasColumn('audits', 'restaurant_id')) {
                    $table->dropForeign(['restaurant_id']);
                }
            });
            
            // Only drop the table if it was created by this migration
            // We'll keep the table if it existed before
            if (!Schema::hasTable('audits_bak')) {
                Schema::dropIfExists('audits');
            }
        }
    }
};
