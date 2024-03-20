<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocalStockMemberChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_stock_member_change', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('change')->nullable();
            $table->float('result')->nullable();
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')->references('id')->on('local_stock_members')->onDelete('cascade');
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
        Schema::dropIfExists('local_stock_member_change');
    }
}
