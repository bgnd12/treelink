@props(['initialDark' => false])

{{-- Stickman Light/Dark switch. Animation + visuals live in
     resources/js/stickman-toggle.js and resources/css/stickman-toggle.css.
     The editor() Alpine component persists the chosen theme. --}}
<div
    class="tl-stickman"
    x-data="stickmanThemeToggle({ initialDark: @js((bool) $initialDark) })"
    :class="{ 'is-dark': dark, 'is-busy': busy }"
    @treelink-theme-mode-sync.window="setDark($event.detail.dark)"
>
    <div
        class="tl-stickman__button"
        role="switch"
        :aria-checked="dark ? 'true' : 'false'"
        aria-label="Ganti tema terang atau gelap"
        tabindex="0"
        @click="go()"
        @keydown.enter.prevent="go()"
        @keydown.space.prevent="go()"
    >
        <div class="tl-stickman__panel" x-ref="panel"></div>

        <div class="tl-stickman__knob" aria-hidden="true">
            <svg class="tl-stickman__sun" viewBox="0 0 24 24" fill="none" stroke="#ffa31a" stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="4.2" fill="#ffa31a"/>
                <path d="M12 2v2.5M12 19.5V22M2 12h2.5M19.5 12H22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/>
            </svg>
            <svg class="tl-stickman__moon" viewBox="0 0 24 24"><path d="M20.5 14.5A8.5 8.5 0 0 1 9.5 3.5a8.5 8.5 0 1 0 11 11z" fill="#2fb4ff"/></svg>
        </div>

        <canvas class="tl-stickman__canvas" x-ref="stage"></canvas>
    </div>
</div>
