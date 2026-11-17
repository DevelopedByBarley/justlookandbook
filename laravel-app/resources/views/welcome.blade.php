<x-layout
    title="{{ config('app.name') }}"
    :description="__('seo.home_description')"
    :canonical="route('home')"
>

    {{-- ════════════════════════════════════════
         HERO  —  trópusi óceán
    ════════════════════════════════════════ --}}
    <section class="relative overflow-hidden py-36 px-6"
             style="background: linear-gradient(150deg, #037F8C 0%, #79DCF2 100%);">

        {{-- Pálma – bal --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 left-0 lg:left-[15vw] h-80 pointer-events-none select-none"
             style="opacity:0.18; filter:brightness(0) invert(1); transform:scaleX(-1);">

        {{-- Pálma – jobb --}}
        <img src="{{ asset('storage/9988726.png') }}" alt=""
             class="absolute bottom-0 right-0 lg:right-[15vw] h-64 pointer-events-none select-none"
             style="opacity:0.15; filter:brightness(0) invert(1);">

        {{-- Tartalom --}}
        <div class="relative max-w-3xl mx-auto text-center">
            <span class="inline-block text-xs font-semibold px-4 py-1.5 rounded-full mb-6 tracking-widest uppercase" style="background-color:#79DCF2; color:#037F8C;">
                <span style="font-size:2em; vertical-align:middle;">🌺</span> {{ __('home.hero_badge') }}
            </span>
            <h1 class="text-5xl sm:text-6xl tracking-tight text-white mb-5"
                style="font-family:'Pacifico',cursive; line-height:1.35;">
                {{ __('home.hero_title') }}<br>
                <span style="color:#F2B705;">{{ __('home.hero_highlight') }}</span> {{ __('home.hero_title_end') }}
            </h1>
            <p class="text-lg text-white/75 leading-relaxed max-w-xl mx-auto mb-10">
                {{ __('home.hero_subtitle') }}
            </p>
            <a href="#programs"
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-bold shadow-lg transition hover:scale-105"
               style="background-color:#F2B705; color:#1a1a1a;">
                {{ __('home.hero_btn') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
        </div>

        {{-- Hullám → programok (animált) --}}
        <div class="absolute bottom-0 left-0 w-full overflow-hidden pointer-events-none" style="height:70px;">
            <svg viewBox="0 0 2880 70" preserveAspectRatio="none"
                 class="wave-animate"
                 style="display:block; height:70px; width:200%;">
                <path fill="#f9fafb" d="M0,35 C240,70 480,0 720,35 C960,70 1200,0 1440,35 C1680,70 1920,0 2160,35 C2400,70 2640,0 2880,35 L2880,70 L0,70 Z"/>
            </svg>
        </div>
    </section>


    {{-- ════════════════════════════════════════
         PROGRAMOK  —  homokos part
    ════════════════════════════════════════ --}}
    <section id="programs" class="relative overflow-hidden pt-6 pb-0 px-6 bg-gray-50">
        <div class="max-w-6xl mx-auto pb-16">

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-3"
                    style="font-family:'Pacifico',cursive;">{{ __('home.programs_title') }}</h2>
                <p class="text-amber-900/60 max-w-md mx-auto">{{ __('home.programs_subtitle') }}</p>
            </div>

            <x-programs.topic-filters :topics="$topics" :activeTopic="$activeTopic" />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($programs as $program)
                    <x-programs.card :program="$program" :popular="$popularIds->contains($program->id)" />
                @empty
                    <p class="col-span-3 text-center text-gray-400 py-16">{{ __('home.programs_empty') }}</p>
                @endforelse
            </div>

            {{ $programs->links('components.pagination') }}

        </div>

    </section>


    {{-- ════════════════════════════════════════
         CTA  —  trópusi naplement
    ════════════════════════════════════════ --}}
    <section class="relative overflow-hidden px-6 pt-16 pb-0"
             style="background: #F2B705;">
        <div class="max-w-3xl mx-auto text-center pb-24">
            <h2 class="text-3xl font-bold text-white mb-3" style="font-family:'Pacifico',cursive;">{{ __('home.cta_title') }}</h2>
            <p class="text-white/80 font-bold mb-8">{{ __('home.cta_subtitle') }}</p>
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
                        onclick="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'wa', data: '' } }))"
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-bold shadow transition hover:scale-105"
                        style="background:#25D366; color:#fff;">
                    {{ __('contact.whatsapp_only') }}
                    <svg class="w-4 h-4 fill-white" viewBox="0 0 32 32">
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
         KAPCSOLAT  —  mélytenger
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
                <p class="text-gray-600 font-bold">{{ __('contact.subtitle') }}</p>
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
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="150"
                           placeholder="{{ __('contact.email_placeholder') }}"
                           class="w-full rounded-xl bg-white border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:ring-2 focus:ring-offset-2 {{ $errors->has('email') ? 'border-red-400 focus:ring-red-300' : 'border-gray-200 focus:ring-cyan-400' }}">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('contact.message') }}</label>
                    <textarea id="message" name="message" rows="5" required maxlength="2000"
                              placeholder="{{ __('contact.message_placeholder') }}"
                              class="w-full rounded-xl bg-white border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition resize-none focus:ring-2 focus:ring-offset-2 {{ $errors->has('message') ? 'border-red-400 focus:ring-red-300' : 'border-gray-200 focus:ring-cyan-400' }}">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="aszf-wrapper">
                    <label class="flex items-start gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="aszf_accepted" id="aszf_accepted" value="1" required
                               class="aszf-cb mt-0.5"
                               {{ old('aszf_accepted') ? 'checked' : '' }} />
                        <span class="text-base text-gray-700 leading-snug font-medium">
                            {{ __('contact.aszf_accept') }}
                            <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
                               class="underline text-amber-700 hover:text-amber-900 font-bold">{{ __('contact.aszf_link') }}</a>.
                        </span>
                    </label>
                    @error('aszf_accepted')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
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
