@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="display-4">How can we help you?</h1>
                <p class="lead text-muted">Ask a question or browse our knowledge base</p>
            </div>

            <!-- Search Box -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <input type="text" id="question" class="form-control form-control-lg" 
                               placeholder="Ask your question here..." />
                    </div>
                    <button id="askBtn" class="btn btn-primary btn-lg w-100">Get Answer</button>
                    
                    <div id="loading" class="text-center mt-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Finding the best answer for you...</p>
                    </div>
                </div>
            </div>

            <!-- Answer Section -->
            <div id="answerSection" style="display:none;" class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Answer:</h5>
                    <div id="answerText" class="mb-4"></div>
                    
                    <div id="sources" class="mb-4"></div>
                    
                    <hr>
                    <p class="mb-2"><strong>Was this helpful?</strong></p>
                    <button class="btn btn-success btn-sm me-2" onclick="submitFeedback(true)">👍 Yes</button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="submitFeedback(false)">👎 No</button>
                </div>
            </div>

            <!-- Categories -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-3">Browse by Category</h4>
                </div>
                @foreach($categories as $category)
                <div class="col-md-4 mb-3">
                    <a href="{{ route('support.category', $category) }}" class="text-decoration-none">
                        <div class="card h-100 hover-shadow">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Popular Articles -->
            <div class="mt-5">
                <h4 class="mb-3">Popular Articles</h4>
                <div class="list-group">
                    @foreach($popularArticles as $article)
                    <a href="{{ route('support.article', $article->id) }}" 
                       class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $article->title }}</h6>
                            <small class="text-muted">{{ $article->view_count }} views</small>
                        </div>
                        <small class="text-muted">{{ $article->category }}</small>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentQueryId = null;

document.getElementById('askBtn').addEventListener('click', askQuestion);
document.getElementById('question').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') askQuestion();
});

async function askQuestion() {
    const question = document.getElementById('question').value.trim();
    if (!question) return;

    document.getElementById('loading').style.display = 'block';
    document.getElementById('answerSection').style.display = 'none';

    try {
        const response = await fetch('{{ route("support.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question })
        });

        const data = await response.json();
        currentQueryId = data.query_id;

        document.getElementById('answerText').innerHTML = data.answer;
        
        if (data.sources && data.sources.length > 0) {
            let sourcesHtml = '<p class="mb-2"><strong>Sources:</strong></p><ul class="list-unstyled">';
            data.sources.forEach(source => {
                sourcesHtml += `<li class="mb-1">
                    <a href="/support/article/${source.id}" target="_blank">
                        📄 ${source.title} <small class="text-muted">(${source.category})</small>
                    </a>
                </li>`;
            });
            sourcesHtml += '</ul>';
            document.getElementById('sources').innerHTML = sourcesHtml;
        } else {
            document.getElementById('sources').innerHTML = '';
        }

        document.getElementById('answerSection').style.display = 'block';
    } catch (error) {
        alert('Error getting answer. Please try again.');
    } finally {
        document.getElementById('loading').style.display = 'none';
    }
}

async function submitFeedback(wasHelpful) {
    if (!currentQueryId) return;

    try {
        await fetch(`/support/feedback/${currentQueryId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ was_helpful: wasHelpful })
        });

        alert('Thank you for your feedback!');
    } catch (error) {
        console.error('Feedback error:', error);
    }
}
</script>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transition: box-shadow 0.3s ease-in-out;
}
</style>
@endsection
