<?php

namespace App\Models;

use FullTextSearch;
use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    // Don't use laravel auto incrementing primary key
    public $incrementing = false;

    // Custom primary key
    public $primaryKey = 'speech_id';

    public function speakers()
    {
        // return $this->hasMany('App\Comment', 'foreign_key', 'local_key');
        return $this->belongsTo('App\Speaker', 'speaker_id', 'speaker_id');
    }

    public function conferences()
    {
        // return $this->belongsTo('App\Post', 'foreign_key', 'other_key');
        return $this->belongsTo('App\Conference', 'speech_conference_date', 'conference_date');
    }
}
