<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('profile_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->bigInteger('industry_id')->nullable()->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->integer('job_budget_id')->unsigned()->nullable();
            $table->foreign('job_budget_id')->references('id')->on('job_budgets')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->decimal('min_budget', 13, 2)->nullable();
            $table->decimal('max_budget', 13, 2)->nullable();
            $table->string('budget_type')->nullable();
            $table->string('slug')->nullable();
            $table->string('status')->nullable();
            $table->string('city')->nullable();
            $table->string('featured')->nullable();
            $table->string('ontime')->nullable();
            $table->string('onbudget')->nullable();
            $table->string('duration')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
