<?php

namespace App\Http\Requests\Admin\Dashboard\Admins;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddAdminRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            'phone' => 'required|string|max:50|unique:users,phone',
            'password' => 'required|string|min:8|max:25',
            'verified' => 'nullable|in:on'
        ]);
    }
}
