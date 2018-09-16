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
        if (!Schema::hasTable('conferences')) {
            if (!Schema::hasTable('speeches')){
                Schema::create('conferences', function (Blueprint $table) {
                    
                    $table->increments('id');
                    
                    # e.x "2017/07/31"
                    $table->date('conference_date');
                    // $table->foreign('conference_date')
                    //     ->references('conference_date')
                    //     ->on('speeches')
                    //     ->onDelete('set null');
            
                    $table->string('conference_indicator');

                    # Document location + document name e.x UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20170731000490.docx
                    $table->string('doc_location');

                    $table->string('doc_name', 300);

                    # e.x "/Vouli-ton-Ellinon/ToKtirio/Fotografiko-Archeio/#0e9a3d7f-ee81-4396-acc6-a7c200abaaa9"
                    $table->string('video_link');

                    # e.x "Β΄Σύνοδος"
                    $table->string('session');
                    
                    # e.x "2017/08/22"
                    $table->date('date_of_crawl');
                    
                    # e.x Document location + document name "none" or UserFiles/a08fc2dd-61a9-4a83-b09a-09f4c564609d/es20110628.pdf
                    $table->string('pdf_loc');
                    
                    $table->string('pdf_name');
                    
                    # e.x "ΙΖ΄ ΠΕΡΙΟΔΟΣ (ΠΡΟΕΔΡΕΥΟΜΕΝΗΣ ΚΟΙΝΟΒΟΥΛΕΥΤΙΚΗΣ ΔΗΜΟΚΡΑΤΙΑΣ)"
                    $table->string('time_period');

                    # e.x True False
                    $table->boolean('downloaded');

                    // Create index
                    $table->index('conference_date');

                    $table->engine = 'InnoDB';
                    $table->charset = 'utf8';
                    $table->collation = 'utf8_unicode_ci';
                });

            } else {
                echo 'speeches table does not exist' . PHP_EOL;
                die;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('conferences', function(Blueprint $table)
        // {
        //     //Put the index back when the migration is rolled back
        //     $table->dropIndex('conference_date');
        // });
        
        Schema::dropIfExists('conferences');
    }
}
