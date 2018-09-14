<?php

use Illuminate\Http\Request;

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
     *   Speeches
     */
    // http://localhost:8000/api/v1/speech/200510131000
    Route::get('speech/{id}', 'Api\v1\SpeechesController@getSpeechById');

    // http://localhost:8000/api/v1/speeches/search/μνημόνιο
    Route::get('speeches/search/{text}', 'Api\v1\SpeechesController@fulltextSpeechSearch');

    /**
     *   Speakers
     */
    Route::get('speakers', 'Api\v1\SpeakersController@index');

    // http://localhost:8000/api/v1/speaker/0ec3bdd6-140b-473d-806d-6ba089cf7a35/speeches
    Route::get('speaker/{speaker_id}', 'Api\v1\SpeakersController@getSpeakerById');

    // http://localhost:8000/api/v1/speaker/name/Τσούκαλης Σπυρίδωνος Νικόλαος/speeches
    Route::get('speaker/name/{name}', 'Api\v1\SpeakersController@getSpeakerByName');

    // http://localhost:8000/api/v1/speakers/search/Τσούκαλης
    Route::get('speakers/search/{name}', 'Api\v1\SpeakersController@searchSpeakerByName');

    // Speaker->Speeches
    Route::get('speaker/{speaker_id}/speeches', 'Api\v1\Speakers\SpeechesController@speechesBySpeakerId');
    Route::get('speaker/name/{speaker_name}/speeches', 'Api\v1\Speakers\SpeechesController@speechesBySpeakerName');
    
    /**
     *   Conference
     */
    // http://localhost:8000/api/v1/conferences?order_field=conference_date&orientation=asc
    Route::get('conferences', 'Api\v1\ConferencesController@index');
    Route::get('conference/{id}', 'Api\v1\ConferencesController@getConferenceById');
    Route::get('conference/date/{date}', 'Api\v1\ConferencesController@getConferenceByDate');
    
    // http://localhost:8000/api/v1/conference/start/1989-07-03/end/1989-07-27
    Route::get('conference/start/{startDate}/end/{endDate}', 'Api\v1\ConferencesController@getConferencesByDateRange');

    // Conference->Speeches
    Route::get('conference/{date}/speeches', 'Api\v1\Conferences\SpeechesController@speechesByConferenceDate');

    // Memberships->Speaker
    // http://localhost:8000/api/v1/speaker/002483ab-3653-4458-b410-6b0ee380cc76/memberships
    Route::get('speaker/{speaker_id}/memberships', 'Api\v1\Speakers\MembershipsController@membershipsBySpeakerId');
    // http://localhost:8000/api/v1/speaker/name/Αλέξης Τσίπρας/memberships
    Route::get('speaker/name/{name}/memberships', 'Api\v1\Speakers\MembershipsController@membershipsBySpeakerName');
    
    /**
     *   Parties
     */
    Route::get('parties', 'Api\v1\PartiesController@index');
    Route::get('party/{id}', 'Api\v1\PartiesController@partyById');
    Route::get('party/name/{name}', 'Api\v1\PartiesController@partyByName');
    Route::get('parties/search/{party_name}', 'Api\v1\PartiesController@searchPartyByName');

    // Parties->Speakers
    Route::get('party/{party_id}/speakers', 'Api\v1\Parties\SpeakersController@partySpeakersByPartyId');
    
    // Parties->Speeches
    // http://localhost:8000/api/v1/speeches/party/syriza
    // Route::get('party/{party_id}/speeches', 'Api\v1\Parties\SpeechesController@speechesByPartyId');
});