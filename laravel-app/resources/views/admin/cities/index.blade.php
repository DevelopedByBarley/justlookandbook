<x-admin-layout title="Városok">

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Városok</h1>
            <p class="text-sm text-gray-500 mt-0.5">Úti célok városai</p>
        </div>
        @if ($countries->isNotEmpty())
            <a href="{{ route('admin.cities.create', $selectedCountry ? ['country' => $selectedCountry->id] : []) }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Új város
            </a>
        @else
            <a href="{{ route('admin.countries.create') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors shrink-0">
                Új ország létrehozása
            </a>
        @endif
    </div>

    @if ($countries->isNotEmpty())
        <form method="GET" action="{{ route('admin.cities.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Ország</label>
                <select id="country" name="country" onchange="this.form.submit()"
                        class="w-full sm:w-64 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Összes ország</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @selected($selectedCountry?->id === $country->id)>
                            {{ $country->name['hu'] ?? '—' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors cursor-pointer">
                Szűrés
            </button>
        </form>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if ($countries->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                <a href="{{ route('admin.countries.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Előbb hozz létre egy országot.</a>
            </div>
        @elseif ($cities->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                {{ $selectedCountry ? 'Ebben az országban még nincs város.' : 'Még nincs egyetlen város sem.' }}
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">Kép</th>
                            <th class="px-6 py-3 text-left">Magyar</th>
                            <th class="px-6 py-3 text-left">English</th>
                            <th class="px-6 py-3 text-left">Italiano</th>
                            <th class="px-6 py-3 text-left">Ország</th>
                            <th class="px-6 py-3 text-right">Programok</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($cities as $city)
                            @php
                                $confirm = 'Biztosan törlöd?';
                                if ($city->programs_count > 0) {
                                    $confirm .= " {$city->programs_count} program városa üres lesz.";
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3">
                                    @if ($city->imageUrl())
                                        <img src="{{ $city->imageUrl() }}" alt="{{ $city->name['hu'] ?? '' }}"
                                             class="w-16 h-10 rounded-md object-cover bg-gray-100">
                                    @else
                                        <div class="w-16 h-10 rounded-md bg-gray-100 flex items-center justify-center text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3 font-medium text-gray-900">
                                    {{ $city->name['hu'] ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ ($city->name['en'] ?? '') ?: '—' }}
                                </td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ ($city->name['it'] ?? '') ?: '—' }}
                                </td>
                                <td class="px-6 py-3 text-gray-700">
                                    {{ $city->country->name['hu'] ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-right text-gray-500">
                                    {{ $city->programs_count }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.cities.edit', $city) }}"
                                           class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                            Szerkesztés
                                        </a>
                                        <form method="POST" action="{{ route('admin.cities.destroy', $city) }}"
                                              onsubmit="return confirm({{ Js::from($confirm) }})">
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
            </div>

            @if ($cities->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $cities->links() }}
                </div>
            @endif
        @endif
    </div>

</x-admin-layout>
