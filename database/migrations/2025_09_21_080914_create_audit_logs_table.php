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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type'); // Model class name
            $table->unsignedBigInteger('auditable_id'); // Model ID
            $table->string('event'); // created, updated, deleted, restored
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->json('changed_fields')->nullable(); // Fields that changed
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // User who made the change
            $table->string('ip_address')->nullable(); // IP address
            $table->string('user_agent')->nullable(); // User agent
            $table->text('url')->nullable(); // URL where change was made
            $table->text('remarks')->nullable(); // Additional remarks
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['event']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
