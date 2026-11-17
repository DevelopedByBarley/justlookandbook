@php $langs = ['hu' => '🇭🇺', 'en' => '🇬🇧', 'it' => '🇮🇹']; @endphp

@props(['mobile' => false])

@if($mobile)
    {{-- Mobil: dropdown --}}
    <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
        <button @click="langOpen = !langOpen"
            class="flex items-center gap-1.5 text-white transition text-sm font-semibold px-3 py-1.5 rounded-lg border border-white/50 hover:border-white hover:bg-white/10">
            <span class="text-2xl">{{ $langs[app()->getLocale()] }}</span>
            <span class="uppercase">{{ app()->getLocale() }}</span>
            <svg class="w-3 h-3 transition-transform" :class="langOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="langOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 top-full mt-2 w-28 bg-white rounded-xl shadow-lg py-1 z-50"
            style="display:none;">
            @foreach($langs as $code => $flag)
                <a href="{{ route('lang.switch', $code) }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition {{ app()->getLocale() === $code ? 'font-semibold' : '' }}">
                    <span class="text-2xl">{{ $flag }}</span>
                    <span class="uppercase">{{ $code }}</span>
                </a>
            @endforeach
        </div>
    </div>
@else
    {{-- Desktop: egymás melletti gombok --}}
    <div class="flex items-center gap-1.5">
        @foreach($langs as $code => $flag)
            <a href="{{ route('lang.switch', $code) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold transition
                    {{ app()->getLocale() === $code
                        ? 'bg-white text-momo-peach shadow-sm'
                        : 'text-white border border-white/40 hover:border-white hover:bg-white/15' }}">
                <span class="text-2xl">{{ $flag }}</span>
                <span class="uppercase">{{ $code }}</span>
            </a>
        @endforeach
    </div>
@endif
