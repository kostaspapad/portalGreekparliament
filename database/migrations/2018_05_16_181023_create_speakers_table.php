<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeakersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if(!Schema::hasTable('speakers')){
            Schema::create('speakers', function (Blueprint $table) {
                // Primary key
                $table->string('speaker_id')->unique();
                $table->primaryKey = 'speaker_id';

                $table->string('english_name');
                $table->string('greek_name');
                $table->string('simple_name');
                $table->string('image');
                $table->string('email');
                $table->string('wiki_el');
                $table->string('wiki_en');
                $table->string('twitter');
                $table->string('website');
                $table->index(['simple_name', 'speaker_id']);
                $table->index(['greek_name', 'speaker_id']);
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
            });
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('speakers');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
