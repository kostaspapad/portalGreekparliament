<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Speech;
use App\Speaker;
use App\Membership;
use DB;
use DateTime;

class CsvImportSpeeches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'speeches:csv-import {csvfile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command: php artisan speeches:csv-import
        Import speech data from csv file, the csv file must be located
                              ./app/storage/public';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        // testing
        // dd($this->get_speech_by_id(20150206000));
// logika trexei 
// to afisa epeidi den exo speeches sto parliament.speech_data
// prepi na ftia3o na pernei apo to membership ta area_id kai party_id otan vriski membership start k end date anamesa apo to conference date tou speech
// na kliso to loggin stin mysql 8a gemisi o skliros 
        // Get args
        $filename = $this->argument('csvfile');
        if ($filename)
        {
            // Must increase memory_limit on /etc/php/php.ini, memory_limit = 256
            $csv_file = storage_path() . '/app/public/' . $filename;

            $data = $this->csv_to_array($csv_file);
            
            // Insert to portal database
            $this->start_csv_insert($data);

        } else {
            echo 'Invalid argument for csv filename.' . PHP_EOL . 'File must be located at /var/www/html/parliamentportal/storage/app/public/';
        }
    }

    // Read csv file and return an array
    public function csv_to_array($filename = '', $delimiter = ',') {

        if (!file_exists($filename) || !is_readable($filename))
        {
            echo 'Error in csv_to_array'.PHP_EOL;
			return FALSE;
        }

        $data = array();
        $header = TRUE;

        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                // Check header data
                if ($header)
                {
                    if ($row[0] == 'speaker_name' && $row[1] == 'speaker_id' && $row[2] == 'speech_id')
                    {
                        $header = FALSE;
                        echo 'Valid csv header' .PHP_EOL;
                    } else {
                        echo 'Invalid csv header. EXIT' . PHP_EOL;
                        die;
                    }
                } else {
                    // Create array of data and leave header out
                    $data[] = $row;
                }
            }
            
			fclose($handle);
        }
        
        // Data validation
        $data = $this->transform_data($data);
        
        if (!$data){
            echo 'Error in transform_data'.PHP_EOL;
            return FALSE;
        }

		return $data;
    }
    
    // Do some validation and fill empty speaker ids with zeros
    public function transform_data($data){
        if (!isset($data) || empty($data))
        {
            return FALSE;
        }

        /*
         * & passes a value of the array as a reference and does not create a new instance of the variable. 
         * Thus if you change the reference the original value will change.
         * http://php.net/manual/en/language.references.pass.php
         */ 
        foreach($data as &$d)
        {
            // speaker_name must not be empty
            if ($d[0] == '')
            {
                echo 'Found empty speaker_name'.PHP_EOL;
                print_r($d);
                return FALSE;
            }

            // If speaker id does not exist fill with zeros
            if ($d[1] == '')
            {
                $d[1] = '00000000-0000-0000-0000-000000000000';
            }

            // speech_id must be an integer
            $d[2] = (int)$d[2];
            if (!is_integer($d[2]))
            {
                print_r($d);
                echo 'Found non integer speech_id (type = ' . gettype($d[2]) . PHP_EOL;
                return FALSE;
            }

            $d[3] = substr($d[3], 0, strpos($d[3], "T"));
            
        }

        return $data;
    }

    /**
     * Insert to parliament.speech_data and after run query to insert to portal.speeches
     *
     * Crawled data exist in parliament.speech_data and have NULL speaker_id, this function
     * fills the matched speaker_ids. 
     *
     * These that dont matched have zeros
     */
    public function start_csv_insert($data){
        if (!isset($data) || empty($data))
        {
            echo 'Got empty data in start_csv_insert' . PHP_EOL;
            return FALSE;
        }        

        foreach($data as $d){
            // $sql = "UPDATE speech_data SET speaker_id = '" . $d[1] . "' WHERE speech_id = " . $d[2] . ";";

                    # gia ka8e row 
        // an den uparxei to speaker_id sto memberships (pernao speech me custom speaker_id)
        //      
        //          koitazo an uparxei eidi to speaker name me custom id
        //          An uparxei:
        //          
        //              to speech pernaei mesa me spekaer id pou vre8ike
        //          Den uparxei;
        //              ftiaxno neo row ston speakers pernw to telefteo custom speaker id pou ftiaxtike kai to af3ano kata 1 (pernaw kai speaker_name)
        //              pernao to neo row ston speeches me speaker id to neo id
        // an uparxei to speaker_id 
        //     pernw ola ta membership gia to speaker_id 
        //         kitazo to conferencedate an ienai se kapio range (iparxei h periptosi na einai null to end_date)
        //                                                          (periptosi na min teriazei to conf date me kanena 
        //                                                           tote afino area_id kai partyid null)
            print_r($d);
            
            $speaker_name = $d[0];
            $speaker_id = $d[1];
            $speech_id = $d[2];
            $conf_date = $d[3];

            $memberships = Membership::where('person_id', $speaker_id)->get();
            // dd($memberships);

            // Check if membership for person exist, if not insert with speaker_id zeros
            if ($memberships->count() != 0){
                // Memberships exist, find the membership that matches the speech date
                echo 'Try to match date: '.$conf_date.PHP_EOL;
                $area_id_party_id = $this->find_membership_metadata($memberships, $conf_date, $speaker_id);
                
                // Get speech blob
                $speech_text = $this->get_speech_by_id($speech_id);
                if ($speech_text != FALSE) {
                    if (isset($area_id) && !empty($area_id) && isset($party_id) && !empty($party_id)){
                        $this->insert_to_portal_speeches($speech_id, $conf_date, $speaker_id, $speech_text, $area_id, $party_id);
                    } else {
                        $this->insert_to_portal_speeches($speech_id, $conf_date, $speaker_id, $speech_text, NULL, NULL);
                    }
                } else {
                    continue;
                }
                continue;

            } else {
                echo PHP_EOL.'Insert to portal.speeches with speaker_id zeros'.PHP_EOL;
                
                // Create new speaker to portal.speakers
                $new_speaker_id = $this->search_unmatched_speaker($speaker_id, $speaker_name);

                // Get speech blob
                $speech_text = $this->get_speech_by_id($speech_id);
                if ($speech_text != FALSE) {
                    $this->insert_to_portal_speeches($speech_id, $conf_date, $new_speaker_id, $speech_text, NULL, NULL);
                } else {
                    continue;
                }
                continue;
            }
        }
    }

    public function find_membership_metadata($memberships, $conference_date, $speaker_id){
        // Convert to timestamp
        $conference_date = strtotime($conference_date);
        
        /** 
         * Loop for all memberships of a speaker and compare dates
         * to find the party he was a member when the speech occured
         *
         * Date is in between start_date and end_date
         * start_date is null, end_date is not
         * start_date is not null, end_date is null
         * Both start_date and end_date are null
         */
        foreach($memberships as $m){
            
            // dd($m);

            // If both dates exist
            if ($m->start_date != '' && $m->end_date != ''){
                $m->start_date = strtotime($m->start_date);
                $m->end_date = strtotime($m->end_date);
                
                if ($conference_date >= $m->start_date && $conference_date <= $m->end_date){
                   
                    echo 'BETWEEN DATES'.PHP_EOL;
                    $a = Membership::where('start_date', '=', $m->start_date)
                            ->where('end_date', '=', $m->end_date)
                            ->select(['area_id', 'on_behalf_of_id'])->get()->all();
                    dd($a);
                    break;
                } else {
                    echo "NOT IN RANGE".PHP_EOL;
                    break;
                }
            } 

            // If start_date is empty and end_date is not
            if ($m->start_date == '' && $m->end_date != ''){
                
                echo 'EMPTY START'.PHP_EOL;
                break;
            }

            // If start_date is not empty and end_date is empty
            if ($m->start_date != '' && $m->end_date == ''){
                echo 'EMPTY END'.PHP_EOL;
                break;
            }

            // If start_date is empty and end_date is empty
            if ($m->start_date == '' && $m->end_date == ''){
                echo 'EMPTY START & END'.PHP_EOL;
                break;
            }
        }
    }

    /*
     * Create a row in portal.speakers for a speaker in csv that does not exist in db
     * e.x 'ΠΟΛΛΟΙ ΒΟΥΛΕΥΤΕΣ'
     */
    public function search_unmatched_speaker($name){
        
        if (!$name){echo 'name not set'.PHP_EOL;die;}

        echo '---- Search for speaker with name: '.$name.PHP_EOL;

        // Check if name exists in portal.speakers
        // if not create new entry with incrementing id
        $speaker = Speaker::select('speaker_id')->where('greek_name', '=', $name)->get();

        if (sizeof($speaker) > 0){
            // Speaker exists get hes id
            $speaker_id = $speaker[0]->speaker_id;
            
        } else {
            // Unmatched speaker has not been created
            $speaker_id = $this->create_new_speaker($name);
        }
        if (isset($speaker_id) && !empty($speaker_id)) {
            return $speaker_id;
            
        } else {
            echo 'ERROR in search_unmatched_speakers'.PHP_EOL;
            die;
        }
    }

    public function create_new_speaker($name) {
       
        // Find custom ids an keep the last
        $speakers = Speaker::where('speaker_id', 'like', '00000000-0000-%')->get();

        if (sizeof($speakers) == 0) {
            // No speakers with custom ids exist. Create the first one
            $id = '00000000-0000-0000-0000-000000000000';

        } else {
            echo 'Generate speaker id'.PHP_EOL;
            $id = $this->generate_speaker_id($speakers);
            echo 'New speaker id: '.$id.PHP_EOL;
        }
       
        Speaker::insert([
            'speaker_id' => $id, 
            'english_name' => '',
            'greek_name' => $name,
            'image' => 'default_speaker_icon.png',
            'email' => '',
            'wiki_el' => '',
            'wiki_en' => '',
            'twitter' => '',
            'website' => ''
        ]);
     
        echo '___NEW SPEAKER INSERTED_________________________________________'.PHP_EOL;
        echo '-------------- |speaker_id: '.$id.PHP_EOL;
        echo '-------------- |english_name: '.''.PHP_EOL;
        echo '-------------- |greek_name: '.$name.PHP_EOL;
        echo '-------------- |image: '.'default_speaker_icon.png'.PHP_EOL;
        echo '-------------- |email: '.''.PHP_EOL;
        echo '-------------- |wiki_el: '.''.PHP_EOL;
        echo '-------------- |wiki_en: '.''.PHP_EOL;
        echo '-------------- |twitter: '.''.PHP_EOL;
        echo '-------------- |website: '.''.PHP_EOL;
        echo '________________________________________________________________'.PHP_EOL;
        
        return $id;
    }
   
    public function generate_speaker_id($speakers) {
        $last_speaker_obj = $speakers->last();
        
        $tmp_id = explode('-', $last_speaker_obj->speaker_id)[4];
        
        // Remove leading zeros
        $tmp_id = ltrim($tmp_id, '0');

        // Convert to int
        $value = (int)$tmp_id;
        
        // Increment
        $value = ++$value;

        // Transform to 00000000-0000-0000-0000-000000000xxx format
        $str_id = '00000000-0000-0000-0000-' . str_pad((string)$value, 12, "0", STR_PAD_LEFT);
        
        return $str_id;
    }
  
    public function insert_to_portal_speeches($speech_id, $conf_date, $speaker_id, $speech_text, $area_id, $party_id){
        // Param validation
        if (!isset($speech_id)){echo 'speech_id not set in insert_to_portal_speeches'.PHP_EOL;die;}
        if (!isset($conf_date)){echo 'conf_date not set in insert_to_portal_speeches'.PHP_EOL;die;}
        if (!isset($speaker_id)){echo 'speaker_id not set in insert_to_portal_speeches'.PHP_EOL;die;}
        if (!isset($speech_text)){echo 'speech_text not set in insert_to_portal_speeches'.PHP_EOL;die;}

        echo '___SPEECH INSERTED______________________________________________'.PHP_EOL;
        echo 'Inserting to portal.speeches'.PHP_EOL;
        echo '|speech_id: '.$speech_id.'|'.PHP_EOL; 
        echo '|speech_conference_date: '.$conf_date.'|'.PHP_EOL;
        echo '|speaker_id: '.$speaker_id.'|'.PHP_EOL;
        echo '|speech: '.$speech_text.'|'.PHP_EOL;
        echo '|create_at: '.now().'|'.PHP_EOL;
        echo '|updated_at: '.now().'|'.PHP_EOL;
        
        if($area_id){echo '|area_id: '.$area_id.'|'.PHP_EOL;}
        if($party_id){echo '|party_id: '.$party_id.'|'.PHP_EOL;}
        echo '________________________________________________________________'.PHP_EOL;
        
        // if (isset($area_id) && isset($party_id)){
        //     Speech::insert([
        //         'speech_id' => $speech_id, 
        //         'speech_conference_date' => $conf_date,
        //         'speaker_id' => $speaker_id,
        //         'speech' => $speech_text,
        //         'create_at' => now(),
        //         'updated_at' => now(),
        //         'md5' => md5($speech_text),
        //         'area_id' => $area_id,
        //         'party_id' => $party_id
        //     ]);
        // } else {
        //     Speech::insert([
        //         'speech_id' => $speech_id, 
        //         'speech_conference_date' => $conf_date,
        //         'speaker_id' => $speaker_id,
        //         'speech' => $speech_text,
        //         'create_at' => now(),
        //         'updated_at' => now(),
        //         'md5' => md5($speech_text)
        //     ]);
        // }
    }
 
    public function get_speech_by_id($speech_id){
        
        if (!$speech_id){echo 'speech_id not set'.PHP_EOL;die;}
        
        $speech_text = DB::connection('mysql_scraper')
            ->table('speech_data')
            ->select('speech')
            ->where('speech_id', '=', $speech_id)->get();
            

        if ($speech_text->isNotEmpty()){
            echo 'Text blob for id: '.$speech_id.' found'.PHP_EOL;
            
            return $speech_text[0]->speech;
            
        } else {
            echo 'text blob for id: '.$speech_id.' not found'.PHP_EOL;
            return false;
        }
    }
}