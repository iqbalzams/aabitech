<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiLoremIpsum()"
    x-init="init()"
    x-cloak
    class="w-full"
>
@assets
    <style>
        [x-cloak] { display: none !important; }

        .aabi-lorem button,
        .aabi-lorem [role="button"],
        .aabi-lorem a,
        .aabi-lorem summary {
            cursor: pointer;
        }

        .aabi-lorem button:disabled {
            cursor: not-allowed;
        }

        .aabi-lorem .compact-tab {
            display: inline-flex;
            height: 32px;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid transparent;
            background: transparent;
            padding: 0 11px;
            font-size: 12px;
            font-weight: 500;
            color: rgb(71 85 105);
            white-space: nowrap;
            transition:
                background-color 150ms ease,
                border-color 150ms ease,
                color 150ms ease;
        }

        .aabi-lorem .compact-tab:hover {
            background: rgb(248 250 252);
        }

        .aabi-lorem [data-active-group].is-active {
            border-color: rgb(129 140 248) !important;
            background: rgb(238 242 254) !important;
            color: rgb(67 56 202) !important;
            box-shadow: none !important;
        }

        .aabi-lorem textarea,
        .aabi-lorem input,
        .aabi-lorem select {
            outline: none;
        }

        .aabi-lorem textarea:focus,
        .aabi-lorem input:focus,
        .aabi-lorem select:focus {
            border-color: rgb(129 140 248);
            box-shadow: 0 0 0 3px rgb(224 231 255);
        }

        .aabi-lorem .output-scroll::-webkit-scrollbar,
        .aabi-lorem .custom-scroll::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        .aabi-lorem .output-scroll::-webkit-scrollbar-thumb,
        .aabi-lorem .custom-scroll::-webkit-scrollbar-thumb {
            background: rgb(203 213 225);
            border-radius: 999px;
        }

        .aabi-lorem .output-scroll::-webkit-scrollbar-track,
        .aabi-lorem .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .aabi-lorem .stat-number {
            font-variant-numeric: tabular-nums;
        }

        .aabi-lorem .pulse-copy {
            animation: aabi-copy-pulse 250ms ease;
        }

        @keyframes aabi-copy-pulse {
            0% { transform: scale(.96); }
            100% { transform: scale(1); }
        }
    </style>
    @endassets
    <div class="aabi-lorem space-y-4">

        {{-- Compact toolbar --}}
        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-2">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 6h16M4 12h16M4 18h10"/>
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-800">
                        Lorem Ipsum Generator
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Generate structured placeholder content locally in your browser
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <label class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-[11px] font-medium text-slate-600">
                    <input
                        type="checkbox"
                        x-model="liveMode"
                        @change="if (liveMode) generate()"
                        class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    Live generation
                </label>

                <button
                    type="button"
                    @click="generate()"
                    class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-indigo-600 px-3 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 11a8.1 8.1 0 0 0-15.5-2M4 5v4h4"/>
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2M20 19v-4h-4"/>
                    </svg>
                    Generate
                </button>

                <button
                    type="button"
                    @click="reset()"
                    class="inline-flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Reset
                </button>
            </div>
        </div>

        {{-- Main workspace --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Generation controls --}}
            <div class="border-b border-slate-200 p-4">

                <div class="grid gap-4 lg:grid-cols-[1fr_220px]">

                    <div>
                        <div class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Generate by
                        </div>

                        <div class="flex flex-wrap gap-1 rounded-lg border border-slate-200 bg-slate-50 p-1">
                            <template x-for="modeItem in modes" :key="modeItem.value">
                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': mode === modeItem.value }"
                                    class="compact-tab"
                                    @click="setMode(modeItem.value)"
                                    x-text="modeItem.label"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                            Quantity
                        </label>

                        <input
                            type="number"
                            x-model.number="quantity"
                            @input="scheduleLiveGeneration()"
                            min="1"
                            :max="maxQuantity"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm text-slate-700"
                        >

                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <template x-for="preset in quantityPresets()" :key="preset">
                                <button
                                    type="button"
                                    @click="quantity = preset; generateIfLive()"
                                    class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600"
                                    x-text="preset"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                            Text flavor
                        </label>

                        <select
                            x-model="flavor"
                            @change="generateIfLive()"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700"
                        >
                            <option value="classic">Classic Lorem</option>
                            <option value="tech">Tech</option>
                            <option value="business">Business</option>
                            <option value="creative">Creative</option>
                            <option value="english">English filler</option>
                            <option value="custom">Custom vocabulary</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                            Output format
                        </label>

                        <div class="flex rounded-lg border border-slate-200 bg-slate-50 p-1">
                            <template x-for="formatItem in formats" :key="formatItem.value">
                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': outputFormat === formatItem.value }"
                                    class="compact-tab flex-1"
                                    @click="setFormat(formatItem.value)"
                                    x-text="formatItem.label"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                            Seed
                        </label>

                        <div class="flex gap-1.5">
                            <input
                                type="text"
                                x-model="seed"
                                @input="scheduleLiveGeneration()"
                                placeholder="Random"
                                class="h-9 min-w-0 flex-1 rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700"
                            >

                            <button
                                type="button"
                                @click="newSeed()"
                                title="Generate a new seed"
                                class="h-9 w-9 shrink-0 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100"
                            >
                                ↻
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                            Quick preset
                        </label>

                        <select
                            x-model="preset"
                            @change="applyPreset()"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700"
                        >
                            <option value="">Choose preset</option>
                            <option value="heading">Heading</option>
                            <option value="card">Card</option>
                            <option value="paragraph">Paragraph</option>
                            <option value="article">Article</option>
                            <option value="ui">UI Labels</option>
                            <option value="design">Design Review</option>
                        </select>
                    </div>
                </div>

                {{-- Compact toggles --}}
                <div class="mt-4 flex flex-wrap gap-2">

                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-2.5 py-1.5 text-[11px] text-slate-600">
                        <input
                            type="checkbox"
                            x-model="classicOpening"
                            @change="generateIfLive()"
                            class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                        >
                        Start with Lorem Ipsum
                    </label>

                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-2.5 py-1.5 text-[11px] text-slate-600">
                        <input
                            type="checkbox"
                            x-model="capitalize"
                            @change="generateIfLive()"
                            class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                        >
                        Capitalize
                    </label>

                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-2.5 py-1.5 text-[11px] text-slate-600">
                        <input
                            type="checkbox"
                            x-model="multipleVariations"
                            @change="generateIfLive()"
                            class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                        >
                        Multiple variations
                    </label>
                </div>

                {{-- Advanced settings --}}
                <details class="mt-4 rounded-lg border border-slate-200 bg-slate-50">
                    <summary class="flex cursor-pointer list-none items-center justify-between px-3 py-2.5 text-xs font-semibold text-slate-700">
                        <span>Advanced settings</span>
                        <span class="text-[10px] font-normal text-slate-400">
                            Structure, exact lengths & custom vocabulary
                        </span>
                    </summary>

                    <div class="border-t border-slate-200 p-3">

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

                            <div>
                                <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                                    Words / sentence
                                </label>

                                <div class="grid grid-cols-2 gap-1.5">
                                    <input
                                        type="number"
                                        x-model.number="wordsMin"
                                        min="2"
                                        max="100"
                                        @input="scheduleLiveGeneration()"
                                        class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs"
                                        title="Minimum words"
                                    >
                                    <input
                                        type="number"
                                        x-model.number="wordsMax"
                                        min="2"
                                        max="100"
                                        @input="scheduleLiveGeneration()"
                                        class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs"
                                        title="Maximum words"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                                    Sentences / paragraph
                                </label>

                                <div class="grid grid-cols-2 gap-1.5">
                                    <input
                                        type="number"
                                        x-model.number="sentencesMin"
                                        min="1"
                                        max="30"
                                        @input="scheduleLiveGeneration()"
                                        class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs"
                                    >
                                    <input
                                        type="number"
                                        x-model.number="sentencesMax"
                                        min="1"
                                        max="30"
                                        @input="scheduleLiveGeneration()"
                                        class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                                    Paragraph length
                                </label>

                                <select
                                    x-model="paragraphLength"
                                    @change="generateIfLive()"
                                    class="h-8 w-full rounded-md border border-slate-200 bg-white px-2 text-xs"
                                >
                                    <option value="short">Short</option>
                                    <option value="medium">Medium</option>
                                    <option value="long">Long</option>
                                    <option value="custom">Custom range</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[11px] font-medium text-slate-600">
                                    Exact characters
                                </label>

                                <div class="flex gap-1.5">
                                    <input
                                        type="number"
                                        x-model.number="exactCharacters"
                                        min="1"
                                        max="100000"
                                        @input="scheduleLiveGeneration()"
                                        placeholder="Optional"
                                        class="h-8 min-w-0 flex-1 rounded-md border border-slate-200 bg-white px-2 text-xs"
                                    >
                                    <button
                                        type="button"
                                        @click="generateExactCharacters()"
                                        class="rounded-md border border-indigo-200 bg-indigo-50 px-2 text-[10px] font-semibold text-indigo-600"
                                    >
                                        Fill
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- HTML structure --}}
                        <div class="mt-4 border-t border-slate-200 pt-3">
                            <div class="mb-2 text-[11px] font-semibold text-slate-600">
                                Structured output
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] text-slate-600">
                                    <input
                                        type="checkbox"
                                        x-model="includeHeadings"
                                        @change="generateIfLive()"
                                        class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                                    >
                                    Article headings
                                </label>

                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] text-slate-600">
                                    <input
                                        type="checkbox"
                                        x-model="includeLists"
                                        @change="generateIfLive()"
                                        class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                                    >
                                    Include lists
                                </label>

                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] text-slate-600">
                                    <input
                                        type="checkbox"
                                        x-model="headingH3"
                                        @change="generateIfLive()"
                                        class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600"
                                    >
                                    Use H3
                                </label>
                            </div>
                        </div>

                        {{-- Custom vocabulary --}}
                        <div class="mt-4 border-t border-slate-200 pt-3">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-[11px] font-semibold text-slate-600">
                                        Custom vocabulary
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        Add words or phrases separated by spaces, commas, or new lines.
                                    </div>
                                </div>

                                <label class="cursor-pointer rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-medium text-slate-600 hover:bg-slate-50">
                                    Import TXT
                                    <input
                                        type="file"
                                        accept=".txt,text/plain"
                                        class="hidden"
                                        @change="importVocabulary($event)"
                                    >
                                </label>
                            </div>

                            <textarea
                                x-model="customVocabulary"
                                @input="scheduleLiveGeneration()"
                                rows="3"
                                placeholder="technology, interface, dashboard, component, responsive, design..."
                                class="custom-scroll w-full resize-y rounded-lg border border-slate-200 bg-white p-2.5 text-xs text-slate-700"
                            ></textarea>

                            <div
                                x-show="flavor === 'custom'"
                                class="mt-1.5 text-[10px] text-amber-600"
                            >
                                Custom vocabulary is active.
                            </div>
                        </div>

                        {{-- Shareable settings --}}
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 pt-3">
                            <div>
                                <div class="text-[11px] font-semibold text-slate-600">
                                    Share settings
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    The generated text is not uploaded; only generation settings are encoded.
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="copyShareUrl()"
                                class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-[10px] font-semibold text-slate-600 hover:bg-slate-50"
                            >
                                <span x-show="!shareCopied">Copy share link</span>
                                <span x-show="shareCopied" class="text-emerald-600">✓ Link copied</span>
                            </button>
                        </div>
                    </div>
                </details>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 divide-x divide-y divide-slate-200 border-b border-slate-200 sm:grid-cols-4 sm:divide-y-0">
                <div class="p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Words</div>
                    <div class="stat-number mt-0.5 text-base font-semibold text-slate-800" x-text="stats.words.toLocaleString()"></div>
                </div>

                <div class="p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Characters</div>
                    <div class="stat-number mt-0.5 text-base font-semibold text-slate-800" x-text="stats.characters.toLocaleString()"></div>
                </div>

                <div class="p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Sentences</div>
                    <div class="stat-number mt-0.5 text-base font-semibold text-slate-800" x-text="stats.sentences.toLocaleString()"></div>
                </div>

                <div class="p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Reading time</div>
                    <div class="stat-number mt-0.5 text-base font-semibold text-slate-800" x-text="readingTime"></div>
                </div>
            </div>

            {{-- Output --}}
            <div class="p-4">

                <div class="mb-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-700">
                            Generated output
                        </div>

                        <div class="mt-0.5 text-[10px] text-slate-400">
                            <span x-text="outputFormatLabel"></span>
                            <span> · </span>
                            <span x-text="variations.length > 1 ? variations.length + ' variations' : '1 variation'"></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        <button
                            type="button"
                            @click="copyOutput()"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-[10px] font-semibold text-slate-600 transition hover:bg-slate-50"
                            :class="{ 'pulse-copy': copied }"
                        >
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" class="text-emerald-600">✓ Copied</span>
                        </button>

                        <button
                            type="button"
                            @click="downloadOutput()"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-[10px] font-semibold text-slate-600 hover:bg-slate-50"
                        >
                            Download
                        </button>

                        <button
                            type="button"
                            @click="clearOutput()"
                            class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-[10px] font-medium text-slate-500 hover:bg-slate-50"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <textarea
                    x-model="displayOutput"
                    readonly
                    spellcheck="false"
                    class="output-scroll min-h-[360px] w-full resize-y rounded-lg border border-slate-200 bg-slate-50 p-4 font-mono text-xs leading-6 text-slate-700 focus:border-slate-300 focus:ring-0 sm:min-h-[430px]"
                    aria-label="Generated Lorem Ipsum output"
                ></textarea>

                <div class="mt-2 flex flex-col gap-1 text-[10px] text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                    <span>
                        Counts exclude HTML/Markdown formatting markup.
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Generated locally in your browser
                    </span>
                </div>
            </div>
        </div>

        {{-- Multiple variation selector --}}
        <div
            x-show="variations.length > 1"
            x-transition
            class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm"
        >
            <div class="mb-2 flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-700">
                        Generated variations
                    </div>
                    <div class="text-[10px] text-slate-400">
                        Select a variation to use in the main output.
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
                <template x-for="(variation, index) in variations" :key="index">
                    <button
                        type="button"
                        @click="selectVariation(index)"
                        data-active-group
                        :class="{ 'is-active': selectedVariation === index }"
                        class="compact-tab border border-slate-200"
                    >
                        Variation <span class="ml-1" x-text="index + 1"></span>
                    </button>
                </template>
            </div>
        </div>

    </div>
    @script
    <script>
        window.aabiLoremIpsum = function () {
            return {
                modes: [
                    { value: 'paragraphs', label: 'Paragraphs' },
                    { value: 'sentences', label: 'Sentences' },
                    { value: 'words', label: 'Words' },
                    { value: 'list', label: 'List items' },
                    { value: 'characters', label: 'Characters' },
                ],

                formats: [
                    { value: 'plain', label: 'Plain' },
                    { value: 'html', label: 'HTML' },
                    { value: 'markdown', label: 'Markdown' },
                ],

                mode: 'paragraphs',
                quantity: 3,
                outputFormat: 'plain',

                flavor: 'classic',
                classicOpening: true,
                capitalize: true,

                liveMode: true,
                multipleVariations: false,
                variationCount: 3,

                seed: '',
                preset: '',

                wordsMin: 7,
                wordsMax: 16,

                sentencesMin: 3,
                sentencesMax: 6,

                paragraphLength: 'medium',
                exactCharacters: '',

                includeHeadings: false,
                includeLists: false,
                headingH3: false,

                customVocabulary: '',

                output: '',
                displayOutput: '',
                plainText: '',

                variations: [],
                selectedVariation: 0,

                copied: false,
                shareCopied: false,

                maxQuantity: 100,

                stats: {
                    words: 0,
                    characters: 0,
                    sentences: 0,
                    paragraphs: 0,
                    bytes: 0,
                },

                random: null,
                liveTimer: null,

                init() {
                    this.loadSettingsFromStorage();
                    this.loadSettingsFromUrl();

                    this.normalizeSettings();

                    if (!this.seed) {
                        this.seed = this.randomSeed();
                    }

                    this.generate();
                },

                normalizeSettings() {
                    this.quantity = Math.max(
                        1,
                        Math.min(Number(this.quantity) || 1, this.maxQuantity)
                    );

                    this.wordsMin = Math.max(2, Number(this.wordsMin) || 7);
                    this.wordsMax = Math.max(
                        this.wordsMin,
                        Number(this.wordsMax) || 16
                    );

                    this.sentencesMin = Math.max(
                        1,
                        Number(this.sentencesMin) || 3
                    );

                    this.sentencesMax = Math.max(
                        this.sentencesMin,
                        Number(this.sentencesMax) || 6
                    );
                },

                setMode(mode) {
                    this.mode = mode;

                    const defaults = {
                        paragraphs: 3,
                        sentences: 6,
                        words: 50,
                        list: 8,
                        characters: 250,
                    };

                    this.quantity = defaults[mode] || 3;
                    this.maxQuantity = mode === 'characters' ? 100000 : 5000;

                    if (this.liveMode) {
                        this.generate();
                    }
                },

                setFormat(format) {
                    this.outputFormat = format;

                    if (this.plainText) {
                        this.refreshFormattedOutput();
                    }
                },

                quantityPresets() {
                    const presets = {
                        paragraphs: [1, 3, 5, 10],
                        sentences: [3, 5, 10, 20],
                        words: [25, 50, 100, 250],
                        list: [3, 5, 8, 10],
                        characters: [60, 155, 280, 500],
                    };

                    return presets[this.mode] || [1, 3, 5];
                },

                applyPreset() {
                    const presets = {
                        heading: {
                            mode: 'words',
                            quantity: 12,
                            wordsMin: 6,
                            wordsMax: 12,
                        },

                        card: {
                            mode: 'sentences',
                            quantity: 2,
                            wordsMin: 7,
                            wordsMax: 14,
                        },

                        paragraph: {
                            mode: 'paragraphs',
                            quantity: 1,
                            sentencesMin: 4,
                            sentencesMax: 6,
                        },

                        article: {
                            mode: 'paragraphs',
                            quantity: 6,
                            sentencesMin: 4,
                            sentencesMax: 7,
                            includeHeadings: true,
                            includeLists: true,
                        },

                        ui: {
                            mode: 'list',
                            quantity: 6,
                            wordsMin: 2,
                            wordsMax: 5,
                        },

                        design: {
                            mode: 'paragraphs',
                            quantity: 4,
                            sentencesMin: 3,
                            sentencesMax: 5,
                            paragraphLength: 'medium',
                        },
                    };

                    const selected = presets[this.preset];

                    if (!selected) return;

                    Object.entries(selected).forEach(([key, value]) => {
                        this[key] = value;
                    });

                    this.normalizeSettings();
                    this.generate();
                },

                newSeed() {
                    this.seed = this.randomSeed();
                    this.generate();
                },

                randomSeed() {
                    return Math.random().toString(36).slice(2, 10);
                },

                scheduleLiveGeneration() {
                    if (!this.liveMode) return;

                    clearTimeout(this.liveTimer);

                    this.liveTimer = setTimeout(() => {
                        this.generate();
                    }, 250);
                },

                generateIfLive() {
                    if (this.liveMode) {
                        this.generate();
                    }
                },

                generate() {
                    this.normalizeSettings();

                    this.persistSettings();

                    const variationTotal = this.multipleVariations
                        ? Math.max(2, Math.min(8, Number(this.variationCount) || 3))
                        : 1;

                    this.variations = [];

                    for (let i = 0; i < variationTotal; i++) {
                        const variationSeed = this.seed
                            ? `${this.seed}:${i}`
                            : this.randomSeed();

                        const rng = this.createRng(variationSeed);

                        let document;

                        if (this.mode === 'characters') {
                            document = this.generateExactCharacters(
                                Number(this.exactCharacters || this.quantity),
                                rng
                            );
                        } else {
                            document = this.generateDocument(rng);
                        }

                        this.variations.push(document);
                    }

                    this.selectedVariation = 0;
                    this.plainText = this.variations[0] || '';

                    this.refreshFormattedOutput();
                    this.updateStats();
                },

                generateDocument(rng) {
                    switch (this.mode) {
                        case 'words':
                            return this.generateWordsDocument(
                                this.quantity,
                                rng
                            );

                        case 'sentences':
                            return this.generateSentencesDocument(
                                this.quantity,
                                rng
                            );

                        case 'list':
                            return this.generateListDocument(
                                this.quantity,
                                rng
                            );

                        case 'paragraphs':
                        default:
                            return this.generateParagraphDocument(
                                this.quantity,
                                rng
                            );
                    }
                },

                generateParagraphDocument(count, rng) {
                    const paragraphs = [];

                    for (let i = 0; i < count; i++) {
                        const sentenceCount = this.randomInt(
                            this.sentencesMin,
                            this.sentencesMax,
                            rng
                        );

                        const sentences = [];

                        for (let j = 0; j < sentenceCount; j++) {
                            sentences.push(this.generateSentence(rng));
                        }

                        paragraphs.push(sentences.join(' '));
                    }

                    if (this.classicOpening && paragraphs.length) {
                        const opener = this.classicOpeningText();

                        paragraphs[0] = this.replaceFirstSentence(
                            paragraphs[0],
                            opener
                        );
                    }

                    return paragraphs.join('\n\n');
                },

                generateSentencesDocument(count, rng) {
                    const sentences = [];

                    for (let i = 0; i < count; i++) {
                        sentences.push(this.generateSentence(rng));
                    }

                    if (this.classicOpening && sentences.length) {
                        sentences[0] = this.classicOpeningText();
                    }

                    return sentences.join(' ');
                },

                generateWordsDocument(count, rng) {
                    let words = this.generateWords(count, rng);

                    if (this.classicOpening) {
                        const openerWords = this.classicOpeningWords();

                        words = [
                            ...openerWords,
                            ...words,
                        ].slice(0, count);
                    }

                    let text = words.join(' ');

                    if (this.capitalize && text) {
                        text = this.capitalizeFirst(text);
                    }

                    return text;
                },

                generateListDocument(count, rng) {
                    const items = [];

                    for (let i = 0; i < count; i++) {
                        const wordCount = this.randomInt(
                            2,
                            7,
                            rng
                        );

                        let item = this.generateWords(wordCount, rng).join(' ');

                        if (this.capitalize) {
                            item = this.capitalizeFirst(item);
                        }

                        items.push(item);
                    }

                    return items.join('\n');
                },

                generateSentence(rng) {
                    let count = this.randomInt(
                        this.wordsMin,
                        this.wordsMax,
                        rng
                    );

                    const words = this.generateWords(count, rng);

                    let sentence = words.join(' ');

                    if (this.capitalize) {
                        sentence = this.capitalizeFirst(sentence);
                    }

                    const punctuation = this.randomChoice(
                        ['.', '.', '.', '!', '?'],
                        rng
                    );

                    return sentence + punctuation;
                },

                generateWords(count, rng) {
                    const pool = this.getVocabulary();

                    const result = [];

                    for (let i = 0; i < count; i++) {
                        result.push(
                            pool[
                                Math.floor(rng() * pool.length)
                            ]
                        );
                    }

                    return result;
                },

                generateExactCharacters(target, rng) {
                    target = Math.max(
                        1,
                        Math.min(Number(target) || 1, 100000)
                    );

                    const pool = this.getVocabulary();

                    let result = '';

                    while (result.length < target) {
                        const word = pool[
                            Math.floor(rng() * pool.length)
                        ];

                        const separator = result ? ' ' : '';

                        if (
                            result.length +
                            separator.length +
                            word.length <= target
                        ) {
                            result += separator + word;
                            continue;
                        }

                        const remaining =
                            target - result.length - separator.length;

                        if (remaining > 0) {
                            const filler = this.makeExactFiller(
                                remaining,
                                pool,
                                rng
                            );

                            result += separator + filler;
                        }

                        break;
                    }

                    if (result.length < target) {
                        result += 'x'.repeat(
                            target - result.length
                        );
                    }

                    return result.slice(0, target);
                },

                makeExactFiller(length, pool, rng) {
                    if (length <= 0) return '';

                    const candidates = pool.filter(
                        word => word.length <= length
                    );

                    if (!candidates.length) {
                        return 'x'.repeat(length);
                    }

                    let result = '';

                    while (result.length < length) {
                        const remaining = length - result.length;

                        const valid = candidates.filter(
                            word => word.length <= remaining
                        );

                        if (!valid.length) {
                            result += 'x'.repeat(remaining);
                            break;
                        }

                        const word = valid[
                            Math.floor(rng() * valid.length)
                        ];

                        if (result) {
                            if (result.length + 1 + word.length <= length) {
                                result += ' ' + word;
                            } else {
                                result += 'x'.repeat(remaining);
                            }
                        } else {
                            result += word;
                        }
                    }

                    return result;
                },

                classicOpeningText() {
                    return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                },

                classicOpeningWords() {
                    return [
                        'Lorem',
                        'ipsum',
                        'dolor',
                        'sit',
                        'amet,',
                        'consectetur',
                        'adipiscing',
                        'elit.'
                    ];
                },

                replaceFirstSentence(paragraph, replacement) {
                    const match = paragraph.match(/^.*?[.!?](?:\s|$)/);

                    if (!match) {
                        return replacement + ' ' + paragraph;
                    }

                    return replacement + paragraph.slice(match[0].length);
                },

                getVocabulary() {
                    if (
                        this.flavor === 'custom' &&
                        this.customVocabulary.trim()
                    ) {
                        const custom = this.parseCustomVocabulary();

                        if (custom.length) {
                            return custom;
                        }
                    }

                    const vocabularies = {
                        classic: `
                            lorem ipsum dolor sit amet consectetur adipiscing elit
                            sed do eiusmod tempor incididunt ut labore et dolore
                            magna aliqua ut enim ad minim veniam quis nostrud
                            exercitation ullamco laboris nisi aliquip ex ea commodo
                            consequat duis aute irure dolor reprehenderit voluptate
                            velit esse cillum fugiat nulla pariatur excepteur sint
                            occaecat cupidatat non proident sunt culpa qui officia
                            deserunt mollit anim id est laborum praesent commodo
                            cursus magna vel scelerisque nisl consectetur
                            pellentesque habitant morbi tristique senectus netus
                            malesuada fames ac turpis egestas integer posuere erat
                            a ante venenatis dapibus posuere velit aliquet
                        `,

                        tech: `
                            interface component responsive frontend backend
                            database server client framework application
                            developer software hardware cloud platform
                            system network browser mobile desktop deployment
                            pipeline repository version feature module function
                            variable object service endpoint request response
                            authentication security performance scalable architecture
                            integration automation testing debugging runtime
                            configuration dashboard analytics workflow technology
                            digital product code data API development
                        `,

                        business: `
                            strategy market customer growth business solution
                            service product company team leadership opportunity
                            performance value innovation management planning
                            organization communication partnership revenue process
                            development quality experience success operation
                            analysis research industry competitive priority
                            investment brand audience campaign result objective
                            project workflow resource decision support professional
                            enterprise scalable efficient modern effective
                        `,

                        creative: `
                            story light color dream rhythm texture visual
                            journey idea imagination scene character creative
                            design movement emotion atmosphere beautiful curious
                            wonder discovery adventure memory shadow shape
                            sound space artistic playful vivid elegant
                            warm bright quiet dramatic magical expressive
                            concept canvas pattern detail inspiration moment
                            craft style world nature energy
                        `,

                        english: `
                            the quick system content example simple useful
                            information page section text layout sample
                            modern clean responsive accessible readable practical
                            flexible clear meaningful placeholder document project
                            website application screen button navigation message
                            paragraph heading article card user experience
                            design content structure language communication
                            important common general local global current
                            reliable consistent helpful professional
                        `
                    };

                    return this.tokenize(
                        vocabularies[this.flavor] || vocabularies.classic
                    );
                },

                parseCustomVocabulary() {
                    return this.tokenize(
                        this.customVocabulary
                    );
                },

                tokenize(value) {
                    return value
                        .replace(/[,\n\r\t]+/g, ' ')
                        .split(/\s+/)
                        .map(word => word.trim())
                        .filter(Boolean)
                        .map(word => word.replace(
                            /^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/gu,
                            ''
                        ))
                        .filter(word => word.length > 0);
                },

                refreshFormattedOutput() {
                    const text = this.plainText || '';

                    if (this.outputFormat === 'html') {
                        this.displayOutput = this.toHtml(text);
                    } else if (this.outputFormat === 'markdown') {
                        this.displayOutput = this.toMarkdown(text);
                    } else {
                        this.displayOutput = text;
                    }

                    this.output = this.displayOutput;
                },

                toHtml(text) {
                    if (this.mode === 'list') {
                        const items = text
                            .split('\n')
                            .filter(Boolean)
                            .map(item => `<li>${this.escapeHtml(item)}</li>`)
                            .join('\n');

                        return `<ul>\n${items}\n</ul>`;
                    }

                    const paragraphs = text
                        .split(/\n{2,}/)
                        .filter(Boolean);

                    if (
                        this.includeHeadings &&
                        this.mode === 'paragraphs'
                    ) {
                        const parts = [];

                        paragraphs.forEach((paragraph, index) => {
                            const tag = this.headingH3 ? 'h3' : 'h2';

                            if (index % 2 === 0) {
                                parts.push(
                                    `<${tag}>Section ${Math.floor(index / 2) + 1}</${tag}>`
                                );
                            }

                            parts.push(
                                `<p>${this.escapeHtml(paragraph)}</p>`
                            );
                        });

                        if (this.includeLists) {
                            parts.push(
                                '<ul>',
                                '<li>Placeholder item for layout testing</li>',
                                '<li>Additional example content</li>',
                                '<li>Another reusable content item</li>',
                                '</ul>'
                            );
                        }

                        return parts.join('\n');
                    }

                    if (this.mode === 'sentences') {
                        return text
                            .split(/(?<=[.!?])\s+/)
                            .filter(Boolean)
                            .map(sentence => `<p>${this.escapeHtml(sentence)}</p>`)
                            .join('\n');
                    }

                    if (this.mode === 'words' || this.mode === 'characters') {
                        return `<p>${this.escapeHtml(text)}</p>`;
                    }

                    return paragraphs
                        .map(paragraph => `<p>${this.escapeHtml(paragraph)}</p>`)
                        .join('\n');
                },

                toMarkdown(text) {
                    if (this.mode === 'list') {
                        return text
                            .split('\n')
                            .filter(Boolean)
                            .map(item => `- ${item}`)
                            .join('\n');
                    }

                    if (
                        this.includeHeadings &&
                        this.mode === 'paragraphs'
                    ) {
                        const paragraphs = text
                            .split(/\n{2,}/)
                            .filter(Boolean);

                        const result = [];

                        paragraphs.forEach((paragraph, index) => {
                            if (index % 2 === 0) {
                                const level = this.headingH3 ? '###' : '##';

                                result.push(
                                    `${level} Section ${Math.floor(index / 2) + 1}`
                                );
                            }

                            result.push(paragraph);
                        });

                        if (this.includeLists) {
                            result.push(
                                '',
                                '- Placeholder item for layout testing',
                                '- Additional example content',
                                '- Another reusable content item'
                            );
                        }

                        return result.join('\n\n');
                    }

                    return text;
                },

                escapeHtml(value) {
                    return value
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                },

                updateStats() {
                    const text = this.plainText || '';

                    const words = this.countWords(text);
                    const sentences = this.countSentences(text);
                    const paragraphs = text
                        ? text.split(/\n{2,}/).filter(Boolean).length
                        : 0;

                    this.stats = {
                        words,
                        characters: [...text].length,
                        sentences,
                        paragraphs,
                        bytes: new TextEncoder().encode(text).length,
                    };
                },

                countWords(text) {
                    if (!text.trim()) return 0;

                    if (
                        typeof Intl !== 'undefined' &&
                        Intl.Segmenter
                    ) {
                        const segmenter = new Intl.Segmenter(
                            undefined,
                            { granularity: 'word' }
                        );

                        let count = 0;

                        for (const item of segmenter.segment(text)) {
                            if (item.isWordLike) {
                                count++;
                            }
                        }

                        return count;
                    }

                    return text
                        .trim()
                        .split(/\s+/)
                        .filter(Boolean)
                        .length;
                },

                countSentences(text) {
                    if (!text.trim()) return 0;

                    const matches = text.match(
                        /[^.!?]+[.!?]+(?=\s|$)/g
                    );

                    return matches
                        ? matches.length
                        : (text.trim() ? 1 : 0);
                },

                get readingTime() {
                    if (!this.stats.words) {
                        return '0 min';
                    }

                    const minutes = this.stats.words / 200;

                    if (minutes < 1) {
                        return '<1 min';
                    }

                    return `${Math.ceil(minutes)} min`;
                },

                get outputFormatLabel() {
                    const item = this.formats.find(
                        format => format.value === this.outputFormat
                    );

                    return item ? item.label : 'Plain';
                },

                selectVariation(index) {
                    this.selectedVariation = index;
                    this.plainText = this.variations[index] || '';

                    this.refreshFormattedOutput();
                    this.updateStats();
                },

                generateExactCharacters() {
                    this.mode = 'characters';

                    const target = Math.max(
                        1,
                        Math.min(
                            Number(this.exactCharacters) || 250,
                            100000
                        )
                    );

                    this.quantity = target;
                    this.generate();
                },

                copyOutput() {
                    const text = this.displayOutput || '';

                    if (!text) return;

                    const success = () => {
                        this.copied = true;

                        setTimeout(() => {
                            this.copied = false;
                        }, 1800);
                    };

                    if (
                        navigator.clipboard &&
                        window.isSecureContext
                    ) {
                        navigator.clipboard
                            .writeText(text)
                            .then(success)
                            .catch(() => this.fallbackCopy(text, success));

                        return;
                    }

                    this.fallbackCopy(text, success);
                },

                fallbackCopy(text, success) {
                    const textarea =
                        document.createElement('textarea');

                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';

                    document.body.appendChild(textarea);

                    textarea.focus();
                    textarea.select();

                    try {
                        document.execCommand('copy');
                        success();
                    } finally {
                        textarea.remove();
                    }
                },

                downloadOutput() {
                    const content = this.displayOutput || '';

                    if (!content) return;

                    let extension = 'txt';
                    let mime = 'text/plain;charset=utf-8';

                    if (this.outputFormat === 'html') {
                        extension = 'html';
                        mime = 'text/html;charset=utf-8';
                    }

                    if (this.outputFormat === 'markdown') {
                        extension = 'md';
                        mime = 'text/markdown;charset=utf-8';
                    }

                    const blob = new Blob(
                        [content],
                        { type: mime }
                    );

                    const url = URL.createObjectURL(blob);
                    const anchor = document.createElement('a');

                    anchor.href = url;
                    anchor.download =
                        `aabitech-lorem-${Date.now()}.${extension}`;

                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();

                    URL.revokeObjectURL(url);
                },

                clearOutput() {
                    this.output = '';
                    this.displayOutput = '';
                    this.plainText = '';

                    this.variations = [];

                    this.stats = {
                        words: 0,
                        characters: 0,
                        sentences: 0,
                        paragraphs: 0,
                        bytes: 0,
                    };
                },

                reset() {
                    this.mode = 'paragraphs';
                    this.quantity = 3;
                    this.outputFormat = 'plain';

                    this.flavor = 'classic';
                    this.classicOpening = true;
                    this.capitalize = true;

                    this.liveMode = true;
                    this.multipleVariations = false;

                    this.seed = this.randomSeed();
                    this.preset = '';

                    this.wordsMin = 7;
                    this.wordsMax = 16;

                    this.sentencesMin = 3;
                    this.sentencesMax = 6;

                    this.paragraphLength = 'medium';
                    this.exactCharacters = '';

                    this.includeHeadings = false;
                    this.includeLists = false;
                    this.headingH3 = false;

                    this.customVocabulary = '';

                    this.generate();
                },

                randomInt(min, max, rng) {
                    min = Math.ceil(min);
                    max = Math.floor(max);

                    return Math.floor(
                        rng() * (max - min + 1)
                    ) + min;
                },

                randomChoice(array, rng) {
                    return array[
                        Math.floor(rng() * array.length)
                    ];
                },

                createRng(seedString) {
                    let seed = 2166136261;

                    const input = String(seedString || 'random');

                    for (let i = 0; i < input.length; i++) {
                        seed ^= input.charCodeAt(i);
                        seed = Math.imul(seed, 16777619);
                    }

                    return function () {
                        seed += 0x6D2B79F5;

                        let t = seed;

                        t = Math.imul(
                            t ^ (t >>> 15),
                            t | 1
                        );

                        t ^= t + Math.imul(
                            t ^ (t >>> 7),
                            t | 61
                        );

                        return (
                            (t ^ (t >>> 14)) >>> 0
                        ) / 4294967296;
                    };
                },

                capitalizeFirst(value) {
                    if (!value) return value;

                    return value.charAt(0).toUpperCase() +
                        value.slice(1);
                },

                persistSettings() {
                    try {
                        localStorage.setItem(
                            'aabitech:lorem-ipsum:settings',
                            JSON.stringify({
                                mode: this.mode,
                                quantity: this.quantity,
                                outputFormat: this.outputFormat,
                                flavor: this.flavor,
                                classicOpening: this.classicOpening,
                                capitalize: this.capitalize,
                                liveMode: this.liveMode,
                                multipleVariations: this.multipleVariations,
                                wordsMin: this.wordsMin,
                                wordsMax: this.wordsMax,
                                sentencesMin: this.sentencesMin,
                                sentencesMax: this.sentencesMax,
                                paragraphLength: this.paragraphLength,
                                includeHeadings: this.includeHeadings,
                                includeLists: this.includeLists,
                                headingH3: this.headingH3,
                                customVocabulary: this.customVocabulary,
                            })
                        );
                    } catch (error) {
                        // Storage can be unavailable in private browsing.
                    }
                },

                loadSettingsFromStorage() {
                    try {
                        const raw = localStorage.getItem(
                            'aabitech:lorem-ipsum:settings'
                        );

                        if (!raw) return;

                        const saved = JSON.parse(raw);

                        Object.keys(saved).forEach(key => {
                            if (key in this) {
                                this[key] = saved[key];
                            }
                        });
                    } catch (error) {
                        // Ignore malformed or unavailable local storage.
                    }
                },

                loadSettingsFromUrl() {
                    try {
                        const params =
                            new URLSearchParams(window.location.search);

                        if (!params.has('lorem')) return;

                        const encoded =
                            params.get('lorem');

                        if (!encoded) return;

                        const decoded = JSON.parse(
                            atob(
                                decodeURIComponent(encoded)
                            )
                        );

                        const allowed = [
                            'mode',
                            'quantity',
                            'outputFormat',
                            'flavor',
                            'classicOpening',
                            'capitalize',
                            'wordsMin',
                            'wordsMax',
                            'sentencesMin',
                            'sentencesMax',
                            'paragraphLength',
                            'includeHeadings',
                            'includeLists',
                            'headingH3',
                            'seed',
                        ];

                        allowed.forEach(key => {
                            if (
                                Object.prototype.hasOwnProperty.call(
                                    decoded,
                                    key
                                ) &&
                                key in this
                            ) {
                                this[key] = decoded[key];
                            }
                        });
                    } catch (error) {
                        // Invalid share settings are ignored.
                    }
                },

                buildShareUrl() {
                    const settings = {
                        mode: this.mode,
                        quantity: this.quantity,
                        outputFormat: this.outputFormat,
                        flavor: this.flavor,
                        classicOpening: this.classicOpening,
                        capitalize: this.capitalize,
                        wordsMin: this.wordsMin,
                        wordsMax: this.wordsMax,
                        sentencesMin: this.sentencesMin,
                        sentencesMax: this.sentencesMax,
                        paragraphLength: this.paragraphLength,
                        includeHeadings: this.includeHeadings,
                        includeLists: this.includeLists,
                        headingH3: this.headingH3,
                        seed: this.seed,
                    };

                    const encoded = encodeURIComponent(
                        btoa(
                            JSON.stringify(settings)
                        )
                    );

                    return `${window.location.origin}${window.location.pathname}?lorem=${encoded}`;
                },

                copyShareUrl() {
                    const url = this.buildShareUrl();

                    const success = () => {
                        this.shareCopied = true;

                        setTimeout(() => {
                            this.shareCopied = false;
                        }, 1800);
                    };

                    if (
                        navigator.clipboard &&
                        window.isSecureContext
                    ) {
                        navigator.clipboard
                            .writeText(url)
                            .then(success)
                            .catch(() => {
                                this.fallbackCopy(url, success);
                            });

                        return;
                    }

                    this.fallbackCopy(url, success);
                },

                importVocabulary(event) {
                    const file = event.target.files?.[0];

                    if (!file) return;

                    if (file.size > 1024 * 1024) {
                        alert('Vocabulary files must be smaller than 1 MB.');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = () => {
                        this.customVocabulary =
                            String(reader.result || '');

                        this.flavor = 'custom';

                        if (this.liveMode) {
                            this.generate();
                        }
                    };

                    reader.readAsText(file);

                    event.target.value = '';
                },
            };
        };
    </script>
    @endscript
</div>