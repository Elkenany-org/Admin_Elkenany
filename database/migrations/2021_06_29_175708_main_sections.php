<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Main;

class MainSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->string('image');
            $table->timestamps();
        });

        $section = new Main;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->image      = 'poultry.svg';
        $section->save();

        $section = new Main;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->image      = 'animal.svg';
        $section->save();

        $section = new Main;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->image      = 'farm.svg';
        $section->save();

        $section = new Main;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->image      = 'fish.svg';
        $section->save();

        $section = new Main;
        $section->name       = 'الخيول';
        $section->type       = 'horses';
        $section->image      = 'horses.svg';
        $section->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_sections');
    }
}
