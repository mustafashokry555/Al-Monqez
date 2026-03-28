<?php

namespace App\Http\Requests\Admin\Dashboard\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required|integer|min:0|max:4',
            'phone' => [
                Rule::requiredIf($this->type == '0'),
                'nullable',
                Rule::exists('users')->where(function ($query) {
                    return $query->whereIn('role_id', [3, 4, 5]);
                }),
            ],
            'title' => 'required|string|max:250',
            'message' => 'required|string|max:5000'
        ];
    }
}
