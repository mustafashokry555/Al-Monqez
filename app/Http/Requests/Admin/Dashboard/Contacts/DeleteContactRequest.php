<?php

namespace App\Http\Requests\Admin\Dashboard\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class DeleteContactRequest extends FormRequest
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
            'contact_id' => 'required|exists:contacts,id'
        ];
    }
}
