<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Conference extends JsonResource
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
            'id' => $this->id,
            'conference_date' => $this->conference_date,
            'conference_indicator' => $this->conference_indicator,
            'doc_location' => $this->doc_location,
            'doc_name' => $this->doc_name,
            'video_link' => $this->video_link,
            'session' => $this->session,
            'date_of_crawl' => $this->date_of_crawl,
            'pdf_loc' => $this->pdf_loc,
            'pdf_name' => $this->pdf_name,
            'time_period' => $this->time_period
            // 'downloaded' => $this->downloaded
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
