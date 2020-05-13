<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Musonza\Chat\ConfigurationManager;

class CreateChatTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_id')->nullable()->unsigned();
            $table->boolean('private')->default(true);
            $table->boolean('direct_message')->default(false);
            $table->text('data')->nullable();
            $table->timestamps();

            $table->foreign('job_id')
            ->references('id')
            ->on('jobs')
            ->onDelete('cascade');
        });

        Schema::create('participation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('settings')->nullable();
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id'], 'participation_index');

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade');
                
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned()->nullable();
            $table->string('type')->default('text');
            $table->timestampsTz();

            $table->foreign('participation_id')
                ->references('id')
                ->on('participation')
                ->onDelete('set null');

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade');
        });

        Schema::create('message_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('message_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned();
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_sender')->default(false);
            $table->boolean('flagged')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['participation_id', 'message_id'], 'participation_message_index');

            $table->foreign('message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade');

            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade');

            $table->foreign('participation_id')
                ->references('id')
                ->on('participation')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('participation');
        Schema::dropIfExists('conversations');
    }
}
