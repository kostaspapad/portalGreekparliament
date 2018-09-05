<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConferencesApiTest extends TestCase
{
    var $api_version = 'api/v1';

    public function testGetAllConferences()
    {
        $endpoint = $this->api_version . '/conferences';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('conference_date');

        echo $endpoint . ': OK' . PHP_EOL;
    }
    
    public function testGetConferenceById()
    {
        $endpoint = $this->api_version . '/conference/100';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "id", 
                        "conference_date",
                        "conference_indicator",
                        "doc_location",
                        "doc_name",
                        "video_link",
                        "session",
                        "date_of_crawl",
                        "pdf_loc",
                        "pdf_name",
                        "time_period"
                    ]
                ]);
        
        echo $endpoint . ': OK' . PHP_EOL;
    }

    public function testGetConferenceByDate()
    {
        $endpoint = $this->api_version . '/conference/1989-07-03';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "id", 
                        "conference_date",
                        "conference_indicator",
                        "doc_location",
                        "doc_name",
                        "video_link",
                        "session",
                        "date_of_crawl",
                        "pdf_loc",
                        "pdf_name",
                        "time_period"
                    ]
                ]);
        
        echo $endpoint . ': OK' . PHP_EOL;
    }

    // public function testGetConferenceByTimePeriod()
    // {
    //     $endpoint = $this->api_version . '/conference/timeperiod/Ε\' ΠΕΡΙΟΔΟΣ';

    //     $response = $this->json('GET', $endpoint);
    //     $response->assertStatus(200)
    //              ->assertJsonStructure([
    //                 "data" => [
    //                     "id", 
    //                     "conference_date",
    //                     "conference_indicator",
    //                     "doc_location",
    //                     "doc_name",
    //                     "video_link",
    //                     "session",
    //                     "date_of_crawl",
    //                     "pdf_loc",
    //                     "pdf_name",
    //                     "time_period"
    //                 ]
    //             ]);
        
    //     echo $endpoint . ': OK' . PHP_EOL;
    // }

    // public function testGetConferenceBySession()
    // {
    //     $endpoint = $this->api_version . '/conference/session/Α\' Σύνοδος';

    //     $response = $this->json('GET', $endpoint);
    //     $response->assertStatus(200)
    //              ->assertJsonStructure([
    //                 "data" => [
    //                     "id", 
    //                     "conference_date",
    //                     "conference_indicator",
    //                     "doc_location",
    //                     "doc_name",
    //                     "video_link",
    //                     "session",
    //                     "date_of_crawl",
    //                     "pdf_loc",
    //                     "pdf_name",
    //                     "time_period"
    //                 ]
    //             ]);
        
    //     echo $endpoint . ': OK' . PHP_EOL;
    // }
    
    public function testGetConferenceByDateRange()
    {
        $endpoint = $this->api_version . '/conference/start/1989-07-03/end/1989-07-27';
        
        $response = $this->json('GET', $endpoint);
        $response->assertSee('conference_date');
        
        echo $endpoint . ': OK' . PHP_EOL;
    }
}
