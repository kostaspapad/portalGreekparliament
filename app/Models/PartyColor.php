<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Party;

class PartyColor extends Model
{
    // Don't use laravel auto incrementing primary key
    public $incrementing = false;
    
    // Do not use timestamp data fields in database
    public $timestamps = false;

    protected $primaryKey = 'party_id';

    /**
     * Get the party that owns the color.
     */
    public function party()
    {
        return $this->belongsTo('App\Models\Party', 'party_id', 'party_id');
    }
}
