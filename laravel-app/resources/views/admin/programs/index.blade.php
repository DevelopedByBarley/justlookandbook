<x-admin-layout title="Programok">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Programok</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $programs->total() }} program összesen</p>
        </div>
        <div class="flex items-center gap-2">
        <a href="{{ route('admin.programs.order') }}"
           class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Sorrend
        </a>
        <a href="{{ route('admin.programs.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Új program
        </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if ($programs->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                Még nincs egyetlen program sem.
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Név</th>
                        <th class="px-6 py-3 text-left">Téma</th>
                        <th class="px-6 py-3 text-left">Felnőtt ár</th>
                        <th class="px-6 py-3 text-left">Gyerek ár</th>
                        <th class="px-6 py-3 text-center">Státusz</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($programs as $program)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $program->getTranslation('name', 'hu') }}</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $program->slug }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $program->topics->map(fn($t) => $t->getTranslation('hu'))->filter()->implode(', ') ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $program->price_adult ? $program->price_adult . ' €' : '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                @if ($program->price_child)
                                    {{ $program->price_child }} €
                                    @if ($program->child_age_min && $program->child_age_max)
                                        <span class="text-xs text-gray-400">({{ $program->child_age_min }}–{{ $program->child_age_max }} év)</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($program->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        Aktív
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Inaktív
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.programs.edit', $program) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                        Szerkesztés
                                    </a>
                                    <form method="POST" action="{{ route('admin.programs.destroy', $program) }}"
                                          onsubmit="return confirm('Biztosan törlöd?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-medium transition-colors cursor-pointer">
                                            Törlés
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($programs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $programs->links() }}
                </div>
            @endif
        @endif
    </div>

</x-admin-layout>
