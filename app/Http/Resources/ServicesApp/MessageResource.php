<?php

namespace App\Http\Resources\ServicesApp;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageResource extends JsonResource
{
    protected $additionalData;

    public function __construct($resource, $additionalData = [])
    {
        parent::__construct($resource);
        $this->additionalData = $additionalData;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_mine' => $this->user_id == auth()->id(),
            'user_name' => $this->user_name ?? __('admin.management'),
            'user_image' => $this->user_imageLink ?? $additionalData['setting']?->logoLink ?? Storage::url('defaults/logo.png'),
            'content' => $this->content,
            'read' => $this->read,
            'created_at' => $this->created_at,
            'files' => FileResource::collection($this->files)
        ];
    }
}
