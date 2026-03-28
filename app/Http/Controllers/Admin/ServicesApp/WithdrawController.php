<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Withdraws\ProcessWithdrawRequest;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $withdraws = Withdraw::query()
            ->select(
                'withdraws.id',
                'users.name AS user_name',
                'users.phone AS user_phone',
                'withdraws.account_holder_name',
                'withdraws.account_number',
                'withdraws.iban_number',
                'withdraws.bank_name',
                'withdraws.amount',
                'withdraws.status',
                'withdraws.created_at'
            )
            ->join('users', 'users.id', '=', 'withdraws.user_id');

        if ($request->filled('status')) {
            $withdraws->where('withdraws.status', $request->status);
        }

        $withdraws = $withdraws->orderBy('withdraws.created_at', 'desc')->paginate(10);

        return view('admin.services-app.withdraws.index', compact('withdraws'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function process(ProcessWithdrawRequest $request)
    {
        $withdraw = Withdraw::find($request->withdraw_id);
        $withdraw->update([
            'status' => $request->status
        ]);

        session()->flash('success', __("messages.chanage_withdraw_status_$withdraw->status"));
        return redirect()->back();
    }
}
