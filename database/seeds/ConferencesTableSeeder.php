<?php

use Illuminate\Database\Seeder;
use App\Models\Conference;

class ConferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds. 
     * 
     * php artisan db:seed --class=ConferencesTableSeeder
     *
     * @return void
     */
    public function run()
    {
        $conferences = DB::connection('mysql_scraper')->select('SELECT * FROM scraper_data');
        
        foreach($conferences as $c){
            // Conference::insert((array) $c);
            echo "---> " . $c->conference_date . PHP_EOL;
            echo "--->" . $c->id. PHP_EOL;
            echo "--->" . $c->conference_date. PHP_EOL;
            echo "--->" . $c->conference_indicator. PHP_EOL;
            echo "--->" . $c->doc_location. PHP_EOL;
            echo "--->" . urldecode($c->doc_name). PHP_EOL;
            echo "--->" . $c->video_link. PHP_EOL;
            echo "--->" . $c->session. PHP_EOL;
            echo "--->" . $c->date_of_crawl. PHP_EOL;
            echo "--->" . $c->pdf_loc. PHP_EOL;
            echo "--->" . $c->pdf_name. PHP_EOL;
            echo "--->" . $c->time_period. PHP_EOL;
            echo "--->" . $c->downloaded . PHP_EOL;
            // print_r($c);
            DB::table('conferences')->insert([
                "id" => $c->id,
                "conference_date" => $c->conference_date,
                "conference_indicator" => $c->conference_indicator,
                "doc_location" => $c->doc_location,
                "doc_name" => urldecode($c->doc_name),
                "video_link" => $c->video_link,
                "session" => $c->session,
                "date_of_crawl" => $c->date_of_crawl,
                "pdf_loc" => $c->pdf_loc,
                "pdf_name" => $c->pdf_name,
                "time_period" => $c->time_period,
                "downloaded" => $c->downloaded
            ]);
        }
    }
}
