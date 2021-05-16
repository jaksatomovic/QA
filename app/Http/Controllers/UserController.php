<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Http\Filters\UserFilters;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserAccountRequest;
use App\Http\Requests\UserPasswordRequest;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param   UserFilters $filters
     * @return  \Illuminate\Http\Response
     */
    public function index(UserFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.users');

        return User::latest()->where('status', 1)
            ->withCount(['questions', 'answers'])
            ->filter($filters)
            ->simplePaginate($limit)
            ->appends(Input::except('page'));
    }

    /**
     * Update profile settings for given user
     *
     * @param   UserProfileRequest $request
     * @param   User $user
     * @return  json with response message
     */
    public function updateProfile(UserProfileRequest $request, User $user)
    {
        $this->authorize('access', $user);

        $user->update($request->only('name', 'credentials', 'location', 'about'));

        return response()->json([
            'message' => __('Your profile was edited').'!',
        ]);
    }

    /**
     * Update account settings for given user
     *
     * @param   UserAccountRequest $request
     * @param   User $user
     * @return  json with response message
     */
    public function updateAccount(UserAccountRequest $request, User $user)
    {
        $this->authorize('access', $user);

        $user->update($request->only('email', 'email_notifications'));

        return response()->json([
            'message' => __('Your account settings was edit').'!',
        ]);
    }

    /**
     * Update password for given user
     *
     * @param   UserPasswordRequest $request
     * @param   User $user
     * @return  json with response message
     */
    public function updatePassword(UserPasswordRequest $request, User $user)
    {
        $this->authorize('access', $user);

        if (!Hash::check($request->current, $user->password)) {
            return response()->json([
                'errors' => [
                    'current'=> [__('Current password does not match')]
                ]
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => __('Your password was edit').'!',
        ]);
    }

    /**
     * Close account function
     *
     * @param   Request $request
     * @param   User $user
     * @return  json with response message
     */
    public function closeAccount(Request $request, User $user)
    {
        $this->authorize('access', $user);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'password'=> [__('Wrong account password')]
                ]
            ], 422);
        }

        $user->status = 0;
        $user->save();

        return response()->json([
            'message' => __('Your account was closed').'!',
        ]);
    }
}
