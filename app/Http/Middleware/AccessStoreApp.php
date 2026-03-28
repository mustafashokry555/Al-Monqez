<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessStoreApp
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/*')) {
            if ($request->user() && !in_array($request->user()->role_id, ['4', '5'])) {
                return $this->apiResponse(401, __('messages.not_auth'), 'not_client_or_driver', [
                    'is_client' => 0,
                    'is_driver' => 0
                ]);
            }
        } else {
            if ($request->user() && !in_array($request->user()->role_id, ['1', '2', '6'])) {
                abort(401);
            }
        }

        return $next($request);
    }
}
