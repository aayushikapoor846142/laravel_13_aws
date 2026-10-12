<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                body {
                    font-family: "Segoe UI", sans-serif;
                    background: linear-gradient(135deg, #020817 0%, #0f172a 45%, #111827 100%);
                }
            </style>
        @endif

        <style>
            body {
                background: linear-gradient(135deg, #020817 0%, #0f172a 38%, #111827 100%);
                color: #e5eefb;
            }

            .landing-shell {
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .landing-shell::before,
            .landing-shell::after {
                content: "";
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.4;
                pointer-events: none;
            }

            .landing-shell::before {
                width: 420px;
                height: 420px;
                background: rgba(34, 211, 238, 0.22);
                top: -100px;
                left: -80px;
            }

            .landing-shell::after {
                width: 480px;
                height: 480px;
                background: rgba(168, 85, 247, 0.18);
                right: -90px;
                bottom: -120px;
            }

            .brand-mark {
                width: 42px;
                height: 42px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 14px;
                background: linear-gradient(135deg, #67e8f9 0%, #3b82f6 45%, #8b5cf6 100%);
                color: #0f172a;
                font-weight: 800;
            }

            .glass {
                background: rgba(15, 23, 42, 0.72);
                border: 1px solid rgba(148, 163, 184, 0.18);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }

            .hero-panel {
                border: 1px solid rgba(148, 163, 184, 0.2);
                background: rgba(15, 23, 42, 0.8);
                border-radius: 24px;
                box-shadow: 0 30px 80px rgba(2, 6, 23, 0.55);
            }

            .metric-box {
                border-radius: 18px;
                border: 1px solid rgba(148, 163, 184, 0.18);
                background: rgba(15, 23, 42, 0.8);
            }

            .feature-card {
                border-radius: 22px;
                border: 1px solid rgba(148, 163, 184, 0.18);
                background: rgba(15, 23, 42, 0.56);
                height: 100%;
            }

            .btn-primary-soft {
                background: linear-gradient(135deg, #67e8f9 0%, #3b82f6 50%, #8b5cf6 100%);
                border: 0;
                color: #0f172a;
                font-weight: 700;
                box-shadow: 0 12px 30px rgba(59, 130, 246, 0.35);
            }

            .btn-primary-soft:hover {
                color: #0f172a;
                filter: brightness(1.04);
            }

            .soft-link {
                color: #a5f3fc;
                text-decoration: none;
            }

            .soft-link:hover {
                color: #dbeafe;
            }
        </style>
    </head>
    <body>
        <div class="landing-shell">
            <div class="container py-4 py-lg-5 position-relative">
                <nav class="glass navbar navbar-expand-lg rounded-pill px-3 px-lg-4 py-3 mb-5">
                    <div class="container-fluid px-2">
                        <a class="navbar-brand d-flex align-items-center gap-3 text-white fw-bold" href="{{ url('/') }}">
                            <span class="brand-mark">A</span>
                            <span class="text-uppercase small tracking-wide text-info">Aegis</span>
                        </a>

                        <div class="d-none d-lg-flex ms-auto gap-4 align-items-center">
                            <a href="#features" class="text-decoration-none text-light-emphasis">Features</a>
                            <a href="#solutions" class="text-decoration-none text-light-emphasis">Solutions</a>
                            <a href="#pricing" class="text-decoration-none text-light-emphasis">Pricing</a>
                        </div>

                        <div class="ms-auto ms-lg-0 d-flex align-items-center gap-2">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-light-emphasis px-2">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-primary-soft btn-sm rounded-pill px-3">Get started</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </nav>

                <main>
                    <section class="row align-items-center py-4 py-lg-5">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <span class="badge rounded-pill border border-info-subtle bg-info-subtle text-info px-3 py-2 small fw-semibold">
                                AI-powered business growth
                            </span>

                            <h1 class="display-4 fw-bolder mt-4 text-white" style="line-height: 1.08; letter-spacing: -0.04em; max-width: 560px;">
                                Turn ideas into momentum.
                            </h1>

                            <p class="mt-4 text-lg text-light-emphasis" style="max-width: 560px; line-height: 1.8;">
                                Build faster, think clearer, and deliver better experiences with a modern AI platform for teams that want growth without bottlenecks.
                            </p>

                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('register') ?? '#' }}" class="btn btn-primary-soft rounded-pill px-4 py-3">Start free</a>
                                <a href="#features" class="btn btn-outline-light rounded-pill px-4 py-3">View features</a>
                            </div>

                            <div class="row mt-5 g-3" style="max-width: 540px;">
                                <div class="col-4">
                                    <div class="metric-box p-3">
                                        <div class="fs-3 fw-bold text-white">3.2x</div>
                                        <div class="small text-light-emphasis">Faster launch</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric-box p-3">
                                        <div class="fs-3 fw-bold text-white">94%</div>
                                        <div class="small text-light-emphasis">Adoption</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metric-box p-3">
                                        <div class="fs-3 fw-bold text-white">24/7</div>
                                        <div class="small text-light-emphasis">AI support</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="hero-panel p-4 p-lg-4 position-relative">
                                <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-3">
                                    <div class="d-flex gap-2">
                                        <span class="rounded-circle bg-danger" style="width: 12px; height: 12px; display: inline-block;"></span>
                                        <span class="rounded-circle bg-warning" style="width: 12px; height: 12px; display: inline-block;"></span>
                                        <span class="rounded-circle bg-success" style="width: 12px; height: 12px; display: inline-block;"></span>
                                    </div>
                                    <span class="badge rounded-pill text-info border border-info-subtle bg-info-subtle small fw-semibold">Live overview</span>
                                </div>

                                <div class="bg-gradient rounded-4 p-3 mb-3" style="background: linear-gradient(135deg, rgba(34,211,238,.12), rgba(59,130,246,.12)); border: 1px solid rgba(103, 232, 249, 0.18);">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-light-emphasis">Revenue pipeline</span>
                                        <span class="badge rounded-pill bg-success-subtle text-success">+18.4%</span>
                                    </div>
                                    <div class="d-flex align-items-end gap-2 h-24">
                                        <span class="d-inline-block flex-fill rounded-top bg-info" style="height: 35%;"></span>
                                        <span class="d-inline-block flex-fill rounded-top bg-info" style="height: 62%;"></span>
                                        <span class="d-inline-block flex-fill rounded-top bg-primary" style="height: 52%;"></span>
                                        <span class="d-inline-block flex-fill rounded-top bg-violet" style="height: 76%;"></span>
                                        <span class="d-inline-block flex-fill rounded-top bg-cyan" style="height: 100%;"></span>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="metric-box p-3">
                                            <div class="text-light-emphasis small">Automations</div>
                                            <div class="fs-3 fw-bold text-white mt-2">128</div>
                                            <div class="small text-success">+32 this week</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="metric-box p-3">
                                            <div class="text-light-emphasis small">Response time</div>
                                            <div class="fs-3 fw-bold text-white mt-2">1.8m</div>
                                            <div class="small text-info">Average resolve</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-box p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-light-emphasis">AI assistant</div>
                                            <div class="mt-1 text-white fw-semibold">“Summarize customer intent”</div>
                                        </div>
                                        <span class="badge rounded-pill bg-violet-subtle text-violet">Ready</span>
                                    </div>
                                    <div class="mt-3 progress" style="height: 10px; background: rgba(148, 163, 184, 0.18);">
                                        <div class="progress-bar" role="progressbar" style="width: 82%; background: linear-gradient(90deg, #67e8f9, #3b82f6, #8b5cf6);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="features" class="py-5 py-lg-6">
                        <div class="mb-4">
                            <div class="small text-uppercase fw-bold text-info">Why teams choose us</div>
                            <h2 class="mt-3 display-6 fw-bold text-white">Everything you need to move faster.</h2>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="feature-card p-4 p-lg-5">
                                    <div class="d-flex align-items-center justify-content-center mb-4 rounded-4" style="width: 54px; height: 54px; background: rgba(103, 232, 249, 0.1); color: #67e8f9; font-size: 1.5rem;">⚡</div>
                                    <h3 class="h4 text-white">Instant automation</h3>
                                    <p class="text-light-emphasis mt-3 mb-0">Create workflows, trigger actions, and turn repetitive tasks into seamless systems.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="feature-card p-4 p-lg-5">
                                    <div class="d-flex align-items-center justify-content-center mb-4 rounded-4" style="width: 54px; height: 54px; background: rgba(168, 85, 247, 0.12); color: #d8b4fe; font-size: 1.5rem;">🧠</div>
                                    <h3 class="h4 text-white">Smarter decisions</h3>
                                    <p class="text-light-emphasis mt-3 mb-0">Analyze trends, surface signals, and make better choices with data-backed AI insight.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="feature-card p-4 p-lg-5">
                                    <div class="d-flex align-items-center justify-content-center mb-4 rounded-4" style="width: 54px; height: 54px; background: rgba(34, 197, 94, 0.12); color: #86efac; font-size: 1.5rem;">📈</div>
                                    <h3 class="h4 text-white">Real growth</h3>
                                    <p class="text-light-emphasis mt-3 mb-0">Turn every customer interaction into a path toward more revenue and stronger retention.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="solutions" class="py-5 py-lg-6">
                        <div class="row g-4 align-items-center glass rounded-5 p-4 p-lg-5">
                            <div class="col-lg-4">
                                <div class="small text-uppercase fw-bold text-info">Built for action</div>
                                <h2 class="mt-3 h1 text-white">A platform your whole team can use.</h2>
                                <p class="text-light-emphasis mt-3 mb-0">From support to product and marketing, your teams get clarity, speed, and smarter collaboration in one place.</p>
                            </div>

                            <div class="col-lg-8">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="metric-box p-4 h-100">
                                            <div class="text-light-emphasis small">Customer support</div>
                                            <div class="fs-2 fw-bold text-white mt-2">24/7</div>
                                            <div class="small text-light-emphasis mt-2">AI-driven help and smarter routing.</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="metric-box p-4 h-100">
                                            <div class="text-light-emphasis small">Productivity</div>
                                            <div class="fs-2 fw-bold text-white mt-2">8 hrs</div>
                                            <div class="small text-light-emphasis mt-2">Saved each week per team member.</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="metric-box p-4 h-100">
                                            <div class="text-light-emphasis small">Analytics</div>
                                            <div class="fs-2 fw-bold text-white mt-2">360°</div>
                                            <div class="small text-light-emphasis mt-2">Full visibility across performance.</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="metric-box p-4 h-100">
                                            <div class="text-light-emphasis small">Workflow</div>
                                            <div class="fs-2 fw-bold text-white mt-2">1 click</div>
                                            <div class="small text-light-emphasis mt-2">Trigger actions from real-time insight.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="pricing" class="py-5 py-lg-6 text-center">
                        <div class="small text-uppercase fw-bold text-info">Simple pricing</div>
                        <h2 class="mt-3 display-6 fw-bold text-white">Start small. Scale fast.</h2>

                        <div class="mx-auto mt-4 glass rounded-5 p-4 p-lg-5" style="max-width: 420px;">
                            <div class="small text-uppercase text-light-emphasis">Starter</div>
                            <div class="mt-3">
                                <span class="display-5 fw-bold text-white">$29</span>
                                <span class="text-light-emphasis">/ month</span>
                            </div>

                            <ul class="list-unstyled mt-4 text-start text-light-emphasis">
                                <li class="mb-2">• Unlimited projects</li>
                                <li class="mb-2">• AI-powered insights</li>
                                <li class="mb-2">• Customer reports</li>
                                <li>• Priority support</li>
                            </ul>

                            <a href="{{ route('register') ?? '#' }}" class="btn btn-primary-soft rounded-pill px-4 py-3 mt-3">Start free</a>
                        </div>
                    </section>
                </main>

                <footer class="border-top border-secondary mt-5 pt-4 pb-3 text-light-emphasis">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div>© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Built for modern teams.</div>
                        <div class="d-flex gap-3">
                            <a href="#features" class="soft-link">Features</a>
                            <a href="#solutions" class="soft-link">Solutions</a>
                            <a href="#pricing" class="soft-link">Pricing</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
