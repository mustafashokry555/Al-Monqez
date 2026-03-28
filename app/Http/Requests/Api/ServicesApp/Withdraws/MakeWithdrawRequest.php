<?php

namespace App\Http\Requests\Api\ServicesApp\Withdraws;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\Wallet;
use App\Models\Withdraw;
use Illuminate\Foundation\Http\FormRequest;

class MakeWithdrawRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $balance = Wallet::select('balance')->where('user_id', auth()->id())->first()->balance;
        $withdraw = Withdraw::where([['user_id', auth()->id()], ['status', '0']])->exists();

        return [
            'account_holder_name' => 'required|string|max:250',
            'account_number' => 'required|string|max:250',
            'iban_number' => 'required|string|max:250',
            'bank_name' => 'required|string|max:250',
            'amount' => [
                'required',
                'numeric',
                'in:' . ($withdraw ? 0 : $this->amount),
                'min:1',
                "max:$balance"
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.in' => __('messages.made_withdraw_before'),
            'amount.max' => __('messages.not_enough_balance')
        ];
    }
}
