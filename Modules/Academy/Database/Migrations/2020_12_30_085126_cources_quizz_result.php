<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesQuizzResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_quizz_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('result');
            $table->integer('success_rate');
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
        Schema::dropIfExists('cources_quizz_result');
    }
}