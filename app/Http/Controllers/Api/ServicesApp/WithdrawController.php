<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\ServicesApp\Withdraws\MakeWithdrawRequest;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    use ApiResponse;

    public function balanceWithStatistics()
    {
        $balance = Wallet::select('balance')->where('user_id', auth()->id())->first()->balance;
        $statistics = Order::select(
            DB::raw("COALESCE(SUM(CASE WHEN DATE(completed_at) = CURDATE() THEN 1 ELSE 0 END), 0) AS daily_total"),
            DB::raw("COALESCE(SUM(CASE WHEN YEARWEEK(completed_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS weekly_total"),
            DB::raw("COALESCE(SUM(CASE WHEN MONTH(completed_at) = MONTH(CURDATE()) AND YEAR(completed_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS monthly_total"),
        )
            ->where([['worker_id', auth()->id()], ['status', '3']])
            ->first();

        return $this->apiResponse(200, 'balance with statistics', null, [
            'balance' => number_format((float)$balance, 2, '.', ''),
            'statistics' => $statistics
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function make(MakeWithdrawRequest $request)
    {
        Withdraw::create([
            'user_id' => auth()->id(),
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'iban_number' => $request->iban_number,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount
        ]);

        return $this->apiResponse(200, __('messages.make_withdraw'));
    }
}
