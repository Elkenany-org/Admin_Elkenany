<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Offices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address');
            $table->longText('mobiles');
            $table->longText('faxs');
            $table->longText('phones');
            $table->longText('emails');
            $table->longText('desc');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('offices');
    }
    }
    
    