<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use Illuminate\Support\Facades\Hash;
use Input;


class AuthController extends Controller
{
    //
    protected $redirectTo = '/';
 
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->with(["access_type" => "offline", "prompt" => "consent select_account"])
        ->redirect();
    }

    public function getApprovedGoogleUser(Request $request) {

        $user=User::where('email', $request->input('email'))->first();
        
        if(!empty($user)){
            $emailDomain = explode("@", $user['email'])[1];

            if($emailDomain === "walts.com") {
                $query = http_build_query([
                    'client_id' => $request->input('client_id'),
                    'redirect_uri' => $request->input('callback_url'),
                    'response_type' => 'code',
                    'scope' => '',
                ]);

                return "Pass"; //redirect(env('APP_URL') . $query);

            }else{
                return "Fail walts check"; //redirect()->away($request->input('fail_url'));
            }
        }

        return "Fail user check";
        
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $emailDomain = explode("@", $user->email);

            if( $emailDomain[1] === "walts.com" /* && email is in list of acceptable emails */){
                $userModel = $this->addNew($user);
                Auth::loginUsingId($userModel->id);
                return redirect()->route('home');
            }
            else { //Redirect to login page because the account is invalid
                return redirect('login');
            }

        } catch (Exception $e) {
            return redirect('login');
        }
    }

    /* --------------------------------------------------------------------------------------------*/
    
    public function addNew($user)
    {
        $authUser=User::where('google_id', $user->id)->first();
        if(!empty($authUser)){
            return $authUser;
        }
        else{
            $authUser=User::where('email', $user->email)->first();
            if(!empty($authUser)) {
                $updateArray = array(
                    'google_id' => $user->user['id'],
                    'g_token' => $user->token,
                    'g_refresh_token'=> $user->refreshToken,
                );
                
                User::where('email', $user->email)->update($updateArray);
                
                return $authUser;
            }
            else {
                
                return User::create([
                    'name' => $user->name,
                    'google_id' => $user->user['id'],
                    'email' => $user->email,
                    'password' => Hash::make(str_random(8)), //Use Passport to get password?
                    'g_token' => $user->token,
                    'g_refresh_token' => $user->refreshToken,
                ]);
            }
        }
    }
}