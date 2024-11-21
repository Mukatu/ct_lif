<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'file_url' => url('storage/' . $this->file_path),
            'file_type' => pathinfo($this->file_path, PATHINFO_EXTENSION),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}