<?php

use App\Models\Category;
use App\Models\Tool;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $slug = '';

    /**
     * Maps database tool slugs to their Livewire implementation components.
     */
    protected array $toolComponents = [

        // Developer Tools
        'json-formatter' => 'tools.developer-tools.json-formatter',
        'base64-encoder-decoder' => 'tools.developer-tools.base64-encoder',
        'url-encoder-decoder' => 'tools.developer-tools.url-encoder',
        'html-beautifier' => 'tools.developer-tools.html-beautifier',
        'regex-tester' => 'tools.developer-tools.regex-tester',
        'jwt-decoder' => 'tools.developer-tools.jwt-decoder',
        'tailwind-css-to-email-safe-inline-style-converter' => 'tools.developer-tools.tailwind-css-email-converter',
        'sql-to-laravel-migration-converter' => 'tools.developer-tools.sql-to-laravel-migration-converter',
        'laravel-env-validator-diff-checker' => 'tools.developer-tools.laravel-env-validator-diff-checker',

        // Text Tools
        'character-counter' => 'tools.text-tools.character-counter',
        'word-counter' => 'tools.text-tools.word-counter',
        'duplicate-line-remover' => 'tools.text-tools.duplicate-line-remover',
        'reading-time-calculator' => 'tools.text-tools.reading-time-calculator',
        'slug-generator' => 'tools.text-tools.slug-generator',
        'lorem-ipsum-generator' => 'tools.text-tools.lorem-ipsum-generator',

        // Design Tools
        'aspect-ratio-calculator' => 'tools.design-tools.aspect-ratio-calculator',
        'css-gradient-generator' => 'tools.design-tools.css-gradient-generator',

        // Security Tools
        'password-strength-checker' => 'tools.security-tools.password-strength-checker',

        // Calculators
        'percentage-calculator' => 'tools.calculators.percentage-calculator',
        'age-calculator' => 'tools.calculators.age-calculator',
        'random-number-generator' => 'tools.calculators.random-number-generator',
        'cgpa-to-percentage-converter' => 'tools.calculators.cgpa-to-percentage-converter',
        'twitch-bits-to-usd-calculator' => 'tools.calculators.twitch-bits-to-usd-calculator',
        'tattoo-price-calculator' => 'tools.calculators.tattoo-price-calculator',
        'construction-estimate-calculator' => 'tools.calculators.construction-estimate-calculator',

        // Date & Time Tools
        'unix-timestamp-converter' => 'tools.date-time-tools.unix-timestamp-converter',
        'timestamp-converter' => 'tools.date-time-tools.timestamp-converter',
    ];

    public function mount(string $slug): void
    {
        $this->slug = $slug;

        if (! $this->tool) {
            abort(404);
        }
    }

    /**
     * Current active tool.
     *
     * SEO sections and FAQs are loaded with the tool so the dynamic
     * page can render the complete SEO/content architecture without
     * putting editorial content inside individual tool components.
     */
    #[Computed]
    public function tool(): ?Tool
    {
        return Tool::query()
            ->where('slug', $this->slug)
            ->where('status', true)
            ->with([
                'category',
                'seoSections',
                'faqs',
            ])
            ->first();
    }

    /**
     * Livewire implementation for the current tool.
     */
    #[Computed]
    public function implementationComponent(): ?string
    {
        if (! $this->tool) {
            return null;
        }

        return $this->toolComponents[$this->tool->slug] ?? null;
    }

    /**
     * Related tools from the same category.
     */
    #[Computed]
    public function relatedTools()
    {
        if (! $this->tool) {
            return collect();
        }

        $relatedTools = $this->tool
            ->relatedTools()
            ->where('tools.status', true)
            ->limit(5)
            ->get();

        if ($relatedTools->isNotEmpty()) {
            return $relatedTools;
        }

        return Tool::query()
            ->where('status', true)
            ->where('category_id', $this->tool->category_id)
            ->whereKeyNot($this->tool->id)
            ->orderByDesc('is_popular')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(5)
            ->get();
    }

    /**
     * Other active categories.
     */
    #[Computed]
    public function otherCategories()
    {
        if (! $this->tool) {
            return collect();
        }

        return Category::query()
            ->where('status', true)
            ->whereKeyNot($this->tool->category_id)
            ->withCount([
                'tools' => fn ($query) => $query->where('status', true),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->get();
    }

    /**
     * Return a specific SEO section by its database key.
     *
     * This lets the page control the semantic order of important sections
     * while the actual content remains database-driven.
     */
    public function seoSection(string $key)
    {
        return $this->tool?->seoSections
            ->firstWhere('section_key', $key);
    }

    /**
     * SEO sections that are not part of the primary controlled slots.
     *
     * These can be rendered as additional editorial content without
     * requiring a new page-template change.
     */
    public function additionalSeoSections()
    {
        $primaryKeys = [
            'introduction',
            'how_to_use',
            'features',
            'use_cases',
            'privacy',
        ];

        return $this->tool?->seoSections
            ->reject(
                fn ($section) => in_array(
                    $section->section_key,
                    $primaryKeys,
                    true
                )
            )
            ->values() ?? collect();
    }
};

?>

@php
    $internalLinker = app(\App\Services\ToolInternalLinker::class);
    $internalLinker->reset();
@endphp
<div class="min-h-screen bg-slate-50">

    {{-- ============================================================
        BREADCRUMB
    ============================================================= --}}

    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto w-full max-w-7xl px-4 py-4 sm:px-6 lg:px-8">

            <nav
                aria-label="Breadcrumb"
                class="flex flex-wrap items-center gap-2 text-sm"
            >

                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="font-medium text-slate-500 transition-colors hover:text-indigo-600"
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
                        d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06L10 9.5 7.22 6.72a.75.75 0 10-1.06 1.06L8.94 10l-2.78 2.78a.75.75 0 001.06 1.06z"
                        clip-rule="evenodd"
                    />
                </svg>

                <a
                    href="{{ route('tools') }}"
                    wire:navigate
                    class="font-medium text-slate-500 transition-colors hover:text-indigo-600"
                >
                    Tools
                </a>

                @if ($this->tool?->category)

                    <svg
                        class="h-4 w-4 text-slate-300"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06L10 9.5 7.22 6.72a.75.75 0 10-1.06 1.06L8.94 10l-2.78 2.78a.75.75 0 001.06 1.06z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    <a
                        href="{{ route('tools.category', ['slug' => $this->tool->category->slug]) }}"
                        wire:navigate
                        class="max-w-[220px] truncate font-medium text-slate-500 transition-colors hover:text-indigo-600"
                    >
                        {{ $this->tool->category->name }}
                    </a>

                @endif

                <svg
                    class="h-4 w-4 text-slate-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06L10 9.5 7.22 6.72a.75.75 0 10-1.06 1.06L8.94 10l-2.78 2.78a.75.75 0 001.06 1.06z"
                        clip-rule="evenodd"
                    />
                </svg>

                <span class="max-w-[280px] truncate font-semibold text-slate-900">
                    {{ $this->tool->name }}
                </span>

            </nav>

        </div>
    </section>

{{-- ============================================================
    TOOL INTRODUCTION
============================================================= --}}

<section class="bg-white">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-start gap-4 sm:gap-6">

            {{-- Tool Icon --}}
            <div class="flex shrink-0 items-center justify-center rounded-xl bg-indigo-50 sm:h-20 sm:w-20">
                @if ($this->tool->icon)
                    <img
                        src="{{ asset($this->tool->icon) }}"
                        decoding="async"
                        width="100"
                        height="100"
                        alt="{{ $this->tool->name }}"
                        class=" object-contain"
                    >
                @else
                    <span class="text-xl sm:text-2xl" aria-hidden="true">⚡</span>
                @endif
            </div>

            {{-- Tool Introduction --}}
            <div class="min-w-0 flex-1">
                <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                    {{ $this->tool->name }}
                </h1>

                @if ($this->tool->short_description)
                    <p class="mt-3 max-w-3xl text-base leading-7 text-slate-600 sm:mt-4 sm:text-lg sm:leading-8">
                        {{ $this->tool->short_description }}
                    </p>
                @endif
            </div>

        </div>
    </div>
</section>


    {{-- ============================================================
        PRIMARY TOOL WORKSPACE

        Tool applications use the available viewport width.
        Editorial content remains constrained below.
    ============================================================= --}}

    <main>

        <section class="bg-slate-50 pb-10 pt-5 sm:pb-12 sm:pt-6 lg:pb-16">

            <div class="w-full px-3 sm:px-4 lg:px-5">

                <div class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <header class="border-b border-slate-200 bg-white px-4 py-3 sm:px-5">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="min-w-0">

                                    <h2 class="truncate text-sm font-semibold text-slate-900">
                                        {{ $this->tool->name }}
                                    </h2>

                                    <p class="mt-0.5 text-xs text-slate-500">
                                        Online tool
                                    </p>

                                </div>

                            </div>

                        </div>

                    </header>


                    @if ($this->implementationComponent)

                        <div class="w-full p-3 sm:p-4 lg:p-5 min-h-[1000px]">

                            <livewire:dynamic-component
                                :is="$this->implementationComponent"
                                :wire:key="'tool-component-'.$this->tool->slug"
                            />

                        </div>

                    @else

                        <div class="flex min-h-[420px] items-center justify-center px-6 py-16">

                            <div class="mx-auto max-w-xl text-center">

                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">
                                    🛠️
                                </div>

                                <h2 class="mt-6 text-2xl font-bold tracking-tight text-slate-950">
                                    {{ $this->tool->name }} is coming soon
                                </h2>

                                <p class="mt-3 text-base leading-7 text-slate-600">
                                    This AabiTech tool page is ready, but its
                                    interactive implementation is still being built.
                                </p>

                                <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">

                                    <a
                                        href="{{ route('tools') }}"
                                        wire:navigate
                                        class="inline-flex h-10 items-center justify-center rounded-lg bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-slate-800"
                                    >
                                        Browse all tools
                                    </a>

                                    @if ($this->tool->category)

                                        <a
                                            href="{{ route('tools.category', ['slug' => $this->tool->category->slug]) }}"
                                            wire:navigate
                                            class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                        >
                                            Browse category
                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </section>


        {{-- ============================================================
            TRUST / TOOL CHARACTERISTICS
        ============================================================= --}}

        <section class="border-y border-slate-200 bg-white">

            <div class="mx-auto w-full max-w-7xl px-4 py-7 sm:px-6 lg:px-8">

                <div class="grid gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 sm:grid-cols-3">

                    <div class="bg-white px-5 py-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                ⚡
                            </div>

                            <div>

                                <h2 class="text-sm font-semibold text-slate-900">
                                    Fast to use
                                </h2>

                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    Designed to help you complete the task
                                    without unnecessary steps.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="bg-white px-5 py-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                ◇
                            </div>

                            <div>

                                <h2 class="text-sm font-semibold text-slate-900">
                                    Works in your browser
                                </h2>

                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    No separate desktop software is needed
                                    for this online tool.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="bg-white px-5 py-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                ✓
                            </div>

                            <div>

                                <h2 class="text-sm font-semibold text-slate-900">
                                    Simple workflow
                                </h2>

                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    Enter your information, use the tool and
                                    work with the result.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ============================================================
            SEO CONTENT
            Loaded dynamically from tool_seo_sections.
        ============================================================= --}}

        @if ($this->seoSection('introduction'))

            @php($section = $this->seoSection('introduction'))

            <section class="bg-white">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <article>

                        <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            {{ $section->heading }}
                        </h2>

                        <div class="prose prose-slate mt-5 max-w-none text-base leading-8">
                            {!! $internalLinker->link(
                                $section->content,
                                $this->tool
                            ) !!}
                        </div>

                    </article>

                </div>

            </section>

        @endif


        {{-- ============================================================
            HOW TO USE
        ============================================================= --}}

        @if ($this->seoSection('how_to_use'))

            @php($section = $this->seoSection('how_to_use'))

            <section class="border-t border-slate-200 bg-slate-50">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <article>

                        <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            {{ $section->heading }}
                        </h2>

                        <div class="prose prose-slate mt-5 max-w-none text-base leading-8">
                            {!! $internalLinker->link(
                                $section->content,
                                $this->tool
                            ) !!}
                        </div>

                    </article>

                </div>

            </section>

        @endif


        {{-- ============================================================
            FEATURES
        ============================================================= --}}

        @if ($this->seoSection('features'))

            @php($section = $this->seoSection('features'))

            <section class="bg-white">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <article>

                        <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            {{ $section->heading }}
                        </h2>

                        <div class="prose prose-slate mt-6 max-w-none text-base leading-8">
                            {!! $internalLinker->link(
                                $section->content,
                                $this->tool
                            ) !!}
                        </div>

                    </article>

                </div>

            </section>

        @endif


        {{-- ============================================================
            USE CASES
        ============================================================= --}}

        @if ($this->seoSection('use_cases'))

            @php($section = $this->seoSection('use_cases'))

            <section class="border-t border-slate-200 bg-slate-50">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <article>

                        <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            {{ $section->heading }}
                        </h2>

                        <div class="prose prose-slate mt-5 max-w-none text-base leading-8">
                            {!! $internalLinker->link(
                                $section->content,
                                $this->tool
                            ) !!}
                        </div>

                    </article>

                </div>

            </section>

        @endif


        {{-- ============================================================
            PRIVACY
        ============================================================= --}}

        @if ($this->seoSection('privacy'))

            @php($section = $this->seoSection('privacy'))

            <section class="bg-slate-950 text-white">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <article>

                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-400">
                            Privacy
                        </p>

                        <h2 class="mt-3 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                            {{ $section->heading }}
                        </h2>

                        <div class="prose prose-invert mt-5 max-w-none text-base leading-8">
                            {!! $internalLinker->link(
                                $section->content,
                                $this->tool
                            ) !!}
                        </div>

                    </article>

                </div>

            </section>

        @endif


        {{-- ============================================================
            ADDITIONAL SEO SECTIONS

            Any future section_key can be added to the database without
            requiring a new Blade section in the primary page structure.
        ============================================================= --}}

        @if ($this->additionalSeoSections()->isNotEmpty())

            <section class="bg-white">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <div class="space-y-14">

                        @foreach ($this->additionalSeoSections() as $section)

                            <article wire:key="seo-section-{{ $section->id }}">

                                <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                                    {{ $section->heading }}
                                </h2>

                                <div class="prose prose-slate mt-5 max-w-none text-base leading-8">
                                    {!! $internalLinker->link(
                                        $section->content,
                                        $this->tool
                                    ) !!}
                                </div>

                            </article>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif


        {{-- ============================================================
            FAQ
        ============================================================= --}}

        @if ($this->tool->faqs->isNotEmpty())

            <section class="border-t border-slate-200 bg-white">

                <div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
                            Questions & answers
                        </p>

                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            Frequently Asked Questions
                        </h2>

                    </div>


                    <div class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white">

                        @foreach ($this->tool->faqs as $faq)

                            <details
                                wire:key="tool-faq-{{ $faq->id }}"
                                class="group border-b border-slate-200 p-5 last:border-b-0 sm:p-6"
                            >

                                <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-semibold text-slate-900">

                                    <span>
                                        {{ $faq->question }}
                                    </span>

                                    <span
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-transform duration-200 group-open:rotate-45"
                                        aria-hidden="true"
                                    >
                                        +
                                    </span>

                                </summary>

                                <div class="prose prose-slate mt-4 max-w-none text-sm leading-7">
                                    {!! $faq->answer !!}
                                </div>

                            </details>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif


        {{-- ============================================================
            RELATED TOOLS
        ============================================================= --}}

        @if ($this->relatedTools->isNotEmpty())

            <section class="border-t border-slate-200 bg-slate-50">

                <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                        <div>

                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
                                Same category
                            </p>

                            <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                                Related tools
                            </h2>

                        </div>

                        @if ($this->tool->category)

                            <a
                                href="{{ route('tools.category', ['slug' => $this->tool->category->slug]) }}"
                                wire:navigate
                                class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                            >
                                View all

                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 001.06 1.06L10 9.5l-3.72-3.72a.75.75 0 00-1.06 1.06l4.25 4.25a.75.75 0 000 1.06l-4.25 4.25a.75.75 0 001.06 1.06L10 10.56l3.72 3.72a.75.75 0 001.06-1.06z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>

                        @endif

                    </div>


                    <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach ($this->relatedTools as $relatedTool)

                            <a
                                wire:key="related-tool-{{ $relatedTool->id }}"
                                href="{{ route('tools.tool', ['slug' => $relatedTool->slug]) }}"
                                wire:navigate
                                class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md"
                            >

                                <div class="flex items-start gap-4">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-lg transition group-hover:bg-indigo-50">
                                       @if ($relatedTool->icon)
                                            <img
                                                src="{{ asset($relatedTool->icon) }}"
                                                loading="lazy"
                                                width="36"
                                                height="36"
                                                alt="{{ $relatedTool->name }}"
                                                class="h-9 w-auto object-contain"
                                            >
                                        @else
                                            <span class="text-xl">⚡</span>
                                        @endif 
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-semibold text-slate-900 transition group-hover:text-indigo-600">
                                            {{ $relatedTool->name }}
                                        </h3>

                                        @if ($relatedTool->short_description)

                                            <p class="mt-1.5 line-clamp-2 text-sm leading-6 text-slate-500">
                                                {{ $relatedTool->short_description }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif


        {{-- ============================================================
            OTHER CATEGORIES
        ============================================================= --}}

        @if ($this->otherCategories->isNotEmpty())

            <section class="bg-white">

                <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

                    <div class="max-w-2xl">

                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">
                            More from AabiTech
                        </p>

                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            Browse other categories
                        </h2>

                    </div>


                    <div class="mt-7 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

                        @foreach ($this->otherCategories as $category)

                            <a
                                wire:key="other-category-{{ $category->id }}"
                                href="{{ route('tools.category', ['slug' => $category->slug]) }}"
                                wire:navigate
                                class="group rounded-xl border border-slate-200 bg-white px-5 py-4 transition hover:border-indigo-200 hover:bg-indigo-50/40"
                            >

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-base transition group-hover:bg-white">
                                        {{ $category->icon ?: '⚡' }}
                                    </div>

                                    <div class="min-w-0 flex-1">

                                        <h3 class="truncate text-sm font-semibold text-slate-900">
                                            {{ $category->name }}
                                        </h3>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $category->tools_count }}
                                            {{ $category->tools_count === 1 ? 'tool' : 'tools' }}
                                        </p>

                                    </div>

                                    <svg
                                        class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06L10 9.5 7.22 6.72a.75.75 0 10-1.06 1.06L8.94 10l-2.78 2.78a.75.75 0 001.06 1.06z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif


        {{-- ============================================================
            FINAL CTA
        ============================================================= --}}

        <section class="border-t border-slate-200 bg-slate-950">

            <div class="mx-auto max-w-4xl px-4 py-14 text-center sm:px-6 lg:px-8 lg:py-16">

                <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Explore more AabiTech tools
                </h2>

                <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-300">
                    Browse the complete collection of practical online tools
                    for development, text, design, calculations and everyday
                    digital work.
                </p>

                <div class="mt-7">

                    <a
                        href="{{ route('tools') }}"
                        wire:navigate
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-white px-6 text-sm font-semibold text-slate-950 transition hover:bg-slate-100"
                    >
                        Explore all tools

                        <svg
                            class="ml-2 h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06L10 9.5 7.22 6.72a.75.75 0 10-1.06 1.06L8.94 10l-2.78 2.78a.75.75 0 001.06 1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </a>

                </div>

            </div>

        </section>

    </main>

</div>