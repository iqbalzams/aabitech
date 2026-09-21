<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.app')]
class extends Component
{
    public string $search = '';

    public array $categories = [
        [
            'name' => 'Image Tools',
            'slug' => 'image-tools',
            'description' => 'Resize, compress, crop, convert and optimize images.',
            'icon' => 'image',
        ],
        [
            'name' => 'PDF Tools',
            'slug' => 'pdf-tools',
            'description' => 'Merge, split, compress and convert PDF files.',
            'icon' => 'pdf',
        ],
        [
            'name' => 'Text Tools',
            'slug' => 'text-tools',
            'description' => 'Count, format, clean and transform text quickly.',
            'icon' => 'text',
        ],
        [
            'name' => 'Developer Tools',
            'slug' => 'developer-tools',
            'description' => 'Useful utilities for developers and programmers.',
            'icon' => 'code',
        ],
        [
            'name' => 'SEO Tools',
            'slug' => 'seo-tools',
            'description' => 'Simple tools for content creators and SEO.',
            'icon' => 'seo',
        ],
        [
            'name' => 'Converters',
            'slug' => 'converters',
            'description' => 'Convert files, units, formats and data.',
            'icon' => 'convert',
        ],
    ];

    public array $tools = [
        [
            'name' => 'Image Resizer',
            'slug' => 'image-resizer',
            'description' => 'Resize images quickly without complicated software.',
            'category' => 'Image Tools',
            'icon' => 'image',
            'popular' => true,
        ],
        [
            'name' => 'JPG to PNG',
            'slug' => 'jpg-to-png',
            'description' => 'Convert JPG images to PNG format online.',
            'category' => 'Image Tools',
            'icon' => 'image',
            'popular' => true,
        ],
        [
            'name' => 'Image Compressor',
            'slug' => 'image-compressor',
            'description' => 'Compress images and reduce file size.',
            'category' => 'Image Tools',
            'icon' => 'compress',
            'popular' => true,
        ],
        [
            'name' => 'Word Counter',
            'slug' => 'word-counter',
            'description' => 'Count words, characters and sentences instantly.',
            'category' => 'Text Tools',
            'icon' => 'text',
            'popular' => true,
        ],
        [
            'name' => 'JSON Formatter',
            'slug' => 'json-formatter',
            'description' => 'Format and validate JSON data.',
            'category' => 'Developer Tools',
            'icon' => 'code',
            'popular' => true,
        ],
        [
            'name' => 'PDF Merger',
            'slug' => 'pdf-merger',
            'description' => 'Combine multiple PDF files into one document.',
            'category' => 'PDF Tools',
            'icon' => 'pdf',
            'popular' => true,
        ],
        [
            'name' => 'Case Converter',
            'slug' => 'case-converter',
            'description' => 'Convert text between uppercase and lowercase.',
            'category' => 'Text Tools',
            'icon' => 'text',
            'popular' => true,
        ],
        [
            'name' => 'QR Code Generator',
            'slug' => 'qr-code-generator',
            'description' => 'Create QR codes quickly and easily.',
            'category' => 'Converters',
            'icon' => 'qr',
            'popular' => true,
        ],
    ];

    #[Computed]
    public function filteredTools(): array
    {
        $search = trim(strtolower($this->search));

        if ($search === '') {
            return array_values(
                array_filter(
                    $this->tools,
                    fn (array $tool) => $tool['popular'] === true
                )
            );
        }

        return array_values(
            array_filter(
                $this->tools,
                function (array $tool) use ($search): bool {
                    return str_contains(
                        strtolower($tool['name']),
                        $search
                    )
                    || str_contains(
                        strtolower($tool['description']),
                        $search
                    )
                    || str_contains(
                        strtolower($tool['category']),
                        $search
                    );
                }
            )
        );
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }
};
?>

