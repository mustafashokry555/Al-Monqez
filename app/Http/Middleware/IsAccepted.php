<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAccepted
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->accepted == '1' || $request->user()->role_id == '4') {
            return $next($request);
        }

        return $this->apiResponse(401, __('messages.worker_not_accepted'), 'accepted', [
            'is_accepted' => 0
        ]);
    }
}
