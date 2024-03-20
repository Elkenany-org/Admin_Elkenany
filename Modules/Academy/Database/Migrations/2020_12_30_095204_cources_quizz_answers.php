<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesQuizzAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_quizz_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('state')->nullable();
            $table->unsignedBigInteger('result_id');
            $table->foreign('result_id')->references('id')->on('cources_quizz_result')->onDelete('cascade');
            $table->longText('articl')->nullable();
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->foreign('answer_id')->references('id')->on('cources_quizz_question_answers')->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('cources_quizz_question')->onDelete('cascade');
            $table->unsignedBigInteger('quizz_id');
            $table->foreign('quizz_id')->references('id')->on('cources_quizz')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('cources_quizz_answers');
    }
}