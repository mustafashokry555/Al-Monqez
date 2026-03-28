<?php

namespace App\Http\Requests\Admin\Dashboard\Profile;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $roles = [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'name' => 'required|string|max:250',
            'phone' => 'required|string|starts_with:+966|min:13|max:13|unique:users,phone,' . auth()->id(),
            'email' => 'nullable|email:filter|max:250|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|max:25'
        ];

        if (auth()->user()->role_id == 6) {
            $languages = ['ar', 'en', 'ur'];

            foreach ($languages as $lang) {
                $roles["address_$lang"] = 'nullable|string|max:250';
            }

            $roles = array_merge($roles, [

                'city_id' => [
                    'nullable',
                    \Illuminate\Validation\Rule::exists('store_cities', 'id')
                        ->where(fn($q) => $q->where('displayed', 1))
                ],

                'category_id' => [
                    'nullable',
                    \Illuminate\Validation\Rule::exists('store_categories', 'id')
                        ->where(fn($q) => $q->where('displayed', 1))
                ],

                'latitude'  => 'nullable|string|max:250',
                'longitude' => 'nullable|string|max:250',

                'cover_image' => 'nullable|mimes:png,jpg,jpeg,webp',
                'commercial_registration' => 'nullable|mimes:png,jpg,jpeg,webp',
                'license' => 'nullable|mimes:png,jpg,jpeg,webp',

                'bank_name' => 'nullable|string|max:250',
                'account_holder_name' => 'nullable|string|max:250',
                'IBAN' => 'nullable|string|max:250',

                'terms' => 'nullable',
            ]);
        }

        return $roles;
    }
}
