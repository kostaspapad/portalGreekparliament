<?php

namespace App\Http\Controllers\api\v1;

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
}
