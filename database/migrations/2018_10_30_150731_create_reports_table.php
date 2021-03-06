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
            $table->bigInteger('speech_id');
            $table->foreign('speech_id')->references('speech_id')->on('speeches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');        
        Schema::dropIfExists('reports');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
