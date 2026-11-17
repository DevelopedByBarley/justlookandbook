<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

    @auth('admin')

        <div class="flex min-h-screen" x-data="{ open: false }">

            {{-- Mobil overlay --}}
            <div x-show="open"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false"
                 class="fixed inset-0 bg-black/50 z-20 lg:hidden"
                 x-cloak></div>

            {{-- Sidebar --}}
            <div :class="open ? 'translate-x-0' : '-translate-x-full'"
                 class="fixed inset-y-0 left-0 z-30 transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:flex lg:shrink-0">
                <x-admin-sidebar />
            </div>

            {{-- Tartalom --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top bar --}}
                <x-admin-navbar :title="$title ?? 'Admin'" />

                <main class="flex-1 p-4 lg:p-8">

                    @if (session('success'))
                        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 shrink-0 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-4a1 1 0 11-2 0v4zm1-8a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}

                </main>
            </div>
        </div>

    @else
        {{ $slot }}
    @endauth

</body>
</html>
