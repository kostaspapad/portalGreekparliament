<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiHelper;

class ReportsController extends Controller
{
    var $apiHelper = null;

    public function __construct(Request $request) 
    {
        $this->apiHelper = new ApiHelper();
        
    }

    public function store( Request $request ){
        //dd($request);
        $report = new Report();
        //get the speech id we want to add the comment
        $report->speech_id = $request->speech_id;

        //report text
        $report->issue = $request->issue;

        //check and save user_id
        $report->user_id = $this->check_user_id();

        if(!$report->save()){
            App::abort(500, 'Error');
        }
        
        return response()->json([
            'msg' => 'Report Submitted',
            // 'data' => [
            //     'comment_id' => $comment->id,
            //     'comment' => $comment->comment,
            //     'speech_id' => $comment->speech_id,
            //     'user_id' => $comment->user_id,
            //     'created_at' => $comment->created_at,
            //     'user_name' => Auth::user()->name
            // ]
            // 'data' => $this->afterSaveGetRow($comment->id)
        ], 201 );
    }

    public function check_user_id(){
        if( Auth::user()->id ){
            return Auth::user()->id;
        }else{
            return null;
        }
    }
}
