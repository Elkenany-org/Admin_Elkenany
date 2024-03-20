<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MagazinesAlboumImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazines_alboum_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('maga_id');
            $table->foreign('maga_id')->references('id')->on('magazines')->onDelete('cascade');
            $table->unsignedBigInteger('gallary_id');
            $table->foreign('gallary_id')->references('id')->on('magazines_gallary')->onDelete('cascade');
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
        Schema::dropIfExists('magazines_alboum_images');
    }
}
