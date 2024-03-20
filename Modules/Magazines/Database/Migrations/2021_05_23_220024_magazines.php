<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Magazines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image');
            $table->string('manage_phone');
            $table->string('manage_email');
            $table->tinyInteger('paied');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address');
            $table->longText('short_desc');
            $table->longText('about');
            $table->longText('mobiles');
            $table->longText('phones');
            $table->longText('emails');
            $table->longText('faxs');
            $table->float('rate')->default(0);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
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
        Schema::dropIfExists('magazines');
    }
    }
    
    