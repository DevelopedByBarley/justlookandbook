<x-aszf-modal />

<div id="wa-widget"
     data-phone="{{ \App\Models\Setting::get('whatsapp_phone', config('services.whatsapp.phone')) }}"
     class="fixed bottom-6 right-6 z-[60] flex flex-col items-end gap-3"
     x-data="{ dialOpen: false, waOpen: false, aszfAccepted: true }"
     @click.outside="if (!$event.target.closest('[data-wa-opener]')) { dialOpen = false; waOpen = false; }">

    {{-- WhatsApp popup + speed dial gomb --}}
    @include('components.chat-widgets.whatsapp')

    {{-- Többi speed dial gomb --}}
    @include('components.chat-widgets.messenger')
    {{-- @include('components.chat-widgets.viber') --}}

    {{-- Fő gomb --}}
    <div class="relative">
        <button @click="dialOpen = !dialOpen; if(!dialOpen) { waOpen = false; }"
                class="flex items-center justify-center w-20 h-20 rounded-full shadow-xl transition-transform duration-200"
                :class="dialOpen ? 'rotate-45' : ''"
                style="background:#25D366;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
        </button>
        <span x-show="!dialOpen"
              class="absolute -top-1 right-0 flex items-center justify-center w-6 h-6 rounded-full text-white text-xs font-bold pointer-events-none animate-pulse"
              style="background:#EF4444; font-size:14px;">1</span>
    </div>
</div>
