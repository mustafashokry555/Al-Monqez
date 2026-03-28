<?php

namespace App\Http\Requests\Api\Main\Auth;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmCodeRequest extends FormRequest
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
        return [
            'code' => [
                'required',
                Rule::exists('otps')->where(function ($query) {
                    return $query->where([['phone', auth()->user()->phone], ['type', 0]]);
                })
            ],
        ];
    }

    public function messages()
    {
        return [
            'code.exists' => __('messages.wrong_code'),
        ];
    }
}
