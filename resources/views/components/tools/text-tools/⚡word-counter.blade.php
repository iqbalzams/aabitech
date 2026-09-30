<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div
    x-data="aabiWordCounter()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full"
>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .aabi-wc-scrollbar::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        .aabi-wc-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .aabi-wc-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(203 213 225);
            border-radius: 999px;
        }

        .aabi-wc-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgb(148 163 184);
        }

        .aabi-wc-stat {
            min-width: 0;
        }

        .aabi-wc-stat-value {
            font-variant-numeric: tabular-nums;
        }

        .aabi-wc-drop-active {
            border-color: rgb(99 102 241) !important;
            background: rgb(238 242 255) !important;
        }

        .aabi-wc-progress {
            transition: width 180ms ease;
        }

        .aabi-wc-table th {
            white-space: nowrap;
        }

        .aabi-wc-table td,
        .aabi-wc-table th {
            padding: 8px 10px;
        }

        .aabi-wc-number {
            font-variant-numeric: tabular-nums;
        }
    </style>

    {{-- Privacy --}}
    <div class="mb-3 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 3l7 4v5c0 4.5-3 7.7-7 9-4-1.3-7-4.5-7-9V7l7-4z"/>
            <path d="M9 12l2 2 4-4"/>
        </svg>
        <span>
            <strong>Your text stays in your browser.</strong>
            Word counting and analysis are performed locally.
        </span>
    </div>

    {{-- Main toolbar --}}
    <div class="mb-3 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 p-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <div class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                    Content type
                </div>

                <div class="aabi-wc-scrollbar flex gap-1 overflow-x-auto pb-0.5">
                    <template x-for="preset in contentPresetOptions" :key="preset.id">
                        <button
                            type="button"
                            data-active-group
                            class="compact-tab"
                            :class="contentPreset === preset.id ? 'is-active' : ''"
                            @click="applyContentPreset(preset.id)"
                            x-text="preset.label"
                        ></button>
                    </template>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <label class="flex h-8 items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-2.5">
                    <span class="text-[10px] font-medium text-slate-500">Platform</span>
                    <select
                        x-model="platformPreset"
                        @change="saveSettings()"
                        class="border-0 bg-transparent p-0 pr-5 text-xs font-medium text-slate-700 outline-none focus:ring-0"
                    >
                        <template x-for="platform in platformPresetOptions" :key="platform.id">
                            <option :value="platform.id" x-text="platform.label"></option>
                        </template>
                    </select>
                </label>

                <button
                    type="button"
                    @click="loadExample()"
                    class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                >
                    Example
                </button>

                <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                >
                    Import TXT
                </button>

                <input
                    x-ref="fileInput"
                    type="file"
                    accept=".txt,text/plain"
                    class="hidden"
                    @change="importSelectedFile($event)"
                >

                <button
                    type="button"
                    @click="copyReport()"
                    class="inline-flex h-8 items-center gap-1.5 rounded-md border border-indigo-200 bg-indigo-50 px-3 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                >
                    <span x-text="copiedAction === 'report' ? '✓ Copied' : 'Copy report'"></span>
                </button>

                <button
                    type="button"
                    @click="clearText()"
                    class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700"
                >
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Main workspace --}}
    <div class="grid gap-3 lg:grid-cols-2">

        {{-- Editor --}}
        <section
            class="min-w-0 rounded-xl border border-slate-200 bg-white shadow-sm"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dropFile($event)"
            :class="dragging ? 'aabi-wc-drop-active' : ''"
        >
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Text editor
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Paste or type your text below
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-1.5">
                    <button
                        type="button"
                        @click="pasteFromClipboard()"
                        class="rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                    >
                        Paste
                    </button>

                    <button
                        type="button"
                        @click="copyText()"
                        class="rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                    >
                        <span x-text="copiedAction === 'text' ? '✓ Copied' : 'Copy text'"></span>
                    </button>

                    <button
                        type="button"
                        @click="downloadText()"
                        class="rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                    >
                        Download
                    </button>
                </div>
            </div>

            <div class="relative p-3">
                <textarea
                    x-ref="editor"
                    x-model="text"
                    @input="queueAnalyze(); refreshSelection()"
                    @select="refreshSelection()"
                    @keyup="refreshSelection()"
                    @mouseup="refreshSelection()"
                    spellcheck="true"
                    autocomplete="off"
                    autocapitalize="sentences"
                    placeholder="Start typing or paste your text here..."
                    class="aabi-wc-scrollbar block h-[380px] w-full resize-y rounded-lg border border-slate-200 bg-slate-50/50 p-4 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                ></textarea>

                <div
                    x-show="dragging"
                    class="pointer-events-none absolute inset-3 flex items-center justify-center rounded-lg border-2 border-dashed border-indigo-400 bg-indigo-50/90"
                >
                    <div class="text-center">
                        <div class="text-sm font-semibold text-indigo-700">
                            Drop TXT file here
                        </div>
                        <div class="mt-1 text-xs text-indigo-500">
                            Maximum file size: 5 MB
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 px-4 py-2.5">
                <div class="flex items-center gap-3 text-[11px] text-slate-500">
                    <span>
                        <strong class="text-slate-700" x-text="formatNumber(words)"></strong>
                        words
                    </span>
                    <span>
                        <strong class="text-slate-700" x-text="formatNumber(characters)"></strong>
                        characters
                    </span>
                    <span
                        x-show="useSelectionOnly"
                        class="rounded bg-indigo-50 px-1.5 py-0.5 font-medium text-indigo-600"
                    >
                        Selection mode
                    </span>
                </div>

                <button
                    type="button"
                    @click="toggleSelectionMode()"
                    data-active-group
                    class="compact-tab"
                    :class="useSelectionOnly ? 'is-active' : ''"
                >
                    <span x-text="useSelectionOnly ? 'Selection counting: ON' : 'Count selection only'"></span>
                </button>
            </div>
        </section>

        {{-- Primary statistics --}}
        <section class="min-w-0 rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Live statistics
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        <span x-show="!useSelectionOnly">Entire text</span>
                        <span x-show="useSelectionOnly">
                            <span x-text="selectedText ? 'Selected text' : 'Select text in the editor'"></span>
                        </span>
                    </div>
                </div>

                <button
                    type="button"
                    @click="copyReport()"
                    class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                >
                    <span x-text="copiedAction === 'report' ? '✓ Copied' : 'Copy statistics'"></span>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-2 p-3 sm:grid-cols-3 xl:grid-cols-4">
                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Words</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(words)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Characters</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(characters)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">No spaces</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(charactersNoSpaces)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Sentences</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(sentences)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Paragraphs</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(paragraphs)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Lines</div>
                    <div class="aabi-wc-stat-value mt-1 text-xl font-bold text-slate-800" x-text="formatNumber(lines)"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Reading</div>
                    <div class="mt-1 text-base font-bold text-indigo-700" x-text="readingTime"></div>
                </div>

                <div class="aabi-wc-stat rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Speaking</div>
                    <div class="mt-1 text-base font-bold text-indigo-700" x-text="speakingTime"></div>
                </div>
            </div>

            {{-- Word target --}}
            <div class="mx-3 mb-3 rounded-lg border border-indigo-100 bg-indigo-50/60 p-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <div class="text-xs font-semibold text-indigo-900">
                            Word target
                        </div>
                        <div class="mt-0.5 text-[11px] text-indigo-600">
                            <span x-show="wordTarget > 0" x-text="wordTargetName || 'Custom target'"></span>
                            <span x-show="wordTarget <= 0">No target set</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <div
                            class="text-sm font-bold"
                            :class="wordTargetExceeded ? 'text-red-600' : 'text-indigo-700'"
                            x-text="wordTargetStatus"
                        ></div>
                    </div>
                </div>

                <div
                    x-show="wordTarget > 0"
                    class="mt-2 h-2 overflow-hidden rounded-full bg-white"
                >
                    <div
                        class="aabi-wc-progress h-full rounded-full"
                        :class="wordTargetExceeded ? 'bg-red-500' : 'bg-indigo-500'"
                        :style="'width:' + wordTargetProgress + '%'"
                    ></div>
                </div>

                <div
                    x-show="wordTarget > 0"
                    class="mt-1 flex justify-between text-[10px] text-indigo-500"
                >
                    <span x-text="formatNumber(words) + ' words'"></span>
                    <span x-text="formatNumber(wordTarget) + ' target'"></span>
                </div>
            </div>

            {{-- Platform limit --}}
            <div
                x-show="platformLimit > 0"
                class="mx-3 mb-3 rounded-lg border border-slate-200 bg-slate-50 p-3"
            >
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <div class="text-xs font-semibold text-slate-700" x-text="platformLabel"></div>
                        <div class="mt-0.5 text-[10px] text-slate-400">
                            Character limit / guideline
                        </div>
                    </div>

                    <div
                        class="text-xs font-semibold"
                        :class="platformExceeded ? 'text-red-600' : 'text-slate-700'"
                    >
                        <span x-text="formatNumber(characters)"></span>
                        /
                        <span x-text="formatNumber(platformLimit)"></span>
                    </div>
                </div>

                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-white">
                    <div
                        class="aabi-wc-progress h-full rounded-full"
                        :class="platformExceeded ? 'bg-red-500' : 'bg-slate-500'"
                        :style="'width:' + platformProgress + '%'"
                    ></div>
                </div>
            </div>

            {{-- Quick composition metrics --}}
            <div class="grid grid-cols-2 gap-2 border-t border-slate-100 p-3 sm:grid-cols-4">
                <div>
                    <div class="text-[10px] text-slate-400">Unique words</div>
                    <div class="mt-0.5 text-sm font-semibold text-slate-700" x-text="formatNumber(uniqueWords)"></div>
                </div>

                <div>
                    <div class="text-[10px] text-slate-400">Whitespace</div>
                    <div class="mt-0.5 text-sm font-semibold text-slate-700" x-text="formatNumber(whitespace)"></div>
                </div>

                <div>
                    <div class="text-[10px] text-slate-400">Letters</div>
                    <div class="mt-0.5 text-sm font-semibold text-slate-700" x-text="formatNumber(letters)"></div>
                </div>

                <div>
                    <div class="text-[10px] text-slate-400">Numbers</div>
                    <div class="mt-0.5 text-sm font-semibold text-slate-700" x-text="formatNumber(numbers)"></div>
                </div>
            </div>
        </section>
    </div>

    {{-- Advanced analysis --}}
    <div class="mt-3 space-y-3">

        {{-- Writing metrics --}}
        <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Writing metrics & analysis
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Detailed composition and time estimates
                    </div>
                </div>

                <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </summary>

            <div class="grid gap-3 border-t border-slate-100 p-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Average word length</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="averageWordLength"></div>
                    <div class="text-[10px] text-slate-400">Unicode characters</div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Average sentence</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="averageSentenceLength"></div>
                    <div class="text-[10px] text-slate-400">words per sentence</div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Longest word</div>
                    <div class="mt-1 truncate text-lg font-bold text-slate-800" :title="longestWord" x-text="longestWord || '—'"></div>
                    <div class="text-[10px] text-slate-400" x-text="longestWord ? longestWordLength + ' characters' : ''"></div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Shortest word</div>
                    <div class="mt-1 truncate text-lg font-bold text-slate-800" :title="shortestWord" x-text="shortestWord || '—'"></div>
                    <div class="text-[10px] text-slate-400" x-text="shortestWord ? shortestWordLength + ' characters' : ''"></div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Writing time</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="typingTime"></div>
                    <div class="text-[10px] text-slate-400" x-text="typingWpm + ' WPM'"></div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Graphemes</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="formatNumber(graphemes)"></div>
                    <div class="text-[10px] text-slate-400">emoji-aware characters</div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Emoji</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="formatNumber(emojis)"></div>
                    <div class="text-[10px] text-slate-400">grapheme sequences</div>
                </div>

                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                    <div class="text-[10px] uppercase tracking-wide text-slate-400">Punctuation</div>
                    <div class="mt-1 text-lg font-bold text-slate-800" x-text="formatNumber(punctuation)"></div>
                    <div class="text-[10px] text-slate-400">Unicode punctuation</div>
                </div>
            </div>
        </details>

        {{-- Frequency analysis --}}
        <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Word frequency analysis
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Most-used words, occurrence percentage and meaningful-word analysis
                    </div>
                </div>

                <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </summary>

            <div class="border-t border-slate-100">
                <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 bg-slate-50/70 p-3">
                    <label class="inline-flex items-center gap-2 text-xs text-slate-600">
                        <input
                            type="checkbox"
                            x-model="stopWordFiltering"
                            @change="analyze(); saveSettings()"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        Exclude stop words
                    </label>

                    <label class="inline-flex items-center gap-2 text-xs text-slate-600">
                        <input
                            type="checkbox"
                            x-model="caseSensitive"
                            @change="analyze(); saveSettings()"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        Case sensitive
                    </label>

                    <label class="flex items-center gap-2 text-xs text-slate-600">
                        Language
                        <select
                            x-model="language"
                            @change="analyze(); saveSettings()"
                            class="rounded-md border border-slate-200 bg-white px-2 py-1.5 text-xs outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100"
                        >
                            <option value="auto">Auto</option>
                            <option value="en">English</option>
                            <option value="ur">Urdu</option>
                            <option value="ar">Arabic</option>
                            <option value="fa">Persian</option>
                            <option value="zh">Chinese</option>
                            <option value="ja">Japanese</option>
                            <option value="ko">Korean</option>
                        </select>
                    </label>
                </div>

                <div class="grid gap-4 p-4 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <div class="mb-2 flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-700">
                                Most-used words
                            </div>
                            <div class="text-[10px] text-slate-400">
                                Top 30
                            </div>
                        </div>

                        <div class="aabi-wc-scrollbar max-h-[360px] overflow-auto rounded-lg border border-slate-200">
                            <table class="aabi-wc-table w-full text-left text-xs">
                                <thead class="sticky top-0 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">
                                    <tr>
                                        <th>#</th>
                                        <th>Word</th>
                                        <th class="text-right">Count</th>
                                        <th class="text-right">%</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    <template x-if="frequencyDisplay.length === 0">
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-slate-400">
                                                No word frequency data yet.
                                            </td>
                                        </tr>
                                    </template>

                                    <template x-for="(item, index) in frequencyDisplay" :key="item.word + '-' + index">
                                        <tr class="hover:bg-slate-50">
                                            <td class="text-slate-400" x-text="index + 1"></td>
                                            <td class="max-w-[220px] truncate font-medium text-slate-700" :title="item.word" x-text="item.word"></td>
                                            <td class="aabi-wc-number text-right font-semibold text-slate-700" x-text="item.count"></td>
                                            <td class="aabi-wc-number text-right text-slate-500" x-text="item.percentage + '%'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 text-xs font-semibold text-slate-700">
                            Custom stop words
                        </div>

                        <textarea
                            x-model="customStopWords"
                            @input.debounce.250ms="analyze(); saveSettings()"
                            rows="7"
                            placeholder="one word per line or comma-separated"
                            class="aabi-wc-scrollbar w-full resize-y rounded-lg border border-slate-200 bg-white p-3 text-xs text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        ></textarea>

                        <div class="mt-2 rounded-lg bg-slate-50 p-3 text-[10px] leading-5 text-slate-500">
                            Stop-word filtering affects frequency analysis only.
                            Total word count remains unchanged.
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-2.5">
                                <div class="text-[10px] text-slate-400">Unique words</div>
                                <div class="mt-0.5 text-sm font-bold text-slate-700" x-text="formatNumber(uniqueWords)"></div>
                            </div>

                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-2.5">
                                <div class="text-[10px] text-slate-400">Analyzed</div>
                                <div class="mt-0.5 text-sm font-bold text-slate-700" x-text="formatNumber(analyzedFrequencyWords)"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </details>

        {{-- Letter / punctuation analysis --}}
        <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Character, punctuation & number analysis
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Unicode-aware frequency breakdown
                    </div>
                </div>

                <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </summary>

            <div class="grid gap-4 border-t border-slate-100 p-4 lg:grid-cols-3">
                {{-- Letters --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-700">Letters</div>
                        <div class="text-[10px] text-slate-400" x-text="formatNumber(letters) + ' total'"></div>
                    </div>

                    <div class="aabi-wc-scrollbar max-h-[280px] overflow-auto rounded-lg border border-slate-200">
                        <table class="aabi-wc-table w-full text-left text-xs">
                            <thead class="sticky top-0 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">
                                <tr>
                                    <th>Character</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <template x-if="letterFrequencyDisplay.length === 0">
                                    <tr>
                                        <td colspan="2" class="py-8 text-center text-slate-400">No letters.</td>
                                    </tr>
                                </template>

                                <template x-for="item in letterFrequencyDisplay" :key="item.char">
                                    <tr>
                                        <td class="font-medium text-slate-700" x-text="item.char"></td>
                                        <td class="aabi-wc-number text-right text-slate-600" x-text="item.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Punctuation --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-700">Punctuation</div>
                        <div class="text-[10px] text-slate-400" x-text="formatNumber(punctuation) + ' total'"></div>
                    </div>

                    <div class="aabi-wc-scrollbar max-h-[280px] overflow-auto rounded-lg border border-slate-200">
                        <table class="aabi-wc-table w-full text-left text-xs">
                            <thead class="sticky top-0 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">
                                <tr>
                                    <th>Mark</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <template x-if="punctuationDisplay.length === 0">
                                    <tr>
                                        <td colspan="2" class="py-8 text-center text-slate-400">No punctuation.</td>
                                    </tr>
                                </template>

                                <template x-for="item in punctuationDisplay" :key="item.char">
                                    <tr>
                                        <td class="font-medium text-slate-700" x-text="item.char"></td>
                                        <td class="aabi-wc-number text-right text-slate-600" x-text="item.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Numbers --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-700">Numbers</div>
                        <div class="text-[10px] text-slate-400" x-text="formatNumber(numbers) + ' total'"></div>
                    </div>

                    <div class="aabi-wc-scrollbar max-h-[280px] overflow-auto rounded-lg border border-slate-200">
                        <table class="aabi-wc-table w-full text-left text-xs">
                            <thead class="sticky top-0 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">
                                <tr>
                                    <th>Digit</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <template x-if="digitFrequencyDisplay.length === 0">
                                    <tr>
                                        <td colspan="2" class="py-8 text-center text-slate-400">No numbers.</td>
                                    </tr>
                                </template>

                                <template x-for="item in digitFrequencyDisplay" :key="item.char">
                                    <tr>
                                        <td class="font-medium text-slate-700" x-text="item.char"></td>
                                        <td class="aabi-wc-number text-right text-slate-600" x-text="item.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </details>

        {{-- Targets and settings --}}
        <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Targets, speeds & settings
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Customize calculations and save your preferences
                    </div>
                </div>

                <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </summary>

            <div class="grid gap-4 border-t border-slate-100 p-4 lg:grid-cols-2">

                {{-- Speeds --}}
                <div class="rounded-lg border border-slate-200 p-3">
                    <div class="mb-3 text-xs font-semibold text-slate-700">
                        Calculation speeds
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Reading WPM</span>
                            <input
                                type="number"
                                min="1"
                                max="2000"
                                x-model.number="readingWpm"
                                @input="normalizeSettings(); saveSettings()"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>

                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Speaking WPM</span>
                            <input
                                type="number"
                                min="1"
                                max="2000"
                                x-model.number="speakingWpm"
                                @input="normalizeSettings(); saveSettings()"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>

                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Typing WPM</span>
                            <input
                                type="number"
                                min="1"
                                max="300"
                                x-model.number="typingWpm"
                                @input="normalizeSettings(); saveSettings()"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-1.5">
                        <button
                            type="button"
                            @click="setSpeedPreset('slow')"
                            class="rounded-md border border-slate-200 px-2 py-1.5 text-[10px] font-medium text-slate-600 hover:bg-slate-50"
                        >
                            Slow
                        </button>

                        <button
                            type="button"
                            @click="setSpeedPreset('average')"
                            class="rounded-md border border-slate-200 px-2 py-1.5 text-[10px] font-medium text-slate-600 hover:bg-slate-50"
                        >
                            Average
                        </button>

                        <button
                            type="button"
                            @click="setSpeedPreset('fast')"
                            class="rounded-md border border-slate-200 px-2 py-1.5 text-[10px] font-medium text-slate-600 hover:bg-slate-50"
                        >
                            Fast
                        </button>
                    </div>
                </div>

                {{-- Word target --}}
                <div class="rounded-lg border border-slate-200 p-3">
                    <div class="mb-3 text-xs font-semibold text-slate-700">
                        Custom word target
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Target words</span>
                            <input
                                type="number"
                                min="0"
                                max="10000000"
                                x-model.number="wordTarget"
                                @input="contentPreset = 'custom'; wordTargetName = 'Custom target'; saveSettings()"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>

                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Target name</span>
                            <input
                                type="text"
                                maxlength="60"
                                x-model="wordTargetName"
                                @input="saveSettings()"
                                placeholder="e.g. Essay target"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <input
                            type="text"
                            maxlength="40"
                            x-model="newLimitName"
                            placeholder="Save this limit as..."
                            class="min-w-0 flex-1 rounded-md border border-slate-200 px-3 py-2 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        >

                        <button
                            type="button"
                            @click="saveCurrentLimit()"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-xs font-medium text-white hover:bg-indigo-700"
                        >
                            Save
                        </button>
                    </div>
                </div>

                {{-- Target time --}}
                <div class="rounded-lg border border-slate-200 p-3">
                    <div class="mb-3 text-xs font-semibold text-slate-700">
                        Target-time mode
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Duration (minutes)</span>
                            <input
                                type="number"
                                min="0"
                                max="100000"
                                step="0.5"
                                x-model.number="targetTimeMinutes"
                                @input="saveSettings()"
                                class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>

                        <label>
                            <span class="mb-1 block text-[10px] font-medium text-slate-500">Basis</span>
                            <select
                                x-model="targetTimeBasis"
                                @change="saveSettings()"
                                class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            >
                                <option value="reading">Reading</option>
                                <option value="speaking">Speaking</option>
                                <option value="typing">Typing</option>
                            </select>
                        </label>
                    </div>

                    <div class="mt-3 rounded-md bg-indigo-50 p-3">
                        <div class="text-[10px] text-indigo-500">
                            Required words
                        </div>
                        <div class="mt-0.5 text-xl font-bold text-indigo-700" x-text="formatNumber(requiredWordsForTargetTime)"></div>
                        <div class="text-[10px] text-indigo-500">
                            at <span x-text="targetTimeWpm"></span> WPM
                        </div>
                    </div>
                </div>

                {{-- Saved limits --}}
                <div class="rounded-lg border border-slate-200 p-3">
                    <div class="mb-3 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-700">
                            Saved word limits
                        </div>

                        <button
                            type="button"
                            @click="savedLimits = []; saveSettings()"
                            x-show="savedLimits.length"
                            class="text-[10px] font-medium text-red-500 hover:text-red-700"
                        >
                            Clear saved
                        </button>
                    </div>

                    <div x-show="savedLimits.length === 0" class="rounded-md bg-slate-50 p-3 text-center text-[11px] text-slate-400">
                        No saved limits.
                    </div>

                    <div x-show="savedLimits.length" class="space-y-1.5">
                        <template x-for="limit in savedLimits" :key="limit.id">
                            <div class="flex items-center gap-2 rounded-md border border-slate-100 px-2.5 py-2">
                                <button
                                    type="button"
                                    @click="applySavedLimit(limit)"
                                    class="min-w-0 flex-1 text-left"
                                >
                                    <div class="truncate text-xs font-medium text-slate-700" x-text="limit.name"></div>
                                    <div class="text-[10px] text-slate-400" x-text="formatNumber(limit.limit) + ' words'"></div>
                                </button>

                                <button
                                    type="button"
                                    @click="deleteSavedLimit(limit.id)"
                                    class="px-1.5 text-slate-400 hover:text-red-600"
                                    aria-label="Delete saved limit"
                                >
                                    ×
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 px-4 py-3">
                <button
                    type="button"
                    @click="shareSettings()"
                    class="inline-flex items-center gap-2 rounded-md border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 hover:bg-indigo-100"
                >
                    <span x-text="shareStatus || 'Copy shareable settings link'"></span>
                </button>

                <span class="ml-2 text-[10px] text-slate-400">
                    Settings are shared; your text is never included.
                </span>
            </div>
        </details>

        {{-- Comparison --}}
        <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                <div>
                    <div class="text-sm font-semibold text-slate-800">
                        Compare two texts
                    </div>
                    <div class="mt-0.5 text-[11px] text-slate-400">
                        Compare word, character, sentence and paragraph counts
                    </div>
                </div>

                <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </summary>

            <div class="grid gap-3 border-t border-slate-100 p-4 lg:grid-cols-2">
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-700">Text B</div>

                        <div class="flex gap-1.5">
                            <button
                                type="button"
                                @click="useCurrentForComparison()"
                                class="rounded-md border border-slate-200 px-2 py-1 text-[10px] font-medium text-slate-600 hover:bg-slate-50"
                            >
                                Use current
                            </button>

                            <button
                                type="button"
                                @click="clearComparison()"
                                class="rounded-md border border-slate-200 px-2 py-1 text-[10px] font-medium text-slate-600 hover:bg-slate-50"
                            >
                                Clear
                            </button>
                        </div>
                    </div>

                    <textarea
                        x-model="comparisonText"
                        @input="queueComparisonAnalyze()"
                        rows="8"
                        placeholder="Paste a second text here..."
                        class="aabi-wc-scrollbar w-full resize-y rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs leading-5 text-slate-700 outline-none focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                    ></textarea>
                </div>

                <div>
                    <div class="mb-2 text-xs font-semibold text-slate-700">
                        Comparison
                    </div>

                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-xs">
                            <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 text-left">Metric</th>
                                    <th class="px-3 py-2 text-right">A</th>
                                    <th class="px-3 py-2 text-right">B</th>
                                    <th class="px-3 py-2 text-right">Δ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <template x-for="metric in comparisonMetricsList" :key="metric.key">
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-slate-600" x-text="metric.label"></td>
                                        <td class="aabi-wc-number px-3 py-2 text-right text-slate-600" x-text="formatNumber(comparisonA[metric.key])"></td>
                                        <td class="aabi-wc-number px-3 py-2 text-right text-slate-600" x-text="formatNumber(comparisonB[metric.key])"></td>
                                        <td
                                            class="aabi-wc-number px-3 py-2 text-right font-semibold"
                                            :class="comparisonDelta(metric.key) > 0 ? 'text-emerald-600' : (comparisonDelta(metric.key) < 0 ? 'text-red-600' : 'text-slate-400')"
                                            x-text="formatDelta(comparisonDelta(metric.key))"
                                        ></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </details>
    </div>

    {{-- Compact footer --}}
    <div class="mt-3 flex flex-col gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-[10px] text-slate-500 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <strong class="text-slate-600">Unicode-aware:</strong>
            supports English, Urdu, Arabic, CJK, emoji and multilingual text.
        </div>

        <div class="text-left sm:text-right">
            <span class="font-medium text-slate-600">Shortcuts:</span>
            Ctrl/⌘+Enter report · Ctrl/⌘+Alt+C statistics · Ctrl/⌘+Alt+E example · Ctrl/⌘+Alt+K clear
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-show="toastMessage"
        x-transition
        x-cloak
        class="fixed bottom-5 right-5 z-50 max-w-sm rounded-lg border border-slate-200 bg-slate-900 px-4 py-3 text-xs font-medium text-white shadow-xl"
        role="status"
        aria-live="polite"
        x-text="toastMessage"
    ></div>
</div>

<script>
    window.aabiWordCounter = function () {
        return {
            text: '',
            selectedText: '',
            comparisonText: '',

            words: 0,
            characters: 0,
            charactersNoSpaces: 0,
            graphemes: 0,
            codePoints: 0,
            sentences: 0,
            paragraphs: 0,
            lines: 0,
            whitespace: 0,
            letters: 0,
            numbers: 0,
            punctuation: 0,
            emojis: 0,

            uniqueWords: 0,
            analyzedFrequencyWords: 0,

            readingWpm: 200,
            speakingWpm: 130,
            typingWpm: 40,

            readingTime: '0 sec',
            speakingTime: '0 sec',
            typingTime: '0 sec',

            averageWordLength: '0',
            averageSentenceLength: '0',
            longestWord: '',
            longestWordLength: 0,
            shortestWord: '',
            shortestWordLength: 0,

            wordTarget: 0,
            wordTargetName: '',
            contentPreset: 'custom',

            platformPreset: 'none',

            targetTimeMinutes: 0,
            targetTimeBasis: 'reading',

            language: 'auto',
            caseSensitive: false,
            stopWordFiltering: true,
            customStopWords: '',

            useSelectionOnly: false,

            frequencyDisplay: [],
            letterFrequencyDisplay: [],
            punctuationDisplay: [],
            digitFrequencyDisplay: [],

            comparisonA: {
                words: 0,
                characters: 0,
                sentences: 0,
                paragraphs: 0
            },

            comparisonB: {
                words: 0,
                characters: 0,
                sentences: 0,
                paragraphs: 0
            },

            comparisonMetricsList: [
                { key: 'words', label: 'Words' },
                { key: 'characters', label: 'Characters' },
                { key: 'sentences', label: 'Sentences' },
                { key: 'paragraphs', label: 'Paragraphs' }
            ],

            contentPresetOptions: [
                { id: 'custom', label: 'Custom', target: 0 },
                { id: 'essay', label: 'Essay', target: 1000 },
                { id: 'article', label: 'Article', target: 1500 },
                { id: 'blog', label: 'Blog', target: 1200 },
                { id: 'assignment', label: 'Assignment', target: 800 },
                { id: 'speech', label: 'Speech', target: 800 },
                { id: 'social', label: 'Social', target: 280 }
            ],

            platformPresetOptions: [
                { id: 'none', label: 'No platform limit', limit: 0 },
                { id: 'x', label: 'X post — 280 chars', limit: 280 },
                { id: 'linkedin', label: 'LinkedIn post — 3,000 chars', limit: 3000 },
                { id: 'instagram', label: 'Instagram caption — 2,200 chars', limit: 2200 },
                { id: 'youtube', label: 'YouTube description — 5,000 chars', limit: 5000 },
                { id: 'seo_title', label: 'SEO title guideline — 60 chars', limit: 60 },
                { id: 'meta_description', label: 'Meta description guideline — 160 chars', limit: 160 }
            ],

            savedLimits: [],
            newLimitName: '',

            dragging: false,
            copiedAction: '',
            shareStatus: '',
            toastMessage: '',

            analysisTimer: null,
            comparisonTimer: null,
            toastTimer: null,

            storageKey: 'aabitech_word_counter_settings_v2',
            draftKey: 'aabitech_word_counter_draft_v2',

            stopWordsByLanguage: {
                en: [
                    'a','an','and','are','as','at','be','been','being','but','by','can','could',
                    'did','do','does','for','from','had','has','have','he','her','here','hers',
                    'him','his','how','i','if','in','into','is','it','its','me','more','most',
                    'my','no','not','of','on','or','our','ours','she','should','so','some',
                    'than','that','the','their','theirs','them','then','there','these','they',
                    'this','those','to','too','up','was','we','were','what','when','where',
                    'which','who','why','will','with','would','you','your','yours'
                ],

                ur: [
                    'اور','ہے','ہیں','تھا','تھی','تھے','میں','سے','کو','کا','کی','کے',
                    'نے','پر','یہ','وہ','ایک','کے','لیے','لیکن','یا','کہ','تو','بھی'
                ],

                ar: [
                    'في','من','إلى','على','عن','أن','إن','هو','هي','هم','هذا','هذه',
                    'ذلك','تلك','و','أو','ثم','ما','لا','لم','لن','كان','كانت'
                ],

                fa: [
                    'و','در','به','از','که','این','آن','را','با','برای','است','بود','یک',
                    'اما','یا','تا','من','تو','او','ما','شما','آنها'
                ],

                zh: [],
                ja: [],
                ko: []
            },

            init() {
                this.loadSettings();
                this.loadDraft();
                this.loadSharedConfig();

                this.normalizeSettings();
                this.refreshSelection();
                this.analyze();
                this.analyzeComparison();

                this._selectionHandler = () => {
                    this.refreshSelection();
                };

                document.addEventListener('selectionchange', this._selectionHandler);
            },

            destroy() {
                if (this._selectionHandler) {
                    document.removeEventListener('selectionchange', this._selectionHandler);
                }

                if (this.analysisTimer) {
                    clearTimeout(this.analysisTimer);
                }

                if (this.comparisonTimer) {
                    clearTimeout(this.comparisonTimer);
                }
            },

            get activeText() {
                if (this.useSelectionOnly) {
                    return this.selectedText || '';
                }

                return this.text || '';
            },

            get platformConfig() {
                return this.platformPresetOptions.find(
                    item => item.id === this.platformPreset
                ) || this.platformPresetOptions[0];
            },

            get platformLimit() {
                return Number(this.platformConfig.limit || 0);
            },

            get platformLabel() {
                return this.platformConfig.label || '';
            },

            get platformExceeded() {
                return this.platformLimit > 0 && this.characters > this.platformLimit;
            },

            get platformProgress() {
                if (!this.platformLimit) {
                    return 0;
                }

                return Math.min(
                    100,
                    Math.round((this.characters / this.platformLimit) * 100)
                );
            },

            get wordTargetExceeded() {
                return this.wordTarget > 0 && this.words > this.wordTarget;
            },

            get wordTargetProgress() {
                if (!this.wordTarget) {
                    return 0;
                }

                return Math.min(
                    100,
                    Math.round((this.words / this.wordTarget) * 100)
                );
            },

            get wordTargetStatus() {
                if (!this.wordTarget) {
                    return 'No target';
                }

                if (this.words === this.wordTarget) {
                    return 'Target reached';
                }

                if (this.words < this.wordTarget) {
                    return this.formatNumber(this.wordTarget - this.words) + ' words left';
                }

                return this.formatNumber(this.words - this.wordTarget) + ' words over';
            },

            get targetTimeWpm() {
                if (this.targetTimeBasis === 'speaking') {
                    return this.speakingWpm;
                }

                if (this.targetTimeBasis === 'typing') {
                    return this.typingWpm;
                }

                return this.readingWpm;
            },

            get requiredWordsForTargetTime() {
                const minutes = Number(this.targetTimeMinutes || 0);
                const wpm = Number(this.targetTimeWpm || 0);

                if (minutes <= 0 || wpm <= 0) {
                    return 0;
                }

                return Math.round(minutes * wpm);
            },

            get reportText() {
                const selectionLabel = this.useSelectionOnly
                    ? 'Selected text'
                    : 'Full text';

                return [
                    'AabiTech Word Counter',
                    '=====================',
                    '',
                    'Scope: ' + selectionLabel,
                    '',
                    'Words: ' + this.words,
                    'Characters: ' + this.characters,
                    'Characters without spaces: ' + this.charactersNoSpaces,
                    'Sentences: ' + this.sentences,
                    'Paragraphs: ' + this.paragraphs,
                    'Lines: ' + this.lines,
                    'Whitespace: ' + this.whitespace,
                    'Letters: ' + this.letters,
                    'Numbers: ' + this.numbers,
                    'Punctuation: ' + this.punctuation,
                    'Graphemes: ' + this.graphemes,
                    'Emoji: ' + this.emojis,
                    '',
                    'Unique words: ' + this.uniqueWords,
                    'Average word length: ' + this.averageWordLength,
                    'Average sentence length: ' + this.averageSentenceLength,
                    'Longest word: ' + (this.longestWord || '—'),
                    'Shortest word: ' + (this.shortestWord || '—'),
                    '',
                    'Reading time: ' + this.readingTime,
                    'Speaking time: ' + this.speakingTime,
                    'Writing time: ' + this.typingTime,
                    '',
                    this.wordTarget > 0
                        ? 'Word target: ' + this.wordTarget + ' (' + this.wordTargetStatus + ')'
                        : 'Word target: none',
                    this.platformLimit > 0
                        ? this.platformLabel + ': ' + this.characters + '/' + this.platformLimit
                        : 'Platform limit: none',
                    ''
                ].join('\n');
            },

            formatNumber(value) {
                return new Intl.NumberFormat().format(Number(value || 0));
            },

            formatDelta(value) {
                const number = Number(value || 0);

                if (number > 0) {
                    return '+' + this.formatNumber(number);
                }

                if (number < 0) {
                    return '−' + this.formatNumber(Math.abs(number));
                }

                return '0';
            },

            formatDuration(seconds) {
                const totalSeconds = Math.max(0, Math.round(Number(seconds || 0)));

                if (totalSeconds === 0) {
                    return '0 sec';
                }

                if (totalSeconds < 60) {
                    return totalSeconds + ' sec';
                }

                const minutes = Math.floor(totalSeconds / 60);
                const remainingSeconds = totalSeconds % 60;

                if (remainingSeconds === 0) {
                    return minutes + ' min';
                }

                return minutes + 'm ' + remainingSeconds + 's';
            },

            normalizeSettings() {
                this.readingWpm = this.clampNumber(this.readingWpm, 1, 2000, 200);
                this.speakingWpm = this.clampNumber(this.speakingWpm, 1, 2000, 130);
                this.typingWpm = this.clampNumber(this.typingWpm, 1, 300, 40);

                this.wordTarget = this.clampNumber(this.wordTarget, 0, 10000000, 0);
                this.targetTimeMinutes = this.clampNumber(this.targetTimeMinutes, 0, 100000, 0);

                if (!Array.isArray(this.savedLimits)) {
                    this.savedLimits = [];
                }
            },

            clampNumber(value, min, max, fallback) {
                const number = Number(value);

                if (!Number.isFinite(number)) {
                    return fallback;
                }

                return Math.min(max, Math.max(min, Math.round(number * 100) / 100));
            },

            applyContentPreset(id) {
                const preset = this.contentPresetOptions.find(item => item.id === id);

                if (!preset) {
                    return;
                }

                this.contentPreset = preset.id;
                this.wordTarget = preset.target;

                if (preset.id === 'custom') {
                    this.wordTargetName = 'Custom target';
                } else {
                    this.wordTargetName = preset.label + ' target';
                }

                this.saveSettings();
            },

            setSpeedPreset(type) {
                if (type === 'slow') {
                    this.readingWpm = 130;
                    this.speakingWpm = 100;
                    this.typingWpm = 25;
                }

                if (type === 'average') {
                    this.readingWpm = 200;
                    this.speakingWpm = 130;
                    this.typingWpm = 40;
                }

                if (type === 'fast') {
                    this.readingWpm = 300;
                    this.speakingWpm = 170;
                    this.typingWpm = 60;
                }

                this.saveSettings();
                this.analyze();
            },

            toggleSelectionMode() {
                this.useSelectionOnly = !this.useSelectionOnly;
                this.refreshSelection();
                this.analyze();
                this.saveSettings();
            },

            refreshSelection() {
                const editor = this.$refs && this.$refs.editor;

                if (!editor) {
                    return;
                }

                const start = editor.selectionStart;
                const end = editor.selectionEnd;

                if (
                    typeof start !== 'number' ||
                    typeof end !== 'number' ||
                    end <= start
                ) {
                    this.selectedText = '';
                    return;
                }

                this.selectedText = this.text.slice(start, end);

                if (this.useSelectionOnly) {
                    this.queueAnalyze();
                }
            },

            queueAnalyze() {
                if (this.analysisTimer) {
                    clearTimeout(this.analysisTimer);
                }

                this.analysisTimer = setTimeout(() => {
                    this.analyze();
                    this.saveDraft();
                }, 60);
            },

            analyze() {
                const value = this.activeText;

                const result = this.measureText(value);

                this.words = result.words;
                this.characters = result.characters;
                this.charactersNoSpaces = result.charactersNoSpaces;
                this.graphemes = result.graphemes;
                this.codePoints = result.codePoints;
                this.sentences = result.sentences;
                this.paragraphs = result.paragraphs;
                this.lines = result.lines;
                this.whitespace = result.whitespace;
                this.letters = result.letters;
                this.numbers = result.numbers;
                this.punctuation = result.punctuation;
                this.emojis = result.emojis;

                this.uniqueWords = result.uniqueWords;
                this.readingTime = this.formatDuration(
                    this.words > 0 ? (this.words / this.readingWpm) * 60 : 0
                );

                this.speakingTime = this.formatDuration(
                    this.words > 0 ? (this.words / this.speakingWpm) * 60 : 0
                );

                this.typingTime = this.formatDuration(
                    this.words > 0 ? (this.words / this.typingWpm) * 60 : 0
                );

                this.averageWordLength = result.averageWordLength;
                this.averageSentenceLength = result.averageSentenceLength;

                this.longestWord = result.longestWord;
                this.longestWordLength = result.longestWordLength;

                this.shortestWord = result.shortestWord;
                this.shortestWordLength = result.shortestWordLength;

                this.frequencyDisplay = this.buildFrequency(result.wordList);
                this.letterFrequencyDisplay = this.buildCharacterFrequency(
                    value,
                    'letter'
                );
                this.punctuationDisplay = this.buildCharacterFrequency(
                    value,
                    'punctuation'
                );
                this.digitFrequencyDisplay = this.buildCharacterFrequency(
                    value,
                    'number'
                );

                this.analyzedFrequencyWords = this.frequencyDisplay.reduce(
                    (sum, item) => sum + item.count,
                    0
                );
            },

            measureText(value) {
                const text = String(value || '');

                const codePointArray = Array.from(text);
                const characterCount = codePointArray.length;

                const graphemeArray = this.getGraphemes(text);
                const graphemeCount = graphemeArray.length;

                const wordList = this.getWordList(text);
                const wordCount = wordList.length;

                const whitespaceMatches = text.match(/\s/gu) || [];
                const letterMatches = text.match(/\p{L}/gu) || [];
                const numberMatches = text.match(/\p{N}/gu) || [];
                const punctuationMatches = text.match(/\p{P}/gu) || [];

                const sentenceCount = this.countSentences(text);
                const paragraphCount = this.countParagraphs(text);
                const lineCount = text ? text.split(/\r\n|\r|\n/).length : 0;

                const normalizedWords = wordList
                    .map(word => this.normalizeFrequencyWord(word))
                    .filter(Boolean);

                const uniqueSet = new Set(normalizedWords);

                let totalWordCharacters = 0;
                let longest = '';
                let shortest = '';

                wordList.forEach(word => {
                    const length = Array.from(word).length;

                    totalWordCharacters += length;

                    if (!longest || length > Array.from(longest).length) {
                        longest = word;
                    }

                    if (!shortest || length < Array.from(shortest).length) {
                        shortest = word;
                    }
                });

                const averageWordLength = wordCount
                    ? (totalWordCharacters / wordCount).toFixed(2)
                    : '0';

                const averageSentenceLength = sentenceCount
                    ? (wordCount / sentenceCount).toFixed(2)
                    : '0';

                return {
                    words: wordCount,
                    characters: characterCount,
                    charactersNoSpaces: codePointArray.filter(
                        character => !/\s/u.test(character)
                    ).length,
                    graphemes: graphemeCount,
                    codePoints: characterCount,
                    sentences: sentenceCount,
                    paragraphs: paragraphCount,
                    lines: lineCount,
                    whitespace: whitespaceMatches.length,
                    letters: letterMatches.length,
                    numbers: numberMatches.length,
                    punctuation: punctuationMatches.length,
                    emojis: this.countEmoji(graphemeArray),
                    uniqueWords: uniqueSet.size,
                    wordList: wordList,
                    averageWordLength: averageWordLength,
                    averageSentenceLength: averageSentenceLength,
                    longestWord: longest,
                    longestWordLength: longest
                        ? Array.from(longest).length
                        : 0,
                    shortestWord: shortest,
                    shortestWordLength: shortest
                        ? Array.from(shortest).length
                        : 0
                };
            },

            getWordList(text) {
                if (!text) {
                    return [];
                }

                try {
                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        const locale = this.language === 'auto'
                            ? undefined
                            : this.language;

                        const segmenter = new Intl.Segmenter(locale, {
                            granularity: 'word'
                        });

                        return Array.from(segmenter.segment(text))
                            .filter(part => part.isWordLike)
                            .map(part => this.cleanWord(part.segment))
                            .filter(Boolean);
                    }
                } catch (error) {
                    // Fallback below.
                }

                return text.match(
                    /[\p{L}\p{N}]+(?:['’_-][\p{L}\p{N}]+)*/gu
                ) || [];
            },

            cleanWord(word) {
                return String(word || '')
                    .normalize('NFC')
                    .replace(/^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/gu, '');
            },

            normalizeFrequencyWord(word) {
                const cleaned = this.cleanWord(word);

                if (!cleaned) {
                    return '';
                }

                if (this.caseSensitive) {
                    return cleaned;
                }

                if (this.language !== 'auto') {
                    return cleaned.toLocaleLowerCase(this.language);
                }

                return cleaned.toLocaleLowerCase();
            },

            getGraphemes(text) {
                if (!text) {
                    return [];
                }

                try {
                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        const segmenter = new Intl.Segmenter(undefined, {
                            granularity: 'grapheme'
                        });

                        return Array.from(segmenter.segment(text))
                            .map(part => part.segment);
                    }
                } catch (error) {
                    // Fallback below.
                }

                return Array.from(text);
            },

            countEmoji(graphemes) {
                let emojiRegex = null;

                try {
                    emojiRegex = new RegExp('\\p{Extended_Pictographic}', 'u');
                } catch (error) {
                    emojiRegex = null;
                }

                if (!emojiRegex) {
                    return 0;
                }

                return graphemes.filter(item => emojiRegex.test(item)).length;
            },

            countSentences(text) {
                const value = String(text || '').trim();

                if (!value) {
                    return 0;
                }

                const matches = value.match(
                    /[^.!?。！？…]+[.!?。！？…]+|[^.!?。！？…]+$/gu
                );

                return matches
                    ? matches.filter(item => item.trim()).length
                    : 0;
            },

            countParagraphs(text) {
                const value = String(text || '').trim();

                if (!value) {
                    return 0;
                }

                return value
                    .split(/\r?\n\s*\r?\n+/u)
                    .filter(item => item.trim())
                    .length;
            },

            getStopWordSet() {
                const base = this.stopWordsByLanguage[this.language] || [];

                const custom = String(this.customStopWords || '')
                    .split(/[\n,]+/u)
                    .map(item => this.normalizeFrequencyWord(item))
                    .filter(Boolean);

                return new Set([
                    ...base.map(item => {
                        if (this.caseSensitive) {
                            return item;
                        }

                        if (this.language !== 'auto') {
                            return item.toLocaleLowerCase(this.language);
                        }

                        return item.toLocaleLowerCase();
                    }),
                    ...custom
                ]);
            },

            buildFrequency(wordList) {
                const frequency = new Map();
                const stopWords = this.stopWordFiltering
                    ? this.getStopWordSet()
                    : new Set();

                wordList.forEach(word => {
                    const normalized = this.normalizeFrequencyWord(word);

                    if (!normalized) {
                        return;
                    }

                    if (stopWords.has(normalized)) {
                        return;
                    }

                    frequency.set(
                        normalized,
                        (frequency.get(normalized) || 0) + 1
                    );
                });

                const totalWords = wordList.length;

                return Array.from(frequency.entries())
                    .map(([word, count]) => ({
                        word,
                        count,
                        percentage: totalWords
                            ? ((count / totalWords) * 100).toFixed(2)
                            : '0.00'
                    }))
                    .sort((a, b) => {
                        if (b.count !== a.count) {
                            return b.count - a.count;
                        }

                        return a.word.localeCompare(b.word);
                    })
                    .slice(0, 30);
            },

            buildCharacterFrequency(text, type) {
                const map = new Map();

                for (const character of Array.from(String(text || ''))) {
                    let matches = false;

                    if (type === 'letter') {
                        matches = /^\p{L}$/u.test(character);
                    }

                    if (type === 'number') {
                        matches = /^\p{N}$/u.test(character);
                    }

                    if (type === 'punctuation') {
                        matches = /^\p{P}$/u.test(character);
                    }

                    if (!matches) {
                        continue;
                    }

                    let key = character.normalize('NFC');

                    if (!this.caseSensitive && type === 'letter') {
                        if (this.language !== 'auto') {
                            key = key.toLocaleLowerCase(this.language);
                        } else {
                            key = key.toLocaleLowerCase();
                        }
                    }

                    map.set(key, (map.get(key) || 0) + 1);
                }

                return Array.from(map.entries())
                    .map(([char, count]) => ({
                        char,
                        count
                    }))
                    .sort((a, b) => {
                        if (b.count !== a.count) {
                            return b.count - a.count;
                        }

                        return a.char.localeCompare(b.char);
                    })
                    .slice(0, 30);
            },

            queueComparisonAnalyze() {
                if (this.comparisonTimer) {
                    clearTimeout(this.comparisonTimer);
                }

                this.comparisonTimer = setTimeout(() => {
                    this.analyzeComparison();
                }, 80);
            },

            analyzeComparison() {
                this.comparisonA = this.measureSummary(this.activeText);
                this.comparisonB = this.measureSummary(this.comparisonText);
            },

            measureSummary(value) {
                const result = this.measureText(value);

                return {
                    words: result.words,
                    characters: result.characters,
                    sentences: result.sentences,
                    paragraphs: result.paragraphs
                };
            },

            comparisonDelta(key) {
                return Number(this.comparisonB[key] || 0) -
                    Number(this.comparisonA[key] || 0);
            },

            useCurrentForComparison() {
                this.comparisonText = this.text;
                this.analyzeComparison();
            },

            clearComparison() {
                this.comparisonText = '';
                this.analyzeComparison();
            },

            saveCurrentLimit() {
                const limit = Number(this.wordTarget || 0);
                const name = String(this.newLimitName || '').trim();

                if (!limit || limit < 1) {
                    this.showToast('Set a word target first.');
                    return;
                }

                if (!name) {
                    this.showToast('Enter a name for the saved limit.');
                    return;
                }

                const id = (
                    typeof crypto !== 'undefined' &&
                    typeof crypto.randomUUID === 'function'
                )
                    ? crypto.randomUUID()
                    : String(Date.now());

                this.savedLimits.unshift({
                    id: id,
                    name: name,
                    limit: limit
                });

                this.savedLimits = this.savedLimits.slice(0, 20);
                this.newLimitName = '';

                this.saveSettings();
                this.showToast('Word limit saved.');
            },

            applySavedLimit(limit) {
                this.wordTarget = Number(limit.limit || 0);
                this.wordTargetName = limit.name || 'Saved target';
                this.contentPreset = 'custom';

                this.saveSettings();
                this.showToast('Saved word limit applied.');
            },

            deleteSavedLimit(id) {
                this.savedLimits = this.savedLimits.filter(
                    item => item.id !== id
                );

                this.saveSettings();
            },

            async copyText() {
                if (!this.text) {
                    this.showToast('There is no text to copy.');
                    return;
                }

                await this.copyValue(this.text, 'text', 'Copied to clipboard');
            },

            async copyReport() {
                await this.copyValue(
                    this.reportText,
                    'report',
                    'Statistics report copied'
                );
            },

            async copyValue(value, action, message) {
                try {
                    if (
                        navigator.clipboard &&
                        typeof navigator.clipboard.writeText === 'function'
                    ) {
                        await navigator.clipboard.writeText(value);
                    } else {
                        this.legacyCopy(value);
                    }

                    this.copiedAction = action;
                    this.showToast(message);

                    setTimeout(() => {
                        if (this.copiedAction === action) {
                            this.copiedAction = '';
                        }
                    }, 1500);
                } catch (error) {
                    try {
                        this.legacyCopy(value);
                        this.copiedAction = action;
                        this.showToast(message);
                    } catch (fallbackError) {
                        this.showToast('Copy failed. Please copy manually.');
                    }
                }
            },

            legacyCopy(value) {
                const textarea = document.createElement('textarea');

                textarea.value = value;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                textarea.style.pointerEvents = 'none';

                document.body.appendChild(textarea);

                textarea.focus();
                textarea.select();

                const successful = document.execCommand('copy');

                textarea.remove();

                if (!successful) {
                    throw new Error('Copy command failed.');
                }
            },

            async pasteFromClipboard() {
                try {
                    if (
                        navigator.clipboard &&
                        typeof navigator.clipboard.readText === 'function'
                    ) {
                        const clipboardText = await navigator.clipboard.readText();

                        if (clipboardText) {
                            this.text = clipboardText;
                            this.analyze();
                            this.saveDraft();
                            this.showToast('Text pasted from clipboard.');
                            return;
                        }
                    }

                    this.showToast('Clipboard access is not available.');
                } catch (error) {
                    this.showToast('Clipboard permission was not granted.');
                }
            },

            loadExample() {
                this.text = [
                    'Clear writing makes ideas easier to understand.',
                    'Students can use a word counter to check essays, assignments, articles and speeches before submission.',
                    '',
                    'اردو میں بھی متن کی گنتی کی جا سکتی ہے۔ یہ ٹول Unicode اور emoji کو بھی بہتر انداز میں handle کرتا ہے. 😊',
                    '',
                    'Good writing is not only about reaching a word count. It is also about clarity, structure, useful information, and meaningful communication.'
                ].join('\n');

                this.contentPreset = 'essay';
                this.wordTarget = 1000;
                this.wordTargetName = 'Essay target';

                this.analyze();
                this.saveDraft();

                this.showToast('Example text loaded.');
            },

            clearText() {
                this.text = '';
                this.selectedText = '';

                if (this.$refs.editor) {
                    this.$refs.editor.value = '';
                }

                this.analyze();
                this.saveDraft();

                this.showToast('Text cleared.');
            },

            downloadText() {
                if (!this.text) {
                    this.showToast('There is no text to download.');
                    return;
                }

                this.downloadBlob(
                    this.text,
                    'aabitech-word-counter-text.txt',
                    'text/plain;charset=utf-8'
                );

                this.showToast('TXT file downloaded.');
            },

            downloadReport() {
                this.downloadBlob(
                    this.reportText,
                    'aabitech-word-counter-report.txt',
                    'text/plain;charset=utf-8'
                );

                this.showToast('Report downloaded.');
            },

            downloadBlob(content, filename, mimeType) {
                const blob = new Blob(
                    [content],
                    { type: mimeType }
                );

                const url = URL.createObjectURL(blob);
                const anchor = document.createElement('a');

                anchor.href = url;
                anchor.download = filename;

                document.body.appendChild(anchor);
                anchor.click();
                anchor.remove();

                setTimeout(() => URL.revokeObjectURL(url), 1000);
            },

            importSelectedFile(event) {
                const file = event.target.files && event.target.files[0];

                if (file) {
                    this.handleFile(file);
                }

                event.target.value = '';
            },

            dropFile(event) {
                this.dragging = false;

                const files = event.dataTransfer && event.dataTransfer.files;

                if (!files || !files.length) {
                    return;
                }

                this.handleFile(files[0]);
            },

            async handleFile(file) {
                const filename = String(file.name || '').toLowerCase();
                const type = String(file.type || '').toLowerCase();

                if (
                    type !== 'text/plain' &&
                    !filename.endsWith('.txt')
                ) {
                    this.showToast('Please select a TXT file.');
                    return;
                }

                const maxSize = 5 * 1024 * 1024;

                if (file.size > maxSize) {
                    this.showToast('File is larger than the 5 MB limit.');
                    return;
                }

                try {
                    const content = await file.text();

                    this.text = content;
                    this.analyze();
                    this.saveDraft();

                    this.showToast('TXT file imported.');
                } catch (error) {
                    this.showToast('Unable to read the selected file.');
                }
            },

            saveDraft() {
                try {
                    const draft = String(this.text || '');

                    if (draft.length > 1000000) {
                        localStorage.removeItem(this.draftKey);
                        return;
                    }

                    localStorage.setItem(
                        this.draftKey,
                        draft
                    );
                } catch (error) {
                    // Local storage may be disabled or full.
                }
            },

            loadDraft() {
                try {
                    const draft = localStorage.getItem(this.draftKey);

                    if (draft && !this.text) {
                        this.text = draft;
                    }
                } catch (error) {
                    // Ignore storage errors.
                }
            },

            saveSettings() {
                try {
                    const settings = {
                        readingWpm: this.readingWpm,
                        speakingWpm: this.speakingWpm,
                        typingWpm: this.typingWpm,
                        wordTarget: this.wordTarget,
                        wordTargetName: this.wordTargetName,
                        contentPreset: this.contentPreset,
                        platformPreset: this.platformPreset,
                        targetTimeMinutes: this.targetTimeMinutes,
                        targetTimeBasis: this.targetTimeBasis,
                        language: this.language,
                        caseSensitive: this.caseSensitive,
                        stopWordFiltering: this.stopWordFiltering,
                        customStopWords: this.customStopWords,
                        useSelectionOnly: this.useSelectionOnly,
                        savedLimits: this.savedLimits
                    };

                    localStorage.setItem(
                        this.storageKey,
                        JSON.stringify(settings)
                    );
                } catch (error) {
                    // Ignore storage errors.
                }
            },

            loadSettings() {
                try {
                    const stored = localStorage.getItem(this.storageKey);

                    if (!stored) {
                        return;
                    }

                    const settings = JSON.parse(stored);

                    if (!settings || typeof settings !== 'object') {
                        return;
                    }

                    Object.keys(settings).forEach(key => {
                        if (key in this) {
                            this[key] = settings[key];
                        }
                    });
                } catch (error) {
                    // Ignore malformed local settings.
                }
            },

            loadSharedConfig() {
                try {
                    const hash = window.location.hash || '';

                    if (!hash.startsWith('#wc=')) {
                        return;
                    }

                    const encoded = hash.slice(4);

                    if (!encoded) {
                        return;
                    }

                    const config = JSON.parse(
                        decodeURIComponent(encoded)
                    );

                    if (!config || typeof config !== 'object') {
                        return;
                    }

                    const allowedKeys = [
                        'readingWpm',
                        'speakingWpm',
                        'typingWpm',
                        'wordTarget',
                        'wordTargetName',
                        'contentPreset',
                        'platformPreset',
                        'targetTimeMinutes',
                        'targetTimeBasis',
                        'language',
                        'caseSensitive',
                        'stopWordFiltering',
                        'customStopWords',
                        'useSelectionOnly'
                    ];

                    allowedKeys.forEach(key => {
                        if (Object.prototype.hasOwnProperty.call(config, key)) {
                            this[key] = config[key];
                        }
                    });

                    this.showToast('Shared counter settings loaded.');
                } catch (error) {
                    // Invalid or unrelated hash.
                }
            },

            async shareSettings() {
                const config = {
                    readingWpm: this.readingWpm,
                    speakingWpm: this.speakingWpm,
                    typingWpm: this.typingWpm,
                    wordTarget: this.wordTarget,
                    wordTargetName: this.wordTargetName,
                    contentPreset: this.contentPreset,
                    platformPreset: this.platformPreset,
                    targetTimeMinutes: this.targetTimeMinutes,
                    targetTimeBasis: this.targetTimeBasis,
                    language: this.language,
                    caseSensitive: this.caseSensitive,
                    stopWordFiltering: this.stopWordFiltering,
                    customStopWords: this.customStopWords,
                    useSelectionOnly: this.useSelectionOnly
                };

                const encoded = encodeURIComponent(
                    JSON.stringify(config)
                );

                const url =
                    window.location.origin +
                    window.location.pathname +
                    window.location.search +
                    '#wc=' +
                    encoded;

                try {
                    if (
                        navigator.clipboard &&
                        typeof navigator.clipboard.writeText === 'function'
                    ) {
                        await navigator.clipboard.writeText(url);
                    } else {
                        this.legacyCopy(url);
                    }

                    this.shareStatus = '✓ Link copied';

                    setTimeout(() => {
                        this.shareStatus = '';
                    }, 1800);
                } catch (error) {
                    this.showToast('Unable to copy the settings link.');
                }
            },

            handleShortcut(event) {
                const modifier = event.ctrlKey || event.metaKey;

                if (!modifier) {
                    return;
                }

                const key = String(event.key || '').toLowerCase();

                if (
                    key === 'enter' &&
                    !event.shiftKey &&
                    !event.altKey
                ) {
                    event.preventDefault();
                    this.copyReport();
                    return;
                }

                if (
                    event.altKey &&
                    !event.shiftKey &&
                    key === 'c'
                ) {
                    event.preventDefault();
                    this.copyReport();
                    return;
                }

                if (
                    event.altKey &&
                    !event.shiftKey &&
                    key === 'e'
                ) {
                    event.preventDefault();
                    this.loadExample();
                    return;
                }

                if (
                    event.altKey &&
                    !event.shiftKey &&
                    key === 'k'
                ) {
                    event.preventDefault();
                    this.clearText();
                }
            },

            showToast(message) {
                this.toastMessage = message;

                if (this.toastTimer) {
                    clearTimeout(this.toastTimer);
                }

                this.toastTimer = setTimeout(() => {
                    this.toastMessage = '';
                }, 2200);
            }
        };
    };
</script>