<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->getMethod() == "OPTIONS") {   
            $headers = [    
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',    
                'Access-Control-Allow-Headers' => 'Content-Type, Origin, Authorization' 
            ];
            return \Response::make('OK', 200, $headers);    
        }   

        return $next($request);
    }
}
