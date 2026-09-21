<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.app')]
class extends Component
{
    public array $seo = [
        'title' => 'Free JSON Formatter & Validator Online - Format, Beautify & Minify JSON',

        'description' => 'Format, validate, beautify and minify JSON online for free. Fast browser-based JSON formatter and validator with no signup required.',

        'canonical' => 'https://aabitech.com/tools/json-formatter',

        'robots' => 'index, follow',

        'og_title' => 'Free JSON Formatter & Validator Online',

        'og_description' => 'Format, validate, beautify and minify JSON online with this fast and free JSON tool from AabiTech.',

        'og_type' => 'website',

        'twitter_card' => 'summary_large_image',

        'schema' => [
            '@context' => 'https://schema.org',

            '@graph' => [

                [
                    '@type' => 'WebPage',

                    '@id' => 'https://aabitech.com/tools/json-formatter#webpage',

                    'url' => 'https://aabitech.com/tools/json-formatter',

                    'name' => 'Free JSON Formatter & Validator Online',

                    'description' => 'Format, validate, beautify and minify JSON online for free.',

                    'isPartOf' => [
                        '@id' => 'https://aabitech.com/#website',
                    ],
                ],

                [
                    '@type' => 'SoftwareApplication',

                    'name' => 'AabiTech JSON Formatter & Validator',

                    'applicationCategory' => 'DeveloperApplication',

                    'operatingSystem' => 'Any',

                    'url' => 'https://aabitech.com/tools/json-formatter',

                    'description' => 'Free online JSON formatter, validator, beautifier and minifier.',

                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '0',
                        'priceCurrency' => 'USD',
                    ],
                ],

                [
                    '@type' => 'BreadcrumbList',

                    'itemListElement' => [

                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => 'https://aabitech.com/',
                        ],

                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => 'Tools',
                            'item' => 'https://aabitech.com/tools',
                        ],

                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => 'JSON Formatter',
                            'item' => 'https://aabitech.com/tools/json-formatter',
                        ],

                    ],
                ],

            ],
        ],
    ];

    public function mount(): void
    {
        view()->share('seo', $this->seo);
    }
};
?>

<div
    x-data="jsonFormatter"
    class="min-h-screen bg-slate-50"
>

    {{-- Breadcrumb --}}
    <nav
        aria-label="Breadcrumb"
        class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8"
    >
        <ol class="flex flex-wrap items-center gap-2 text-sm text-slate-500">

            <li>
                <a
                    href="{{ route('home') }}"
                    class="hover:text-indigo-600"
                >
                    Home
                </a>
            </li>

            <li aria-hidden="true">/</li>

            <li>
                <a
                    href="{{ url('/tools') }}"
                    class="hover:text-indigo-600"
                >
                    Tools
                </a>
            </li>

            <li aria-hidden="true">/</li>

            <li
                class="font-medium text-slate-700"
                aria-current="page"
            >
                JSON Formatter
            </li>

        </ol>
    </nav>


    {{-- Hero --}}
    <section class="px-4 pb-10 pt-10 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-4xl text-center">

            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                Free Online Developer Tool
            </div>

            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                JSON Formatter &amp; Validator Online
            </h1>

            <p class="mx-auto mt-5 max-w-3xl text-lg leading-8 text-slate-600">
                Format, validate, beautify, pretty print and minify JSON online
                for free. Clean up messy JSON and check JSON syntax instantly
                in your browser.
            </p>

            <div class="mt-6 flex flex-wrap justify-center gap-3">

                <span class="rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200">
                    Free
                </span>

                <span class="rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200">
                    No Signup
                </span>

                <span class="rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200">
                    Browser Based
                </span>

                <span class="rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200">
                    Fast &amp; Easy
                </span>

            </div>

        </div>

    </section>


    {{-- Main Tool --}}
    <section
        id="json-tool"
        class="scroll-mt-24 px-4 pb-16 sm:px-6 lg:px-8"
    >

        <div class="mx-auto max-w-7xl">

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/50">

                {{-- Header --}}

                <div class="border-b border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <h2 class="text-lg font-bold text-slate-900">
                                JSON Formatter &amp; Validator
                            </h2>

                            <p class="text-sm text-slate-500">
                                Paste JSON below to format, validate or minify it.
                            </p>

                        </div>

                        <div
                            x-show="validJson"
                            x-cloak
                            class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700"
                        >
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Valid JSON
                        </div>

                    </div>

                </div>


                {{-- Editors --}}

                <div class="grid lg:grid-cols-2">

                    {{-- Input --}}

                    <div class="border-b border-slate-200 lg:border-b-0 lg:border-r">

                        <div class="border-b border-slate-200 px-5 py-3">

                            <label
                                for="json-input"
                                class="text-sm font-semibold text-slate-800"
                            >
                                JSON Input
                            </label>

                        </div>

                        <div class="min-h-[420px]">

                            <textarea
                                id="json-input"
                                x-model="jsonInput"
                                @input="onInput"
                                @keydown.ctrl.enter.prevent="formatJson"
                                @keydown.meta.enter.prevent="formatJson"
                                spellcheck="false"
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                placeholder='Paste JSON here...

