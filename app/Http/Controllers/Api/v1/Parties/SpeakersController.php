<?php

namespace App\Http\Controllers\api\v1\Parties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Models\Party;
use App\Models\Speaker;
use App\Helpers\CacheExpiration;
use Illuminate\Support\Facades\Cache;


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
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
        //CacheExpiration::checkCache('party_speakers',true,$current_page,0,$party_id,true);
    
        $cache_party_speakers =  Cache::remember('party_speakers-'.$party_id.'-'.$current_page, CacheExpiration::expiration(720), function() use ($party_id) {
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

            return $speakers;
        });
         
        return $this->apiHelper::returnResource('Speaker', $cache_party_speakers);
    }
}