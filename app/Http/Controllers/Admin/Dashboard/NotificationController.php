<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dashboard\Notifications\SendNotificationRequest;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::select('notifications.id', 'users.phone', 'notifications.type', 'notifications.title', 'notifications.message', 'notifications.created_at')
            ->leftJoin('users', 'notifications.user_id', '=', 'users.id')
            ->whereNotNull('type')
            ->where('type', '<=', 4)
            ->latest()
            ->paginate(10);

        return view('admin.dashboard.notifications.index', compact('notifications'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(SendNotificationRequest $request)
    {
        $user = null;
        if ($request->type == '0') {
            $user = User::where('phone', $request->phone)->select('id')->first();
        }

        $notification = Notification::create([
            'user_id' => $user->id ?? null,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message
        ]);

        $users = User::query();

        switch ($request->type) {
            case '0':
                $users->where('id', $notification->user_id);
                break;
            case '1':
                $users->whereNotIn('role_id', [1, 2]);
                break;
            default:
                $users->where('role_id', $request->type + 1);
                break;
        }

        $devicesTokens = $users->pluck('device_token')->all();

        $notificationService = new FirebaseService();
        $notificationService->notify(
            $notification->title,
            $notification->message,
            $devicesTokens,
            [
                'navigation' => 'notifications'
            ]
        );

        session()->flash('success', __('messages.send_notification'));
        return redirect()->back();
    }
}
