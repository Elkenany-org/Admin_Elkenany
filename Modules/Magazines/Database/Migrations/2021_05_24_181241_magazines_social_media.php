<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MagazinesSocialMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazines_social_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('social_link');
            $table->unsignedBigInteger('maga_id');
            $table->foreign('maga_id')->references('id')->on('magazines')->onDelete('cascade');
            $table->unsignedBigInteger('social_id');
            $table->foreign('social_id')->references('id')->on('social_media')->onDelete('cascade');
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
        Schema::dropIfExists('magazines_social_media');
    }
}

