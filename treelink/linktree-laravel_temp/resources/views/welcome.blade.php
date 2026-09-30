@extends('layouts.guest')

@section('title', config('app.name').' — Find Your People, Build Your Future')
@section('description', 'TreeLink helps students discover friends, mentors, and communities that help them grow.')

@section('content')
<div class="landing-page">
    @include('components.navbar')

    {{-- HERO --}}
    <section class="hero-stage relative overflow-hidden pt-28 pb-20 sm:pt-36 sm:pb-28">
        <div class="hero-grid absolute inset-0 -z-10"></div>
        <div class="max-w-7xl min-h-[calc(100svh-7rem)] mx-auto px-5 sm:px-8 grid md:grid-cols-[.92fr_1.08fr] gap-8 xl:gap-16 items-center">
            <div class="animate-fade-up max-w-xl">
                <p class="hero-kicker"><span></span> CONNECT · LEARN · GROW</p>
                <h1 class="hero-title mt-6">Find your people,<br><em>build your future.</em></h1>
                <p class="mt-6 text-lg text-ink-600 max-w-lg leading-relaxed">
                    TreeLink connects students, mentors, and learning communities. Meet new people, discover new opportunities, and grow together.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}" class="hero-primary">Get started <span>↗</span></a>
                    <a href="#features" class="hero-secondary">Explore features <span>↓</span></a>
                </div>
                <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-semibold text-ink-500">
                    <span><b class="hero-check">✓</b> Meet new people</span>
                    <span><b class="hero-check">✓</b> Learn with mentors</span>
                </div>
            </div>

            {{-- Product-led hero artwork --}}
            <div class="hero-art relative min-h-[530px] sm:min-h-[590px] animate-fade-up" style="animation-delay:.15s">
                <div class="hero-orbit hero-orbit-one"></div>
                <div class="hero-orbit hero-orbit-two"></div>
                <div class="hero-note hero-note-top"><span class="hero-dot"></span><div><b>LIVE PREVIEW</b><small>See every connection grow</small></div></div>
                <div class="hero-note hero-note-bottom"><span class="hero-spark">↗</span><div><b>+24% this week</b><small>New people discovered</small></div></div>
                <div class="hero-student-image"><img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=900&q=85" alt="TreeLink community member"></div>
                <div class="hero-note hero-note-students"><span class="hero-spark">✦</span><div><b>12.5K+</b><small>active students</small></div></div>

                <div class="hero-profile-card">
                    <div class="hero-profile-head">
                        <div class="hero-avatar">T</div>
                        <div class="hero-profile-meta"><b>tree.link</b><span>student community</span></div>
                        <span class="hero-menu">•••</span>
                    </div>
                    <div class="hero-profile-intro"><strong>Better<br>together.</strong><span>↗</span></div>
                    <div class="hero-link hero-link-featured"><i class="hero-icon hero-icon-lime">✦</i><span>Join communities</span><b>↗</b></div>
                    <div class="hero-link"><i class="hero-icon hero-icon-dark">◎</i><span>Find a mentor</span><b>↗</b></div>
                    <div class="hero-link"><i class="hero-icon hero-icon-red">⌁</i><span>Explore opportunities</span><b>↗</b></div>
                    <div class="hero-profile-footer"><span>treelink.app/connect</span><span>♡</span></div>
                </div>

                <div class="hero-mini-card hero-mini-share"><span>✦</span><b>Better together</b><small>grow with your people</small></div>
                <div class="hero-mini-card hero-mini-custom"><span class="hero-mini-number">↗</span><b>Join communities</b><small>learn, share, grow</small></div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="landing-features py-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <p class="hero-kicker justify-center"><span></span> WHY TREELINK?</p>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-ink-900 mt-4">Why TreeLink?</h2>
                <p class="mt-4 text-ink-600">You do not have to figure everything out alone. Connect, learn, and grow with people who get you.</p>
            </div>

            <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                        $features = [
                            ['icon' => '✦', 'title' => 'Connect with New Friends', 'desc' => 'Meet people who share your interests and goals.'],
                            ['icon' => '▣', 'title' => 'Access Mentors & Learning', 'desc' => 'Learn from people who have already walked the path.'],
                            ['icon' => '⌁', 'title' => 'Join Communities', 'desc' => 'Discover communities built around your passions.'],
                            ['icon' => '◎', 'title' => 'Achieve Your Goals Together', 'desc' => 'Turn small conversations into meaningful progress.'],
                        ];
                @endphp

                @foreach ($features as $feature)
                    <div class="landing-feature-card p-7 rounded-2xl border hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-2xl">{{ $feature['icon'] }}</div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-600 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how-it-works" class="landing-steps py-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <p class="hero-kicker justify-center"><span></span> SIMPLE BY DESIGN</p>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-ink-900 mt-4">How TreeLink Works</h2>
                <p class="mt-4 text-ink-600">Simple, clear, and designed around your next step.</p>
            </div>

            <div class="mt-16 grid md:grid-cols-3 gap-8">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Create an Account', 'desc' => 'Set up your space in a few simple steps.'],
                        ['num' => '02', 'title' => 'Explore & Discover', 'desc' => 'Find friends, mentors, and communities that match your interests.'],
                        ['num' => '03', 'title' => 'Start Connecting', 'desc' => 'Join in, learn together, and keep growing.'],
                    ];
                @endphp
                @foreach ($steps as $step)
                    <div class="landing-step-card relative p-8 rounded-2xl text-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-soft">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-600">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="landing-cta py-24">
        <div class="max-w-5xl mx-auto px-5 sm:px-8">
            <div class="relative overflow-hidden rounded-3xl px-8 py-16 sm:px-16 text-center shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white">Ready to be part of <span class="text-lime-300">TreeLink?</span></h2>
                <p class="mt-4 text-brand-100 max-w-xl mx-auto">Start your journey today and discover communities that help you grow.</p>
                <a href="{{ route('register') }}" class="mt-8 inline-block px-8 py-4 rounded-full bg-lime-300 text-ink-900 font-bold shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all">
                    Get Started ↗
                </a>
            </div>
        </div>
    </section>

    @include('components.footer')
</div>
@endsection
