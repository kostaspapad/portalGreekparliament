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
                        "speech",
                        "greek_name",
                        "image",
                        "fullname_el",
                        "party_color"
                    ]
                ]);

        // na kanw ton elenxo gia keno kai gia invalid
    }

    public function testSpeechesBySpeakerId()
    {
        $endpoint = $this->api_version . '/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35/speeches';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speech_id');

    }

    
    public function testSpeechesBySpeakerName()
    {
        $endpoint = $this->api_version . '/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος/speeches';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
    }

    public function testSpeechesByConferenceDate()
    {
        $endpoint = $this->api_version . '/conference/2010-05-28/speeches';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('speech_id');

        // na kanw ton elenxo gia keno kai gia invalid
    }
}

