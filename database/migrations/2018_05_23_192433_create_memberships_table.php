<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if(!Schema::hasTable('memberships')){
            if (Schema::hasTable('parties')){
                if (Schema::hasTable('speakers')){
                    Schema::create('memberships', function (Blueprint $table) {
                        // Primary key 
                        $table->increments('id');

                        $table->string("area_id");

                        $table->string("legislative_period_id");

                        
                        // Foreigh key 
                        $table->string("on_behalf_of_id");
                        // Create index
                        $table->index('on_behalf_of_id');
                        // Create reference
                        $table->foreign('on_behalf_of_id')
                            ->references('party_id')
                            ->on('parties');

                        /* 
                         * Organization id is staying the same for all rows
                         * Refers to higher entities of the popolo database
                         */
                        $table->string("organization_id");

                        // Foreigh key 
                        $table->string("person_id");
                        // Create index
                        $table->index('person_id');
                        // Create reference
                        $table->foreign('person_id')
                            ->references('speaker_id')
                            ->on('speakers');

                        /*
                         * Role is staying the same for all rows
                         */
                        $table->string("role");

                        /*
                         * The date when the speaker started as a parliament member
                         * 
                         * When this field is empty start date is the date of the
                         * legislative period
                         */
                        $table->date("start_date")->nullable();

                        /*
                         * The date when the speaker stoped being a parliament member
                         * of the date when the speaker changed party
                         * 
                         */
                        $table->date("end_date")->nullable();
                        $table->engine = 'InnoDB';
                        $table->charset = 'utf8';
                        $table->collation = 'utf8_unicode_ci';
                    });
                    
                } else {
                    echo 'Speakers table does not exist' . PHP_EOL;
                    die;
                }
            } else {
                echo "parties table does not exist" . PHP_EOL;
                die;
            }
            // Schema::table('memberships', function($table) {
            //     $table->foreign('on_behalf_of_id')
            //         ->references('id_name')
            //         ->on('parties')
            //         ->onDelete('set null');
            // });

            // Schema::table('memberships', function($table) {
            //     $table->foreign('person_id')
            //         ->references('speaker_id')
            //         ->on('speakers')
            //         ->onDelete('set null');
            // });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('memberships');
    }
}
