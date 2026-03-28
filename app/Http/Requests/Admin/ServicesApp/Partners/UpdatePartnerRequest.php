<?php

namespace App\Http\Requests\Admin\ServicesApp\Partners;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerRequest extends FormRequest
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
        return array_merge(Partner::rules(), [
            'partner_id' => 'required|exists:partners,id',
            'coupon_code' => 'required|string|max:50|unique:partners,coupon_code' . ($this->partner_id ? ",$this->partner_id" : ''),
        ]);
    }
}
