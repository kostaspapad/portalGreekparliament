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




/*
|-------------------------------------------------------------------------------
| Parties API endpoints
|-------------------------------------------------------------------------------
*/
/*
|-------------------------------------------------------------------------------
| Get all parties
|-------------------------------------------------------------------------------
| URL:            /api/parties
| Controller:     API\PartiesController@index
| Method:         GET
*/
Route::get('parties', 'Api\PartiesController@index');

/*
|-------------------------------------------------------------------------------
| Get data of a single party by id
|-------------------------------------------------------------------------------
| URL:            /api/party/{id}
| Controller:     API\PartiesController@getPartyById
| Method:         GET
*/
Route::get('Api/party/{id}', 'Api\PartiesController@getPartyById');

/*
|-------------------------------------------------------------------------------
| Get data of a single party by name
|-------------------------------------------------------------------------------
| URL:            /api/party/{name}
| Controller:     API\PartiesController@getPartyByName
| Method:         GET
*/
Route::get('Api/party/{name}', 'Api\PartiesController@getPartyByName');

/*
|-------------------------------------------------------------------------------
| Get speeches of a party with pagination
|-------------------------------------------------------------------------------
| URL:            /api/party/{id}/speeches
| Controller:     API\PartiesController@getPartySpeeches
| Method:         GET
*/
Route::get('party/{id}/speeches', 'Api\PartiesController@getPartySpeeches');

/*
|-------------------------------------------------------------------------------
|
| Speeches API endpoints
|
|-------------------------------------------------------------------------------
| Get all speeches with pagination of 25
|-------------------------------------------------------------------------------
| URL:            /api/speeches
| Controller:     API\SpeechesController@index
| Method:         GET
| Description:    Gets all of the speeches in the application
*/
Route::get('speeches', 'Api\SpeechesController@index');

/*
|-------------------------------------------------------------------------------
| Get a single speech by id
|-------------------------------------------------------------------------------
| URL:            /api/speech/{id}
| Controller:     API\SpeechesController@getSpeechById
| Method:         GET
*/
Route::get('speech/{id}', 'Api\SpeechesController@getSpeechById');

/*
|-------------------------------------------------------------------------------
| Delete a speech by id
|-------------------------------------------------------------------------------
| URL:            /api/speech/{id}
| Controller:     API\SpeechesController@getSpeechById
| Method:         DELETE
*/
Route::delete('speech/{id}', 'Api\SpeechesController@destroy');

// // Create new speech (FUTURE)
// Route::post('speech', 'Api\SpeechesController@store');

// // Create new speech (FUTURE)
// Route::put('speech', 'Api\SpeechesController@store');

/*
|-------------------------------------------------------------------------------
| Get speeches of a speaker by id
|-------------------------------------------------------------------------------
| URL:            /api/speeches/speaker/{speaker_id}
| Sample:         /api/speeches/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35
| Controller:     API\SpeechesController@speechesBySpeakerId
| Method:         GET
| Description:    Gets all of the speeches of a speaker specified by speaker_id
*/
Route::get('speeches/speaker/{speaker_id}', 'Api\SpeechesController@speechesBySpeakerId');

/*
|-------------------------------------------------------------------------------
| Get speeches of a speaker by name
|-------------------------------------------------------------------------------
| URL:            /api/speeches/speaker/name/{speaker_name}
| Sample:         http://localhost:8000/api/speeches/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος
| Controller:     API\SpeechesController@speechBySpeakerName
| Method:         GET
| Description:    Gets all of the speeches of a speaker specified by greek_name
|                 or english_name
*/
Route::get('speeches/speaker/name/{speaker_name}', 'Api\SpeechesController@speechesBySpeakerName');

/*
|-------------------------------------------------------------------------------
|
| Speakers API endpoints
|
|-------------------------------------------------------------------------------
| Get all speakers with pagination
|-------------------------------------------------------------------------------
| URL:            /api/speakers
| Controller:     API\SpeakersController@index
| Method:         GET
*/
Route::get('speakers', 'Api\SpeakersController@index');

/*
|-------------------------------------------------------------------------------
| Get a speaker by id
|-------------------------------------------------------------------------------
| URL:            /api/speaker/{speaker_id}
| Controller:     API\SpeechesController@getSpeakerById
| Method:         GET
*/
Route::get('speaker/{speaker_id}', 'Api\SpeakersController@getSpeakerById');

/*
|-------------------------------------------------------------------------------
| Get a speaker by name
|-------------------------------------------------------------------------------
| URL:            /api/speaker/{name}
| Controller:     API\SpeechesController@getSpeakerByName
| Method:         GET
*/
Route::get('speaker/name/{name}', 'Api\SpeakersController@getSpeakerByName');


/*
|-------------------------------------------------------------------------------
|
| Conferences API endpoints
|
|-------------------------------------------------------------------------------
| Get all conferences with pagination
|-------------------------------------------------------------------------------
| URL:            /api/conferences
| Controller:     API\ConferencesController@index
| Method:         GET
*/
Route::get('conferences', 'Api\ConferencesController@index');

/*
|-------------------------------------------------------------------------------
| Get conferences by id
|-------------------------------------------------------------------------------
| URL:            /api/conference/{id}
| Controller:     API\ConferencesController@getConferenceById
| Method:         GET
*/
Route::get('conference/{id}', 'Api\ConferencesController@getConferenceById');

/*
|-------------------------------------------------------------------------------
| Get conferences by date
|-------------------------------------------------------------------------------
| URL:            /api/conference/{date}
| Controller:     API\ConferencesController@getConferenceByDate
| Method:         GET
*/
Route::get('conference/{date}', 'Api\ConferencesController@getConferenceByDate');

/*
|-------------------------------------------------------------------------------
| Get conferences by date rage
|-------------------------------------------------------------------------------
| URL:            /api/conference/start/{date}/end/{date}
| Controller:     API\ConferencesController@getConferenceByDateRange
| Method:         GET
*/
Route::get('conference/start/{datestart}/end/{dateend}', 'Api\ConferencesController@getConferenceByDateRange');

/*
|-------------------------------------------------------------------------------
| Get conference speeches by id
|-------------------------------------------------------------------------------
| URL:            /api/conference/{id}/speeches
| Controller:     API\ConferencesController@getConferenceSpeechesById
| Method:         GET
*/
Route::get('conference/{id}/speeches', 'Api\ConferencesController@getConferenceSpeechesById');

/*
|-------------------------------------------------------------------------------
| Get conference speeches by date
|-------------------------------------------------------------------------------
| URL:            /api/conference/{date}/speeches
| Controller:     API\ConferencesController@getConferenceSpeechesByDate
| Method:         GET
*/
Route::get('conference/{id}/speeches', 'Api\ConferencesController@getConferenceSpeechesByDate');
