<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class InventoryTest extends TestCase
{
    /**
     *
     *
     * @return void
     */

    use WithoutMiddleware;

    public function testInventoryIndex()
    {


        $response = $this->json('GET', '/api/inventory');
        // print_r($response);
        $response
            ->assertStatus(200);



    }

    public function testInventoryStore()
    {

        $response = $this->json('POST', '/api/inventory',
            ['products_id' => 2691,
                'model' => 'ML-776X-YZ',
                'brand' => 'SAMSUNG',
                'primary_category' => 'TV-52',
                'part_number' => 'ML-902X-YZ',
                'upc' => '3322052958267',
                'box_code' => '5729477883491',
                'model_bar_code' => '3278640520562',
                'ean' => '2546303075140',
                'asin' => 'B093453T89',
                'serial_tracked' => 1,
                'serial_number' => 'SN80560436SER',
                'alternate_serial_number' => 'ASN7827529NUM',
                'bundle_products_id' => 3453,
                'location_id' => 1,
                'initial_purchase_condition' => 'GRADED',
                'current_condition' => 'USED',
                'current_condition_notes' => 'Labore nostrum dicta eum magni. Incidunt cum molestias ut velit. Vel labore atque fuga molestiae quia aut illum.',
                'selling_status' => 'AVAILABLE',
                'assigned_to_invoice' => 0,
                'sold_at' => '2016-06-25 13:14:07.000000',
                'rma_number' => '123456',
                'rma_tracking_number' => 0,
                'rma_status' => 'COMMITTED',
                'rma_credit_amount_rec' => 25,
                'rma_credit_rec_at' => '2016-07-22 14:09:17.000000',
                'vendor_id' => 12,
                'purchase_order_id' => 10012,
                'ordered_at' => '2016-06-06 15:43:33.000000',
                'received_at' => '2017-01-25 07:54:40.000000',
                'received_by' => 'Trantow',
                'invoice_cost' => 7495.72,
                'program_cost' => 12355.47,
                'billed_amount' => 4487.08,
                'purchase_shipping_cost' => 92.15,
                'fulfillment_type' => 'LOCAL DELIVERY',
                'fulfillment_cost' => 176.35,
                'commission_paid' => 10,
                'spiff_paid' => 1,
                'other_costs' => 0.5,
                'spa' => 10,
                'mdf' => 3,
                'vir' => 1.5,
                'payment_discount' => 2,
                'trailing_credit_program' => 'TC104-234',
                'trailing_credit_program_notes' => 'Trail Credit Notes',
                'trailing_credit_submission_status' => 'NOT SUBMITTED',
                'trailing_credit_claimed_at' => '2017-02-05 21:52:33.000000',
                'trailing_credit_received_at' => '2017-02-15 05:40:27.000000',
                'trailing_credit_amount' => 25,
                'pre_tc_gross_margin' => 0.1,
                'post_tc_gross_margin' => 0.25,
                'initial_gross_margin' => 0.06,
                'program_cost_gross_margin' => 0.06,
                'gross_profit_after_commission_spiff' => 50,
                'final_gross_profit' => 25

            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("model" => "ML-776X-YZ")]
            );


    }






    public function testInventoryUpdate()
    {
        $response = $this->json('PUT', '/api/inventory/1',
            ['products_id' => 2691,
                'model' => 'ML-776X-YZ',
                'brand' => 'SAMSUNG',
                'primary_category' => 'TV-52',
                'part_number' => 'ML-902X-YZ',
                'upc' => '3322052958267',
                'box_code' => '5729477883491',
                'model_bar_code' => '3278640520562',
                'ean' => '2546303075140',
                'asin' => 'B093453T89',
                'serial_tracked' => 1,
                'serial_number' => 'SN80560436SER',
                'alternate_serial_number' => 'ASN7827529NUM',
                'bundle_products_id' => 3453,
                'location_id' => 1,
                'initial_purchase_condition' => 'GRADED',
                'current_condition' => 'USED',
                'current_condition_notes' => 'Labore nostrum dicta eum magni. Incidunt cum molestias ut velit. Vel labore atque fuga molestiae quia aut illum.',
                'selling_status' => 'AVAILABLE',
                'assigned_to_invoice' => 0,
                'sold_at' => '2016-06-25 13:14:07.000000',
                'rma_number' => '123456',
                'rma_tracking_number' => 0,
                'rma_status' => 'COMMITTED',
                'rma_credit_amount_rec' => 25,
                'rma_credit_rec_at' => '2016-07-22 14:09:17.000000',
                'vendor_id' => 12,
                'purchase_order_id' => 10012,
                'ordered_at' => '2016-06-06 15:43:33.000000',
                'received_at' => '2017-01-25 07:54:40.000000',
                'received_by' => 'Trantow',
                'invoice_cost' => 7495.72,
                'program_cost' => 12355.47,
                'billed_amount' => 4487.08,
                'purchase_shipping_cost' => 92.15,
                'fulfillment_type' => 'LOCAL DELIVERY',
                'fulfillment_cost' => 176.35,
                'commission_paid' => 10,
                'spiff_paid' => 1,
                'other_costs' => 0.5,
                'spa' => 10,
                'mdf' => 3,
                'vir' => 1.5,
                'payment_discount' => 2,
                'trailing_credit_program' => 'TC104-234',
                'trailing_credit_program_notes' => 'Trail Credit Notes',
                'trailing_credit_submission_status' => 'NOT SUBMITTED',
                'trailing_credit_claimed_at' => '2017-02-05 21:52:33.000000',
                'trailing_credit_received_at' => '2017-02-15 05:40:27.000000',
                'trailing_credit_amount' => 25,
                'pre_tc_gross_margin' => 0.1,
                'post_tc_gross_margin' => 0.25,
                'initial_gross_margin' => 0.06,
                'program_cost_gross_margin' => 0.06,
                'gross_profit_after_commission_spiff' => 50,
                'final_gross_profit' => 25
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([ "data" => array("model" => "ML-776X-YZ")]
            );



    }
    public function testInventoryShow()
    {
        $response = $this->json('GET', '/api/inventory/1');
        // print_r($response);
        $response
            ->assertStatus(200);



    }



}