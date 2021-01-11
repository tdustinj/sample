<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class WorkOrderItemTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;



    public function testWorkOrderItemStore()
    {

        $response = $this->json('POST', '/api/workorder/item',
            [
                'workorder_id' => 1,
                'product_id' => 1,
                'employee_id' => 1,

                'model' => "WD-82",
                'part_number' => "WD-82N",
                'brand' => "Mitsubishi",
                'description' => "82 Inch Dlp HDTV",
                'upc' => "0989828374",

                'category' => "DLP TV",
                'item_class' => "Serial",
                'item_type' => "Complete",
                'serial_number' => "123LLLKJ7JH",
                'workorder_date' => '2016-10-21',
                'source_vendor' => 'MDEA',
                'condition' => 'NEW',



                'tax_code' => 'TAX_AZ_TEMPE',
                'tax_amount' => 50,
                'ext_price' => 100,
                'unit_price' => 100,
                'number_units' => 1,
                'standard_gross_profit' => 10,
                'final_gross_profit' => 15

            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("workorder_id" => "1")]
            );


    }



    public function testWorkOrderItemIndex()
    {


        $response = $this->json('GET', '/api/workorder/item');
        // print_r($response);
        $response
            ->assertStatus(200);



    }


    public function testWorkOrderItemUpdate()
    {

        $response = $this->json('PUT', '/api/workorder/item/1',
            [
                'workorder_id' => 1,
                'product_id' => 1,
                'employee_id' => 1,

                'model' => "WD-82",
                'part_number' => "WD-82N",
                'brand' => "Mitsubishi",
                'description' => "82 Inch Dlp HDTV",
                'upc' => "0989828374",

                'category' => "DLP TV",
                'item_class' => "Serial",
                'item_type' => "Complete",
                'serial_number' => "123LLLKJ7JH",
                'workorder_date' => '2016-10-21',
                'source_vendor' => 'MDEA',
                'condition' => 'NEW',



                'tax_code' => 'TAX_AZ_TEMPE',
                'tax_amount' => 50,
                'ext_price' => 100,
                'unit_price' => 100,
                'number_units' => 1,
                'standard_gross_profit' => 10,
                'final_gross_profit' => 15
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("workorder_id" => "1")]
            );



    }
    public function testWorkOrderItemShow()
    {
        $response = $this->json('GET', '/api/workorder/item/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}