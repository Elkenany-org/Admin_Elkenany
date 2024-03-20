<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourcesOfflineVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources_offline_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('video');
            $table->string('title');
            $table->string('desc');
            $table->unsignedBigInteger('folder_id');
            $table->foreign('folder_id')->references('id')->on('offline_folder')->onDelete('cascade');
            $table->unsignedBigInteger('offline_id');
            $table->foreign('offline_id')->references('id')->on('cources_offline')->onDelete('cascade');
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
        Schema::dropIfExists('cources_offline_videos');
    }
}

