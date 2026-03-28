<?php

namespace App\Observers\StoreApp;

use App\Models\Notification;
use App\Models\StoreCart;
use App\Models\StoreOrder;
use App\Models\User;
use App\Models\Wallet;
use App\Services\FirebaseService;

class OrderObserver
{
    private $notificationService;

    public function __construct(FirebaseService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /*-----------------------------------------------------------------------------------------------*/

    /**
     * Handle the order "created" event.
     */
    public function created(StoreOrder $order): void
    {
        StoreCart::where([['user_id', auth()->id()], ['store_id', $order->store_id]])->delete();
    }

    /*-----------------------------------------------------------------------------------------------*/


    /**
     * Handle the order "updated" event.
     */
    public function updated(StoreOrder $order): void
    {
    
    if (!$order->wasChanged('status')) {
        return;
    }
        $title = "";
        $message = "";
        $date = date('Y-m-d H:i:s');
        switch ($order->status) {
            case 1:
                $title = __('messages.update_order');
                $message = __('messages.change_store_order_status_1', ['ORDER_ID' => $order->id]);
                break;
            case 2:
                $title = __('messages.update_order');
                $message = __('messages.change_store_order_status_2', ['ORDER_ID' => $order->id]);
                break;
            case 3:
                $title = __('messages.update_order');
                $message = __('messages.change_store_order_status_3', ['ORDER_ID' => $order->id]);
                break;
            case 4:
                $title = __('messages.update_order');
                $message = __('messages.change_store_order_status_4', ['ORDER_ID' => $order->id]);
                $fullOrder = StoreOrder::select(
                    'store_orders.*',
                    'store_used_coupons.coupon_code',
                    'store_used_coupons.discount_percentage',
                    'store_used_coupons.max_discount_amount',
                )
                    ->leftJoin('store_used_coupons', 'store_used_coupons.order_id', '=', 'store_orders.id')
                    ->where('store_orders.id', $order->id)
                    ->first();
                $wallet = Wallet::where('user_id', $fullOrder->store_id)->first();
                $wallet->update([
                    'balance' => $wallet->balance + $fullOrder->store_dues
                ]);
                $wallet = Wallet::where('user_id', $fullOrder->driver_id)->first();
                $wallet->update([
                    'balance' => $wallet->balance + $fullOrder->delivery_charge
                ]);
                break;
            case 5:
                $wallet = Wallet::where('user_id', $order->user_id)->first();
                $wallet->update([
                    'balance' => $wallet->balance + $order->total
                ]);
        }

        $notificatons = [
            'type' => null,
            'user_id' => $order->user_id,
            'notifiable_type' => 'store_order',
            'notifiable_id' => $order->id,
            'title' => $title,
            'message' => $message,
            'created_at' => $date,
            'updated_at' => $date
        ];

        if ($order->status == "2") {
            $drivers = User::select('id', 'device_token')->where('role_id', 5)->get();

            foreach ($drivers as $driver) {
                array_merge($notificatons, [
                    'type' => null,
                    'user_id' => $driver->id,
                    'notifiable_type' => 'store_order',
                    'notifiable_id' => $order->id,
                    'title' => __('messages.new_order'),
                    'message' => __('messages.new_store_order_message', ['ORDER_ID' => $order->id]),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }

        Notification::insert($notificatons);

        $devicesTokens = User::select('device_token')
            ->where('id', $order->user_id)
            ->WhereNotNull('device_token')
            ->pluck('device_token')
            ->all();

        $this->notificationService->notify(
            $title,
            $message,
            $devicesTokens,
            [
                'order_id' => $order->id,
                'navigation' => 'store_order'
            ]
        );

        if ($order->status == "2") {
            $devicesTokens = $drivers->pluck('device_token')->all();
            $this->notificationService->notify(
                __('messages.new_order'),
                __('messages.new_store_order_message', ['ORDER_ID' => $order->id]),
                $devicesTokens,
                [
                    'order_id' => $order->id,
                    'navigation' => 'store_order'
                ]
            );
        }
    }
}
