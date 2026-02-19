<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->integer('attendance_seconds')->default(0);
            $table->enum('attendance_status', ['none', 'virtual', 'physical'])->default('none')->index();
            $table->timestamp('attendance_last_at')->nullable()->index();
            $table->timestamp('attendance_eligible_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_seconds',
                'attendance_status',
                'attendance_last_at',
                'attendance_eligible_at',
            ]);
        });
    }
};
