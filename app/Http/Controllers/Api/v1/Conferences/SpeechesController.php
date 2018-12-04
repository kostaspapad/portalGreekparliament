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
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheExpiration;

class SpeechesController extends Controller
{
    var $allowed_order_fields = ['conference_date', 'conference_indicator', 'session', 'time_period'];
    var $orientations = ['asc', 'desc'];
    var $prev_speech_id = null;

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

            if(isset($_GET['page'])){
                $current_page = $_GET['page'];
            }else{
                $current_page = 1;
            // Convert string to date object
            $date = date($date);
            if ($user) {
                
                $user_id = $user->id;
                
                // One speaker can be in many parties (check it later)
                $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                    ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                    ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
                    ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                    ->leftJoin('favorites', function($join) use ($user_id){
                        $join->on('favorites.speech_id', '=', 'speeches.speech_id')
                             ->where('favorites.user_id', '=', $user_id);
                    })
                    ->select([
                        'conf.conference_date', 
                        'sp.greek_name', 
                        'sp.english_name',
                        'sp.speaker_id', 
                        'speeches.speech_id', 
                        'speeches.speech',
                        'speeches.party_id', 
                        'sp.image', 
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
                    ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
                    ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                    ->select([
                        'conf.conference_date', 
                        'sp.greek_name', 
                        'sp.english_name',
                        'sp.speaker_id', 
                        'speeches.speech_id', 
                        'speeches.speech', 
                        'speeches.party_id',
                        'sp.image', 
                        'parties.fullname_el',
                        'party_colors.color'
                    ])
                    ->groupBy('speeches.speech_id')
                    ->where([
                        ['conf.conference_date', '=', $date],
                    ])
                    ->paginate(25);
            }

            //check if cache is set or not ($key,$seperator,$current_page,$main_var,$before_page,$isPagination)
           // CacheExpiration::checkCache('conference_speeches',true,$current_page,$date,0,true);
            $cache_conference_speeches =  Cache::remember('conference_speeches-'.$date.'-'.$current_page, CacheExpiration::expiration(720), function() use ($date,$user) {
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
                            'sp.speaker_id', 
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
                            'sp.speaker_id', 
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

                //check if is there a missing speech
                $speeches->transform(function ($item, $key) {
                    $currentLast3Chars = substr($item->speech_id, -3);
                    $res = $currentLast3Chars + 001; //is integer
                    if($this->prev_speech_id){
                        //if id is the next one ex: 000 -> 001
                        if($this->prev_speech_id + 001 == $currentLast3Chars){
                        
                        }else{
                            $item->missing_prev = true;
                        }
                        $this->prev_speech_id = $currentLast3Chars;
                        //must return the current object
                        return $item;
                    }else{
                        $this->prev_speech_id = $currentLast3Chars;
                        //must return the current object
                        return $item;
                    }
                });

                return $speeches;
            });
           // dd($speeches);

            return $this->apiHelper::returnResource('Speech', $cache_conference_speeches);
        }
    }

    public function fulltextSearchSpeechesByConferenceDate($date, $input)
    {
        if (isset($date) && is_string($date) && isset($input) && is_string($input)) {

            // Convert string to date object
            $date = date($date);

            $exp = explode(' ', $input);

            $s = '';
            $c = 1;
            foreach ($exp AS $e)
            {
                $s .= "+$e*";

                if ($c + 1 == count($exp))
                    $s .= ' ';

                $c++;
            }

            $query = "MATCH (speech) AGAINST ('$s' IN BOOLEAN MODE)";
            // $query looks like 
            // + is for AND 
            // - is for NOT
            // MATCH (speech) AGAINST ('+Μνημόνιο* +Σύριζα*' IN BOOLEAN MODE)

            $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
                ->select(['sp.speaker_id', 'conf.conference_date', 'sp.greek_name', 'sp.english_name', 'speeches.speech_id', 'speeches.speech', 'sp.image', 'speeches.party_id', 'parties.fullname_el'])
                ->groupBy('speeches.speech_id')
                ->whereRaw($query)
                ->where('speeches.speech_conference_date', '=', $date)
                ->paginate(25);
        }
        return $this->apiHelper::returnResource('Speech', $speeches);
    }
}