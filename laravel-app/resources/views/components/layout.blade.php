@props([
    'title'       => config('app.name'),
    'description' => null,
    'ogImage'     => null,
    'canonical'   => null,
])

@php
    $pageTitle   = $title ?? config('app.name');
    $pageDesc    = $description ?? null;
    $pageImage   = $ogImage ?? asset('storage/logo.png');
    $pageUrl     = $canonical ?? url()->current();
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $pageTitle }}</title>

    @if($pageDesc)
    <meta name="description" content="{{ $pageDesc }}">
    @endif

    <link rel="canonical" href="{{ $pageUrl }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ $pageUrl }}">
    <meta property="og:title"       content="{{ $pageTitle }}">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    @if($pageDesc)
    <meta property="og:description" content="{{ $pageDesc }}">
    @endif
    @if($pageImage)
    <meta property="og:image"       content="{{ $pageImage }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="{{ $pageImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title"       content="{{ $pageTitle }}">
    @if($pageDesc)
    <meta name="twitter:description" content="{{ $pageDesc }}">
    @endif
    @if($pageImage)
    <meta name="twitter:image"       content="{{ $pageImage }}">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    {{-- Tiltott tárolás (privát mód, letiltott sütik) esetén a localStorage kivételt dob --}}
    <script>
        window.safeStorage = {
            get: function (key) { try { return localStorage.getItem(key); } catch (e) { return null; } },
            set: function (key, value) { try { localStorage.setItem(key, value); } catch (e) {} },
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-abyss antialiased text-gray-900">

    <x-navbar />

    <main
        x-data="{ hasDisclaimer: !safeStorage.get('disclaimer_dismissed') }"
        @disclaimer-dismissed.window="hasDisclaimer = false"
        :class="hasDisclaimer ? 'pt-28' : 'pt-24'">
        {{ $slot }}
    </main>

    <x-footer />

    {{-- WhatsApp widget --}}
    @include('components.whatsapp-widget')

    {{--  Cookie Banner --}}
    @include('components.cookie-consent')


    <script>
        const navLinks = document.querySelectorAll('.nav-link[data-section]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const link = document.querySelector(`.nav-link[data-section="${entry.target.id}"]`);
                if (!link) return;
                if (entry.isIntersecting) {
                    navLinks.forEach(l => l.classList.remove('!text-white', 'underline',
                        'underline-offset-4'));
                    link.classList.add('!text-white', 'underline', 'underline-offset-4');
                }
            });
        }, {
            threshold: 0.3
        });

        document.querySelectorAll('[id]').forEach(section => {
            if (document.querySelector(`.nav-link[data-section="${section.id}"]`)) {
                observer.observe(section);
            }
        });
    </script>
</body>

</html>
