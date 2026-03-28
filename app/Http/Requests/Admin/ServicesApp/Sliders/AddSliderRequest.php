<?php

namespace App\Http\Requests\Admin\ServicesApp\Sliders;

use App\Models\Slider;
use Illuminate\Foundation\Http\FormRequest;

class AddSliderRequest extends FormRequest
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
        return array_merge(Slider::rules(), [
            'show' => 'nullable|in:on'
        ]);
    }
}
