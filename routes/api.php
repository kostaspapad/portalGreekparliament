<?php

use Illuminate\Http\Request;
use App\Speaker;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('speakers', function(){
    $speakers = Speaker::orderBy('id','desc')->paginate(2);
    return response()->json(compact('speakers'));
});
