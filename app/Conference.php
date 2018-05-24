<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;
}
