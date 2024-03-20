<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocalStockMovementDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_stock_movement_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable();
            $table->unsignedBigInteger('column_id');
            $table->string('column_type')->nullable();
            $table->foreign('column_id')->references('id')->on('local_stock_columns')->onDelete('cascade');
            $table->unsignedBigInteger('movement_id')->nullable();
            $table->foreign('movement_id')->references('id')->on('local_stock_movement')->onDelete('cascade');
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')->references('id')->on('local_stock_members')->onDelete('cascade');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('local_stock_subsections')->onDelete('cascade');
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
        Schema::dropIfExists('local_stock_movement_details');
    }
}