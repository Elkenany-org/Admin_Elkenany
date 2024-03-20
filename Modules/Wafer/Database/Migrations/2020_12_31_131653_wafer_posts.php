<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaferPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wafer_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_type');
            $table->string('item_age');
            $table->float('price');
            $table->float('average_weight');
            $table->tinyInteger('status')->default(0);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address');
            $table->date('date_of_sale');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('wafer_sections')->onDelete('cascade');
            $table->unsignedBigInteger('farm_id');
            $table->foreign('farm_id')->references('id')->on('wafer_farmers')->onDelete('cascade');
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
        Schema::dropIfExists('wafer_posts');
    }
}