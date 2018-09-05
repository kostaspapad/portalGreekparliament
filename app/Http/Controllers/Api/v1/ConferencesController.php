<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Conference;
use App\Http\Resources\Conference as ConferenceResource;

class ConferencesController extends Controller {

    var $allowed_order_fields = ['conference_date', 'conference_indicator', 'session', 'time_period'];
    var $orientations = ['asc', 'desc'];

    public function __construct(Request $request) {
        // Get query parameters from Request object
        $this->order_field = $request->get('order_field');
        $this->order_orientation = $request->get('orientation');
    }

    public function validate_query_params() {
        // Query parameter validation
        if ($this->order_field && !in_array($this->order_field, $this->allowed_order_fields)){
            return ['Error' => 'Invalid order field'];
        }
        
        if ($this->order_orientation && !in_array($this->order_orientation, $this->orientations)){
            return ['Error' => 'Invalid order orientation'];
        }
        
        // Create dynamic field for query
        if (isset($this->order_field) && !empty($this->order_field)){
            $this->order_field = 'conferences.'.$this->order_field;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        

        if ($this->order_field && $this->order_orientation) {
            $conferences = Conference::orderBy($this->order_field, $this->order_orientation)
                ->paginate(20);
        } else {
            $conferences = Conference::paginate(20);
        }

        if (isset($conferences) && !empty($conferences)) {
            return ConferenceResource::collection($conferences);
        }
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
       
        if (isset($conference) && !empty($conference)) {
            return new ConferenceResource($conference);
        }
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

        if (isset($conferences) && !empty($conferences)) {
            return ConferenceResource::collection($conferences);
        }
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

    /**
    * @OA\Get(
    *   path="/api/v1/conference/from/{start}/to/{end}",
    *   summary="list products",
    *   @OA\Response(
    *     response=200,
    *     description="A list with products"
    *   ),
    *   @OA\Response(
    *     response="default",
    *     description="an ""unexpected"" error"
    *   )
    * )
    */
    public function getConferenceByDateRange($start, $end) {
        // Check if string for safety
        if (is_string($start) && is_string($end)) {
            if (isset($start) && isset($end)) {
                // Convert str to date obj
                $start = date($start);
                $end = date($end);
                
                // Check if valid range
                if ($start <= $end) {
                    $conferences = Conference::whereBetween('conference_date', [$start, $end])->get();
                }
            }

            if (isset($conferences) && !empty($conferences)) {
                return ConferenceResource::collection($conferences);
            }
        }
    }
}
