<?php

namespace App\Observers\ServicesApp;

use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdraw;
use App\Services\FirebaseService;

class WithdrawObserver
{
    private $notificationService;

    public function __construct(FirebaseService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /*-----------------------------------------------------------------------------------------------*/

    /**
     * Handle the Withdraw "updated" event.
     *
     * @param  \App\Models\Withdraw  $withdraw
     * @return void
     */
    public function updated(Withdraw $withdraw)
    {
        if ($withdraw->status == '1') {
            $user = User::findOrFail($withdraw->user_id);
            $wallet = Wallet::where('user_id', $withdraw->user_id)->first();
            $wallet->update([
                'balance' => $wallet->balance - $withdraw->amount
            ]);

            $notification = Notification::create([
                'user_id' => $withdraw->user_id,
                'title' => __('messages.withdraw'),
                'message' => __('messages.withdraw_success_message', ['AMOUNT' => $withdraw->amount]),
            ]);

            if ($user->device_token) {
                $this->notificationService->notify(
                    $notification->title,
                    $notification->message,
                    [
                        $user->device_token
                    ],
                    [
                        'navigation' => 'balance_with_statistics'
                    ]
                );
            }
        }
    }
}
