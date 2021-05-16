<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class SpacesSeeder extends Seeder
{
    private $spacesNumber = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < $this->spacesNumber; $i++) {
            $topic = App\User::inRandomOrder()->first()->spaces()->create([
                'is_space' => 1,
                'is_approved' => 1,
                'title' => $faker->unique()->word(),
                'body' => $faker->paragraphs(rand(2, 7), true),
                'picture' => 'images/'.$faker->image('storage/app/public/images', 400, 400, null, false),
            ]);
        }
    }
}
