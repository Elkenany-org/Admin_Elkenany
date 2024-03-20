<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShowsOrganisers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows_organisers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organisers')->onDelete('cascade');
            $table->unsignedBigInteger('show_id');
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
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
        Schema::dropIfExists('shows_organisers');
    }
}