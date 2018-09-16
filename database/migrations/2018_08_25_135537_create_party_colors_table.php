<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartyColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('parties')) {
            Schema::create('party_colors', function (Blueprint $table) {
                // Primary key 
                $table->string('party_id')->unique();
                $table->primary('party_id');

                // Holds color code
                $table->string('color');

                // Set db engine type
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
            });
        } else {
            echo "parties table does not exist" . PHP_EOL;
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
        Schema::dropIfExists('party_colors');
    }
}
