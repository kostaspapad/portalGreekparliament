<?php
namespace App\Helpers;

use App\Http\Resources\Conference as ConferenceResource;
use App\Http\Resources\Speech as SpeechResource;
use App\Http\Resources\Speaker as SpeakerResource;
use App\Http\Resources\Membership as MembershipResource;
use App\Http\Resources\Party as PartyResource;
use App\Http\Resources\Comment as CommentResource;

class ApiHelper {

    public function __construct() {}

    // /**
    //  * Validate query order field parameters.
    //  */
    // public static function validate_order_field($order_field, $allowed_order_fields) {
    //     if ($order_field && !in_array($order_field, $allowed_order_fields)) {
    //         return ['Error' => 'Invalid order field'];
    //     }
    // }

    // public static function validate_orientation_field($order_orientation) {
    //     if ($order_orientation && !in_array($order_orientation, ['asc', 'desc'])) {
    //         return ['Error' => 'Invalid order orientation'];
    //     }
    // }

    // // Create dynamic field for query
    // public static function create_dynamic_field($alias, $order_field) {
    //     if (isset($alias) && !empty($alias) && isset($order_field) && !empty($order_field)) {
    //         return $order_field = $alias.'.'.$order_field;
    //     }
    // }

    public static function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function validate_speaker_id($speaker_id) 
    {
        if (preg_match('@[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}@', $speaker_id)) {
            return true;
        } else {
            return false;
        }
        // cbc3c1b1-4543-49d1-8d64-e7934286a81f
    }

    /**
     * Validate query parameters if exist.
     */
    public static function validate_query_params($order_field, 
        $allowed_order_fields, $order_orientation) 
    {
        // Query parameter validation
        if ($order_field && !in_array($order_field, $allowed_order_fields)) {
            return ['Error' => 'Invalid order field'];
        }
        
        if ($order_orientation && !in_array($order_orientation, $orientations)) {
            return ['Error' => 'Invalid order orientation'];
        }

        // Create dynamic field for query
        if (isset($order_field) && !empty($order_field)) {
            $order_field = 'conferences.'.$order_field;
        }
    }

    public static function returnResource($resource_type, $data) {

        $baseClass = class_basename($data);

        if (!is_string($resource_type)) {
            throw new InvalidArgumentException('Resource type is not a string');
        }

        if (!in_array($resource_type, ['Speech', 'Speaker', 'Conference', 'Membership', 'Party', 'Comment'])) {
            throw new InvalidArgumentException('Resource type is not a valid resource');
        }

        if (isset($data) && !empty($data)) {
            if ($resource_type == 'Speech') {
                if ($baseClass == 'Speech') {
                    return new SpeechResource($data);
                } else {
                    return SpeechResource::collection($data);
                }
            } else if ($resource_type == 'Speaker') {
                if ($baseClass == 'Speaker') {
                    return new SpeakerResource($data);
                } else {
                    return SpeakerResource::collection($data);
                }
            } else if ($resource_type == 'Conference') {
                if ($baseClass == 'Conference') {
                    return new ConferenceResource($data);
                } else {
                    return ConferenceResource::collection($data);
                }
            } else if ($resource_type == 'Membership') {
                if ($baseClass == 'Membership') {
                    return new MembershipResource($data);
                } else {
                    return MembershipResource::collection($data);
                }
            } else if ($resource_type == 'Party') {
                if ($baseClass == 'Party') {
                    return new PartyResource($data);
                } else {
                    return PartyResource::collection($data);
                }
            } else if ($resource_type == 'Comment') {
                if ($baseClass == 'Collection') {
                    return CommentResource::collection($data);
                }else{
                    return new CommentResource($data);
                }
            }
        }
    }
}