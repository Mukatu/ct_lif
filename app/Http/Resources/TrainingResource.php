<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'level' => $this->level,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'video_url' => $this->media ? url('storage/' . $this->media->path) : null,
            'thumbnail_url' => $this->media?->thumbnail_path 
                ? url('storage/' . $this->media->thumbnail_path)
                : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}