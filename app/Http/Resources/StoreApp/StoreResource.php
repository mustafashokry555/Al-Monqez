<?php

namespace App\Http\Resources\StoreApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'name' => $this->name,
            $this->mergeWhen(!$request->routeIs('api.store-app.cart.show'), [
                'address' => $this->address,
            ]),
            'image' => $this->imageLink,
            $this->mergeWhen($request->routeIs('api.store-app.store.show'), [
                'cover_image' => $this->coverImageLink,
                'rating' => $this->rating,
                'classifications' => ClassificationResource::collection($this->classifications)
            ]),
            $this->mergeWhen($request->routeIs('api.store-app.store.show', 'api.store-app.stores.favorites'), [
                'is_favorite' => $this->is_favorite
            ])
        ];
    }
}
