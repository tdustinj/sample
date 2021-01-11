<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkOrderTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testWorkOrderIndex()
    {


        $response = $this->json('GET', '/api/workorder');
       //  print_r($response);
        $response
            ->assertStatus(200);



    }

    public function testWorkOrderStore()
    {

        $response = $this->json('POST', '/api/workorder',
            [
                'sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,
                'sold_account_id' => 1,

                'workorder_type' => "In Home",
                'workorder_class' => "Install",
                'workorder_status' => "Building",
                'requirement_status' => 'Pending',
                'quote_id' => '1',
                'invoice_id' => '1',
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
            ->assertJson([ "data" => array("sold_contact_id" => "1")]
            );


    }






    public function testWorkOrderUpdate()
    {

        $response = $this->json('PUT', '/api/workorder/1',
            ['sold_contact_id' => 1,
                'ship_contact_id' => 1,
                'bill_contact_id' => 1,

                'workorder_type' => "In Home",
                'workorder_class' => "Install",
                'workorder_status' => "Building",

                'primary_sales_id' => 1,
                'second_sales_id' => 1,
                'third_sales_id' => 1,
                'requirement_status' => 'Pending',
                'quote_id' => '1',
                'invoice_id' => '1',

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
            ->assertJson([ "data" => array("sold_contact_id" => "1")]
            );



    }
    public function testWorkOrderShow()
    {
        $response = $this->json('GET', '/api/workorder/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}