<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testAccountIndex()
    {

     /*   $this->get('/api/account')
            ->seeJsonStructure([
                "*" => ["created_at","updated_at","account_name","notes","tax_code","tax_id"
                    ,"terms_number_days","account_type","credit_line_limit","terms_payment_type","source"]
            ]);
       */
        $response = $this->json('GET', '/api/account');

        $response
            ->assertStatus(200);



    }

    public function testAccountStore()
    {

        $response = $this->json('POST', '/api/account',
            ['account_name' => 'Sally Company'
                , 'notes' => 'This is a note'
                , 'tax_code' => 'NON_TAXABLE'
                , 'tax_id' => '86-12345667'
                , 'terms_number_of_days' => '30'
                , 'account_type' => 'B2B'
                , 'credit_line_limit' => '100'
                , 'terms_number_days' => '30'
                , 'terms_payment_type' => 'COD'
                , 'source' => 'test'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("account_name" => "Sally Company")
            ]
            );


    }






    public function testAccountUpdate()
    {
        $response = $this->json('PUT', '/api/account/1',
            ['account_name' => 'Sally Company'
                , 'notes' => 'This is a note'
                , 'tax_code' => 'NON_TAXABLE'
                , 'tax_id' => '86-12345667'
                , 'terms_number_of_days' => '30'
                , 'account_type' => 'B2B'
                , 'credit_line_limit' => '100'
                , 'terms_number_days' => '30'
                , 'terms_payment_type' => 'COD'
                , 'source' => 'test'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("account_name" => "Sally Company")
                ]
            );


    }
    public function testAccountShow()
    {
        $response = $this->json('GET', '/api/account/1');

        $response
            ->assertStatus(200);



    }

    public function search()
    {

        $response = $this->json('POST', '/api/account/search',
            ['searchKey' => 'account_name',
            'searchValue' => 'Sally']);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("account_name" => "Sally Company")
                ]
            );


    }

}
