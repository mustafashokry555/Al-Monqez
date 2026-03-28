<?php

namespace App\Http\Requests\Api\ServicesApp\Chats;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
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
        return array_merge([
            'order_id' => [
                'nullable',
                Rule::exists('orders', 'id')->where(function ($query) {
                    return $query->where(function ($query) {
                        return $query->where('client_id', auth()->id())->orWhere('worker_id', auth()->id());
                    })->whereIn('status', [1, 2]);
                })
            ]
        ], Message::rules());
    }

    public function messages(): array
    {
        return [
            'order_id.exists' => __('messages.chat_not_exists')
        ];
    }
}
