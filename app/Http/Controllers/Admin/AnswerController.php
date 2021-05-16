<?php

namespace App\Http\Controllers\Admin;

use App\Answer;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\AnswerRequest;
use App\Http\Filters\AnswerFilters;
use App\Notifications\DeleteAnswerNotification;

class AnswerController extends AdminController
{
    /**
     * Return counted per day answers for last 7 days
     *
     * @return array with results
     */
    public function countPerDay()
    {
        return $this->countGroupedByDay(Answer::class);
    }

    /**
     * Counting number of answers for selected dates
     *
     * @return integer - answers number
     */
    public function countPerRange()
    {
        return $this->countPerDateRange(Answer::class, request()->from, request()->to);
    }

    /**
     * Return filtered answers
     *
     * @param   AnswerFilters $filters
     * @return  array with answers
     */
    public function index(AnswerFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $answers = Answer::with('user')
            ->filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($answers));
    }

    /**
     * Update the given answer
     *
     * @param   AnswerRequest $request
     * @param   Answer $answer
     * @return  \Illuminate\Http\Response
     */
    public function update(AnswerRequest $request, Answer $answer)
    {
        $answer->update($request->only('body'));

        return response()->json([
            'message' => __('Answer was edited').'!',
            'body_html' => $answer->body_html,
        ]);
    }

    /**
     * Delete answer
     *
     * @param   Answer   $answer
     * @return  json with response
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();

        if (request()->send_notification) {
            $answer->user->notify(new DeleteAnswerNotification([
                'id' => $answer->id,
                'answer' => $answer->body_text
            ]));
        }

        return response()->json([
            'message' => __('Answer was deleted').'!',
        ]);
    }
}
