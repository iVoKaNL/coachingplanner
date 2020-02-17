<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Student::class, function (Faker $faker) {
    return [
        'studentnumber' => rand(1110000, 1120000),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'coach_id' => \App\Models\User::first()->id,
        'guid' => \App\Models\User::createGuid()
    ];
});
