@props([
    'necessary'  => true,
    'analytics'  => false,
    'marketing'  => false,
    'privacyUrl' => '#',
])

@php
    $hasOptional = $analytics || $marketing;
    $gridCols    = ($analytics && $marketing) ? 'sm:grid-cols-3'
                 : (($analytics || $marketing)  ? 'sm:grid-cols-2'
                 : '');
@endphp

<div id="cookie-banner"
     class="fixed bottom-0 inset-x-0 z-50 p-4 sm:p-6 transition-transform duration-500"
     style="display:none;">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Fejléc --}}
        <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-gray-100">
            <span class="text-2xl shrink-0">🍪</span>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ __('cookies.title') }}</p>
                <p class="text-xs text-gray-400">{{ __('cookies.subtitle') }}</p>
            </div>
        </div>

        {{-- Alap tartalom --}}
        <div class="px-6 py-4 space-y-3">
            <p class="text-xs text-gray-600 leading-relaxed">
                @if($analytics && $marketing)
                    {{ __('cookies.desc_all') }}
                @elseif($analytics)
                    {{ __('cookies.desc_analytics') }}
                @elseif($marketing)
                    {{ __('cookies.desc_marketing') }}
                @else
                    {{ __('cookies.desc_necessary') }}
                @endif
            </p>

            {{-- Részletek toggle --}}
            <button onclick="toggleCookieDetails(this)"
                    data-show="{{ __('cookies.details_show') }}"
                    data-hide="{{ __('cookies.details_hide') }}"
                    id="cookie-details-toggle"
                    class="flex items-center gap-1 text-[11px] text-gray-400 hover:text-gray-700 transition">
                <svg id="cookie-details-chevron"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="w-3.5 h-3.5 transition-transform duration-200">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
                <span id="cookie-details-label">{{ __('cookies.details_show') }}</span>
            </button>

            {{-- Részletek szekció --}}
            <div id="cookie-details" class="hidden space-y-3">

                <div class="grid grid-cols-1 gap-2 {{ $gridCols }}">
                    <div class="flex items-start gap-2 bg-gray-50 rounded-lg px-3 py-3">
                        <span class="text-base mt-0.5 shrink-0">⚙️</span>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-semibold text-gray-800">{{ __('cookies.cat_necessary') }}</p>
                                <span class="text-[10px] bg-gray-200 text-gray-500 rounded px-1 py-0.5 leading-none">{{ __('cookies.cat_necessary_always') }}</span>
                            </div>
                            <p class="text-[11px] text-gray-400 leading-snug">{{ __('cookies.cat_necessary_desc') }}</p>
                        </div>
                    </div>

                    @if($analytics)
                    <div class="flex items-start gap-2 bg-gray-50 rounded-lg px-3 py-3">
                        <span class="text-base mt-0.5 shrink-0">📊</span>
                        <div class="space-y-0.5">
                            <p class="text-xs font-semibold text-gray-800">{{ __('cookies.cat_analytics') }}</p>
                            <p class="text-[11px] text-gray-400 leading-snug">{{ __('cookies.cat_analytics_desc') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($marketing)
                    <div class="flex items-start gap-2 bg-gray-50 rounded-lg px-3 py-3">
                        <span class="text-base mt-0.5 shrink-0">🎯</span>
                        <div class="space-y-0.5">
                            <p class="text-xs font-semibold text-gray-800">{{ __('cookies.cat_marketing') }}</p>
                            <p class="text-[11px] text-gray-400 leading-snug">{{ __('cookies.cat_marketing_desc') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="rounded-lg border border-gray-100 overflow-hidden text-[11px]">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-left">
                                <th class="px-3 py-2 font-medium">{{ __('cookies.table_name') }}</th>
                                <th class="px-3 py-2 font-medium">{{ __('cookies.table_purpose') }}</th>
                                <th class="px-3 py-2 font-medium whitespace-nowrap">{{ __('cookies.table_expiry') }}</th>
                                <th class="px-3 py-2 font-medium">{{ __('cookies.table_type') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-600">
                            <tr>
                                <td class="px-3 py-2 font-mono">{{ config('session.cookie', 'laravel_session') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.row_session') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ __('cookies.row_session_exp') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.cat_necessary') }}</td>
                            </tr>
                            <tr class="bg-gray-50/50">
                                <td class="px-3 py-2 font-mono">XSRF-TOKEN</td>
                                <td class="px-3 py-2">{{ __('cookies.row_csrf') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ __('cookies.row_session_exp') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.cat_necessary') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 font-mono">cookie_consent</td>
                                <td class="px-3 py-2">{{ __('cookies.row_consent') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ __('cookies.row_1year') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.cat_necessary') }}</td>
                            </tr>
                            @if($analytics)
                            <tr class="bg-gray-50/50">
                                <td class="px-3 py-2 font-mono">_ga</td>
                                <td class="px-3 py-2">{{ __('cookies.row_ga') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ __('cookies.row_2year') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.cat_analytics') }}</td>
                            </tr>
                            @endif
                            @if($marketing)
                            <tr class="bg-gray-50/50">
                                <td class="px-3 py-2 font-mono">_fbp</td>
                                <td class="px-3 py-2">{{ __('cookies.row_fbp') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ __('cookies.row_3month') }}</td>
                                <td class="px-3 py-2">{{ __('cookies.cat_marketing') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <p class="text-[11px] text-gray-400">
                    @if($hasOptional){{ __('cookies.withdraw') }} @endif
                    {!! __('cookies.privacy_link', ['link' => '<a href="' . e($privacyUrl) . '" class="underline hover:text-gray-700 transition">' . e(__('cookies.privacy_label')) . '</a>']) !!}
                </p>
            </div>
        </div>

        {{-- Gombok --}}
        <div class="flex flex-col sm:flex-row items-center justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100">
            @if($hasOptional)
                <button onclick="cookieDecline()"
                        class="w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-200 transition">
                    {{ __('cookies.btn_necessary') }}
                </button>
                <button onclick="cookieAccept()"
                        class="w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-semibold bg-gray-900 text-white hover:bg-gray-700 transition">
                    {{ __('cookies.btn_accept_all') }}
                </button>
            @else
                <button onclick="cookieAccept()"
                        class="w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-semibold bg-gray-900 text-white hover:bg-gray-700 transition">
                    {{ __('cookies.btn_ok') }}
                </button>
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        if (!safeStorage.get('cookie_consent')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }
    })();

    function toggleCookieDetails(btn) {
        var details = document.getElementById('cookie-details');
        var chevron = document.getElementById('cookie-details-chevron');
        var label   = document.getElementById('cookie-details-label');
        var open    = !details.classList.contains('hidden');

        details.classList.toggle('hidden');
        chevron.style.transform = open ? '' : 'rotate(180deg)';
        label.textContent       = open ? btn.dataset.show : btn.dataset.hide;
    }

    function cookieAccept() {
        safeStorage.set('cookie_consent', 'accepted');
        hideCookieBanner();
    }

    function cookieDecline() {
        safeStorage.set('cookie_consent', 'necessary');
        hideCookieBanner();
    }

    function hideCookieBanner() {
        var banner = document.getElementById('cookie-banner');
        banner.style.transform = 'translateY(110%)';
        setTimeout(function () { banner.style.display = 'none'; }, 500);
    }
</script>
