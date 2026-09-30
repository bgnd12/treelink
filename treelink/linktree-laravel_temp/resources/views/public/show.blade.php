<!DOCTYPE html>
<html lang="id">
@php
    $settings = $profile->settings();
    $theme = $profile->themeConfig();
    $name = (string) ($profile->display_name ?: $profileUser->name);
    $username = '@'.$profileUser->username;

    $background = $profile->backgroundStyle();
    $text = $profile->textColor();
    $accent = $settings['accent_color'] ?: $theme['from_hex'];
    $btn = $profile->buttonStyle();
    $font = \App\Models\Profile::FONTS[$profile->font]['family'] ?? \App\Models\Profile::FONTS['sans']['family'];

    $chip = 'border:1px solid rgba(128,90,213,0.35); background-color:rgba(128,90,213,0.12); color:'.$text.';';
    $line = 'background-color:'.$text.'; opacity:0.5;';
    $glow = 'background-color:'.$accent.';';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['seo_title'] ?: $name.' ('.$username.')' }}</title>
    <meta name="description" content="{{ $settings['seo_description'] ?: ($profile->bio ?: 'Lihat semua link ' . $name . ' di satu halaman.') }}">

    <meta property="og:title" content="{{ $settings['seo_title'] ?: $name.' ('.$username.')' }}">
    <meta property="og:description" content="{{ $settings['seo_description'] ?: $profile->bio }}">
    <meta property="og:image" content="{{ $profile->avatar_url }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans" style="background:{{ $background }}; color:{{ $text }}; font-family:{{ $font }};">

    <div class="relative min-h-screen flex items-center justify-center px-4 py-12 sm:py-16">

        {{-- Decorative soft glows --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full blur-3xl opacity-60" style="{{ $glow }}"></div>
            <div class="absolute -bottom-20 -right-20 h-72 w-72 rounded-full blur-3xl opacity-60" style="{{ $glow }}"></div>
        </div>

        <main class="relative w-full max-w-[460px] px-6 py-10 sm:px-8 text-center rounded-[2rem] bg-white/80 shadow-2xl ring-1 ring-white/70 backdrop-blur-md {{ $settings['animations_enabled'] ? 'animate-fade-up' : '' }}">

            {{-- Layout: Classic --}}
            @if ($settings['layout'] === 'classic')
                <img src="{{ $profile->avatar_url }}" alt="{{ $name }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4" style="border-color:{{ $accent }}">
                <h1 class="mt-3 text-lg font-extrabold tracking-tight">{{ $name }}</h1>
                <p class="text-xs opacity-70 font-medium">{{ $username }}</p>
                @if ($profile->bio)<p class="text-xs opacity-85 mt-2.5 px-2 leading-relaxed">{{ $profile->bio }}</p>@endif

            {{-- Layout: Hero --}}
            @elseif ($settings['layout'] === 'hero')
                <div class="relative pt-2">
                    <span class="absolute left-1/2 -top-3 -translate-x-1/2 w-36 h-36 rounded-full blur-2xl opacity-40" style="{{ $glow }}"></span>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $name }}" class="relative w-24 h-24 rounded-full object-cover mx-auto border-4" style="border-color:{{ $accent }}">
                </div>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight">{{ $name }}</h1>
                <p class="text-xs opacity-70 font-medium">{{ $username }}</p>
                @if ($profile->bio)<p class="text-xs opacity-85 mt-2.5 px-2 leading-relaxed">{{ $profile->bio }}</p>@endif

            {{-- Layout: Banner --}}
            @elseif ($settings['layout'] === 'banner')
                <div class="-mx-6 -mt-10 mb-2 h-24 rounded-b-[2.5rem]" style="background:linear-gradient(135deg, {{ $theme['from_hex'] }}, {{ $theme['to_hex'] }}); opacity:0.9"></div>
                <img src="{{ $profile->avatar_url }}" alt="{{ $name }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/50 -mt-10 relative shadow-lg">
                <h1 class="mt-2 text-lg font-extrabold tracking-tight">{{ $name }}</h1>
                <p class="text-xs opacity-70 font-medium">{{ $username }}</p>
                @if ($profile->bio)<p class="text-xs opacity-85 mt-2 px-2 leading-relaxed">{{ $profile->bio }}</p>@endif

            {{-- Layout: Cutout --}}
            @elseif ($settings['layout'] === 'cutout')
                <div class="relative w-24 h-24 mx-auto pt-2">
                    <span class="absolute inset-0 rounded-full opacity-30" style="{{ $glow }}"></span>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $name }}" class="relative w-24 h-24 rounded-full object-cover border-4 border-white/50">
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-bold shadow" style="background:{{ $text === '#0f172a' ? '#ffffff' : '#0f172a' }}; color:{{ $text === '#0f172a' ? '#0f172a' : '#ffffff' }}">{{ $username }}</span>
                </div>
                <h1 class="mt-5 text-lg font-extrabold tracking-tight">{{ $name }}</h1>
                @if ($profile->bio)<p class="text-xs opacity-85 mt-1 px-2 leading-relaxed">{{ $profile->bio }}</p>@endif

            {{-- Layout: Shape --}}
            @else
                <img src="{{ $profile->avatar_url }}" alt="{{ $name }}" class="w-20 h-20 rounded-[1.75rem] object-cover mx-auto border-4 rotate-3" style="border-color:{{ $accent }}">
                <h1 class="mt-4 text-xl font-extrabold tracking-tight">{{ $name }}</h1>
                <p class="text-xs opacity-70 font-medium">{{ $username }}</p>
                @if ($profile->bio)<p class="text-xs opacity-85 mt-2 px-2 leading-relaxed">{{ $profile->bio }}</p>@endif
            @endif

            {{-- Social icons --}}
            @php $socials = array_filter($profile->social_links ?? []); @endphp
            @if ($settings['show_social'] && count($socials))
                <div class="flex justify-center flex-wrap gap-2 mt-5">
                    @foreach ($socials as $platform => $url)
                        @php
                            $isEmail = str_starts_with($platform, 'email');
                            $href = $isEmail
                                ? (str_starts_with($url, 'mailto:') ? $url : 'mailto:'.$url)
                                : (preg_match('#^https?://#i', $url) ? $url : 'https://'.$url);
                        @endphp
                        <a href="{{ $href }}" target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold hover:scale-110 transition-transform"
                           style="{{ $chip }}"
                           title="{{ \App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform) }}">
                            {!! \App\Models\Link::iconSvg($platform) !!}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Links --}}
            <div class="mt-8">
                <div class="flex items-center gap-3 text-[9px] font-bold uppercase tracking-[0.2em] opacity-60">
                    <span class="h-px flex-1" style="{{ $line }}"></span>
                    <span>My Links</span>
                    <span class="h-px flex-1" style="{{ $line }}"></span>
                </div>

                <div class="space-y-2.5 mt-4">
                    @forelse ($links as $link)
                        <a href="{{ route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id]) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2.5 w-full px-4 py-3 text-[13px] font-semibold transition-all duration-200 hover:-translate-y-0.5"
                           style="{{ $btn }}; {{ (string) $settings['featured_link_id'] === (string) $link->id ? 'outline:2px solid #facc15; transform:scale(1.03);' : '' }} {{ $settings['animations_enabled'] ? 'animation:fade-up .5s both;' : '' }}">
                            @if ((string) $settings['featured_link_id'] === (string) $link->id)<span>⭐</span>@endif
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-extrabold text-white shrink-0" style="background:{{ \App\Models\Link::iconColor($link->icon) }}">{!! \App\Models\Link::iconSvg($link->icon) !!}</span>
                            <span class="truncate flex-1 text-left">{{ $link->title }}</span>
                        </a>
                    @empty
                        <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                    @endforelse
                </div>
            </div>

            {{-- Share button --}}
            @if ($settings['show_share'])
                <div class="mt-8">
                    <button type="button" onclick="shareProfile()"
                            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full text-[11px] font-semibold backdrop-blur transition-colors"
                            style="{{ $chip }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                        </svg>
                        Bagikan Halaman Ini
                    </button>
                </div>
            @endif

            <p class="mt-10 text-xs opacity-50">
                Dibuat dengan <a href="{{ route('home') }}" class="underline hover:opacity-80">TreeLink</a>
            </p>
        </main>
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