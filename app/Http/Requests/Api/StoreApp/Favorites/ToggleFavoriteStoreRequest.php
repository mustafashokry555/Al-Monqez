<?php

namespace App\Http\Requests\Api\StoreApp\Favorites;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleFavoriteStoreRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

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
            'store_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role_id', '6')->where('blocked', '0');
                }),
            ]
        ];
    }
}
