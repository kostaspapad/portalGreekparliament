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
            $table->increments('ID');

            $table->string('Conference');

            # e.x "2017/07/31"
            $table->date('Date');

            # Document location + document name e.x UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20170731000490.docx
            $table->string('DocumentLocation');

            $table->string('DocumentName', 300);

            # e.x "/Vouli-ton-Ellinon/ToKtirio/Fotografiko-Archeio/#0e9a3d7f-ee81-4396-acc6-a7c200abaaa9"
            $table->string('RelatedVideosLink');

            # e.x "Β΄Σύνοδος"
            $table->string('Session');

            # e.x "ΙΖ΄ ΠΕΡΙΟΔΟΣ (ΠΡΟΕΔΡΕΥΟΜΕΝΗΣ ΚΟΙΝΟΒΟΥΛΕΥΤΙΚΗΣ ΔΗΜΟΚΡΑΤΙΑΣ)"
            $table->string('TimePeriod');

            # e.x "2017/08/22"
            $table->date('DateOfCrawl');

            # e.x Document location + document name "none" or UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20110628.pdf
            $table->string('PDFdocumentLocation');

            $table->string('PDFdocuments');

            # e.x 452
            $table->integer('WebPageNum');

            # e.x True False
            $table->boolean('MorningConf');

            # e.x True False
            $table->boolean('AfternoonConf');

            # e.x True False
            $table->boolean('Downloaded');
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
