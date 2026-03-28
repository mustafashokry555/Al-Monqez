<?php

namespace App\Http\Requests\Admin\ServicesApp\Cities;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
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
        return array_merge(City::rules(), [
            'city_id' => 'required|exists:cities,id'
        ]);
    }
}
