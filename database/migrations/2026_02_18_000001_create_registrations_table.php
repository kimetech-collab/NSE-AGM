<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->index();
            $table->boolean('is_member')->default(false);
            $table->string('membership_number')->nullable();
            $table->unsignedBigInteger('pricing_version_id')->nullable()->index();
            $table->unsignedBigInteger('pricing_item_id')->nullable()->index();
            $table->integer('price_cents')->default(0);
            $table->string('currency', 8)->default('NGN');
            $table->timestamp('registration_timestamp')->useCurrent();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->string('ticket_token')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
};
