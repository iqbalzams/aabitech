@php
    $navCategories = \App\Models\Category::query()
        ->where('status', true)
        ->withCount([
            'tools' => fn ($query) => $query->where('status', true),
        ])
        ->orderBy('sort_order')
        ->get();

    $popularTools = \App\Models\Tool::query()
        ->where('status', true)
        ->where('is_popular', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();

    $footerTools = $popularTools;

    $currentRoute = request()->route()?->getName();

    $isToolsRoute = in_array($currentRoute, [
        'tools',
        'tools.tool',
        'tools.category',
    ], true);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/aabitech-logo.svg') }}">
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <x-seo :seo="$seo ?? []" />

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    {{-- =========================================================
        SKIP LINK
    ========================================================== --}}

    <a
        href="#main-content"
        class="sr-only z-[100] rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-slate-950"
    >
        Skip to main content
    </a>

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <header
        x-data="{
            mobileMenuOpen: false,
            toolsOpen: false,
            categoriesOpen: false
        }"
        @keydown.escape.window="
            mobileMenuOpen = false;
            toolsOpen = false;
            categoriesOpen = false;
        "
        class="sticky top-0 z-50 border-b border-slate-800/90 bg-slate-950/95 shadow-[0_8px_30px_rgba(15,23,42,0.16)] backdrop-blur-xl"
    >
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/80 to-transparent"
            aria-hidden="true"
        ></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-4">

                {{-- =================================================
                    LOGO
                ================================================== --}}

                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="group flex shrink-0 items-center rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950"
                    aria-label="AabiTech Home"
                >
                    <img
                        src="{{ asset('images/aabitech-logo.svg') }}"
                        width="300"
                        height="75"
                        alt="AabiTech"
                        class="object-contain transition-transform duration-200 group-hover:scale-[1.02]"
                    >
                </a>

                {{-- =================================================
                    DESKTOP NAVIGATION
                ================================================== --}}

                <nav
                    class="hidden h-full items-center gap-1.5 lg:flex"
                    aria-label="Primary navigation"
                >

                    {{-- Home --}}

                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        @class([
                            'inline-flex h-10 items-center rounded-lg px-3.5 text-[14px] font-semibold tracking-[-0.01em] transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950',
                            'bg-blue-950 text-blue-100 shadow-sm ring-1 ring-blue-800/90' => $currentRoute === 'home',
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => $currentRoute !== 'home',
                        ])
                        @if ($currentRoute === 'home')
                            aria-current="page"
                        @endif
                    >
                        Home
                    </a>

                    {{-- Tools dropdown --}}

                    <div class="relative">
                        <button
                            type="button"
                            @click="
                                toolsOpen = !toolsOpen;
                                categoriesOpen = false;
                            "
                            @click.outside="toolsOpen = false"
                            :aria-expanded="toolsOpen.toString()"
                            aria-haspopup="true"
                            aria-controls="tools-menu"
                            class="inline-flex h-10 cursor-pointer items-center gap-1.5 rounded-lg px-3.5 text-[14px] font-semibold tracking-[-0.01em] transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950
                                {{ $isToolsRoute
                                    ? 'bg-blue-950 text-blue-100 shadow-sm ring-1 ring-blue-800/90'
                                    : 'text-slate-300 hover:bg-blue-950/60 hover:text-white' }}"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>

                            <span>Tools</span>

                            <svg
                                class="h-3.5 w-3.5 transition-transform duration-150"
                                :class="{ 'rotate-180': toolsOpen }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            id="tools-menu"
                            x-show="toolsOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-1/2 top-full z-50 mt-2 w-[340px] -translate-x-1/2 overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-2 shadow-2xl shadow-slate-950/40 ring-1 ring-white/5"
                        >
                            <div class="flex items-center justify-between px-3 pb-2 pt-2">
                                <div>
                                    <p class="text-[13px] font-semibold text-white">
                                        Tools
                                    </p>

                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                        Popular tools to get started
                                    </p>
                                </div>

                                <a
                                    href="{{ route('tools') }}"
                                    wire:navigate
                                    @click="toolsOpen = false"
                                    class="cursor-pointer text-[11px] font-semibold text-blue-400 transition-colors hover:text-blue-300"
                                >
                                    View all
                                </a>
                            </div>

                            <div class="space-y-0.5">
                                @forelse ($popularTools as $tool)
                                    <a
                                        href="{{ route('tools.tool', ['slug' => $tool->slug]) }}"
                                        wire:navigate
                                        @click="toolsOpen = false"
                                        class="group flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 transition-all hover:bg-blue-950/50 focus-visible:bg-blue-950/50 focus-visible:outline-none"
                                    >
                                        <span
                                            aria-hidden="true"
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/[0.06] text-sm ring-1 ring-white/[0.05] transition-colors group-hover:bg-blue-900/60"
                                        >
                                            @if ($tool->icon)
                                                <img
                                                    src="{{ asset($tool->icon) }}"
                                                    width="36"
                                                    height="36"
                                                    alt="{{$tool->name}}"
                                                    class="h-9 w-auto object-contain"
                                                >
                                            @else
                                                <span class="text-xl">⚡</span>
                                            @endif
                                        </span>

                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-[12px] font-semibold text-slate-200">
                                                {{ $tool->name }}
                                            </span>

                                            @if ($tool->short_description)
                                                <span class="mt-0.5 block truncate text-[10px] text-slate-500">
                                                    {{ $tool->short_description }}
                                                </span>
                                            @endif
                                        </span>

                                        <svg
                                            class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-colors group-hover:text-blue-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="m9 18 6-6-6-6"
                                            />
                                        </svg>
                                    </a>
                                @empty
                                    <a
                                        href="{{ route('tools') }}"
                                        wire:navigate
                                        @click="toolsOpen = false"
                                        class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-3 text-[13px] font-medium text-slate-300 hover:bg-blue-950/50"
                                    >
                                        Browse all tools
                                    </a>
                                @endforelse
                            </div>

                            <div class="mt-2 border-t border-white/[0.07] pt-2">
                                <a
                                    href="{{ route('tools') }}"
                                    wire:navigate
                                    @click="toolsOpen = false"
                                    class="flex cursor-pointer items-center justify-center rounded-xl bg-white/[0.05] px-3 py-2.5 text-[12px] font-semibold text-slate-300 transition-all hover:bg-blue-950/60 hover:text-blue-200"
                                >
                                    Explore all AabiTech tools
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Categories dropdown --}}

                    <div class="relative">
                        <button
                            type="button"
                            @click="
                                categoriesOpen = !categoriesOpen;
                                toolsOpen = false;
                            "
                            @click.outside="categoriesOpen = false"
                            :aria-expanded="categoriesOpen.toString()"
                            aria-haspopup="true"
                            aria-controls="categories-menu"
                            class="inline-flex h-10 cursor-pointer items-center gap-1.5 rounded-lg px-3.5 text-[14px] font-semibold tracking-[-0.01em] transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950
                                {{ $currentRoute === 'tools.category'
                                    ? 'bg-blue-950 text-blue-100 shadow-sm ring-1 ring-blue-800/90'
                                    : 'text-slate-300 hover:bg-blue-950/60 hover:text-white' }}"
                        >
                            <span>Categories</span>

                            <svg
                                class="h-3.5 w-3.5 transition-transform duration-150"
                                :class="{ 'rotate-180': categoriesOpen }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            id="categories-menu"
                            x-show="categoriesOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-1/2 top-full z-50 mt-2 w-[560px] -translate-x-1/2 overflow-hidden rounded-2xl border border-slate-700/80 bg-slate-900 p-3 shadow-2xl shadow-slate-950/40 ring-1 ring-white/5"
                        >
                            <div class="flex items-center justify-between px-2 pb-2">
                                <div>
                                    <p class="text-[13px] font-semibold text-white">
                                        Browse by category
                                    </p>

                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                        Find the right tool for your task
                                    </p>
                                </div>

                                <span class="text-[11px] text-slate-500">
                                    {{ $navCategories->sum('tools_count') }}
                                    tools
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-1.5">
                                @foreach ($navCategories as $category)
                                    <a
                                        href="{{ route('tools.category', ['slug' => $category->slug]) }}"
                                        wire:navigate
                                        @click="categoriesOpen = false"
                                        class="group flex min-w-0 cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 transition-all hover:bg-blue-950/50 focus-visible:bg-blue-950/50 focus-visible:outline-none"
                                    >
                                        <span
                                            aria-hidden="true"
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/[0.06] text-base ring-1 ring-white/[0.05] transition-colors group-hover:bg-blue-900/60"
                                        >
                                            {{ $category->icon }}
                                        </span>

                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-[12px] font-semibold text-slate-200">
                                                {{ $category->name }}
                                            </span>

                                            <span class="mt-0.5 block text-[10px] text-slate-500">
                                                {{ $category->tools_count }}
                                                {{ \Illuminate\Support\Str::plural('tool', $category->tools_count) }}
                                            </span>
                                        </span>

                                        <svg
                                            class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-colors group-hover:text-blue-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="m9 18 6-6-6-6"
                                            />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- About --}}

                    <a
                        href="{{ route('about') }}"
                        wire:navigate
                        @class([
                            'inline-flex h-10 items-center rounded-lg px-3.5 text-[14px] font-semibold tracking-[-0.01em] transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950',
                            'bg-blue-950 text-blue-100 shadow-sm ring-1 ring-blue-800/90' => $currentRoute === 'about',
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => $currentRoute !== 'about',
                        ])
                        @if ($currentRoute === 'about')
                            aria-current="page"
                        @endif
                    >
                        About
                    </a>
                </nav>

                {{-- Desktop right side --}}

                <div class="hidden items-center gap-2 md:flex">
                    <div
                        class="h-7 w-px bg-white/10"
                        aria-hidden="true"
                    ></div>

                    <span class="hidden text-[10px] font-medium uppercase tracking-[0.14em] text-slate-300 xl:inline">
                        Free online tools
                    </span>
                </div>

                {{-- Mobile menu button --}}

                <div class="flex items-center gap-1 md:hidden">
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="mobile-navigation"
                        aria-label="Toggle navigation menu"
                        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg text-slate-300 transition-all hover:bg-blue-950/70 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950"
                    >
                        <svg
                            x-show="!mobileMenuOpen"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>

                        <svg
                            x-show="mobileMenuOpen"
                            x-cloak
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- =================================================
                MOBILE NAVIGATION
            ================================================== --}}

            <div
                id="mobile-navigation"
                x-show="mobileMenuOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="border-t border-white/[0.08] py-3 md:hidden"
            >
                <nav
                    class="space-y-1"
                    aria-label="Mobile navigation"
                >
                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        @class([
                            'flex h-11 cursor-pointer items-center rounded-lg px-3.5 text-[14px] font-semibold transition-colors',
                            'bg-blue-950 text-blue-100 ring-1 ring-blue-800/90' => $currentRoute === 'home',
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => $currentRoute !== 'home',
                        ])
                        @if ($currentRoute === 'home')
                            aria-current="page"
                        @endif
                    >
                        Home
                    </a>

                    <a
                        href="{{ route('tools') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        @class([
                            'flex h-11 cursor-pointer items-center rounded-lg px-3.5 text-[14px] font-semibold transition-colors',
                            'bg-blue-950 text-blue-100 ring-1 ring-blue-800/90' => $isToolsRoute,
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => ! $isToolsRoute,
                        ])
                        @if ($isToolsRoute)
                            aria-current="page"
                        @endif
                    >
                        All Tools
                    </a>

                    <div class="px-3.5 pb-1 pt-4">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                            Popular Tools
                        </p>
                    </div>

                    @foreach ($popularTools as $tool)
                        <a
                            href="{{ route('tools.tool', ['slug' => $tool->slug]) }}"
                            wire:navigate
                            @click="mobileMenuOpen = false"
                            class="flex min-h-10 cursor-pointer items-center gap-3 rounded-lg px-3.5 text-[13px] font-medium text-slate-300 transition-colors hover:bg-blue-950/60 hover:text-blue-200"
                        >
                            <span
                                aria-hidden="true"
                                class="flex h-6 w-6 shrink-0 items-center justify-center text-sm"
                            >
                                @if ($tool->icon)
                                    <img
                                        src="{{ asset($tool->icon) }}"
                                        width="36"
                                        height="36"
                                        alt="{{$tool->name}}"
                                        class="h-9 w-auto object-contain"
                                    >
                                @else
                                    <span class="text-xl">⚡</span>
                                @endif
                            </span>

                            <span class="flex-1 truncate">
                                {{ $tool->name }}
                            </span>
                        </a>
                    @endforeach

                    <div class="px-3.5 pb-1 pt-4">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                            Categories
                        </p>
                    </div>

                    @foreach ($navCategories as $category)
                        <a
                            href="{{ route('tools.category', ['slug' => $category->slug]) }}"
                            wire:navigate
                            @click="mobileMenuOpen = false"
                            class="flex min-h-10 cursor-pointer items-center gap-3 rounded-lg px-3.5 text-[13px] font-medium text-slate-300 transition-colors hover:bg-blue-950/60 hover:text-blue-200"
                        >
                            <span
                                aria-hidden="true"
                                class="flex h-6 w-6 shrink-0 items-center justify-center text-sm"
                            >
                            @if ($category->icon)

                                {{ $category->icon }}
                            @else
                                <span class="text-xl">⚡</span>
                            @endif
                            </span>

                            <span class="flex-1 truncate">
                                {{ $category->name }}
                            </span>

                            <span class="text-[10px] text-slate-500">
                                {{ $category->tools_count }}
                            </span>
                        </a>
                    @endforeach

                    <div class="my-3 border-t border-white/[0.08]"></div>

                    <a
                        href="{{ route('about') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        @class([
                            'flex h-11 cursor-pointer items-center rounded-lg px-3.5 text-[14px] font-semibold transition-colors',
                            'bg-blue-950 text-blue-100 ring-1 ring-blue-800/90' => $currentRoute === 'about',
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => $currentRoute !== 'about',
                        ])
                        @if ($currentRoute === 'about')
                            aria-current="page"
                        @endif
                    >
                        About
                    </a>

                    <a
                        href="{{ route('contact') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        @class([
                            'flex h-11 cursor-pointer items-center rounded-lg px-3.5 text-[14px] font-semibold transition-colors',
                            'bg-blue-950 text-blue-100 ring-1 ring-blue-800/90' => $currentRoute === 'contact',
                            'text-slate-300 hover:bg-blue-950/60 hover:text-white' => $currentRoute !== 'contact',
                        ])
                        @if ($currentRoute === 'contact')
                            aria-current="page"
                        @endif
                    >
                        Contact
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <main id="main-content">
        {{ $slot }}
    </main>

    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <footer class="relative overflow-hidden border-t border-slate-800 bg-slate-950 text-slate-300">
        <div
            class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-blue-600/10 blur-3xl"
            aria-hidden="true"
        ></div>

        <div
            class="pointer-events-none absolute -bottom-32 right-0 h-80 w-80 rounded-full bg-indigo-600/10 blur-3xl"
            aria-hidden="true"
        ></div>

        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

                {{-- Brand --}}

                <div class="lg:col-span-2">
                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400"
                        aria-label="AabiTech Home"
                    >
                        <img
                            src="{{ asset('images/aabitech-logo.svg') }}"
                            width="400"
                            height="100"
                            alt="AabiTech"
                            class="object-contain"
                        >
                    </a>

                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-200">
                        Free, fast and privacy-friendly online tools for developers,
                        students, writers, creators and everyday tasks.
                    </p>

                    <div class="mt-5 flex flex-wrap items-center gap-2">
                        <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-slate-400">
                            Free to use
                        </span>

                        <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-slate-400">
                            Browser-friendly
                        </span>

                        <span class="rounded-full border border-slate-800 bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-slate-400">
                            No signup required
                        </span>
                    </div>
                </div>

                {{-- Popular tools --}}

                <div>
                    <h2 class="text-sm font-semibold text-white">
                        Popular Tools
                    </h2>

                    <ul class="mt-4 space-y-2.5">
                        @forelse ($footerTools as $tool)
                            <li>
                                <a
                                    href="{{ route('tools.tool', ['slug' => $tool->slug]) }}"
                                    wire:navigate
                                    class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                                >
                                    {{ $tool->name }}
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-slate-500">
                                More tools coming soon.
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Quick links --}}

                <div>
                    <h2 class="text-sm font-semibold text-white">
                        AabiTech
                    </h2>

                    <ul class="mt-4 space-y-2.5">
                        <li>
                            <a
                                href="{{ route('home') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                Home
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('tools') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                All Tools
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('about') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                About
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('contact') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                Contact
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('privacy-policy') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                Privacy Policy
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('terms') }}"
                                wire:navigate
                                class="inline-flex cursor-pointer items-center text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                Terms of Service
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Footer bottom --}}

            <div class="mt-10 border-t border-slate-800/80 pt-6">
                <div class="flex flex-col gap-3 text-xs text-slate-200 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        &copy; {{ date('Y') }} AabiTech. All rights reserved.
                    </p>

                    <p>
                        Useful tools. Simple experience.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    {{-- =========================================================
        LIVEWIRE
    ========================================================== --}}

    @livewireScripts

    @stack('scripts')

    <x-cookie-banner />

</body>
</html>