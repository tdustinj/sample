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

$factory->define(App\Models\Account::class, function (Faker\Generator $faker) {


    return [

        'created_at' => $faker->dateTimeThisYear,
        'updated_at' => $faker->dateTimeThisYear,
        'account_name' => $faker->firstName . '' . $faker->lastName,
        'notes' => $faker->text,
        'tax_code' => 'TAXABLE',
        'tax_id' => '123456789ABC',
        'account_type' => 'B2C',
         'credit_line_limit' => '100',
        'terms_number_days' => '15',
        'terms_payment_type' => 'PREPAY',
        'source' => 'faker test',


    ];
});
