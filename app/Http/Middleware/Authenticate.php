<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    use ApiResponse;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        if (! $request->expectsJson()) {
            if($request->is('api*')) {
                return $this->apiResponse(401, __("messages.not_auth"));
            }

            return route('login');
        }
    }
}
