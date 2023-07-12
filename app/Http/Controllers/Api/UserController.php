<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @group Users Endpoints
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     *
     * @apiResourceModel App\Models\User paginate=15,simple
     */
    public function index()
    {
        return UserResource::collection(User::query()->simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     *
     * @apiResource App\Http\Resources\UserResource with=paginate
     *
     * @apiResourceModel App\Models\User
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
