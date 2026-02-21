<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title')->nullable();
            $table->string('organization')->nullable();
            $table->longText('bio')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Media & Social
            $table->string('photo_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // Expertise & Session
            $table->json('expertise_topics')->nullable();
            $table->string('session_title')->nullable();
            $table->longText('session_description')->nullable();
            $table->dateTime('session_time')->nullable();

            // Status & Sorting
            $table->boolean('is_keynote')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();

            $table->timestamps();

            // Indexes for better query performance
            $table->index(['is_active', 'is_keynote']);
            
            // FULLTEXT index only for MySQL/MariaDB
            if (config('database.default') === 'mysql' || config('database.default') === 'mariadb') {
                $table->fullText(['first_name', 'last_name', 'organization', 'bio']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speakers');
    }
};
