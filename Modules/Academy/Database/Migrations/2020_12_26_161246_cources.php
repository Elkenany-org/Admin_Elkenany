<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('image');
            $table->longText('desc');
            $table->tinyInteger('live')->default(0);
            $table->tinyInteger('meeting')->default(0);
            $table->tinyInteger('offline')->default(0);
            $table->string('price_live')->nullable();
            $table->string('price_meeting')->nullable();
            $table->string('price_offline')->nullable();
            $table->string('hourse_live')->nullable();
            $table->string('hourse_meeting')->nullable();
            $table->string('hourse_offline')->nullable();
            $table->tinyInteger('rate')->default(0);
            $table->tinyInteger('status_l')->default(0);
            $table->tinyInteger('status_o')->default(0);
            $table->tinyInteger('status_n')->default(0);
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
        Schema::dropIfExists('cources');
    }
}
