<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use DB;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Conference;

class ConferencesController extends Controller {

    var $allowed_order_fields = ['conference_date', 'conference_indicator', 'session', 'time_period'];
    var $orientations = ['asc', 'desc'];

    public function __construct(Request $request) {
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
    public function validate_query_params() {
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
    public function index() {
        
        $conferences = Conference::join('speeches as sp', 'sp.speech_conference_date', '=', 'conferences.conference_date')
                        ->select('*', DB::raw('COUNT(sp.speech_conference_date) as speech_count'))
                        ->groupBy('conferences.conference_date');

        if ($this->order_field && $this->order_orientation) {
            $conferences = $conferences->orderBy($this->order_field, $this->order_orientation)
                ->paginate(20);
        } else {
            $conferences = $conferences->paginate(20);
        }
        
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
    public function getConferenceById($conference_id) {
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
    public function getConferenceByDate($conference_date) {
        $conferences = Conference::where('conference_date', '=', $conference_date)->get();

        return $this->apiHelper::returnResource('Conference', $conferences);
    }
    
    // /**
    //  * Display conference data specified by time period.
    //  *
    //  * example /conference/timeperiod/Ε' ΠΕΡΙΟΔΟΣ
    //  * 
    //  * @param  int  $time_period
    //  * @return \Illuminate\Http\Response
    //  */
    // public function getConferenceByTimePeriod($time_period)
    // {
    //     $conferences = Conference::findorfail($time_period);
    //     dd($conferences);

    //     if (isset($conference) && !empty($conference)) {
    //         return ConferenceResource::collection($conferences);
    //     }
    // }

    // /**
    //  * Display conference data specified by session.
    //  *
    //  * example /conference/Α' Σύνοδος
    //  * 
    //  * @param  int  $session
    //  * @return \Illuminate\Http\Response
    //  */
    // public function getConferenceBySession($session)
    // {
    //     $conferences = Conference::findorfail($session);

    //     if (isset($conference) && !empty($conference)) {
    //         return ConferenceResource::collection($conferences);
    //     }
    // }

    public function getConferenceByDateRange($start, $end) {
        // Check if string for safety
        if (is_string($start) && is_string($end)) {
            if (isset($start) && isset($end)) {

                // Convert str to date obj
                $start = date($start);
                $end = date($end);

                // Check if valid range
                if ($start <= $end) {
                    $conferences = Conference::join('speeches as sp', 'sp.speech_conference_date', '=', 'conferences.conference_date')
                        ->select('*', DB::raw('COUNT(sp.speech_conference_date) as speech_count'))
                        ->whereBetween('conference_date', [$start, $end])
                        ->groupBy('conference_date');

                    if ($this->order_field && $this->order_orientation) {
                        $conferences->orderBy($this->order_field, $this->order_orientation);
                    }

                    $conferences = $conferences->paginate(10);
                }
            }
            
            return $this->apiHelper::returnResource('Conference', $conferences);
        }
        
    }
}
