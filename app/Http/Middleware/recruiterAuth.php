<?php

namespace App\Http\Middleware;

use Closure;
use Modules\Recruitment\Entities\Recruiter;
use Session;

class recruiterAuth
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

                $recruiter = Recruiter::where('api_token',$token[1])->first();
                if($recruiter)
                {
                    Session::flash('recruiter',$recruiter);

                    return $next($request);
                }else{
                    return response()->json(["error" => "يرجي تسجيل الدخول"],401);
                }
            }else{
                return response()->json(["error" => "يرجي تسجيل الدخول"],401);

            }
        }else{
            return response()->json(["error" => "يرجي تسجيل الدخول"],401);
        }
    }

}
