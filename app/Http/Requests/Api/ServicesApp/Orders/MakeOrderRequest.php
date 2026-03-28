<?php

namespace App\Http\Requests\Api\ServicesApp\Orders;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\Order;
use App\Models\OrderSetting;
use App\Models\SubCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeOrderRequest extends FormRequest
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
        $hasTwoPickupPoints = SubCategory::where([['id', $this->sub_category_id], ['location_type', '1']])->exists();
        $orderSetting = OrderSetting::first();

        $parentOrder = null;
        if ($this->filled('order_id')) {
            $parentOrder = Order::select(
                'orders.city_id',
                'orders.date',
                'orders.time',
                'start_locations.title AS start_title',
                'start_locations.latitude AS start_latitude',
                'start_locations.longitude AS start_longitude',
                'end_locations.title AS end_title',
                'end_locations.latitude AS end_latitude',
                'end_locations.longitude AS end_longitude',
            )
                ->leftJoin('order_locations AS start_locations', function ($join) {
                    $join->on('start_locations.order_id', '=', 'orders.id')
                        ->where('start_locations.type', '0');
                })
                ->join('order_locations AS end_locations', function ($join) {
                    $join->on('end_locations.order_id', '=', 'orders.id')
                        ->where('end_locations.type', '1');
                })
                ->find($this->order_id);
        }

        $this->merge([
            'parent_order' => $parentOrder,
            'has_two_pickup_points' => $hasTwoPickupPoints,
            'management_ratio' => $orderSetting->management_ratio,
            'deposit_ratio' => $orderSetting->deposit_ratio,
            'vat' => $orderSetting->vat
        ]);

        $date = date('Y-m-d');
        $start_time = date('H:00:00', strtotime('+1 hour'));

        if (strtotime($start_time) > strtotime($orderSetting->end_time)) {
            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            $start_time = $orderSetting->start_time;
        } elseif (strtotime($start_time) < strtotime($orderSetting->start_time)) {
            $start_time = $orderSetting->start_time;
        }

        if ($this->date > $date) {
            $start_time = $orderSetting->start_time;
        }

        $end_date = date('Y-m-d', strtotime('+6 days', strtotime($date)));
        $end_time = $orderSetting->end_time;

        $servicesRules = [
            'required',
            'integer',
            Rule::exists('services', 'id')->where(function ($query) {
                return $query->where('sub_category_id', $this->sub_category_id)
                    ->where('displayed', '1');
            })
        ];

        if ($this->filled('order_id')) {
            $servicesValidation[] = Rule::exists('user_services', 'service_id')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            });
        }

        return [
            'order_id' => [
                'nullable',
                Rule::exists('orders', 'id')->where(function ($query) {
                    return $query->where('client_id', auth()->id())
                        ->whereIn('status', ['1', '2'])
                        ->whereNull('parent_order_id');
                })
            ],
            'sub_category_id' => [
                'required',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    return $query->where('displayed', '1');
                })
            ],
            'services' => 'required|array',
            'services.*' => $servicesRules,
            'city_id' => [
                Rule::requiredIf(!$this->filled('order_id')),
                'nullable',
                Rule::exists('cities', 'id')->where(function ($query) {
                    return $query->where('displayed', 1);
                })
            ],
            'start_title' => [
                Rule::requiredIf($hasTwoPickupPoints && !$this->parent_order?->start_title),
                'nullable',
                'string',
                'max:250'
            ],
            'start_latitude' => [
                Rule::requiredIf($hasTwoPickupPoints && !$this->parent_order?->start_latitude),
                'nullable',
                'string',
                'max:250'
            ],
            'start_longitude' => [
                Rule::requiredIf($hasTwoPickupPoints && !$this->parent_order?->start_longitude),
                'nullable',
                'string',
                'max:250'
            ],
            'end_title' => [
                Rule::requiredIf(!$this->parent_order?->end_title),
                'nullable',
                'string',
                'max:250'
            ],
            'end_latitude' => [
                Rule::requiredIf(!$this->parent_order?->end_latitude),
                'nullable',
                'string',
                'max:250'
            ],
            'end_longitude' => [
                Rule::requiredIf(!$this->parent_order?->end_longitude),
                'nullable',
                'string',
                'max:250'
            ],
            'description' => 'required|string|max:5000',
            'images' => 'required|array',
            'images.*' => 'required|mimes:png,jpg,jpeg,webp',
            'date' => [
                Rule::requiredIf(!$this->filled('order_id')),
                'nullable',
                'date_format:Y-m-d',
                "after_or_equal:$date",
                "before_or_equal:$end_date"
            ],
            'time' => [
                Rule::requiredIf(!$this->filled('order_id')),
                'nullable',
                'date_format:H:i:s',
                "after_or_equal:$start_time",
                "before_or_equal:$end_time"
            ]
        ];
    }
}
