<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qr_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable()->index();
            $table->unsignedBigInteger('scanned_by')->nullable()->index();
            $table->string('status', 32)->index();
            $table->string('token', 128)->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamp('scanned_at')->useCurrent()->index();
            $table->string('ip_address', 45)->nullable();

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('scanned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
    }
};
