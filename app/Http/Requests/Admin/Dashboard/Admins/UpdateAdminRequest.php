<?php

namespace App\Http\Requests\Admin\Dashboard\Admins;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
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
        return array_merge(User::permissionRules(), [
            'admin_id' => [
                'required',
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('role_id', 2);
                })
            ],
            'name' => 'required|string|max:250',
            'phone' => [
                'required',
                'string',
                'max:250',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->admin_id);
                })
            ],
            'password' => 'nullable|string|min:8|max:25',
            'terms' => 'nullable'
        ]);
    }
}
