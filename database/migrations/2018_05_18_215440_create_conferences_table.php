<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->string('conference_name');

            # e.x "2017/07/31"
            $table->date('conference_date');

            # Document location + document name e.x UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20170731000490.docx
            $table->string('transcript_doc_loc');

            $table->string('document_name');

            # e.x "/Vouli-ton-Ellinon/ToKtirio/Fotografiko-Archeio/#0e9a3d7f-ee81-4396-acc6-a7c200abaaa9"
            $table->string('related_video_link');

            # e.x "Β΄Σύνοδος"
            $table->string('session');

            # e.x "ΙΖ΄ ΠΕΡΙΟΔΟΣ (ΠΡΟΕΔΡΕΥΟΜΕΝΗΣ ΚΟΙΝΟΒΟΥΛΕΥΤΙΚΗΣ ΔΗΜΟΚΡΑΤΙΑΣ)"
            $table->string('time_period');

            # e.x "2017/08/22"
            $table->date('date_of_crawl');

            # e.x Document location + document name "none" or UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20110628.pdf
            $table->string('pdf_doc_loc');

            $table->string('pdf_document_name');

            # e.x 452
            $table->integer('web_page_number');

            # e.x True False
            $table->binary('is_morning_conference');

            # e.x True False
            $table->binary('is_afternoon_conference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conferences');
    }
}
