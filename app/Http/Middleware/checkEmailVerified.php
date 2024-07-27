<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class checkEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !in_array(Auth::user()->user_type, [USER_TYPE_ADMIN, USER_TYPE_SUB_ADMIN]) && !Auth::user()->email_verified) {
            Auth::logout();

            $message = "Please verify your email address.";
            return Redirect()->to('login')->with('error', $message);
        }
        return $next($request);
    }
}
