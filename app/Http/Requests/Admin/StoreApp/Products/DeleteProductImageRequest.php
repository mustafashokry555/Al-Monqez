<?php

namespace App\Http\Requests\Admin\StoreApp\Products;

use Illuminate\Foundation\Http\FormRequest;

class DeleteProductImageRequest extends FormRequest
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
        return [
            'image_id' => 'required|exists:store_product_images,id'
        ];
    }
}
