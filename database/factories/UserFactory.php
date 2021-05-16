<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'picture' => 'avatars/'.$faker->image('storage/app/public/avatars', 400, 400, null, false),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'credentials' => $faker->sentence(rand(3, 8)),
        'location' => $faker->country,
        'about' => $faker->paragraphs(rand(1, 5), true),
        'email_notifications' => '0',
    ];
});
