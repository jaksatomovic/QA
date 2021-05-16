<?php

use App\User;
use App\Question;
use Faker\Generator as Faker;

$factory->define(App\Favorites::class, function (Faker $faker) {
    return [
        'question_id' => Question::pluck('id')->random(),
        'user_id' => User::pluck('id')->random(),
    ];
});
