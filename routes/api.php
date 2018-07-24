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

Route::get('conferences', function(){
    $dates = $_GET['dates'];
    $conferences = DB::table('conferences')->whereBetween('conference_date', [$dates[0], $dates[1]])->get();//Conference::select("SELECT * FROM conferences WHERE conferences.Date = '1992-05-05' ")->paginate(2);
    //print_r($conferences);
    return response()->json(compact('conferences'));
});

// Run tests with ./vendor/bin/phpunit (Install phpunit with composer)
Route::group(['prefix' => 'v1'], function () {
    /*
    |-------------------------------------------------------------------------------
    | Parties API endpoints
    |-------------------------------------------------------------------------------
    */
    /*
    |-------------------------------------------------------------------------------
    | Get all parties
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/parties
    | Controller:     api\v1\PartiesController@index
    | Method:         GET
    */
    Route::get('parties', 'Api\v1\PartiesController@index');

    /*
    |-------------------------------------------------------------------------------
    | Get data of a single party by id
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/party/{id}
    | Controller:     api\v1\PartiesController@getPartyById
    | Method:         GET
    */
    Route::get('party/{id}', 'Api\v1\PartiesController@getPartyById');

    /*
    |-------------------------------------------------------------------------------
    | Get data of a single party by name
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/party/{name}
    | Controller:     api\v1\PartiesController@getPartyByName
    | Method:         GET
    */
    Route::get('party/name/{name}', 'Api\v1\PartiesController@getPartyByName');
    
    /*
    |-------------------------------------------------------------------------------
    | Get speeches of a party with pagination
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/party/{id}/speeches
    | Controller:     api\v1\PartiesController@getPartySpeeches
    | Method:         GET
    */
    // Route::get('party/{id}/speeches', 'Api\v1\PartiesController@getPartySpeeches');

    /*
    |-------------------------------------------------------------------------------
    |
    | Speeches API endpoints
    |
    |-------------------------------------------------------------------------------
    | Get all speeches with pagination of 25
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speeches
    | Controller:     api\v1\SpeechesController@index
    | Method:         GET
    | Description:    Gets all of the speeches in the application
    */
    Route::get('speeches', 'Api\v1\SpeechesController@index');

    /*
    |-------------------------------------------------------------------------------
    | Get a single speech by id
    |-------------------------------------------------------------------------------
    | URL:            /api/speech/{id}
    | Sample:         http/v1://localhost:8000/api/speech/200510131000
    | Controller:     api\v1\SpeechesController@getSpeechById
    | Method:         GET
    */
    Route::get('speech/{id}', 'Api\v1\SpeechesController@getSpeechById');

    /*
    |-------------------------------------------------------------------------------
    | Delete a speech by id
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speech/{id}
    | Controller:     api\v1\SpeechesController@getSpeechById
    | Method:         DELETE
    */
    Route::delete('speech/{id}', 'Api\v1\SpeechesController@destroy');

    // // Create new speech (FUTURE)
    // Route::post('speech', 'Api\SpeechesController@store');

    // // Create new speech (FUTURE)
    // Route::put('speech', 'Api\SpeechesController@store');

    /*
    |-------------------------------------------------------------------------------
    | Get speeches of a speaker by id
    |-------------------------------------------------------------------------------
    | URL:            /api/speeches/speaker/{speaker_id}
    | Sample:         /api/v1/speeches/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35
    | Controller:     api\v1\SpeechesController@speechesBySpeakerId
    | Method:         GET
    | Description:    Gets all of the speeches of a speaker specified by speaker_id
    */
    Route::get('speeches/speaker/{speaker_id}', 'Api\v1\SpeechesController@speechesBySpeakerId');

    /*
    |-------------------------------------------------------------------------------
    | Get speeches of a speaker by name
    |-------------------------------------------------------------------------------
    | URL:            /api/speeches/speaker/name/{speaker_name}
    | Sample:         http/v1://localhost:8000/api/speeches/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος
    | Controller:     api\v1\SpeechesController@speechBySpeakerName
    | Method:         GET
    | Description:    Gets all of the speeches of a speaker specified by greek_name
    |                 or english_name
    */
    Route::get('speeches/speaker/name/{speaker_name}', 'Api\v1\SpeechesController@speechesBySpeakerName');

    /*
    |-------------------------------------------------------------------------------
    | Get speeches of a party by party id
    |-------------------------------------------------------------------------------
    | URL:            /api/speeches/party/{speaker_id}
    | Sample:         http/v1://localhost:8000/api/speeches/party/syriza
    | Controller:     api\v1\SpeechesController@speechBySpeakerName
    | Method:         GET
    | Description:    Gets all of the speeches of a speaker specified by greek_name
    |                 or english_name
    */
    Route::get('speeches/party/{party_id}', 'Api\v1\SpeechesController@speechesByPartyName');

    /*
    |-------------------------------------------------------------------------------
    |
    | Speakers API endpoints
    |
    |-------------------------------------------------------------------------------
    | Get all speakers with pagination
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speakers
    | Controller:     api\v1\SpeakersController@index
    | Method:         GET
    */
    Route::get('speakers', 'Api\v1\SpeakersController@index');

    /*
    |-------------------------------------------------------------------------------
    | Get a speaker by id
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speaker/{speaker_id}
    | Controller:     api\v1\SpeechesController@getSpeakerById
    | Method:         GET
    */
    Route::get('speaker/{speaker_id}', 'Api\v1\SpeakersController@getSpeakerById');

    /*
    |-------------------------------------------------------------------------------
    | Get a speaker by name
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speaker/{name}
    | Controller:     api\v1\SpeechesController@getSpeakerByName
    | Method:         GET
    */
    Route::get('speaker/name/{name}', 'Api\v1\SpeakersController@getSpeakerByName');

    /*
    |-------------------------------------------------------------------------------
    | Search for speaker by name
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/speakers/search/{name}
    | Controller:     api\v1\SpeechesController@searchSpeakerByName
    | Method:         POST
    */
    Route::get('speakers/search/{name}', 'Api\v1\SpeakersController@searchSpeakerByName');

    /*
    |-------------------------------------------------------------------------------
    |
    | Conferences API endpoints
    |
    |-------------------------------------------------------------------------------
    | Get all conferences with pagination
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conferences
    | Controller:     api\v1\ConferencesController@index
    | Method:         GET
    */
    Route::get('conferences', 'Api\v1\ConferencesController@index');

    /*
    |-------------------------------------------------------------------------------
    | Get conferences by id
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{id}
    | Controller:     api\v1\ConferencesController@getConferenceById
    | Method:         GET
    */
    Route::get('conference/{id}', 'Api\v1\ConferencesController@getConferenceById');

    /*
    |-------------------------------------------------------------------------------
    | Get conferences by date
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{date}
    | Controller:     api\v1\ConferencesController@getConferenceByDate
    | Method:         GET
    */
    Route::get('conference/date/{date}', 'Api\v1\ConferencesController@getConferenceByDate');

    /*
    |-------------------------------------------------------------------------------
    | Get conferences by session
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{session}
    | Controller:     api\v1\ConferencesController@getConferenceBySession
    | Method:         GET
    */
    Route::get('conference/session/{session}', 'Api\v1\ConferencesController@getConferenceBySession');

    /*
    |-------------------------------------------------------------------------------
    | Get conferences by time period
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{timeperiod}
    | Controller:     api\v1\ConferencesController@getConferenceByTimePeriod
    | Method:         GET
    */
    Route::get('conference/timeperiod/{timeperiod}', 'Api\v1\ConferencesController@getConferenceByTimePeriod');
    
    /*
    |-------------------------------------------------------------------------------
    | Get conferences by date rage
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/start/{date}/end/{date}
    | Sample          /api/v1/conference/start/1989-07-03/end/1989-07-27
    | Controller:     api\v1\ConferencesController@getConferenceByDateRange
    | Method:         GET
    */
    Route::post('conference/range', 'Api\v1\ConferencesController@getConferenceByDateRange');

    /*
    |-------------------------------------------------------------------------------
    | Get conference speeches by id
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{id}/speeches
    | Controller:     api\v1\ConferencesController@getConferenceSpeechesById
    | Method:         GET
    */
    // Route::get('conference/{id}/speeches', 'Api\v1\ConferencesController@getConferenceSpeechesById');

    /*
    |-------------------------------------------------------------------------------
    | Get conference speeches by date
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/conference/{date}/speeches
    | Controller:     api\v1\ConferencesController@getConferenceSpeechesByDate
    | Method:         GET
    */
    Route::get('conference/{date}/speeches', 'Api\v1\SpeechesController@getSpeechesByConferenceDate');
});