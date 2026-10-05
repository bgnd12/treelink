<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->seo_title ?: ($profile->display_name ?: $profileUser->name).' (@'.$profileUser->username.')' }}</title>
    <meta name="description" content="{{ $profile->seo_description ?: ($profile->bio ?: 'Lihat semua link '.$profileUser->name.' di satu halaman.') }}">

    {{-- Open Graph for nice link previews when shared --}}
    <meta property="og:title" content="{{ $profile->seo_title ?: ($profile->display_name ?: $profileUser->name) }}">
    <meta property="og:description" content="{{ $profile->seo_description ?: $profile->bio }}">
    <meta property="og:image" content="{{ $profile->avatar_url }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $theme = $profile->themeConfig();
        $isLight = $theme['text'] !== 'text-white';
        $fontFamily = \App\Models\Profile::FONT_FAMILIES[$profile->font] ?? \App\Models\Profile::FONT_FAMILIES['sans'];

        // Background ------------------------------------------------------------------
        $bgType = $profile->background_type;
        $bgClass = '';
        $bgStyle = '';
        $bgOverlay = false;
        if ($bgType === 'gradient') {
            $bgClass = 'bg-gradient-to-b '.$theme['from'].' '.$theme['to'];
        } elseif ($bgType === 'solid') {
            $bgStyle = 'background-color:'.($profile->background_value ?: $theme['pattern_bg'] ?: '#7c4dff').';';
        } elseif ($bgType === 'pattern') {
            $bgClass = 'pattern-'.($profile->background_value ?: 'dots');
            $bgStyle = 'background-color:'.($theme['pattern_bg'] ?: '#3f1c99').';';
        } elseif ($bgType === 'image' && $profile->background_image_url) {
            $bgStyle = "background-image:url('".$profile->background_image_url."');background-size:cover;background-position:center;";
            $bgOverlay = true;
        }

        // Buttons ----------------------------------------------------------------------
        $btnRadius = $profile->buttonRadiusPx();
        $btnBorder = $profile->button_style === 'outline' ? max((int) $profile->button_border_width, 2) : (int) $profile->button_border_width;
        $btnColor = $profile->button_color ?: $theme['accent'];
        $btnText = $profile->buttonTextColor();
        $btnStyle = $profile->button_style === 'outline'
            ? 'background:transparent;color:'.($isLight ? '#111827' : $theme['accent']).';'
            : "background:{$btnColor};color:{$btnText};";
        $btnStyle .= "border-radius:{$btnRadius}px;border:{$btnBorder}px solid ".($isLight ? 'rgba(0,0,0,.35)' : 'rgba(255,255,255,.45)').';';
        $btnStyle .= $profile->button_shadow ? 'box-shadow:0 10px 28px -10px rgba(0,0,0,.45);' : '';

        $socials = array_filter($profile->social_links ?? []);
        $layout = $profile->header_layout ?? 'classic';
        $animate = (bool) $profile->animation_enabled;
        $hoverClass = 'transition-all duration-200 hover:-translate-y-0.5';
        $shadowClass = $profile->button_shadow ? ' hover:shadow-lg' : '';

        // Card ----------------------------------------------------------------------
        // The whole profile can live inside one card that floats over the page
        // background. When the card is on, the readable text colour is derived
        // from the card itself so a light card on a dark page still reads well.
        $themeIsLight = $theme['text'] !== 'text-white';
        $cardOn = (bool) $profile->card_enabled;
        $isLight = $cardOn ? $profile->cardTextColor($themeIsLight) === '#111827' : $themeIsLight;

        $cardStyle = 'background:'.$profile->cardBackgroundCss($themeIsLight).';'
            .'color:'.$profile->cardTextColor($themeIsLight).';'
            .'border:'.$profile->cardBorderCss($themeIsLight).';'
            .'box-shadow:'.$profile->cardShadowCss().';'
            .'border-radius:'.$profile->cardRadiusPx().'px;';

        if ($profile->cardStyle() === 'glass') {
            $cardStyle .= 'backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);';
        }
    @endphp
