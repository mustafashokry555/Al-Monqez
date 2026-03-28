<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen($request->routeIs('api.profile.index') && auth()->user()->role_id == '3', [
                'id' => $this->id,
                'sub_category_type' => $this->sub_category_type,
                'services' => ServiceResource::collection($this->services)
            ]),
            'name' => $this->name
        ];
    }
}
