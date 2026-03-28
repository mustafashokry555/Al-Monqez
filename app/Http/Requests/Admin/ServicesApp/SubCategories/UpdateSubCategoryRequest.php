<?php

namespace App\Http\Requests\Admin\ServicesApp\SubCategories;

use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubCategoryRequest extends FormRequest
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
        return array_merge(SubCategory::rules(), [
            'sub_category_id' => 'required|exists:sub_categories,id',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp'
        ]);
    }
}
