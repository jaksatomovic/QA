<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminAccountSeeder::class,
            UsersSeeder::class,
            TopicsSeeder::class,
            SpacesSeeder::class,
            QuestionsSeeder::class,
            FollowingSeeder::class,
            AnswersSeeder::class,
        ]);
    }
}
