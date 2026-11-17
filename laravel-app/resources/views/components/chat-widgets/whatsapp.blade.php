{{-- WhatsApp popup --}}
<div id="wa-popup"
     x-show="waOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="w-80 sm:w-110 rounded-2xl shadow-2xl overflow-hidden"
     style="font-family: sans-serif; display:none;"
     @open-wa.window="waOpen = true; if ($event.detail?.accepted) aszfAccepted = true; if ($event.detail?.message) { $nextTick(() => { document.getElementById('wa-message').value = $event.detail.message; document.getElementById('wa-message').focus(); }); } else { $nextTick(() => document.getElementById('wa-message')?.focus()); }">

    {{-- Fejléc --}}
    <div class="flex items-center gap-3 px-4 py-3" style="background:#075E54;">
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-6 h-6 fill-white">
                <path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.47 2.027 7.773L0 32l8.489-2.003A15.93 15.93 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.27 13.27 0 0 1-6.769-1.849l-.485-.288-5.04 1.189 1.226-4.908-.317-.504A13.27 13.27 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.273-9.98c-.398-.199-2.355-1.162-2.72-1.295-.365-.133-.631-.199-.897.199s-1.029 1.295-1.261 1.561c-.232.266-.465.299-.863.1-.398-.199-1.681-.62-3.201-1.977-1.183-1.056-1.981-2.36-2.213-2.758-.232-.398-.025-.613.175-.811.179-.178.398-.465.598-.698.199-.232.265-.398.398-.664.133-.266.066-.498-.033-.697-.1-.199-.897-2.163-1.229-2.961-.324-.778-.653-.673-.897-.685l-.764-.013c-.266 0-.697.1-1.063.498s-1.395 1.362-1.395 3.322 1.428 3.853 1.628 4.12c.199.265 2.81 4.291 6.808 6.019.951.411 1.693.656 2.272.839.955.304 1.824.261 2.511.158.766-.114 2.355-.963 2.688-1.893.332-.93.332-1.727.232-1.893-.099-.166-.365-.265-.763-.464z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-white font-semibold text-sm leading-tight">{{ config('app.name') }}</p>
            <p class="text-green-200 text-xs">{{ __('whatsapp.subtitle') }}</p>
        </div>
        <button @click="waOpen = false" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
    </div>

    {{-- Chat buborék --}}
    <div class="px-4 py-4" style="background:#ECE5DD;">
        <div class="bg-white rounded-lg rounded-tl-none px-4 py-3 shadow text-base text-gray-700 max-w-xs">
            {{ __('whatsapp.greeting') }}
            <p class="text-xs text-gray-400 mt-1 text-right">{{ __('whatsapp.now') }}</p>
        </div>
    </div>

    {{-- ÁSZF checkbox (ideiglenesen kikommentelve - közös checkbox használata) --}}
    {{-- <div class="px-4 pt-3 pb-1" style="background:#F0F0F0;">
        <label class="flex items-start gap-2 cursor-pointer">
            <input id="wa-aszf" type="checkbox" class="mt-0.5 shrink-0 accent-green-600" onchange="waUpdateBtn()" />
            <span class="text-xs text-gray-600 leading-snug">
                {{ __('whatsapp.aszf_accept') }}
                <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
                   class="underline text-green-700 hover:text-green-900 font-medium">{{ __('whatsapp.aszf_link') }}</a>.
            </span>
        </label>
    </div> --}}

    {{-- Üzenet mező --}}
    <div class="flex items-center gap-3 px-4 py-4" style="background:#F0F0F0;">
        <input id="wa-message" type="text"
               placeholder="{{ __('whatsapp.placeholder') }}"
               class="flex-1 rounded-full px-5 py-3 text-base border-2 outline-none bg-white shadow-inner"
               style="border-color:#25D366;"
               @keydown.enter="if(aszfAccepted) window.sendWA()" />
        <button id="wa-send" @click="if(aszfAccepted) window.sendWA()"
                :class="aszfAccepted ? 'opacity-100 cursor-pointer' : 'opacity-40 cursor-not-allowed'"
                class="flex items-center justify-center w-12 h-12 rounded-full text-white shrink-0 transition-opacity"
                style="background:#25D366;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-white">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
        </button>
    </div>
</div>

{{-- WhatsApp speed dial gomb --}}
<div x-show="dialOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="flex items-center gap-2"
     style="display:none;">
    <span class="bg-gray-800 text-white text-xs px-2.5 py-1 rounded-lg shadow">WhatsApp</span>
    <button @click="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'wa', data: '' } }))"
            class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg transition-opacity"
            style="background:#25D366;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-8 h-8 fill-white">
            <path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.47 2.027 7.773L0 32l8.489-2.003A15.93 15.93 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.27 13.27 0 0 1-6.769-1.849l-.485-.288-5.04 1.189 1.226-4.908-.317-.504A13.27 13.27 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.273-9.98c-.398-.199-2.355-1.162-2.72-1.295-.365-.133-.631-.199-.897.199s-1.029 1.295-1.261 1.561c-.232.266-.465.299-.863.1-.398-.199-1.681-.62-3.201-1.977-1.183-1.056-1.981-2.36-2.213-2.758-.232-.398-.025-.613.175-.811.179-.178.398-.465.598-.698.199-.232.265-.398.398-.664.133-.266.066-.498-.033-.697-.1-.199-.897-2.163-1.229-2.961-.324-.778-.653-.673-.897-.685l-.764-.013c-.266 0-.697.1-1.063.498s-1.395 1.362-1.395 3.322 1.428 3.853 1.628 4.12c.199.265 2.81 4.291 6.808 6.019.951.411 1.693.656 2.272.839.955.304 1.824.261 2.511.158.766-.114 2.355-.963 2.688-1.893.332-.93.332-1.727.232-1.893-.099-.166-.365-.265-.763-.464z"/>
        </svg>
    </button>
</div>
