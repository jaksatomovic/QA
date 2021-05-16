<?php

namespace App\Http\Controllers\Admin;

use App\Question;
use App\Http\Controllers\Admin\AdminController;
use App\Notifications\CloseQuestionNotification;
use App\Notifications\DeleteQuestionNotification;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\QuestionRequest;
use App\Http\Filters\QuestionFilters;

class QuestionController extends AdminController
{
    /**
     * Return counted per day questions for last 7 days
     *
     * @return array with results
     */
    public function countPerDay()
    {
        return $this->countGroupedByDay(Question::class);
    }

    /**
     * Counting number of questions for selected dates
     *
     * @return integer - questions number
     */
    public function countPerRange()
    {
        return $this->countPerDateRange(Question::class, request()->from, request()->to);
    }

    /**
     * Return filtered questions
     *
     * @param   QuestionFilters $filters
     * @return  array with questions
     */
    public function index(QuestionFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $questions = Question::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'picture', 'created_at', 'points');
                },
                'topics' => function ($query) {
                    $query->select('id', 'title', 'slug');
                },
                'favorited'
            ])->filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($questions));
    }

    /**
     * Open question
     *
     * @param   Question    $question
     * @return  json with response
     */
    public function open(Question $question)
    {
        $question->is_closed = '0';
        $question->save();

        return response()->json([
            'message' => __('Question was opened back').'!',
        ]);
    }

    /**
     * Close question
     *
     * @param   Question    $question
     * @return  json with response
     */
    public function close(Question $question)
    {
        $question->is_closed = '1';
        $question->save();

        if (request()->send_notification) {
            $question->user->notify(new CloseQuestionNotification([
                'id' => $question->id,
                'question' => $question->title,
                'url' => $question->path
            ]));
        }

        return response()->json([
            'message' => __('Question was closed').'!',
        ]);
    }

    /**
     * Update the given question
     *
     * @param   QuestionRequest $request
     * @param   Question $question
     * @return  json with response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->only('title', 'body'));
        $question->touchEdited();

        $question->topics()->sync(
            $request->only('topics')['topics']
        );

        return response()->json([
            'message' => __('Question was edited').'!',
            'body_html' => $question->body_html,
            'slug' => $question->slug,
        ]);
    }

    /**
     * Delete question
     *
     * @param   Question    $question
     * @return  json with response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        if (request()->send_notification) {
            $question->user->notify(new DeleteQuestionNotification([
                'id' => $question->id,
                'question' => $question->title
            ]));
        }

        return response()->json([
            'message' => __('Question was deleted').'!',
        ]);
    }
}
