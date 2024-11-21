<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire ajouté avec succès',
            'data' => new CommentResource($comment)
        ], 201);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire mis à jour avec succès',
            'data' => new CommentResource($comment)
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire supprimé avec succès'
        ]);
    }
}