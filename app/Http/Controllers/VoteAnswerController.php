<?php

namespace App\Http\Controllers;

use App\Answer;

class VoteAnswerController extends Controller
{
    /**
     * Create a new VoteAnswerController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vote an answer
     *
     * @param   Answer $answer
     * @return  json - message with updated number of votes
     */
    public function __invoke(Answer $answer)
    {
        $vote = (int) request()->vote;

        $votesCounted = auth()->user()->voteAnswer($answer, $vote);

        return response()->json([
            'message' => __('Vote added').'!',
            'votesCounted' => $votesCounted
        ]);
    }
}
