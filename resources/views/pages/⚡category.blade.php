<?php

use App\Models\Category;
use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $slug = '';

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;

        if (! $this->category) {
            abort(404);
        }
    }

    public function getCategoryProperty(): ?Category
    {
        return Category::query()
            ->where('slug', $this->slug)
            ->where('status', true)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->first();
    }

    public function getToolsProperty()
    {
        if (! $this->category) {
            return collect();
        }

        $query = Tool::query()
            ->where('category_id', $this->category->id)
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name');

        $search = trim($this->search);

        if (mb_strlen($search) >= 2) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function getRelatedCategoriesProperty()
    {
        if (! $this->category) {
            return collect();
        }

        return Category::query()
            ->where('status', true)
            ->whereKeyNot($this->category->id)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->get();
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }
};

?>

<div class="min-h-screen bg-slate-50">

    {{-- ============================================================
        CATEGORY HERO
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

                <a
                    href="{{ route('tools') }}"
                    wire:navigate
                    class="transition-colors hover:text-indigo-600"
                >
                    Tools
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
                    {{ $this->category->name }}
                </span>
            </nav>

            <div class="grid items-center gap-10 lg:grid-cols-[1fr_320px]">

                {{-- Category introduction --}}
                <div class="max-w-3xl">

                    <div class="mb-5 flex items-center gap-4">

                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 text-2xl text-indigo-600"
                            aria-hidden="true"
                        >
                            {{ $this->category->icon ?: '🛠️' }}
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">
                                {{ $this->category->tools_count }}
                                {{ $this->category->tools_count === 1 ? 'tool' : 'tools' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Free online tools from AabiTech
                            </p>
                        </div>

                    </div>

                    <h1 class="text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        {{ $this->category->name }}
                    </h1>

                    @if ($this->category->short_description)
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">
                            {{ $this->category->short_description }}
                        </p>
                    @elseif ($this->category->description)
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">
                            {{ $this->category->description }}
                        </p>
                    @else
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 sm:text-xl">
                            Free online tools for everyday tasks, with simple interfaces and useful results right in your browser.
                        </p>
                    @endif

                </div>

                {{-- Category summary --}}
                <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Available tools
                    </p>

                    <div class="mt-5 flex items-end gap-3">

                        <span class="text-4xl font-bold tracking-tight text-slate-950">
                            {{ $this->category->tools_count }}
                        </span>

                        <span class="pb-1 text-sm text-slate-500">
                            {{ $this->category->tools_count === 1 ? 'tool' : 'tools' }}
                        </span>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Browse the tools below or search by tool name, keyword, or task.
                    </p>

                </aside>

            </div>

        </div>

    </section>


    {{-- ============================================================
        TOOL COLLECTION
    ============================================================= --}}

    <section
        id="tools"
        class="border-b border-slate-200 bg-slate-50"
    >

        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

            {{-- Collection heading --}}
            <div class="max-w-3xl">

                <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Browse {{ $this->category->name }}
                </h2>

                <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">
                    Choose a tool below or search the collection to find the one that fits your task.
                </p>

            </div>


            {{-- Search --}}
            <div class="mt-8 max-w-2xl">

                <label
                    for="category-tool-search"
                    class="sr-only"
                >
                    Search {{ $this->category->name }} tools
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
                        id="category-tool-search"
                        type="search"
                        wire:model.live.debounce.250ms="search"
                        placeholder="Search {{ strtolower($this->category->name) }}..."
                        autocomplete="off"
                        class="h-12 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                    />

                    @if ($search)
                        <button
                            type="button"
                            wire:click="clearSearch"
                            aria-label="Clear {{ strtolower($this->category->name) }} search"
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
                                    d="M4.22 4.22a.75.75 0 011.06 0L10 8.94l4.72-4.72a.75.75 0 111.06 1.06L11.06 10l4.72 4.72a.75.75 0 01-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 01-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 010-1.06z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    @endif

                </div>

            </div>


            {{-- Search status --}}
            @if (mb_strlen(trim($search)) >= 2)

                <div class="mt-6 flex items-center justify-between gap-4 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3">

                    <p class="text-sm text-indigo-900">

                        <span class="font-semibold">
                            {{ $this->tools->count() }}
                        </span>

                        {{ $this->tools->count() === 1 ? 'tool' : 'tools' }}

                        found for

                        <span class="font-semibold">
                            “{{ $search }}”
                        </span>

                    </p>

                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="shrink-0 text-xs font-semibold text-indigo-700 transition hover:text-indigo-900"
                    >
                        Clear search
                    </button>

                </div>

            @endif


            {{-- Tool cards --}}
            @if ($this->tools->isNotEmpty())

                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach ($this->tools as $tool)

                        <a
                            href="{{ route('tools.tool', ['slug' => $tool->slug]) }}"
                            wire:navigate
                            wire:key="category-tool-{{ $tool->id }}"
                            class="group flex min-h-[190px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md"
                        >

                            <div class="flex items-start justify-between gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-lg text-slate-700 transition group-hover:border-indigo-100 group-hover:bg-indigo-50 group-hover:text-indigo-600">

                                    @if ($tool->icon)

                                        <img
                                            src="{{ asset($tool->icon) }}"
                                            loading="lazy"
                                            width="36"
                                            height="36"
                                            alt="{{ $tool->name }}"
                                            class="h-9 w-auto object-contain"
                                        >

                                    @else

                                        <span class="text-xl" aria-hidden="true">⚡</span>

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

            @else

                {{-- Empty search state --}}
                <div class="mt-10 rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center">

                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-xl"
                        aria-hidden="true"
                    >
                        🔎
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        No matching tools
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                        No tools matched that search. Try a different keyword or browse the complete collection.
                    </p>

                    @if ($search)

                        <button
                            type="button"
                            wire:click="clearSearch"
                            class="mt-6 inline-flex h-9 items-center justify-center rounded-lg bg-slate-900 px-4 text-xs font-semibold text-white transition hover:bg-slate-800"
                        >
                            Show all tools
                        </button>

                    @endif

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
                        Keep your input on your device when local processing is supported.
                    </h2>

                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        Many AabiTech tools can process text, code, numbers, and other input directly in your browser. When a tool works locally, your input can be handled on your device without being sent to AabiTech's servers.
                    </p>

                    <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-400">
                        Processing can differ from one tool to another. Check the individual tool page for details about how that tool handles your input.
                    </p>

                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">

                    <div class="space-y-5">

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-400/10 text-emerald-400">
                                ✓
                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-white">
                                    Local processing when supported
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    Browser-based tools can handle supported input directly on your device.
                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-400/10 text-indigo-400">
                                ↗
                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-white">
                                    No unnecessary upload
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-slate-400">
                                    Local processing means your input does not need to be sent to a server for supported operations.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
        HOW TO USE
    ============================================================= --}}

    <section class="bg-white">

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

            <div class="max-w-3xl">

                <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    How to use {{ strtolower($this->category->name) }}
                </h2>

                <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">
                    Pick a tool, enter the information it needs, and use the result when you're done. Each tool provides the controls needed for its specific task.
                </p>

            </div>

            <div class="mt-10 grid gap-8 md:grid-cols-3">

                <article>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-600">
                        01
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        Find the right tool
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Browse the tools in this category or search for a specific name, keyword, or task.
                    </p>

                </article>

                <article>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-600">
                        02
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        Enter your input
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Add the text, code, numbers, dates, or other information requested by the tool.
                    </p>

                </article>

                <article>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-600">
                        03
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        Use the result
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Review the result, then copy, download, or use it in your next step.
                    </p>

                </article>

            </div>

        </div>

    </section>


    {{-- ============================================================
        RELATED CATEGORIES
    ============================================================= --}}

    @if ($this->relatedCategories->isNotEmpty())

        <section class="border-t border-slate-200 bg-slate-50">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

                    <div class="max-w-2xl">

                        <h2 class="text-3xl font-bold tracking-tight text-slate-950">
                            Explore more tool categories
                        </h2>

                        <p class="mt-3 text-base leading-7 text-slate-600">
                            Browse other AabiTech categories to find tools for different tasks.
                        </p>

                    </div>

                    <a
                        href="{{ route('tools') }}"
                        wire:navigate
                        class="inline-flex shrink-0 items-center text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                    >
                        View all tools

                        <svg
                            class="ml-1.5 h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06l-4.25-4.25a.75.75 0 00-1.06-1.06L10.94 10l-3.72 3.72a.75.75 0 000 1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </a>

                </div>


                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach ($this->relatedCategories as $relatedCategory)

                        <a
                            href="{{ route('tools.category', ['slug' => $relatedCategory->slug]) }}"
                            wire:navigate
                            wire:key="related-category-{{ $relatedCategory->id }}"
                            class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-sm"
                        >

                            <div class="flex items-start justify-between gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-lg"
                                    aria-hidden="true"
                                >
                                    {{ $relatedCategory->icon ?: '🛠️' }}
                                </div>

                                <span class="text-xs font-medium text-slate-400">
                                    {{ $relatedCategory->tools_count }}
                                    {{ $relatedCategory->tools_count === 1 ? 'tool' : 'tools' }}
                                </span>

                            </div>

                            <h3 class="mt-5 text-base font-semibold text-slate-950 transition group-hover:text-indigo-600">
                                {{ $relatedCategory->name }}
                            </h3>

                            @if ($relatedCategory->short_description)

                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ $relatedCategory->short_description }}
                                </p>

                            @endif

                        </a>

                    @endforeach

                </div>

            </div>

        </section>

    @endif


    {{-- ============================================================
        FAQ
    ============================================================= --}}

    <section class="bg-white">

        <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

            <div class="max-w-2xl">

                <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Frequently asked questions
                </h2>

                <p class="mt-4 text-base leading-7 text-slate-600">
                    Common questions about AabiTech's {{ strtolower($this->category->name) }}.
                </p>

            </div>


            <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">

                {{-- FAQ 1 --}}
                <details class="group py-5">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">

                        What can I use {{ strtolower($this->category->name) }} for?

                        <svg
                            class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </summary>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">
                        {{ $this->category->short_description ?: 'These online tools help with common tasks related to this category. Choose a tool above to see its specific features and supported functions.' }}
                    </p>

                </details>


                {{-- FAQ 2 --}}
                <details class="group py-5">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">

                        Do I need to install software?

                        <svg
                            class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </summary>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">
                        No installation is required to use AabiTech's online tools. Open a tool in a modern web browser and use the features available on its page.
                    </p>

                </details>


                {{-- FAQ 3 --}}
                <details class="group py-5">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">

                        Are these tools free to use?

                        <svg
                            class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25-4.5a.75.75 0 01.08-1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </summary>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">
                        AabiTech tools are available to use online without a paid subscription. Open the tool you need and use the functionality provided on its page.
                    </p>

                </details>


                {{-- FAQ 4 --}}
                <details class="group py-5">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-slate-900">

                        Is my input uploaded to a server?

                        <svg
                            class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 01.08-1.06l-4.25-4.5a.75.75 0 011.08-1.04l4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01-1.06-1.04z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </summary>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">
                        Many AabiTech tools can process input directly in your browser. When a tool supports local processing, the input does not need to be sent to AabiTech's servers. Processing can vary by tool, so check the individual tool page for details.
                    </p>

                </details>

            </div>

        </div>

    </section>


    {{-- ============================================================
        FINAL CTA
    ============================================================= --}}

    <section class="border-t border-slate-200 bg-slate-50">

        <div class="mx-auto max-w-4xl px-4 py-14 text-center sm:px-6 lg:px-8 lg:py-20">

            <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                Looking for another tool?
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                Browse the full AabiTech collection for tools covering development, writing, calculations, design, security, and other everyday digital tasks.
            </p>

            <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">

                <a
                    href="{{ route('tools') }}"
                    wire:navigate
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-950 px-6 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Browse all tools
                </a>

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