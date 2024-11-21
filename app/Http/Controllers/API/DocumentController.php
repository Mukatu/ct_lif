<?php

namespace App\Http\Controllers\API;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\MediaUploadService;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;

class DocumentController extends Controller
{
    public function __construct(private MediaUploadService $mediaUploadService)
    {
    }

    public function index(): JsonResponse
    {
        $documents = Document::with('category')
            ->when(request('category'), function ($query) {
                $query->where('category_id', request('category'));
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => DocumentResource::collection($documents)
        ]);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $this->authorize('create', Document::class);

        $path = $this->mediaUploadService->uploadMedia(
            $request->file('file'),
            'documents'
        );

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'file_path' => $path['path']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Document ajouté avec succès',
            'data' => new DocumentResource($document)
        ], 201);
    }

    public function update(UpdateDocumentRequest $request, Document $document): JsonResponse
    {
        $this->authorize('update', $document);

        $document->update($request->validated());

        if ($request->hasFile('file')) {
            $this->mediaUploadService->deleteMedia($document->file_path);
            
            $path = $this->mediaUploadService->uploadMedia(
                $request->file('file'),
                'documents'
            );
            
            $document->update(['file_path' => $path['path']]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Document mis à jour avec succès',
            'data' => new DocumentResource($document)
        ]);
    }

    public function destroy(Document $document): JsonResponse
    {
        $this->authorize('delete', $document);

        $this->mediaUploadService->deleteMedia($document->file_path);
        $document->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Document supprimé avec succès'
        ]);
    }

    public function download(Document $document)
    {
        $this->authorize('download', $document);

        return response()->download(
            storage_path('app/' . $document->file_path),
            $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION)
        );
    }
}