<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScraperdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraper_data', function (Blueprint $table) {
            $table->increments('id');
            $table->date('conference_date')->nullable();
            $table->string('time_period')->nullable();
            $table->string('session');
            $table->string('conference_indicator');
            $table->string('video_link')->nullable();
            $table->string('pdf_loc')->nullable();
            $table->text('pdf_name');
            $table->string('doc_location');
            $table->text('doc_name');
            $table->date('date_of_crawl')->nullable();
            $table->integer('morning_conference');
            $table->integer('noon_conference');
            $table->tinyInteger('downloaded')->default('0');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('scraper_data');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
