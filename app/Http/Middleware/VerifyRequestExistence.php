<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseTrait;
use App\Models\VerifyRequest;
use Closure;
use Illuminate\Http\Request;

class VerifyRequestExistence
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $verifyrequest = VerifyRequest::where('user_id', $request->id) -> first();

        if($verifyrequest) {
            $request->attributes->add(['verifyrequest' => $verifyrequest]);

            return $next($request);
        } else
            return $this->customResponse(false, 'not found', null, 400);
    }
}
