@props(['program', 'popular' => false])

@php
    $url        = route('program.show', $program->slug);
    $name       = $program->getTranslation('name');
    $topicNames = $program->topics->map->getTranslation()->filter()->values();
    $img        = $program->coverImage?->url() ?? 'https://picsum.photos/seed/' . $program->slug . '/600/400';
    $price     = number_format($program->price_adult ?? 0, 0, ',', ' ');
@endphp

<a href="{{ $url }}"
   class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group flex flex-col">

    {{-- Kép --}}
    <div class="relative overflow-hidden h-48 bg-gray-200 animate-pulse">
        <img src="{{ $img }}"
             alt="{{ $name }}"
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-0"
             onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('animate-pulse', 'bg-gray-200')">

        {{-- Téma badge-ek --}}
        @if($topicNames->isNotEmpty())
            <div class="absolute bottom-3 left-3 flex flex-wrap gap-1">
                @foreach($topicNames as $topicName)
                    <span class="text-white text-xs font-semibold px-3 py-1 rounded-full"
                          style="background-color:rgba(3,127,140,0.85); backdrop-filter:blur(4px);">
                        {{ $topicName }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Népszerű badge --}}
        @if($popular)
            <span class="absolute top-3 right-3 text-white text-xs font-bold px-3 py-1 rounded-full shadow"
                  style="background-color:#F27979;">
                🌺 {{ __('programs.popular') }}
            </span>
        @endif
    </div>

    {{-- Tartalom --}}
    <div class="p-5 flex flex-col flex-1">
        <h3 class="font-bold text-gray-900 text-lg mb-3 leading-snug">{{ $name }}</h3>

        <div class="mt-auto flex items-center justify-between">
            <span class="text-xl font-extrabold" style="color:#F28705;">
                {{ $price }} €
            </span>

            <span class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-xl transition"
                  style="color:#037F8C; background-color:#037F8C1A;">
                {{ __('programs.details') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </span>
        </div>
    </div>

</a>
