<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class InvoiceItemTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;
    public function testInvoiceItemStore()
    {

        $response = $this->json('POST', '/api/invoice/item',
            [

                'invoice_id' => 1,
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
                'invoice_date' => '2016-10-21',
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
            ->assertJson([ "data" => array("product_id" => "1")]
            );


    }





    public function testInvoiceItemIndex()
    {


        $response = $this->json('GET', '/api/invoice/item');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



    public function testInvoiceItemUpdate()
    {

        $response = $this->json('PUT', '/api/invoice/item/1',
            [ 'invoice_id' => 1,
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
                'invoice_date' => '2016-10-21',
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
            ->assertJson([ "data" => array("product_id" => "1")]
            );



    }
    public function testInvoiceItemShow()
    {
        $response = $this->json('GET', '/api/invoice/item/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}