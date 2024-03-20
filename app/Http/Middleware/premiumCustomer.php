<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Auth;

class premiumCustomer
{    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){
            return response()->json([
                'message'  => null,
                'error'    => PREMIUM_ALERT,
            ],402);
        }else{
            if(!Auth::guard('customer-api')->user()){
                return response()->json([
                    'message'  => null,
                    'error'    => LOGIN_ALERT,
                ],400);
            }
        }

        return $next($request);
    }
}
