<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpeakersApiTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->json('GET', 'api/speakers');
        $response->assertSee('speaker_id');



    }
    
    public function testGetSpeakerById()
    {
        $response = $this->json('GET', 'api/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "speaker_id",
                        "english_name",
                        "greek_name",
                        "image",
                        "email",
                        "wiki_el",
                        "wiki_en",
                        "twitter",
                        "website"
                    ]
                ]);
    }
    
    public function testGetSpeakerByName()
    {
        $response = $this->json('GET', 'api/speaker/name/Κωνσταντίνος Στεφανόπουλος');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "speaker_id",
                        "english_name",
                        "greek_name",
                        "image",
                        "email",
                        "wiki_el",
                        "wiki_en",
                        "twitter",
                        "website"
                    ]
                ]);
    }
    
    // public function testSearchSpeakerByName()
    // {
        
    //     $response = $this->post( 'api/speakers/search/', 'Κωνσταντίνος');
    //     $response->assertSee('speaker_id');
    // }

}
