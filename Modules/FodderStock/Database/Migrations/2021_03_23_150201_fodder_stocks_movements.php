<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FodderStocksMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fodder_stocks_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fodder_id')->nullable();
            $table->foreign('fodder_id')->references('id')->on('stock_feeds')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('stock_fodder_sections')->onDelete('cascade');
            $table->unsignedBigInteger('sub_id')->nullable();
            $table->foreign('sub_id')->references('id')->on('fodder_sub_sections')->onDelete('cascade');
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('fodder_stocks')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->float('price');
            $table->float('change')->nullable();
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
        Schema::dropIfExists('fodder_stocks_movements');
    }
}