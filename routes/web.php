<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
// THIS IS FOR WEB CALLS ORIGINATING FROM *api-ospos.walts.com hosted web applications
// Not to be used for Listing-manager or ospos-web or ospos-native
Route::get('/', function () {
  return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('testBigSearch/test/peter', 'AccountController@testBigSearch');

Route::get('testEnv', function () {
  return App::environment();
});

Route::get('publish', function () {
  // Route logic...

  Redis::publish('quote-edit-channel', json_encode([
    'quote' =>
    [
      'action' => 'update',
      'id' => '28',
      'user' => 'tjones',
      'time' => '3:43'
    ]
  ]));
});

Route::post('auth/createApiUser', 'Auth\RegisterController@createApiUser');
Route::get('auth/google', 'Auth\AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\AuthController@handleGoogleCallback');

Route::post('auth/google/get-approved-user', 'Auth\AuthController@getApprovedGoogleUser')->middleware('cors');

Route::get('/callback', function (Request $request) {
  $http = new GuzzleHttp\Client;
  /*
    echo "<pre>";
    print_r($request);
    exit;
    */
  $response = $http->post(env('APP_URL') . '/oauth/token', [
    'form_params' => [
      'grant_type' => 'authorization_code',
      'client_id' => '14',
      'client_secret' => 'EoBvrq0ZJI4OHEy6nHW4Smf3BrdOi4SYXHAVK2M4',
      'redirect_uri' => 'https://www.google.com',
      'code' => $request->code,
    ],
  ]);

  return json_decode((string)$response->getBody(), true);
});

Route::post('/pass/oauth/post', 'Auth\PassportController@oauthClientsPost')->middleware('cors');


// below used for testing
Route::get('/attachments/upload', 'AttachmentController@index');
Route::post('attachments/attach', 'AttachmentController@attach');
