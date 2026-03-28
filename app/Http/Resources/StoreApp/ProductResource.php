<?php

namespace App\Http\Resources\StoreApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            $this->mergeWhen(!$request->routeIs('api.store-app.cart.show'), [
                'store_name' => $this->store_name
            ]),
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->imageLink,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            $this->mergeWhen(!$request->routeIs('api.store-app.cart.show'), [
                'is_favorite' => $this->is_favorite
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.cart.show', 'api.store-app.product.show'), [
                'max_quantity' => $this->max_quantity,
                'quantity' => $this->quantity
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.product.show'), [
                'images' => ImageResource::collection($this->images)
            ])
        ];
    }
}
