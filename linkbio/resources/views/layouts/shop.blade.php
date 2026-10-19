<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop') &mdash; TreeLink</title>
    @hasSection('meta')
        @yield('meta')
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen bg-[#f6f7f4] text-[#14213d] font-sans antialiased">
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-4xl items-center justify-between px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold tracking-tight text-[#14213d]">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#c8ff4d]">
                    <svg class="h-5 w-5 text-[#4d7c0f]" viewBox="0 0 24 24" fill="currentColor"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                </span>
                TreeLink
            </a>
        </div>
    </header>

    <main class="mx-auto w-full max-w-4xl px-4 py-6 sm:py-9">
        @if (session('status'))
            <div class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
    @include('components.page-loading-overlay')
</body>
</html>
