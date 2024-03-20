<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatMassages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_massages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('resav_id');
            $table->foreign('resav_id')->references('id')->on('customers')->onDelete('cascade');
            $table->longText('massage');
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
        Schema::dropIfExists('chat_massages');
    }
}

