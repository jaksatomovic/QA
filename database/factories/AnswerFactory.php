<?php

use App\User;
use App\Question;
use App\Answer;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'question_id' => Question::pluck('id')->random(),
        'user_id' => User::pluck('id')->random(),
        'body' => $faker->paragraphs(rand(2, 7), true),
    ];
});
