<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartiesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('parties')) {
            Schema::create('parties', function (Blueprint $table) {
                /*
                * Primary key 
                */
                $table->increments('id');
                $table->string('id_name')->unique();
                $table->string('fullname_el');
                $table->string('fullname_en');
                $table->string('image')->nullable();
                $table->string('url')->nullable();
                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('parties');
    }
}
