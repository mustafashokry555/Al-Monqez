<?php

namespace App\Http\ViewComposer;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PermissionComposer
{
    public function __construct() {}

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = auth()->user();
        if ($user) {
            if ($user->role_id == '1') {
                $view->with('super_admin', true);
            } else {
                $permissions = Cache::rememberForever("permissions_$user->id", function () use ($user) {
                    return Permission::select('permissions.name')
                        ->join('user_permissions', 'permissions.id', '=', 'user_permissions.permission_id')
                        ->where('user_id', $user->id)->get();
                });

                foreach ($permissions as $permission) {
                    $view->with("$permission->name", true);
                }
            }
        }
    }
}
