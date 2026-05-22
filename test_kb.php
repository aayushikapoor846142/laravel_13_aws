<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KbArticle;
use App\Services\SupportKnowledgeBaseService;

echo "Testing Knowledge Base...\n\n";

// Check articles
$articles = KbArticle::all();
echo "Total articles: " . $articles->count() . "\n";

foreach ($articles as $article) {
    $hasEmbedding = !empty($article->embedding) && $article->embedding !== 'null';
    echo "- {$article->title}: " . ($hasEmbedding ? "✓ Has embedding" : "✗ No embedding") . "\n";
}

echo "\n\nTesting query...\n";

$service = app(SupportKnowledgeBaseService::class);
$result = $service->answerQuestion('How do I reset my password?');

echo "Answer: " . $result['answer'] . "\n";
echo "Has relevant info: " . ($result['has_relevant_info'] ? 'Yes' : 'No') . "\n";
echo "Sources: " . count($result['sources']) . "\n";

if (!empty($result['sources'])) {
    foreach ($result['sources'] as $source) {
        echo "  - {$source['title']} (similarity: {$source['similarity']})\n";
    }
}
