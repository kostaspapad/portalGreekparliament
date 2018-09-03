<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Membership extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arr = [
            'area_id' => $this->area_id,
            'legislative_period_id' => $this->legislative_period_id,
            'on_behalf_of_id' => $this->on_behalf_of_id,
            'organization_id' => $this->organization_id,
            'role' => $this->role,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
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
