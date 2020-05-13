<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->BigInteger('user_id')->index()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->string('city')->nullable();
            $table->Integer('country_id')->index()->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');            
            $table->integer('verified')->default(0)->nullable();
            $table->decimal('rate', 13, 2)->nullable();
            $table->string('featured')->nullable();
            $table->json('options')->nullable();
            $table->string('momo')->nullable();
            $table->string('momo_network')->nullable();
            $table->string('momo_country')->nullable();
            $table->string('paypal')->nullable();
            $table->string('skrill')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_no')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_country')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('profiles');
    }
}
