<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        
        
        if(!$request->bearerToken()){
            return response()->json([
                "login"=> "fail",
                'message'=>"lütfen token girişi yapınız."
            ]);
        }
        return $next($request);
    }
}

