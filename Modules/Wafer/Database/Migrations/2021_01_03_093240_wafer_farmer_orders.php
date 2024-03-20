<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaferFarmerOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wafer_farmer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content');
            $table->longText('management_response')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('wafer_farmer_orders');
    }
}
