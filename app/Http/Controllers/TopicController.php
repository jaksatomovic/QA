<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\TopicCreateRequest;
use App\Http\Requests\TopicUpdateRequest;
use App\Http\Filters\TopicFilters;

class TopicController extends Controller
{
    /**
     * Create a new TopicController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Return the filtered topics
     *
     * @param   TopicFilters $filters
     * @return  \Illuminate\Http\Response
     */
    public function index(TopicFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.topics');

        return Topic::latest()->with(['followed'])
            ->where('is_approved', 1)
            ->withCount('questions')
            ->filter($filters)
            ->simplePaginate($limit)
            ->appends(Input::except('page'));
    }

    /**
     * Return topics for questions page
     *
     * @return  \Illuminate\Http\Response
     */
    public function all()
    {
        return Topic::select(['id', 'title', 'slug'])
            ->where('is_approved', 1)->get();
    }

    /**
     * Store a newly created topic or space in database
     *
     * @param   TopicCreateRequest $request
     * @return  json with topic/space url
     */
    public function store(TopicCreateRequest $request)
    {
        $topic = $request->user()->spaces()->create([
            'is_space' => 1,
            'title' => $request->title,
            'body' => $request->body,
            'picture' => $request->file('image')->store('images', 'public'),
        ]);

        return response()->json([
            'slug' => $topic->slug,
        ]);
    }

    /**
     * Update the given topic / space
     *
     * @param   TopicUpdateRequest $request
     * @param   Topic $topic
     * @return  json with response
     */
    public function update(TopicUpdateRequest $request, Topic $topic)
    {
        $this->authorize('access', $topic);

        if ($topic->title != $request->title || $request->file('image')) {
            $topic->is_approved = 0;
            $topic->save();
        }

        $topic->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        if ($request->file('image')) {
            $topic->update([
                'picture' => $request->file('image')->store('images', 'public'),
            ]);
        }

        return response()->json([
            'message' => __('Space was edited').'!',
            'body_html' => $topic->body_html,
            'slug' => $topic->slug,
            'is_approved' => $topic->is_approved,
        ]);
    }
}
