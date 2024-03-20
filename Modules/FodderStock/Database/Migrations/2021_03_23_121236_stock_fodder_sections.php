<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FodderStock\Entities\Stock_Fodder_Section;

class StockFodderSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_fodder_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image');
            $table->timestamps();
        });

        $section = new Stock_Fodder_Section;
        $section->name       = 'الداجني';
        $section->image       = 'default.png';
        $section->save();

        $section = new Stock_Fodder_Section;
        $section->name       = 'الحيواني';
        $section->image       = 'default.png';
        $section->save();

        $section = new Stock_Fodder_Section;
        $section->name       = 'الزراعي';
        $section->image       = 'default.png';
        $section->save();

        $section = new Stock_Fodder_Section;
        $section->name       = 'السمكي';
        $section->image       = 'default.png';
        $section->save();

        $section = new Stock_Fodder_Section;
        $section->name       = 'الخيول';
        $section->image       = 'default.png';
        $section->save();

      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_fodder_sections');
    }
}
