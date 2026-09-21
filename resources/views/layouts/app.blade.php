<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <x-seo :seo="$seo ?? []" />

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @livewireStyles

</head>

</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <header
        x-data="{ mobileMenuOpen: false }"
        class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex h-16 items-center justify-between">

                {{-- Logo --}}
                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-2"
                    aria-label="AabiTech Home"
                >

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white shadow-sm"
                    >
                        A
                    </span>

                    <span class="text-xl font-bold tracking-tight text-slate-900">
                        Aabi<span class="text-indigo-600">Tech</span>
                    </span>

                </a>

                {{-- Desktop Navigation --}}
                <nav
                    class="hidden items-center gap-8 md:flex"
                    aria-label="Main navigation"
                >

                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Home
                    </a>

                    <a
                        href="{{ url('/tools') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Tools
                    </a>

                    <a
                        href="{{ url('/categories') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Categories
                    </a>

                    <a
                        href="{{ url('/about') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        About
                    </a>

                </nav>

                {{-- Mobile Menu Button --}}
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-controls="mobile-navigation"
                    aria-label="Toggle navigation"
                >

                    <svg
                        x-show="!mobileMenuOpen"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>

                    <svg
                        x-show="mobileMenuOpen"
                        x-cloak
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>

            {{-- Mobile Navigation --}}
            <div
                id="mobile-navigation"
                x-show="mobileMenuOpen"
                x-cloak
                x-transition
                class="border-t border-slate-200 py-4 md:hidden"
            >

                <nav
                    class="flex flex-col gap-1"
                    aria-label="Mobile navigation"
                >

                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Home
                    </a>

                    <a
                        href="{{ url('/tools') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Tools
                    </a>

                    <a
                        href="{{ url('/categories') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        Categories
                    </a>

                    <a
                        href="{{ url('/about') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                    >
                        About
                    </a>

                </nav>

            </div>

        </div>

    </header>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <main>
        {{ $slot }}
    </main>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <footer class="border-t border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

            <div class="grid gap-10 md:grid-cols-4">

                {{-- Brand --}}
                <div class="md:col-span-2">

                    <a
                        href="{{ route('home') }}"
                        class="inline-flex items-center gap-2"
                    >

                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white"
                        >
                            A
                        </span>

                        <span class="text-xl font-bold">
                            Aabi<span class="text-indigo-600">Tech</span>
                        </span>

                    </a>

                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-600">
                        Fast, free and easy-to-use online tools for developers,
                        students, creators and everyday digital tasks.
                    </p>

                </div>


                {{-- Tools --}}
                <div>

                    <h2 class="text-sm font-semibold text-slate-900">
                        Tools
                    </h2>

                    <ul class="mt-4 space-y-3 text-sm">

                        <li>
                            <a
                                href="{{ url('/tools/json-formatter') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                JSON Formatter
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ url('/tools/word-counter') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                Word Counter
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ url('/tools/image-resizer') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                Image Resizer
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ url('/tools/image-compressor') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                Image Compressor
                            </a>
                        </li>

                    </ul>

                </div>


                {{-- Company --}}
                <div>

                    <h2 class="text-sm font-semibold text-slate-900">
                        AabiTech
                    </h2>

                    <ul class="mt-4 space-y-3 text-sm">

                        <li>
                            <a
                                href="{{ url('/tools') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                All Tools
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ url('/categories') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                Categories
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ url('/about') }}"
                                class="text-slate-600 hover:text-indigo-600"
                            >
                                About
                            </a>
                        </li>

                    </ul>

                </div>

            </div>


            <div class="mt-10 border-t border-slate-200 pt-8">

                <p class="text-center text-sm text-slate-500">
                    © {{ date('Y') }} AabiTech. All rights reserved.
                </p>

            </div>

        </div>

    </footer>


    @livewireScripts

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>
</html>