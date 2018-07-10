<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Speaker;
use App\Http\Resources\Speaker as SpeakerResource;

class SpeakersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get speakers
        $speakers = Speaker::paginate(15);

        if (isset($speakers) && !empty($speakers)) {
            return  SpeakerResource::collection($speakers);
        }
    }

    /**
     * Display a speakers data specified by id.
     *
     * example /speaker/0067f43b-4d76-46d5-89f2-6a71f7e236e8
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpeakerById($speaker_id){
        $speaker = Speaker::findorfail($speaker_id);

        if (isset($speaker) && !empty($speaker)) {
            return new SpeakerResource($speaker);
        }
    }
    
    public function getSpeakerByName($speaker_name){
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $speaker = Speaker::where('english_name', '=', $speaker_name)->first();
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $speaker = Speaker::where('greek_name', '=', $speaker_name)->first();
        }
        
        if (isset($speaker) && !empty($speaker)) {
            return new SpeakerResource($speaker);
        }
    }
    
    public function searchSpeakerByName($name){
        $name = '%'.$name.'%';

        if (mb_detect_encoding($name) == 'ASCII') {
            $speaker = Speaker::select('speakers.*')
                ->where('speakers.english_name', 'like', $name)
                ->paginate(50);

        } else if (mb_detect_encoding($name) == 'UTF-8') {
            $speaker = Speaker::select('speakers.*')
                ->where('speakers.greek_name', 'like', $name)
                ->paginate(50);
        }
        
        // Return the collection of Speeches as a resource
        if (isset($speaker) && !empty($speaker)) {
            return SpeakerResource::collection($speaker);
        }
    }
}
