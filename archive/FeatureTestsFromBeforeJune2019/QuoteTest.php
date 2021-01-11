<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuoteTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testQuoteIndex()
    {


        $response = $this->json('GET', '/api/quote');
        // print_r($response);
        $response
            ->assertStatus(200);



    }

    public function testQuoteStore()
    {

        $response = $this->json('POST', '/api/quote',
            [
                'sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,
                'sold_account_id' => 1,

                'quote_type' => "In Home",
                'quote_class' => "Install",
                'quote_status' => "Building",

                'primary_sales_id' => 1,
                'second_sales_id' => 1,
                'third_sales_id' => 1,

                'product_total'=> 1000,
                'labor_total' => 300,
                'shipping_total' => 0,
                'tax_total' => 34.56,
                'total' => 1334.56,
                'notes' => "Waiting on Switch Research for Main Room",
                'lead_source' => "Google Local Products In Store",
                'primary_development' => "Phone", // on site , phone, store
                'primary_product_interest' => "84UN9500",
                'primary_feature_interest' => "4k Netflix",
                'demo_affinity' => "Boomer Enlightened",
                'approval_status' => "Not Approved",
                'approval_status_notes' => "Not Submitted"


            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("approval_status" => "Not Approved")]
            );


    }






    public function testQuoteUpdate()
    {

        $response = $this->json('PUT', '/api/quote/1',
            ['sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,

                'quote_type' => "In Home",
                'quote_class' => "Install",
                'quote_status' => "Building",

                'primary_sales_id' => 1,
                'second_sales_id' => 1,
                'third_sales_id' => 1,

                'product_total'=> 1000,
                'labor_total' => 300,
                'shipping_total' => 0,
                'tax_total' => 34.56,
                'total' => 1334.56,
                'notes' => "Waiting on Switch Research for Main Room",
                'lead_source' => "Google Local Products In Store",
                'primary_development' => "Phone", // on site , phone, store
                'primary_product_interest' => "84UN9500",
                'primary_feature_interest' => "4k Netflix",
                'demo_affinity' => "Boomer Enlightened",
                'approval_status' => "Approved",
                'approval_status_notes' => "Not Submitted"

            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("approval_status" => "Approved")]
            );



    }
    public function testQuoteShow()
    {
        $response = $this->json('GET', '/api/quote/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}