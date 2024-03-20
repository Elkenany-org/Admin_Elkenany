<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GuideSubSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide_sub_sections', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->string('type');
        $table->unsignedBigInteger('section_id')->nullable();
        $table->foreign('section_id')->references('id')->on('guide_sections')->onDelete('set null');
        $table->string('image');
        $table->bigInteger('view_count')->default(0);
        $table->integer('sort')->nullable();
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
    Schema::dropIfExists('guide_sub_sections');
}
}
