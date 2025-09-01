<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $this->userService->update($request->user()->id, $request->validated());

        return new UserResource($user);
    }

    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }
}
