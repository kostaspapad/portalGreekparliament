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
    { Na do ti 8a kanw me ta drops cascade ktlp
        Schema::create('speeches', function (Blueprint $table) {
            $table->primary('speech_id');
            $table->string('speaker_id')->references('speaker_id')->on('speakers');
            $table->text('speech');
            $table->date('conference_date')->reference('conference_date')->on('conferences')
            $table->string('f_name');
            $table->string('md5');
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
        Schema::dropIfExists('speeches');
    }
}
