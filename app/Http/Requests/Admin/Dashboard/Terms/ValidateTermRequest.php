<?php

namespace App\Http\Requests\Admin\Dashboard\Terms;

use Illuminate\Foundation\Http\FormRequest;

class ValidateTermRequest extends FormRequest
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
            'term_id' => 'required|exists:terms,id'
        ];
    }
}
