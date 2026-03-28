<?php

namespace App\Http\Resources\ServicesApp;

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
            'parent_order_id' => $this->parent_order_id,
            $this->mergeWhen(auth()->user()->role_id == '3', [
                'client_name' => $this->client_name,
                'client_image' => $this->client_imageLink
            ]),
            $this->mergeWhen(auth()->user()->role_id == '4' && $this->status > 0 && $this->status < 4, [
                'worker_name' => $this->worker_name,
                'worker_phone' => $this->worker_phone,
                'worker_image' => $this->worker_imageLink
            ]),
            'category_name' => $this->category_name,
            'sub_category_name' => $this->sub_category_name,
            $this->mergeWhen($request->routeIs('orders.show'), [
                'services' => $this->services[0]->description ?? "",
                'description' => $this->description,
                'images' => ImageResource::collection($this->problemImages),
                'city_name' => $this->city_name,
                'start_title' => $this->start_title,
                'start_latitude' => $this->start_latitude,
                'start_longitude' => $this->start_longitude,
                'end_title' => $this->end_title,
                'end_latitude' => $this->end_latitude,
                'end_longitude' => $this->end_longitude,
                'child_order_ids' => $this->childOrders->pluck('id'),
            ]),
            $this->mergeWhen(auth()->user()->role_id == '3' || ($this->status > 0 && $this->status < 4), [
                'total' => $this->total ? number_format((float)$this->total, 2, '.', '') : null
            ]),
            'date' => $this->dateFormatted,
            'time' => $this->timeFormatted,
            'status' => $this->status,
           'payment_method' => $this->payment_method,
           'payment' => $this->payment,
            $this->mergeWhen($this->status == '0' && auth()->user()->role_id == '4', [
                'requests_count' => $this->requests_count
            ]),
            $this->mergeWhen($request->routeIs('orders.index'), [
                'have_new_notifications' => $this->have_new_notifications,
                'have_new_messages' => $this->have_new_messages
            ]),
            'warranty_end_date' => $this->warranty_end_date
        ];
    }
}
