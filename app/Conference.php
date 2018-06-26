<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;

    public function speeches()
    {
        return $this->hasMany('App\Speech', 'conference_date', 'conference_date');
    }
}
