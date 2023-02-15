<?php

namespace App\Http\Middleware;

use App\Utils\FormatResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class logedinOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(empty($request->header("username")) || empty($request->header("password"))) {
            return response()->json(new FormatResponse(403, "Not allowed", []), 403);
        }
        
        $user = \App\Models\User::where("email", $request->header("username"))->first();
        if(empty($user)) {
            return response()->json(new FormatResponse(400, "Invalid credential", []), 400);
        }
        
        if(!Hash::check($request->header("password"), $user->password)) {
            return response()->json(new FormatResponse(400, "Invalid credential", []), 400);
        }

        Auth::login($user);
        return $next($request);
    }
}
