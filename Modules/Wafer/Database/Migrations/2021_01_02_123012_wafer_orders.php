<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaferOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wafer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('app_commission')->default(0);
            $table->string('payment')->nullable();
            $table->string('qr_code')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('farm_id');
            $table->foreign('farm_id')->references('id')->on('wafer_farmers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('wafer_posts')->onDelete('cascade');
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
        Schema::dropIfExists('wafer_orders');
    }
}


