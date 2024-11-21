<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentationController extends Controller
{
    /**
     * Récupérer la liste des documents
     */
    public function index(): JsonResponse
    {
        $documents = Document::with('category')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $documents
        ]);
    }

    /**
     * Ajouter un nouveau document
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $path = $request->file('file')->store('documents');
        
        $document = Document::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'file_path' => $path
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Document ajouté avec succès',
            'data' => $document
        ], 201);
    }

    /**
     * Télécharger un document
     */
    public function download(Document $document)
    {
        return response()->download(storage_path('app/' . $document->file_path));
    }

    /**
     * Supprimer un document
     */
    public function destroy(Document $document): JsonResponse
    {
        $document->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Document supprimé avec succès'
        ]);
    }
}