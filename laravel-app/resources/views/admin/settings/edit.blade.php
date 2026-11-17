<x-admin-layout title="Beállítások">

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Beállítások</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kapcsolati és egyéb beállítások</p>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 max-w-lg">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">WhatsApp</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Telefonszám <span class="text-indigo-500 text-xs">kötelező</span>
                </label>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 shrink-0">+</span>
                    <input type="text" name="whatsapp_phone"
                           value="{{ old('whatsapp_phone', $whatsapp_phone) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('whatsapp_phone') border-red-400 @enderror"
                           placeholder="36201234567">
                </div>
                <p class="mt-1 text-xs text-gray-400">Országkóddal, szóköz és + jel nélkül (pl. 36201234567)</p>
                @error('whatsapp_phone')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors cursor-pointer">
                Mentés
            </button>
        </div>

    </form>

</x-admin-layout>
