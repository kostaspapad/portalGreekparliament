<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterTopQuestionsC1 extends Model
{
    // Do not use timestamp data fields in database
    public $timestamps = false;

    public $incrementing = true;

    // Custom primary key
    public $primaryKey = 'qst_newsletter_id';

    public function TopQuestionsNewsletter()
    {
        // return $this->belongsTo('App\Post', 'foreign_key', 'other_key');
        return $this->belongsTo('App\Models\TopQuestionsNewsletter', 'qst_newsletter_date', 'qst_newsletter_date');
    }
}
