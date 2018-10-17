<?php

namespace App\Http\Controllers\Api\v1\Conferences;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speech;
use DB;
use App\Models\Conference;
use App\Helpers\ApiHelper;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\ApiAuthController;

class SpeechesController extends Controller
{
    var $allowed_order_fields = ['conference_date', 'conference_indicator', 'session', 'time_period'];
    var $orientations = ['asc', 'desc'];

    public function __construct(Request $request) 
    {
        // Get query parameters from Request object
        $this->order_field = $request->get('order_field');
        $this->order_orientation = $request->get('orientation');
        $this->speech_count = $request->get('speech_count');

        // $this->validate_query_params();

        $this->apiHelper = new ApiHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        
    }

    /**
     * Get all speeches of a conference specified by date
     *
     * @param  str  $date
     * @return App\Http\Resources\Speech
     */
    public function speechesByConferenceDate($date)
    {
        $user = auth('api')->user();
        if (isset($date) && is_string($date)) {

            // Convert string to date object
            $date = date($date);
            if ($user) {
                
                $user_id = $user->id;
                
                // One speaker can be in many parties (check it later)
                $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                    ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                    ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                    ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                    ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                    ->leftJoin('favorites', function($join) use ($user_id){
                        $join->on('favorites.speech_id', '=', 'speeches.speech_id')
                             ->where('favorites.user_id', '=', $user_id);
                    })
                    ->select([
                        'conf.conference_date', 
                        'sp.greek_name', 
                        'sp.english_name', 
                        'speeches.speech_id', 
                        'speeches.speech', 
                        'sp.image', 
                        'm.on_behalf_of_id', 
                        'parties.fullname_el',
                        'party_colors.color',
                        'favorites.isFavorite'
                    ])
                    ->groupBy('speeches.speech_id')
                    ->where([
                        ['conf.conference_date', '=', $date]
                    ])
                    ->paginate(25);
                
            } else {
                // One speaker can be in many parties (check it later)
                $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                    ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                    ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                    ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                    ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                    ->select([
                        'conf.conference_date', 
                        'sp.greek_name', 
                        'sp.english_name', 
                        'speeches.speech_id', 
                        'speeches.speech', 
                        'sp.image', 
                        'm.on_behalf_of_id', 
                        'parties.fullname_el',
                        'party_colors.color'
                    ])
                    ->groupBy('speeches.speech_id')
                    ->where([
                        ['conf.conference_date', '=', $date],
                    ])
                    ->paginate(25);
            }

            return $this->apiHelper::returnResource('Speech', $speeches);
        }
    }
}