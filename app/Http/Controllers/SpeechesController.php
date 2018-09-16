<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Speech;
use App\Models\Speaker;
use App\Http\Resources\Speech as SpeechResource;
use Illuminate\Support\Facades\Auth;

class SpeechesController extends Controller
{
    public function postFavoriteSpeech( $speech_id )
    {
        $speech = Speech::where('speech_id', '=', $speech_id)->first();

        if (!$speech->favorites->contains(Auth::user()->id)){
            /*
                If the user doesn't already favorite the speech, 
                attaches the speech to the user's favorites
            */
            $speech->favorites()->attach( Auth::user()->id, [
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

            return response()->json( ['speech_favorited' => true], 201 );
        }
    }

    public function deleteFavoriteSpeech( $speech_id )
    {
        $speech = Speech::where('speech_id', '=', $speech_id)->first();
        
        $speech->favorites()->detach( Auth::user()->id );
        
        return response(null, 204);
    }
}
