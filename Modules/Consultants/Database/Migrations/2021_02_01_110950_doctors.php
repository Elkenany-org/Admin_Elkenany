<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Doctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('avatar')->default('default.png');
            $table->longText('certificates');
            $table->longText('experiences');
            $table->string('call_price');
            $table->string('call_duration');
            $table->string('online_price');
            $table->string('online_duration');
            $table->string('meeting_price');
            $table->string('meeting_duration');
            $table->string('password');
            $table->string('adress');
            $table->float('rate')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('doctors');
    }
}