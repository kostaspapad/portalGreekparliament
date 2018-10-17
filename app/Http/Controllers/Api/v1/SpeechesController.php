<?php

namespace App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speech;
use DB;
use App\Helpers\ApiHelper;

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
            $this->order_field = 'speeches.'.$this->order_field;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\Speech
     */
    public function index()
    {
        // Get Speeches
        $speeches = Speech::paginate(25);

        return $this->apiHelper::returnResource('Speech', $speeches);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return App\Http\Resources\Speech
     */
    public function getSpeechById($id)
    {
        // $speech = Speech::findorfail($id);
        $user = auth('api')->user();
        if ($user) {
            // One speaker can be in many parties (check it later)
            $speech = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                ->leftJoin('favorites', 'favorites.speech_id', '=', 'speeches.speech_id')
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
                    ['speeches.speech_id', '=', $id]
                    //,['favorites.user_id', '=', $user->id]
                ])
                // ->orWhere('favorites.speech_id', '=', NULL)
                // ->orWhere('favorites.speech_id', '!=', NULL)
                ->first();
        } 
        
        return $this->apiHelper::returnResource('Speech', $speech);
    }

    public function fulltextSpeechSearch($input)
    {
        if (isset($input) && is_string($input)) {
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
                ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                ->select(['sp.speaker_id', 'conf.conference_date', 'sp.greek_name', 'sp.english_name', 'speeches.speech_id', 'speeches.speech', 'sp.image', 'm.on_behalf_of_id', 'parties.fullname_el'])
                ->groupBy('speeches.speech_id')
                ->whereRaw($query)
                ->paginate(25);
        }

        return $this->apiHelper::returnResource('Speech', $speeches);
    }

    public function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
