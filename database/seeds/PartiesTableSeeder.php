<?php

use Illuminate\Database\Seeder;
use App\Party;

class PartiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parties')->delete();
        $json = File::get("database/data/political_parties.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            echo "insert -> " . $obj->party_id . PHP_EOL;
            Party::create(array(
                'party_id' => $obj->party_id,
                'fullname_el' => $obj->fullname_el,
                'fullname_en' => $obj->fullname_en,
                'image' => $obj->image,
                'url' => $obj->url
            ));
        }
    }
}
