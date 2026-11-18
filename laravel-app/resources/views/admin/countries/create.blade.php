<x-admin-layout title="Új ország">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.countries.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Új ország</h1>
            <p class="text-sm text-gray-500 mt-0.5">Magyar és angol név megadása kötelező</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.countries.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.countries._form', ['country' => new \App\Models\Country()])

        <div class="mt-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors cursor-pointer">
                Mentés
            </button>
            <a href="{{ route('admin.countries.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Mégse
            </a>
        </div>
    </form>

</x-admin-layout>
