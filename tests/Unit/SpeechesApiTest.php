<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpeechesApiTest extends TestCase
{
    public function testGetSpeechById()
    {
        $response = $this->json('GET', 'api/speech/200510131000');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "speech_id",
                        "speech_conference_date",
                        "speaker_id",
                        "speech",
                        "f_name",
                        "md5"
                    ]
                ]);

        // na kanw ton elenxo gia keno kai gia invalid
    }

    public function testSpeechesBySpeakerId()
    {
        $response = $this->json('GET', 'api/speeches/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35');
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
    }

    
    public function testSpeechesBySpeakerName()
    {
        $response = $this->json('GET', 'api/speeches/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος');
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
    }
}

