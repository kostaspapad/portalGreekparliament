<?php

namespace App\Http\Controllers\api\v1;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Helpers\ApiHelper;
use App\Helpers\CacheExpiration;
use Illuminate\Support\Facades\Cache;

class SpeakersController extends Controller
{
    var $allowed_order_fields = ['greek_name', 'english_name', 'fullname_el'];
    var $orientations = ['asc', 'desc'];
    
    public function __construct(Request $request)
    {
        // Get query parameters from Request object
        $this->order_field = $request->get('order_field');
        $this->order_orientation = $request->get('orientation');

        // echo $this->order_field.PHP_EOL;
        
        // echo $this->order_orientation.PHP_EOL;die;
        
        // DB::enableQueryLog();     
        // dd(DB::getQueryLog());   
        $this->apiHelper = new ApiHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }

        // Query parameter validation
        if ($this->order_field && !in_array($this->order_field, $this->allowed_order_fields)){
            return ['Error' => 'Invalid order field'];
        }
        
        if ($this->order_orientation && !in_array($this->order_orientation, $this->orientations)){
            return ['Error' => 'Invalid order orientation'];
        }
        
        // Create dynamic field for query
        if ($this->order_field){
            if($this->order_field == 'fullname_el'){
                $this->order_field = 'p.'.$this->order_field;
            } else {
                $this->order_field = 'speakers.'.$this->order_field;
            }
        }

        //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
        //CacheExpiration::checkCache('speakers',true,$current_page,0,0,true);

        //use to have cache all the pages from pagination
        //if it's set it returns from cache else execute the code in function and then set the key to cache
        $cache_speakers = Cache::remember('speakers-'.$current_page, CacheExpiration::expiration(720), function(){
            // Use the start_date field to find the latest membership
            // $speakers = DB::table('speakers as s')
            $speakers = Speaker::join(
                DB::raw("(SELECT m.person_id, m.on_behalf_of_id, m.start_date
                        FROM(
                            SELECT
                                person_id,
                                MAX(start_date) AS start_date
                                FROM
                                    memberships
                                GROUP BY
                                    person_id
                        ) r
                        INNER JOIN memberships m
                        ON m.start_date = r.start_date 
                        AND m.person_id = r.person_id) AS m"), 'm.person_id', '=', 'speakers.speaker_id')
            ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
            ->join('party_colors as pc', 'pc.party_id', '=', 'p.party_id')
            ->select([
                'speakers.speaker_id', 
                'speakers.english_name',
                'speakers.greek_name',
                'speakers.wiki_en',
                'speakers.wiki_el',
                'speakers.twitter',
                'speakers.image',
                'speakers.email',
                'p.party_id',
                'p.image as party_image',
                'p.fullname_el',
                'pc.color',
            ]);

            if ($this->order_field && $this->order_orientation) {
                $speakers->orderBy($this->order_field, $this->order_orientation);
            }

            return $speakers = $speakers->paginate(50);
        });
        
        return $this->apiHelper::returnResource('Speaker', $cache_speakers);
    }
    
