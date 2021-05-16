<?php

use App\Topic;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'is_approved' => 1,
        'title' => ucfirst($faker->unique()->word()),
        'body' => $faker->paragraphs(rand(2, 7), true),
        'picture' => 'images/'.$faker->image('storage/app/public/images', 400, 400, null, false),
    ];
});
