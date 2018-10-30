<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        "issue",
        "speech_id",
        "user_id"
    ];

    public function speech()
    {
        return $this->belongsTo('App\Models\Speech' , 'speech_id' , 'speech_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id' , 'user_id');
    }
}
