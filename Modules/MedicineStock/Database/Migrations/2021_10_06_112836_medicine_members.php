<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MedicineMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('active_id')->nullable();
            $table->foreign('active_id')->references('id')->on('medicine_substances')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('medicine_sections')->onDelete('cascade');
            $table->unsignedBigInteger('sub_id')->nullable();
            $table->foreign('sub_id')->references('id')->on('medicine_stocks')->onDelete('cascade');
            $table->unsignedBigInteger('name_id')->nullable();
            $table->foreign('name_id')->references('id')->on('commercial_names')->onDelete('cascade');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('check')->default(0);
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
        Schema::dropIfExists('medicine_members');
    }
}

