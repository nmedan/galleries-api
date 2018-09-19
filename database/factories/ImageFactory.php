<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'image_url' => $faker->imageUrl($width=200, $height=100),
    ];
});