{
  "name": "AabiTech",
  "free": true
}'
                                class="min-h-[420px] w-full resize-y border-0 bg-white p-5 font-mono text-sm leading-6 text-slate-800 outline-none focus:ring-0"
                            ></textarea>

                        </div>

                    </div>


                    {{-- Output --}}

                    <div>

                        <div class="border-b border-slate-200 px-5 py-3">

                            <span class="text-sm font-semibold text-slate-800">
                                Formatted Output
                            </span>

                        </div>

                        <pre
                            class="min-h-[420px] max-h-[600px] overflow-auto bg-white p-5 font-mono text-sm leading-6 text-slate-700"
                        ><code x-text="output || 'Formatted JSON will appear here...'"></code></pre>

                    </div>

                </div>


                {{-- Controls --}}

                <div class="border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-6">

                    <div class="flex flex-wrap gap-2">

                        <button
                            type="button"
                            @click="formatJson"
                            class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Format
                        </button>

                        <button
                            type="button"
                            @click="validateJson"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Validate
                        </button>

                        <button
                            type="button"
                            @click="minifyJson"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Minify
                        </button>

                        <button
                            type="button"
                            @click="copyJson"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            <span x-show="!copied">
                                Copy
                            </span>

                            <span x-show="copied" x-cloak>
                                Copied!
                            </span>
                        </button>

                        <button
                            type="button"
                            @click="downloadJson"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Download
                        </button>

                        <button
                            type="button"
                            @click="loadExample"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Example
                        </button>

                        <button
                            type="button"
                            @click="clearJson"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Clear
                        </button>

                    </div>


                    {{-- Status --}}

                    <div
                        x-show="status.message"
                        x-cloak
                        class="mt-4 rounded-xl border p-4"
                        :class="{
                            'border-emerald-200 bg-emerald-50 text-emerald-800': status.type === 'success',
                            'border-red-200 bg-red-50 text-red-800': status.type === 'error',
                            'border-blue-200 bg-blue-50 text-blue-800': status.type === 'info'
                        }"
                    >

                        <p
                            class="font-semibold"
                            x-text="status.title"
                        ></p>

                        <p
                            class="mt-1 text-sm"
                            x-text="status.message"
                        ></p>

                    </div>


                    <div class="mt-4 flex flex-wrap gap-6 text-xs text-slate-500">

                        <span>
                            Characters:
                            <strong
                                class="text-slate-700"
                                x-text="characterCount"
                            ></strong>
                        </span>

                        <span>
                            Lines:
                            <strong
                                class="text-slate-700"
                                x-text="lineCount"
                            ></strong>
                        </span>

                        <span>
                            Shortcut:
                            <strong class="text-slate-700">
                                Ctrl + Enter
                            </strong>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- Introduction --}}

    <section class="bg-white">

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6">

            <h2 class="text-3xl font-bold text-slate-900">
                Free Online JSON Formatter and Validator
            </h2>

            <p class="mt-5 text-lg leading-8 text-slate-600">
                A JSON formatter converts compact or difficult-to-read JSON
                into a clean and readable structure. A JSON validator checks
                whether the JSON follows valid JSON syntax.
            </p>

            <p class="mt-4 leading-7 text-slate-600">
                This free JSON formatter combines both functions in one tool.
                You can format JSON, validate JSON, beautify JSON, pretty print
                JSON and minify JSON directly in your browser.
            </p>

        </div>

    </section>


    {{-- Features --}}

    <section class="border-y border-slate-200 bg-slate-50">

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-3xl text-center">

                <h2 class="text-3xl font-bold text-slate-900">
                    JSON Formatting Tools in One Place
                </h2>

                <p class="mt-4 text-slate-600">
                    Useful features for developers, students and anyone
                    working with JSON data.
                </p>

            </div>


            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Format JSON Online
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Format messy or compressed JSON into an easy-to-read
                        structure with proper indentation.
                    </p>
                </article>


                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Validate JSON Online
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Check JSON syntax and identify invalid JSON before
                        using it in your application.
                    </p>
                </article>


                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        JSON Beautifier
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Pretty print JSON with indentation and line breaks
                        for easier reading.
                    </p>
                </article>


                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        JSON Minifier
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Remove unnecessary whitespace and create compact JSON.
                    </p>
                </article>


                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Copy JSON
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Copy the formatted result directly to your clipboard.
                    </p>
                </article>


                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Download JSON
                    </h3>
                    <p class="mt-2 leading-6 text-slate-600">
                        Download your processed JSON as a JSON file.
                    </p>
                </article>

            </div>

        </div>

    </section>


    {{-- How to use --}}

    <section class="bg-white">

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6">

            <h2 class="text-3xl font-bold text-slate-900">
                How to Format JSON Online
            </h2>

            <div class="mt-10 space-y-8">

                <div>
                    <h3 class="font-semibold text-slate-900">
                        1. Paste your JSON
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        Copy your JSON data and paste it into the JSON input
                        box above.
                    </p>
                </div>


                <div>
                    <h3 class="font-semibold text-slate-900">
                        2. Click Format
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        Click the Format button to validate and pretty print
                        your JSON.
                    </p>
                </div>


                <div>
                    <h3 class="font-semibold text-slate-900">
                        3. Fix JSON errors if necessary
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        If the JSON is invalid, the tool will display an error
                        message so you can correct the syntax.
                    </p>
                </div>


                <div>
                    <h3 class="font-semibold text-slate-900">
                        4. Copy or download your JSON
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        Copy the formatted JSON or download it as a JSON file.
                    </p>
                </div>

            </div>

        </div>

    </section>


    {{-- Example --}}

    <section class="bg-slate-950 text-white">

        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">

            <div class="text-center">

                <h2 class="text-3xl font-bold">
                    JSON Formatter Example
                </h2>

                <p class="mt-4 text-slate-300">
                    Format compact JSON into a readable structure.
                </p>

            </div>


            <div class="mt-10 grid gap-6 lg:grid-cols-2">

                <div>

                    <h3 class="mb-3 font-semibold">
                        Before
                    </h3>

                    <pre class="overflow-x-auto rounded-2xl bg-slate-900 p-6 text-sm leading-7 text-slate-300"><code>{"name":"AabiTech","free":true,"tools":["JSON Formatter","Word Counter"]}</code></pre>

                </div>


                <div>

                    <h3 class="mb-3 font-semibold">
                        After
                    </h3>

                    <pre class="overflow-x-auto rounded-2xl bg-slate-900 p-6 text-sm leading-7 text-slate-300"><code>{
  "name": "AabiTech",
  "free": true,
  "tools": [
    "JSON Formatter",
    "Word Counter"
  ]
}</code></pre>

                </div>

            </div>

        </div>

    </section>


    {{-- Common errors --}}

    <section class="bg-white">

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6">

            <h2 class="text-3xl font-bold text-slate-900">
                Common JSON Syntax Errors
            </h2>

            <div class="mt-8 space-y-4">

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <h3 class="font-semibold text-slate-900">
                        Missing comma
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        JSON properties and array values normally need commas
                        between them.
                    </p>

                </div>


                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <h3 class="font-semibold text-slate-900">
                        Single quotation marks
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        JSON strings use double quotation marks rather than
                        single quotation marks.
                    </p>

                </div>


                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <h3 class="font-semibold text-slate-900">
                        Trailing comma
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        A trailing comma after the final object property or
                        array item is not valid standard JSON.
                    </p>

                </div>


                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <h3 class="font-semibold text-slate-900">
                        Missing bracket or brace
                    </h3>

                    <p class="mt-2 leading-7 text-slate-600">
                        Every opening object brace or array bracket must be
                        correctly closed.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- FAQ --}}

    <section class="bg-slate-50">

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6">

            <div class="text-center">

                <h2 class="text-3xl font-bold text-slate-900">
                    Frequently Asked Questions About JSON
                </h2>

            </div>


            <div class="mt-10 space-y-4">

                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        What is a JSON formatter?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        A JSON formatter makes JSON easier to read by adding
                        indentation, spaces and line breaks.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        How do I format JSON online?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        Paste your JSON into the input box and click Format.
                        The tool validates the JSON and displays a formatted
                        version.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        How can I validate JSON?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        Paste your JSON and click Validate. The tool checks
                        whether the data follows standard JSON syntax.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        What is a JSON beautifier?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        A JSON beautifier makes JSON more readable by applying
                        indentation and line breaks. JSON formatter and JSON
                        beautifier commonly describe the same type of operation.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        Can I minify JSON?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        Yes. Click Minify to remove unnecessary whitespace from
                        valid JSON.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        Is this JSON formatter free?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        Yes. The core JSON formatting and validation features
                        are available for free without registration.
                    </p>

                </details>


                <details class="rounded-2xl border border-slate-200 bg-white p-6">

                    <summary class="cursor-pointer font-semibold text-slate-900">
                        Do I need to install software?
                    </summary>

                    <p class="mt-4 leading-7 text-slate-600">
                        No. You can use the JSON formatter directly in a
                        modern web browser.
                    </p>

                </details>

            </div>

        </div>

    </section>


    {{-- Related tools --}}

    <section class="bg-white">

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <h2 class="text-3xl font-bold text-slate-900">
                Related Online Tools
            </h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <a
                    href="{{ url('/tools/word-counter') }}"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition hover:bg-white hover:shadow-lg"
                >

                    <h3 class="font-semibold text-slate-900">
                        Word Counter
                    </h3>

                    <p class="mt-2 text-sm text-slate-600">
                        Count words, characters and lines online.
                    </p>

                </a>


                <a
                    href="{{ url('/tools/image-resizer') }}"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition hover:bg-white hover:shadow-lg"
                >

                    <h3 class="font-semibold text-slate-900">
                        Image Resizer
                    </h3>

                    <p class="mt-2 text-sm text-slate-600">
                        Resize images online quickly.
                    </p>

                </a>


                <a
                    href="{{ url('/tools/image-compressor') }}"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition hover:bg-white hover:shadow-lg"
                >

                    <h3 class="font-semibold text-slate-900">
                        Image Compressor
                    </h3>

                    <p class="mt-2 text-sm text-slate-600">
                        Reduce image file size online.
                    </p>

                </a>


                <a
                    href="{{ url('/tools/jpg-to-png') }}"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition hover:bg-white hover:shadow-lg"
                >

                    <h3 class="font-semibold text-slate-900">
                        JPG to PNG
                    </h3>

                    <p class="mt-2 text-sm text-slate-600">
                        Convert JPG images to PNG online.
                    </p>

                </a>

            </div>

        </div>

    </section>


    {{-- Final CTA --}}

    <section class="bg-slate-950 text-white">

        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6">

            <h2 class="text-3xl font-bold">
                Format JSON Online for Free
            </h2>

            <p class="mt-4 text-slate-300">
                Paste your JSON above and format, validate, beautify or
                minify it instantly.
            </p>

            <a
                href="#json-tool"
                class="mt-7 inline-flex rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white hover:bg-indigo-500"
            >
                Format JSON Now
            </a>

        </div>

    </section>

