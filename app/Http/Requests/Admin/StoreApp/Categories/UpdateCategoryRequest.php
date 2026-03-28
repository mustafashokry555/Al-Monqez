<?php

namespace App\Http\Requests\Admin\StoreApp\Categories;

use App\Models\StoreCategory as Category;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        return array_merge(Category::rules(), [
            'category_id' => 'required|exists:store_categories,id',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp'
        ]);
    }
}
