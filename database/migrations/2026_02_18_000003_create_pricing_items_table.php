<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pricing_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_version_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price_cents')->default(0);
            $table->string('currency', 8)->default('NGN');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_items');
    }
};
