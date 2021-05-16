<?php

namespace App\Http\Controllers\Admin;

use App\Topic;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Filters\TopicFilters;
use App\Http\Requests\TopicCreateRequest;
use App\Http\Requests\TopicUpdateRequest;
use App\Notifications\ApproveTopicNotification;
use App\Notifications\DisapproveTopicNotification;
use App\Notifications\DeleteTopicNotification;

class TopicController extends AdminController
{
    /**
     * Return counted per day users for last 7 days
     *
     * @return array with results
     */
    public function countPerDay()
    {
        return $this->countGroupedByDay(Topic::class);
    }

    /**
     * Counting number of users for selected dates
     *
     * @return integer - users number
     */
    public function countPerRange()
    {
        return $this->countPerDateRange(Topic::class, request()->from, request()->to);
    }

    /**
     * Counting number of unapproved spaces
     *
     * @return integer - unapproved spaces number
     */
    public function countUnapproved()
    {
        return Topic::where('is_space', '1')
            ->where('is_approved', '0')->count();
    }

    /**
     * Return filtered topics & spaces
     *
     * @param   TopicFilters $filters
     * @return  array with topics
     */
    public function index(TopicFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $topics = Topic::with('followed')
            ->withCount('questions')
            ->filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($topics));
    }

    /**
     * Approve topic
     *
     * @param   Topic    $topic
     * @return  json with response
     */
    public function approve(Topic $topic)
    {
        $topic->is_approved = 1;
        $topic->save();

        if (request()->send_notification && $topic->user_id) {
            $topic->user->notify(new ApproveTopicNotification([
                'id' => $topic->id,
                'title' => $topic->title,
                'url' => $topic->path
            ]));
        }

        return response()->json([
            'message' => __('Space is approved').'!',
        ]);
    }

    /**
     * Disapprove topic
     *
     * @param   Topic    $topic
     * @return  json with response
     */
    public function disapprove(Topic $topic)
    {
        $topic->is_approved = 0;
        $topic->save();

        if (request()->send_notification && $topic->user_id) {
            $topic->user->notify(new DisapproveTopicNotification([
                'id' => $topic->id,
                'title' => $topic->title,
                'url' => $topic->path
            ]));
        }

        return response()->json([
            'message' => __('Space is disapproved').'!',
        ]);
    }

    /**
     * Store a newly created topic or space in database
     *
     * @param   TopicCreateRequest $request
     * @return  json with topic/space url
     */
    public function store(TopicCreateRequest $request)
    {
        $topic = $request->user()->topics()->create([
            'is_approved' => 1,
            'is_space' => 0,
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
            'message' => __('Topic / Space was edited').'!'
        ]);
    }

    /**
     * Delete topic
     *
     * @param   Topic    $topic
     * @return  json with response
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        if (request()->send_notification && $topic->user_id) {
            $topic->user->notify(new DeleteTopicNotification([
                'id' => $topic->id,
                'title' => $topic->title
            ]));
        }

        return response()->json([
            'message' => __('Topic was deleted').'!',
        ]);
    }
}
