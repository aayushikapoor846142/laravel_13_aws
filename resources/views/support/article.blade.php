@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Support</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('support.category', $article->category) }}">{{ $article->category }}</a></li>
                    <li class="breadcrumb-item active">{{ $article->title }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <span class="badge bg-primary mb-3">{{ $article->category }}</span>
                    <h1 class="card-title mb-4">{{ $article->title }}</h1>
                    
                    <div class="article-content">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            👁️ {{ $article->view_count }} views | 
                            👍 {{ $article->helpful_count }} found helpful
                        </small>
                        <div>
                            <button class="btn btn-success btn-sm" onclick="markHelpful({{ $article->id }})">
                                👍 Helpful
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('support.index') }}" class="btn btn-outline-primary">
                    ← Back to Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function markHelpful(articleId) {
    alert('Thank you for your feedback!');
}
</script>
@endsection
