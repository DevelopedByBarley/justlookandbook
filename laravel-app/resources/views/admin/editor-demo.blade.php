<x-admin-layout title="Editor demo">

    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-900">TipTap Rich Editor</h1>
            <p class="text-sm text-gray-500 mt-1">Szerkesztő demo — így fog kinézni a program leírás mezőjénél.</p>
        </div>

        <form method="POST" action="#" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program neve</label>
                <input type="text" name="name" placeholder="pl. Vízisport tábor"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Leírás</label>
                <x-rich-editor name="description" />
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                    Mentés
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>
