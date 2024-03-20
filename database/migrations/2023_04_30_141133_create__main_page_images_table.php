<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainPageImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->string('description');
            $table->enum('type', ['cta', 'claim', 'questions', 'app', 'howtouse'])->nullable();

            $table->unsignedBigInteger('services')->nullable();
            $table->foreign('services')->references('id')->on('home_services')->onDelete('cascade');

            $table->unsignedBigInteger('most_visited')->nullable();
            $table->foreign('most_visited')->references('id')->on('home_services')->onDelete('cascade');

            $table->unsignedBigInteger('newest')->nullable();
            $table->foreign('newest')->references('id')->on('home_services')->onDelete('cascade');

            $table->string('link');
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
        Schema::dropIfExists('main_images');
    }
}
