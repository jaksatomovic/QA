<?php

use Illuminate\Database\Seeder;

class TopicsSeeder extends Seeder
{
    private $topicsNumber = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Topic::class, $this->topicsNumber)->create();
    }
}
