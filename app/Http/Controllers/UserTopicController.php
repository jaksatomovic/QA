<?php

namespace App\Http\Controllers;

use App\Topic;

class UserTopicController extends Controller
{
    /**
     * Create a new UserTopicController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Follow topic or space
     *
     * @param   Topic $topic
     * @return  json - updated number of followers
     */
    public function store(Topic $topic)
    {
        if ($topic->is_approved == 1) {
            $topic->followers()->attach(auth()->id());

            $followers = $topic->updateFollowersCounter($topic);
        }

        return response()->json([
            'followers' => $followers,
        ]);
    }

    /**
     * Unfollow topic or space
     *
     * @param   Topic $topic
     * @return  json - updated number of followers
     */
    public function destroy(Topic $topic)
    {
        $topic->followers()->detach(auth()->id());

        $followers = $topic->updateFollowersCounter($topic);

        return response()->json([
            'followers' => $followers,
        ]);
    }
}
