@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Support</a></li>
                    <li class="breadcrumb-item active">{{ $category }}</li>
                </ol>
            </nav>

            <h2 class="mb-4">{{ $category }}</h2>

            <div class="list-group">
                @forelse($articles as $article)
                <a href="{{ route('support.article', $article->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $article->title }}</h5>
                        <small class="text-muted">{{ $article->view_count }} views</small>
                    </div>
                    <p class="mb-1">{{ Str::limit($article->content, 150) }}</p>
                </a>
                @empty
                <div class="alert alert-info">No articles found in this category.</div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
