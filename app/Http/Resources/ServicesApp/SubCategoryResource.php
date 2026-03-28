<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            $this->mergeWhen($request->routeIs('api.sub.categories', 'api.sub.categories.with.services'), [
                'sub_category_type' => $this->sub_category_type,
                'image' => $this->imageLink
            ]),
            $this->mergeWhen($request->routeIs('api.services.index'), [
                'location_type' => $this->location_type,
            ]),
            $this->mergeWhen($request->routeIs('api.services.index', 'api.sub.categories.with.services'), [
                'services' => ServiceResource::collection($this->services)
            ])
        ];
    }
}
