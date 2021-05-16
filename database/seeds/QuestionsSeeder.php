<?php

use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    private $questionsNumber = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Question::class, $this->questionsNumber)->create();

        $users = App\User::all();
        $topics = App\Topic::all();
        App\Question::latest()->take($this->questionsNumber)->get()->each(function ($question) use ($users, $topics) {
            // Adding topics
            $topicsNumber = rand(1, 5);
            $currentTopics = $topics->random($topicsNumber)->pluck('id')->toArray();
            $question->topics()->attach($currentTopics);
            // Adding in favorites
            $rand = rand(2, 18);
            $question->favorites()->attach(
                $users->random($rand)->pluck('id')->toArray()
            );
            $question->increment('favorites_counted', $rand);
            // Voting questions
            $votes = [-1, 1];
            for ($i = 0; $i < rand(1, count($users)); $i++) {
                $user = $users[$i];
                $user->voteQuestion($question, $votes[rand(0, 1)]);
            }
        });
    }
}
