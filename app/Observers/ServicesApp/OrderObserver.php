<?php

namespace App\Observers\ServicesApp;

use App\Events\NewNotification;
use App\Models\Chat;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\Setting;
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
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $orderServiceIds = request()->get('services');

        $baseUsersQuery = User::query()
            ->whereHas('services', function ($query) use ($orderServiceIds) {
                $query->whereIn('user_services.service_id', $orderServiceIds)
                    ->groupBy('user_services.user_id')
                    ->havingRaw('COUNT(DISTINCT user_services.service_id) = ?', [count($orderServiceIds)]);
            });

        if ($order->parent_order_id) {
            $parentOrder = Order::select('worker_id')->find($order->parent_order_id);
            $baseUsersQuery->where('users.id', $parentOrder->worker_id);
        }

        $workers = (clone $baseUsersQuery)->select('users.id', 'users.device_token')->whereNull('users.company_id')->whereNotNull('users.device_token')->distinct()->get();
        $companies = (clone $baseUsersQuery)->select('users.company_id')->whereNotNull('users.company_id')->distinct()->get();

        $date = date('Y-m-d H:i:s');
        $dashboardMessage = __('messages.dashboard_new_order_message', ['ORDER_ID' => $order->id], 'ar');

        $workers_count = count($workers);
        $companies_count = count($companies);
        if ($workers_count > 0 || $companies_count > 0) {
            $requests = [];
            if ($workers_count > 0) {
                $notifications = [
                    [
                        'type' => 7,
                        'user_id' => null,
                        'notifiable_type' => 'order',
                        'notifiable_id' => $order->id,
                        'title' => __('messages.new_order', [], 'ar'),
                        'message' => $dashboardMessage,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]
                ];

                foreach ($workers as $worker) {
                    array_push($requests, [
                        'worker_id' => $worker->id,
                        'order_id' => $order->id,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                    array_push($notifications, [
                        'type' => null,
                        'user_id' => $worker->id,
                        'notifiable_type' => 'order',
                        'notifiable_id' => $order->id,
                        'title' => __('messages.new_order'),
                        'message' => __('messages.new_order_message', ['ORDER_ID' => $order->id]),
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                }

                Notification::insert($notifications);

                $devicesTokens = $workers->pluck('device_token');
                $this->notificationService->notify(
                    __('messages.new_order'),
                    __('messages.new_order_message', ['ORDER_ID' => $order->id]),
                    $devicesTokens,
                    [
                        'order_id' => $order->id,
                        'navigation' => 'order'
                    ]
                );
            }

            if ($companies_count > 0) {
                foreach ($companies as $company) {
                    array_push($requests, [
                        'worker_id' => $company->company_id,
                        'order_id' => $order->id,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                }

                event(new NewNotification((object) [
                    'title' => __('messages.new_order', [], 'ar'),
                    'message' => __('messages.new_order_message', ['ORDER_ID' => $order->id]),
                    'role' => '7',
                    'authenticated' => $companies->pluck('company_id')->toArray()
                ]));
            }

            OrderRequest::insert($requests);
        } else {
            $phone = Setting::select('phone')->first()->phone ?? '';
            $dashboardMessage = __('messages.dashboard_alert_order_message', ['ORDER_ID' => $order->id], 'ar');
            Notification::insert([
                [
                    'type' => null,
                    'user_id' => auth()->id(),
                    'notifiable_type' => 'order',
                    'notifiable_id' => $order->id,
                    'title' => __('messages.alert_order'),
                    'message' => __('messages.alert_order_message', [
                        'PHONE' => $phone,
                        'ORDER_ID' => $order->id
                    ]),
                    'created_at' => $date,
                    'updated_at' => $date
                ],
                [
                    'type' => 7,
                    'user_id' => null,
                    'notifiable_type' => 'order',
                    'notifiable_id' => $order->id,
                    'title' => __('messages.new_order', [], 'ar'),
                    'message' => $dashboardMessage,
                    'created_at' => $date,
                    'updated_at' => $date
                ]
            ]);

            if (auth()->user()->device_token) {
                $this->notificationService->notify(
                    __('messages.alert_order'),
                    __('messages.alert_order_message', [
                        'PHONE' => $phone,
                        'ORDER_ID' => $order->id
                    ]),
                    [auth()->user()->device_token],
                    [
                        'order_id' => $order->id,
                        'phone' => $phone,
                        'navigation' => 'order'
                    ]
                );
            }
        }

        event(new NewNotification((object) [
            'title' => __('messages.new_order', [], 'ar'),
            'message' => $dashboardMessage
        ]));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $title = "";
        $message = "";
        $user_id = $order->client_id;
        $date = date('Y-m-d H:i:s');
        switch ($order->status) {
            case 1:
                $user_id = $order->worker_id;
                $title = __('messages.update_offer');
                $message = __('messages.accept_offer_message', ['ORDER_ID' => $order->id]);
                $dashboardMessage = __('messages.dashboard_accept_offer_message', ['ORDER_ID' => $order->id], 'ar');
                if ($user_id && $order->company_id) {
                    $title = __('messages.update_order');
                    $message = __('messages.assign_worker_message', ['ORDER_ID' => $order->id]);
                    $dashboardMessage = __('messages.dashboard_assign_worker_message', ['ORDER_ID' => $order->id], 'ar');
                }
                Chat::create([
                    'order_id' => $order->id
                ]);
                break;
            case 2:
                $title = __('messages.update_order');
                $message = __('messages.change_status_2_message', ['ORDER_ID' => $order->id]);
                $dashboardMessage = __('messages.dashboard_change_status_2_message', ['ORDER_ID' => $order->id], 'ar');
                break;
            case 3:
                $title = __('messages.update_order');
                $message = __('messages.change_status_3_message', ['ORDER_ID' => $order->id]);
                $dashboardMessage = __('messages.dashboard_change_status_3_message', ['ORDER_ID' => $order->id], 'ar');
                $wallet = Wallet::where('user_id', $order->company_id ?? $order->worker_id)->first();
                $wallet->update([
                    'balance' => $wallet->balance + $order->worker_dues
                ]);
                break;
            case 4:
                $title = __('messages.update_order');
                $message = __('messages.cancel_order_message', ['ORDER_ID' => $order->id]);
                $dashboardMessage = __('messages.dashboard_cancel_order_message', ['ORDER_ID' => $order->id], 'ar');
                $user_id = ($user_id == auth()->user()->id) ? $order->worker_id : $user_id;
                if ($order->getOriginal('status') == 1) {
                    $wallet = Wallet::where('user_id', $order->client_id)->first();
                    $wallet->update([
                        'balance' => $wallet->balance + $order->e_paid_amount
                    ]);
                }
                break;
        }

        $notifications = [
            [
                'type' => 7,
                'user_id' => null,
                'notifiable_type' => 'order',
                'notifiable_id' => $order->id,
                'title' => __('messages.update_order', [], 'ar'),
                'message' => $dashboardMessage,
                'created_at' => $date,
                'updated_at' => $date
            ]
        ];

        if ($user_id) {
            $notifications[] = [
                'type' => null,
                'user_id' => $user_id,
                'notifiable_type' => 'order',
                'notifiable_id' => $order->id,
                'title' => $title,
                'message' => $message,
                'created_at' => $date,
                'updated_at' => $date
            ];
        }

        Notification::insert($notifications);

        if ($user_id) {
            $devicesTokens = User::select('device_token')
                ->where('id', $user_id)
                ->WhereNotNull('device_token')
                ->pluck('device_token')
                ->all();

            $this->notificationService->notify(
                $title,
                $message,
                $devicesTokens,
                [
                    'order_id' => $order->id,
                    'navigation' => 'order'
                ]
            );
        }

        event(new NewNotification((object)[
            'title' => $title,
            'message' => $message,
            'role' => '7',
            'authenticated' => [$order->company_id]
        ]));

        event(new NewNotification((object)[
            'title' => __('messages.update_order', [], 'ar'),
            'message' => $dashboardMessage
        ]));
    }

    /*-----------------------------------------------------------------------------------------------*/

    /**
     * Handle the Order "deleteing" event.
     */
    public function deleting(Order $order): void
    {
        OrderRequest::where('order_id', $order->id)->delete();
    }
}
