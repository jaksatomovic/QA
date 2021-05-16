<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Admin\AdminController;
use App\Notifications\BanUserNotification;
use App\Notifications\UnbanUserNotification;
use App\Notifications\DeleteUserNotification;
use App\Http\Filters\UserFilters;

class UserController extends AdminController
{
    /**
     * Return counted per day users for last 7 days
     *
     * @return array with results
     */
    public function countPerDay()
    {
        return $this->countGroupedByDay(User::class);
    }

    /**
     * Counting number of users for selected dates
     *
     * @return integer - users number
     */
    public function countPerRange()
    {
        return $this->countPerDateRange(User::class, request()->from, request()->to);
    }

    /**
     * Return filtered users
     *
     * @param   UserFilters $filters
     * @return  array with users
     */
    public function index(UserFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $users = User::withCount(['questions', 'answers'])
            ->filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($users));
    }

    /**
     * Ban user
     *
     * @param   User    $user
     * @return  json with response
     */
    public function ban(User $user)
    {
        $user->status = 2;
        $user->save();

        if (request()->send_notification) {
            $user->notify(new BanUserNotification([]));
        }

        return response()->json([
            'message' => __('User was banned').'!',
        ]);
    }

    /**
     * Unban user
     *
     * @param   User    $user
     * @return  json with response
     */
    public function unban(User $user)
    {
        $user->status = 1;
        $user->save();

        if (request()->send_notification) {
            $user->notify(new UnbanUserNotification([]));
        }

        return response()->json([
            'message' => __('User was unbanned').'!',
        ]);
    }

    /**
     * Delete user
     *
     * @param   Topic    $topic
     * @return  json with response
     */
    public function destroy(User $user)
    {
        $user->delete();

        if (request()->send_notification) {
            $user->notify(new DeleteUserNotification([]));
        }

        return response()->json([
            'message' => __('User was deleted').'!',
        ]);
    }
}
