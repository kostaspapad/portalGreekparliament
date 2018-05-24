<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;

    protected $fillable = [
        "id_name",
        "fullname_el",
        "fullname_en",
        "image",
        "url",
        "members"
    ];

    public function memberships()
    {
        return $this->belongsTo('App\Membership', 'id_name', 'on_behalf_of_id');
    }
}
