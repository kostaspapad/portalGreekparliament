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
            'SELECT 
                conf_speeches.conference_date AS conference_date,
                conf_speeches.on_behalf_of_id AS on_behalf_of_id,
                conf_speeches.fullname_el AS fullname_el,
                conf_speeches.color AS color,
                COUNT(conf_speeches.on_behalf_of_id) AS party_count
            FROM
                (SELECT 
                    conf.conference_date AS conference_date,
                        m.on_behalf_of_id AS on_behalf_of_id,
                        portal.parties.fullname_el AS fullname_el,
                        portal.party_colors.color AS color
                FROM
                    (((((portal.speeches
                JOIN portal.conferences conf ON (conf.conference_date = portal.speeches.speech_conference_date))
                JOIN portal.speakers sp ON (sp.speaker_id = portal.speeches.speaker_id))
                JOIN portal.memberships m ON (sp.speaker_id = m.person_id))
                JOIN portal.parties ON (portal.parties.party_id = m.on_behalf_of_id))
                JOIN portal.party_colors ON (portal.parties.party_id = portal.party_colors.party_id))
                GROUP BY portal.speeches.speech_id) conf_speeches
            WHERE conference_date = :conf_date
            GROUP BY conf_speeches.conference_date , conf_speeches.on_behalf_of_id'
        , ['conf_date' => $conf_date]);
        // 'SELECT * FROM count_party_speeches_by_conference WHERE conference_date = :conf_date',['conf_date' => $conf_date]
        return $count_party_speeches;
        //dd($count_party_speeches);
    }
}
