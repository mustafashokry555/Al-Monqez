<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessServicesApp
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
            if ($request->user() && !in_array($request->user()->role_id, ['3', '4'])) {
                return $this->apiResponse(401, __('messages.not_auth'), 'not_client_or_worker', [
                    'is_client' => 0,
                    'is_worker' => 0
                ]);
            }
        } else {
            if ($request->user() && !in_array($request->user()->role_id, ['1', '2', '7'])) {
                abort(401);
            }
        }

        return $next($request);
    }
}
