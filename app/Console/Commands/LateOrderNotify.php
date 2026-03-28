<?php

namespace App\Console\Commands;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LateOrderNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:late-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admin when orders are late';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $orders = Order::select('orders.id', 'users.name as worker_name')
            ->join('users', 'orders.worker_id', '=', 'users.id')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('notifications.type', 6);
            })
            ->whereRaw("TIMESTAMP(orders.date, orders.time) < ?", [$now])
            ->where('orders.status', 1)
            ->get();

        if (!$orders->isEmpty()) {
            foreach ($orders as $order) {
                $notification = Notification::create([
                    'notifiable_type' => 'order',
                    'notifiable_id' => $order->id,
                    'type' => 6,
                    'title' => __('messages.late_order'),
                    'message' => __('messages.late_order_message', ['ORDER_ID' => $order->id, 'USER' => $order->worker_name])
                ]);

                event(new NewNotification($notification));
            }
        }
    }
}
