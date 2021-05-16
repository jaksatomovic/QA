<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAvatarRequest;

class UserAvatarController extends Controller
{
    /**
     * Create a new UserAvatarController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new user avatar.
     *
     * @param   UserAvatarRequest $request
     * @return  json with message
     */
    public function store(UserAvatarRequest $request)
    {
        auth()->user()->update([
            'picture' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return response()->json([
            'message' => __('Avatar updated').'!',
        ]);
    }
}
