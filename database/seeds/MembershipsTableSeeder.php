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
        DB::table('memberships')->delete();
        $json = File::get("database/data/memberships.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Membership::create(array(
                'area_id' => $obj->area_id,
                'legislative_period_id' => $obj->legislative_period_id,
                'on_behalf_of_id' => $obj->on_behalf_of_id,
                'organization_id' => $obj->organization_id,
                'person_id' => $obj->person_id,
                'role' => $obj->role
            ));
        }
    }
}
