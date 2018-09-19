<?php

use Faker\Generator as Faker;

$factory->define(App\Gallery::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text(50),
    ];
});
