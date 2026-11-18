<x-admin-layout title="Város szerkesztése">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.cities.index', ['country' => $city->country_id]) }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $city->name['hu'] ?? 'Város szerkesztése' }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Város szerkesztése</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.cities.update', $city) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.cities._form')

        <div class="mt-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors cursor-pointer">
                Mentés
            </button>
            <a href="{{ route('admin.cities.index', ['country' => $city->country_id]) }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Mégse
            </a>
        </div>
    </form>

</x-admin-layout>
