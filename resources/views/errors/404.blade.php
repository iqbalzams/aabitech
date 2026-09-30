<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Page Not Found | AabiTech</title>

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-slate-900 antialiased dark:bg-zinc-950 dark:text-white">

    <main
        class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8"
        aria-labelledby="error-title"
    >
        <div class="w-full max-w-3xl text-center">

            {{-- Brand --}}
            <a
                href="{{ url('/') }}"
                class="mb-10 inline-flex items-center gap-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-4 dark:focus:ring-offset-zinc-950"
                aria-label="AabiTech home"
            >
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white shadow-sm"
                    aria-hidden="true"
                >
                    A
                </span>

                <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                    AabiTech
                </span>
            </a>

            {{-- 404 visual --}}
            <div class="relative mx-auto mb-8 select-none">

                <div
                    class="text-[7rem] font-black leading-none tracking-[-0.08em] text-slate-100 sm:text-[10rem] dark:text-zinc-900"
                    aria-hidden="true"
                >
                    404
                </div>

                <div class="absolute inset-0 flex items-center justify-center">

                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl border border-indigo-100 bg-white text-indigo-600 shadow-sm sm:h-20 sm:w-20 dark:border-zinc-800 dark:bg-zinc-900 dark:text-indigo-400"
                        aria-hidden="true"
                    >
                        <svg
                            class="h-8 w-8 sm:h-9 sm:w-9"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M10.5 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-6.5" />
                            <path d="M14 3h7v7" />
                            <path d="m21 3-9 9" />
                        </svg>
                    </div>

                </div>
            </div>

            {{-- Message --}}
            <h1
                id="error-title"
                class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white"
            >
                We couldn't find that page
            </h1>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base dark:text-zinc-400">
                The page you're looking for may have moved, been removed,
                or the address may be incorrect.
            </p>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">

                <a
                    href="{{ url('/') }}"
                    class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto dark:focus:ring-offset-zinc-950"
                    aria-label="Return to AabiTech homepage"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m3 10 9-7 9 7" />
                        <path d="M5 9v11h14V9" />
                        <path d="M9 20v-6h6v6" />
                    </svg>

                    Go to homepage
                </a>

                <a
                    href="{{ url('/tools') }}"
                    class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-zinc-700 dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-950"
                    aria-label="Browse AabiTech tools"
                >
                    Browse tools

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M5 12h14" />
                        <path d="m13 6 6 6-6 6" />
                    </svg>
                </a>

            </div>

            {{-- Useful destinations --}}
            <div class="mx-auto mt-12 max-w-2xl border-t border-slate-200 pt-8 dark:border-zinc-800">

                <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                    Explore AabiTech
                </p>

                <nav
                    class="grid grid-cols-1 gap-3 sm:grid-cols-3"
                    aria-label="Helpful links"
                >

                    <a
                        href="{{ url('/tools') }}"
                        class="group rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-indigo-900"
                    >
                        <span class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-800 dark:text-zinc-100">
                                All Tools
                            </span>

                            <svg
                                class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M5 12h14" />
                                <path d="m13 6 6 6-6 6" />
                            </svg>
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500 dark:text-zinc-500">
                            Browse useful online utilities
                        </span>
                    </a>

                    <a
                        href="{{ url('/about') }}"
                        class="group rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-indigo-900"
                    >
                        <span class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-800 dark:text-zinc-100">
                                About AabiTech
                            </span>

                            <svg
                                class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M5 12h14" />
                                <path d="m13 6 6 6-6 6" />
                            </svg>
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500 dark:text-zinc-500">
                            Learn more about AabiTech
                        </span>
                    </a>

                    <a
                        href="{{ url('/contact') }}"
                        class="group rounded-xl border border-slate-200 bg-white px-4 py-4 text-left transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-indigo-900"
                    >
                        <span class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-800 dark:text-zinc-100">
                                Contact Us
                            </span>

                            <svg
                                class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M5 12h14" />
                                <path d="m13 6 6 6-6 6" />
                            </svg>
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500 dark:text-zinc-500">
                            Report a broken page or get help
                        </span>
                    </a>

                </nav>
            </div>

            <p class="mt-8 text-xs text-slate-400 dark:text-zinc-600">
                AabiTech · Simple tools for everyday digital work
            </p>

        </div>
    </main>

</body>
</html>