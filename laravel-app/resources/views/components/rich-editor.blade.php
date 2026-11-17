@props(['name', 'value' => ''])

@php $btn = 'px-2 py-1 rounded transition'; @endphp
@php $active = 'bg-gray-200 text-gray-900'; @endphp
@php $inactive = 'text-gray-500 hover:bg-gray-100'; @endphp
@php $divider = '<div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>'; @endphp

<div
    x-data="richEditor({{ Js::from($value) }})"
    @destroy.window="destroy()"
    :class="fullscreen ? 'fixed inset-0 z-50 flex flex-col bg-white' : ''"
>
    {{-- ───── Toolbar ───── --}}
    <div class="flex flex-wrap items-center gap-0.5 border border-gray-300 bg-gray-50 px-2 py-1.5"
         :class="fullscreen ? 'rounded-none shrink-0' : 'rounded-t-xl'">

        {{-- Bekezdés / Cím dropdown --}}
        <div class="relative" @click.away="showHeadingMenu = false">
            <button type="button" @click="showHeadingMenu = !showHeadingMenu"
                class="{{ $btn }} flex items-center gap-1 {{ $inactive }} min-w-22 text-sm">
                <span x-text="currentBlock()"></span>
                <svg class="w-3 h-3 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="showHeadingMenu" x-cloak
                 class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-20 py-1 min-w-35">
                <button type="button" @click="setHeading(0)"
                    class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Bekezdés</button>
                <button type="button" @click="setHeading(1)"
                    class="w-full text-left px-3 py-1.5 text-xl font-bold hover:bg-gray-50">Cím 1</button>
                <button type="button" @click="setHeading(2)"
                    class="w-full text-left px-3 py-1.5 text-lg font-bold hover:bg-gray-50">Cím 2</button>
                <button type="button" @click="setHeading(3)"
                    class="w-full text-left px-3 py-1.5 text-base font-bold hover:bg-gray-50">Cím 3</button>
                <button type="button" @click="setHeading(4)"
                    class="w-full text-left px-3 py-1.5 text-sm font-bold hover:bg-gray-50">Cím 4</button>
            </div>
        </div>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Szöveg formázás --}}
        <button type="button" @click="run('toggleBold')"
            :class="isActive('bold') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-sm font-bold" title="Félkövér">B</button>

        <button type="button" @click="run('toggleItalic')"
            :class="isActive('italic') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-sm italic" title="Dőlt">I</button>

        <button type="button" @click="run('toggleUnderline')"
            :class="isActive('underline') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-sm underline" title="Aláhúzott">U</button>

        <button type="button" @click="run('toggleStrike')"
            :class="isActive('strike') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-sm line-through" title="Áthúzott">S</button>

        <button type="button" @click="run('toggleSubscript')"
            :class="isActive('subscript') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-xs" title="Alsó index">X₂</button>

        <button type="button" @click="run('toggleSuperscript')"
            :class="isActive('superscript') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} text-xs" title="Felső index">X²</button>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Szövegszín --}}
        <label class="relative {{ $btn }} {{ $inactive }} cursor-pointer" title="Szöveg színe">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L7 13h2.5l1-2.5h3l1 2.5H17L12 2zm-1 7l1-2.5 1 2.5h-2zM5 19a2 2 0 002 2h10a2 2 0 002-2v-1H5v1z"/>
            </svg>
            <input type="color" value="#000000" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer"
                   @change="setTextColor($event.target.value)">
        </label>

        {{-- Kiemelés --}}
        <label class="relative {{ $btn }} {{ $inactive }} cursor-pointer" title="Kiemelés">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <input type="color" value="#fde047" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer"
                   @change="setHighlight($event.target.value)">
        </label>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Igazítás --}}
        <button type="button" @click="align('left')"
            :class="alignActive('left') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Balra igazít">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h12"/>
            </svg>
        </button>

        <button type="button" @click="align('center')"
            :class="alignActive('center') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Középre igazít">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M8 12h8M6 18h12"/>
            </svg>
        </button>

        <button type="button" @click="align('right')"
            :class="alignActive('right') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Jobbra igazít">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M12 12h8M8 18h12"/>
            </svg>
        </button>

        <button type="button" @click="align('justify')"
            :class="alignActive('justify') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Sorkizárt">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Listák --}}
        <button type="button" @click="run('toggleBulletList')"
            :class="isActive('bulletList') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Felsorolás">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
            </svg>
        </button>

        <button type="button" @click="run('toggleOrderedList')"
            :class="isActive('orderedList') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Számozott lista">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 12h10M7 16h10M3 8h.01M3 12h.01M3 16h.01"/>
            </svg>
        </button>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Blokk elemek --}}
        <button type="button" @click="run('toggleBlockquote')"
            :class="isActive('blockquote') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Idézet">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 17h3l2-4V7h-6v6h3l-2 4zm-8 0h3l2-4V7H5v6h3l-2 4z"/>
            </svg>
        </button>

        <button type="button" @click="run('toggleCode')"
            :class="isActive('code') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} font-mono text-xs" title="Inline kód">&lt;/&gt;</button>

        <button type="button" @click="run('toggleCodeBlock')"
            :class="isActive('codeBlock') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} font-mono text-xs" title="Kódblokk">{ }</button>

        <button type="button" @click="run('setHorizontalRule')"
            class="{{ $btn }} {{ $inactive }}" title="Elválasztó vonal">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16"/>
            </svg>
        </button>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Beillesztés: link, kép --}}
        <button type="button" @click="isActive('link') ? run('unsetLink') : setLink()"
            :class="isActive('link') ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }}" title="Link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
        </button>

        <button type="button" @click="insertImage()"
            class="{{ $btn }} {{ $inactive }}" title="Kép beillesztése">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </button>

        {{-- Táblázat dropdown --}}
        <div class="relative" @click.away="showTableMenu = false">
            <button type="button" @click="showTableMenu = !showTableMenu"
                :class="inTable() ? '{{ $active }}' : '{{ $inactive }}'"
                class="{{ $btn }} flex items-center gap-0.5" title="Táblázat">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M3 14h18M10 3v18M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/>
                </svg>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="showTableMenu" x-cloak
                 class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-20 py-1 min-w-50">
                <button type="button" @click="insertTable()"
                    class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Táblázat beillesztése (3×3)</button>
                <template x-if="inTable()">
                    <div>
                        <div class="border-t border-gray-100 my-1"></div>
                        <p class="px-3 py-1 text-xs text-gray-400 font-medium uppercase tracking-wide">Sorok</p>
                        <button type="button" @click="tableCmd('addRowBefore')"
                            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Sor hozzáadása elé</button>
                        <button type="button" @click="tableCmd('addRowAfter')"
                            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Sor hozzáadása után</button>
                        <button type="button" @click="tableCmd('deleteRow')"
                            class="w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Sor törlése</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <p class="px-3 py-1 text-xs text-gray-400 font-medium uppercase tracking-wide">Oszlopok</p>
                        <button type="button" @click="tableCmd('addColumnBefore')"
                            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Oszlop hozzáadása elé</button>
                        <button type="button" @click="tableCmd('addColumnAfter')"
                            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50">Oszlop hozzáadása után</button>
                        <button type="button" @click="tableCmd('deleteColumn')"
                            class="w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Oszlop törlése</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <button type="button" @click="tableCmd('deleteTable')"
                            class="w-full text-left px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Táblázat törlése</button>
                    </div>
                </template>
            </div>
        </div>

        <div class="w-px h-5 bg-gray-300 mx-1 shrink-0"></div>

        {{-- Undo / Redo --}}
        <button type="button" @click="run('undo')"
            class="{{ $btn }} {{ $inactive }}" title="Visszavonás">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
        </button>
        <button type="button" @click="run('redo')"
            class="{{ $btn }} {{ $inactive }}" title="Újra">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 10H11a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/>
            </svg>
        </button>

        {{-- Teljes képernyő --}}
        <button type="button" @click="fullscreen = !fullscreen"
            :class="fullscreen ? '{{ $active }}' : '{{ $inactive }}'"
            class="{{ $btn }} ml-auto" title="Teljes képernyő">
            <svg x-show="!fullscreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
            </svg>
            <svg x-show="fullscreen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 9V4m0 5H4m5 0L3 3m12 6h5m-5 0V4m0 5l6-6M9 15H4m5 0v5m0-5l-6 6m12-6h5m-5 0v5m0-5l6 6"/>
            </svg>
        </button>
    </div>

    {{-- ───── Szerkesztő terület ───── --}}
    <div
        x-ref="editorContent"
        class="border border-t-0 border-gray-300 bg-white transition focus-within:ring-2 focus-within:ring-indigo-400 focus-within:border-indigo-400"
        :class="fullscreen ? 'flex-1 overflow-y-auto rounded-none' : 'rounded-b-xl'"
    ></div>

    {{-- ───── Lábléc: karakter / szószámláló ───── --}}
    <div class="flex justify-end gap-4 text-xs text-gray-400 mt-1 px-1">
        <span x-text="charCount + ' karakter'"></span>
        <span x-text="wordCount + ' szó'"></span>
    </div>

    {{-- Rejtett input a form küldéshez --}}
    <input type="hidden" name="{{ $name }}" :value="content">
</div>
