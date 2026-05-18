<?php

namespace App\Http\Controllers;

use App\Services\RagService;
use Illuminate\Http\Request;
use App\Models\RagDocument;

class RagController extends Controller
{
    private $ragService;

    public function __construct(RagService $ragService)
    {
        $this->ragService = $ragService;
    }

    /**
     * Store a new document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'source' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        $document = $this->ragService->storeDocument(
            $validated['title'],
            $validated['content'],
            $validated['source'] ?? null,
            $validated['metadata'] ?? []
        );

        return response()->json([
            'success' => true,
            'document' => $document
        ], 201);
    }

    /**
     * Search similar documents
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $results = $this->ragService->searchSimilar(
            $validated['query'],
            $validated['limit'] ?? 5
        );

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Generate answer using RAG
     */
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string'
        ]);

        $result = $this->ragService->generateAnswer($validated['question']);

        return response()->json([
            'success' => true,
            'answer' => $result['answer'],
            'sources' => $result['sources']
        ]);
    }

    /**
     * List all documents
     */
    public function index()
    {
        $documents = RagDocument::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'documents' => $documents
        ]);
    }

    /**
     * Delete a document
     */
    public function destroy($id)
    {
        $document = RagDocument::findOrFail($id);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
}
