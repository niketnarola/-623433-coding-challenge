<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_connections', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_id')->unsigned()->comment('user id - who send the request');
            $table->integer('receiver_id')->unsigned()->comment('user id - who receiver the request');
            $table->tinyInteger('status')->unsigned()->comment('0 - pending request, 1 - request accept, 2 - request rejected');
            $table->timestamp('request_sent_at')->comment('when user has sent the request');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_connections');
    }
};
