<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
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
            'comment_id' => $this->comment_id,
            'comment' => $this->comment,
            'speech_id' => $this->speech_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'user_name' => $this->user_name
        ];

        // array_filter without second argument removes null elements of entry array
        return array_filter((array) $arr);
    }
}
