<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->string("area_id");
            $table->string("legislative_period_id");
            $table->string("on_behalf_of_id")->references('id_name')->on('parties');
            $table->string("organization_id");
            $table->string("person_id")->references('id_name')->on('speakers');
            $table->string("role");
            $table->date("start_date");
            $table->date("end_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberships');
    }
}
