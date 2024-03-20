<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->longText('desc')->nullable();
            $table->longText('link')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('ads_user_id');
            $table->foreign('ads_user_id')->references('id')->on('ads_users')->onDelete('cascade');
            $table->enum('sub', ['0', '1'])->default('0');
            $table->enum('main', ['0', '1'])->default('0');
            $table->enum('type', ['banner', 'logo', 'sort', 'popup', 'notification']);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->enum('status', ['0', '1','2','3','4'])->default('0');
            $table->time('not_time')->nullable();
            $table->string('app')->nullable();
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
        Schema::dropIfExists('system_ads');
    }
}