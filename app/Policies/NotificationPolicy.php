<?php

namespace App\Policies;

use App\User;
use App\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has access to an notification (edit/delete)
     *
     * @param  User  $user
     * @param  Notification $notification
     * @return bool
     */
    public function access(User $user, Notification $notification)
    {
        return $notification->notifiable_id == $user->id ? true : null;
    }
}
