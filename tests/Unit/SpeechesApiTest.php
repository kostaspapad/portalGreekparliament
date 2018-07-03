<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpeechesApiTest extends TestCase
{
    var $api_version = 'api/v1';

    public function testGetSpeechById()
    {
        $endpoint = $this->api_version . '/speech/200510131000';

        $response = $this->json('GET', $endpoint);
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
        echo $endpoint . ': OK' . PHP_EOL;
    }

    public function testSpeechesBySpeakerId()
    {
        $endpoint = $this->api_version . '/speeches/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
        echo $endpoint . ': OK' . PHP_EOL;
    }

    
    public function testSpeechesBySpeakerName()
    {
        $endpoint = $this->api_version . '/speeches/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
        echo $endpoint . ': OK' . PHP_EOL;
    }
}

