<?php

namespace App\Policies;

use App\User;
use App\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has access to a topics (edit/delete)
     *
     * @param  User  $user
     * @param  Topic $topic
     * @return bool
     */
    public function access(User $user, Topic $topic)
    {
        return ($user->is_admin == 1 || $topic->user_id == $user->id) ? true : null;
    }
}
