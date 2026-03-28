<?php

namespace App\Http\Resources\Main;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo' => $this->logoLink,
            'store_image' => $this->storeImageLink,
            'services_image' => $this->servicesImageLink,
            'android_app_link' => $this->android_app_link,
            'ios_app_link' => $this->ios_app_link,
            'registration_link' => $this->registration_link,
            'app_version' => $this->app_version
        ];
    }
}
