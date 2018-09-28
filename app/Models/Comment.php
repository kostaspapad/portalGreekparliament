<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Do not use timestamp data fields in database
    //public $timestamps = false;

    // protected $primaryKey = 'id';

    protected $fillable = [
        "comment",
        "speech_id",
        "user_id"
    ];

    public function speech()
    {
        return $this->belongsTo('App\Models\Speech' , 'speech_id' , 'speech_id');
    }
}
