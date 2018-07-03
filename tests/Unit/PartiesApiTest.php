<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartiesApiTest extends TestCase
{
    var $api_version = 'api/v1';

    public function testGetAllParties()
    {
        $endpoint = $this->api_version . '/parties';

        $response = $this->json('GET', $endpoint);
        $response->assertSee('party_id');

        echo $endpoint . ': OK' . PHP_EOL;
    }
    
    public function testGetPartyById()
    {
        $endpoint = $this->api_version . '/party/syriza';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "party_id",
                        "fullname_el",
                        "fullname_en",
                        "image",
                        "url"
                    ]
                ]);
        
        echo $endpoint . ': OK' . PHP_EOL;
    }
    
    public function testGetPartyByName()
    {
        $endpoint = $this->api_version . '/party/name/Κομμουνιστικό Κόμμα Ελλάδας';

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "data" => [
                        "party_id",
                        "fullname_el",
                        "fullname_en",
                        "image",
                        "url"
                    ]
                ]);
        
        echo $endpoint . ': OK' . PHP_EOL;
        
        $endpoint = $this->api_version . '/party/name/Communist Party of Greece';    
            
        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200)
                    ->assertJsonStructure([
                    "data" => [
                        "party_id",
                        "fullname_el",
                        "fullname_en",
                        "image",
                        "url"
                    ]
                ]);
        
        echo $endpoint . ': OK' . PHP_EOL;
    }

    // public function testGetPartySpeechesById()
    // {
        // $response = $this->json('GET', $version_endpoint . '/party/syriza/speeches');
        // $response->assertStatus(200)
        //          ->assertJsonStructure([
        //             "data" => [
        //                 "party_id",
        //                 "fullname_el",
        //                 "fullname_en",
        //                 "image",
        //                 "url"
        //             ]
        //         ]);
    // }
}