<div>

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="relative overflow-hidden bg-white">

        <div class="absolute inset-0 -z-10">
            <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-indigo-50 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-20 pt-20 sm:px-6 lg:px-8 lg:pb-28 lg:pt-28">

            <div class="mx-auto max-w-4xl text-center">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    Free online tools for everyday work
                </div>


                <h1 class="text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-7xl">
                    Simple tools.
                    <span class="text-indigo-600">
                        Real results.
                    </span>
                </h1>


                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                    Fast, simple and useful online tools for students,
                    professionals, developers, creators and everyone else.
                </p>


                {{-- Search --}}
                <div class="mx-auto mt-10 max-w-2xl">

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-slate-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"
                                />
                            </svg>
                        </div>


                        <input
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="What tool do you need?"
                            class="h-16 w-full rounded-2xl border border-slate-200 bg-white pl-14 pr-14 text-base shadow-xl shadow-slate-200/60 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        />


                        @if($search !== '')
                            <button
                                type="button"
                                wire:click="clearSearch"
                                class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18 18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        @endif

                    </div>

                </div>


                {{-- Quick links --}}
                <div class="mt-6 flex flex-wrap justify-center gap-2">

                    <span class="mr-1 text-sm text-slate-400">
                        Popular:
                    </span>

                    <button
                        type="button"
                        wire:click="$set('search', 'image')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        Image Tools
                    </button>

                    <span class="text-slate-300">•</span>

                    <button
                        type="button"
                        wire:click="$set('search', 'pdf')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        PDF Tools
                    </button>

                    <span class="text-slate-300">•</span>

                    <button
                        type="button"
                        wire:click="$set('search', 'json')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                    >
                        JSON Formatter
                    </button>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        POPULAR TOOLS
    ========================================================== --}}
    <section
        id="tools"
        class="scroll-mt-20 bg-slate-50 py-20"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="text-sm font-bold uppercase tracking-wider text-indigo-600">
                        Explore
                    </p>

                    <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950">
                        Popular Tools
                    </h2>

                    <p class="mt-2 text-slate-500">
                        Useful tools people use again and again.
                    </p>

                </div>

            </div>


            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                @forelse($this->filteredTools as $tool)

                    <a
                        href="{{ url('/tools/' . $tool['slug']) }}"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50"
                    >

                        <div class="flex items-start justify-between">

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">

                                @if($tool['icon'] === 'image')

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 16 4.5-4.5 3 3L16 10l4 6M5 20h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1Z"/>
                                    </svg>

                                @elseif($tool['icon'] === 'code')

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 9-4 3 4 3m8-6 4 3-4 3m-3-9-2 12"/>
                                    </svg>

                                @elseif($tool['icon'] === 'text')

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h14M12 5v14m-4 0h8"/>
                                    </svg>

                                @elseif($tool['icon'] === 'pdf')

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l4 4v14H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v5h5"/>
                                    </svg>

                                @else

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m9-9H3"/>
                                    </svg>

                                @endif

                            </div>


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-slate-300 transition group-hover:translate-x-1 group-hover:text-indigo-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m9 5 7 7-7 7"
                                />
                            </svg>

                        </div>


                        <h3 class="mt-5 text-lg font-bold text-slate-950">
                            {{ $tool['name'] }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            {{ $tool['description'] }}
                        </p>

                        <div class="mt-5 text-xs font-semibold text-indigo-600">
                            {{ $tool['category'] }}
                        </div>

                    </a>

                @empty

                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">

                        <h3 class="text-lg font-bold text-slate-900">
                            No tools found
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Try another search term.
                        </p>

                        <button
                            type="button"
                            wire:click="clearSearch"
                            class="mt-5 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            Clear Search
                        </button>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        CATEGORIES
    ========================================================== --}}
    <section
        id="categories"
        class="scroll-mt-20 bg-white py-20"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl">

                <p class="text-sm font-bold uppercase tracking-wider text-indigo-600">
                    Categories
                </p>

                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950">
                    Find the right tool faster
                </h2>

                <p class="mt-3 text-slate-500">
                    Explore AabiTech tools by category.
                </p>

            </div>


            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($categories as $category)

                    <a
                        href="{{ url('/category/' . $category['slug']) }}"
                        class="group rounded-2xl border border-slate-200 p-6 transition hover:border-indigo-200 hover:bg-indigo-50/40"
                    >

                        <div class="flex items-center justify-between">

                            <h3 class="font-bold text-slate-950">
                                {{ $category['name'] }}
                            </h3>

                            <span class="text-slate-300 transition group-hover:translate-x-1 group-hover:text-indigo-600">
                                →
                            </span>

                        </div>

                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            {{ $category['description'] }}
                        </p>

                    </a>

                @endforeach

            </div>

        </div>

    </section>


    {{-- =========================================================
        VALUE PROPOSITION
    ========================================================== --}}
    <section
        id="about"
        class="scroll-mt-20 bg-slate-950 py-20 text-white"
    >

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">

                <div>

                    <p class="text-sm font-bold uppercase tracking-wider text-indigo-400">
                        Why AabiTech
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                        Useful tools without unnecessary complexity.
                    </h2>

                    <p class="mt-5 max-w-xl leading-7 text-slate-400">
                        AabiTech is being built around a simple idea:
                        everyday digital tasks should be quick, easy and
                        accessible from any device.
                    </p>

                </div>


                <div class="grid gap-4 sm:grid-cols-2">

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h3 class="font-bold">
                            Fast
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Lightweight tools designed for quick results.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h3 class="font-bold">
                            Simple
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Clean interfaces without unnecessary steps.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h3 class="font-bold">
                            Free
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Useful online utilities accessible to everyone.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h3 class="font-bold">
                            Practical
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">
                            Tools designed around real-world digital tasks.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        SEO CONTENT
    ========================================================== --}}
    <section class="bg-white py-20">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <h2 class="text-2xl font-black text-slate-950">
                Free Online Tools for Everyday Digital Tasks
            </h2>

            <div class="mt-5 space-y-5 text-sm leading-7 text-slate-600">

                <p>
                    AabiTech provides simple online tools that help you
                    complete common digital tasks quickly. From image
                    compression and resizing to PDF, text and developer
                    utilities, the goal is to make everyday work easier.
                </p>

                <p>
                    Whether you are a student, teacher, developer, content
                    creator, freelancer or business professional, you can
                    use AabiTech tools directly from your browser without
                    installing complicated software.
                </p>

                <p>
                    New utilities will continue to be added across image,
                    PDF, text, developer, SEO and conversion categories.
                </p>

            </div>

        </div>

    </section>

</div>