<?php

use Illuminate\Database\Seeder;

class FollowingSeeder extends Seeder
{
    private $minTopicsFollowed = 2;
    private $maxTopicsFollowed = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();
        $topics = App\Topic::all();

        // Following topics
        foreach ($users as $user) {
            // Randomize number of topics followed
            $topicsFollowed = rand($this->minTopicsFollowed, $this->maxTopicsFollowed);
            // Follow topics
            for ($i = 0; $i < $topicsFollowed; $i++) {
                // Follow topic
                $currentTopic = $topics->random(1);
                $user->followTopicsSpaces()->syncWithoutDetaching($currentTopic);
                $currentTopic[0]->followers_counted = $currentTopic[0]->followers()->count();
                $currentTopic[0]->save();
            }
        }
    }
}
