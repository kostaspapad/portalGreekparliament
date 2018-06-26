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
        DB::table('memberships')->delete();
        $json = File::get("database/data/memberships.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
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
                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'start_date' => $obj->start_date
                ));
            }

            // Start date does not exist
            else if(!isset($obj->start_date) && isset($obj->end_date)){
                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role,
                    'end_date' => $obj->end_date
                ));
            }

            // Start date and End date does not exist
            else if(!isset($obj->start_date) && !isset($obj->end_date)){
                Membership::create(array(
                    'area_id' => $obj->area_id,
                    'legislative_period_id' => $obj->legislative_period_id,
                    'on_behalf_of_id' => $obj->on_behalf_of_id,
                    'organization_id' => $obj->organization_id,
                    'person_id' => $obj->person_id,
                    'role' => $obj->role
                ));
            }
            
            $counter++;
            echo $counter . " " . $obj->person_id . " -> inserted" . PHP_EOL;
        }
    }
}
