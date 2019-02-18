<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Hash;
class ApiAuthController extends Controller
{
 
   public $successStatus = 200;
 
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        // Check if a user with the specified email exists
        $user = User::whereEmail(request('username'))->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 400
            ], 400);
        }
        
        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 400
            ], 400);
        }
        
        // Send an internal API request to get an access token
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Make sure a Password Client exists in the DB
        if (!$client) {
            return response()->json([
                'message' => 'Laravel Passport is not setup properly.',
                'status' => 500
            ], 500);
        }
        
        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => request('username'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);
        
        $response = app()->handle($request);
        
        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 400
            ], 400);
        }

        // Get the data from the response
        $data = json_decode($response->getContent());

        // Format the final response in a desirable format
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ]);
    }
 
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');

        if (!isset($name) || empty($name)) {
            return response()->json([
                'message' => 'No name was specified.',
                'status' => 400
            ], 400);
        }

        if (!isset($email) || empty($email)) {
            return response()->json([
                'message' => 'No email was specified.',
                'status' => 400
            ], 400);
        }

        if (!isset($password) || empty($password)) {
            return response()->json([
                'message' => 'No password was specified.',
                'status' => 400
            ], 400);
        }

        if (!isset($confirm_password) || empty($confirm_password)) {
            return response()->json([
                'message' => 'No password confirmation was specified.',
                'status' => 400
            ], 400);
        }

        if ($password !== $confirm_password) {
            return response()->json([
                'message' => 'Passwords are not the same.',
                'status' => 400
            ], 400);
        }
        
        if ( strlen($password) <= '8' ) {
            return response()->json([
                'message' => "Your Password Must Contain At Least 8 Characters!",
                'status' => 400
            ], 400);
        // } elseif ( !preg_match("#[0-9]+#",$password) ) {
        //     return response()->json([
        //         'message' => "Your Password Must Contain At Least 1 Number!",
        //         'status' => 400
        //     ], 400);
        // } elseif ( !preg_match("#[A-Z]+#",$password )) {
        //     return response()->json([
        //         'message' => "Your Password Must Contain At Least 1 Capital Letter!",
        //         'status' => 400
        //     ], 400);
        // } elseif ( !preg_match("#[a-z]+#",$password )) {
        //     return response()->json([
        //         'message' => "Your Password Must Contain At Least 1 Lowercase Letter!",
        //         'status' => 400
        //     ], 400);
        }

        if (User::where('email', '=', $email)->first() == null){
            $user = User::create([
                        'name' => request('name'),
                        'email' => request('email'),
                        'password' => bcrypt(request('password'))
                    ]);
                
            $data['token'] =  $user->createToken('gp-api')->accessToken;
            $data['user'] = $user->name;

            return response()->json([
                'status' => 201,
                'data' => $data
            ]);
            
        } else {
            return response()->json([
                'message' => 'A user with the same email already exists.',
                'status' => 400
            ], 400);
        }
    }
 
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $accessToken = auth()->user()->token();
        
        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        // Set revoked flag to 1
        $accessToken->revoke();
                
        return response()->json(['status' => 200]);
        // $request->user()->token()->revoke();

        // return response()->json([
        //     'message' => 'Successfully logged out'
        // ]);
    }
   
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getUser()
    {
        return auth()->user()->token();
    }
}
