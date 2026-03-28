<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function activityLogs()
    {
        $activity_logs = User::select(
            'users.id',
            'users.name',
            'users.phone',
            'users.image',
            'user_activity_logs.device_type',
            'user_activity_logs.last_active_at',
            'user_activity_logs.is_online'
        )
            ->leftJoin('user_activity_logs', 'users.id', '=', 'user_activity_logs.user_id')
            ->whereIn('users.role_id', ['3', '4', '5'])
            ->orderBy('user_activity_logs.last_active_at', 'DESC')
            ->paginate(10);

        return view('admin.dashboard.users.activity_logs', compact('activity_logs'));
    }
}
