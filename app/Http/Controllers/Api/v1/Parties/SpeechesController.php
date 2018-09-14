<?php

namespace App\Http\Controllers\api\v1\Parties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Models\Party;
use App\Models\Speaker;


class SpeechesController extends Controller {

    public function __construct(Request $request) 
    {
        // Get query parameters from Request object
        // $this->order_field = $request->get('order_field');
        // $this->order_orientation = $request->get('orientation');

        $this->apiHelper = new ApiHelper();
    }

    /**
     * Get speeches of a party specified by name
     * 
     * Use this path for getting data using the english name
     * and the greek name of the party
     * 
     * @param  str  $party_id
     * @return App\Http\Resources\Speech
     */
    public function speechesByPartyId($party_id)
    {
        // ASCII = english name
        // UTF-8 = greek name
        die;
        // $speeches = Speech::select('speeches.*')
        //     ->join('memberships', 'speeches.speaker_id', '=', 'memberships.person_id')
        //     ->where('memberships.on_behalf_of_id', '=', $party_id)
        //     ->get();
        
        // // DEN DOULEVEI! MEMORY ERROR!!
        // // Return the collection of Speeches as a resource
        // if (isset($speeches) && !empty($speeches)) {
        //     return  SpeechResource::collection($speeches);
        // }
    }
}