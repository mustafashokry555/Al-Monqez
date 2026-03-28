<?php

namespace App\Observers\ServicesApp;

use App\Events\NewNotification;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class EvaluationObserver
{
    private $notificationService;

    public function __construct(FirebaseService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /*-----------------------------------------------------------------------------------------------*/

    /**
     * Handle the Evaluation "created" event.
     */
    public function created(Evaluation $evaluation): void
    {
        $worker = User::findOrFail($evaluation->worker_id);
        $worker->update([
            'rating' => round(Evaluation::where('worker_id', $evaluation->worker_id)->avg('rating'), 1)
        ]);

        $notification = Notification::create([
            'user_id' => $evaluation->worker_id,
            'notifiable_type' => 'order',
            'notifiable_id' => $evaluation->order_id,
            'title' => __('messages.update_order'),
            'message' => __('messages.evaluate_order_message', ['ORDER_ID' => $evaluation->order_id])
        ]);

        $devicesTokens = User::select('device_token')
            ->where('id', $evaluation->worker_id)
            ->WhereNotNull('device_token')
            ->pluck('device_token')
            ->all();

        $this->notificationService->notify(
            $notification->title,
            $notification->message,
            $devicesTokens,
            [
                'order_id' => $evaluation->order_id,
                'navigation' => 'order'
            ]
        );

        if ($evaluation->rating < 3) {
            $notification = Notification::create([
                'type' => 5,
                'notifiable_type' => 'order',
                'notifiable_id' => $evaluation->order_id,
                'title' => __('messages.low_rating', [], 'ar'),
                'message' => __('messages.low_rating_message', [
                    'USER' => $worker->name,
                    'ORDER_ID' => $evaluation->order_id,
                    'RATE' => $evaluation->rating
                ], 'ar')
            ]);

            event(new NewNotification($notification));
        }
    }
}
