<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse as ApiResponse;
use Closure;
use Illuminate\Http\Request;

class IsBlocked
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
        if ($request->user()->blocked == '0') {
            return $next($request);
        }

        if ($request->is("api*")) {
            return $this->apiResponse(401, __("messages.is_blocked"), 'blocked', [
                'is_blocked' => 1
            ]);
        }

        session()->flash('error', __('messages.is_blocked'));

        if (auth()->user()->role_id == 6) {
            return redirect(route('store_app.admin'));
        }

        return redirect(route('admin'));
    }
}
