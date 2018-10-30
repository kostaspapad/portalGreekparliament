<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issue');
            $table->string('speech_id');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });

        Schema::table('reports' , function($table){
            $table->foreign('speech_id')->references('speech_id')->on('speeches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports' , function($table){
            $table->dropForeign(['speech_id']);
            $table->dropForeign(['user_id']);
        });
        
        Schema::dropIfExists('reports');
    }
}
