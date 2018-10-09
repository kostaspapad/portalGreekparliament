<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\ContactUS;

class ContactUsController extends Controller
{
    public function contactUs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
 
        if (ContactUS::create($request->all())){
            return response()->json([
                'message' => 'Message sent',
                'status' => 200
            ], 200);

        } else {
            return response()->json([
                'message' => 'The message has not been sent',
                'status' => 400
            ], 400);
        }
    }
}
