<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            $this->mergeWhen(!$request->routeIs('api.sub.categories.with.services') && !$request->routeIs('api.profile.index'), [
                'brief' => $this->brief,
            ]),
            $this->mergeWhen($request->routeIs('api.services.index'), [
                'description' => $this->description,
            ]),
            $this->mergeWhen(!$request->routeIs('api.profile.index'), [
                'image' => $this->imageLink,
            ]),
            $this->mergeWhen($request->routeIs('api.services.common', 'api.services.search'), [
                'sub_category_id' => $this->sub_category_id,
                'rating' => number_format((float)$this->rating, 1, '.', '')
            ])
        ];
    }
}
