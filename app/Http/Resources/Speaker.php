<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Speaker extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'speaker_id' => $this->speaker_id,
            'english_name' => $this->english_name,
            'greek_name' => $this->greek_name,
            'image' => $this->image,
            'email' => $this->email,
            'wiki_el' => $this->wiki_el,
            'wiki_en' => $this->wiki_en,
            'twitter' => $this->twitter,
            'website' => $this->website
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
        $response->header('Api-Version', '1');
    }
}
