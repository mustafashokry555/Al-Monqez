<?php

namespace App\Http\Requests\Admin\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'phone' => [
                'required',
                'string',
                Rule::exists('users')->where(function ($query) {
                    return $query->where('role_id', 1)->orWhere(function ($query) {
                        return $query->where(function ($query) {
                            return $query->whereIn('role_id', [2, 6, 7]);
                        })->where('blocked', 0);
                    });
                })
            ],
            'password' => 'required|string'
        ];
    }
}
