<?php

namespace App\Http\Controllers\API;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\MediaUploadService;
use App\Http\Resources\TrainingResource;
use App\Http\Requests\Training\StoreTrainingRequest;
use App\Http\Requests\Training\UpdateTrainingRequest;

class TrainingController extends Controller
{
    public function __construct(private MediaUploadService $mediaUploadService)
    {
    }

    public function index(): JsonResponse
    {
        $trainings = Training::with('category')
            ->when(request('category'), function ($query) {
                $query->where('category_id', request('category'));
            })
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => TrainingResource::collection($trainings)
        ]);
    }

    public function store(StoreTrainingRequest $request): JsonResponse
    {
        $this->authorize('create', Training::class);

        $training = Training::create($request->validated());

        if ($request->hasFile('video')) {
            $media = $this->mediaUploadService->uploadMedia(
                $request->file('video'),
                'trainings'
            );
            $training->media()->create($media);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Formation créée avec succès',
            'data' => new TrainingResource($training)
        ], 201);
    }

    public function update(UpdateTrainingRequest $request, Training $training): JsonResponse
    {
        $this->authorize('update', $training);

        $training->update($request->validated());

        if ($request->hasFile('video')) {
            if ($training->media) {
                $this->mediaUploadService->deleteMedia($training->media->path);
                $training->media->delete();
            }

            $media = $this->mediaUploadService->uploadMedia(
                $request->file('video'),
                'trainings'
            );
            $training->media()->create($media);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Formation mise à jour avec succès',
            'data' => new TrainingResource($training)
        ]);
    }

    public function destroy(Training $training): JsonResponse
    {
        $this->authorize('delete', $training);

        if ($training->media) {
            $this->mediaUploadService->deleteMedia($training->media->path);
        }

        $training->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Formation supprimée avec succès'
        ]);
    }
}