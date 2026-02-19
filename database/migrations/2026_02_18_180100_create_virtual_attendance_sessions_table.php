<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('virtual_attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->index();
            $table->string('session_id', 64)->index();
            $table->string('platform', 32)->nullable()->index();
            $table->timestamp('started_at')->useCurrent()->index();
            $table->timestamp('last_heartbeat_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->integer('total_seconds')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['registration_id', 'session_id']);
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_attendance_sessions');
    }
};
