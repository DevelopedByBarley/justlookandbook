<x-admin-layout title="Témák">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Témák</h1>
            <p class="text-sm text-gray-500 mt-0.5">Programok kategóriái</p>
        </div>
        <a href="{{ route('admin.topics.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Új téma
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if ($topics->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                Még nincs egyetlen téma sem.
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Magyar</th>
                        <th class="px-6 py-3 text-left">English</th>
                        <th class="px-6 py-3 text-left">Italiano</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($topics as $topic)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $topic->name['hu'] ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $topic->name['en'] ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $topic->name['it'] ?: '—' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                {{ $topic->slug }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.topics.edit', $topic) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                        Szerkesztés
                                    </a>
                                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}"
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

            @if ($topics->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $topics->links() }}
                </div>
            @endif
        @endif
    </div>

</x-admin-layout>
