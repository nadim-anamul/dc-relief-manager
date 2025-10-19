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
        Schema::table('relief_applications', function (Blueprint $table) {
            // Add application type field only if it doesn't exist
            if (!Schema::hasColumn('relief_applications', 'application_type')) {
                $table->enum('application_type', ['individual', 'organization'])->default('organization')->after('id');
            }
            
            // Add NID field for applicant only if it doesn't exist
            if (!Schema::hasColumn('relief_applications', 'applicant_nid')) {
                $table->string('applicant_nid')->nullable()->after('applicant_name');
            }
            
            // Make organization fields nullable since they're not required for individual applications
            $table->string('organization_name')->nullable()->change();
            $table->text('organization_address')->nullable()->change();
            
            // Add index for application type only if it doesn't exist
            if (!Schema::hasIndex('relief_applications', 'relief_applications_application_type_index')) {
                $table->index('application_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relief_applications', function (Blueprint $table) {
            // Remove the new fields
            $table->dropColumn(['application_type', 'applicant_nid']);
            
            // Revert organization fields to required
            $table->string('organization_name')->nullable(false)->change();
            $table->text('organization_address')->nullable(false)->change();
            
            // Drop the index
            $table->dropIndex(['application_type']);
        });
    }
};
