<?php

namespace App\Services\Auth;

use App\Http\Requests\auth\LoginRequest;
use App\Jobs\auth\client\StoreRegisterJob;
use App\Models\Admin;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class AdminAuthService extends BaseService
{
    /**
     * @param LoginRequest
     * @return Response
     */
    public function login($request): Response
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
//        config(['auth.guards.user-api.provider' => 'user']);
        if (auth()->attempt($data)) {
            $user = Admin::find(auth()->user()->id);
            $token = $user->createToken('Login Admin Token')->accessToken;

            $response = [
                'user' => $user,
                'accessToken' => $token
            ];
            return $this->customResponse(true, 'Login success', $response);
        } else
            return $this->customResponse(false, 'Password is wrong', null, 400);
    }

    /**
     * @param Request
     * @return Response
     */
    public function logout($request): Response
    {
        $request->user()->token()->revoke();
        return $this->customResponse(true, 'Logout success');
    }
}
