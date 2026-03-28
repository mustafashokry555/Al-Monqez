<?php

namespace App\Http\Requests\Api\ServicesApp\Offers;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\OrderRequest;
use App\Rules\ServicesApp\UsedCoupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessOfferRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;

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
        $orderRequest = OrderRequest::select('order_requests.*')
            ->join('orders', 'orders.id', '=', 'order_requests.order_id')
            ->where([['order_requests.id', $this->offer_id], ['orders.status', '=', '0'], ['orders.client_id', auth()->id()]])
            ->whereNotNull('order_requests.price')
            ->first();

        $this->merge([
            'order_request' => $orderRequest
        ]);

        return [
            'offer_id' => [
                'required',
                Rule::in($orderRequest->id ?? 0)
            ],
            'status' => 'required|boolean',
            'payment_type' => [
                Rule::requiredIf($this->status == '1'),
                'nullable',
                'boolean'
            ],
            'transaction_id' => [
            //    Rule::requiredIf($this->status == '1'),
                'nullable',
                'string',
                'max:250'
            ],
            'coupon_code' => [
                'nullable',
                'string',
                'max:250',
                'exists:partners',
                new UsedCoupon()
            ]
        ];
    }
}
