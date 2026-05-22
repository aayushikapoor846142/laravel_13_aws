@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Support Analytics</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ $analytics['total_articles'] }}</h3>
                    <p class="text-muted mb-0">Total Articles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ $analytics['total_queries'] }}</h3>
                    <p class="text-muted mb-0">Total Queries</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ number_format($analytics['helpful_rate'], 1) }}%</h3>
                    <p class="text-muted mb-0">Helpful Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Articles</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($analytics['top_articles'] as $article)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ $article->title }}</span>
                                <span class="badge bg-primary">{{ $article->view_count }} views</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Queries</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($analytics['recent_queries'] as $query)
                        <div class="list-group-item">
                            <small class="text-muted">{{ $query->created_at->diffForHumans() }}</small>
                            <p class="mb-0">{{ Str::limit($query->question, 80) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
