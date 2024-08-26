<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'admin') {
                return $next($request);
            }
        }

        return apiResponseStandard(message: 'شما دسترسی استفاده ازین سرویس را ندارید', statusCode: 403);
    }
}
