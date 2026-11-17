<x-admin-layout title="Új jelszó beállítása">
    <div class="min-h-screen flex">

        {{-- Bal panel --}}
        <div class="hidden lg:flex w-1/2 bg-gray-950 flex-col justify-between p-12">
            <span class="text-white font-bold text-xl tracking-tight">
                {{ config('app.name') }}
                <span class="text-indigo-400 font-normal text-sm ml-1">admin</span>
            </span>
            <div>
                <p class="text-3xl font-bold text-white leading-snug mb-3">
                    Állíts be egy<br>új jelszót.
                </p>
                <p class="text-gray-500 text-sm">Legalább 8 karakter hosszú jelszót adj meg.</p>
            </div>
            <p class="text-xs text-gray-700">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>

        {{-- Jobb panel --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-white">
            <div class="w-full max-w-sm">

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Új jelszó beállítása</h1>
                    <p class="text-sm text-gray-500 mt-1">Minimum 10 karakter, kis- és nagybetű, szám és speciális karakter szükséges.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-4a1 1 0 11-2 0v4zm1-8a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mail cím</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Új jelszó</label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Jelszó megerősítése</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition">
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition cursor-pointer mt-2">
                        Jelszó mentése
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>
