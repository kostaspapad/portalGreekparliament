<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use DB;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Support\Facades\Cache;

class ConferencesController extends Controller 
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
            $this->order_field = 'conferences.'.$this->order_field;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {

        // $conferences =  Cache::remember('conferencesIndex', 22*60, function() {
        
            $conferences = Conference::with('speeches')
                ->when($this->order_field && $this->order_orientation, function ($query) {
                    $query->orderBy($this->order_field, $this->order_orientation);
                })
                ->has('speeches')
                ->select([
                    'conferences.id', 
                    'conferences.conference_date',
                    'conferences.session',
                    'conferences.time_period'
                ])
                ->withCount('speeches')
                ->groupBy('conferences.id')
                ->paginate(10);
                    
        // });
        
        return $this->apiHelper::returnResource('Conference', $conferences);
    }

    
    /**
     * Display a conference data specified by id.
     *
     * example /conference/1
     * 
     * @param  int  $conference_id
     * @return \Illuminate\Http\Response
     */
    public function conferenceById($conference_id) 
    {
        $conference = Conference::findorfail($conference_id);

        return $this->apiHelper::returnResource('Conference', $conference);
    }

    /**
     * Display a conference data specified by date.
     *
     * example /conference/1989-07-03
     * 
     * @param  int  $conference_date
     * @return \Illuminate\Http\Response
     */
    public function conferenceByDate($conference_date) 
    {
        $conference = Conference::where('conference_date', '=', $conference_date)->first();

        return $this->apiHelper::returnResource('Conference', $conference);
    }

    public function conferencesByDateRange($start, $end) 
    {
        if (isset($start) && isset($end)) {

            // Convert str to date obj
            $start = date($start);
            $end = date($end);

            // Check if valid range
            if ($start <= $end) {
                $conferences = Conference::with('speeches')
                    ->has('speeches')
                    ->when($this->order_field && $this->order_orientation, function ($query) {
                        $query->orderBy($this->order_field, $this->order_orientation);
                    })
                    ->select([
                        'conferences.id', 
                        'conferences.conference_date',
                        'conferences.session',
                        'conferences.time_period'
                    ])
                    ->whereBetween('conferences.conference_date', array($start, $end))
                    ->withCount('speeches')
                    ->groupBy('conferences.id')
                    ->paginate(10);
            }
            
            return $this->apiHelper::returnResource('Conference', $conferences);
        }
    }

    public function getPartyCountByConference(Request $request, $conf_date)
    {
        $count_party_speeches = DB::select(
            'SELECT sp.speech_conference_date, p.party_id, fullname_el, pc.color, COUNT(p.party_id) AS party_count
            FROM portal.speeches sp
            INNER JOIN portal.memberships mem ON mem.person_id = sp.speaker_id
            INNER JOIN portal.parties p ON mem.on_behalf_of_id = p.party_id
            INNER JOIN portal.party_colors pc ON p.party_id = pc.party_id
            WHERE sp.speech_conference_date = :conf_date
            GROUP BY p.party_id', ['conf_date' => $conf_date]
        );
        
        return $count_party_speeches;
    }
}
