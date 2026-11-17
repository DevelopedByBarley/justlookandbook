@props(['title' => 'Admin'])

<header class="h-14 bg-white border-b border-gray-200 flex items-center px-4 lg:px-8 gap-3 shrink-0">
    <button @click="open = !open"
            class="lg:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition cursor-pointer"
            aria-label="Menü">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h2 class="text-sm font-semibold text-gray-800">{{ $title ?? 'Admin' }}</h2>
</header>
