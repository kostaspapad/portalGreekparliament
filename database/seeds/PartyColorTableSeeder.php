<?php

use Illuminate\Database\Seeder;
use App\PartyColor;

class PartyColorTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('party_colors')->delete();
        $json = File::get("database/data/party_colors.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            echo "insert -> " . $obj->party_id . ' color ' . $obj->color . PHP_EOL;
            PartyColor::create(array(
                'party_id' => $obj->party_id,
                'color' => $obj->color
            ));
        }
    }
}