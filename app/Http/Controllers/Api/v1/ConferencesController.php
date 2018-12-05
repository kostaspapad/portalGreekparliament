<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use DB;
use App\Helpers\ApiHelper;
use App\Helpers\CacheExpiration;
use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Pagination\LengthAwarePaginator;

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
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }

        //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
        //CacheExpiration::checkCache('conferences',true,$current_page,0,0,true);

        $cache_conferences =  Cache::remember('conferences-'.$current_page, CacheExpiration::expiration(720), function() {
        
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

            return $conferences;        
        });
        
        return $this->apiHelper::returnResource('Conference', $cache_conferences);
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

    // get all time_periods
    public function conferencesPeriods(){
        $periods = Conference::
        // with('speeches')->has('speeches')->
        where('time_period' , '<>' , 'Empty')
        ->when($this->order_field && $this->order_orientation, function ($query) {
            $query->orderBy($this->order_field, $this->order_orientation);
        })
        ->groupBy('time_period')
        ->orderBy('time_period', 'desc')
        ->select(['time_period'])
        ->get();

        //return $periods;
        return $this->apiHelper::returnResource('Conference', $periods);
    }

    public function conferencesByPeriod(Request $request,$period){
        $period = '"' . $period . '"';
        // dd($period);
        if(isset($period)){
            if(isset($_GET['page'])){
                $current_page = $_GET['page'];
            }else{
                $current_page = 1;
            }
    
            //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
            //CacheExpiration::checkCache('conferences_time_periods_speeches',true,$current_page,$period,0,true);
    
            $cache_conferences_time_periods_speeches =  Cache::remember('conferences_time_periods_speeches-'.$period.'-'.$current_page, CacheExpiration::expiration(720), function() use ($period) {
                $period = Conference::has('speeches')
                // with('speeches')
                    ->when($this->order_field && $this->order_orientation, function ($query) {
                        $query->orderBy($this->order_field, $this->order_orientation);
                    })
                    ->whereRaw("time_period = ".$period." ")
                    ->groupBy('conferences.id')
                    ->select(['conference_date','time_period','session'])
                    ->paginate(10);
                return $period;
            });
            return $this->apiHelper::returnResource('Conference', $cache_conferences_time_periods_speeches);
        }
    }

    //to create custom paginate 
    //example $period = $this->arrayPaginator($period, $request);
    // public function arrayPaginator($array, $request)
    // {
    //     $page = $request->input('page', 1);
    //     $perPage = 10;
    //     $offset = ($page * $perPage) - $perPage;

    //     return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
    //         ['path' => $request->url(), 'query' => $request->query()]);
    // }

    public function getPartyCountByConference($conf_date)
    {
        //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
        //CacheExpiration::checkCache('count_party_speeches',true,0,$conf_date,0,false);
        $cache_count_party_speeches =  Cache::remember('count_party_speeches-'.$conf_date, CacheExpiration::expiration(720), function() use ($conf_date) {
            $count_party_speeches = DB::select(
                'SELECT 
                    conf_speeches.conference_date AS conference_date,
                    conf_speeches.on_behalf_of_id AS on_behalf_of_id,
                    conf_speeches.fullname_el AS fullname_el,
                    conf_speeches.greek_name,
                    conf_speeches.color,
                    COUNT(conf_speeches.on_behalf_of_id) AS party_count
                FROM
                    (SELECT 
                        conferences.conference_date AS conference_date,
                        memberships.on_behalf_of_id AS on_behalf_of_id,
                        speakers.greek_name,
                        memberships.start_date,
                        memberships.end_date,
                        parties.fullname_el AS fullname_el,
                        party_colors.color
                    FROM
                        speeches
                    INNER JOIN conferences ON conferences.conference_date = speeches.speech_conference_date
                    INNER JOIN speakers ON speakers.speaker_id = speeches.speaker_id
                    INNER JOIN memberships ON speakers.speaker_id = memberships.person_id
                    INNER JOIN parties ON parties.party_id = memberships.on_behalf_of_id
                    INNER JOIN party_colors ON party_colors.party_id = parties.party_id
                    WHERE
                        conferences.conference_date = :conf_date
                    ORDER BY speeches.speech_id
                    ) conf_speeches
                    WHERE conf_speeches.fullname_el = IF(
                                #if conf_date is between range return the party
                                (conf_speeches.conference_date >= conf_speeches.start_date AND conf_speeches.conference_date <= conf_speeches.end_date),
                                #then
                                conf_speeches.fullname_el
                                ,
                                #else if not in range check if conf_date is greater then start_date and end_date is NULL
                                #if true return party
                                IF( 
                                    (conf_speeches.conference_date >= conf_speeches.start_date AND conf_speeches.end_date IS NULL)
                                    #then
                                    ,conf_speeches.fullname_el
                                    #else
                                    ,NULL
                                )
                            )
                    GROUP BY conf_speeches.conference_date , conf_speeches.on_behalf_of_id',['conf_date' => $conf_date]
                );
            
            return $count_party_speeches;
        });
        
        return $cache_count_party_speeches;
    }
}

