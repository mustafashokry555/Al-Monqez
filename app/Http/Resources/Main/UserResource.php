<?php

namespace App\Http\Resources\Main;

use App\Http\Resources\ServicesApp\UserSubCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            "image" => $this->imageLink,
            $this->mergeWhen($request->routeIs('api.profile.index') && auth()->user()->role_id == '3', [
                'city_id' => $this->city_id,
                'category_id' => $this->category_id,
                'category_name' => $this->category_name,
                'sub_categories' => UserSubCategoryResource::collection($this->subCategories),
                'description' => $this->description
            ]),
            $this->mergeWhen($request->routeIs('api.profile.index') && in_array(auth()->user()->role_id, ['3', '5']), [
                'id_number' => $this->id_number,
                'vehicle_license_image' => $this->vehicle_license_image_link,
                'driving_license_image' => $this->driving_license_image_link,
                'bank_name' => $this->bank_name,
                'iban_number' => $this->iban_number,
                'images' => UserFileResource::collection($this->files),
            ]),
            $this->mergeWhen($request->routeIs('api.profile.worker'), [
                'rating' => number_format((float)$this->rating, 1, '.', ''),
                'city_name' => $this->city_name,
                'category_name' => $this->category_name,
                'sub_category_name' => $this->sub_category_name,
                'description' => $this->description
            ])
        ];
    }
}
