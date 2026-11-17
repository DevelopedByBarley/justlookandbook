@php
    $metaDesc  = $program['description']
        ? Str::limit(strip_tags($program['description']), 155)
        : null;
    $metaImage = !empty($program['gallery'])
        ? url($program['gallery'][0])
        : null;
@endphp

<x-layout
    title="{{ $program['name'] }} — {{ config('app.name') }}"
    :description="$metaDesc"
    :ogImage="$metaImage"
    :canonical="route('program.show', $program['slug'])"
>

    {{-- ════════════════════════════════════════
         HERO
    ════════════════════════════════════════ --}}
    <section class="relative overflow-hidden py-32 px-6"
             style="background: linear-gradient(150deg, #037F8C 0%, #79DCF2 100%);">

        {{-- Pálma – bal --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 left-0 lg:left-[15vw] h-72 pointer-events-none select-none"
             style="opacity:0.18; filter:brightness(0) invert(1); transform:scaleX(-1);">

        {{-- Pálma – jobb --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 right-0 lg:right-[15vw] h-60 pointer-events-none select-none"
             style="opacity:0.15; filter:brightness(0) invert(1);">

        <div class="relative max-w-3xl mx-auto text-center">

            {{-- Vissza gomb --}}
            <div class="flex justify-center mb-6">
                <a href="{{ route('home') }}#programs"
                   class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-medium transition group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('show.back') }}
                </a>
            </div>

            {{-- Topic badge --}}
            <span class="inline-block bg-white/20 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase backdrop-blur-sm">
                🌺 {{ $program['topic'] }}
            </span>

            <h1 class="text-5xl sm:text-6xl tracking-tight text-white mb-5"
                style="font-family:'Pacifico',cursive; line-height:1.35;">
                {{ $program['name'] }}
            </h1>
            <p class="text-lg text-white/75 leading-relaxed max-w-xl mx-auto">
                {{ $program['tagline'] }}
            </p>

            {{-- Quick stats --}}
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <div class="flex items-center gap-2 bg-white/15 backdrop-blur-sm px-5 py-2.5 rounded-full text-white text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $program['price'] }} € {{ __('show.per_person') }}
                </div>
                <div class="flex items-center gap-2 bg-white/15 backdrop-blur-sm px-5 py-2.5 rounded-full text-white text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $program['duration'] }}
                </div>
            </div>
        </div>

        {{-- Hullám → galéria (animált) --}}
        <div class="absolute bottom-0 left-0 w-full overflow-hidden pointer-events-none" style="height:70px;">
            <svg viewBox="0 0 2880 70" preserveAspectRatio="none"
                 class="wave-animate"
                 style="display:block; height:70px; width:200%;">
                <path fill="#f9fafb" d="M0,35 C240,70 480,0 720,35 C960,70 1200,0 1440,35 C1680,70 1920,0 2160,35 C2400,70 2640,0 2880,35 L2880,70 L0,70 Z"/>
            </svg>
        </div>
    </section>


    {{-- ════════════════════════════════════════
         GALÉRIA
    ════════════════════════════════════════ --}}
    <section class="relative bg-gray-50 pt-10 pb-0 px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto pb-16">

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2"
                    style="font-family:'Pacifico',cursive;">{{ __('show.gallery_title') }}</h2>
                <p class="text-amber-900/60">{{ __('show.gallery_subtitle') }}</p>
            </div>

            {{-- Galéria Alpine lightbox --}}
            <div x-data="{
                    open: false,
                    current: 0,
                    images: @js($program['gallery']),
                    prev() { this.current = (this.current - 1 + this.images.length) % this.images.length },
                    next() { this.current = (this.current + 1) % this.images.length },
                    show(i) { this.current = i; this.open = true }
                 }"
                 @keydown.escape.window="open = false"
                 @keydown.arrow-left.window="if(open) prev()"
                 @keydown.arrow-right.window="if(open) next()">

                {{-- Rács --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 auto-rows-[200px]">

                    {{-- Nagy kiemelt kép --}}
                    <div class="col-span-2 row-span-2 rounded-2xl overflow-hidden group cursor-zoom-in bg-gray-200 animate-pulse"
                         @click="show(0)">
                        <img src="{{ $program['gallery'][0] }}" alt="{{ __('show.gallery_featured') }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-0"
                             onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('animate-pulse', 'bg-gray-200')">
                    </div>

                    @foreach(array_slice($program['gallery'], 1) as $i => $img)
                        <div class="rounded-2xl overflow-hidden group cursor-zoom-in bg-gray-200 animate-pulse"
                             @click="show({{ $i + 1 }})">
                            <img src="{{ $img }}" alt="{{ __('show.gallery_thumb') }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-0"
                                 onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('animate-pulse', 'bg-gray-200')">
                        </div>
                    @endforeach

                </div>

                {{-- Lightbox modál --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-100 flex items-center justify-center"
                     style="background:rgba(0,0,0,0.88); display:none;"
                     @click.self="open = false">

                    {{-- Bezárás --}}
                    <button @click="open = false"
                            class="absolute top-5 right-5 text-white/70 hover:text-white transition"
                            aria-label="Bezárás">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Bal nyíl --}}
                    <button @click="prev()"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-2 rounded-full hover:bg-white/10"
                            aria-label="Előző">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    {{-- Kép --}}
                    <div class="max-w-4xl max-h-[85vh] w-full px-16 flex items-center justify-center">
                        <img :src="images[current]" :alt="'Kép ' + (current + 1)"
                             class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl">
                    </div>

                    {{-- Jobb nyíl --}}
                    <button @click="next()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-2 rounded-full hover:bg-white/10"
                            aria-label="Következő">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    {{-- Számláló --}}
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white/60 text-sm font-medium">
                        <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                    </div>

                    {{-- Dot navigáció --}}
                    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-2">
                        <template x-for="(img, i) in images" :key="i">
                            <button @click="current = i"
                                    :style="i === current ? 'background:#F27979; transform:scale(1.25)' : 'background:rgba(242,121,121,0.4)'"
                                    class="w-2 h-2 rounded-full transition-all duration-200">
                            </button>
                        </template>
                    </div>

                </div>
            </div>
        </div>

        {{-- Hullám → leírás --}}
        <svg viewBox="0 0 1440 70" preserveAspectRatio="none"
             class="absolute bottom-0 left-0 w-full pointer-events-none"
             style="display:block; height:70px;">
            <path fill="#fff" d="M0,35 C240,0 480,70 720,35 C960,0 1200,70 1440,35 L1440,70 L0,70 Z"/>
        </svg>
    </section>


    {{-- ════════════════════════════════════════
         LEÍRÁS + VIDEÓ
    ════════════════════════════════════════ --}}
    <section class="relative bg-white pt-10 pb-0 px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto pb-20">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                {{-- Bővebb leírás --}}
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6"
                        style="font-family:'Pacifico',cursive;">{{ __('show.about_title') }}</h2>

                    <div id="desc-wrapper" class="relative overflow-hidden transition-all duration-500"
                         style="max-height: 320px;">
                        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed
                                    prose-headings:text-gray-900 prose-headings:font-bold
                                    prose-strong:text-gray-800
                                    prose-ul:pl-5 prose-li:my-1">
                            {!! $program['description'] !!}
                        </div>
                        <div id="desc-fade"
                             class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                    </div>

                    <button id="desc-toggle"
                            class="mt-3 text-sm font-semibold transition"
                            style="color:#037F8C;"
                            onclick="toggleDesc()">
                        {{ __('show.read_more') }}
                    </button>

                    <script>
                        const COLLAPSED_HEIGHT = '320px';

                        function toggleDesc() {
                            const wrapper = document.getElementById('desc-wrapper');
                            const fade    = document.getElementById('desc-fade');
                            const btn     = document.getElementById('desc-toggle');
                            const isOpen  = wrapper.dataset.open === '1';

                            if (isOpen) {
                                wrapper.style.maxHeight = COLLAPSED_HEIGHT;
                                wrapper.dataset.open = '0';
                                fade.classList.remove('hidden');
                                btn.textContent = '{{ __('show.read_more') }}';
                            } else {
                                wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
                                wrapper.dataset.open = '1';
                                fade.classList.add('hidden');
                                btn.textContent = '{{ __('show.collapse') }}';
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const wrapper = document.getElementById('desc-wrapper');
                            const btn     = document.getElementById('desc-toggle');
                            if (wrapper.scrollHeight <= 320) {
                                btn.classList.add('hidden');
                                document.getElementById('desc-fade').classList.add('hidden');
                                wrapper.style.maxHeight = 'none';
                            }
                        });
                    </script>

                    {{-- Info kártyák --}}
                    <div class="mt-8 grid grid-cols-2 gap-4">

                        {{-- Felnőtt ár --}}
                        <div class="rounded-2xl p-5" style="background:#037F8C1A;">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#037F8C;">{{ __('show.price_label') }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ $program['price'] }} €</p>
                        </div>

                        {{-- Gyerek ár --}}
                        <div class="rounded-2xl p-5" style="background:#F2B70520;">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#c99400;">
                                {{ __('show.child_price') }}
                                @if($program['child_age_min'] && $program['child_age_max'])
                                    <span class="normal-case font-medium">({{ __('show.age_range', ['min' => $program['child_age_min'], 'max' => $program['child_age_max']]) }})</span>
                                @elseif($program['child_age_max'])
                                    <span class="normal-case font-medium">({{ __('show.age_until', ['max' => $program['child_age_max']]) }})</span>
                                @elseif($program['child_age_min'])
                                    <span class="normal-case font-medium">({{ __('show.age_from', ['min' => $program['child_age_min']]) }})</span>
                                @endif
                            </p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $program['price_child'] !== null ? $program['price_child'] . ' €' : '—' }}
                            </p>
                        </div>

                        {{-- Napi elérhetőség --}}
                        <div class="rounded-2xl p-5" style="background:#F2790520;">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#d9644e;">{{ __('show.availability') }}</p>
                            <p class="text-base font-bold text-gray-900 leading-snug">
                                {{ !empty($program['available_days'])
                                    ? collect($program['available_days'])->map(fn($d) => __('days.' . $d))->implode(', ')
                                    : '—' }}
                            </p>
                        </div>

                        {{-- Időtartam --}}
                        <div class="rounded-2xl p-5" style="background:#F2870520;">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#c26b00;">{{ __('show.duration') }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ $program['duration'] ?: '—' }}</p>
                        </div>

                    </div>
                </div>

                {{-- Videó + kapcsolat --}}
                <div class="flex flex-col gap-6">

                    @if($program['youtube_embed_url'])
                    <h2 class="text-3xl font-bold text-gray-900"
                        style="font-family:'Pacifico',cursive;">{{ __('show.video_title') }}</h2>

                    <div class="relative rounded-2xl overflow-hidden shadow-xl"
                         style="padding-bottom: 56.25%;">
                        <iframe
                            class="absolute inset-0 w-full h-full"
                            src="{{ $program['youtube_embed_url'] }}"
                            title="{{ $program['name'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                    @endif

                    {{-- Kapcsolat doboz --}}
                    <div class="rounded-2xl border-2 p-6 flex flex-col gap-4"
                         style="border-color:#F2B705; background:#FFFBEB;">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ __('show.price_box_label') }}</span>
                            <span class="text-3xl font-extrabold" style="color:#F2B705;">{{ $program['price'] }} € {{ __('show.per_person') }}</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ __('show.contact_hint') }}</p>
                        <div class="grid gap-3">
                            <a href="#contact"
                               class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105 active:scale-95"
                               style="background:#037F8C; color:#fff;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ __('contact.email_btn') }}
                            </a>
                            <button data-wa-opener
                                    data-wa-prefill="{{ __('show.wa_prefill', ['name' => $program['name']]) }}"
                                    onclick="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'wa', data: this.dataset.waPrefill } }))"
                                    class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105 active:scale-95 bg-[#25D366] text-white"
                                    style="transition: transform .2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-4 h-4 fill-white">
                                    <path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.47 2.027 7.773L0 32l8.489-2.003A15.93 15.93 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm7.273 19.353c-.398-.199-2.355-1.162-2.72-1.295-.365-.133-.631-.199-.897.199s-1.029 1.295-1.261 1.561c-.232.266-.465.299-.863.1-.398-.199-1.681-.62-3.201-1.977-1.183-1.056-1.981-2.36-2.213-2.758-.232-.398-.025-.613.175-.811.179-.178.398-.465.598-.698.199-.232.265-.398.398-.664.133-.266.066-.498-.033-.697-.1-.199-.897-2.163-1.229-2.961-.324-.778-.653-.673-.897-.685l-.764-.013c-.266 0-.697.1-1.063.498s-1.395 1.362-1.395 3.322 1.428 3.853 1.628 4.12c.199.265 2.81 4.291 6.808 6.019.951.411 1.693.656 2.272.839.955.304 1.824.261 2.511.158.766-.114 2.355-.963 2.688-1.893.332-.93.332-1.727.232-1.893-.099-.166-.365-.265-.763-.464z"/>
                                </svg>
                                {{ __('contact.whatsapp_btn') }}
                            </button>
                            <button data-wa-opener
                                    onclick="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'messenger', data: '' } }))"
                                    class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105 active:scale-95 bg-blue-600 text-white"
                                    style="transition: transform .2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-white">
                                    <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.652V24l4.088-2.242c1.092.301 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111S18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.1l3.131 3.26L19.752 8.1l-6.561 6.863z"/>
                                </svg>
                                {{ __('contact.messenger_btn') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Hullám → CTA --}}
        <svg viewBox="0 0 1440 70" preserveAspectRatio="none"
             class="absolute bottom-0 left-0 w-full pointer-events-none"
             style="display:block; height:70px;">
            <path fill="#F2B705" d="M0,35 C240,70 480,0 720,35 C960,70 1200,0 1440,35 L1440,70 L0,70 Z"/>
        </svg>
    </section>


    {{-- ════════════════════════════════════════
         CTA
    ════════════════════════════════════════ --}}
    <section class="relative overflow-hidden px-6 pt-16 pb-0"
             style="background: #F2B705;">
        <div class="max-w-3xl mx-auto text-center pb-24">
            <h2 class="text-3xl font-bold text-white mb-3">{{ __('show.cta_title') }}</h2>
            <p class="text-white/80 mb-8">{{ __('show.cta_subtitle') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#contact"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white rounded-xl text-sm font-bold shadow transition hover:scale-105"
                   style="color:#F2B705;">
                    {{ __('contact.email_btn') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
                <button data-wa-opener
                        data-wa-prefill="{{ __('show.wa_prefill', ['name' => $program['name']]) }}"
                        onclick="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'wa', data: this.dataset.waPrefill } }))"
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105"
                        style="background:#25D366; color:#fff;">
                    {{ __('contact.whatsapp_only') }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-4 h-4 fill-white">
                        <path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.47 2.027 7.773L0 32l8.489-2.003A15.93 15.93 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm7.273 19.353c-.398-.199-2.355-1.162-2.72-1.295-.365-.133-.631-.199-.897.199s-1.029 1.295-1.261 1.561c-.232.266-.465.299-.863.1-.398-.199-1.681-.62-3.201-1.977-1.183-1.056-1.981-2.36-2.213-2.758-.232-.398-.025-.613.175-.811.179-.178.398-.465.598-.698.199-.232.265-.398.398-.664.133-.266.066-.498-.033-.697-.1-.199-.897-2.163-1.229-2.961-.324-.778-.653-.673-.897-.685l-.764-.013c-.266 0-.697.1-1.063.498s-1.395 1.362-1.395 3.322 1.428 3.853 1.628 4.12c.199.265 2.81 4.291 6.808 6.019.951.411 1.693.656 2.272.839.955.304 1.824.261 2.511.158.766-.114 2.355-.963 2.688-1.893.332-.93.332-1.727.232-1.893-.099-.166-.365-.265-.763-.464z"/>
                    </svg>
                </button>
                <button data-wa-opener
                        onclick="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'messenger', data: '' } }))"
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105"
                        style="background:linear-gradient(135deg,#0099FF 0%,#A033FF 60%,#FF5C87 100%); color:#fff;">
                    {{ __('contact.messenger_btn') }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-white">
                        <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.652V24l4.088-2.242c1.092.301 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111S18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.1l3.131 3.26L19.752 8.1l-6.561 6.863z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Hullám → kapcsolat --}}
        <svg viewBox="0 0 1440 70" preserveAspectRatio="none"
             class="absolute bottom-0 left-0 w-full pointer-events-none"
             style="display:block; height:70px;">
            <path fill="#79DCF2" d="M0,35 C240,70 480,0 720,35 C960,70 1200,0 1440,35 L1440,70 L0,70 Z"/>
        </svg>
    </section>


    {{-- ════════════════════════════════════════
         KAPCSOLAT
    ════════════════════════════════════════ --}}
    <section id="contact" class="relative overflow-hidden pt-20 pb-0 px-6 bg-dead-sea">

        {{-- Pálma – bal --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 left-0 lg:left-[15vw] h-80 pointer-events-none select-none palm-contact"
             style="opacity:0.2; transform:scaleX(-1);">

        {{-- Pálma – jobb --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 right-0 lg:right-[15vw] h-64 pointer-events-none select-none palm-contact"
             style="opacity:0.2;">

        <div class="max-w-2xl mx-auto pb-24">

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-3"
                    style="font-family:'Pacifico',cursive;">{{ __('contact.title') }}</h2>
                <p class="text-gray-600">{{ __('contact.subtitle') }}</p>
            </div>

            @if(session('contact_success'))
                <div class="flex items-center gap-3 bg-white/40 border border-white/50 text-gray-800 rounded-xl px-5 py-4 mb-8">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium">{{ __('contact.success') }}</span>
                </div>
            @endif

            @error('contact')
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 mb-8 text-sm font-medium">
                    {{ $message }}
                </div>
            @enderror

            <form action="{{ route('contact.store') }}" method="POST" class="space-y-5"
                  onsubmit="var b = this.querySelector('button[type=submit]'); b.disabled = true; b.classList.add('opacity-60', 'cursor-wait'); setTimeout(function () { b.disabled = false; b.classList.remove('opacity-60', 'cursor-wait'); }, 15000);">
                @csrf

                <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                    <input type="text" name="contact_hp" value="" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100"
                           placeholder="{{ __('contact.name_placeholder') }}"
                           class="w-full rounded-xl bg-white border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:ring-2 focus:ring-offset-2 {{ $errors->has('name') ? 'border-red-400 focus:ring-red-300' : 'border-gray-200 focus:ring-cyan-400' }}">
                    @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="150"
                           placeholder="{{ __('contact.email_placeholder') }}"
                           class="w-full rounded-xl bg-white border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:ring-2 focus:ring-offset-2 {{ $errors->has('email') ? 'border-red-400 focus:ring-red-300' : 'border-gray-200 focus:ring-cyan-400' }}">
                    @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Program neve előtöltve --}}
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.program') }}</label>
                    <input type="text" id="subject" name="subject" value="{{ $program['name'] }}"
                           class="w-full rounded-xl bg-white/80 border border-gray-200 px-4 py-3 text-sm text-gray-900 outline-none" readonly>
                    @error('subject') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.message') }}</label>
                    <textarea id="message" name="message" rows="5" required maxlength="2000"
                              placeholder="{{ __('contact.message_placeholder') }}"
                              class="w-full rounded-xl bg-white border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition resize-none focus:ring-2 focus:ring-offset-2 {{ $errors->has('message') ? 'border-red-400 focus:ring-red-300' : 'border-gray-200 focus:ring-cyan-400' }}">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="aszf-wrapper">
                    <label class="flex items-start gap-3 cursor-pointer select-none">
                        <input id="aszf_accepted" name="aszf_accepted" type="checkbox" value="1" required
                               class="aszf-cb mt-0.5" {{ old('aszf_accepted') ? 'checked' : '' }} />
                        <span class="text-base text-gray-700 leading-snug font-medium">
                            {{ __('contact.aszf_accept') }}
                            <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
                               class="underline text-amber-700 hover:text-amber-900 font-bold">{{ __('contact.aszf_link') }}</a>.
                        </span>
                    </label>
                    @error('aszf_accepted') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center py-3.5 rounded-xl text-sm font-bold transition hover:opacity-90 active:scale-95"
                        style="background-color:#F2B705; color:#1a1a1a;">
                    {{ __('contact.send') }}
                </button>
            </form>

        </div>

        {{-- Hullám → footer --}}
        <svg viewBox="0 0 1440 50" preserveAspectRatio="none"
             class="absolute bottom-0 left-0 w-full pointer-events-none"
             style="display:block; height:50px;">
            <path fill="#037F8C" d="M0,25 C360,50 1080,0 1440,25 L1440,50 L0,50 Z"/>
        </svg>
    </section>

</x-layout>
