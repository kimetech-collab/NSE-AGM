<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->unique();
            $table->string('certificate_id', 32)->unique()->index();
            $table->enum('status', ['issued', 'revoked'])->default('issued')->index();
            $table->timestamp('issued_at')->nullable()->index();
            $table->timestamp('revoked_at')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
