<?php

namespace App\Http\Requests\Admin\StoreApp\Stores;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
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
        return array_merge(User::clientRules(), User::storeRules($this), [
            'store_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '6');
                })
            ],
            'email' => [
                'nullable',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->store_id);
                })
            ],
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->store_id);
                }),
                'starts_with:+966',
                'min:13',
                'max:13'
            ],
            'password' => 'nullable|string|max:50',
             'cover_image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'commercial_registration' => 'nullable|mimes:png,jpg,jpeg,webp',
            'license' => 'nullable|mimes:png,jpg,jpeg,webp',
            'bank_name' => 'nullable|string|max:250',
            'account_holder_name' => 'nullable|string|max:250',
            'IBAN' => 'nullable|string|max:250',
        ]);
    }
}
