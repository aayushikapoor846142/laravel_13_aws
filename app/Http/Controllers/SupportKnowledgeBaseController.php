<?php

namespace App\Http\Controllers;

use App\Services\SupportKnowledgeBaseService;
use App\Models\KbArticle;
use Illuminate\Http\Request;

class SupportKnowledgeBaseController extends Controller
{
    private $kbService;

    public function __construct(SupportKnowledgeBaseService $kbService)
    {
        $this->kbService = $kbService;
    }

    /**
     * Show support page
     */
    public function index()
    {
        $categories = KbArticle::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        $popularArticles = KbArticle::where('is_published', true)
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        return view('support.index', compact('categories', 'popularArticles'));
    }

    /**
     * Ask a question (API endpoint)
     */
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|min:5'
        ]);

        $userId = auth()->id();
        $result = $this->kbService->answerQuestion($validated['question'], $userId);

        return response()->json($result);
    }

    /**
     * Submit feedback
     */
    public function feedback(Request $request, $queryId)
    {
        $validated = $request->validate([
            'was_helpful' => 'required|boolean',
            'feedback' => 'nullable|string|max:500'
        ]);

        $this->kbService->recordFeedback(
            $queryId,
            $validated['was_helpful'],
            $validated['feedback'] ?? null
        );

        return response()->json(['success' => true]);
    }

    /**
     * Admin: Add article
     */
    public function storeArticle(Request $request)
    {
        $this->authorize('admin'); // Add admin check

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100'
        ]);

        $article = $this->kbService->addArticle(
            $validated['title'],
            $validated['content'],
            $validated['category']
        );

        return response()->json([
            'success' => true,
            'article' => $article
        ], 201);
    }

    /**
     * Admin: View analytics
     */
    public function analytics()
    {
        $this->authorize('admin');

        $analytics = $this->kbService->getAnalytics();

        return view('support.analytics', compact('analytics'));
    }

    /**
     * Browse articles by category
     */
    public function category($category)
    {
        $articles = KbArticle::where('category', $category)
            ->where('is_published', true)
            ->orderBy('helpful_count', 'desc')
            ->paginate(10);

        return view('support.category', compact('articles', 'category'));
    }

    /**
     * View single article
     */
    public function show($id)
    {
        $article = KbArticle::where('is_published', true)->findOrFail($id);
        $article->incrementViews();

        return view('support.article', compact('article'));
    }
}
