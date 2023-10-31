<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = [
            'status' => false,
            'message' => 'You do not have permission',
            'data' => null,
        ];

//        config(['auth.guards.admin-api.driver' => 'session']);
        $user = Admin::find(auth()->user()->id);

        if ($user->role->name == "super-admin")
            return $next($request);

        $permission = $request->route()->getName();

        if ($permission == "cms.login")
            return $next($request);

        $permissions = $user->role->permissions;

        for ($i = 0; $i < sizeof($permissions); $i++) {
            if ($permission === $permissions[$i]->name)
                return $next($request);
        }
        return response($response, 403);
    }
}
