<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('job_application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('phone');
            $table->string('notice_period');

            $table->string('education');
            $table->string('experience');
            $table->longText('other_info')->nullable();

            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('job_offers')->onDelete('cascade');

            $table->integer('expected_salary');

            $table->string('cv_link');
            $table->enum('qualified', ['0', '1'])->default(null);

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
        Schema::dropIfExists('job_application');
    }
}
