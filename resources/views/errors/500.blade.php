<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    <meta
        name="theme-color"
        content="#0f172a"
    >

    <title>Something Went Wrong | AabiTech</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    <main class="flex min-h-screen items-center justify-center px-5 py-12 sm:px-6">
        <div class="w-full max-w-xl text-center">

            {{-- Brand --}}
            <a
                href="{{ url('/') }}"
                class="mb-10 inline-flex items-center gap-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                aria-label="AabiTech home"
            >
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm"
                    aria-hidden="true"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 2.8 5.7 13h5.1l-.8 8.2L18.3 11h-5.1L13 2.8Z"
                        />
                    </svg>
                </span>

                <span class="text-lg font-bold tracking-tight text-slate-900">
                    Aabi<span class="text-indigo-600">Tech</span>
                </span>
            </a>

            {{-- Error visual --}}
            <div
                class="mx-auto mb-7 flex h-24 w-24 items-center justify-center rounded-3xl border border-amber-200 bg-amber-50 text-amber-600 shadow-sm"
                aria-hidden="true"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    class="h-11 w-11"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3.5 21 19H3L12 3.5Z"
                    />

                    <path
                        stroke-linecap="round"
                        d="M12 9v4.2"
                    />

                    <path
                        stroke-linecap="round"
                        d="M12 16.5h.01"
                    />
                </svg>
            </div>

            {{-- Status --}}
            <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-indigo-600">
                Error 500
            </p>

            <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Something went wrong
            </h1>

            <p class="mx-auto mt-4 max-w-lg text-sm leading-6 text-slate-600 sm:text-base">
                We couldn't complete your request right now. The problem may be
                temporary, so please try again or return to AabiTech and continue
                using the available tools.
            </p>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">

                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="inline-flex h-10 w-full cursor-pointer items-center justify-center rounded-lg bg-indigo-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                >
                    Try again
                </button>

                <a
                    href="{{ url('/') }}"
                    class="inline-flex h-10 w-full cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                >
                    Go to homepage
                </a>

                <a
                    href="{{ url('/tools') }}"
                    class="inline-flex h-10 w-full cursor-pointer items-center justify-center rounded-lg px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                >
                    Browse tools
                </a>
            </div>

            {{-- Help --}}
            <div class="mt-10 border-t border-slate-200 pt-6">
                <p class="text-xs leading-5 text-slate-500">
                    If the problem continues, please
                    <a
                        href="{{ url('/contact') }}"
                        class="font-medium text-indigo-600 underline decoration-indigo-600/30 underline-offset-2 hover:text-indigo-700"
                    >
                        contact AabiTech
                    </a>
                    and let us know what happened.
                </p>
            </div>

            {{-- Small footer --}}
            <p class="mt-6 text-xs text-slate-400">
                &copy; {{ date('Y') }} AabiTech. All rights reserved.
            </p>

        </div>
    </main>

</body>
</html>