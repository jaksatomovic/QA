<?php

namespace App\Policies;

use App\User;
use App\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has access to an answer (edit/delete)
     *
     * @param  User  $user
     * @param  Answer $answer
     * @return bool
     */
    public function access(User $user, Answer $answer)
    {
        return $answer->user_id == $user->id ? true : null;
    }

    /**
     * Determine if the user can accept an answer as best
     *
     * @param  User  $user
     * @param  Answer $answer
     * @return bool
     */
    public function accept(User $user, Answer $answer)
    {
        return $answer->question->user_id == $user->id ? true : null;
    }
}
