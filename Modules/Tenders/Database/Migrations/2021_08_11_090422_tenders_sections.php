<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Tenders\Entities\Tender_Section;

class TendersSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->timestamps();
        });

        $section = new Tender_Section;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->save();

        $section = new Tender_Section;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->save();

        $section = new Tender_Section;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->save();

        $section = new Tender_Section;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->save();

        $section = new Tender_Section;
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
        Schema::dropIfExists('tenders_sections');
    }
}

