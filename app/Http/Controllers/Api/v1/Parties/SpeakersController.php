<?php

namespace App\Http\Controllers\api\v1\Parties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Models\Party;
use App\Models\Speaker;


class SpeakersController extends Controller {

    public function __construct(Request $request) 
    {
        // Get query parameters from Request object
        // $this->order_field = $request->get('order_field');
        // $this->order_orientation = $request->get('orientation');

        $this->apiHelper = new ApiHelper();
    }

    public function partySpeakersByPartyId($party_id) 
    {
        $speakers = Party::join('memberships as m', 'parties.party_id', '=' ,'m.on_behalf_of_id')
            ->join('speakers as s', 'm.person_id', '=', 's.speaker_id')
            ->select([
                's.speaker_id',
                's.greek_name', 
                's.english_name',
                's.image'])
            ->where('parties.party_id', '=', $party_id)
            ->groupBy('s.speaker_id')
            ->orderBy('s.greek_name', 'asc')
            ->paginate(20);
         
        return $this->apiHelper::returnResource('Speaker', $speakers);
    }
}