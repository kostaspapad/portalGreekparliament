<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    // Don't use laravel auto incrementing primary key
    public $incrementing = false;

    public function speakers()
    {
        return $this->belongsTo('App\Speaker', 'speaker_id', 'speaker_id');
    }

    public function conferences()
    {
        return $this->belongsTo('App\Conference', 'conference_date', 'conference_date');
    }
}
