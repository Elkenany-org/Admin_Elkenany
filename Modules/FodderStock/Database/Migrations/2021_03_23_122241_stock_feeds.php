<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockFeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->tinyInteger('fixed')->default(0);
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('fodder_sub_sections')->onDelete('cascade');
            $table->unsignedBigInteger('mini_id')->nullable();
            $table->foreign('mini_id')->references('id')->on('mini_sub')->onDelete('set null');
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
        Schema::dropIfExists('stock_feeds');
    }
}
