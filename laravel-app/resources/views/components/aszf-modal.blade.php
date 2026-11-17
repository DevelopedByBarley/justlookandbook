{{-- ÁSZF elfogadó modal – widget speed dial gombokhoz --}}
<div x-data="{ open: false, pendingAction: null, pendingData: '' }"
     @open-aszf-modal.window="open = true; pendingAction = $event.detail.action; pendingData = $event.detail.data ?? ''"
     x-show="open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[200] flex items-end sm:items-center justify-center px-4 pb-4 sm:pb-0"
     style="background:rgba(0,0,0,0.55); display:none;"
     @click.self="open = false">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl flex flex-col gap-5 p-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         @click.stop>

        {{-- Fejléc --}}
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-xl font-bold text-gray-800 leading-snug">
                {{ __('whatsapp.modal_title') }}
            </h3>
            <button @click="open = false"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none mt-0.5 transition shrink-0">
                &times;
            </button>
        </div>

        <p class="text-sm text-gray-500 -mt-2">{{ __('whatsapp.modal_subtitle') }}</p>

        {{-- ÁSZF link --}}
        <div class="aszf-wrapper">
            <p class="text-base text-gray-700 leading-snug font-medium">
                {{ __('whatsapp.aszf_accept') }}
                <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
                   class="underline text-amber-700 hover:text-amber-900 font-bold">{{ __('whatsapp.aszf_link') }}</a>.
            </p>
        </div>

        {{-- Gombok --}}
        <div class="flex gap-3">
            <button @click="open = false"
                    class="flex-1 py-3 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                {{ __('whatsapp.modal_cancel') }}
            </button>
            <button @click="if (pendingAction === 'wa') window.openWAWidget(pendingData, true);
                            if (pendingAction === 'messenger') window.openMessengerWidget();
                            open = false;"
                    class="flex-1 py-3 rounded-xl text-sm font-bold transition cursor-pointer text-gray-900 hover:opacity-90"
                    style="background:#F2B705;">
                {{ __('whatsapp.modal_confirm') }}
            </button>
        </div>

    </div>
</div>
