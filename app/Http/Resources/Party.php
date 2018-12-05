<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Party extends JsonResource
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
            'party_id' => $this->party_id,
            'fullname_el' => $this->fullname_el,
            'fullname_en' => $this->fullname_en,
            'image' => $this->image,
            'url' => $this->url
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
