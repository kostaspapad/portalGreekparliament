<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('speaker_id');
            $table->string('english_name',200);
            $table->string('greek_name',200);
            $table->string('image',200);
            $table->string('email',254);
            $table->string('wiki_el',250);
            $table->string('wiki_en',250);
            $table->string('twitter',250);
            $table->string('website',250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speakers');
    }
}
