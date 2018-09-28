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
        return $this->belongsTo('App\Models\Speaker', 'speaker_id', 'speaker_id');
    }

    public function conferences()
    {
        // return $this->belongsTo('App\Post', 'foreign_key', 'other_key');
        return $this->belongsTo('App\Models\Conference', 'speech_conference_date', 'conference_date');
    }

    public function favorites(){
        return $this->belongsToMany('App\User', 'favorites', 'speech_id', 'user_id');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment' , 'speech_id' , 'speech_id');
    }
}
