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
     * Display a speakers data specified by id. If request query parameter
     * exists (?color=true) return the party color code
     *
     * example /speaker/0067f43b-4d76-46d5-89f2-6a71f7e236e8
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpeakerById($speaker_id, Request $request){
        
        if ($request->get('color') == true)
        {
            $speaker = Speaker::join('memberships as m', 'speakers.speaker_id', '=' ,'m.person_id')
                ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
                ->join('party_colors as c', 'c.party_id', '=', 'p.party_id')
                ->where('speakers.speaker_id', '=', $speaker_id)
                ->first();
        } else {
            $speaker = Speaker::findorfail($speaker_id);
        }

        if (isset($speaker) && !empty($speaker)) {
            return new SpeakerResource($speaker);
        }
    }
    
    /**
     * This function is returning a speaker, name can be greek(utf8) or
     * an english(ASCII)
     * 
     * If request query parameter exists (?color=true) return the party
     * color code
     */ 
    public function getSpeakerByName($speaker_name, Request $request){
        $name_lang = '';
        
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'speakers.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'speakers.greek_name';
        }

        if ($request->get('color') == true)
        {
            $speaker = Speaker::join('memberships as m', 'speakers.speaker_id', '=' ,'m.person_id')
                ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
                ->join('party_colors as c', 'c.party_id', '=', 'p.party_id')
                ->where($name_lang, '=', $speaker_name)
                ->first();
        } else {
            $speaker = Speaker::where($name_lang, '=', $speaker_name)->first();
        }
        
        if (isset($speaker) && !empty($speaker)) {
            return new SpeakerResource($speaker);
        }
    }
    
    /**
     * This function is returning a speaker, name can be greek(utf8) or
     * an english(ASCII)
     * 
     * If request query parameter exists (?color=true) return the party
     * color code
     * 
     * When using color parameter the response will contain all the 
     * memberships of a speaker that has changed more than one party
     */ 
    public function searchSpeakerByName($speaker_name, Request $request){
        $speaker_name = '%'.$speaker_name.'%';
        $name_lang = '';
        
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'speakers.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'speakers.greek_name';
        }

        if ($request->get('color') == true)
        {
            $speaker = Speaker::join('memberships as m', 'speakers.speaker_id', '=' ,'m.person_id')
                ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
                ->join('party_colors as c', 'c.party_id', '=', 'p.party_id')
                ->where($name_lang, 'like', $speaker_name)
                ->paginate(50);
        } else {
            $speaker = Speaker::select('speakers.*')
                ->where($name_lang, 'like', $speaker_name)
                ->paginate(50);
        }

        // Return the collection of Speeches as a resource
        if (isset($speaker) && !empty($speaker)) {
            return SpeakerResource::collection($speaker);
        }
    }
}
