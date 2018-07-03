<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    // Don't use laravel auto incrementing primary key
    public $incrementing = false;
    
    // Do not use timestamp data fields in database
    public $timestamps = false;

    protected $primaryKey = 'party_id';
    
    protected $fillable = [
        "party_id",
        "fullname_el",
        "fullname_en",
        "image",
        "url",
        "members"
    ];

    public function memberships()
    {
        return $this->belongsTo('App\Membership', 'on_behalf_of_id', 'party_id');
    }
}
