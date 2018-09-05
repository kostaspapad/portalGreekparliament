<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpeakersApiTest extends TestCase
{
    var $api_version = 'api/v1';
    
    public function testIndex()
    {
        $endpoint = $this->api_version . '/speakers';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speaker_id');

        echo $endpoint . ': OK' . PHP_EOL;
    }
    
    public function testGetSpeakerById()
    {
        $endpoint = $this->api_version . '/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "speaker_id",
                        "greek_name"
                    ]
                ]);

        echo $endpoint . ': OK' . PHP_EOL;
    }
    
    public function testGetSpeakerByName()
    {
        $endpoint = $this->api_version . '/speaker/name/Κωνσταντίνος Στεφανόπουλος';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "speaker_id",
                        "greek_name"
                    ]
                ]);
        
        echo $endpoint . ': OK'.PHP_EOL;
    }
    
    // public function testSearchSpeakerByName()
    // {
        
    //     $response = $this->post( 'api/speakers/search/', 'Κωνσταντίνος');
    //     $response->assertSee('speaker_id');
    // }

}
