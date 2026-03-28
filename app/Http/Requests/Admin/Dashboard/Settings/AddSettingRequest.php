<?php

namespace App\Http\Requests\Admin\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AddSettingRequest extends FormRequest
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
        $languages = ['ar', 'en', 'ur'];

        $rules = [];
        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
            $rules["closed_message_$lang"] = 'required|string|max:250';
        }

        return array_merge($rules, [
            'phone' => 'required|string|max:50',
            'email' => 'required|string|email:filter|max:250',
            'site_status' => 'nullable|in:on',
            'logo' => 'nullable|mimes:png,jpg,jpeg,webp,ico',
            'android_app_link' => 'nullable|url:http,https|max:250',
            'ios_app_link' => 'nullable|url:http,https|max:250',
            'registration_link' => 'nullable|url:http,https|max:250',
            'app_version' => 'nullable|string|max:250'
        ]);
    }
}
