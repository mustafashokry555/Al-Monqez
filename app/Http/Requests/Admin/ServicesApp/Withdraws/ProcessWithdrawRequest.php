<?php

namespace App\Http\Requests\Admin\ServicesApp\Withdraws;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessWithdrawRequest extends FormRequest
{
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
        return [
            'withdraw_id' => [
                'required',
                Rule::exists('withdraws', 'id')->where(function ($query) {
                    return $query->where('status', '0');
                })
            ],
            'status' => 'required|integer|in:1,2'
        ];
    }
}
