<header class="fixed top-0 inset-x-0 z-50 bg-momo-peach shadow-lg" x-data="{ open: false }">
    <div
        x-data="{ show: !safeStorage.get('disclaimer_dismissed') }"
        x-show="show"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 max-h-20"
        x-transition:leave-end="opacity-0 max-h-0"
        class="w-full bg-milky-aquamarine border-b border-white/20 px-4 py-1.5 overflow-hidden"
        style="display:none;">
        <div class="flex items-center justify-center gap-2">
            <p class="text-xs text-white/90 leading-snug text-center">
                {{ __('nav.disclaimer') }}
                <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
                   class="underline underline-offset-2 font-semibold hover:text-white transition ml-1">
                    {{ __('nav.aszf_label') }}
                </a>
            </p>
            <button
                @click="show = false; safeStorage.set('disclaimer_dismissed', '1'); $dispatch('disclaimer-dismissed')"
                class="shrink-0 text-white/70 hover:text-white border border-white/40 hover:border-white/80 rounded-full p-0.5 transition"
                aria-label="Bezárás">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-6 h-24 flex items-center justify-between lg:grid lg:grid-cols-3">
        <a href="{{ route('home') }}" class="flex items-center">
            <div class="h-20 w-20 rounded-full overflow-hidden p-1 shrink-0">
                <img src="{{ asset('storage/logo.png') }}" alt="{{ config('app.name') }}" class="h-full w-full object-cover scale-100">
            </div>
        </a>

        {{-- Desktop nav -- középen --}}
        <nav class="hidden lg:flex items-center justify-center gap-6">
            <a href="{{ route('home') }}#programs" data-section="programs"
                class="nav-link text-base font-medium text-white/80 hover:text-white transition">{{ __('nav.programs') }}</a>
            <a href="{{ route('home') }}#contact" data-section="contact"
                class="nav-link text-base font-medium text-white/80 hover:text-white transition">{{ __('nav.contact') }}</a>
        </nav>

        {{-- Jobb oldal: nyelvválasztó (desktop) + hamburger (mobil) --}}
        <div class="flex items-center justify-end gap-4">
            <div class="hidden lg:block">
                <x-language-switcher />
            </div>
            <div class="lg:hidden flex items-center gap-3">
                <x-language-switcher :mobile="true" />
            </div>
            <button class="lg:hidden flex flex-col justify-center items-center w-9 h-9 gap-1.5" @click="open = !open"
                aria-label="Menü">
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"
                    :class="open ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"
                    :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-6 h-0.5 bg-white transition-all duration-300"
                    :class="open ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>
        </div>
    </div>

    {{-- Mobil menü --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-momo-peach border-t border-white/20 px-6 py-4 flex flex-col gap-4"
        style="display:none;">
        <a href="{{ route('home') }}#programs" data-section="programs"
            class="nav-link text-base font-medium text-white/80 hover:text-white transition"
            @click="open = false">{{ __('nav.programs') }}</a>
        <a href="{{ route('home') }}#contact" data-section="contact"
            class="nav-link text-base font-medium text-white/80 hover:text-white transition"
            @click="open = false">{{ __('nav.contact') }}</a>
    </div>
</header>
