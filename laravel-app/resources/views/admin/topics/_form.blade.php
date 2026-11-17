<div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">

    {{-- Magyar (kötelező) --}}
    <div class="px-6 py-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">Magyar</span>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-0.5 rounded-full">kötelező</span>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Név</label>
            <input type="text" name="name[hu]"
                   value="{{ old('name.hu', $topic->name['hu'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.hu') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="pl. Vízi sportok">
            @error('name.hu')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- English (kötelező) --}}
    <div class="px-6 py-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">English</span>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-0.5 rounded-full">kötelező</span>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name[en]"
                   value="{{ old('name.en', $topic->name['en'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.en') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="e.g. Water Sports">
            @error('name.en')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Italiano (opcionális) --}}
    <div class="px-6 py-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">Italiano</span>
            <span class="text-xs bg-gray-100 text-gray-500 font-medium px-2 py-0.5 rounded-full">opzionale</span>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <input type="text" name="name[it]"
                   value="{{ old('name.it', $topic->name['it'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.it') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="es. Sport Acquatici">
            @error('name.it')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>
