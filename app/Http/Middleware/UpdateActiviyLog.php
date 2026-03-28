<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateActiviyLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check()){
            UserActivityLog::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                ],
                [
                    'last_active_at' => now(),
                    'is_online' => true
                ]
            );
        }

        return $next($request);
    }
}