</head>
<body class="min-h-screen {{ $theme['text'] }} {{ $bgClass }}" style="{{ $bgStyle }} font-family:{{ $fontFamily }}">

    @if ($bgOverlay)
        <div class="fixed inset-0 bg-black/35"></div>
    @endif

    <div class="relative min-h-screen flex flex-col items-center px-4 py-8 sm:py-14 {{ $animate ? 'animate-fade-up' : '' }}">
        <div class="w-full max-w-5xl mx-auto text-center">
            @if ($cardOn)
                <div class="overflow-hidden px-6 pt-9 pb-8" style="{{ $cardStyle }}">
            @else
                <div class="-mx-4 -my-8 sm:-my-14 px-4 py-8 sm:py-14">
            @endif

            {{-- ============ HEADER LAYOUT ============ --}}
            @if ($layout === 'banner')
                <div class="{{ $cardOn ? '-mx-6 -mt-9' : '-mt-8' }}">
                    <div class="h-28 {{ $cardOn ? '' : 'rounded-b-3xl' }} {{ $isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur' }}"></div>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                         class="w-24 h-24 rounded-full object-cover mx-auto -mt-12 border-4 {{ $isLight ? 'border-gray-200 shadow-xl' : 'border-white/80 shadow-xl' }}">
                    <h1 class="mt-3 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                    <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                    @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
                </div>
            @elseif ($layout === 'cutout')
                <div class="relative mx-auto mt-2 w-24 h-24">
                    <div class="absolute inset-2 rotate-45 rounded-3xl {{ $isLight ? 'bg-white/80' : 'bg-white/20' }}"></div>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                         class="absolute inset-0 w-full h-full rounded-full object-cover border-4 {{ $isLight ? 'border-white' : 'border-white/80' }}">
                </div>
                <h1 class="mt-5 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @elseif ($layout === 'shape')
                <div class="mx-auto mt-2 w-24 h-24 rounded-[36%] p-1.5 {{ $isLight ? 'bg-white shadow-xl' : 'bg-white/25' }}">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}" class="w-full h-full rounded-[34%] object-cover">
                </div>
                <h1 class="mt-5 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @elseif ($layout === 'hero')
                <div class="mx-auto mt-2 w-28 h-28 rounded-[1.75rem] p-1 {{ $isLight ? 'bg-white shadow-xl' : 'bg-white/90' }}">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}" class="w-full h-full rounded-[1.5rem] object-cover">
                </div>
                <h1 class="mt-5 text-2xl font-extrabold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70 mt-0.5">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @else
                {{-- classic --}}
                <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto border-4 {{ $isLight ? 'border-black/10' : 'border-white/40' }} shadow-lg">
                <h1 class="mt-4 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-3 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @endif

            {{-- Social icons --}}
            @if (count($socials))
                <div class="flex justify-center flex-wrap gap-3 mt-5">
                    @foreach ($socials as $platform => $url)
                        <a href="{{ str_starts_with($platform, 'email') ? 'mailto:'.$url : $url }}" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full {{ $hoverClass }} flex items-center justify-center text-sm font-bold {{ $hoverClass }}"
                           style="{{ $isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff' }}"
                           title="{{ \App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform) }}">
                            {!! \App\Support\Brands::markup($platform === 'website' ? 'globe' : ($platform === 'email' ? 'mail' : $platform), 'w-4 h-4') !!}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid gap-8 md:grid-cols-2 md:items-start md:text-left">
            {{-- Links --}}
            <section class="space-y-3.5">
                <h2 class="text-xs font-bold uppercase tracking-[0.2em] opacity-70">Links</h2>
                @forelse ($links as $link)
                    <a href="{{ route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id]) }}"
                       target="_blank" rel="noopener"
                       class="group flex items-center justify-center gap-2.5 w-full py-3.5 px-5 font-semibold text-sm {{ $hoverClass }}{{ $shadowClass }}"
                       style="{{ $btnStyle }}">
                        @if ($link->is_featured)<span>⭐</span>@endif
                        {!! \App\Support\Brands::render($link->url, $link->icon, 'w-5 h-5 flex-shrink-0') !!}
                        {{ $link->title }}
                        <span class="opacity-0 group-hover:opacity-60 transition-opacity">&rarr;</span>
                    </a>
                @empty
                    <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                @endforelse
            </section>

            {{-- Shop / Products --}}
            @if ($products->count())
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-[0.2em] opacity-70">Shop</h2>
                        <span class="text-xs opacity-60">{{ $products->count() }} produk</span>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($products as $product)
                            <a href="{{ $product->url }}" target="_blank" rel="noopener"
                               class="flex min-w-0 flex-col overflow-hidden text-left font-semibold {{ $hoverClass }}{{ $shadowClass }}"
                               style="{{ $btnStyle }}">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                         class="aspect-[1.6] w-full object-cover">
                                @else
                                    <span class="flex aspect-[1.6] w-full items-center justify-center text-xl"
                                          style="{{ $isLight ? 'background:rgba(0,0,0,.10)' : 'background:rgba(0,0,0,.22)' }}">🛍️</span>
                                @endif
                                <span class="block w-full p-3">
                                    @if($product->category)<span class="block text-[10px] font-bold uppercase tracking-[0.15em] opacity-60">{{ $product->category }}</span>@endif
                                    <span class="mt-1 block truncate text-sm">{{ $product->name }}</span>
                                    @if ($product->price_label)
                                        <span class="block text-xs opacity-75">{{ $product->price_label }}</span>
                                    @endif
                                    @if($product->stock !== null)
                                        <span class="mt-1 block text-[11px] opacity-60">{{ $product->stock > 0 ? 'Stok '.$product->stock : 'Stok habis' }}</span>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
            </div>

            @if ($profile->is_linkid_active)
                @php
                    $linkIdTypes = collect($profile->linkid_types ?? [])->filter(fn ($value) => is_string($value) && trim($value) !== '')->values()->all();
                @endphp
                <div id="linkid" class="mt-8 w-full" x-data="{ openCollabModal: false }">
                    <div class="rounded-2xl border border-white/30 bg-white/10 backdrop-blur-sm p-4 text-left">
                        <p class="text-[11px] font-bold uppercase tracking-[0.24em] opacity-80">LinkID</p>
                        <h2 class="mt-2 text-lg font-extrabold">Collaborate with me</h2>
                        @if ($profile->linkid_description)
                            <p class="mt-2 text-sm opacity-80">{{ $profile->linkid_description }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($linkIdTypes as $type)
                                <span class="px-2.5 py-1 rounded-full bg-white/15 text-xs font-semibold">{{ $type }}</span>
                            @endforeach
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button type="button" @click="openCollabModal = true" class="flex-1 py-2.5 rounded-xl bg-white text-ink-900 font-bold text-sm shadow-sm">Ajak Kolaborasi</button>
                        </div>
                    </div>

                    <div x-show="openCollabModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                        <div @click.outside="openCollabModal = false" class="bg-white text-ink-900 rounded-2xl w-full max-w-md p-6 text-left shadow-2xl relative">
                            <button @click="openCollabModal = false" class="absolute top-4 right-4 text-ink-400 hover:text-ink-600 text-xl font-bold">&times;</button>
                            <h3 class="font-bold text-xl mb-4">Ajak Kolaborasi</h3>
                            <form method="POST" action="{{ route('public.linkid.request', $profileUser->username) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="mb-2 block text-sm font-semibold">Nama</label>
                                    <input name="requester_name" required maxlength="120" value="{{ old('requester_name', auth()->user()?->name) }}" class="mb-4 w-full rounded-xl border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Nama kamu">
                                    <label class="mb-2 block text-sm font-semibold">Email</label>
                                    <input name="requester_email" type="email" required maxlength="255" value="{{ old('requester_email', auth()->user()?->email) }}" class="w-full rounded-xl border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500" placeholder="nama@email.com">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold mb-2">Jenis Kolaborasi</label>
                                    <select name="type" required class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                        <option value="general">Umum</option>
                                        @foreach ($linkIdTypes as $type)
                                            <option value="{{ strtolower(str_replace(' ', '-', $type)) }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold mb-2">Pesan</label>
                                    <textarea name="message" required rows="4" class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500 text-sm" placeholder="Ceritakan detail kolaborasi..."></textarea>
                                </div>
                                <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl transition">
                                    Kirim Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Share button --}}
            <div class="mt-10 flex items-center justify-center gap-3">
                <button type="button" onclick="shareProfile()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full {{ $hoverClass }}"
                        style="{{ $btnStyle }}">
                    <span>🔗</span> Bagikan Halaman Ini
                </button>
            </div>

            <p class="mt-10 text-xs opacity-50">
                Dibuat dengan <a href="{{ route('home') }}" class="underline hover:opacity-80">TreeLink</a>
            </p>
            </div>
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