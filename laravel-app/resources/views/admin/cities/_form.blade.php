<div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">

    {{-- Ország (kötelező) --}}
    <div class="px-6 py-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">Ország</span>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-0.5 rounded-full">kötelező</span>
        </div>
        @php
            // Előválasztás: régi bevitel, a város országa, vagy a lista szűrőjéből érkező ?country=
            $selectedCountryId = old('country_id', $city->country_id ?? request()->query('country'));
            $selectedCountryId = is_scalar($selectedCountryId) ? (string) $selectedCountryId : '';
        @endphp
        <div>
            <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Ország</label>
            <select id="country_id" name="country_id"
                    class="w-full max-w-md border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('country_id') ? 'border-red-400' : 'border-gray-300' }}">
                <option value="">Válassz országot…</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected($selectedCountryId === (string) $country->id)>
                        {{ $country->name['hu'] ?? '—' }}
                    </option>
                @endforeach
            </select>
            @error('country_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Magyar (kötelező) --}}
    <div class="px-6 py-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">Magyar</span>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-0.5 rounded-full">kötelező</span>
        </div>
        <div>
            <label for="name_hu" class="block text-sm font-medium text-gray-700 mb-1">Név</label>
            <input type="text" id="name_hu" name="name[hu]"
                   value="{{ old('name.hu', $city->name['hu'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.hu') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="pl. Hurghada">
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
            <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name_en" name="name[en]"
                   value="{{ old('name.en', $city->name['en'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.en') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="e.g. Hurghada">
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
            <label for="name_it" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <input type="text" id="name_it" name="name[it]"
                   value="{{ old('name.it', $city->name['it'] ?? '') }}"
                   class="w-full max-w-md border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent {{ $errors->has('name.it') ? 'border-red-400' : 'border-gray-300' }}"
                   placeholder="es. Hurghada">
            @error('name.it')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Kép (nem kötelező) --}}
    <div class="px-6 py-5" x-data="{ preview: null }">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-base font-semibold text-gray-900">Kép</span>
            <span class="text-xs bg-gray-100 text-gray-500 font-medium px-2 py-0.5 rounded-full">nem kötelező</span>
        </div>

        @if ($city->imageUrl())
            <div class="mb-4" x-show="!preview">
                <img src="{{ $city->imageUrl() }}" alt="{{ $city->name['hu'] ?? '' }}"
                     class="w-full max-w-xs aspect-video rounded-xl object-cover border border-gray-200 bg-gray-50">
                <p class="mt-2 text-xs text-gray-400">Új kép feltöltésével lecserélheted.</p>
                <label for="remove_image" class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1" @checked(old('remove_image'))
                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    Kép eltávolítása
                </label>
            </div>
        @endif

        <div x-show="preview" style="display: none" class="mb-4">
            <img :src="preview" alt="Kiválasztott kép előnézete"
                 class="w-full max-w-xs aspect-video rounded-xl object-cover border-2 border-indigo-500 bg-gray-50">
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Képfájl</label>
            <input type="file" id="image" name="image" accept="image/*"
                   @change="preview = null; const f = $event.target.files[0]; if (f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f) }"
                   class="block w-full max-w-md text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 file:cursor-pointer">
            <p class="mt-1 text-xs text-gray-400">JPG, PNG, GIF, WebP — max. 3 MB</p>
            @error('image')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>
