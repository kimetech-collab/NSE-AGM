<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Drop the old table if it exists and recreate with new schema
        if (Schema::hasTable('audit_logs')) {
            Schema::dropIfExists('audit_logs');
        }

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('action', 150)->index();
            $table->string('entity_type', 100)->index();
            $table->unsignedBigInteger('entity_id');
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('status', ['Success', 'Failure'])->default('Success')->index();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            
            // Composite indexes for common queries
            $table->index(['action', 'entity_type']);
            $table->index(['actor_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            
            // Foreign key
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
