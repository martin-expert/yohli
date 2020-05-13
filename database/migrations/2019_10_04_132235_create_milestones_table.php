<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('profile_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->bigInteger('job_id')->unsigned()->nullable();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->bigInteger('bid_id')->unsigned()->nullable();
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->string('heading')->nullable();
            $table->string('status')->nullable();
            $table->text('activity')->nullable();
            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('cost', 13,2)->nullable();
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
        Schema::dropIfExists('milestones');
    }
}
