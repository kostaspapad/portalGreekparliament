<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    // Don't use laravel auto incrementing primary key
    public $incrementing = false;

    // Do not use timestamp data fields in database
    public $timestamps = false;

    protected $fillable = [
        "speaker_id",
        "english_name",
        "image",
        "email",
        "wiki_el",
        "twitter",
        "greek_name",
        "wiki_en",
        "website"
    ];

    public function memberships()
    {
        return $this->belongsTo('App\Membership', 'speaker_id', 'person_id');
    }

    public function speeches()
    {
        return $this->hasMany('App\Speech', 'speaker_id', 'speaker_id');
    }
}
