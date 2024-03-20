<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MagazinesSubSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazines_sub_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('magazines_sections')->onDelete('cascade');
            $table->string('image');
            $table->bigInteger('view_count')->default(0);
            $table->bigInteger('power')->default(0);
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
        Schema::dropIfExists('magazines_sub_sections');
    }
    }