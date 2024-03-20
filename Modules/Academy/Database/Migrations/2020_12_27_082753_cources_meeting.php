<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesMeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_meeting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('prof');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('hourse_count');
            $table->unsignedBigInteger('courses_id');
            $table->foreign('courses_id')->references('id')->on('cources')->onDelete('cascade');
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
        Schema::dropIfExists('cources_meeting');
    }
}
