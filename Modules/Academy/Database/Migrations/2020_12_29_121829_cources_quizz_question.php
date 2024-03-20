<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesQuizzQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_quizz_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->string('type');
            $table->unsignedBigInteger('quizz_id');
            $table->foreign('quizz_id')->references('id')->on('cources_quizz')->onDelete('cascade');
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
        Schema::dropIfExists('cources_quizz_question');
    }
}
