<aside class="w-64 min-h-screen bg-gray-950 flex flex-col shrink-0">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-white/10">
        <span class="text-white font-bold text-lg tracking-tight">
            {{ config('app.name') }}
            <span class="text-indigo-400 font-normal text-sm ml-1">admin</span>
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">

        <p class="px-3 pt-2 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Áttekintés</p>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tartalom</p>


        <a href="{{ route('admin.topics.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.topics.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Témák
        </a>
        <a href="{{ route('admin.countries.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.countries.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Országok
        </a>
        <a href="{{ route('admin.programs.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.programs.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Programok
        </a>


        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Súgó</p>

        <a href="{{ route('admin.handbook') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.handbook') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Kézikönyv
        </a>

        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Beállítások</p>

        <a href="{{ route('admin.settings.edit') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Általános
        </a>

        <a href="{{ route('admin.profile.password') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.profile.*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Jelszó változtatás
        </a>

    </nav>

    {{-- User + Logout --}}
    <div class="px-3 py-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-3 py-2 mb-1">
            <div
                class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::guard('admin')->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::guard('admin')->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-red-400 transition-colors cursor-pointer">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Kijelentkezés
            </button>
        </form>
    </div>

</aside>
