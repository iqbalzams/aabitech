<?php

use App\Models\Category;
use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function getCategoriesProperty()
    {
        return Category::query()
            ->where('status', true)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->orderBy('sort_order')
            ->get();
    }

    public function getFeaturedToolsProperty()
    {
        return Tool::query()
            ->where('status', true)
            ->where('is_featured', true)
            ->with('category')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();
    }

    public function getPopularToolsProperty()
    {
        return Tool::query()
            ->where('status', true)
            ->where('is_popular', true)
            ->with('category')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();
    }

    public function getSearchResultsProperty()
    {
        if (mb_strlen($this->search) < 2) {
            return collect();
        }

        $search = addcslashes($this->search, '%_\\');
        $like = "%{$search}%";
        $prefix = "{$search}%";

        return Tool::query()
            ->where('status', true)
            ->where(function ($query) use ($like) {
                $query
                    ->where('name', 'like', $like)
                    ->orWhere('short_description', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->with('category')
            ->orderByRaw(
                "CASE
                    WHEN name LIKE ? THEN 0
                    WHEN name LIKE ? THEN 1
                    ELSE 2
                END",
                [$search, $prefix]
            )
            ->limit(8)
            ->get();
    }
};

?>

<div class="min-h-screen bg-slate-50">

    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="relative overflow-hidden border-b border-slate-200 bg-white">
        <div
            class="pointer-events-none absolute inset-0"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.06)_1px,transparent_1px)] bg-[size:48px_48px]"></div>
            <div class="absolute left-1/2 top-[-180px] h-[600px] w-[1000px] -translate-x-1/2 rounded-full bg-indigo-100/50 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-4xl text-center">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3.5 py-2 text-xs font-semibold text-indigo-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                    Free online tools
                </div>

                <h1 class="mx-auto max-w-4xl text-5xl font-bold tracking-[-0.035em] text-slate-950 sm:text-6xl lg:text-7xl lg:leading-[1.04]">
                    Free Online Tools for Everyday Digital Work
                </h1>

                <p class="mx-auto mt-7 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl sm:leading-9">
                    AabiTech brings useful online tools for developers, students,
                    writers, designers and everyday users into one simple place.
                    Format data, transform text, calculate values and handle
                    common digital tasks directly from your browser.
                </p>

                {{-- Search --}}

                <div class="mx-auto mt-10 max-w-2xl">
                    <label for="tool-search" class="sr-only">
                        Search AabiTech tools
                    </label>

                    <div class="relative">
                        <svg
                            class="pointer-events-none absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>

                        <input
                            id="tool-search"
                            type="search"
                            wire:model.live.debounce.250ms="search"
                            placeholder="Search for a tool..."
                            autocomplete="off"
                            class="h-16 w-full rounded-2xl border border-slate-300 bg-white pl-14 pr-12 text-base text-slate-900 shadow-lg shadow-slate-200/50 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
                        />

                        @if ($search)
                            <button
                                type="button"
                                wire:click="clearSearch"
                                class="absolute right-4 top-1/2 inline-flex h-8 w-8 -translate-y-1/2 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                aria-label="Clear search"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path d="M6 6l12 12M18 6 6 18"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    @if (mb_strlen($search) >= 2)
                        <div class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-xl">
                            @forelse ($this->searchResults as $tool)
                                <a
                                    href="{{ route('tools.tool', $tool->slug) }}"
                                    wire:navigate
                                    class="flex items-center gap-4 border-b border-slate-100 px-5 py-4 transition last:border-0 hover:bg-slate-50"
                                >
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-600">
                                        {{ $tool->category?->icon ?? '◆' }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $tool->name }}
                                        </div>

                                        <div class="mt-1 truncate text-xs text-slate-500">
                                            {{ $tool->short_description }}
                                        </div>
                                    </div>

                                    <span class="hidden text-xs font-semibold text-indigo-600 sm:block">
                                        Open →
                                    </span>
                                </a>
                            @empty
                                <div class="px-5 py-8 text-center">
                                    <p class="text-sm font-semibold text-slate-800">
                                        No matching tools found
                                    </p>

                                    <p class="mt-1.5 text-sm text-slate-500">
                                        Try another search term or browse the complete tool collection.
                                    </p>
                                </div>
                            @endforelse

                            @if ($this->searchResults->isNotEmpty())
                                <div class="border-t border-slate-100 bg-slate-50 px-5 py-3.5">
                                    <a
                                        href="{{ route('tools') }}"
                                        wire:navigate
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-700"
                                    >
                                        Browse all tools →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Popular searches --}}

                <div class="mt-6 flex flex-wrap items-center justify-center gap-2.5 text-sm">
                    <span class="text-slate-400">
                        Try:
                    </span>

                    @foreach ([
                        'JSON Formatter' => 'json-formatter',
                        'Word Counter' => 'word-counter',
                        'Base64 Encoder' => 'base64-encoder-decoder',
                        'Percentage Calculator' => 'percentage-calculator',
                    ] as $label => $slug)
                        <a
                            href="{{ route('tools.tool', $slug) }}"
                            wire:navigate
                            class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
         INTRODUCTION / CONTENT
    ========================================================== --}}

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">

                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                    One place for everyday digital tasks
                </p>

                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Practical tools without unnecessary complexity
                </h2>

                <div class="mt-7 space-y-5 text-lg leading-8 text-slate-600">
                    <p>
                        Small digital tasks can interrupt your workflow. You may need
                        to format a JSON response, count words in an assignment,
                        encode a value, calculate a percentage, create a URL slug,
                        test a regular expression or convert a timestamp.
                    </p>

                    <p>
                        AabiTech keeps these tasks focused and straightforward.
                        Each utility is built around a specific job, with a simple
                        interface that works directly in a modern web browser.
                    </p>

                    <p>
                        Browse by category or search for a tool when you need it.
                        The collection covers developer utilities, text tools,
                        calculators, design helpers, security tools and date and
                        time utilities.
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
         FEATURED TOOLS
    ========================================================== --}}

    <section class="bg-slate-50 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                    Start with a useful tool
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Featured Online Tools
                </h2>

                <p class="mt-4 text-lg leading-8 text-slate-600">
                    Explore useful utilities from the AabiTech collection.
                    Each tool is designed to help with a specific task without
                    unnecessary setup or complicated steps.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($this->featuredTools as $tool)
                    <a
                        href="{{ route('tools.tool', $tool->slug) }}"
                        wire:navigate
                        class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:shadow-slate-200/50"
                    >
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-sm text-indigo-600">
                            {{ $tool->category?->icon ?? '◆' }}
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-slate-900 group-hover:text-indigo-700">
                            {{ $tool->name }}
                        </h3>

                        <p class="mt-2.5 text-sm leading-6 text-slate-500">
                            {{ $tool->short_description }}
                        </p>

                        <span class="mt-5 inline-flex text-sm font-semibold text-indigo-600">
                            Use tool →
                        </span>
                    </a>
                @endforeach
            </div>

        </div>
    </section>


    {{-- =========================================================
         CATEGORIES + EXPLANATORY CONTENT
    ========================================================== --}}

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">

                <div class="max-w-xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                        Browse the collection
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Find tools by category
                    </h2>

                    <div class="mt-6 space-y-5 text-base leading-7 text-slate-600">
                        <p>
                            Different tasks need different kinds of utilities.
                            Categories make it easier to find a useful tool without
                            searching through unrelated results.
                        </p>

                        <p>
                            Developers can work with data, URLs, HTML, regular
                            expressions and tokens. Students and writers can work
                            with text, while designers and other users can choose
                            from calculators, design and date-related utilities.
                        </p>

                        <p>
                            Select a category to explore the tools available in it.
                        </p>
                    </div>
                </div>

                <div class="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-slate-50">
                    @foreach ($this->categories as $category)
                        <a
                            href="{{ route('tools.category', $category->slug) }}"
                            wire:navigate
                            class="group flex items-center gap-5 px-5 py-5 transition first:rounded-t-2xl last:rounded-b-2xl hover:bg-white"
                        >
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-sm text-slate-600 shadow-sm ring-1 ring-slate-200 group-hover:text-indigo-600">
                                {{ $category->icon ?? '◆' }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-semibold text-slate-900 group-hover:text-indigo-700">
                                    {{ $category->name }}
                                </h3>

                                @if ($category->short_description)
                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        {{ $category->short_description }}
                                    </p>
                                @endif
                            </div>

                            <span class="hidden shrink-0 text-xs font-medium text-slate-400 sm:block">
                                {{ $category->tools_count }}
                                {{ $category->tools_count === 1 ? 'tool' : 'tools' }}
                            </span>

                            <svg
                                class="h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
         PRIVACY-FIRST CONTENT
    ========================================================== --}}

    <section class="border-y border-slate-800 bg-slate-950 py-20 text-white sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-12 lg:grid-cols-[1fr_0.75fr] lg:items-center">

                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-300">
                        Privacy-first design
                    </p>

                    <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                        Your data should not leave your browser when it does not need to.
                    </h2>

                    <div class="mt-7 space-y-5 text-base leading-8 text-slate-300 sm:text-lg">
                        <p>
                            AabiTech follows a browser-first approach for tools that
                            can perform their work locally. When an operation can be
                            completed in your browser, the input does not need to be
                            sent to a remote server simply to perform that operation.
                        </p>

                        <p>
                            This approach can be useful when working with text, code,
                            numbers or other information that you prefer to keep on
                            your device.
                        </p>

                        <p class="text-sm leading-7 text-slate-400">
                            Processing can vary between tools. Tools that require
                            server-side processing, external services or AI features
                            may handle information differently. The individual tool
                            page should explain those requirements where applicable.
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-7">
                    <h3 class="text-lg font-semibold text-white">
                        Browser-based processing
                    </h3>

                    <div class="mt-6 space-y-6">
                        <div>
                            <p class="text-sm font-semibold text-white">
                                Process locally
                            </p>

                            <p class="mt-1.5 text-sm leading-6 text-slate-400">
                                Supported tools can perform their calculations or
                                transformations inside your browser.
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Avoid unnecessary transfers
                            </p>

                            <p class="mt-1.5 text-sm leading-6 text-slate-400">
                                Local processing does not require your input to be
                                uploaded simply to complete the operation.
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Clear information
                            </p>

                            <p class="mt-1.5 text-sm leading-6 text-slate-400">
                                Tool-specific processing details should explain when
                                local processing is not available.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- =========================================================
         POPULAR TOOLS
    ========================================================== --}}

    <section class="bg-slate-50 py-20 sm:py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                    Frequently used
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Popular AabiTech Tools
                </h2>

                <p class="mt-4 text-lg leading-8 text-slate-600">
                    Quickly access commonly used utilities for development,
                    writing, calculations and other everyday digital tasks.
                </p>
            </div>

            <div class="mt-9 divide-y divide-slate-200 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                @foreach ($this->popularTools as $tool)
                    <a
                        href="{{ route('tools.tool', $tool->slug) }}"
                        wire:navigate
                        class="group flex items-center gap-4 px-5 py-5 transition hover:bg-slate-50 sm:px-6"
                    >
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600">
                            {{ $tool->category?->icon ?? '◆' }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-semibold text-slate-900 group-hover:text-indigo-700">
                                {{ $tool->name }}
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                {{ $tool->short_description }}
                            </p>
                        </div>

                        <span class="hidden shrink-0 text-xs font-medium text-slate-400 md:block">
                            {{ $tool->category?->name }}
                        </span>

                        <span class="shrink-0 text-sm font-semibold text-indigo-600">
                            Open →
                        </span>
                    </a>
                @endforeach
            </div>

        </div>
    </section>


    {{-- =========================================================
         HOW IT WORKS
    ========================================================== --}}

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                    Simple workflow
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Get the job done in a few seconds
                </h2>

                <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    AabiTech tools are designed to stay focused. For simple tasks,
                    you can use a utility without creating an account or learning
                    a complicated workflow.
                </p>
            </div>

            <div class="mt-12 grid gap-10 md:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-base font-bold text-white">
                        1
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-900">
                        Find your tool
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Search for a specific utility or browse the available categories.
                    </p>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-base font-bold text-white">
                        2
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-900">
                        Enter your information
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Provide the text, code, numbers or other input required by the tool.
                    </p>
                </div>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-base font-bold text-white">
                        3
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-900">
                        Use the result
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Copy, download or use the result in your work.
                    </p>
                </div>
            </div>

        </div>
    </section>


    {{-- =========================================================
         DETAILED SEO / INFORMATIONAL CONTENT
    ========================================================== --}}

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <article class="space-y-10">

                <header>
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                        About the collection
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Free online utilities for modern digital work
                    </h2>
                </header>

                <section>
                    <h3 class="text-2xl font-bold tracking-tight text-slate-900">
                        Developer tools
                    </h3>

                    <div class="mt-4 space-y-4 text-base leading-8 text-slate-600">
                        <p>
                            Developers often need small utilities while working with
                            websites, applications and APIs. AabiTech includes tools
                            for JSON formatting, Base64 encoding and decoding, URL
                            encoding and decoding, HTML beautification, regular
                            expression testing and JWT decoding.
                        </p>

                        <p>
                            These tools are built for common tasks that are easier to
                            handle with a focused browser utility than with a separate
                            desktop application.
                        </p>
                    </div>
                </section>

                <section>
                    <h3 class="text-2xl font-bold tracking-tight text-slate-900">
                        Text and writing tools
                    </h3>

                    <div class="mt-4 space-y-4 text-base leading-8 text-slate-600">
                        <p>
                            Text tools can help with everyday writing, study and
                            content tasks. Word and character counters can check
                            document length, while reading-time calculations provide
                            a quick estimate for longer text.
                        </p>

                        <p>
                            Other utilities can remove duplicate lines, generate
                            URL-friendly slugs or create placeholder text for
                            projects and layouts.
                        </p>
                    </div>
                </section>

                <section>
                    <h3 class="text-2xl font-bold tracking-tight text-slate-900">
                        Calculators and practical utilities
                    </h3>

                    <div class="mt-4 space-y-4 text-base leading-8 text-slate-600">
                        <p>
                            Some tasks are simple but still need a reliable result.
                            Percentage, age, random number, timestamp and aspect-ratio
                            calculators can handle these calculations without requiring
                            specialized software.
                        </p>

                        <p>
                            The goal is simple: enter the required values, get the
                            result and continue with your work.
                        </p>
                    </div>
                </section>

                <section>
                    <h3 class="text-2xl font-bold tracking-tight text-slate-900">
                        Browser-first processing
                    </h3>

                    <div class="mt-4 space-y-4 text-base leading-8 text-slate-600">
                        <p>
                            When a task can be completed entirely in the browser,
                            local processing can avoid sending the input to a remote
                            server. This can be useful for text, code and other
                            information that you prefer to keep on your device.
                        </p>

                        <p>
                            Processing depends on the individual tool. Utilities that
                            use server-side processing, external services or AI
                            features may work differently, and those requirements
                            should be explained on the relevant tool page.
                        </p>
                    </div>
                </section>

            </article>
        </div>
    </section>


    {{-- =========================================================
         FAQ
    ========================================================== --}}

    <section class="bg-slate-50 py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">
                    Frequently asked questions
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Questions about AabiTech tools
                </h2>
            </div>

            <div class="mt-10 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">

                <details class="group p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">
                        Are AabiTech tools free?

                        <span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        AabiTech's online tools are free to use through a modern web
                        browser. They are designed to provide quick access to useful
                        digital utilities without requiring software installation.
                    </p>
                </details>

                <details class="group p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">
                        Do AabiTech tools upload my data?

                        <span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        Many AabiTech tools are designed to process input directly
                        in the browser and therefore do not need to upload it to a
                        server. Processing can vary between tools, so check the
                        individual tool page for details about how that utility works.
                    </p>
                </details>

                <details class="group p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">
                        What types of tools are available?

                        <span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        AabiTech includes developer tools, text tools, design
                        utilities, security-related tools, calculators and date
                        and time utilities. The collection will continue to grow
                        as new tools are added.
                    </p>
                </details>

                <details class="group p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">
                        Do I need to install software?

                        <span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        No. AabiTech tools are available through your web browser.
                        For browser-based utilities, processing can take place
                        directly in the browser without installing a separate
                        desktop application.
                    </p>
                </details>

                <details class="group p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">
                        Who are AabiTech tools for?

                        <span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        The collection is intended for developers, students, writers,
                        designers, content creators and anyone who needs to complete
                        a small digital task quickly.
                    </p>
                </details>

            </div>
        </div>
    </section>


    {{-- =========================================================
         FINAL CTA
    ========================================================== --}}

    <section class="border-t border-slate-200 bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">

            <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Find the tool you need
            </h2>

            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                Search the AabiTech collection or explore tools by category.
                Use a focused utility when you need to complete a task quickly.
            </p>

            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a
                    href="{{ route('tools') }}"
                    wire:navigate
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Browse all tools
                </a>

                <button
                    type="button"
                    onclick="document.getElementById('tool-search')?.focus()"
                    class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                >
                    Search tools
                </button>
            </div>

        </div>
    </section>

</div>