<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Models\Party;
use App\Models\Speaker;


class PartiesController extends Controller
{
    public function __construct(Request $request) {
        // Get query parameters from Request object
        // $this->order_field = $request->get('order_field');
        // $this->order_orientation = $request->get('orientation');

        $this->apiHelper = new ApiHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get speakers
        $parties = Party::paginate(50);

        return $this->apiHelper::returnResource('Party', $parties);
    }

    /**
     * Display a party data specified by id.
     *
     * example /party/allianceofprogressiveandleftforces
     * 
     * @param  int  $party_id
     * @return \Illuminate\Http\Response
     */
    public function getPartyById($party_id)
    {
        $party = Party::findorfail($party_id);

        return $this->apiHelper::returnResource('Party', $party);
    }
    
    /**
     * Display a party data specified by name.
     *
     * example /
     * 
     * @param  int  $party_name
     * @return \Illuminate\Http\Response
     */
    public function getPartyByName($party_name){
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($party_name) == 'ASCII') {
            $party = Party::where('fullname_en', '=', $party_name)->first();
        } else if (mb_detect_encoding($party_name) == 'UTF-8') {
            $party = Party::where('fullname_el', '=', $party_name)->first();
        }
        
        return $this->apiHelper::returnResource('Party', $party);
    }

    public function getPartySpeakers($party_id) {
        
        $speakers = Party::join('memberships as m', 'parties.party_id', '=' ,'m.on_behalf_of_id')
            ->join('speakers as s', 'm.person_id', '=', 's.speaker_id')
            ->select([
                's.speaker_id',
                's.greek_name', 
                's.english_name',
                's.image'])
            ->where('parties.party_id', '=', $party_id)
            ->groupBy('s.speaker_id')
            ->paginate(20);
         
        return $this->apiHelper::returnResource('Speaker', $speakers);
    }
}
