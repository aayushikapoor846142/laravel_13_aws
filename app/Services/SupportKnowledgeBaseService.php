<?php

namespace App\Services;

use App\Models\KbArticle;
use App\Models\SupportQuery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupportKnowledgeBaseService
{
    private $ollamaUrl;
    private $model;

    public function __construct()
    {
        $this->ollamaUrl = config('services.ollama.api_url');
        $this->model = config('services.ollama.model');
    }

    /**
     * Add article to knowledge base
     */
    public function addArticle(string $title, string $content, string $category): KbArticle
    {
        $embedding = $this->generateEmbedding($content);
        
        return KbArticle::create([
            'title' => $title,
            'content' => $content,
            'category' => $category,
            'embedding' => json_encode($embedding),
            'is_published' => true
        ]);
    }

    /**
     * Generate embedding using Ollama
     */
    private function generateEmbedding(string $text): array
    {
        try {
            $response = Http::timeout(120)->post($this->ollamaUrl . '/api/embeddings', [
                'model' => $this->model,
                'prompt' => $text,
            ]);

            if ($response->successful()) {
                return $response->json()['embedding'];
            }

            Log::warning('Ollama embedding failed, using fallback', ['response' => $response->body()]);
            return $this->generateSimpleEmbedding($text);
        } catch (\Exception $e) {
            Log::warning('Ollama not available, using fallback: ' . $e->getMessage());
            return $this->generateSimpleEmbedding($text);
        }
    }

    /**
     * Fallback: Simple embedding using TF-IDF-like approach
     */
    private function generateSimpleEmbedding(string $text): array
    {
        $words = str_word_count(strtolower($text), 1);
        $embedding = array_fill(0, 384, 0.0);
        
        foreach ($words as $i => $word) {
            $hash = crc32($word) % 384;
            $embedding[$hash] += 1.0;
        }
        
        $magnitude = sqrt(array_sum(array_map(fn($x) => $x * $x, $embedding)));
        if ($magnitude > 0) {
            $embedding = array_map(fn($x) => $x / $magnitude, $embedding);
        }
        
        return $embedding;
    }

    /**
     * Find relevant articles
     */
    private function findRelevantArticles(string $query, int $limit = 3): array
    {
        $queryEmbedding = $this->generateEmbedding($query);
        
        if (empty($queryEmbedding)) {
            return [];
        }

        $articles = KbArticle::where('is_published', true)->get();
        $results = [];

        foreach ($articles as $article) {
            if ($article->embedding) {
                $articleEmbedding = json_decode($article->embedding, true);
                $similarity = $this->cosineSimilarity($queryEmbedding, $articleEmbedding);
                
                Log::info("Article: {$article->title}, Similarity: {$similarity}");
                
                if ($similarity > 0.3) { // Lowered threshold for better matching
                    $results[] = [
                        'article' => $article,
                        'similarity' => $similarity
                    ];
                }
            }
        }

        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);
        
        return array_slice($results, 0, $limit);
    }

    /**
     * Cosine similarity calculation
     */
    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0;
        $mag1 = 0;
        $mag2 = 0;
        
        $minLength = min(count($vec1), count($vec2));

        for ($i = 0; $i < $minLength; $i++) {
            $dotProduct += ($vec1[$i] ?? 0) * ($vec2[$i] ?? 0);
            $mag1 += ($vec1[$i] ?? 0) ** 2;
            $mag2 += ($vec2[$i] ?? 0) ** 2;
        }

        $magnitude = sqrt($mag1) * sqrt($mag2);
        
        return $magnitude > 0 ? $dotProduct / $magnitude : 0;
    }

    /**
     * Answer customer question using RAG
     */
    public function answerQuestion(string $question, ?int $userId = null): array
    {
        $relevantArticles = $this->findRelevantArticles($question, 3);
        
        if (empty($relevantArticles)) {
            $answer = "I couldn't find relevant information in our knowledge base. Please contact our support team for assistance.";
            $sources = [];
        } else {
            // Build context from relevant articles
            $context = collect($relevantArticles)->map(function($item) {
                return "Article: {$item['article']->title}\n{$item['article']->content}";
            })->join("\n\n---\n\n");
            
            $answer = $this->generateAnswer($question, $context);
            $sources = collect($relevantArticles)->map(fn($item) => [
                'id' => $item['article']->id,
                'title' => $item['article']->title,
                'category' => $item['article']->category,
                'similarity' => round($item['similarity'], 3)
            ])->toArray();

            // Increment view count for used articles
            foreach ($relevantArticles as $item) {
                $item['article']->incrementViews();
            }
        }

        // Log the query
        $query = SupportQuery::create([
            'user_id' => $userId,
            'question' => $question,
            'answer' => $answer,
            'sources' => $sources
        ]);

        return [
            'query_id' => $query->id,
            'answer' => $answer,
            'sources' => $sources,
            'has_relevant_info' => !empty($relevantArticles)
        ];
    }

    /**
     * Generate answer using Ollama
     */
    private function generateAnswer(string $question, string $context): string
    {
        try {
            $prompt = "You are a helpful customer support assistant. Answer the question based ONLY on the provided knowledge base articles. Be concise, friendly, and professional.\n\nKnowledge Base:\n{$context}\n\nCustomer Question: {$question}\n\nAnswer:";

            $response = Http::timeout(120)->post($this->ollamaUrl . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($response->successful()) {
                return $response->json()['response'];
            }

            Log::warning('Ollama answer generation failed', ['response' => $response->body()]);
            return $this->generateFallbackAnswer($question, $context);
        } catch (\Exception $e) {
            Log::warning('Ollama not available: ' . $e->getMessage());
            return $this->generateFallbackAnswer($question, $context);
        }
    }

    /**
     * Fallback answer without LLM
     */
    private function generateFallbackAnswer(string $question, string $context): string
    {
        // Extract first 500 chars from most relevant article
        $excerpt = substr($context, 0, 500);
        return "Based on our knowledge base:\n\n" . $excerpt . "\n\n(Note: AI assistant is currently unavailable. Showing relevant article excerpt.)";
    }

    /**
     * Record feedback
     */
    public function recordFeedback(int $queryId, bool $wasHelpful, ?string $feedback = null): void
    {
        $query = SupportQuery::findOrFail($queryId);
        $query->update([
            'was_helpful' => $wasHelpful,
            'feedback' => $feedback
        ]);

        // If helpful, increment helpful count for source articles
        if ($wasHelpful && $query->sources) {
            foreach ($query->sources as $source) {
                if (isset($source['id'])) {
                    KbArticle::find($source['id'])?->markHelpful();
                }
            }
        }
    }

    /**
     * Get analytics
     */
    public function getAnalytics(): array
    {
        return [
            'total_articles' => KbArticle::count(),
            'total_queries' => SupportQuery::count(),
            'helpful_rate' => SupportQuery::whereNotNull('was_helpful')->avg('was_helpful') * 100,
            'top_articles' => KbArticle::orderBy('view_count', 'desc')->limit(5)->get(),
            'recent_queries' => SupportQuery::with('user')->latest()->limit(10)->get()
        ];
    }
}
