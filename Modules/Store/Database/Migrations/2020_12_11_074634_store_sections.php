<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Store\Entities\Store_Section;
class StoreSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->enum('selected', ['0', '1'])->default('0');
            $table->timestamps();
        });

        $section = new Store_Section;
        $section->name       = 'الداجني';
        $section->type       = 'poultry';
        $section->save();

        $section = new Store_Section;
        $section->name       = 'الحيواني';
        $section->type       = 'animal';
        $section->save();

        $section = new Store_Section;
        $section->name       = 'الزراعي';
        $section->type       = 'farm';
        $section->save();

        $section = new Store_Section;
        $section->name       = 'السمكي';
        $section->type       = 'fish';
        $section->save();

        $section = new Store_Section;
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
        Schema::dropIfExists('store_sections');
    }
}
