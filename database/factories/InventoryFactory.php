<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Inventory::class, function (Faker\Generator $faker) {


    return [

        'created_at' => $faker->dateTimeThisYear,
        'updated_at' => $faker->dateTimeThisYear,
        'products_id' => $faker->unique()->numberBetween(1000, 10000) ,
    'model' => 'ML-' . $faker->unique()->numberBetween(10, 999) . 'X-YZ' ,
    'brand' => $faker->randomElements(array('LG', 'SAMSUNG', 'POLK-Audio', 'Control4', 'Pioneer Elite')),
    'primary_category' => 'TV-' . $faker->numberBetween(10, 90) ,
    'part_number' => 'ML-' . $faker->unique()->numberBetween(10, 999) . 'X-YZ' ,
    'upc' => $faker->ean13 ,
    'box_code' => $faker->ean13 ,
    'model_bar_code' => $faker->ean13 ,
    'ean' => $faker->ean13 ,
    'asin' => 'B093453T89' ,
    'serial_tracked' => $faker->boolean() ,
    'serial_number' => 'SN' . $faker->numberBetween(100000, 90000000) . 'SER',
    'alternate_serial_number' =>  'ASN' . $faker->numberBetween(100000, 90000000) . 'NUM' ,
    'bundle_products_id' =>  $faker->unique()->numberBetween(1000, 10000) ,

    // actual product disposition
    'location_id' => $faker->randomElements(array(1, 2, 3)) ,

    'initial_purchase_condition' => $faker->randomElements(array('USED','NEW', 'GRADED', 'REFURBISHED')) ,
    'current_condition' => $faker->randomElements(array('USED','NEW', 'GRADED', 'REFURBISHED')) ,
    'current_condition_notes' => $faker->text(125) ,
    'selling_status' => $faker->randomElements(array('RESERVED LOCAL','RESERVED MARKETPLACE','AVAILABLE', 'COMMITTED')) ,
    'assigned_to_invoice' => $faker->randomElements(array(123456,0,167546,0,0,0,0,0)) ,
    'sold_at' => $faker->dateTimeThisYear ,
    // rma information
    'rma_number' => $faker->randomElements(array(123456,0,167546,0,0,0,0,0)) ,
    'rma_tracking_number' => $faker->randomElements(array(1234562099098475834,0,556790000167546,0,0,0,0,0)) ,
    'rma_status' => $faker->randomElements(array('RESERVED LOCAL','RESERVED MARKETPLACE','AVAILABLE', 'COMMITTED')) ,
        'rma_credit_amount_rec' => 25 ,
    'rma_credit_rec_at' => $faker->dateTimeThisYear ,

    // purchased from
    'vendor_id' => 12 ,
    'purchase_order_id' => 10012 ,
    'ordered_at' => $faker->dateTimeThisYear ,
    'received_at' => $faker->dateTimeThisMonth ,
    'received_by' => $faker->lastName ,


    // costs associated with purchase
    'invoice_cost' => $faker->randomFloat(2,10,15000) ,
    'program_cost' => $faker->randomFloat(2,10,15000) ,
    'billed_amount' => $faker->randomFloat(2,10,15000) ,
    'purchase_shipping_cost' => $faker->randomFloat(2,0,100) ,

    //cost associated with selling/fulfillment
    'fulfillment_type' => $faker->randomElements(array('LOCAL DELIVERY','LTL','UPS', 'FBA', 'DISTRIBUTOR')) ,
    'fulfillment_cost' => $faker->randomFloat(2,10,300) ,
    'commission_paid' => 10 ,
    'spiff_paid' => 1 ,
    'other_costs' => .50 ,


    // backend or upfront discounts to cost of product
    'spa' => 10 ,
    'mdf' => 3 ,
    'vir' => 1.5 ,
    'payment_discount' => 2 ,
    'trailing_credit_program' => 'TC104-234' ,
    'trailing_credit_program_notes' => 'Trail Credit Notes' ,
    'trailing_credit_submission_status' => 'NOT SUBMITTED' ,
    'trailing_credit_claimed_at' => $faker->dateTimeThisMonth  ,
    'trailing_credit_received_at' => $faker->dateTimeThisMonth ,
    'trailing_credit_amount' => 25 ,

    // computed columns

    'pre_tc_gross_margin' => .10 ,
    'post_tc_gross_margin' => .25 ,
    'initial_gross_margin' => .06 ,
    'program_cost_gross_margin' => .06 ,
    'gross_profit_after_commission_spiff' => 50,
    'final_gross_profit' => 25 ,



    ];
});
