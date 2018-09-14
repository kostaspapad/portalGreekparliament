<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;

    // protected $primaryKey = 'id';

    public function speeches()
    {
        return $this->hasMany('App\Speech', 'speech_conference_date', 'conference_date');
    }
}
