<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'author' => new UserResource($this->author),
            'media' => $this->media ? [
                'type' => $this->media->type,
                'url' => url('storage/' . $this->media->path),
                'thumbnail_url' => $this->media->thumbnail_path 
                    ? url('storage/' . $this->media->thumbnail_path)
                    : null
            ] : null,
            'likes_count' => $this->likes()->count(),
            'liked_by_user' => $this->likes()->where('user_id', auth()->id())->exists(),
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}