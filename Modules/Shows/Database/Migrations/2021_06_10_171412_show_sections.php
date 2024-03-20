<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Shows\Entities\Show_Section;
class ShowSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->enum('selected', ['0', '1'])->default('0');
            $table->timestamps();
        });

        $section = new Show_Section;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->save();

        $section = new Show_Section;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->save();

        $section = new Show_Section;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->save();

        $section = new Show_Section;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->save();

        $section = new Show_Section;
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
        Schema::dropIfExists('show_sections');
    }
}
