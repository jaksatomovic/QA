<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\User;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\AnswerRequest;
use App\Http\Filters\AnswerFilters;
use App\Notifications\AddAnswerNotification;
use App\Notifications\SetBestAnswerNotification;

class AnswerController extends Controller
{
    /**
     * Create a new AnswerController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Return answers for question
     *
     * @param   Question        $question
     * @param   User            $user
     * @param   AnswerFilters   $filters
     * @return  \Illuminate\Http\Response
     */
    public function index(Question $question, User $user, AnswerFilters $filters)
    {
        $model = ($question->id) ? $question : $user;
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.answers');

        return $model->answers()
            ->with(['user'])
            ->filter($filters)
            ->simplePaginate($limit)
            ->appends(Input::except('page'));
    }

    /**
     * Return the answer
     *
     * @param   Answer $answer
     * @return  answer object
     */
    public function show(Answer $answer)
    {
        return $answer;
    }

    /**
     * Store a newly created answer in database.
     *
     * @param   AnswerRequest $request
     * @param   Question $question
     * @return  \Illuminate\Http\Response
     */
    public function store(AnswerRequest $request, Question $question)
    {
        if ($question->is_closed == 1) {
            return response()->json([
                'message' => __('Question is closed'),
            ]);
        }

        $answer = $question->answers()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        $answer->question->user->notify(new AddAnswerNotification([
            'id' => $answer->id,
            'question' => $answer->question->title,
            'url' => $answer->question->path
        ]));

        return response()->json([
            'message' => __('Your Answer was added').'!',
            'answer' => $answer->load('user'),
        ]);
    }

    /**
     * Update the given answer
     *
     * @param   AnswerRequest $request
     * @param   Question $question
     * @param   Answer $answer
     * @return  \Illuminate\Http\Response
     */
    public function update(AnswerRequest $request, Question $question, Answer $answer)
    {
        $this->authorize('access', $answer);

        $answer->update($request->only('body'));

        return response()->json([
            'message' => __('Your Answer was edited').'!',
            'body_html' => $answer->body_html,
        ]);
    }

    /**
     * Delete the given answer
     *
     * @param   Question $question
     * @param   Answer $answer
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('access', $answer);

        $answer->delete();

        return response()->json([
            'message' => __('Your Answer was deleted').'!',
        ]);
    }

    /**
     * Mark answer as the best
     *
     * @param   Answer $answer
     * @return  \Illuminate\Http\Response
     */
    public function accept(Answer $answer)
    {
        $this->authorize('accept', $answer);

        $answer->question->acceptBestAnswer($answer);

        $answer->user->notify(new SetBestAnswerNotification([
            'id' => $answer->id,
            'question' => $answer->question->title,
            'url' => $answer->question->path
        ]));

        return response()->json([
            'message' => __('Answer set as best answer'),
        ]);
    }
}
