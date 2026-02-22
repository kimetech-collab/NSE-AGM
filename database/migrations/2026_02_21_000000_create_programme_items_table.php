<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->date('programme_date');
            $table->string('start_time', 5);
            $table->string('end_time', 5)->nullable();
            $table->string('location')->nullable();
            $table->string('track')->nullable();
            $table->string('speaker_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('programme_date');
            $table->index('is_active');
            $table->index(['programme_date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_items');
    }
};
