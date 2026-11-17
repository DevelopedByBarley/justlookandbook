<x-admin-layout title="Dashboard">

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Üdv, {{ Auth::guard('admin')->user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('Y. F j., l') }}</p>
    </div>

    {{-- Stat kártyák --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Összes program</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stats['programs_total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#037F8C1A;">
                <svg class="w-5 h-5" style="color:#037F8C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Aktív program</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stats['programs_active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Témák</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stats['topics'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Összes megtekintés</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ number_format($stats['views_total'], 0, ',', ' ') }}</p>
            </div>
        </div>

    </div>

    {{-- Legutóbbi programok --}}
    <div class="bg-white rounded-2xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">Legutóbb hozzáadott programok</h2>
            <a href="{{ route('admin.programs.index') }}"
               class="text-xs font-medium transition" style="color:#037F8C;">
                Összes →
            </a>
        </div>

        @if ($recent->isEmpty())
            <p class="px-6 py-8 text-sm text-gray-400 text-center">Még nincs egyetlen program sem.</p>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($recent as $program)
                    <li class="flex items-center gap-4 px-6 py-3">
                        <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                            @if ($program->coverImage)
                                <img src="{{ $program->coverImage->url() }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $program->getTranslation('name', 'hu') }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $program->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $program->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $program->active ? 'Aktív' : 'Inaktív' }}
                            </span>
                            <a href="{{ route('admin.programs.edit', $program) }}"
                               class="text-gray-400 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-admin-layout>
