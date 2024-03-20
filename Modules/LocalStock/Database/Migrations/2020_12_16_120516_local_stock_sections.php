<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\LocalStock\Entities\Local_Stock_Sections;

class LocalStockSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_stock_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->enum('selected', ['0', '1'])->default('0');
            $table->timestamps();
        });

        $section = new Local_Stock_Sections;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->save();

        $section = new Local_Stock_Sections;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->save();

        $section = new Local_Stock_Sections;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->save();

        $section = new Local_Stock_Sections;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->save();

        $section = new Local_Stock_Sections;
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
        Schema::dropIfExists('local_stock_sections');
    }
}
