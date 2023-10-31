<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Services\Auth\AdminAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminAuthController extends Controller
{
    /**
     * The auth service implementation.
     *
     * @var AdminAuthService
     */
    protected AdminAuthService $adminAuthService;

    // singleton pattern, service container
    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(LoginRequest $request): Response
    {
        return $this->adminAuthService->login($request);
    }

    public function logout(Request $request): Response
    {
        return $this->adminAuthService->logout($request);
    }
}
