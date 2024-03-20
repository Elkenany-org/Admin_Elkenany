<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShowReats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_reats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('rate')->nullable();
            $table->longText('desc')->nullable();
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
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
        Schema::dropIfExists('show_reats');
    }
}