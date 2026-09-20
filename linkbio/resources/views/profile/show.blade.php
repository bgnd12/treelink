<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->display_name ?: $profileUser->name }} (@{{ $profileUser->username }})</title>
    <meta name="description" content="{{ $profile->bio ?: 'Lihat semua link '.$profileUser->name.' di satu halaman.' }}">

    {{-- Open Graph for nice link previews when shared --}}
    <meta property="og:title" content="{{ $profile->display_name ?: $profileUser->name }}">
    <meta property="og:description" content="{{ $profile->bio }}">
    <meta property="og:image" content="{{ $profile->avatar_url }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $theme = $profile->themeConfig();
        $fontClass = match($profile->font) {
            'serif' => "style='font-family: Playfair Display, serif'",
            'mono' => "style='font-family: JetBrains Mono, monospace'",
            default => '',
        };
        $btnRadius = match($profile->button_style) {
            'pill' => 'rounded-full',
            'square' => 'rounded-md',
            'outline' => 'rounded-xl border-2',
            default => 'rounded-xl',
        };
        $isLight = $theme['text'] !== 'text-white';
        $btnBg = $profile->button_style === 'outline'
            ? ($isLight ? 'bg-transparent border-ink-900' : 'bg-transparent border-white')
            : ($isLight ? 'bg-white shadow-card' : 'bg-white/15 backdrop-blur-md hover:bg-white/25');
    @endphp
</head>
<body class="min-h-screen bg-gradient-to-b {{ $theme['from'] }} {{ $theme['to'] }} {{ $theme['text'] }}" @if($profile->font === 'serif') style="font-family: 'Playfair Display', serif" @elseif($profile->font === 'mono') style="font-family: 'JetBrains Mono', monospace" @endif>

    <div class="min-h-screen flex flex-col items-center px-5 py-14 sm:py-20">
        <div class="w-full max-w-md mx-auto text-center animate-fade-up">

            {{-- Avatar --}}
            <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                 class="w-24 h-24 rounded-full object-cover mx-auto border-4 {{ $isLight ? 'border-black/10' : 'border-white/40' }} shadow-lg">

            <h1 class="mt-4 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
            <p class="text-sm opacity-70">@{{ $profileUser->username }}</p>

            @if ($profile->bio)
                <p class="mt-3 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>
            @endif

            {{-- Social icons --}}
            @php $socials = array_filter($profile->social_links ?? []); @endphp
            @if (count($socials))
                <div class="flex justify-center flex-wrap gap-3 mt-5">
                    @foreach ($socials as $platform => $url)
                        <a href="{{ str_starts_with($platform, 'email') ? 'mailto:'.$url : $url }}" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full {{ $btnBg }} flex items-center justify-center text-sm font-bold hover:scale-110 transition-transform"
                           title="{{ \App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform) }}">
                            {{ strtoupper(substr($platform, 0, 1)) }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Links --}}
            <div class="mt-8 space-y-3.5">
                @forelse ($links as $link)
                    <a href="{{ route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id]) }}"
                       target="_blank" rel="noopener"
                       class="group flex items-center justify-center gap-2 w-full py-3.5 px-5 {{ $btnRadius }} {{ $btnBg }} font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                        {{ $link->title }}
                        <span class="opacity-0 group-hover:opacity-60 transition-opacity">&rarr;</span>
                    </a>
                @empty
                    <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                @endforelse
            </div>

            {{-- Share button --}}
            <div class="mt-10 flex items-center justify-center gap-3">
                <button type="button" onclick="shareProfile()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full {{ $btnBg }} text-xs font-semibold">
                    <span>🔗</span> Bagikan Halaman Ini
                </button>
            </div>

            <p class="mt-10 text-xs opacity-50">
                Dibuat dengan <a href="{{ route('home') }}" class="underline hover:opacity-80">LinkBio</a>
            </p>
        </div>
    </div>

    <script>
        function shareProfile() {
            const url = window.location.href;
            const title = document.title;
            if (navigator.share) {
                navigator.share({ title, url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL profil disalin ke clipboard!');
                });
            }
        }
    </script>
</body>
</html>
