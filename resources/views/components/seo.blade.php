@php
    /*
    |--------------------------------------------------------------------------
    | AabiTech SEO Component
    |--------------------------------------------------------------------------
    | Supported values:
    | title
    | description
    | canonical
    | robots
    | og_title
    | og_description
    | og_image
    | og_type
    | og_locale
    | twitter_card
    | schema
    */

    $seo = is_array($seo ?? null) ? $seo : [];

    $siteName = config(
        'aabitech.site_name',
        'AabiTech'
    );

    $siteUrl = rtrim(
        config(
            'aabitech.site_url',
            config('app.url', url('/'))
        ),
        '/'
    );

    /*
    |--------------------------------------------------------------------------
    | Basic SEO
    |--------------------------------------------------------------------------
    */

    $title = trim(
        $seo['title']
            ?? config(
                'aabitech.seo.default_title',
                'AabiTech – Free Online Developer & Daily Productivity Tools'
            )
    );

    $description = trim(
        $seo['description']
            ?? config(
                'aabitech.seo.default_description',
                ''
            )
    );

    $robots = trim(
        $seo['robots']
            ?? config(
                'aabitech.seo.default_robots',
                'index, follow'
            )
    );

    /*
    |--------------------------------------------------------------------------
    | Canonical
    |--------------------------------------------------------------------------
    */

    $canonical = $seo['canonical'] ?? url()->current();

    if ($canonical && ! filter_var($canonical, FILTER_VALIDATE_URL)) {
        $canonical = $siteUrl . '/' . ltrim($canonical, '/');
    }

    /*
    |--------------------------------------------------------------------------
    | Open Graph
    |--------------------------------------------------------------------------
    */

    $ogTitle = trim(
        $seo['og_title'] ?? $title
    );

    $ogDescription = trim(
        $seo['og_description'] ?? $description
    );

    $ogType = $seo['og_type'] ?? 'website';

    $ogLocale = $seo['og_locale'] ?? 'en_US';

    $ogImage = $seo['og_image']
        ?? config(
            'aabitech.seo.default_og_image'
        );

    if ($ogImage && ! filter_var($ogImage, FILTER_VALIDATE_URL)) {
        $ogImage = $siteUrl . '/' . ltrim($ogImage, '/');
    }

    /*
    |--------------------------------------------------------------------------
    | Twitter / X
    |--------------------------------------------------------------------------
    */

    $twitterCard = $seo['twitter_card']
        ?? 'summary_large_image';

    /*
    |--------------------------------------------------------------------------
    | Structured Data
    |--------------------------------------------------------------------------
    */

    $schema = $seo['schema'] ?? null;
@endphp

{{-- =========================================================
     BASIC SEO
========================================================== --}}

<title>{{ $title }}</title>

@if ($description)
    <meta
        name="description"
        content="{{ $description }}"
    >
@endif

<meta
    name="robots"
    content="{{ $robots }}"
>

<link
    rel="canonical"
    href="{{ $canonical }}"
>

{{-- =========================================================
     OPEN GRAPH
========================================================== --}}

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

<meta
    property="og:locale"
    content="{{ $ogLocale }}"
>

@if ($ogImage)
    <meta
        property="og:image"
        content="{{ $ogImage }}"
    >

    <meta
        property="og:image:alt"
        content="{{ $ogTitle }}"
    >
@endif

{{-- =========================================================
     TWITTER / X
========================================================== --}}

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

{{-- =========================================================
     STRUCTURED DATA
========================================================== --}}

@if (! empty($schema))
    <script type="application/ld+json">
{!! json_encode(
    $schema,
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE |
    JSON_HEX_TAG |
    JSON_HEX_AMP |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_PRETTY_PRINT |
    JSON_THROW_ON_ERROR
) !!}
    </script>
@endif