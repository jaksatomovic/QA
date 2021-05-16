<?php

namespace App\Http\Controllers;

use App\Question;

class VoteQuestionController extends Controller
{
    /**
     * Create a new VoteQuestionController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vote a question
     *
     * @param   Question $question
     * @return  json with message and updated votes number
     */
    public function __invoke(Question $question)
    {
        $vote = (int) request()->vote;

        $votesCounted = auth()->user()->voteQuestion($question, $vote);

        return response()->json([
            'message' => __('Vote added').'!',
            'votesCounted' => $votesCounted
        ]);
    }
}
