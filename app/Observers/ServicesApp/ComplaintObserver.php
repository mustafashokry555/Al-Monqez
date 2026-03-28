<?php

namespace App\Observers\ServicesApp;

use App\Events\NewNotification;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class ComplaintObserver
{
    /**
     * Handle the Complaint "created" event.
     */

    public function created(Complaint $complaint)
    {
        event(new NewNotification((object)[
            'title' => __('messages.new_complaint', [], 'ar'),
            'message' => __('messages.new_complaint_message', ['ORDER_ID' => $complaint->order_id], 'ar'),
        ]));
    }

    /**
     * Handle the Complaint "updated" event.
     */
    public function updated(Complaint $complaint)
    {
        $notificationService = new FirebaseService();

        switch ($complaint->status) {
            case '1':
                $title = __('messages.update_complaint');
                $message = __('messages.change_complaint_status_1', [
                    'COMPLAINT_ID' => $complaint->id,
                    'ORDER_ID' => $complaint->order_id
                ]);
                break;
            case '2':
                $title = __('messages.update_complaint');
                $message = __('messages.change_complaint_status_2', [
                    'COMPLAINT_ID' => $complaint->id,
                    'ORDER_ID' => $complaint->order_id
                ]);
                break;
            case '3':
                $title = __('messages.update_complaint');
                $message = __('messages.change_complaint_status_3', [
                    'COMPLAINT_ID' => $complaint->id,
                    'ORDER_ID' => $complaint->order_id
                ]);
                break;
            default:
                return;
        }

        $client = User::select('users.id', 'device_token')
            ->join('orders', 'orders.client_id', '=', 'users.id')
            ->where('orders.id', $complaint->order_id)
            ->first();

        Notification::create([
            'type' => null,
            'user_id' => $client->id,
            'notifiable_type' => 'order',
            'notifiable_id' => $complaint->order_id,
            'title' => $title,
            'message' => $message
        ]);

        $devicesTokens = [];
        if ($client && $client->device_token) {
            $devicesTokens[] = $client->device_token;
        }

        $notificationService->notify(
            $title,
            $message,
            $devicesTokens,
            [
                'order_id' => $complaint->order_id,
                'navigation' => 'order_complaints'
            ]
        );
    }
}
