<?php

use Illuminate\Database\Seeder;
use App\Conference;

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
            echo $c->ID.PHP_EOL;
            DB::table('conferences')->insert([
                "ID" => $c->ID,
                "Conference" => $c->Conference,
                "Date" => $c->Date,
                "DocumentLocation" => $c->DocumentLocation,
                "DocumentName" => urldecode($c->DocumentName),
                "RelatedVideosLink" => $c->RelatedVideosLink,
                "Session" => $c->Session,
                "DateOfCrawl" => $c->DateOfCrawl,
                "PDFdocumentLocation" => $c->PDFdocumentLocation,
                "PDFdocuments" => $c->PDFdocuments,
                "TimePeriod" => $c->TimePeriod,
                "DateOfCrawl" => $c->DateOfCrawl,
                "WebPageNum" => $c->WebPageNum,
                "MorningConf" => $c->MorningConf,
                "AfternoonConf" => $c->AfternoonConf,
                "Downloaded" => $c->Downloaded
            ]);
        }
    }
}