    /**
     * Display a speakers data specified by id. If request query parameter
     * exists (?color=true) return the party color code
     *
     * example /speaker/0067f43b-4d76-46d5-89f2-6a71f7e236e8
     * 
     * @param  int  $speaker_id
     * @return \Illuminate\Http\Response
     */
    public function getSpeakerById($speaker_id)
    {
        //use to have cache all the pages from pagination
        //if it's set it returns from cache else execute the code in function and then set the key to cache

        //check if cache is set or not ($key,$seperator,$current_page,$no_pagination_var,$before_page,$isPagination)
        //CacheExpiration::checkCache('speaker',true,0,$speaker_id,0,false);
        $cache_speaker = Cache::remember('speaker-'.$speaker_id, CacheExpiration::expiration(720), function() use ($speaker_id){
            $speaker = Speaker::join(
                DB::raw("(SELECT m.person_id, m.on_behalf_of_id, m.start_date
                        FROM(
                            SELECT
                                person_id,
                                MAX(start_date) AS start_date
                                FROM
                                    memberships
                                GROUP BY
                                    person_id
                        ) r
                        INNER JOIN memberships m
                        ON m.start_date = r.start_date 
                        AND m.person_id = r.person_id) AS m"), 'm.person_id', '=', 'speakers.speaker_id')
                ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
                ->join('party_colors as pc', 'pc.party_id', '=', 'p.party_id')
                ->select([
                    'speakers.speaker_id', 
                    'speakers.english_name',
                    'speakers.greek_name',
                    'speakers.wiki_en',
                    'speakers.wiki_el',
                    'speakers.twitter',
                    'speakers.image',
                    'speakers.email',
                    'p.party_id',
                    'p.image as party_image',
                    'p.fullname_el',
                    'pc.color',
                ])
                ->where('speakers.speaker_id', '=', $speaker_id)
                ->first();

                return $speaker;
            });

        return $this->apiHelper::returnResource('Speaker', $cache_speaker);
    }
    
    /**
     * This function is returning a speaker, name can be greek(utf8) or
     * an english(ASCII)
     * 
     */ 
    public function getSpeakerByName($speaker_name)
    {
        $name_lang = '';
        
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'speakers.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'speakers.greek_name';
        }

        $speaker = Speaker::join(
            DB::raw("(SELECT m.person_id, m.on_behalf_of_id, m.start_date
                    FROM(
                        SELECT
                            person_id,
                            MAX(start_date) AS start_date
                            FROM
                                memberships
                            GROUP BY
                                person_id
                    ) r
                    INNER JOIN memberships m
                    ON m.start_date = r.start_date 
                    AND m.person_id = r.person_id) AS m"), 'm.person_id', '=', 'speakers.speaker_id')
            ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
            ->join('party_colors as pc', 'pc.party_id', '=', 'p.party_id')
            ->select([
                'speakers.speaker_id', 
                'speakers.english_name',
                'speakers.greek_name',
                'speakers.wiki_en',
                'speakers.wiki_el',
                'speakers.twitter',
                'speakers.image',
                'speakers.email',
                'p.party_id',
                'p.image as party_image',
                'p.fullname_el',
                'pc.color',
            ])
            ->where($name_lang, '=', $speaker_name)
            ->first();
        
        return $this->apiHelper::returnResource('Speaker', $speaker);
    }
    
    /**
     * This function is returning a speaker, name can be greek(utf8) or
     * an english(ASCII)
     * 
     * If request query parameter exists (?color=true) return the party
     * color code
     * 
     * When using color parameter the response will contain all the 
     * memberships of a speaker that has changed more than one party
     */ 
    public function searchSpeakerByName($speaker_name)
    {
        if((isset($this->order_orientation) && !empty($this->order_orientation)) &&
           (isset($this->order_field) && !empty($this->order_field))){
            // Query parameter validation
            if ($this->order_field && !in_array($this->order_field, $this->allowed_order_fields)){
                return ['Error' => 'Invalid order field'];
            }
            
            if ($this->order_orientation && !in_array($this->order_orientation, $this->orientations)){
                return ['Error' => 'Invalid order orientation'];
            }
            
            // Create dynamic field for query
            if ($this->order_field){
                if($this->order_field == 'fullname_el'){
                    $this->order_field = 'p.'.$this->order_field;
                } else {
                    $this->order_field = 'speakers.'.$this->order_field;
                }
            }
        } 

        $speaker_name = '%'.$speaker_name.'%';
        $name_lang = '';
        
        // ASCII = english name
        // UTF-8 = greek name
        if (mb_detect_encoding($speaker_name) == 'ASCII') {
            $name_lang = 'speakers.english_name';
        } else if (mb_detect_encoding($speaker_name) == 'UTF-8') {
            $name_lang = 'speakers.greek_name';
        }

        $speakers = Speaker::join(
            DB::raw("(SELECT m.person_id, m.on_behalf_of_id, m.start_date
                    FROM(
                        SELECT
                            person_id,
                            MAX(start_date) AS start_date
                            FROM
                                memberships
                            GROUP BY
                                person_id
                    ) r
                    INNER JOIN memberships m
                    ON m.start_date = r.start_date 
                    AND m.person_id = r.person_id) AS m"), 'm.person_id', '=', 'speakers.speaker_id')
            ->join('parties as p', 'p.party_id', '=', 'm.on_behalf_of_id')
            ->join('party_colors as pc', 'pc.party_id', '=', 'p.party_id')
            ->select([
                'speakers.speaker_id', 
                'speakers.english_name',
                'speakers.greek_name',
                'speakers.wiki_en',
                'speakers.wiki_el',
                'speakers.twitter',
                'speakers.image',
                'speakers.email',
                'p.party_id',
                'p.image as party_image',
                'p.fullname_el',
                'pc.color',
            ])
            ->where($name_lang, 'like', $speaker_name);
        
        $speakers = $speakers->groupBy('speakers.speaker_id');

        if ($this->order_field && $this->order_orientation) {
            $speakers->orderBy($this->order_field, $this->order_orientation);
        }
        
        $speakers = $speakers->paginate(50);

        return $this->apiHelper::returnResource('Speaker', $speakers);
    }
}
