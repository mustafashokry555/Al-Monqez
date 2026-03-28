<?php

namespace App\Http\Requests\Admin\ServicesApp\Offers;

use App\Models\OrderRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeOfferRequest extends FormRequest
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
        $orderRequest = OrderRequest::select('order_requests.*')
            ->join('orders', 'orders.id', '=', 'order_requests.order_id')
            ->where([['orders.id', $this->order_id], ['orders.status', '=', '0'], ['order_requests.worker_id', auth()->id()]])
            ->first();

        $this->merge([
            'order_request' => $orderRequest
        ]);

        return [
            'order_id' => [
                'required',
                Rule::in($orderRequest->order_id ?? 0),
                Rule::exists('order_requests')->where(function ($query) {
                    return $query->where('worker_id', auth()->id())->whereNull('price');
                })
            ],
            'price' => 'required|numeric|min:1|max:' . PHP_INT_MAX
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.in' => __('messages.invalid_order'),
            'order_id.exists' => __('messages.offer_sent_before')
        ];
    }
}
