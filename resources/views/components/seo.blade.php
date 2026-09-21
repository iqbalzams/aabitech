@php

    $seo = $seo ?? [];

    $siteName = config('seo.site_name', 'AabiTech');

    $title = $seo['title']
        ?? config('seo.default_title');

    $description = $seo['description']
        ?? config('seo.default_description');

    $canonical = $seo['canonical']
        ?? url()->current();

    $robots = $seo['robots']
        ?? config('seo.default_robots', 'index, follow');

    $ogTitle = $seo['og_title']
        ?? $title;

    $ogDescription = $seo['og_description']
        ?? $description;

    $ogImage = $seo['og_image']
        ?? config('seo.default_og_image');

    $ogType = $seo['og_type']
        ?? 'website';

    $twitterCard = $seo['twitter_card']
        ?? 'summary_large_image';

    $schema = $seo['schema']
        ?? null;

@endphp

<title>{{ $title }}</title>

<meta
    name="description"
    content="{{ $description }}"
>

<meta
    name="robots"
    content="{{ $robots }}"
>

<link
    rel="canonical"
    href="{{ $canonical }}"
>

{{-- Open Graph --}}

<meta
    property="og:type"
    content="{{ $ogType }}"
>

<meta
    property="og:title"
    content="{{ $ogTitle }}"
>

<meta
    property="og:description"
    content="{{ $ogDescription }}"
>

<meta
    property="og:url"
    content="{{ $canonical }}"
>

<meta
    property="og:site_name"
    content="{{ $siteName }}"
>

@if ($ogImage)
    <meta
        property="og:image"
        content="{{ $ogImage }}"
    >
@endif

{{-- Twitter / X --}}

<meta
    name="twitter:card"
    content="{{ $twitterCard }}"
>

<meta
    name="twitter:title"
    content="{{ $ogTitle }}"
>

<meta
    name="twitter:description"
    content="{{ $ogDescription }}"
>

@if ($ogImage)
    <meta
        name="twitter:image"
        content="{{ $ogImage }}"
    >
@endif

{{-- Structured Data --}}

@if ($schema)

    <script type="application/ld+json">
        {!! json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PRETTY_PRINT
        ) !!}
    </script>

@endif