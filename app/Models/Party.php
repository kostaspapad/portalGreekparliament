<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\PartyColor;
use App\Models\Membership;

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
        "url"
    ];

    public function memberships()
    {
        return $this->belongsTo('App\Models\Membership', 'on_behalf_of_id', 'party_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function party_colors()
    {
        // return $this->hasMany('App\Models\PartyColor', 'party_id', 'party_id');
        return $this->hasOne('App\Models\PartyColor');
    }


    /**
     * Get all of the speakers for a party
     */
    public function speakers()
    {
        // return $this->hasManyThrough('App\Models\Speaker', 'App\Models\Membership');
        // return $this->hasManyThrough(    <----- LATHOS
        //     'App\Models\Speaker',
        //     'App\Models\Membership',
        //     'party_id', // Foreign key on Membership table...
        //     'speaker_id', // Foreign key on Speaker table...
        //     'party_id', // Local key on countries table...
        //     'id' // Local key on Membership table...
        // );
    }
    
}
