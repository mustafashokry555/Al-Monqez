<?php

namespace App\Http\Resources\StoreApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_name' => $this->store_name,
            'store_image' => $this->store_image_link,
            $this->mergeWhen(!$request->routeIs('api.store-app.order.show') && auth()->user()->role_id == '5', [
                'address' => $this->address,
                'client_phone' => $this->client_phone,
                'products_count' => $this->products_count
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.order.show') && auth()->user()->role_id == '4', [
                'driver_name' => $this->driver_name,
                'driver_image' => ($this->driver_name) ? $this->driver_image_link : null,
                'driver_phone' => $this->driver_phone,
                'coupon_code' => $this->coupon_code
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.order.show') && auth()->user()->role_id == '5', [
                'client_name' => $this->client_name,
                'client_image' => ($this->client_name) ? $this->client_image_link : null,
                'client_phone' => $this->client_phone
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.order.show'), [
                'store_id' => $this->store_id,
                'products' => OrderProductResource::collection($this->products),
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'vat_percentage' => $this->vat,
                'vat_amount' => number_format((float)$this->vat_amount, 2, '.', ''),
                'delivery_charge' => $this->delivery_charge,
                'sub_total_price' => $this->sub_total,
                'discount_amount' => number_format((float)$this->discount_amount, 2, '.', '')
            ]),
            'total_price' => $this->total,
            'status' => $this->status,
            'created_at' => $this->created_at,
          'payment_method' => $this->payment_method,
          'payment' => $this->payment
        ];
    }
}
