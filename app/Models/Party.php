<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\PartyColor;

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

    /**
     * Get the comments for the blog post.
     */
    public function party_colors()
    {
        return $this->hasMany('App\PartyColor', 'party_id', 'party_id');
    }


    /**
     * Get all of the speakers for a party
     */
    public function speakers()
    {
        return $this->hasManyThrough('App\Speaker', 'App\Membership');
    }
}
