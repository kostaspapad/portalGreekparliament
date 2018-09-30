<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Speech extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $arr = [
            'speech_id' => $this->speech_id,
            'speech_conference_date' => $this->speech_conference_date,
            'speaker_id' => $this->speaker_id,
            'speech' => $this->speech,
            'f_name' => $this->f_name,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'md5' => $this->md5,
            'speaker_id' => $this->speaker_id,
            'english_name' => $this->english_name,
            'greek_name' => $this->greek_name,
            'image' => $this->image,
            'email' => $this->email,
            'wiki_el' => $this->wiki_el,
            'wiki_en' => $this->wiki_en,
            'twitter' => $this->twitter,
            'website' => $this->website,
            'on_behalf_of_id' => $this->on_behalf_of_id,
            'fullname_el' => $this->fullname_el,
            'party_color' => $this->color,
            'favorite_user_id' => $this->favorite_user_id,
            'isFavorite' => $this->isFavorite
        ];
        
        // array_filter without second argument removes null elements of entry array
        return array_filter((array) $arr);
        
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
