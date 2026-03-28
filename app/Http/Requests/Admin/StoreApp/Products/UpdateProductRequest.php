<?php

namespace App\Http\Requests\Admin\StoreApp\Products;

use App\Models\StoreProduct;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return array_merge(StoreProduct::rules($this), [
            'product_id' => 'required|exists:store_products,id',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp',
            'images' => 'nullable|array|max:10',
        ]);
    }
}
