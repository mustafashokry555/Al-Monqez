<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\UserPermission;
use Closure;
use Illuminate\Http\Request;

class HasAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permissions)
    {
        $user = auth()->user();
        if ($user->role_id == '1') {
            return $next($request);
        }

        $userPermissions = UserPermission::where('user_id', $user->id)->pluck('permission_id')->all();
        $permissions = Permission::whereIn('name', explode('|', $permissions))->pluck('id')->all();

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return $next($request);
            }
        }

        return abort(401);
    }
}
