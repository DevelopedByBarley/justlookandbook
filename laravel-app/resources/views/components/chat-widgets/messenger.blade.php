{{-- Messenger speed dial gomb --}}
<div x-show="dialOpen"
     x-transition:enter="transition ease-out duration-200 delay-75"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="flex items-center gap-2 z-50"
     style="display:none;">
    <span class="bg-gray-800 text-white text-xs px-2.5 py-1 rounded-lg shadow">Messenger</span>
    <a href="javascript:void(0)"
       @click.prevent="window.dispatchEvent(new CustomEvent('open-aszf-modal', { detail: { action: 'messenger', data: '' } }))"
       class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg transition-opacity"
       style="background: linear-gradient(135deg, #0099FF 0%, #A033FF 60%, #FF5C87 100%);">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 h-8 fill-white">
            <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.652V24l4.088-2.242c1.092.301 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111S18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.1l3.131 3.26L19.752 8.1l-6.561 6.863z"/>
        </svg>
    </a>
</div>
