<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function received()
    {
        $notifications = Notification::select('id', 'notifiable_id AS order_id', 'type', 'title', 'message', 'created_at')
            ->whereNotNull('type')
            ->where('type', '>', 4)
            ->where('type', '<=', 7)
            ->latest()
            ->paginate(10);

        return view('admin.services-app.notifications.received', compact('notifications'));
    }
}
