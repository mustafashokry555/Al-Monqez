<?php

namespace App\Http\Requests\Admin\ServicesApp\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AddOrderSettingRequest extends FormRequest
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
            'warranty_days' => 'required|integer|min:0|max:365',
            'management_ratio' => 'required|integer|min:0|max:100',
            'deposit_ratio' =>  'required|integer|min:0|max:100',
            'vat' => 'required|integer|min:0|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
          'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ];
    }
}
