<?php

namespace App\Http\Requests\Api\ServicesApp\Complains;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\Complaint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeComplainRequest extends FormRequest
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
        $order_id = Complaint::where('order_id', $this->order_id)
            ->where('status', '<', '2')
            ->exists() ? 0 : $this->order_id;

        return [
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('client_id', auth()->id())
                        ->where('status', '3')
                        ->where('warranty_end_date', '>=', now());
                }),
                'in:' . $order_id
            ],
            'message' => 'required|string|max:5000'
        ];
    }


    public function messages()
    {
        return [
            'order_id.in' => __('messages.complaint_already_made')
        ];
    }
}
