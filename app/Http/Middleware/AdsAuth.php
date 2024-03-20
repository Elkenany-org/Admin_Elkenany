<?php

namespace App\Http\Middleware;
use Modules\SystemAds\Entities\Ads_User;
use Closure;
use Session;

class AdsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization'); 
            $token = explode(' ',$token);

            if(count( $token) == 2)
            {
                $user = Ads_User::where('api_token',$token[1])->first();
                if($user)
                {
                    Session::flash('user',$user);
                 
                    return $next($request);
                }else{
                    return response()->json(["error" => "Unauthenticated."],401);
                }
            }else{
                return response()->json(["error" => "Unauthenticated."],401);

            }
        }else{
            return response()->json(["error" => "Unauthenticated."],401);
        }
    }

}
