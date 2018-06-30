<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Speech;
use App\Speaker;
use App\Http\Resources\Speech as SpeechResource;

class SpeechController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Speeches
        $speeches = Speech::paginate(15);

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $speech = Speech::findorfail($id);

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function speechBySpeakerId($speaker_id)
    {
        $speeches = Speech::select('speeches.*')
            ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
            ->where('speakers.speaker_id', '=', $speaker_id)
            ->get();

        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }

    /**
     * Display the specified resource.
     * 
     * Use this path for getting data using the english name
     * and the greek name of the speaker
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function speechBySpeakerName($speaker_name)
    {
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $speeches = Speech::select('speeches.*')
                ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
                // ->select('speeches.*', 'contacts.phone', 'orders.price')
                ->where('speakers.english_name', '=', $speaker_name)
                ->get();

        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $speeches = Speech::select('speeches.*')
                ->join('speakers', 'speeches.speaker_id', '=', 'speakers.speaker_id')
                // ->select('speeches.*', 'contacts.phone', 'orders.price')
                ->where('speakers.greek_name', '=', $speaker_name)
                ->get();
        }
        
        // Return the collection of Speeches as a resource
        if (isset($speeches) && !empty($speeches)) {
            return  SpeechResource::collection($speeches);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
