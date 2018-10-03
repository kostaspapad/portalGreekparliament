<?php

namespace App\Http\Controllers\api\v1\Speakers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Membership;
use App\Helpers\ApiHelper;

class MembershipsController extends Controller
{
    public function __construct(Request $request) 
    {
        // Get query parameters from Request object
        // $this->order_field = $request->get('order_field');
        // $this->order_orientation = $request->get('orientation');

        // $this->validate_query_params();

        $this->apiHelper = new ApiHelper();
    }

    public function membershipsBySpeakerId($speaker_id)
    {
        $membership = Membership:://join('memberships as m', 'speakers.speaker_id', '=', 'm.person_id')
            select([
                'area_id',
                'legislative_period_id',
                'on_behalf_of_id',
                'organization_id',
                'role',
                'start_date',
                'end_date'
            ])
            ->where('person_id', '=', $speaker_id);

        $membership = $membership->paginate(50);
        
        return $this->apiHelper::returnResource('Membership', $membership);
    }

    public function membershipsBySpeakerName($speaker_name) 
    {
        //$speaker_name = '%'.$speaker_name.'%';
        $speaker_name = "'".$speaker_name."'";
        $name_lang = '';
        
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'speakers.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'speakers.greek_name';
        }

        // $membership = Membership::join('speakers as s', 's.speaker_id', '=', 'memberships.person_id')
        //     ->select([
        //         'memberships.area_id',
        //         'memberships.legislative_period_id',
        //         'memberships.on_behalf_of_id',
        //         'memberships.organization_id',
        //         'memberships.role',
        //         'memberships.start_date',
        //         'memberships.end_date'
        //     ])
        //     ->where($name_lang, 'like', $speaker_name);

        // $membership = $membership->paginate(50);
        $membership = DB::select(
            DB::raw('
                SELECT 
                    speakers.greek_name,
                    memberships.on_behalf_of_id,
                    memberships.start_date,
                    memberships.end_date,
                    parties.fullname_el,
                    party_colors.color
                FROM
                    memberships
                        INNER JOIN
                    speakers speakers ON speakers.speaker_id = memberships.person_id
                        INNER JOIN
                    parties ON memberships.on_behalf_of_id = parties.party_id
                        INNER JOIN
                    party_colors ON parties.party_id = party_colors.party_id
                WHERE '.$name_lang.' = '.$speaker_name.'
                GROUP BY speakers.greek_name , memberships.on_behalf_of_id , memberships.start_date , memberships.end_date
                ORDER BY speakers.greek_name, memberships.start_date
            ')
        );
        //dd($membership);
        return $membership;
        return $this->apiHelper::returnResource('Membership', $membership);
    }
}
