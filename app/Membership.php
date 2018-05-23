<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;

    protected $fillable = [
        "area_id",
        "legislative_period_id",
        "on_behalf_of_id",
        "organization_id",
        "person_id",
        "role"
    ];
}
