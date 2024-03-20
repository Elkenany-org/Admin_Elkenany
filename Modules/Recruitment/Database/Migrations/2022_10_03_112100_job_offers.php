<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('job_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('desc');
            $table->float('salary', 12, 2);
            $table->string('phone')->unique()->nullable();
            $table->longText('address');
            $table->string('experience');
            $table->BigInteger('view_count')->default(0);
            $table->enum('paid', ['0', '1'])->default('0');
            $table->string('con_type');
            $table->enum('work_hours', ['دوام كلي','دوام جزئي','عن بعد','مرن'])->default('كلي');
            $table->enum('approved', ['0', '1'])->default('0');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('recruiter_id')->nullable();
            $table->foreign('recruiter_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('job_catergories')->onDelete('cascade');
            $table->unsignedBigInteger('sector_id');
            $table->foreign('sector_id')->references('id')->on('main_sections')->onDelete('cascade');
            $table->json('skills')->nullable();
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
        Schema::dropIfExists('job_offers');

    }
}
