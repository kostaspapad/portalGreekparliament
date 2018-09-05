<?php

use Illuminate\Http\Request;
use App\Speaker;
use App\Conference;

/**
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
  */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Throttle specifies how many requests per minute
// Run tests with ./vendor/bin/phpunit (Install phpunit with composer)
Route::group(['middleware' => ['throttle:30,1'], 'prefix' => 'v1'], function () {

  /**
    *-------------------------------------------------------------------------------
    * Memberships API endpoints
    *-------------------------------------------------------------------------------
    *
    *-------------------------------------------------------------------------------
    * Get a speaker memberships by id
    *-------------------------------------------------------------------------------
    * Sample:         http://localhost:8000/api/v1/memberships/speaker/002483ab-3653-4458-b410-6b0ee380cc76/
    * Controller:     api\v1\MembershipsController@getMembershipsBySpeakerId
    * Method:         GET
    */
    Route::get('memberships/speaker/{speaker_id}/', 'Api\v1\MembershipsController@getMembershipsBySpeakerId');

    /**
      *-------------------------------------------------------------------------------
      * Get a speaker memberships by name
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/memberships/speaker/name/Αλέξης Τσίπρας
      * Controller:     api\v1\MembershipsController@getMembershipsBySpeakerName
      * Method:         GET
      */
    Route::get('memberships/speaker/name/{name}', 'Api\v1\MembershipsController@getMembershipsBySpeakerName');
  
  
    /**
      *-------------------------------------------------------------------------------
      * Parties API endpoints
      *-------------------------------------------------------------------------------
      *
      *-------------------------------------------------------------------------------
      * Get all parties
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\PartiesController@index
      * Method:         GET
      */
    Route::get('parties', 'Api\v1\PartiesController@index');

    /**
      *-------------------------------------------------------------------------------
      * Get data of a single party by id
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\PartiesController@getPartyById
      * Method:         GET
      */
    Route::get('party/{id}', 'Api\v1\PartiesController@getPartyById');

    /**
      *-------------------------------------------------------------------------------
      * Get data of a single party by name
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\PartiesController@getPartyByName
      * Method:         GET
      */
    Route::get('party/name/{name}', 'Api\v1\PartiesController@getPartyByName');
    
    /**
      *-------------------------------------------------------------------------------
      * Get speeches of a party with pagination
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\PartiesController@getPartySpeeches
      * Method:         GET
      */
    // Route::get('party/{id}/speeches', 'Api\v1\PartiesController@getPartySpeeches');

    /**
      *-------------------------------------------------------------------------------
      *
      * Speeches API endpoints
      *
      *-------------------------------------------------------------------------------
      * Get all speeches with pagination of 25
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\SpeechesController@index
      * Method:         GET
      * Description:    Gets all of the speeches in the application
      */
    Route::get('speeches', 'Api\v1\SpeechesController@index');

    /**
      *-------------------------------------------------------------------------------
      * Get a single speech by id
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/speech/200510131000
      * Controller:     api\v1\SpeechesController@getSpeechById
      * Method:         GET
      */
    Route::get('speech/{id}', 'Api\v1\SpeechesController@getSpeechById');

    /**
      *-------------------------------------------------------------------------------
      * Get speeches of a speaker by id
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/speeches/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35
      * Controller:     api\v1\SpeechesController@speechesBySpeakerId
      * Method:         GET
      * Description:    Gets all of the speeches of a speaker specified by speaker_id
      */
    Route::get('speeches/speaker/{speaker_id}', 'Api\v1\SpeechesController@getSpeechesBySpeakerId');

    /**
      *-------------------------------------------------------------------------------
      * Get speeches of a speaker by name
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/speeches/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος
      * Controller:     api\v1\SpeechesController@speechBySpeakerName
      * Method:         GET
      * Description:    Gets all of the speeches of a speaker specified by greek_name
      *                 or english_name
      */
    Route::get('speeches/speaker/name/{speaker_name}', 'Api\v1\SpeechesController@speechesBySpeakerName');

    /**
      *-------------------------------------------------------------------------------
      * Get speeches of a party by party id
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/speeches/party/syriza
      * Controller:     api\v1\SpeechesController@speechBySpeakerName
      * Method:         GET
      * Description:    Gets all of the speeches of a speaker specified by greek_name
      *                 or english_name
      */
    Route::get('speeches/party/{party_id}', 'Api\v1\SpeechesController@speechesByPartyName');

    /**
      *-------------------------------------------------------------------------------
      * Fulltext search for speeches
      *-------------------------------------------------------------------------------
      * Sample:         http://localhost:8000/api/v1/speeches/search/μνημόνιο
      * Controller:     api\v1\SpeechesController@fulltextSpeechesSearch
      * Method:         GET
      * Description:    Full text search for speeches
      */
    Route::get('speeches/search/{text}', 'Api\v1\SpeechesController@fulltextSpeechSearch');

    /**
      *-------------------------------------------------------------------------------
      *
      * Speakers API endpoints
      *
      *-------------------------------------------------------------------------------
      * Get all speakers with pagination
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\SpeakersController@index
      * Method:         GET
      */
    Route::get('speakers', 'Api\v1\SpeakersController@index');

    /**
      *-------------------------------------------------------------------------------
      * Get a speaker by id
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\SpeechesController@getSpeakerById
      * Method:         GET
      */
    Route::get('speaker/{speaker_id}', 'Api\v1\SpeakersController@getSpeakerById');

    /**
      *-------------------------------------------------------------------------------
      * Get a speaker by name
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\SpeechesController@getSpeakerByName
      * Method:         GET
      */
    Route::get('speaker/name/{name}', 'Api\v1\SpeakersController@getSpeakerByName');

    /**
      *-------------------------------------------------------------------------------
      * Search for speaker by name
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\SpeechesController@searchSpeakerByName
      * Method:         GET
      */
    Route::get('speakers/search/{name}', 'Api\v1\SpeakersController@searchSpeakerByName');

    /**
      *-------------------------------------------------------------------------------
      *
      * Conferences API endpoints
      *
      *-------------------------------------------------------------------------------
      * Get all conferences with pagination and query parameters for ordering
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@index
      * Method:         GET
      * Example:        localhost:8000/api/v1/conferences?order_field=conference_date&orientation=asc
      */
    Route::get('conferences', 'Api\v1\ConferencesController@index');

    /**
      *-------------------------------------------------------------------------------
      * Get conferences by id
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceById
      * Method:         GET
      */
    Route::get('conference/{id}', 'Api\v1\ConferencesController@getConferenceById');

    /**
      *-------------------------------------------------------------------------------
      * Get conferences by date
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceByDate
      * Method:         GET
      */
    Route::get('conference/date/{date}', 'Api\v1\ConferencesController@getConferenceByDate');

    /**
      *-------------------------------------------------------------------------------
      * Get conferences by session
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceBySession
      * Method:         GET
      */
    Route::get('conference/session/{session}', 'Api\v1\ConferencesController@getConferenceBySession');

    /**
      *-------------------------------------------------------------------------------
      * Get conferences by time period
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceByTimePeriod
      * Method:         GET
      */
    Route::get('conference/timeperiod/{timeperiod}', 'Api\v1\ConferencesController@getConferenceByTimePeriod');
    
    /**
      *-------------------------------------------------------------------------------
      * Get conferences by date rage
      *-------------------------------------------------------------------------------
      * Sample          /api/v1/conference/start/1989-07-03/end/1989-07-27
      * Controller:     api\v1\ConferencesController@getConferenceByDateRange
      * Method:         GET
      */
    Route::get('conference/start/{startDate}/end/{endDate}', 'Api\v1\ConferencesController@getConferenceByDateRange');

    /**
      *-------------------------------------------------------------------------------
      * Get conference speeches by id
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceSpeechesById
      * Method:         GET
      */
    // Route::get('conference/{id}/speeches', 'Api\v1\ConferencesController@getConferenceSpeechesById');

    /**
      *-------------------------------------------------------------------------------
      * Get conference speeches by date
      *-------------------------------------------------------------------------------
      * Controller:     api\v1\ConferencesController@getConferenceSpeechesByDate
      * Method:         GET
      */
    Route::get('conference/{date}/speeches', 'Api\v1\SpeechesController@getSpeechesByConferenceDate');

    
});