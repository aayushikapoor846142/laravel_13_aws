@extends('layouts.app')

@section('content')
<div class="landing-shell">
    <div class="container py-4 py-lg-5 position-relative">
        <nav class="glass navbar navbar-expand-lg rounded-pill px-3 px-lg-4 py-3 mb-5">
            <div class="container-fluid px-2">
                <a class="navbar-brand d-flex align-items-center gap-3 text-white fw-bold" href="{{ url('/') }}">
                    <span class="brand-mark">A</span>
                    <span class="text-uppercase small tracking-wide text-info">Aegis</span>
                </a>

                @if (Route::has('login'))
                    <div class="ms-auto">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">Sign in</a>
                    </div>
                @endif
            </div>
        </nav>

        <div class="row align-items-center justify-content-center py-4 py-lg-5">
            <div class="col-lg-5">
                <div class="glass rounded-5 p-4 p-lg-5 shadow-lg">
                    <div class="mb-4">
                        <div class="small text-uppercase fw-bold text-violet">Register</div>
                        <h1 class="mt-2 h2 text-white mb-0">Create your account</h1>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label text-light-emphasis">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus class="form-control form-control-lg rounded-4 border-0 text-white @error('name') is-invalid @enderror" style="background: rgba(2, 6, 23, 0.75); color: white;">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-light-emphasis">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-control form-control-lg rounded-4 border-0 text-white @error('email') is-invalid @enderror" style="background: rgba(2, 6, 23, 0.75); color: white;">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light-emphasis">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control form-control-lg rounded-4 border-0 text-white @error('password') is-invalid @enderror" style="background: rgba(2, 6, 23, 0.75); color: white;">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label text-light-emphasis">Confirm password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control form-control-lg rounded-4 border-0 text-white" style="background: rgba(2, 6, 23, 0.75); color: white;">
                        </div>

                        <button type="submit" class="btn btn-primary-soft w-100 rounded-pill py-3 fw-bold">Create account</button>
                    </form>

                    <p class="mt-4 mb-0 text-center text-light-emphasis small">
                        Already have an account?
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="soft-link">Sign in</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
