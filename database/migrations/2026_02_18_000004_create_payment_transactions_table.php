<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable()->index();
            $table->string('provider')->index();
            $table->string('provider_reference')->index();
            $table->integer('amount_cents')->default(0);
            $table->string('currency', 8)->default('NGN');
            $table->enum('status', ['pending','success','failed','refunded'])->default('pending');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};
