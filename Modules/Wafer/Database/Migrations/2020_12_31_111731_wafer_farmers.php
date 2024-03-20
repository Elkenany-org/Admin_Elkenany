<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaferFarmers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wafer_farmers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('avatar')->default('default.png');
            $table->string('farm_name');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('wafer_sections')->onDelete('cascade');
            $table->string('password');
            $table->string('api_token', 80)->unique()->nullable();
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
        //
        Schema::dropIfExists('wafer_farmers');
    }
}