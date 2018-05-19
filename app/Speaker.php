<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
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
}
