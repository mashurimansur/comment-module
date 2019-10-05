<?php

namespace App\Http\Middleware;

use Closure;

class GatewayMiddleware
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
        $arrayHeader = $request->headers->all();
        $isApiGateway = array_key_exists("x-forwarded-for",$arrayHeader);

        if(!$isApiGateway){
            return response()->json([
                "diagnostics"=>[
                    "login"=>false,
                    "status"=>"Access forbidden.",
                    "code"=>503
                ]
            ]);
        }

        return $next($request);
    }
}
