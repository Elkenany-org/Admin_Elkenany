<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar')->default('default.png');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('google_id')->nullable();
            $table->string('api_token', 80)->unique()->nullable();
            $table->string('device_token')->nullable();
            $table->longText('web_fcm_token')->nullable();
            $table->longText('app_fcm_token')->nullable();
            $table->string('code', 5)->unique()->nullable();
            $table->enum('memb', ['0', '1'])->default('0');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->enum('verified_company', ['0','1','2'])->default('0');
            $table->enum('type', ['تاجر', 'مصنع/شركة','مزرعة/مدشة', 'مربي','طالب'])->nullable();
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
        Schema::dropIfExists('customers');
    }
}
