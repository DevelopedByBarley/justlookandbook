<x-admin-layout title="Jelszó változtatás">

    <div class="max-w-lg">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-900">Jelszó változtatás</h1>
            <p class="text-sm text-gray-500 mt-0.5">Adj meg egy erős, legalább 8 karakter hosszú jelszót.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Jelenlegi jelszó</label>
                    <input id="current_password" type="password" name="current_password" required
                           class="w-full px-3.5 py-2.5 bg-gray-50 border rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition
                                  {{ $errors->has('current_password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Új jelszó</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-3.5 py-2.5 bg-gray-50 border rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Új jelszó megerősítése</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition cursor-pointer">
                        Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
