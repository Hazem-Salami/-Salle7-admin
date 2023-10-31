<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * The user service implementation.
     *
     * @var UserService
     */
    protected UserService $userService;

    // singleton pattern, service container
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsersByType(Request $request): Response
    {
        return $this->userService->getUsersByType($request);
    }

    public function showUser(User $user): Response
    {
        return $this->userService->showUser($user);
    }

    public function blocking(User $user): Response
    {
        return $this->userService->blocking($user);
    }
}
