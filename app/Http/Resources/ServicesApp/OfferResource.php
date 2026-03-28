<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'order_id' => $this->order_id,
            'price' => number_format((float)$this->price, 2, '.', ''),
            'discount_amount' => number_format((float)$this->discount_amount, 2, '.', ''),
            'vat_ratio' => $this->vat,
            'vat' => number_format((float)$this->vat_value, 2, '.', ''),
            'deposit' => number_format((float)$this->deposit_price, 2, '.', ''),
            'total' => number_format((float)$this->total_price, 2, '.', ''),
            'date' => $this->date,
            'time' => $this->time,
            'category_name' => $this->category_name,
            'sub_category_name' => $this->sub_category_name,
            'worker_name' => $this->worker_name,
            'worker_phone' => $this->worker_phone,
            'worker_image' => $this->worker_imageLink,
         
        ];
    }
}
