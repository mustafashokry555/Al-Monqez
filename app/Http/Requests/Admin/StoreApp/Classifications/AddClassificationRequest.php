<?php

namespace App\Http\Requests\Admin\StoreApp\Classifications;

use App\Models\StoreClassification;
use Illuminate\Foundation\Http\FormRequest;

class AddClassificationRequest extends FormRequest
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
        return array_merge(StoreClassification::rules(), [
            'show' => 'nullable|in:on'
        ]);
    }
}
