<?php
    namespace App\Helpers;
    use Illuminate\Support\Facades\Cache;

    class CacheExpiration {

        private static $isCacheSet = false;

        public function __construct() {}
        
        //check if cache was set
        /**
         * $key -> key of cache
         * $current_page -> pagination page
         * $main_var -> used after key, such as speaker_id,speech_id etc... ,example: '$key-speaker_id'
         * $middle_var -> used before current_page, example: '$key-$middle_var-$current_page'
         * $isPagination -> if it is pagination
         * 
         */
        public static function checkCache($key,$seperator = false,$current_page,$main_var,$before_page,$isPagination = false){
            $seperator_str = '-';
            echo "key -> $key ";
            if($isPagination){
                echo "\npagination: true ";
                //seperator true
                if($seperator){
                    if($before_page){
                        echo "\nbefore_page -> $before_page ";
                        echo "\npage -> $current_page ";
                        if (Cache::has($key.$seperator_str.$before_page.$seperator_str.$current_page)) {
                            self::$isCacheSet = true;
                        }else{
                            self::$isCacheSet = false;
                        }
                    }else{
                        if (Cache::has($key.$seperator_str.$current_page)) {
                            self::$isCacheSet = true;
                        }else{
                            self::$isCacheSet = false;
                        }
    
                    }
                }
            }else{
                if($seperator){
                    if($main_var){
                        echo "\nmain_var -> $main_var";
                        if (Cache::has($key.$seperator_str.$main_var)) {
                            self::$isCacheSet = true;
                        }else{
                            self::$isCacheSet = false;
                        }
                    }
                }else{
                    if (Cache::has($key)) {
                        self::$isCacheSet = true;
                    }else{
                        self::$isCacheSet = false;
                    }
                }
            }
            if(self::$isCacheSet){
                echo " \n--- cache set";
            }else{
                echo " \n--- cache not set";
            }
            die;
        }

        public static function expiration($minutes){
            if(isset($minutes)){
                return now()->addMinutes($minutes);
            }else{
                echo "set the minutes";
            }
        }
    }