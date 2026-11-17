<x-admin-layout title="Program szerkesztése">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.programs.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $program->getTranslation('name', 'hu') }}</h1>
            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $program->slug }}</p>
        </div>
    </div>

    {{-- Program adatok + képek egy formban --}}
    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data"
          x-data="{ deleteIds: [], newPreviews: [], coverId: {{ $program->coverImage?->id ?? 'null' }} }"
          class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Képek --}}
    <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">

        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Képek</h2>

        {{-- Meglévő képek --}}
        @if ($program->images->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                @foreach ($program->images as $image)
                    <div class="relative group rounded-xl overflow-hidden aspect-video bg-gray-50 transition-all"
                         :class="deleteIds.includes({{ $image->id }}) ? 'opacity-40 scale-95' : ''"
                         x-bind:style="coverId === {{ $image->id }} ? 'outline: 2px solid #4f46e5; outline-offset: 2px;' : ''">
                        <img src="{{ $image->url() }}" class="w-full h-full object-cover">

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            {{-- Borítókép --}}
                            <button type="button" @click="coverId = {{ $image->id }}" title="Borítókép"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg p-1.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </button>
                            {{-- Törlés jelölése --}}
                            <button type="button"
                                    @click="deleteIds.includes({{ $image->id }}) ? deleteIds.splice(deleteIds.indexOf({{ $image->id }}), 1) : deleteIds.push({{ $image->id }})"
                                    title="Törlés"
                                    class="bg-red-500 hover:bg-red-600 text-white rounded-lg p-1.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <span x-show="coverId === {{ $image->id }}"
                              class="absolute top-2 left-2 text-xs font-semibold bg-indigo-600 text-white px-2 py-0.5 rounded-full">
                            Borítókép
                        </span>
                        <span x-show="deleteIds.includes({{ $image->id }})"
                              class="absolute top-2 right-2 text-xs font-semibold bg-red-500 text-white px-2 py-0.5 rounded-full">
                            Törlésre jelölve
                        </span>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mb-4">A törlésre jelölt képek mentéskor törlődnek véglegesen.</p>
        @endif

        {{-- Új képek hozzáadása --}}
        <div @dragover.prevent @drop.prevent="newPreviews = []; [...$event.dataTransfer.files].forEach(f => { const r = new FileReader(); r.onload = e => newPreviews.push(e.target.result); r.readAsDataURL(f) }); $refs.fileInput.files = $event.dataTransfer.files"
             @click="$refs.fileInput.click()"
             class="border-2 border-dashed border-gray-200 rounded-xl px-6 py-8 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors">
            <p class="text-sm text-gray-500">Kattints vagy húzd ide az új képeket</p>
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — max. 3 MB / kép</p>
            <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput"
                   @change="newPreviews = []; [...$event.target.files].forEach(f => { const r = new FileReader(); r.onload = e => newPreviews.push(e.target.result); r.readAsDataURL(f) })"
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

        <div x-show="newPreviews.length > 0" class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
            <template x-for="(src, i) in newPreviews" :key="i">
                <div class="relative rounded-xl overflow-hidden border border-dashed border-indigo-300 aspect-video bg-gray-50">
                    <img :src="src" class="w-full h-full object-cover opacity-70">
                    <span class="absolute bottom-2 left-2 text-xs text-indigo-600 font-medium">Új</span>
                </div>
            </template>
        </div>

        {{-- Hidden inputok a formhoz --}}
        <template x-for="id in deleteIds" :key="id">
            <input type="hidden" name="delete_images[]" :value="id">
        </template>
        <input type="hidden" name="cover_image_id" :value="coverId">

    </div>

        {{-- Nevek --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Név</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Magyar <span class="text-indigo-500 text-xs">kötelező</span>
                    </label>
                    <input type="text" name="name[hu]" value="{{ old('name.hu', $program->name['hu'] ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.hu') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('name.hu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        English <span class="text-indigo-500 text-xs">kötelező · slug</span>
                    </label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $program->name['en'] ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.en') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('name.en') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Italiano <span class="text-gray-400 text-xs">opzionale</span>
                    </label>
                    <input type="text" name="name[it]" value="{{ old('name.it', $program->name['it'] ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name.it') ? 'border-red-400' : 'border-gray-300' }}">
                </div>

            </div>
        </div>

        {{-- Besorolás --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Besorolás</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Témák</label>
                    @php $selectedTopicIds = old('topic_ids', $program->topics->pluck('id')->toArray()); @endphp
                    <div class="space-y-2">
                        @foreach ($topics as $topic)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="topic_ids[]" value="{{ $topic->id }}"
                                       {{ in_array($topic->id, $selectedTopicIds) ? 'checked' : '' }}
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
                        <option value="1" {{ old('active', $program->active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktív</option>
                        <option value="0" {{ old('active', $program->active ? '1' : '0') === '0' ? 'selected' : '' }}>Inaktív</option>
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
                    <input type="number" name="price_adult" value="{{ old('price_adult', $program->price_adult) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="pl. 55">
                    @error('price_adult') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Gyerek ár (€)</label>
                    <input type="number" name="price_child" value="{{ old('price_child', $program->price_child) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="pl. 22">
                    @error('price_child') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <div class="flex items-center gap-2">
                        <input type="number" name="child_age_min" value="{{ old('child_age_min', $program->child_age_min) }}" min="0" max="18"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Min. életkor">
                        <span class="text-gray-400 shrink-0">—</span>
                        <input type="number" name="child_age_max" value="{{ old('child_age_max', $program->child_age_max) }}" min="0" max="18"
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
                                       value="{{ old('duration.' . $locale, $program->duration[$locale] ?? '') }}"
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
                                       {{ in_array($val, old('available_days', $program->available_days ?? [])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('available_days') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- YouTube --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5"
             x-data="{
                 raw: {{ Js::from(old('youtube_embed', $program->youtube_embed ?? '')) }},
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
                           placeholder="https://www.youtube.com/watch?v=... vagy video ID">
                    <p class="mt-1 text-xs text-gray-400">Teljes URL-t vagy csak az ID-t is megadhatod.</p>
                    @error('youtube_embed') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="embedUrl" class="rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-50">
                    <iframe :src="embedUrl" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                </div>

            </div>
        </div>

        {{-- Leírás --}}
        <div class="bg-white rounded-2xl border border-gray-200 px-6 py-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">
                Leírás <span class="text-indigo-500 text-xs normal-case font-normal">Magyar kötelező</span>
            </h2>
            <div class="space-y-6">
                @foreach (['hu' => 'Magyar', 'en' => 'English', 'it' => 'Italiano'] as $locale => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                        <x-rich-editor name="description[{{ $locale }}]"
                                       :value="old('description.' . $locale, $program->description[$locale] ?? '')" />
                        @error('description.' . $locale) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @endforeach
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
