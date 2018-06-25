<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeechesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('speeches')) {
            if (Schema::hasTable('conferences')) {
                if (Schema::hasTable('speakers')){
                    Schema::create('speeches', function (Blueprint $table) {
                        
                        // Primary key
                        $table->string('speech_id')->unique();
                        $table->primary('speech_id');

                        // Foreign key
                        $table->date('speech_conference_date');

                        // Create index
                        $table->index('speech_conference_date');
                        
                        $table->foreign('speech_conference_date')
                            ->references('conference_date')
                            ->on('conferences');
                            //->onDelete('set null');

                        // Foreign key
                        $table->string('speaker_id')->nullable();
                        $table->foreign('speaker_id')
                            ->references('speaker_id')
                            ->on('speakers')
                            ->onDelete('set null');

                        // Text
                        $table->text('speech');

                        // Speaker name
                        $table->string('f_name');

                        // Speech timestamp(when the speech parsed and inserted to db)
                        $table->timestamps();
                        
                        // MD5 hash generated from the speech text
                        $table->string('md5');


                        // Set db engine type
                        $table->engine = 'InnoDB';
                        
                    });

                } else {
                    echo 'Speakers table does not exist' . PHP_EOL;
                    die;
                }

            } else {
                echo 'conferences table does not exist' . PHP_EOL;
                die;
            }

        } else {
            echo 'Speech table already exists' . PHP_EOL;
            die;
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
        Schema::dropIfExists('speeches');
    }
}
