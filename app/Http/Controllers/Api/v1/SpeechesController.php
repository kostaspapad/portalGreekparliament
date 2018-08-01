<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Speech;
use DB;
use App\Http\Resources\Speech as SpeechResource;

class SpeechesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\Speech
     */
    public function index()
    {
        // Get Speeches
        $speeches = Speech::paginate(25);

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\Speech
     */
    public function store(Request $request)
    {

        // THIS IS A SAMPLE ITS NOT GOING TO WORK THIS WAY!!!

        // $speech = $request->isMethod('put') ? Speech::findorfail(
        //     $request->speech_id) : new Speech;
        
        // $speech->speech_id = $request->input('speech_id');
        // $speech->speech_conference_date = $request->input('speech_conference_date');
        // $speech->speaker_id = $request->input('speaker_id');
        // $speech->speech = $request->input('speech');
        // $speech->f_name = $request->input('f_name');
        // $speech->md5 = $request->input('md5');
            
        // if (Speech->save()){
        //     return new SpeechResource($speech);
        // }            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return App\Http\Resources\Speech
     */
    public function getSpeechById($id)
    {
        $speech = Speech::findorfail($id);
            
        if (isset($speech) && !empty($speech)) {
            return new SpeechResource($speech);
        }
    }

    /**
     * Get speeches of a speaker specified by ID
     *
     * @param  int  $id
     * @return App\Http\Resources\Speech
     */
    public function speechesBySpeakerId($speaker_id)
    {
        $speeches = Speech::select('*')
            ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
            ->where('speakers.speaker_id', '=', $speaker_id)
            ->get();

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return SpeechResource::collection($speeches);
        }
    }

    /**
     * Get speeches of a speaker specified by name
     * 
     * Use this path for getting data using the english name
     * and the greek name of the speaker
     * 
     * @param  str  $speaker_name
     * @return App\Http\Resources\Speech
     */
    public function speechesBySpeakerName($speaker_name)
    {
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $speeches = Speech::select('speeches.*')
                ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
                ->where('speakers.english_name', '=', $speaker_name)
                ->get();

        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $speeches = Speech::select('speeches.*')
                ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
                ->where('speakers.greek_name', '=', $speaker_name)
                ->get();
        }

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }
    
    /**
     * Get speeches of a party specified by name
     * 
     * Use this path for getting data using the english name
     * and the greek name of the party
     * 
     * @param  str  $party_id
     * @return App\Http\Resources\Speech
     */
    public function speechesByPartyName($party_id)
    {
        // ASCII = english name
        // UTF-8 = greek name
        die;
        // $speeches = Speech::select('speeches.*')
        //     ->join('memberships', 'speeches.speaker_id', '=', 'memberships.person_id')
        //     ->where('memberships.on_behalf_of_id', '=', $party_id)
        //     ->get();
        
        // // DEN DOULEVEI! MEMORY ERROR!!
        // // Return the collection of Speeches as a resource
        // if (isset($speeches) && !empty($speeches)) {
        //     return  SpeechResource::collection($speeches);
        // }
    }

    /**
     * Get all speeches of a conference specified by date
     *
     * @param  str  $date
     * @return App\Http\Resources\Speech
     */
    public function getSpeechesByConferenceDate($date)
    {
        if (isset($date) && is_string($date))
        {
            $date = date($date);
            // One speaker can be in many parties (check it later)
            $speeches = Speech::join('conferences as conf', 'conf.conference_date', '=', 'speeches.speech_conference_date')
                ->join('speakers as sp', 'sp.speaker_id', '=', 'speeches.speaker_id')
                ->join('memberships as m', 'sp.speaker_id', '=' ,'m.person_id')
                ->join('parties', 'parties.party_id', '=', 'm.on_behalf_of_id')
                ->select(['conf.conference_date', 'sp.greek_name', 'sp.english_name', 'speeches.speech_id', 'speeches.speech', 'sp.image', 'm.on_behalf_of_id', 'parties.fullname_el'])
                ->groupBy('speeches.speech_id')
                ->where([
                    ['conf.conference_date', '=', $date],
                    // ['m.start_date', '>', $date],
                    // ['m.end_date', '<=', $date]
                ])
                ->paginate(25);

            if (isset($speeches) && !empty($speeches)) 
            {
                return SpeechResource::collection($speeches);
            }
        }
    }

    public function fulltextSpeechSearch($input)
    {
        if (isset($input) && is_string($input))
        {
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

            $speeches = Speech::whereRaw($query)
                ->paginate(25);
        }

        if (isset($speeches) && !empty($speeches))
        {
            return SpeechResource::collection($speeches);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return App\Http\Resources\Speech
     */
    public function destroy($id)
    {
        // MAYBE WORKS (FUTURE)
        // $speech = Speech::findorfail($id);

        // if ($speech->delete()){
        //     return new SpeechResource($speech);
        // }
    }
}
