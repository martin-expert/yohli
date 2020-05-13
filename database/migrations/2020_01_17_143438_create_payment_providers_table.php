<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->decimal('withdrawal_fixed_rate')->nullable();
            $table->decimal('withdrawal_rate')->nullable();
            $table->decimal('deposit_fixed_rate')->nullable();
            $table->decimal('deposit_rate')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('merchank_key')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_providers');
    }
}
