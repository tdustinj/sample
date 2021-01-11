<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class InvoiceTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testInvoiceIndex()
    {


        $response = $this->json('GET', '/api/invoice');
        // print_r($response);
        $response
            ->assertStatus(200);



    }

    public function testInvoiceStore()
    {

        $response = $this->json('POST', '/api/invoice',
            [
                'sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,
                'sold_account_id' => 1,

                'invoice_type' => "In Home",
                'invoice_class' => "Install",
                'invoice_status' => "Building",

                'primary_sales_id' => 1,
                'second_sales_id' => 1,
                'third_sales_id' => 1,
                'workorder_id' => 1,
                'quote_id' => 1,


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
                'demo_affinity' => "Boomer Enlightened"



            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("quote_id" => "1")]
            );


    }






    public function testInvoiceUpdate()
    {

        $response = $this->json('PUT', '/api/invoice/1',
            ['sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,

                'invoice_type' => "In Home",
                'invoice_class' => "Install",
                'invoice_status' => "Building",

                'primary_sales_id' => 1,
                'second_sales_id' => 1,
                'third_sales_id' => 1,
                'workorder_id' => 1,
                'quote_id' => 1,

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

            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("workorder_id" => "1")]
            );



    }
    public function testInvoiceShow()
    {
        $response = $this->json('GET', '/api/invoice/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}