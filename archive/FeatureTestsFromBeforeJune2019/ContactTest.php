<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ContactTest extends TestCase
{
    /**
     *  [created_at] => 2016-03-03 20:38:47
    [updated_at] => 2016-02-09 20:43:05
    [first_name] => Sterling
    [last_name] => Maggio
    [middle_initial] => T
    [personal_email] => sipes.shannon@example.com
    [work_email] => emely.johns@example.net
    [third_party_email] => cschuster@example.com
    [home_phone] => (494) 322-7164 x229
    [work_phone] => +1-667-460-6676
    [mobile_phone] => 870.787.8977
    [address] => 86545 Cummings Isle Apt. 355
    [address2] => 18105
    [city] => Malvinafurt
    [state] => Alabama
    [zip] => 61576-5778
    [country] => Netherlands
    [source] => faker test
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testContactIndex()
    {


        $response = $this->json('GET', '/api/contact');
       // print_r($response);
        $response
            ->assertStatus(200);



    }

    public function testContactStore()
    {

        $response = $this->json('POST', '/api/contact',
            ['first_name' => 'Sterling',
    'last_name' => 'Maggio',
    'middle_initial' => 'T',
    'personal_email' => 'sipes.shannon@example.com',
    'work_email' => 'emely.johns@example.net',
    'third_party_email' => 'cschuster@example.com',
    'home_phone' => '(494) 322-7164 x229',
    'work_phone' => '+1-667-460-6676',
    'mobile_phone' => '870.787.8977',
    'address' => '86545 Cummings Isle Apt. 355',
    'address2' => '18105',
    'city' => 'Malvinafurt',
    'state' => 'Alabama',
    'zip' => '61576-5778',
    'country' => 'Netherlands',
    'source' => 'test framework',
                'account_id' => '1'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("first_name" => "Sterling")]
            );


    }






    public function testContactUpdate()
    {
        $response = $this->json('PUT', '/api/contact/1',
            ['first_name' => 'Sterling',
                'last_name' => 'Maggio',
                'middle_initial' => 'T',
                'personal_email' => 'sipes.shannon@example.com',
                'work_email' => 'emely.johns@example.net',
                'third_party_email' => 'cschuster@example.com',
                'home_phone' => '(494) 322-7164 x229',
                'work_phone' => '+1-667-460-6676',
                'mobile_phone' => '870.787.8977',
                'address' => '86545 Cummings Isle Apt. 355',
                'address2' => '18105',
                'city' => 'Malvinafurt',
                'state' => 'Alabama',
                'zip' => '61576-5778',
                'country' => 'Netherlands',
                'source' => 'test framework'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("first_name" => "Sterling")]
            );



    }
    public function testContactShow()
    {
        $response = $this->json('GET', '/api/contact/1');
       // print_r($response);
        $response
            ->assertStatus(200);



    }



}