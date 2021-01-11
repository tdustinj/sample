<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function auth(Request $request)
    {

        $params = $request->only('email', 'password');

        $username = $params['email'];
        $password = $params['password'];

        if(\Auth::attempt(['email' => $username, 'password' => $password])){

            $userCheck = DB::table('users')->where('email', $username)->first();
            $token = DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->count() < 1
                ? \Auth::user()->createToken('my_user', [])
                : DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->first();

            //Check if token is expired
            //Make new token if expired
            //Delete old token
            return $token;
        }

        return response()->json(['error' => 'Invalid username or Password']);
    }

    public function authGoogle(Request $request)
    {
       // require_once('../vendor/autoload.php');


        $params = $request->only('email', 'google_id', 'g_token', 'g_refresh', 'g_name');

        $username = $params['email'];
        $g_id = $params['google_id'];
        $g_token = $params['g_token'];
        // $g_ref = $params['g_refresh'];
        $g_name = $params['g_name'];

        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($g_token);

        if (array_key_exists('hd', $payload)) {
            $domain = $payload['hd'];
            $userid = $payload['sub'];
            $user = User::where('email', $payload['email'])->first();

            if( empty($user) && $domain == "walts.com") {
                $user = User::create([
                    'name' => $payload['name'],
                    'google_id' => $g_id,
                    'email' => $payload['email'],
                    'password' => Hash::make(str_random(8)),
                    //TODO: Get real token and refresh token from google
                    'g_token' => Hash::make(str_random(8)),
                    'g_refresh_token' => Hash::make(str_random(8)),
                ]);
            }

            if($user['google_id'] == $g_id && $domain == "walts.com") {
                //Check if user already has a token
                \Auth::loginUsingId($user['id']);
                $userCheck = DB::table('users')->where('email', $username)->first();
                $token = DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->count() < 1
                    ? $user->createToken('api_access_token')->accessToken
                    : DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->first();
                    
                $token = DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->first();
                if(Carbon::parse($token->expires_at)->gte(Carbon::now())) {
                    //Delete old tokens
                    DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->delete();
                    //Make new token
                    $token = \Auth::user()->createToken('api_access_token', []);
                }
                $visibleColumns = Array('auth_super_user', 'auth_sales', 'auth_admin', 'auth_operations');
                return response()->json([
                    'data' => array('token' => $token, 'user' => $user->makeVisible($visibleColumns))
                ],200);
            }
        }else{
            return response()->json([
                'data' => array('error' => "Bad Credentials, must be a Walts User.")
            ], 401);
        }

        // $domain = $payload['hd'];
        // $userid = $payload['sub'];

        // $user=User::where('email', $payload['email'])->first();

        // if( empty($user) && $domain == "walts.com") {
        //     $user = User::create([
        //         'name' => $payload['name'],
        //         'google_id' => $g_id,
        //         'email' => $payload['email'],
        //         'password' => Hash::make(str_random(8)),
        //         //TODO: Get real token and refresh token from google
        //         'g_token' => Hash::make(str_random(8)),
        //         'g_refresh_token' => Hash::make(str_random(8)),
        //     ]);
        // }

        // if($payload) {
        //     $domain = $payload['hd'];
        //     Log::debug($domain);
        //     if($user['google_id'] == $g_id && $domain == "walts.com") {
        //         //Check if user already has a token
        //         \Auth::loginUsingId($user['id']);
        //         $userCheck = DB::table('users')->where('email', $username)->first();
        //         $token = DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->count() < 1
        //             ? $user->createToken('api_access_token')->accessToken
        //             // \Auth::user()->createToken('my_user', [])
        //             // $user->createToken('Test Token')->accessToken;
        //             : DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->first();
                   
        //         $token = DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->first();
        //             // return json_encode($token);
        //         // $token = print_r($token, true);
        //         // $token = print_r($token->expires_at, true);
        //         // Log::debug($token);
                
        //         if ( Carbon::parse($token->expires_at)->gte(Carbon::now())) {
        //             //Delete old tokens
        //             DB::table('oauth_access_tokens')->where('user_id', $userCheck->id)->delete();
        //             //Make new token
        //             $token = \Auth::user()->createToken('api_access_token', []);
        //             Log::debug("we are coming in here");
        //         }

        //         return $token;

        //     }
        //     else {
        //         return "Not a Walts user";
        //     }

        // } else {
        //     return "Invalid authentication token";
        // }


    }

    public function index(Request $request)
    {
        return $request->user();
    }
    public function search(Request $request)

    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $account = Account::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function AllUsers()
    {
        try {
            $users = User::select('id', 'name')->get();
            return response()->json([
                'data' => $users->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function AdminAllUsers()
    {
        $authUser = Auth::user();
        if($authUser->auth_super_user){
            try {
                $visibleColumns = Array('auth_super_user', 'auth_sales', 'auth_admin', 'auth_operations');
                $users = User::all();
                $users->makeVisible($visibleColumns);
                return response()->json([
                    'data' => $users->toArray()
                ], 200);
            }

            catch(exception $e)
            {
                return response()->json([
                    'data' => array('error'=> "No Users", 'exceptionMessage' => $e->getMessage())
                ], 404);
            }
        }

        return response()->json([
            'data' => array('error'=> "Not Authorized for User information.")
        ], 401);
    }

    public function UpdateUser(Request $request)
    {
        $data = $request->all();
        $authUser = Auth::user();
        if($authUser->auth_super_user){
            try {
                $visibleColumns = Array('auth_super_user', 'auth_sales', 'auth_admin', 'auth_operations');
                $user = User::where('id', $data['id'])->first();
                $user->makeVisible($visibleColumns);
                $user->auth_super_user = $data['auth_super_user'];
                $user->auth_admin = $data['auth_admin'];
                $user->auth_operations = $data['auth_operations'];
                $user->auth_sales = $data['auth_sales'];
                $user->save();
                return response()->json([
                    'data' => $user->toArray()
                ], 200);
            }

            catch(exception $e)
            {
                return response()->json([
                    'data' => array('error'=> "Error Updating User", 'exceptionMessage' => $e->getMessage())
                ], 404);
            }
        }

        return response()->json([
            'data' => array('error'=> "Not Authorized for User information.")
        ], 401);
    }

}
