<?php

use App\Models\Category;
use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    public function getCategoriesProperty()
    {
        $search = trim($this->search);

        return Category::query()
            ->where('status', true)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->with([
                'tools' => function ($query) use ($search) {
                    $query
                        ->where('status', true)
                        ->when(
                            mb_strlen($search) >= 2,
                            function ($query) use ($search) {
                                $query->where(function ($query) use ($search) {
                                    $query
                                        ->where('name', 'like', "%{$search}%")
                                        ->orWhere(
                                            'short_description',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'description',
                                            'like',
                                            "%{$search}%"
                                        );
                                });
                            }
                        )
                        ->orderBy('sort_order')
                        ->orderBy('name');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getTotalToolsProperty(): int
    {
        return Tool::query()
            ->where('status', true)
            ->count();
    }

    public function getVisibleToolsProperty(): int
    {
        return $this->categories->sum(
            fn ($category) => $category->tools->count()
        );
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }
};
?>

<div class="min-h-screen bg-slate-50">

    {{-- ============================================================
        HERO
    ============================================================= --}}

    <section class="border-b border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

            {{-- Breadcrumb --}}
            <nav
                aria-label="Breadcrumb"
                class="mb-8 flex items-center gap-2 text-sm text-slate-500"
            >
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="transition-colors hover:text-indigo-600"
                >
                    Home
                </a>

                <svg
                    class="h-4 w-4 text-slate-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.24 4.24a.75.75 0 010 1.06l-4.24 4.24a.75.75 0 01-1.08 0z"
                        clip-rule="evenodd"
                    />
                </svg>

                <span class="font-medium text-slate-700">
                    Tools
                </span>
            </nav>

            <div class="grid items-end gap-10 lg:grid-cols-[1fr_320px]">

                {{-- Main introduction --}}
                <div class="max-w-3xl">

                    <div class="mb-5 flex items-center gap-4">

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 text-2xl text-indigo-600"
                            aria-hidden="true"
                        >
                            ⚡
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">
                                {{ $this->totalTools }}
                                {{ $this->totalTools === 1 ? 'tool' : 'tools' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Free online utilities from AabiTech
                            </p>
                        </div>

                    </div>

                    <h1 class="text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Free Online Tools
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">
                        Use practical online tools for development, writing,
                        design, calculations, security, date and time tasks,
                        and other everyday digital work.
                    </p>

                </div>

                {{-- Summary --}}
                <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        AabiTech collection
                    </p>

                    <div class="mt-5 flex items-end gap-3">
                        <span class="text-4xl font-bold tracking-tight text-slate-950">
                            {{ $this->totalTools }}
                        </span>

                        <span class="pb-1 text-sm text-slate-500">
                            {{ $this->totalTools === 1 ? 'tool' : 'tools' }}
                        </span>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Browse the collection below or search by tool name,
                        task, or description.
                    </p>

                </aside>

            </div>

        </div>

    </section>


    {{-- ============================================================
        TOOL DIRECTORY
    ============================================================= --}}

    <section
        id="tools"
        class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16"
    >

        {{-- Directory introduction --}}
        <div class="max-w-3xl">

            <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Browse Online Tools
            </h2>

            <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">
                Explore the available AabiTech tools by category, or search
                for the specific task you want to complete.
            </p>

        </div>


        {{-- ========================================================
            SEARCH
        ========================================================= --}}

        <div class="mt-8 max-w-2xl">

            <label
                for="tools-search"
                class="sr-only"
            >
                Search AabiTech tools
            </label>

            <div class="relative">

                <svg
                    class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M9 3.5a5.5 5.5 0 104.31 8.92l2.64 2.64a.75.75 0 101.06-1.06l-2.64-2.64A5.5 5.5 0 009 3.5zM5 9a4 4 0 118 0 4 4 0 01-8 0z"
                        clip-rule="evenodd"
                    />
                </svg>

                <input
                    id="tools-search"
                    type="search"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search tools..."
                    autocomplete="off"
                    class="h-12 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                />

                @if ($search)
                    <button
                        type="button"
                        wire:click="clearSearch"
                        aria-label="Clear search"
                        class="absolute right-3 top-1/2 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.22 4.22a.75.75 0 011.06 0L10 8.94l4.72-4.72a.75.75 0 111.06 1.06L11.06 10l4.72 4.72a.75.75 0 11-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 01-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 010-1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                @endif

            </div>

        </div>


        {{-- Search result information --}}
        @if (mb_strlen(trim($search)) >= 2)

            <div class="mt-6 flex flex-col gap-3 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">

                <p class="text-sm text-indigo-900">
                    <span class="font-semibold">
                        {{ $this->visibleTools }}
                    </span>

                    {{ $this->visibleTools === 1 ? 'tool' : 'tools' }}
                    found for

                    <span class="font-semibold">
                        “{{ $search }}”
                    </span>
                </p>

                <button
                    type="button"
                    wire:click="clearSearch"
                    class="self-start text-xs font-semibold text-indigo-700 transition hover:text-indigo-900 sm:self-auto"
                >
                    Clear search
                </button>

            </div>

        @endif


        {{-- ========================================================
            CATEGORY QUICK NAVIGATION
        ========================================================= --}}

        @if ($this->categories->isNotEmpty() && mb_strlen(trim($search)) < 2)

            <nav
                aria-label="Tool categories"
                class="mt-8 flex flex-wrap gap-2"
            >

                @foreach ($this->categories as $category)

                    <a
                        href="#category-{{ $category->slug }}"
                        class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
                    >
                        <span aria-hidden="true">
                            {{ $category->icon ?: '⚡' }}
                        </span>

                        <span>
                            {{ $category->name }}
                        </span>

                        <span class="text-slate-400">
                            {{ $category->tools_count }}
                        </span>
                    </a>

                @endforeach

            </nav>

        @endif


        {{-- ========================================================
            ALL CATEGORIES / TOOLS
        ========================================================= --}}

        <div class="mt-12 space-y-16">

            @forelse ($this->categories as $category)

                @if ($category->tools->isNotEmpty())

                    <section
                        id="category-{{ $category->slug }}"
                        aria-labelledby="category-heading-{{ $category->slug }}"
                        class="scroll-mt-24"
                    >

                        {{-- Category heading --}}
                        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                            <div class="max-w-3xl">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-lg"
                                        aria-hidden="true"
                                    >
                                        {{ $category->icon ?: '⚡' }}
                                    </div>

                                    <div>

                                        <h2
                                            id="category-heading-{{ $category->slug }}"
                                            class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl"
                                        >
                                            {{ $category->name }}
                                        </h2>

                                        <p class="mt-1 text-xs font-medium text-slate-400">
                                            {{ $category->tools->count() }}
                                            {{ $category->tools->count() === 1 ? 'tool' : 'tools' }}
                                        </p>

                                    </div>

                                </div>

                                @if ($category->short_description)

                                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600">
                                        {{ $category->short_description }}
                                    </p>

                                @endif

                            </div>

                            <a
                                href="{{ route('tools.category', ['slug' => $category->slug]) }}"
                                wire:navigate
                                class="inline-flex shrink-0 items-center gap-1.5 text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                            >
                                View category

                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06l-4.25-4.25a.75.75 0 10-1.06 1.06L10.94 10l-3.72 3.72a.75.75 0 000 1.06z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>

                        </div>


                        {{-- Tools --}}
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                            @foreach ($category->tools as $tool)

                                <a
                                    href="{{ route('tools.tool', ['slug' => $tool->slug]) }}"
                                    wire:navigate
                                    wire:key="directory-tool-{{ $tool->id }}"
                                    class="group flex min-h-[190px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md"
                                >

                                    <div class="flex items-start justify-between gap-4">

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-lg text-slate-700 transition group-hover:border-indigo-100 group-hover:bg-indigo-50 group-hover:text-indigo-600"
                                        >

                                            @if ($tool->icon)

                                                <img
                                                    src="{{ asset($tool->icon) }}"
                                                    alt="{{ $tool->name }}"
                                                    loading="lazy"
                                                    width="36"
                                                    height="36"
                                                    class="h-9 w-auto object-contain"
                                                >

                                            @else

                                                <span
                                                    class="text-xl"
                                                    aria-hidden="true"
                                                >
                                                    ⚡
                                                </span>

                                            @endif

                                        </div>

                                        <span class="inline-flex shrink-0 items-center rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-700">
                                            Online tool
                                        </span>

                                    </div>

                                    <h3 class="mt-5 text-lg font-semibold leading-6 text-slate-950 transition group-hover:text-indigo-600">
                                        {{ $tool->name }}
                                    </h3>

                                    @if ($tool->short_description)

                                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                                            {{ $tool->short_description }}
                                        </p>

                                    @elseif ($tool->description)

                                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                                            {{ $tool->description }}
                                        </p>

                                    @endif

                                    <div class="mt-auto flex items-center gap-1 pt-5 text-xs font-semibold text-indigo-600">
                                        Use tool

                                        <svg
                                            class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06l-4.25-4.25a.75.75 0 10-1.06 1.06L10.94 10l-3.72 3.72a.75.75 0 000 1.06z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>

                                </a>

                            @endforeach

                        </div>

                    </section>

                @endif

            @empty

                {{-- No categories --}}
                <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center">

                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-xl"
                        aria-hidden="true"
                    >
                        🛠️
                    </div>

                    <h2 class="mt-5 text-xl font-semibold text-slate-950">
                        Tools are coming soon
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                        We're preparing useful free online utilities for you.
                    </p>

                </div>

            @endforelse


            {{-- No search results --}}
            @if (
                mb_strlen(trim($search)) >= 2 &&
                $this->visibleTools === 0
            )

                <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center">

                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-xl"
                        aria-hidden="true"
                    >
                        🔎
                    </div>

                    <h2 class="mt-5 text-xl font-semibold text-slate-950">
                        No tools found
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                        We couldn't find a tool matching
                        “{{ $search }}”.
                        Try another search term.
                    </p>

                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="mt-6 inline-flex h-9 items-center justify-center rounded-lg bg-slate-950 px-4 text-xs font-semibold text-white transition hover:bg-slate-800"
                    >
                        Show all tools
                    </button>

                </div>

            @endif

        </div>

    </section>


    {{-- ============================================================
        PRIVACY / PROCESSING
    ============================================================= --}}

    <section class="bg-slate-950 text-white">

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

            <div class="grid gap-10 lg:grid-cols-[1fr_420px] lg:items-center">

                <div class="max-w-3xl">

                    <div class="flex items-center gap-2 text-sm font-semibold text-emerald-400">

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 1.75a.75.75 0 01.75.75v.72a7.5 7.5 0 015.78 7.28v1.68a3.75 3.75 0 001.22 2.77.75.75 0 01-.5 1.31H2.75a.75.75 0 01-.5-1.31 3.75 3.75 0 001.22-2.77V10.5a7.5 7.5 0 01-5.78-7.28V2.5a.75.75 0 01.75-.75z"
                                clip-rule="evenodd"
                            />
                        </svg>

                        Browser-based processing

                    </div>

                    <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                        Keep simple tasks on your device when possible.
                    </h2>

                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        Many AabiTech utilities are designed to process input
                        directly in your browser. For supported tools, this can
                        allow text, code, numbers and other information to be
                        handled locally on your device.
                    </p>

                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-400">
                        Processing behavior can vary between utilities. Check
                        the individual tool page for information about how
                        that particular utility handles your data.
                    </p>

                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">

                    <div class="space-y-5">

                        <div class="flex gap-4">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-400/10 text-emerald-400"
                                aria-hidden="true"
                            >
                                ✓
                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-white">
                                    Runs locally when supported
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    Browser-based utilities can process your
                                    input directly on your device.
                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-400/10 text-indigo-400"
                                aria-hidden="true"
                            >
                                ↗
                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-white">
                                    No unnecessary upload
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    Local processing can avoid sending input
                                    to a server when server processing is not required.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        FINAL NAVIGATION
    ============================================================= --}}

    <section class="border-t border-slate-200 bg-white">

        <div class="mx-auto max-w-4xl px-4 py-14 text-center sm:px-6 lg:px-8 lg:py-16">

            <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Find the right tool for your task
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                Browse the complete AabiTech collection or explore a category
                to discover more specialized utilities.
            </p>

            <div class="mt-7">

                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                >
                    Back to AabiTech
                </a>

            </div>

        </div>

    </section>

</div>