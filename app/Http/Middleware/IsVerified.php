<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\OtpHelper;
use Closure;
use Illuminate\Http\Request;

class IsVerified
{
    use ApiResponse, OtpHelper;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->verified == '1') {
            return $next($request);
        }

        $this->sendOtp($request->user(), 0);
        return $this->apiResponse(401, __('messages.verify_code'), 'verified', [
            'is_verified' => 0
        ]);
    }
}
