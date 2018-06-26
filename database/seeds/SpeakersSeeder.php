<?php

use Illuminate\Database\Seeder;
use App\Speaker;


class SpeakersSeeder extends Seeder
{
    /**
     * Run database seed for speakers
     * 
     * Load data from json file
     *
     * @return void
     */
    public function run()
    {
        DB::table('speakers')->delete();
        $json = File::get("database/data/speakers_information.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            echo "insert -> " . $obj->greek_name . PHP_EOL;
            Speaker::create(array(
                'speaker_id' => $obj->speaker_id,
                'english_name' => $obj->english_name,
                'image' => $obj->image,
                'email' => $obj->email,
                'wiki_el' => $obj->wiki_el,
                'twitter' => $obj->twitter,
                'greek_name' => $obj->greek_name,
                'wiki_en' => $obj->wiki_en,
                'website' => $obj->website
            ));
        }
    }
}