</div>


@script

<script>

    Alpine.data('jsonFormatter', () => ({

        jsonInput: '',
        output: '',
        validJson: false,
        copied: false,

        status: {
            type: '',
            title: '',
            message: ''
        },

        characterCount: 0,
        lineCount: 1,

        init() {
            this.updateStats();
        },

        onInput() {

            this.validJson = false;

            this.status = {
                type: '',
                title: '',
                message: ''
            };

            this.updateStats();
        },

        updateStats() {

            this.characterCount =
                this.jsonInput.length;

            this.lineCount =
                this.jsonInput
                    ? this.jsonInput.split(/\r\n|\r|\n/).length
                    : 1;
        },

        parseJson() {

            if (!this.jsonInput.trim()) {

                throw new Error(
                    'Please enter some JSON first.'
                );
            }

            try {

                return JSON.parse(
                    this.jsonInput
                );

            } catch (error) {

                throw new Error(
                    this.getFriendlyError(error)
                );
            }
        },

        formatJson() {

            try {

                const data =
                    this.parseJson();

                this.output =
                    JSON.stringify(
                        data,
                        null,
                        2
                    );

                this.validJson = true;

                this.showStatus(
                    'success',
                    'Valid JSON',
                    'Your JSON is valid and has been formatted successfully.'
                );

            } catch (error) {

                this.validJson = false;

                this.showStatus(
                    'error',
                    'Invalid JSON',
                    error.message
                );
            }
        },

        validateJson() {

            try {

                this.parseJson();

                this.validJson = true;

                this.showStatus(
                    'success',
                    'Valid JSON',
                    'The JSON syntax is valid.'
                );

            } catch (error) {

                this.validJson = false;

                this.showStatus(
                    'error',
                    'Invalid JSON',
                    error.message
                );
            }
        },

        minifyJson() {

            try {

                const data =
                    this.parseJson();

                this.output =
                    JSON.stringify(data);

                this.validJson = true;

                this.showStatus(
                    'success',
                    'JSON Minified',
                    'Unnecessary whitespace has been removed.'
                );

            } catch (error) {

                this.validJson = false;

                this.showStatus(
                    'error',
                    'Invalid JSON',
                    error.message
                );
            }
        },

        async copyJson() {

            const text =
                this.output ||
                this.jsonInput;

            if (!text) {

                this.showStatus(
                    'info',
                    'Nothing to copy',
                    'Enter or generate some JSON first.'
                );

                return;
            }

            try {

                await navigator.clipboard.writeText(
                    text
                );

                this.copied = true;

                setTimeout(() => {

                    this.copied = false;

                }, 1800);

            } catch (error) {

                this.showStatus(
                    'error',
                    'Copy failed',
                    'Your browser did not allow clipboard access.'
                );
            }
        },

        downloadJson() {

            const text =
                this.output ||
                this.jsonInput;

            if (!text) {

                this.showStatus(
                    'info',
                    'Nothing to download',
                    'Enter or generate some JSON first.'
                );

                return;
            }

            const blob =
                new Blob(
                    [text],
                    {
                        type: 'application/json;charset=utf-8'
                    }
                );

            const url =
                URL.createObjectURL(blob);

            const link =
                document.createElement('a');

            link.href = url;
            link.download = 'formatted.json';

            document.body.appendChild(link);

            link.click();

            document.body.removeChild(link);

            URL.revokeObjectURL(url);
        },

        clearJson() {

            this.jsonInput = '';
            this.output = '';
            this.validJson = false;
            this.copied = false;

            this.status = {
                type: '',
                title: '',
                message: ''
            };

            this.updateStats();
        },

        loadExample() {

            this.jsonInput =
                JSON.stringify(
                    {
                        name: 'AabiTech',
                        website: 'aabitech.com',
                        free: true,
                        tools: [
                            'JSON Formatter',
                            'Word Counter'
                        ]
                    },
                    null,
                    2
                );

            this.output = '';
            this.validJson = false;

            this.status = {
                type: 'info',
                title: 'Example loaded',
                message: 'Click Format or Validate to process the example.'
            };

            this.updateStats();
        },

        getFriendlyError(error) {

            const message =
                error.message ||
                'Invalid JSON syntax.';

            const positionMatch =
                message.match(
                    /position\s+(\d+)/i
                );

            if (positionMatch) {

                const position =
                    Number(positionMatch[1]);

                const beforeError =
                    this.jsonInput.slice(
                        0,
                        position
                    );

                const line =
                    beforeError.split(
                        /\r\n|\r|\n/
                    ).length;

                const lastNewLine =
                    Math.max(
                        beforeError.lastIndexOf('\n'),
                        beforeError.lastIndexOf('\r')
                    );

                const column =
                    position -
                    lastNewLine;

                return `${message} Line ${line}, approximately column ${column}.`;
            }

            return message;
        },

        showStatus(
            type,
            title,
            message
        ) {

            this.status = {
                type,
                title,
                message
            };
        }

    }));

</script>

@endscript