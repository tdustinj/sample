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


class PassportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('cors');
    }


    public function oauthClientsPost(Request $request) {

        $http = new GuzzleHttp\Client;

        $response = $http->post('https://test-api-ospos.walts.com/oauth/clients', $request);

        return $response;
    }
}
