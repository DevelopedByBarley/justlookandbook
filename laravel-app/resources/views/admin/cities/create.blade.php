<x-admin-layout title="Új város">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.cities.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Új város</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ország, magyar és angol név megadása kötelező</p>
        </div>
    </div>

    @if ($countries->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-16 text-center text-sm">
            <a href="{{ route('admin.countries.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Előbb hozz létre egy országot.</a>
        </div>
    @else
        <form method="POST" action="{{ route('admin.cities.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.cities._form', ['city' => new \App\Models\City()])

            <div class="mt-4 flex items-center gap-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors cursor-pointer">
                    Mentés
                </button>
                <a href="{{ route('admin.cities.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Mégse
                </a>
            </div>
        </form>
    @endif

</x-admin-layout>
