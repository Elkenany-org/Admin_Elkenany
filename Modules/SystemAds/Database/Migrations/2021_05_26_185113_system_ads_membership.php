<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemAdsMembership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_ads_membership', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ads_count');
            $table->enum('type', ['banner', 'logo', 'sort', 'popup', 'notification']);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('ads_user_id');
            $table->foreign('ads_user_id')->references('id')->on('ads_users')->onDelete('cascade');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->enum('status', ['0', '1'])->default('0');
            $table->integer('main')->nullable();
            $table->integer('sub')->nullable();
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
        Schema::dropIfExists('system_ads_membership');
    }
}
