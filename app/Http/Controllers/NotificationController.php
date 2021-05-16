<?php

namespace App\Http\Controllers;

use App\User;
use App\Notification;
use Illuminate\Support\Facades\Input;
use App\Http\Filters\NotificationFilters;

class NotificationController extends Controller
{
    /**
     * Create a new NotificationController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Checking for new notifications
     *
     * @return JSON with number of unread notifications
     */
    public function check()
    {
        $notificationsCounted = Notification::where('notifiable_id', auth()->id())
            ->whereNull('read_at')->count();

        return response()->json([
            'unread' => $notificationsCounted,
        ]);
    }

    /**
     * Return notifications
     *
     * @param   NotificationFilters   $filters
     * @return  \Illuminate\Http\Response
     */
    public function get(NotificationFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.notifications');

        return Notification::where('notifiable_id', auth()->id())
            ->filter($filters)
            ->simplePaginate($limit)
            ->appends(Input::except('page'));
    }

    /**
     * Mark notifications as read
     *
     * @return  \Illuminate\Http\Response
     */
    public function read()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => __('Marked as read'),
        ]);
    }

    /**
     * Update notification
     *
     * @param   \App\Notification   $notification
     * @return  \Illuminate\Http\Response
     */
    public function update(Notification $notification)
    {
        auth()->user()->notifications()->findOrFail($notification->id)->markAsRead();

        return response()->json([
            'message' => __('Marked as read'),
        ]);
    }

    /**
     * Remove the specified notification from database
     *
     * @param   \App\Notification   $notification
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('access', $notification);

        $notification->delete();

        return response()->json([
            'message' => __('Notification deleted').'!',
        ]);
    }
}
