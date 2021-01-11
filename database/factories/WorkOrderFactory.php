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

$factory->define(App\Models\WorkOrder::class, function (Faker\Generator $faker) {


    return [


        'created_at' => $faker->dateTimeThisYear,
        'updated_at' => $faker->dateTimeThisYear,
        'sold_contact_id' => 1,
        'ship_contact_id' => 1,
        'bill_contact_id' => 1,
        'sold_account_id' => 1,
        'workorder_type' => 'Installation',
        'workorder_class' => "Installation",
        'workorder_status' => 'Building',
        'requirement_status' => 'Pending',
        'quote_id' => '1',
        'invoice_id' => '1',

        'primary_sales_id' => 1,
        'second_sales_id' => 1,
        'third_sales_id' => 1,

        'product_total'=> 2500,
        'labor_total' => 500,
        'shipping_total' => 25,
        'tax_total' => 50,
        'total' => 3075,
        'notes' => $faker->text(100),
        'lead_source' => $faker->text(10),
        'primary_development' => 'Phone', // on site , phone, store
        'primary_product_interest' => 'Television',
        'primary_feature_interest' => 'Netflix 4k Streaming',
        'demo_affinity' => 'Boomer',



    ];
});
