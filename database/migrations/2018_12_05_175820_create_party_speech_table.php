<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartySpeechTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('party_speech')) {
            if (Schema::hasTable('parties')){
                if (Schema::hasTable('speeches')){
                    Schema::create('party_speech', function (Blueprint $table) {
                        $table->string('speech_id');
                        $table->string('party_id');
                        $table->index(['speech_id', 'party_id']);

                        // Set db engine type
                        $table->engine = 'InnoDB';
                        $table->charset = 'utf8';
                        $table->collation = 'utf8_unicode_ci';
                    });

                } else {
                    echo 'Speeches table does not exist' . PHP_EOL;
                    die;
                }

            } else {
                echo 'Parties table does not exist' . PHP_EOL;
                die;
            }
        
        } else {
            echo 'Party_speech table already exists' . PHP_EOL;
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
        Schema::dropIfExists('party_speech');
    }
}
