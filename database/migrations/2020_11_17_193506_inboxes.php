<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Inboxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inboxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',500)->nullable();
            $table->longText('subject')->nullable();
            $table->string('user_name',500)->nullable();
            $table->string('user_phone',500)->nullable();
            $table->string('user_email',500)->nullable();
            $table->string('message_type',191)->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('inboxes');
    }
}
