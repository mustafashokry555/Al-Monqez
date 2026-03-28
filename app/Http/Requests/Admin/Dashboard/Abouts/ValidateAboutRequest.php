<?php

namespace App\Http\Requests\Admin\Dashboard\Abouts;

use Illuminate\Foundation\Http\FormRequest;

class ValidateAboutRequest extends FormRequest
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
            'about_id' => 'required|exists:abouts,id'
        ];
    }
}
