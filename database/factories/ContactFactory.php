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

$factory->define(App\Models\Contact::class, function (Faker\Generator $faker) {


    return [

        'created_at' => $faker->dateTimeThisYear,
        'updated_at' => $faker->dateTimeThisYear,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_initial' => 'T',
        'personal_email' => $faker->unique()->safeEmail,
        'work_email' => $faker->unique()->safeEmail,
        'third_party_email' => $faker->unique()->safeEmail,
         'home_phone' => $faker->phoneNumber,
        'work_phone' => $faker->phoneNumber,
        'mobile_phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'address2' => $faker->buildingNumber,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => $faker->postcode,
        'country' => $faker->country,
        'source' => 'faker test',

    ];
});
