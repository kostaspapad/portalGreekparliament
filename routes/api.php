<?php

use Illuminate\Http\Request;
use App\Speaker;
use App\Conference;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('speakers', function(){
    #echo "sssweqw";die;
    $speakers = Speaker::orderBy('speaker_id','desc')->paginate(51);
    #$speakers = DB::table('speakers')->get();
    #print_r($speakers);die;
    return response()->json(compact('speakers'));
    #return response()->json($speakers);
    
});
// Route::get('conferences/{year?}/{month?}/{day?}', function($year,$month,$day){
//     $date =  $year."/".$month."/".$day;
//     $conferences = DB::table('conferences')->where('Date', '=', $date)->get();//Conference::select("SELECT * FROM conferences WHERE conferences.Date = '1992-05-05' ")->paginate(2);
//     //print_r($conferences);
//     return response()->json(compact('conferences'));
// })->where(['year' =>'[0-9]+', 'month' =>'[0-9]+', 'day' =>'[0-9]+']);
Route::get('conferences', function(){
    //print_r($_GET);
    $dates = $_GET['dates'];
    $conferences = DB::table('conferences')->whereBetween('conference_date', [$dates[0], $dates[1]])->get();//Conference::select("SELECT * FROM conferences WHERE conferences.Date = '1992-05-05' ")->paginate(2);
    //print_r($conferences);
    return response()->json(compact('conferences'));
});
// Route::get('conferences/{date}', function($date){
//     return $dates;
//     // $conferences = DB::table('conferences')->where('Date', '=', '1992-05-05')->get();//Conference::select("SELECT * FROM conferences WHERE conferences.Date = '1992-05-05' ")->paginate(2);
//     //print_r($conferences);
//     //return response()->json(compact('conferences'));
// });