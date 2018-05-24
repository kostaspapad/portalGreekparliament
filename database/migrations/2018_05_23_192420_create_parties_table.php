<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_name')->unique();
            $table->string('fullname_el');
            $table->string('fullname_en');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('members'); <<<<< MIPOS NA FIGI??
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parties');
    }
}
