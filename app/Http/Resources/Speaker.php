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
        // array_filter without second argument removes null elements of entry array
        return array_filter((array) $this->resource);
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
