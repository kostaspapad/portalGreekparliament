<?php
namespace App\Helpers;

use App\Http\Resources\Conference as ConferenceResource;
use App\Http\Resources\Speech as SpeechResource;
use App\Http\Resources\Speaker as SpeakerResource;
use App\Http\Resources\Membership as MembershipResource;
use App\Http\Resources\Party as PartyResource;

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

    public static function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Validate query parameters if exist.
     */
    public static function validate_query_params($order_field, $allowed_order_fields, $order_orientation) {
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

        if (!in_array($resource_type, ['Speech', 'Speaker', 'Conference', 'Membership', 'Party'])) {
            throw new InvalidArgumentException('Resource type is not a valid resource');
        }

        if (isset($data) && !empty($data)) {
            if ($resource_type == 'Speech') {
                if ($baseClass == 'LengthAwarePaginator') {
                    return SpeechResource::collection($data);

                } else if ($baseClass == 'Speech') {
                    return new SpeechResource($data);
                }
            } else if ($resource_type == 'Speaker') {
                if ($baseClass == 'LengthAwarePaginator') {
                    return SpeakerResource::collection($data);

                } else if ($baseClass == 'Speaker') {
                    return new SpeakerResource($data);
                }
            } else if ($resource_type == 'Conference') {
                if ($baseClass == 'LengthAwarePaginator') {
                    return ConferenceResource::collection($data);

                } else if ($baseClass == 'Conference') {
                    return new ConferenceResource($data);
                }
            } else if ($resource_type == 'Membership') {
                if ($baseClass == 'LengthAwarePaginator') {
                    return MembershipResource::collection($data);

                } else if ($baseClass == 'Membership') {
                    return new MembershipResource($data);
                }
            } else if ($resource_type == 'Party') {
                if ($baseClass == 'LengthAwarePaginator') {
                    return PartyResource::collection($data);

                } else if ($baseClass == 'Party') {
                    return new PartyResource($data);
                }
            }
        }
    }
}