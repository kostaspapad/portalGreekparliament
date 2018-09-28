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
        
        //get the speech id we want to add the comment
        //$speech_id = $request->input('speech_id');
        
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->speech_id = $request->speech_id;
        //check and save user_id
        $comment->user_id = $this->check_user_id();

        if(!$comment->save()){
            App::abort(500, 'Error');
        }
        
        return response()->json([
            'msg' => 'Comment Sent',
            'data' => $this->afterSaveGetRow($comment->id)
        ], 201 );
    }

    public function show( Request $request , $speech_id){

        $comments = Comment::join('users','comments.user_id', '=', 'users.id')
            ->select([
                'comments.id as comment_id',
                'comments.comment',
                'comments.speech_id',
                'comments.user_id',
                'comments.created_at',
                'users.name as user_name'
            ])
            ->where('speech_id', '=', $speech_id)->get();
        
        return $this->apiHelper::returnResource('Comment', $comments);
    }

    public function afterSaveGetRow($comment_id) {
        $comment = Comment::join('users','comments.user_id', '=', 'users.id')
            ->select([
                'comments.id as comment_id',
                'comments.comment',
                'comments.speech_id',
                'comments.user_id',
                'comments.created_at',
                'users.name as user_name'
            ])
            ->where('comments.id', '=', $comment_id)->first();
        return $this->apiHelper::returnResource('Comment', $comment);
    }

    public function check_user_id(){
        if( Auth::user()->id ){
            return Auth::user()->id;
        }else{
            return null;
        }
    }
}
