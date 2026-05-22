<?php

namespace App\Console\Commands;

use App\Models\KbArticle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateEmbeddings extends Command
{
    protected $signature = 'kb:generate-embeddings';
    protected $description = 'Generate embeddings for all knowledge base articles';

    public function handle()
    {
        $articles = KbArticle::whereNull('embedding')->orWhere('embedding', '')->get();
        
        if ($articles->isEmpty()) {
            $this->info('All articles already have embeddings!');
            return 0;
        }

        $this->info("Generating embeddings for {$articles->count()} articles...");
        $bar = $this->output->createProgressBar($articles->count());

        $ollamaUrl = config('services.ollama.api_url');
        $model = config('services.ollama.model');

        foreach ($articles as $article) {
            try {
                $response = Http::timeout(120)->post($ollamaUrl . '/api/embeddings', [
                    'model' => $model,
                    'prompt' => $article->content,
                ]);

                if ($response->successful()) {
                    $embedding = $response->json()['embedding'];
                    $article->update(['embedding' => json_encode($embedding)]);
                    $this->newLine();
                    $this->info("✓ Generated embedding for: {$article->title}");
                } else {
                    $this->newLine();
                    $this->warn("✗ Failed for: {$article->title}");
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("✗ Error for {$article->title}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Embeddings generation complete!');
        
        return 0;
    }
}
