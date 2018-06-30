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
        return [
            'speech_id' => $this->speech_id,
            'speech_conference_date' => $this->speech_conference_date,
            'speaker_id' => $this->speaker_id,
            'speech' => $this->speech,
            'f_name' => $this->f_name,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'md5' => $this->md5
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
        return [
            'version' => '1.0.0'
        ];
    }
}
