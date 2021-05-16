<?php

use App\User;
use App\Question;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'title' => rtrim($faker->sentence(rand(5, 10)), '.').'?',
        'body' => $faker->paragraphs(rand(1, 5), true),
        'user_id' => User::pluck('id')->random(),
        'views' => rand(0, 100),
    ];
});
