<?php

namespace App\Http\Requests\Admin\ServicesApp\Services;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
        return array_merge(Service::rules(), [
            'service_id' => 'required|exists:services,id',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp'
        ]);
    }
}
