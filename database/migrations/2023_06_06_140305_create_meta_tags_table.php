<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('desc');
            $table->string('title_social');
            $table->text('desc_social');
            $table->text('keywords')->nullable();
            $table->string('link');
            $table->string('image');
            $table->string('alt');

            $table->unsignedBigInteger('news_id')->nullable();
            $table->unsignedBigInteger('news_section_id')->nullable();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->foreign('news_section_id')->references('id')->on('news_sections')->onDelete('cascade');

            $table->unsignedBigInteger('show_id')->nullable();
            $table->unsignedBigInteger('show_section_id')->nullable();
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->foreign('show_section_id')->references('id')->on('show_sections')->onDelete('cascade');

            $table->unsignedBigInteger('local_subsection_id')->nullable();
            $table->unsignedBigInteger('local_section_id')->nullable();
            $table->foreign('local_subsection_id')->references('id')->on('local_stock_subsections')->onDelete('cascade');
            $table->foreign('local_section_id')->references('id')->on('local_stock_sections')->onDelete('cascade');

            $table->unsignedBigInteger('fodder_subsection_id')->nullable();
            $table->unsignedBigInteger('fodder_section_id')->nullable();
            $table->foreign('fodder_subsection_id')->references('id')->on('fodder_sub_sections')->onDelete('cascade');
            $table->foreign('fodder_section_id')->references('id')->on('stock_fodder_sections')->onDelete('cascade');

            $table->unsignedBigInteger('tender_id')->nullable();
            $table->unsignedBigInteger('tender_section_id')->nullable();
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            $table->foreign('tender_section_id')->references('id')->on('tenders_sections')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('company_section_id')->nullable();
            $table->unsignedBigInteger('company_sub_section_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('company_section_id')->references('id')->on('guide_sections')->onDelete('cascade');
            $table->foreign('company_sub_section_id')->references('id')->on('guide_sub_sections')->onDelete('cascade');


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
        Schema::dropIfExists('meta_tags');
    }
}
