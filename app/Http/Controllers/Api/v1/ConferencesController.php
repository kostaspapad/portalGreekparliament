<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Conference;
use App\Http\Resources\Conference as ConferenceResource;

class ConferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conferences = Conference::paginate(50);

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
    public function getConferenceById($conference_id)
    {
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
    public function getConferenceByDate($conference_date)
    {
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
}
