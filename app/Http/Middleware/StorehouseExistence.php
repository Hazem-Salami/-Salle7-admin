<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseTrait;
use App\Models\Storehouse;
use Closure;
use Illuminate\Http\Request;

class StorehouseExistence
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
        $storehouse = Storehouse::where('id', $request->storehouse_id)->first();

        if($storehouse)  {

            $request->attributes->add(['storehouse' => $storehouse]);

            return $next($request);

        } else{
            return $this->customResponse(false, 'storehouse Not Found', null, 400);
        }
    }
}
