<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesQuizzQuestionAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_quizz_question_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('answer');
            $table->tinyInteger('correct')->default(0);
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('cources_quizz_question')->onDelete('cascade');
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
        Schema::dropIfExists('cources_quizz_question_answers');
    }
}
