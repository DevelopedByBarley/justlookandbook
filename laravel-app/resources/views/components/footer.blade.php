<footer class="px-6 bg-milky-aquamarine">
    <div class="max-w-6xl mx-auto py-8 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-base text-white" style="font-family:'Pacifico',cursive;">{{ config('app.name') }}</span>
        <a href="{{ asset('storage/aszf.pdf') }}" target="_blank" rel="noopener"
           class="text-sm text-white/50 hover:text-white underline underline-offset-2 transition">
            {{ __('nav.aszf_footer') }}
        </a>
    </div>
    <div class="border-t border-white/20 py-3 text-center">
        <p class="text-xs text-white/40">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('nav.all_rights') }}</p>
    </div>
</footer>
