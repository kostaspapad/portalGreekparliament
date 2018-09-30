<?php
namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Speech;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiHelper;

class DashboardController extends Controller
{
    var $apiHelper = null;
    var $user = null;

    public function __construct(Request $request) 
    {
        //$this->apiHelper = new ApiHelper();
        $this->user = $this->setUserData();
    }

    public function getData( Request $request ){
        
        //$count_comments = Comment::where('user_id', '=' , Auth::user()->id)->count();
        // $test = Speech::with('comments')->toSql();
        // dd($test);
        $count_comments = $this->getCountOfComments();
        //dd($count_comments);
        return response()->json([
            'data' => $count_comments
        ], 200 );
    }

    public function getCountOfComments(){
        // $count = DB::table('comments')
        //     ->select('speech_id', DB::raw('count(*) as total_comments'))
        //     ->where('user_id', '=' , Auth::user()->id)
        //     ->groupBy('speech_id')
        //     ->get();
        if($this->user){
            $count = DB::select(
                    DB::raw('
                        SELECT comments.comment,conferences.conference_date,favorites.isFavorite,speeches.speech_id,COUNT(*) total_comments from comments
                            INNER JOIN
                        speeches ON comments.speech_id = speeches.speech_id
                            INNER JOIN
                        conferences ON speeches.speech_conference_date = conferences.conference_date
                            LEFT JOIN 
                        favorites ON comments.speech_id = favorites.speech_id
                        WHERE 
                            comments.user_id = '. $this->user->id .'
                        GROUP BY comments.speech_id
                    ')
                );
            //dd($count);
            return $count;
        }else{
            App::abort(500, 'Error');
        }
    }

    public function setUserData(){
        $user = auth('api')->user();
        return $user;
    }
    
}
