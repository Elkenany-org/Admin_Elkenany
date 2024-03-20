<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemAdsPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_ads_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('section_type')->nullable();
            $table->integer('sub_id')->nullable();
            $table->unsignedBigInteger('ads_id');
            $table->foreign('ads_id')->references('id')->on('system_ads')->onDelete('cascade');
            $table->enum('status', ['0', '1'])->default('0');
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
        Schema::dropIfExists('system_ads_pages');
    }
}
