<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompaniesAlboumImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_alboum_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('guide_sections')->onDelete('cascade');
            $table->unsignedBigInteger('sub_section_id')->nullable();
            $table->foreign('sub_section_id')->references('id')->on('guide_sub_sections')->onDelete('cascade');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('gallary_id');
            $table->foreign('gallary_id')->references('id')->on('company_gallary')->onDelete('cascade');
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
        Schema::dropIfExists('companies_alboum_images');
    }
}
