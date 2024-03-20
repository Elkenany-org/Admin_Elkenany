<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DataAnalysisKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_analysis_keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('keyword');
            $table->string('type')->nullable();
            $table->bigInteger('use_count')->default(0);
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
        Schema::dropIfExists('data_analysis_keywords');
    }
}

