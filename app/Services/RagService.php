<?php

namespace App\Services;

use App\Models\RagDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = config('services.openai.api_url', 'https://api.openai.com/v1');
    }

    /**
     * Store document with embedding
     */
    public function storeDocument(string $title, string $content, ?string $source = null, ?array $metadata = []): RagDocument
    {
        $embedding = $this->generateEmbedding($content);
        
        return RagDocument::create([
            'title' => $title,
            'content' => $content,
            'embedding' => json_encode($embedding),
            'source' => $source,
            'metadata' => $metadata
        ]);
    }

    /**
     * Generate embedding for text
     */
    private function generateEmbedding(string $text): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/embeddings', [
                'model' => 'text-embedding-ada-002',
                'input' => $text,
            ]);

            if ($response->successful()) {
                return $response->json()['data'][0]['embedding'];
            }

            Log::error('Embedding generation failed', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Embedding error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search similar documents using cosine similarity
     */
    public function searchSimilar(string $query, int $limit = 5): array
    {
        $queryEmbedding = $this->generateEmbedding($query);
        
        if (empty($queryEmbedding)) {
            return [];
        }

        $documents = RagDocument::all();
        $results = [];

        foreach ($documents as $doc) {
            if ($doc->embedding) {
                $docEmbedding = json_decode($doc->embedding, true);
                $similarity = $this->cosineSimilarity($queryEmbedding, $docEmbedding);
                
                $results[] = [
                    'document' => $doc,
                    'similarity' => $similarity
                ];
            }
        }

        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);
        
        return array_slice($results, 0, $limit);
    }

    /**
     * Calculate cosine similarity
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
     * Generate answer using RAG
     */
    public function generateAnswer(string $question): array
    {
        $relevantDocs = $this->searchSimilar($question, 3);
        
        if (empty($relevantDocs)) {
            return [
                'answer' => 'No relevant information found.',
                'sources' => []
            ];
        }

        $context = collect($relevantDocs)->map(fn($item) => $item['document']->content)->join("\n\n");
        
        $prompt = "Based on the following context, answer the question.\n\nContext:\n{$context}\n\nQuestion: {$question}\n\nAnswer:";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that answers questions based on provided context.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return [
                    'answer' => $response->json()['choices'][0]['message']['content'],
                    'sources' => collect($relevantDocs)->map(fn($item) => [
                        'title' => $item['document']->title,
                        'similarity' => round($item['similarity'], 3)
                    ])->toArray()
                ];
            }

            return [
                'answer' => 'Failed to generate answer.',
                'sources' => []
            ];
        } catch (\Exception $e) {
            Log::error('RAG generation error: ' . $e->getMessage());
            return [
                'answer' => 'Error generating answer.',
                'sources' => []
            ];
        }
    }
}
