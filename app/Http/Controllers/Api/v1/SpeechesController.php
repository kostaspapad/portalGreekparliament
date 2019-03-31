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
                ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
                ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                ->leftJoin('favorites', 'favorites.speech_id', '=', 'speeches.speech_id')
                ->select([
                    'conf.conference_date', 
                    'sp.greek_name', 
                    'sp.english_name', 
                    'speeches.speech_id', 
                    'speeches.speech', 
                    'sp.image', 
                    'speeches.party_id', 
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
        }else{
            // One speaker can be in many parties (check it later)
            $speech = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
                ->join('party_colors', 'party_colors.party_id', '=', 'parties.party_id')
                ->select([
                    'conf.conference_date', 
                    'sp.greek_name', 
                    'sp.english_name', 
                    'speeches.speech_id', 
                    'speeches.speech', 
                    'sp.image', 
                    'speeches.party_id', 
                    'parties.fullname_el',
                    'party_colors.color'
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

    public function search_all_speeches(Request $request)
    {
        if (!$request->isMethod('post')) {
            return ['Error' => 'Method must be of type POST'];
        } 
        
        if (!$request->filled('tags')) {
            return ['Error' => 'No tags specified'];
        }

        // $data = json_decode($request->getContent(), true));
        if (!$data = json_decode($request->getContent(), true)) {
            return ['Error' => 'Cannot process request'];
        }

        $speakers = array();
        $parties = array();
        $tags = '';
        
        if ($data['tags']) {
            foreach ($data['tags'] as $value) {
                $tags .= $value['name'] . ' ';
            }
            // Trim last space
            $tags = rtrim($tags);
        }

        if ($data['speakers']) {
            foreach ($data['speakers'] as $value) {
                $speakers[] = $value;
            }
        }

        if ($data['parties']) {
            foreach ($data['parties'] as $value) {
                $parties[] = $value;
            }
        }

        // bool flag for range of dates
        $date_range = false;

        $single_date = null;
        $date_range_start = null;#date('1989-07-12');
        $date_range_end = null;#date('1989-07-17');

        // Get and convert string to date object
        if ($single_date = $data['singleDate']) {
            $single_date = date($single_date);
            $date_range = false;
        }
        
        if ($date_range_start = $data['dateRange']['startDate']) {
            $date_range_start = date($date_range_start);
        }
        if ($date_range_end = $data['dateRange']['endDate']) {
            $date_range_end = date($date_range_end);
            $date_range = true;
            $single_date = null;
        }

        # validate sanitize
        if ($tags) {
            $exp = explode(' ', $tags);
    
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
        }
        
        $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
            ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
            ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
            ->select(['sp.speaker_id', 'speeches.speech_conference_date', 'sp.greek_name', 'sp.english_name', 'speeches.speech_id', 'speeches.speech', 'sp.image', 'speeches.party_id', 'parties.fullname_el'])
            ->groupBy('speeches.speech_id')
            ->whereRaw($query);

        if ($date_range) {
            $speeches->whereBetween('conf.conference_date', array($date_range_start, $date_range_end));
        }
        if ($single_date) {
            $speeches->where('conf.conference_date', '=', $single_date);
        }
        if ($speakers) {
            $speeches->whereIn('sp.speaker_id', $speakers);
        }
        if ($parties) {
            $speeches->whereIn('parties.party_id', $parties);
        }
        
        // if ($this->order_field && $this->order_orientation) {
        //     $speeches->orderBy($this->order_field, $this->order_orientation);
        // }

        // dd($speeches->toSql());
        $speeches = $speeches->paginate(25);
        
        return $this->apiHelper::returnResource('Speech', $speeches);
        
    }

    // public function fulltextSpeechSearch($input)
    // {
    //     if (isset($input) && is_string($input)) {
    //         $exp = explode(' ', $input);

    //         $s = '';
    //         $c = 1;
    //         foreach ($exp AS $e)
    //         {
    //             $s .= "+$e*";

    //             if ($c + 1 == count($exp))
    //                 $s .= ' ';

    //             $c++;
    //         }

    //         $query = "MATCH (speech) AGAINST ('$s' IN BOOLEAN MODE)";
    //         // $query looks like 
    //         // + is for AND 
    //         // - is for NOT
    //         // MATCH (speech) AGAINST ('+Μνημόνιο* +Σύριζα*' IN BOOLEAN MODE)

    //         $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
    //             ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
    //             ->join('parties', 'parties.party_id', '=', 'speeches.party_id')
    //             ->select(['sp.speaker_id', 'conf.conference_date', 'sp.greek_name', 'sp.english_name', 'speeches.speech_id', 'speeches.speech', 'sp.image', 'speeches.party_id', 'parties.fullname_el'])
    //             ->groupBy('speeches.speech_id')
    //             ->whereRaw($query)
    //             ->paginate(25);
    //     }

    //     return $this->apiHelper::returnResource('Speech', $speeches);
    // }

    public function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
