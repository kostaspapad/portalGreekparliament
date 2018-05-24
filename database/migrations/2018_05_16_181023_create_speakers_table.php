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
            $table->primary('speaker_id');
            $table->string('english_name')->unique();
            $table->string('greek_name')->unique();
            $table->string('image');
            $table->string('email')->unique();
            $table->string('wiki_el');
            $table->string('wiki_en');
            $table->string('twitter');
            $table->string('website');
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
