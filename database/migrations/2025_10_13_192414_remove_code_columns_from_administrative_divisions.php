<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove code columns from all administrative division tables
        // Using raw SQL to handle foreign key constraints properly
        
        // First, check if columns exist before trying to drop them
        $this->dropColumnIfExists('zillas', 'code');
        $this->dropColumnIfExists('upazilas', 'code');
        $this->dropColumnIfExists('unions', 'code');
        $this->dropColumnIfExists('wards', 'code');
        
        // Add new indexes for name-based uniqueness
        Schema::table('upazilas', function (Blueprint $table) {
            $table->index(['zilla_id', 'name']);
        });

        Schema::table('unions', function (Blueprint $table) {
            $table->index(['upazila_id', 'name']);
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->index(['union_id', 'name']);
        });
    }

    /**
     * Safely drop a column if it exists
     */
    private function dropColumnIfExists(string $tableName, string $column): void
    {
        if (Schema::hasColumn($tableName, $column)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $column) {
                // Try to drop unique constraint first
                try {
                    $table->dropUnique([$column]);
                } catch (\Exception $e) {
                    // Ignore if unique constraint doesn't exist
                }
                
                // Try to drop composite indexes that include this column
                try {
                    if ($tableName === 'upazilas') {
                        $table->dropIndex(['zilla_id', $column]);
                    } elseif ($tableName === 'unions') {
                        $table->dropIndex(['upazila_id', $column]);
                    } elseif ($tableName === 'wards') {
                        $table->dropIndex(['union_id', $column]);
                    }
                } catch (\Exception $e) {
                    // Ignore if index doesn't exist
                }
                
                // Drop the column
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore code columns to all administrative division tables
        Schema::table('zillas', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
        });

        Schema::table('upazilas', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            // Drop name-based index and add code-based index
            try {
                $table->dropIndex(['zilla_id', 'name']);
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
            $table->index(['zilla_id', 'code']);
        });

        Schema::table('unions', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            // Drop name-based index and add code-based index
            try {
                $table->dropIndex(['upazila_id', 'name']);
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
            $table->index(['upazila_id', 'code']);
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            // Drop name-based index and add code-based index
            try {
                $table->dropIndex(['union_id', 'name']);
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
            $table->index(['union_id', 'code']);
        });
    }
};
