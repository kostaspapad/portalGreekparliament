<?php

namespace App\Http\Controllers\Api\v1\Speakers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speech;
use DB;
use App\Models\Speaker;
use App\Helpers\ApiHelper;

class SpeechesController extends Controller
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
     * Get speeches by speaker id
     *
     * @return App\Http\Resources\Speech
     */
    public function speechesBySpeakerId($speaker_id) 
    {
        $speaker_id = $this->apiHelper::test_input($speaker_id);

        $speech_ids = Speech::select('speeches.speech_id')
                ->where('speeches.speaker_id', '=', $speaker_id)
                ->get();

        $conversation_ids = array();

        // Get next and previous id
        foreach($speech_ids as $id){
            // echo $id->speech_id.PHP_EOL;
            $conversation_ids[] = $id->speech_id - 1;
            $conversation_ids[] = $id->speech_id;
            $conversation_ids[] = $id->speech_id + 1;
        }
        
        // Remove duplicates
        $conversation_ids = array_unique($conversation_ids);
        
        // Get speeches
        // [FUTURE] Show memberships defined by the date that the speech took place
        $speeches = Speech::join('speakers as sp', 'speeches.speaker_id', '=', 'sp.speaker_id')
                ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                ->select(['speeches.speech_conference_date', 'sp.greek_name', 'sp.english_name', 
                    'speeches.speech_id', 'speeches.speech', 'sp.image'
                    // 'm.on_behalf_of_id', 
                    // 'parties.fullname_el'
                ])
                ->groupBy('speeches.speech_id')
                ->where('sp.speaker_id', '=', $speaker_id)
                ->whereIn('speeches.speech_id', $conversation_ids)
                ->paginate(20);

        return $this->apiHelper::returnResource('Speech', $speeches);
    }

    /**
     * Get speeches by speaker id
     *
     * @return App\Http\Resources\Speech
     */
    public function speechesBySpeakerName($speaker_name) 
    {
        $speaker_name = $this->apiHelper::test_input($speaker_name);

        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'sp.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'sp.greek_name';
        }

        $speech_ids = Speech::select('speeches.speech_id')
                ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                ->where($name_lang, 'like', $speaker_name)
                ->get();

        $conversation_ids = array();

        // Get next and previous id
        foreach($speech_ids as $id) {
            // echo $id->speech_id.PHP_EOL;
            $conversation_ids[] = $id->speech_id - 1;
            $conversation_ids[] = $id->speech_id;
            $conversation_ids[] = $id->speech_id + 1;
        }
        
        // Remove duplicates
        $conversation_ids = array_unique($conversation_ids);
        
        // Get speeches
        // [FUTURE] Show memberships defined by the date that the speech took place
        $speeches = Speech::join('speakers as sp', 'speeches.speaker_id', '=', 'sp.speaker_id')
                ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                ->select(['speeches.speech_conference_date', 'sp.greek_name', 'sp.english_name', 
                    'speeches.speech_id', 'speeches.speech', 'sp.image'
                    // 'm.on_behalf_of_id', 
                    // 'parties.fullname_el'
                ])
                ->groupBy('speeches.speech_id')
                ->where('sp.greek_name', '=', $speaker_name)
                ->whereIn('speeches.speech_id', $conversation_ids)
                ->paginate(20);
        
        return $this->apiHelper::returnResource('Speech', $speeches);
    }

    public function searchSpeakerSpeeches(Request $request) 
    {
        
        // Id of speaker
        $speaker_id = $request->input('speaker_id');
        
        // Search string
        $input = $request->input('input');

        // validation
        $speaker_id = $this->apiHelper::test_input($speaker_id);

        $input = $this->apiHelper::test_input($input);

        if (!$this->apiHelper::validate_speaker_id($speaker_id)) {
            return response()->json([
                'message' => "Bad request.",
                'status' => 400
            ], 400);
        }
        
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

            $speeches = Speech::where('speaker_id', '=', $speaker_id)
                ->groupBy('speeches.speech_id')
                ->whereRaw($query)
                ->paginate(25);
            
            return $this->apiHelper::returnResource('Speech', $speeches);
        }
    }
}