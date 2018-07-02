<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpeechesApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSpeechById()
    {
        $response = $this->get('/speech/201005281353');

        $response->assertStatus(200);
    }
}
