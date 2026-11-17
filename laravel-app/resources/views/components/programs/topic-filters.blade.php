@props(['topics', 'activeTopic' => null])

<div class="flex flex-wrap justify-center gap-3 mb-12">
    <a href="{{ route('home') }}"
       class="px-5 py-2 rounded-full text-sm font-semibold border transition"
       style="{{ $activeTopic === null
           ? 'background-color:#F27979; color:#fff; border-color:#F27979;'
           : 'background-color:#fff; color:#4b5563; border-color:#e5e7eb;' }}">
        {{ __('programs.all') }}
    </a>

    @foreach ($topics as $topic)
        <a href="{{ route('home', ['topic' => $topic->slug]) }}"
           class="px-5 py-2 rounded-full text-sm font-semibold border transition"
           style="{{ $activeTopic === $topic->slug
               ? 'background-color:#F27979; color:#fff; border-color:#F27979;'
               : 'background-color:#fff; color:#4b5563; border-color:#e5e7eb;' }}">
            {{ $topic->getTranslation() }}
        </a>
    @endforeach
</div>
