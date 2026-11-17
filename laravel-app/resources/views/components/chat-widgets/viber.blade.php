{{-- Viber speed dial gomb --}}
<div x-show="dialOpen"
     x-transition:enter="transition ease-out duration-200 delay-75"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="flex items-center gap-2"
     style="display:none;">
    <span class="bg-gray-800 text-white text-xs px-2.5 py-1 rounded-lg shadow">Viber</span>
    <a href="viber://chat?number=+36305541007" target="_blank" rel="noopener"
       :class="aszfAccepted ? 'opacity-100' : 'opacity-40 pointer-events-none'"
       class="flex items-center justify-center w-12 h-12 rounded-full shadow-lg transition-opacity"
       style="background:#7360F2;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-white">
            <path d="M11.4 0C6.15.3 1.9 4.55 1.6 9.8c-.15 2.7.6 5.25 2.1 7.3L2.1 21.6c-.15.45.3.9.75.75l4.5-1.6c1.95 1.35 4.35 2.1 6.9 2.1C19.8 22.85 24 18.5 24 13.2V11.4C23.7 5.1 18.15-.3 11.4 0zm5.55 16.65c-.45.75-1.2 1.35-2.1 1.5-.45.075-.975.15-2.7-.6-2.325-.975-4.2-2.85-5.4-5.1-.6-1.2-.975-2.55-.975-3.9 0-.9.3-1.725.9-2.325.45-.45 1.05-.675 1.65-.675h.45c.525 0 .975.3 1.2.75l1.05 2.325c.225.525.075 1.125-.3 1.5l-.525.6c-.15.15-.15.375-.075.525.375.75.975 1.425 1.65 1.95.675.525 1.425.9 2.25 1.125.225.075.45 0 .6-.15l.525-.6c.375-.45.975-.6 1.5-.375l2.325 1.05c.525.225.825.75.75 1.275l-.015.075c-.15.525-.45 1.05-.75 1.575z"/>
        </svg>
    </a>
</div>
