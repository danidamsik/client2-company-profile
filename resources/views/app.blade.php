<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $seo = $page['props']['seo'] ?? [];
            $seoTitle = $seo['title'] ?? config('app.name', 'Laravel');
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ $seoTitle }}</title>

        @if (! empty($seo['description']))
            <meta name="description" content="{{ $seo['description'] }}">
        @endif

        @if (! empty($seo['keywords']))
            <meta name="keywords" content="{{ $seo['keywords'] }}">
        @endif

        @if (! empty($seo))
            <meta name="robots" content="index, follow">
        @endif

        @if (! empty($seo['url']))
            <link rel="canonical" href="{{ $seo['url'] }}">
        @endif

        @if (! empty($seo['title']))
            <meta property="og:title" content="{{ $seo['title'] }}">
            <meta name="twitter:title" content="{{ $seo['title'] }}">
        @endif

        @if (! empty($seo['description']))
            <meta property="og:description" content="{{ $seo['description'] }}">
            <meta name="twitter:description" content="{{ $seo['description'] }}">
        @endif

        @if (! empty($seo['type']))
            <meta property="og:type" content="{{ $seo['type'] }}">
        @endif

        @if (! empty($seo['url']))
            <meta property="og:url" content="{{ $seo['url'] }}">
        @endif

        @if (! empty($seo['image']))
            <meta property="og:image" content="{{ $seo['image'] }}">
            <meta name="twitter:image" content="{{ $seo['image'] }}">
        @endif

        @if (! empty($seo['siteName']))
            <meta property="og:site_name" content="{{ $seo['siteName'] }}">
        @endif

        @if (! empty($seo['locale']))
            <meta property="og:locale" content="{{ $seo['locale'] }}">
        @endif

        @if (! empty($seo))
            <meta name="twitter:card" content="summary_large_image">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
