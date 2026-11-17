<x-admin-layout title="Új program">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.programs.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Új program</h1>
            <p class="text-sm text-gray-500 mt-0.5">Magyar és angol név megadása kötelező</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Nevek --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Név</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Magyar <span class="text-indigo-500 text-xs">kötelező</span>
                        </label>
                        <input type="text" name="name[hu]" value="{{ old('name.hu') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.hu') ? 'border-red-400' : 'border-gray-300' }}"
                               placeholder="pl. Vízisport tábor">
                        @error('name.hu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            English <span class="text-indigo-500 text-xs">kötelező · slug</span>
                        </label>
                        <input type="text" name="name[en]" value="{{ old('name.en') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.en') ? 'border-red-400' : 'border-gray-300' }}"
                               placeholder="e.g. Water Sports Camp">
                        @error('name.en') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Italiano <span class="text-gray-400 text-xs">opzionale</span>
                        </label>
                        <input type="text" name="name[it]" value="{{ old('name.it') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.it') ? 'border-red-400' : 'border-gray-300' }}"
                               placeholder="es. Campo Sport Acquatici">
                        @error('name.it') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

        </div>

        {{-- Téma + Státusz --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Besorolás</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Témák</label>
                    <div class="space-y-2">
                        @foreach ($topics as $topic)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="topic_ids[]" value="{{ $topic->id }}"
                                       {{ in_array($topic->id, old('topic_ids', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">{{ $topic->getTranslation('hu') }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Státusz</label>
                    <select name="active"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="1" {{ old('active', '1') === '1' ? 'selected' : '' }}>Aktív</option>
                        <option value="0" {{ old('active') === '0' ? 'selected' : '' }}>Inaktív</option>
                    </select>
                    @error('active') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Árak --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Árak</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Felnőtt ár (€)</label>
                    <input type="number" name="price_adult" value="{{ old('price_adult') }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="pl. 55">
                    @error('price_adult') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Gyerek ár (€)</label>
                    <input type="number" name="price_child" value="{{ old('price_child') }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="pl. 22">
                    @error('price_child') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <div class="flex items-center gap-2">
                        <input type="number" name="child_age_min" value="{{ old('child_age_min') }}" min="0" max="18"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Min. életkor">
                        <span class="text-gray-400 shrink-0">—</span>
                        <input type="number" name="child_age_max" value="{{ old('child_age_max') }}" min="0" max="18"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Max. életkor">
                        <span class="text-gray-400 shrink-0 text-sm">év</span>
                    </div>
                    @error('child_age_min') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('child_age_max') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Részletek --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Részletek</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Időtartam</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach (['hu' => '🇭🇺 Magyar', 'en' => '🇬🇧 English', 'it' => '🇮🇹 Italiano'] as $locale => $label)
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
                                <input type="text" name="duration[{{ $locale }}]"
                                       value="{{ old('duration.' . $locale) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       placeholder="{{ $locale === 'hu' ? '4–5 óra' : ($locale === 'en' ? '4–5 hours' : '4–5 ore') }}">
                                @error('duration.' . $locale) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Elérhető napok</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['mon' => 'Hétfő', 'tue' => 'Kedd', 'wed' => 'Szerda', 'thu' => 'Csütörtök', 'fri' => 'Péntek', 'sat' => 'Szombat', 'sun' => 'Vasárnap'] as $val => $label)
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="available_days[]" value="{{ $val }}"
                                       {{ in_array($val, old('available_days', [])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('available_days') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Leírás --}}
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">
                    Leírás <span class="text-indigo-500 text-xs normal-case font-normal">Magyar kötelező</span>
                </h2>

                <div class="space-y-6">
                    @foreach (['hu' => 'Magyar', 'en' => 'English', 'it' => 'Italiano'] as $locale => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                            <x-rich-editor name="description[{{ $locale }}]" :value="old('description.' . $locale, '')" />
                            @error('description.' . $locale) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Képek --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5"
             x-data="{ previews: [], drag: false }">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-1">Képek</h2>
            <p class="text-xs text-gray-400 mb-4">Az első feltöltött kép lesz automatikusan a borítókép.</p>

            <div @dragover.prevent="drag = true" @dragleave.prevent="drag = false"
                 @drop.prevent="drag = false; const files = [...$event.dataTransfer.files]; $refs.fileInput.files = $event.dataTransfer.files; files.forEach(f => { const r = new FileReader(); r.onload = e => previews.push(e.target.result); r.readAsDataURL(f) })"
                 @click="$refs.fileInput.click()"
                 :class="drag ? 'border-indigo-400 bg-indigo-50/30' : 'border-gray-200'"
                 class="border-2 border-dashed rounded-xl px-6 py-10 text-center cursor-pointer transition-colors">
                <svg class="w-8 h-8 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">Kattints vagy húzd ide a képeket</p>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — max. 3 MB / kép</p>
                <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput"
                       @change="previews = []; [...$event.target.files].forEach(f => { const r = new FileReader(); r.onload = e => previews.push(e.target.result); r.readAsDataURL(f) })"
                       class="hidden">
            </div>

            @if ($errors->hasAny(['images', 'images.*']))
                <div class="mt-3 space-y-1">
                    @error('images') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    @foreach ($errors->get('images.*') as $messages)
                        @foreach ($messages as $message)
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @endforeach
                    @endforeach
                </div>
            @endif

            <div x-show="previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                <template x-for="(src, i) in previews" :key="i">
                    <div class="relative rounded-xl overflow-hidden border-2 aspect-video bg-gray-50"
                         :class="i === 0 ? 'border-indigo-500' : 'border-gray-200'">
                        <img :src="src" class="w-full h-full object-cover">
                        <span x-show="i === 0"
                              class="absolute top-2 left-2 text-xs font-semibold bg-indigo-600 text-white px-2 py-0.5 rounded-full">
                            Borítókép
                        </span>
                    </div>
                </template>
            </div>
        </div>

        {{-- YouTube --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5"
             x-data="{
                 raw: {{ Js::from(old('youtube_embed', '')) }},
                 get embedUrl() {
                     const v = this.raw.trim();
                     if (!v) return '';
                     if (v.includes('youtube.com/embed/')) return v;
                     const m = v.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
                     if (m) return 'https://www.youtube.com/embed/' + m[1];
                     if (/^[a-zA-Z0-9_-]{11}$/.test(v)) return 'https://www.youtube.com/embed/' + v;
                     return '';
                 }
             }">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">YouTube videó</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Videó URL vagy ID</label>
                    <input type="text" name="youtube_embed" x-model="raw"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <p class="mt-1 text-xs text-gray-400">Teljes URL-t vagy csak az ID-t is megadhatod.</p>
                    @error('youtube_embed') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="embedUrl" class="rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-50">
                    <iframe :src="embedUrl" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </div>

            </div>
        </div>

        {{-- Mentés --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors cursor-pointer">
                Mentés
            </button>
            <a href="{{ route('admin.programs.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Mégse
            </a>
        </div>

    </form>

</x-admin-layout>
