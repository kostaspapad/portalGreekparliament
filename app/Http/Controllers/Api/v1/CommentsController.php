<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Speech;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiHelper;

class CommentsController extends Controller
{
    var $apiHelper = null;

    public function __construct(Request $request) 
    {
        $this->apiHelper = new ApiHelper();
        
    }

    public function store( Request $request ){

        $comment = new Comment();
        $comment->comment = $request->comment;
        
        //get the speech id we want to add the comment
        $comment->speech_id = $request->speech_id;

        //check and save user_id
        $comment->user_id = $this->check_user_id();

        if(!$comment->save()){
            App::abort(500, 'Error');
        }
        
        return response()->json([
            'msg' => 'Comment Sent',
            'data' => [
                'comment_id' => $comment->id,
                'comment' => $comment->comment,
                'speech_id' => $comment->speech_id,
                'user_id' => $comment->user_id,
                'created_at' => $comment->created_at,
                'user_name' => Auth::user()->name
            ]
            // 'data' => $this->afterSaveGetRow($comment->id)
        ], 201 );
    }

    public function show( Request $request , $conference_date){

        // $comments = Comment::join('users','comments.user_id', '=', 'users.id')
        //     ->select([
        //         'comments.id as comment_id',
        //         'comments.comment',
        //         'comments.speech_id',
        //         'comments.user_id',
        //         'comments.created_at',
        //         'users.name as user_name'
        //     ])
        //     ->where('speech_id', '=', $speech_id)->get();
        $comments = Comment::join('users','comments.user_id', '=', 'users.id')
                    ->join('speeches', 'comments.speech_id', '=', 'speeches.speech_id')
                    ->join('conferences', 'speeches.speech_conference_date', '=', 'conferences.conference_date')
                    ->select([
                        'comments.id as comment_id',
                        'comments.comment',
                        'comments.speech_id',
                        'comments.user_id',
                        'comments.created_at',
                        'users.name as user_name'
                    ])
                    ->groupBy('speeches.speech_id', 'conferences.conference_date' , 'comments.id')
                    ->where('conferences.conference_date' , '=', $conference_date)
                    ->get();
        
        return $this->apiHelper::returnResource('Comment', $comments);
    }

    public function show_by_speech_id( Request $request , $speech_id){
        $comments = Comment::join('users','comments.user_id', '=', 'users.id')
                    ->join('speeches', 'comments.speech_id', '=', 'speeches.speech_id')
                    // ->join('conferences', 'speeches.speech_conference_date', '=', 'conferences.conference_date')
                    ->select([
                        'comments.id as comment_id',
                        'comments.comment',
                        'comments.speech_id',
                        'comments.user_id',
                        'comments.created_at',
                        'users.name as user_name'
                    ])
                    ->groupBy('speeches.speech_id' , 'comments.id')
                    ->where('speeches.speech_id' , '=', $speech_id)
                    ->get();
       // dd($comments);
        return $this->apiHelper::returnResource('Comment', $comments);
    }

    public function afterSaveGetRow($comment_id) {

        // $comment = Comment::join('users','comments.user_id', '=', 'users.id')
        //     ->select([
        //         'comments.id as comment_id',
        //         'comments.comment',
        //         'comments.speech_id',
        //         'comments.user_id',
        //         'comments.created_at',
        //         'users.name as user_name'
        //     ])
        //     ->where('comments.id', '=', $comment_id)->first();
        // return $this->apiHelper::returnResource('Comment', $comment);
    }

    public function check_user_id(){
        if( Auth::user()->id ){
            return Auth::user()->id;
        }else{
            return null;
        }
    }
}
