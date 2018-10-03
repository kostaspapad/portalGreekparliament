<?php

namespace App\Http\Controllers\Api\v1\Favorites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Speech;
use App\Models\Speaker;
use App\Http\Resources\Speech as SpeechResource;
use Illuminate\Support\Facades\Auth;
use DB;

class SpeechesController extends Controller
{
    public function store( Request $request )
    {
        $speech_id = $request->input('speech_id');
        // Get speech
        $speech = Speech::where('speech_id', '=', $speech_id)->first();

        /*
            If the user doesn't already favorite the speech, 
            attaches the speech to the user's favorites
        */
        // dd(
        //     //$speech->favorites()->where('user_id', '=', Auth::user()->id)->toSql()
        // );
        if($speech->favorites()->where('user_id', '=', Auth::user()->id)->first()){
            DB::table('favorites')
            ->where([
                ['user_id', '=' , Auth::user()->id],
                ['speech_id', '=', $speech_id]
            ])
            ->update(['isFavorite' => 1]);
            return response()->json( ['speech_favorited' => true], 201 );
        }else{

            if ($speech->favorites->whereNotIn('id', '=', Auth::user()->id)){
                $speech->favorites()->attach( Auth::user()->id, [
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'isFavorite'    => 1
                ]);
    
                return response()->json( ['speech_favorited' => true], 201 );
    
            } else {
                return response()->json([
                    'speech_favorited' => false,
                    'reason' => 'Allready favorited'
                ], 202 );
            }
        }
        
    }

    public function destroy( Request $request )
    {
        $speech_id = $request->input('speech_id');
        dd($speech_id);
        if ($speech_id) {

            $speech = Speech::where('speech_id', '=', $speech_id)->first();
            
            $speech->favorites()->detach( Auth::user()->id );
            
            return response(null, 204);
            
        } else {
            return response()->json([
                'speech_deleted' => false,
                'reason' => 'No speech id specified'
            ], 401 );
        }
    }
}
