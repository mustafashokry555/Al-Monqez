<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class IsOpen
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $language = app()->getLocale();
        $settings = Setting::select('site_status', "closed_message_$language AS closed_message")->first();

        if ($settings && $settings->site_status == '1') {
            return $next($request);
        }

        return $this->apiResponse(503, $settings->closed_message ?? __("messages.closed_message"));
    }
}
