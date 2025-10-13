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
        
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
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
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Safely drop a column if it exists
     */
    private function dropColumnIfExists(string $tableName, string $column): void
    {
        if (Schema::hasColumn($tableName, $column)) {
            // First, check what indexes exist and drop them safely
            $this->dropIndexesForColumn($tableName, $column);
            
            // Then drop the column
            Schema::table($tableName, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Drop indexes that contain the specified column
     */
    private function dropIndexesForColumn(string $tableName, string $column): void
    {
        // Get all indexes for the table
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        
        foreach ($indexes as $index) {
            // Skip primary key
            if ($index->Key_name === 'PRIMARY') {
                continue;
            }
            
            // Check if this index contains our column
            $indexColumns = DB::select("SHOW INDEX FROM {$tableName} WHERE Key_name = ?", [$index->Key_name]);
            $columnNames = array_column($indexColumns, 'Column_name');
            
            if (in_array($column, $columnNames)) {
                try {
                    // Drop the index using raw SQL
                    DB::statement("ALTER TABLE {$tableName} DROP INDEX {$index->Key_name}");
                } catch (\Exception $e) {
                    // Ignore if index doesn't exist or can't be dropped
                    echo "Could not drop index {$index->Key_name} from {$tableName}: " . $e->getMessage() . "\n";
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
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
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
};
