<x-admin-layout title="Programok sorrendje">

    <style>
        #program-order .drag-handle { cursor: grab; }
        #program-order .sortable-ghost { opacity: .4; background: #eef2ff; }
    </style>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Programok sorrendje</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Fogd meg a sort a bal oldali ikonnál, és húzd a helyére. A sorrend automatikusan mentődik.
                A sorszám a publikus oldalon elfoglalt helyet mutatja (oldalanként 20 program), az inaktív programok nem jelennek meg.
                <span id="order-status" class="font-medium"></span>
            </p>
        </div>
        <a href="{{ route('admin.programs.index') }}"
           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors">
            Vissza a listához
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <ul id="program-order" class="divide-y divide-gray-100 text-sm">
            @php($position = 0)
            @foreach ($programs as $program)
                <li data-id="{{ $program->id }}" data-active="{{ $program->active ? 1 : 0 }}"
                    class="flex items-center gap-4 px-6 py-3 bg-white">
                    <span class="drag-handle text-gray-400 hover:text-gray-600" title="Húzd a helyére">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </span>
                    {{-- Helyezés a publikus oldalon (csak az aktívakat számolja) --}}
                    <span class="position w-6 text-right text-xs text-gray-400">{{ $program->active ? ++$position : '–' }}</span>
                    <span class="flex-1 font-medium {{ $program->active ? 'text-gray-900' : 'text-gray-400' }}">{{ $program->getTranslation('name', 'hu') }}</span>
                    @unless ($program->active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                            Inaktív
                        </span>
                    @endunless
                </li>
            @endforeach
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        (() => {
            const list = document.getElementById('program-order')
            const status = document.getElementById('order-status')

            const showStatus = (text, ok) => {
                status.textContent = text
                status.className = 'font-medium ' + (ok ? 'text-emerald-700' : 'text-red-500')
            }

            const sortable = Sortable.create(list, {
                handle: '.drag-handle',
                animation: 150,
                onEnd(e) {
                    if (e.oldIndex === e.newIndex) return

                    let position = 0
                    list.querySelectorAll('li').forEach(li => {
                        li.querySelector('.position').textContent = li.dataset.active === '1' ? ++position : '–'
                    })
                    showStatus('Mentés…', true)

                    fetch(@json(route('admin.programs.reorder')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        },
                        body: JSON.stringify({ ids: sortable.toArray() }),
                    })
                        .then(r => {
                            if (!r.ok) throw new Error(r.status)
                            showStatus('Sorrend mentve.', true)
                        })
                        .catch(() => showStatus('Hiba történt a mentéskor, frissítsd az oldalt!', false))
                },
            })
        })()
    </script>

</x-admin-layout>
