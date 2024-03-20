<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Consultants\Entities\Major;

class Majors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->timestamps();
        });

        $section = new Major;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->save();

        $section = new Major;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->save();

        $section = new Major;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->save();

        $section = new Major;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->save();

        $section = new Major;
        $section->name       = 'الخيول';
        $section->type       = 'horses';
        $section->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('majors');
    }
}