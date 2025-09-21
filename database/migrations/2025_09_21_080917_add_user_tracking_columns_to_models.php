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
        // Add user tracking columns to all relevant tables
        $tables = [
            'zillas', 'upazilas', 'unions', 'wards', 
            'economic_years', 'relief_types', 'projects', 
            'organization_types', 'relief_applications'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('created_at');
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('updated_at');
                    $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null')->after('deleted_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'zillas', 'upazilas', 'unions', 'wards', 
            'economic_years', 'relief_types', 'projects', 
            'organization_types', 'relief_applications'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['created_by']);
                    $table->dropForeign(['updated_by']);
                    $table->dropForeign(['deleted_by']);
                    $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
                });
            }
        }
    }
};
