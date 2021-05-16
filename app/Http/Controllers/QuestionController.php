<?php

namespace App\Http\Controllers;

use App\Question;
use App\Topic;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\QuestionRequest;
use App\Http\Filters\QuestionFilters;

class QuestionController extends Controller
{
    /**
     * Create a new QuestionController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'feed']);
    }

    /**
     * Return filtered questions
     *
     * @param   QuestionFilters $filters
     * @return  array with questions
     */
    public function index(QuestionFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.questions');

        return Question::with([
            'user' => function ($query) {
                $query->select('id', 'name', 'picture', 'created_at', 'points');
            },
            'topics' => function ($query) {
                $query->select('id', 'title', 'slug');
            },
            'favorited'
        ])->filter($filters)
            ->simplePaginate($limit)
            ->appends(Input::except('page'));
    }

    /**
     * Return questions feed
     * If user is not signed in - he will get index function response
     *
     * @param   QuestionFilters $filters
     * @return  array with questions
     */
    public function feed(QuestionFilters $filters)
    {
        if (! \Auth::check()) {
            return $this->index($filters);
        } else {
            $topics = Topic::latest()->whereHas('followers', function ($query) {
                $query->where('user_id', '=', auth()->id());
            })->pluck('id')->toArray();
        }

        return Question::with([
            'user' => function ($query) {
                $query->select('id', 'name', 'picture', 'created_at', 'points');
            },
            'topics' => function ($query) {
                $query->select('id', 'title', 'slug');
            },
            'favorited'
        ])->whereHas('topics', function ($query) use ($topics) {
            $query->whereIn('topic_id', $topics);
        })->filter($filters)
            ->simplePaginate(config('services.pagination.questions'))
            ->appends(Input::except('page'));
    }

    /**
     * Store a newly created question into database
     *
     * @param   QuestionRequest $request
     * @return  json with response
     */
    public function store(QuestionRequest $request)
    {
        $question = $request->user()->questions()
            ->create($request->only('title', 'body'));

        $question->topics()->attach(
            $request->only('topics')['topics']
        );

        return response()->json([
            'message' => __('Your Question was added').'!',
            'slug' => $question->slug,
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
        $this->authorize('access', $question);

        $question->update($request->only('title', 'body'));
        $question->touchEdited();

        $question->topics()->sync(
            $request->only('topics')['topics']
        );

        return response()->json([
            'message' => __('Your Question was edited').'!',
            'body_html' => $question->body_html,
            'slug' => $question->slug,
        ]);
    }

    /**
     * Delete the given question
     *
     * @param   Question $question
     * @return  json with response
     */
    public function destroy(Question $question)
    {
        $this->authorize('access', $question);

        $question->delete();

        return response()->json([
            'message' => __('Your Question was deleted').'!',
        ]);
    }

    /**
     * Close the given question
     *
     * @param   Question $question
     * @return  json with response
     */
    public function close(Question $question)
    {
        $this->authorize('access', $question);

        $question->is_closed = '1';
        $question->save();

        return response()->json([
            'message' => __('Your Question was closed').'!',
        ]);
    }
}
