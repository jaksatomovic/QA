<?php

use Illuminate\Database\Seeder;

class AnswersSeeder extends Seeder
{
    private $answersNumber = 150;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Answer::class, $this->answersNumber)->create();

        $users = App\User::all();
        App\Answer::latest()->take($this->answersNumber)->get()->each(function ($answer) use ($users) {
            // Voting answers
            $votes = [-1, 1];
            for ($i = 0; $i < rand(1, count($users)); $i++) {
                $user = $users[$i];
                $user->voteAnswer($answer, $votes[rand(0, 1)]);
            }
        });
    }
}
