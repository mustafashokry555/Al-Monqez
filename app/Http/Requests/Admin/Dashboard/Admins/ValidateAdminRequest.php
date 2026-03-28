<?php

namespace App\Http\Requests\Admin\Dashboard\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateAdminRequest extends FormRequest
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
            'admin_id' => [
                'required',
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('role_id', 2);
                })
            ]
        ];
    }
}
