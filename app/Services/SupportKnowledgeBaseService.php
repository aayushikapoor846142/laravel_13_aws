<?php

namespace App\Services;

use App\Models\KbArticle;
use App\Models\SupportQuery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupportKnowledgeBaseService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = config('services.openai.api_url', 'https://api.openai.com/v1');
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
     * Generate embedding
     */
    private function generateEmbedding(string $text): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/embeddings', [
                'model' => 'text-embedding-ada-002',
                'input' => $text,
            ]);

            if ($response->successful()) {
                return $response->json()['data'][0]['embedding'];
            }

            Log::error('Embedding failed', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Embedding error: ' . $e->getMessage());
            return [];
        }
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
                
                if ($similarity > 0.7) { // Only include relevant articles
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

        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $mag1 += $vec1[$i] ** 2;
            $mag2 += $vec2[$i] ** 2;
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
     * Generate answer using GPT
     */
    private function generateAnswer(string $question, string $context): string
    {
        try {
            $systemPrompt = "You are a helpful customer support assistant. Answer questions based ONLY on the provided knowledge base articles. Be concise, friendly, and professional. If the context doesn't contain enough information, say so.";
            
            $userPrompt = "Knowledge Base:\n{$context}\n\nCustomer Question: {$question}\n\nProvide a helpful answer:";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            Log::error('Answer generation failed', ['response' => $response->body()]);
            return "I'm having trouble generating an answer right now. Please try again.";
        } catch (\Exception $e) {
            Log::error('Answer generation error: ' . $e->getMessage());
            return "An error occurred. Please contact support.";
        }
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
