<?php

namespace App\Observers\ServicesApp;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\User;
use App\Services\FirebaseService;

class OfferObserver
{
    private $notificationService;

    public function __construct(FirebaseService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /*-----------------------------------------------------------------------------------------------*/

    /**
     * Handle the OrderRequest "updated" event.
     */
    public function updated(OrderRequest $orderRequest): void
    {
        $notification = null;
        if (in_array(auth()->user()->role_id, ['3', '7'])) {
            $order = Order::select('client_id')->findOrFail($orderRequest->order_id);
            $notification = Notification::create([
                'user_id' => $order->client_id,
                'notifiable_type' => 'order',
                'notifiable_id' => $orderRequest->order_id,
                'title' => __('messages.new_offer'),
                'message' => __('messages.new_offer_message', ['ORDER_ID' => $orderRequest->order_id])
            ]);
        } else {
            if (User::where([['id', $orderRequest->worker_id], ['role_id', '3']])->exists()) {
                $notification = Notification::create([
                    'user_id' => $orderRequest->worker_id,
                    'notifiable_type' => 'order',
                    'notifiable_id' => $orderRequest->order_id,
                    'title' => __('messages.update_offer'),
                    'message' => __('messages.reject_offer_message', ['ORDER_ID' => $orderRequest->order_id])
                ]);
            } else {
                event(new NewNotification((object) [
                    'title' => __('messages.update_offer', [], 'ar'),
                    'message' => __('messages.reject_offer_message', ['ORDER_ID' => $orderRequest->order_id], 'ar'),
                    'role' => '7',
                    'authenticated' => [$orderRequest->worker_id]
                ]));
            }
        }

        if ($notification) {
            $devicesTokens = User::select('device_token')
                ->where('id', $notification->user_id)
                ->WhereNotNull('device_token')
                ->pluck('device_token')
                ->all();

            $this->notificationService->notify(
                $notification->title,
                $notification->message,
                $devicesTokens,
                [
                    'order_id' => $notification->order_id,
                    'navigation' => 'order'
                ]
            );
        }
    }
}
