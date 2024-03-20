<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Guide\Entities\Services;

class HomeServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('home_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['1','0'])->default('0');//0 for private 1 for public
            $table->timestamps();
        });

        $service = new Services;
        $service->name       = 'المعارض';
        $service->type       = 'shows';
        $service->image       = 'shows.png';
        $service->save();

        $service = new Services;
        $service->name       = 'دلائل و مجلات';
        $service->type       = 'magazine';
        $service->image       = 'magazine.png';
        $service->save();

        $service = new Services;
        $service->name       = 'حركة السفن';
        $service->type       = 'ships';
        $service->image       = 'ships.png';
        $service->save();

        $service = new Services;
        $service->name       = 'المناقصات';
        $service->type       = 'tenders';
        $service->save();

        $service = new Services;
        $service->name       = 'الوظاثف';
        $service->type       = 'jobs';
        $service->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('home_services');

    }
}
