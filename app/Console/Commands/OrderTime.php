<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Order;
use App\Services\FirebaseService;
use Illuminate\Console\Command;

class OrderTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users who related to the order that the order execution time is about to come';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::select(
            'orders.id',
            'orders.date',
            'orders.time',
            'workers.id AS worker_id',
            'workers.device_token AS worker_device_token',
            'clients.id AS client_id',
            'clients.device_token AS client_device_token'
        )
            ->join('users AS workers', 'workers.id', '=', 'orders.worker_id')
            ->join('users AS clients', 'clients.id', '=', 'orders.client_id')
            ->leftJoin('notifications', function ($join) {
                $join->on('orders.id', '=', 'notifications.notifiable_id')
                    ->where('notifications.notifiable_type', 'order')
                    ->where('notifications.type', '8');
            })
            ->where('orders.status', 1)
            ->where('orders.date', date('Y-m-d'))
            ->where('orders.time', '<', date('H:i:s', strtotime(date('H:i:s') . '+5 minutes')))
            ->whereNull('notifications.id')
            ->distinct()
            ->get();

        $date = date('Y-m-d H:i:s');
        $data = [];
        foreach ($orders as $order) {
            array_push($data, [
                'type' => 8,
                'user_id' => $order->worker_id,
                'notifiable_type' => 'order',
                'notifiable_id' => $order->id,
                'title' => __('messages.order_time'),
                'message' => __('messages.order_time_message', ['ORDER_ID' => $order->id]),
                'created_at' => $date,
                'updated_at' => $date
            ], [
                'type' => 8,
                'user_id' => $order->client_id,
                'notifiable_type' => 'order',
                'notifiable_id' => $order->id,
                'title' => __('messages.order_time'),
                'message' => __('messages.order_time_message', ['ORDER_ID' => $order->id]),
                'created_at' => $date,
                'updated_at' => $date
            ]);

            $deviceTokens = [];
            if ($order->worker_device_token) {
                array_push($deviceTokens, $order->worker_device_token);
            }
            if ($order->client_device_token) {
                array_push($deviceTokens, $order->client_device_token);
            }

            $firebaseService = new FirebaseService();
            $firebaseService->notify(__('messages.order_time'), __('messages.order_time_message', ['ORDER_ID' => $order->id]), $deviceTokens, [
                'order_id' => $order->id,
                'navigation' => 'order'
            ]);
        }

        if ($data) {
            Notification::insert($data);
        }
    }
}
