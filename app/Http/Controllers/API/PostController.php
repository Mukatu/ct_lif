<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\MediaUploadService;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    public function __construct(private MediaUploadService $mediaUploadService)
    {
    }

    public function index(): JsonResponse
    {
        $posts = Post::with(['author', 'comments.author', 'media'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => PostResource::collection($posts)
        ]);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        if ($request->hasFile('media')) {
            $media = $this->mediaUploadService->uploadMedia(
                $request->file('media'),
                'posts'
            );
            $post->media()->create($media);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Publication créée avec succès',
            'data' => new PostResource($post)
        ], 201);
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        if ($request->hasFile('media')) {
            // Supprimer l'ancien média s'il existe
            if ($post->media) {
                $this->mediaUploadService->deleteMedia($post->media->path);
                $post->media->delete();
            }

            $media = $this->mediaUploadService->uploadMedia(
                $request->file('media'),
                'posts'
            );
            $post->media()->create($media);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Publication mise à jour avec succès',
            'data' => new PostResource($post)
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        if ($post->media) {
            $this->mediaUploadService->deleteMedia($post->media->path);
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Publication supprimée avec succès'
        ]);
    }

    public function like(Post $post): JsonResponse
    {
        $post->likes()->toggle(auth()->id());

        return response()->json([
            'status' => 'success',
            'liked' => $post->likes()->where('user_id', auth()->id())->exists(),
            'likes_count' => $post->likes()->count()
        ]);
    }
}