<?php

namespace App\Policies;

use App\User;
use App\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has access to a question (edit/delete)
     *
     * @param  User  $user
     * @param  Question $question
     * @return bool
     */
    public function access(User $user, Question $question)
    {
        return $question->user_id == $user->id ? true : null;
    }
}
