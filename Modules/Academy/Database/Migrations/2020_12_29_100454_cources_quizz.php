<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesQuizz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_quizz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('residuum');
            $table->integer('accepted');
            $table->integer('good');
            $table->integer('very_good');
            $table->integer('excellent');
            $table->unsignedBigInteger('courses_id');
            $table->foreign('courses_id')->references('id')->on('cources')->onDelete('cascade');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->foreign('folder_id')->references('id')->on('offline_folder')->onDelete('cascade');
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
        Schema::dropIfExists('cources_quizz');
    }
}