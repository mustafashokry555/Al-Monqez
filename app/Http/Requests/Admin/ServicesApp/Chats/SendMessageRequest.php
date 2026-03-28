<?php

namespace App\Http\Requests\Admin\ServicesApp\Chats;

use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
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
        return array_merge([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->whereIn('role_id', [3, 4]);
                })
            ]
        ], Message::rules());
    }
}
