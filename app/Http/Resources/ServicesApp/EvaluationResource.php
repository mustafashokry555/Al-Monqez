<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'client_name' => $this->client_name,
            'client_image' => $this->client_imageLink,
            'rating' => number_format((float)$this->rating, 1, '.', ''),
            'message' => $this->message
        ];
    }
}
