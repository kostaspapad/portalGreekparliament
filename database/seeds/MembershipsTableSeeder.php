<?php

use Illuminate\Database\Seeder;
use App\Membership;

class MembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $counter = 1;
        DB::table('memberships')->truncate();
        $memberships_json = File::get("database/data/memberships.json");
        $events_json = File::get("database/data/events.json");

        $data = json_decode($memberships_json);
        $events = json_decode($events_json);

        foreach ($data as $obj) {
            // print_r($obj);
            
            // Both dates exist
            if(isset($obj->start_date) && isset($obj->end_date)){
                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'start_date' => $obj->start_date,
                    'end_date' => $obj->end_date
                ));
            }

            // End date does not exist
            else if(isset($obj->start_date) && !isset($obj->end_date)){
                $obj->end_date = NULL;
                // When end date does not exist get end date from json events file
                // join event id with legislative_period_id and if exist get en date
                // If the legislative period has not ended end_date = null
                foreach($events as $ev){
                    if($ev->classification == "legislative period" && $ev->id == $obj->legislative_period_id){
                        if(isset($ev->end_date)){
                            $obj->end_date = $ev->end_date;
                        }
                    }
                }
                
                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'start_date' => $obj->start_date,
                    'end_date' => $obj->end_date
                ));
            }

            // Start date does not exist
            else if(!isset($obj->start_date) && isset($obj->end_date)){
                $obj->end_date = NULL;
                // When end date does not exist get end date from json events file
                // join event id with legislative_period_id and if exist get en date
                // If the legislative period has not ended end_date = null
                foreach($events as $ev){
                    if($ev->classification == "legislative period" && $ev->id == $obj->legislative_period_id){
                        if(isset($ev->start_date)){
                            $obj->start_date = $ev->start_date;
                        }
                    }
                }

                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'start_date' => $obj->start_date,
                    'end_date' => $obj->end_date
                ));
            }

            // Start date and End date does not exist
            // Get start date and end date from legislative period
            else if(!isset($obj->start_date) && !isset($obj->end_date)){
                $obj->start_date = NULL;
                $obj->end_date = NULL;
                foreach($events as $ev){
                    if($ev->classification == "legislative period" && $ev->id == $obj->legislative_period_id){
                        if(isset($ev->start_date)){
                            $obj->start_date = $ev->start_date;
                        }
                        if(isset($ev->end_date)){
                            $obj->end_date = $ev->end_date;
                        }
                    }
                }

                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'start_date' => $obj->start_date,
                    'end_date' => $obj->end_date
                ));
            }
            
            $counter++;
            echo $counter . ": OK" . PHP_EOL;
        }
    }
}
