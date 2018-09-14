<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    // Use laravel auto incrementing primary key
    public $incrementing = true;

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

    public function parties()
    {
       return $this->hasMany('App\Models\Party', 'id_name', 'on_behalf_of_id');
    }
    
    public function speakers()
    {
       // return $this->hasMany('App\Comment', 'foreign_key', 'local_key');
       return $this->hasMany('App\Models\Speaker', 'speaker_id', 'person_id');
    }

}
