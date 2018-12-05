<?php

namespace App\Http\Controllers\Api\v1\Speakers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speech;
use DB;
use App\Models\Speaker;
use App\Models\Conference;
use App\Helpers\ApiHelper;
use App\Helpers\CacheExpiration;
use Illuminate\Support\Facades\Cache;

class ConferenceController extends Controller
{
    var $allowed_order_fields = ['greek_name', 'english_name', 'fullname_el'];
    var $orientations = ['asc', 'desc'];

    public function __construct(Request $request) 
    {
        // Get query parameters from Request object
        $this->order_field = $request->get('order_field');
        $this->order_orientation = $request->get('orientation');
        $this->speech_count = $request->get('speech_count');

        $this->validate_query_params();
        $this->apiHelper = new ApiHelper();
    }

    /**
     * Validate query parameters if exist.
     */
    public function validate_query_params() 
    {
        // Query parameter validation
        if ($this->order_field && !in_array($this->order_field, $this->allowed_order_fields)) {
            return ['Error' => 'Invalid order field'];
        }
        
        if ($this->order_orientation && !in_array($this->order_orientation, $this->orientations)) {
            return ['Error' => 'Invalid order orientation'];
        }

        // Create dynamic field for query
        if (isset($this->order_field) && !empty($this->order_field)) {
            $this->order_field = 'conferences.'.$this->order_field;
        }
    }

    /**
     * Get conferences by speaker id
     *
     * @return App\Http\Resources\Speech
     */
    public function conferencesBySpeakerId($speaker_id) 
    {
        if(isset($speaker_id)){
            //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
            //CacheExpiration::checkCache('speaker_profile_conferences',true,0,$speaker_id,0,false);
    
            $cache_speaker_profile_conferences =  Cache::remember('speaker_profile_conferences-'.$speaker_id, CacheExpiration::expiration(720), function() use ($speaker_id) {
                $speaker_id = $this->apiHelper::test_input($speaker_id);

                $conferences = Conference::join('speeches' , 'speeches.speech_conference_date' , '=' , 'conferences.conference_date')
                    ->join('speakers' ,'speakers.speaker_id', '=','speeches.speaker_id')
                    ->where('speakers.speaker_id', '=', $speaker_id)
                    ->groupBy('conferences.conference_date')
                    ->orderBy('conferences.conference_date', 'desc')
                    ->select(['conferences.conference_date'])
                    ->get();
                //dd($conferences);
                return $conferences;
            });
            return $this->apiHelper::returnResource('Conference', $cache_speaker_profile_conferences);
        }
    }

    /**
     * Get conferences by speaker id
     *
     * @return App\Http\Resources\Speech
     */
    public function speechesByConferenceDate($conf_date,$speaker_id) 
    {
        if(isset($speaker_id) && isset($conf_date)){
            $speaker_id = $this->apiHelper::test_input($speaker_id);
            $conf_date = $this->apiHelper::test_input($conf_date);
    
            $speeches = Speech::join('conferences' , 'conferences.conference_date' , '=' , 'speeches.speech_conference_date')
            ->join('speakers as sp', 'speeches.speaker_id', '=', 'sp.speaker_id')
            ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
            ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
            ->select(['speeches.speech_conference_date', 'sp.greek_name', 'sp.english_name', 
                'speeches.speech_id', 'speeches.speech', 'sp.image', 'sp.speaker_id'
                // 'm.on_behalf_of_id', 
                // 'parties.fullname_el'
            ])
            ->groupBy('speeches.speech_id')
            ->where([
                ['conferences.conference_date', '=', $conf_date],
                ['sp.speaker_id', '=', $speaker_id]
            ])
            ->orderBy('speeches.speech_id', 'desc')
            ->paginate(5);
            //dd($speeches);
            return $this->apiHelper::returnResource('Speech', $speeches);      
        }
    }

}