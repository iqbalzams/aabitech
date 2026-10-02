<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div
    x-data="aabiDuplicateLineRemover()"
    x-init="init()"
    x-cloak
    class="w-full"
>
    {{-- ============================================================
        MAIN WORKSPACE
    ============================================================= --}}

    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        {{-- TOOLBAR --}}
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800"
        >
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Duplicate Line Remover
                    </h2>

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Local
                    </span>
                </div>

                <p class="mt-0.5 text-xs text-slate-500">
                    Deduplicate, inspect and clean line-based text
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    @click="loadExample()"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Example
                </button>

                <button
                    type="button"
                    @click="pasteText()"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Paste
                </button>

                <button
                    type="button"
                    @click="triggerFileInput()"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Import
                </button>

                <input
                    x-ref="fileInput"
                    type="file"
                    accept=".txt,.text,.csv,.log,.md,.json,.xml,.html,.css,.js,.php"
                    class="hidden"
                    @change="handleFile($event)"
                >

                <button
                    type="button"
                    @click="swapText()"
                    :disabled="!output"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Swap
                </button>

                <button
                    type="button"
                    @click="clearAll()"
                    :disabled="!input && !output"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Clear
                </button>
            </div>
        </div>

        {{-- DROP ZONE --}}
        <div
            class="border-b border-slate-200 bg-slate-50/70 px-5 py-2.5 text-center dark:border-slate-800 dark:bg-slate-950/30"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="handleDrop($event)"
            :class="dragging ? 'bg-indigo-50 dark:bg-indigo-950/20' : ''"
        >
            <p class="text-[11px] text-slate-500">
                Drop a text file anywhere in this area
                <span class="mx-1 text-slate-300">•</span>
                Maximum 10 MB
            </p>
        </div>

        {{-- EDITORS --}}
        <div class="grid gap-5 p-5 xl:grid-cols-2">
            {{-- INPUT --}}
            <div class="min-w-0">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            Input
                        </h3>

                        <p class="text-xs text-slate-500">
                            One item per line
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-500"
                        >
                            <span x-text="formatNumber(inputLineCount)"></span>
                            lines
                        </span>
                    </div>
                </div>

                <textarea
                    x-ref="input"
                    x-model="input"
                    @input="analyze()"
                    @keydown="handleEditorKeydown($event)"
                    @select="updateSelectionStats($event)"
                    @keyup="updateSelectionStats($event)"
                    @mouseup="updateSelectionStats($event)"
                    @drop="handleTextDrop($event)"
                    spellcheck="false"
                    autocomplete="off"
                    autocapitalize="off"
                    class="h-[620px] min-h-[420px] w-full resize-y rounded-xl border border-slate-200 bg-slate-50 p-4 font-mono text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-500 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"
                    placeholder="Paste or type one item per line..."
                ></textarea>

                <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                    <span>
                        <span x-text="formatNumber(inputCharacters)"></span>
                        characters
                        <span class="mx-1">•</span>
                        <span x-text="formatBytes(inputBytes)"></span>
                    </span>

                    <span x-show="savedStatus" x-text="savedStatus" class="text-emerald-600 dark:text-emerald-400"></span>
                </div>
            </div>

            {{-- OUTPUT --}}
            <div class="min-w-0">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            Result
                        </h3>

                        <p class="text-xs text-slate-500">
                            Processed output
                        </p>
                    </div>

                    <span
                        class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300"
                    >
                        <span x-text="formatNumber(outputLineCount)"></span>
                        lines
                    </span>
                </div>

                <textarea
                    x-model="output"
                    readonly
                    spellcheck="false"
                    class="h-[620px] min-h-[420px] w-full resize-y rounded-xl border border-slate-200 bg-slate-50 p-4 font-mono text-sm leading-6 text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    placeholder="Cleaned result will appear here..."
                ></textarea>

                <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                    <span>
                        <span x-text="formatNumber(outputCharacters)"></span>
                        characters
                        <span class="mx-1">•</span>
                        <span x-text="formatBytes(outputBytes)"></span>
                    </span>

                    <span
                        x-show="verificationMessage"
                        x-text="verificationMessage"
                        :class="verificationPassed
                            ? 'text-emerald-600 dark:text-emerald-400'
                            : 'text-amber-600 dark:text-amber-400'"
                    ></span>
                </div>
            </div>
        </div>

        {{-- PRIMARY ACTIONS --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    @click="copyOutput()"
                    :disabled="!output"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-40 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200"
                >
                    <template x-if="!copyOutputSuccess">
                        <span>Copy Output</span>
                    </template>

                    <template x-if="copyOutputSuccess">
                        <span class="inline-flex items-center gap-1.5">
                            <span>✓</span>
                            Copied
                        </span>
                    </template>
                </button>

                <button
                    type="button"
                    @click="downloadOutput()"
                    :disabled="!output"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Download
                </button>

                <button
                    type="button"
                    @click="restoreOriginal()"
                    :disabled="!originalInput"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Restore
                </button>
            </div>

            <div class="text-xs text-slate-500">
                <span
                    class="font-semibold text-slate-700 dark:text-slate-300"
                    x-text="formatNumber(removedCount)"
                ></span>
                removed
                <span class="mx-1">•</span>
                <span
                    class="font-semibold text-slate-700 dark:text-slate-300"
                    x-text="deduplicationPercentage + '%'"
                ></span>
                reduction
            </div>
        </div>
    </section>

    {{-- ============================================================
        QUICK MODE + MATCHING
    ============================================================= --}}

    <section
        class="mt-5 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Cleaning Mode
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Choose exactly what should remain in the result
                    </p>
                </div>

                <span
                    class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-500"
                >
                    Live processing
                </span>
            </div>
        </div>

        <div class="grid gap-3 p-5 sm:grid-cols-2 lg:grid-cols-4">
            <template x-for="mode in modes" :key="mode.value">
                <button
                    type="button"
                    @click="setMode(mode.value)"
                    :data-active-group="'mode-' + mode.value"
                    :class="mode.value === processingMode ? 'is-active' : ''"
                    class="rounded-xl border border-slate-200 p-4 text-left transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
                >
                    <span
                        class="block text-sm font-semibold text-slate-900 dark:text-white"
                        x-text="mode.label"
                    ></span>

                    <span
                        class="mt-1 block text-xs leading-5 text-slate-500"
                        x-text="mode.description"
                    ></span>
                </button>
            </template>
        </div>
    </section>

    {{-- ============================================================
        MATCHING OPTIONS
    ============================================================= --}}

    <section
        class="mt-5 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <h2 class="font-semibold text-slate-900 dark:text-white">
                Matching Rules
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Control how two lines are considered the same
            </p>
        </div>

        <div class="grid gap-3 p-5 sm:grid-cols-2 lg:grid-cols-4">
            {{-- CASE --}}
            <label
                class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
            >
                <input
                    type="checkbox"
                    x-model="ignoreCase"
                    @change="analyze(); saveSettings()"
                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                        Ignore case
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Apple and apple match.
                    </span>
                </span>
            </label>

            {{-- TRIM --}}
            <label
                class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
            >
                <input
                    type="checkbox"
                    x-model="trimWhitespace"
                    @change="analyze(); saveSettings()"
                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                        Trim edges
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Ignore leading and trailing whitespace.
                    </span>
                </span>
            </label>

            {{-- ALL WHITESPACE --}}
            <label
                class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
            >
                <input
                    type="checkbox"
                    x-model="ignoreWhitespace"
                    @change="analyze(); saveSettings()"
                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                        Ignore whitespace
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Ignore spaces and tabs inside lines.
                    </span>
                </span>
            </label>

            {{-- PUNCTUATION --}}
            <label
                class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
            >
                <input
                    type="checkbox"
                    x-model="ignorePunctuation"
                    @change="analyze(); saveSettings()"
                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                        Ignore punctuation
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                        Treat punctuation as irrelevant.
                    </span>
                </span>
            </label>
        </div>

        <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- KEEP --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-500">
                        Occurrence to keep
                    </label>

                    <select
                        x-model="keepMode"
                        @change="analyze(); saveSettings()"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="first">Keep first</option>
                        <option value="last">Keep last</option>
                    </select>
                </div>

                {{-- SORT --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-500">
                        Sort result
                    </label>

                    <select
                        x-model="sortMode"
                        @change="analyze(); saveSettings()"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="original">Original order</option>
                        <option value="az">A → Z</option>
                        <option value="za">Z → A</option>
                        <option value="natural">Natural numeric</option>
                        <option value="lengthAsc">Shortest first</option>
                        <option value="lengthDesc">Longest first</option>
                        <option value="reverse">Reverse</option>
                    </select>
                </div>

                {{-- BLANKS --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-500">
                        Blank lines
                    </label>

                    <select
                        x-model="blankMode"
                        @change="analyze(); saveSettings()"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="remove">Remove blanks</option>
                        <option value="keep">Keep blanks</option>
                        <option value="collapse">Collapse consecutive blanks</option>
                    </select>
                </div>

                {{-- CONSECUTIVE --}}
                <label
                    class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 dark:border-slate-700 dark:bg-slate-950"
                >
                    <input
                        type="checkbox"
                        x-model="consecutiveOnly"
                        @change="analyze(); saveSettings()"
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Consecutive duplicates only
                    </span>
                </label>
            </div>
        </div>
    </section>

    {{-- ============================================================
        ANALYSIS SUMMARY
    ============================================================= --}}

    <section class="mt-5">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">Input</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="formatNumber(inputLineCount)"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">lines</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">Unique</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="formatNumber(uniqueLineCount)"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">groups</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">Removed</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="formatNumber(removedCount)"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">duplicates</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">Duplicate rate</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="duplicatePercentage + '%'"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">of input</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">Characters</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="formatNumber(outputCharacters)"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">output</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-[11px] text-slate-500">UTF-8</p>
                <p class="mt-1.5 text-xl font-bold text-slate-950 dark:text-white" x-text="formatBytes(outputBytes)"></p>
                <p class="mt-0.5 text-[10px] text-slate-500">output size</p>
            </div>
        </div>
    </section>

    {{-- ============================================================
        ADVANCED ANALYSIS
    ============================================================= --}}

    <section
        class="mt-5 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <details>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Duplicate Analysis
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Inspect duplicate groups, line numbers and occurrence counts
                    </p>
                </div>

                <span class="text-xs text-slate-500">
                    Expand
                </span>
            </summary>

            <div class="border-t border-slate-200 dark:border-slate-800">
                <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                    <div class="text-xs text-slate-500">
                        <span
                            class="font-semibold text-slate-700 dark:text-slate-300"
                            x-text="formatNumber(duplicateGroupCount)"
                        ></span>
                        duplicate groups
                    </div>

                    <button
                        type="button"
                        @click="copyAnalysisReport()"
                        :disabled="duplicateGroups.length === 0"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span x-text="copyReportSuccess ? '✓ Copied' : 'Copy Report'"></span>
                    </button>
                </div>

                <div
                    x-show="duplicateGroups.length > 0"
                    class="max-h-[420px] overflow-auto border-t border-slate-200 dark:border-slate-800"
                >
                    <template x-for="group in duplicateGroups" :key="group.key">
                        <div class="border-b border-slate-100 px-5 py-4 last:border-b-0 dark:border-slate-800">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p
                                        class="break-all font-mono text-sm font-semibold text-slate-900 dark:text-white"
                                        x-text="group.display"
                                    ></p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Lines:
                                        <span
                                            class="font-medium text-slate-700 dark:text-slate-300"
                                            x-text="group.lineNumbers.join(', ')"
                                        ></span>
                                    </p>
                                </div>

                                <span
                                    class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-300"
                                    x-text="group.count + ' occurrences'"
                                ></span>
                            </div>
                        </div>
                    </template>

                    <div
                        x-show="duplicateGroups.length === 0"
                        class="px-5 py-8 text-center text-sm text-slate-500"
                    >
                        No duplicate groups detected.
                    </div>
                </div>

                <div
                    x-show="duplicateGroups.length === 0"
                    class="border-t border-slate-200 px-5 py-8 text-center text-sm text-slate-500 dark:border-slate-800"
                >
                    No duplicate groups detected.
                </div>
            </div>
        </details>
    </section>

    {{-- ============================================================
        COMPARISON / NORMALIZATION
    ============================================================= --}}

    <section
        class="mt-5 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <details>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Advanced Normalization
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Compare normalized values while preserving the original kept line
                    </p>
                </div>

                <span class="text-xs text-slate-500">
                    Expand
                </span>
            </summary>

            <div class="grid gap-3 border-t border-slate-200 p-5 sm:grid-cols-2 lg:grid-cols-4 dark:border-slate-800">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <input
                        type="checkbox"
                        x-model="ignoreDigits"
                        @change="analyze(); saveSettings()"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                            Ignore digits
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                            Numbers do not affect matching.
                        </span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <input
                        type="checkbox"
                        x-model="removeInvisible"
                        @change="analyze(); saveSettings()"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                            Ignore invisible characters
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                            Remove zero-width and BOM characters for comparison.
                        </span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <input
                        type="checkbox"
                        x-model="unicodeNormalize"
                        @change="analyze(); saveSettings()"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                            Unicode normalize
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                            Normalize equivalent Unicode forms.
                        </span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    <input
                        type="checkbox"
                        x-model="collapseWhitespace"
                        @change="analyze(); saveSettings()"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-900 dark:text-white">
                            Collapse whitespace
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                            Treat repeated whitespace as one separator.
                        </span>
                    </span>
                </label>
            </div>
        </details>
    </section>

    {{-- ============================================================
        FILE / PROCESSING INFORMATION
    ============================================================= --}}

    <section class="mt-5 grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-[11px] text-slate-500">Input characters</p>
            <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white" x-text="formatNumber(inputCharacters)"></p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-[11px] text-slate-500">Output characters</p>
            <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white" x-text="formatNumber(outputCharacters)"></p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-[11px] text-slate-500">Selection</p>
            <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white" x-text="formatNumber(selectionCharacters)"></p>
        </div>
    </section>

    {{-- ============================================================
        ALPINE LOGIC
    ============================================================= --}}

    @script
    <script>
        window.aabiDuplicateLineRemover = function () {
            return {
                input: '',
                output: '',
                originalInput: '',

                processingMode: 'dedupe',

                modes: [
                    {
                        value: 'dedupe',
                        label: 'Remove duplicates',
                        description: 'Keep one occurrence of every repeated line.'
                    },
                    {
                        value: 'unique',
                        label: 'Unique only',
                        description: 'Keep only lines that occur exactly once.'
                    },
                    {
                        value: 'duplicates',
                        label: 'Duplicates only',
                        description: 'Show lines that occur more than once.'
                    },
                    {
                        value: 'consecutive',
                        label: 'Consecutive only',
                        description: 'Remove only directly repeated lines.'
                    }
                ],

                keepMode: 'first',

                ignoreCase: false,
                trimWhitespace: true,
                ignoreWhitespace: false,
                ignorePunctuation: false,
                ignoreDigits: false,
                removeInvisible: true,
                unicodeNormalize: true,
                collapseWhitespace: false,

                blankMode: 'remove',

                sortMode: 'original',

                consecutiveOnly: false,

                inputLineCount: 0,
                outputLineCount: 0,

                uniqueLineCount: 0,
                removedCount: 0,

                duplicateGroupCount: 0,
                duplicatePercentage: 0,
                deduplicationPercentage: 0,

                emptyLineCount: 0,

                inputCharacters: 0,
                outputCharacters: 0,

                inputBytes: 0,
                outputBytes: 0,

                selectionCharacters: 0,
                selectionLines: 0,

                duplicateGroups: [],

                verificationPassed: true,
                verificationMessage: '',

                copyOutputSuccess: false,
                copyReportSuccess: false,

                dragging: false,

                savedStatus: '',
                saveTimer: null,

                maxFileSize: 10 * 1024 * 1024,

                init() {
                    this.loadSettings();
                    this.loadDraft();
                    this.analyze();

                    window.addEventListener('beforeunload', () => {
                        this.saveDraft();
                    });

                    window.addEventListener('paste', () => {
                        setTimeout(() => {
                            this.updateSelectionStats();
                        }, 0);
                    });
                },

                setMode(mode) {
                    this.processingMode = mode;

                    if (mode === 'consecutive') {
                        this.consecutiveOnly = true;
                    } else {
                        this.consecutiveOnly = false;
                    }

                    this.analyze();
                    this.saveSettings();
                },

                analyze() {
                    const source = String(this.input || '');

                    this.inputCharacters = source.length;
                    this.inputBytes = this.getUtf8Bytes(source);

                    if (!source) {
                        this.resetAnalysis();
                        this.saveDraftDebounced();
                        return;
                    }

                    const rawLines = this.splitLines(source);

                    this.inputLineCount = rawLines.length;

                    this.emptyLineCount = rawLines.filter(
                        line => !String(line).trim()
                    ).length;

                    this.buildDuplicateGroups(rawLines);

                    let result;

                    switch (this.processingMode) {
                        case 'unique':
                            result = this.getUniqueOnly(rawLines);
                            break;

                        case 'duplicates':
                            result = this.getDuplicatesOnly(rawLines);
                            break;

                        case 'consecutive':
                            result = this.removeConsecutiveDuplicates(rawLines);
                            break;

                        case 'dedupe':
                        default:
                            result = this.dedupeLines(rawLines);
                            break;
                    }

                    result = this.applyBlankMode(result);

                    result = this.sortLines(result);

                    this.output = result.join('\n');

                    this.outputLineCount = result.length;

                    this.outputCharacters = this.output.length;
                    this.outputBytes = this.getUtf8Bytes(this.output);

                    this.uniqueLineCount = this.getUniqueCount(rawLines);

                    this.removedCount = Math.max(
                        0,
                        this.inputLineCount - this.outputLineCount
                    );

                    const duplicateLines = Math.max(
                        0,
                        this.inputLineCount -
                        this.emptyLineCount -
                        this.uniqueLineCount
                    );

                    this.duplicatePercentage = this.inputLineCount
                        ? this.roundPercentage(
                            duplicateLines,
                            this.inputLineCount
                        )
                        : 0;

                    this.deduplicationPercentage = this.inputLineCount
                        ? this.roundPercentage(
                            this.removedCount,
                            this.inputLineCount
                        )
                        : 0;

                    this.verifyOutput();

                    this.saveDraftDebounced();
                },

                resetAnalysis() {
                    this.output = '';

                    this.inputLineCount = 0;
                    this.outputLineCount = 0;

                    this.uniqueLineCount = 0;
                    this.removedCount = 0;

                    this.duplicateGroupCount = 0;
                    this.duplicatePercentage = 0;
                    this.deduplicationPercentage = 0;

                    this.emptyLineCount = 0;

                    this.inputCharacters = 0;
                    this.outputCharacters = 0;

                    this.inputBytes = 0;
                    this.outputBytes = 0;

                    this.duplicateGroups = [];

                    this.verificationPassed = true;
                    this.verificationMessage = '';

                    this.updateSelectionStats();
                },

                splitLines(value) {
                    return String(value || '').split(/\r\n|\r|\n/);
                },

                prepareForComparison(line) {
                    let value = String(line ?? '');

                    if (this.removeInvisible) {
                        value = value.replace(
                            /[\u0000\u200B-\u200D\uFEFF\u2060]/g,
                            ''
                        );
                    }

                    if (this.trimWhitespace) {
                        value = value.trim();
                    }

                    if (this.ignoreWhitespace || this.collapseWhitespace) {
                        value = value.replace(/\s+/gu, ' ');
                    }

                    if (this.ignorePunctuation) {
                        value = value.replace(
                            /[\p{P}\p{S}]/gu,
                            ''
                        );
                    }

                    if (this.ignoreDigits) {
                        value = value.replace(
                            /\p{N}/gu,
                            ''
                        );
                    }

                    if (this.unicodeNormalize && value.normalize) {
                        value = value.normalize('NFC');
                    }

                    if (this.ignoreCase) {
                        value = value.toLocaleLowerCase();
                    }

                    return value;
                },

                getComparisonKey(line) {
                    return this.prepareForComparison(line);
                },

                buildDuplicateGroups(lines) {
                    const groups = new Map();

                    lines.forEach((line, index) => {
                        const key = this.getComparisonKey(line);

                        if (!groups.has(key)) {
                            groups.set(key, {
                                key,
                                display: line,
                                count: 0,
                                lineNumbers: []
                            });
                        }

                        const group = groups.get(key);

                        group.count++;
                        group.lineNumbers.push(index + 1);

                        if (!group.display && line) {
                            group.display = line;
                        }
                    });

                    this.duplicateGroups = Array.from(groups.values())
                        .filter(group => group.count > 1)
                        .sort((a, b) => {
                            return b.count - a.count;
                        });

                    this.duplicateGroupCount =
                        this.duplicateGroups.length;
                },

                dedupeLines(lines) {
                    if (this.keepMode === 'last') {
                        return this.keepLast(lines);
                    }

                    const seen = new Set();
                    const result = [];

                    for (const line of lines) {
                        const key = this.getComparisonKey(line);

                        if (seen.has(key)) {
                            continue;
                        }

                        seen.add(key);
                        result.push(line);
                    }

                    return result;
                },

                keepLast(lines) {
                    const seen = new Set();
                    const result = [];

                    for (let i = lines.length - 1; i >= 0; i--) {
                        const line = lines[i];
                        const key = this.getComparisonKey(line);

                        if (seen.has(key)) {
                            continue;
                        }

                        seen.add(key);
                        result.unshift(line);
                    }

                    return result;
                },

                getUniqueOnly(lines) {
                    const frequencies = this.getFrequencyMap(lines);

                    return lines.filter(line => {
                        return frequencies.get(
                            this.getComparisonKey(line)
                        ) === 1;
                    });
                },

                getDuplicatesOnly(lines) {
                    const frequencies = this.getFrequencyMap(lines);

                    const seen = new Set();
                    const result = [];

                    for (const line of lines) {
                        const key = this.getComparisonKey(line);

                        if (
                            frequencies.get(key) > 1 &&
                            !seen.has(key)
                        ) {
                            seen.add(key);
                            result.push(line);
                        }
                    }

                    return result;
                },

                removeConsecutiveDuplicates(lines) {
                    if (!lines.length) {
                        return [];
                    }

                    const result = [lines[0]];

                    for (let i = 1; i < lines.length; i++) {
                        const previous = lines[i - 1];
                        const current = lines[i];

                        if (
                            this.getComparisonKey(previous) ===
                            this.getComparisonKey(current)
                        ) {
                            continue;
                        }

                        result.push(current);
                    }

                    return result;
                },

                getFrequencyMap(lines) {
                    const frequencies = new Map();

                    for (const line of lines) {
                        const key = this.getComparisonKey(line);

                        frequencies.set(
                            key,
                            (frequencies.get(key) || 0) + 1
                        );
                    }

                    return frequencies;
                },

                getUniqueCount(lines) {
                    const keys = new Set();

                    for (const line of lines) {
                        keys.add(this.getComparisonKey(line));
                    }

                    return keys.size;
                },

                applyBlankMode(lines) {
                    if (this.blankMode === 'remove') {
                        return lines.filter(
                            line => String(line).trim() !== ''
                        );
                    }

                    if (this.blankMode === 'collapse') {
                        const result = [];
                        let previousBlank = false;

                        for (const line of lines) {
                            const blank =
                                String(line).trim() === '';

                            if (blank && previousBlank) {
                                continue;
                            }

                            result.push(line);
                            previousBlank = blank;
                        }

                        return result;
                    }

                    return lines;
                },

                sortLines(lines) {
                    const result = [...lines];

                    if (this.sortMode === 'original') {
                        return result;
                    }

                    if (this.sortMode === 'reverse') {
                        return result.reverse();
                    }

                    if (this.sortMode === 'lengthAsc') {
                        return result.sort((a, b) => {
                            const difference =
                                Array.from(a).length -
                                Array.from(b).length;

                            return difference ||
                                a.localeCompare(b);
                        });
                    }

                    if (this.sortMode === 'lengthDesc') {
                        return result.sort((a, b) => {
                            const difference =
                                Array.from(b).length -
                                Array.from(a).length;

                            return difference ||
                                a.localeCompare(b);
                        });
                    }

                    if (this.sortMode === 'natural') {
                        return result.sort((a, b) =>
                            a.localeCompare(
                                b,
                                undefined,
                                {
                                    numeric: true,
                                    sensitivity: this.ignoreCase
                                        ? 'base'
                                        : 'variant'
                                }
                            )
                        );
                    }

                    if (this.sortMode === 'az') {
                        return result.sort((a, b) =>
                            a.localeCompare(
                                b,
                                undefined,
                                {
                                    numeric: false,
                                    sensitivity: this.ignoreCase
                                        ? 'base'
                                        : 'variant'
                                }
                            )
                        );
                    }

                    if (this.sortMode === 'za') {
                        return result.sort((a, b) =>
                            b.localeCompare(
                                a,
                                undefined,
                                {
                                    numeric: false,
                                    sensitivity: this.ignoreCase
                                        ? 'base'
                                        : 'variant'
                                }
                            )
                        );
                    }

                    return result;
                },

                verifyOutput() {
                    if (!this.output) {
                        this.verificationPassed = true;
                        this.verificationMessage = '';
                        return;
                    }

                    const lines = this.splitLines(this.output);

                    if (
                        this.processingMode === 'dedupe' ||
                        this.processingMode === 'consecutive'
                    ) {
                        const seen = new Set();
                        let valid = true;

                        for (const line of lines) {
                            const key = this.getComparisonKey(line);

                            if (
                                this.processingMode === 'dedupe' &&
                                seen.has(key)
                            ) {
                                valid = false;
                                break;
                            }

                            seen.add(key);
                        }

                        this.verificationPassed = valid;

                        this.verificationMessage = valid
                            ? '✓ No unintended duplicates'
                            : 'Review duplicate output';
                    } else {
                        this.verificationPassed = true;
                        this.verificationMessage = '';
                    }
                },

                roundPercentage(value, total) {
                    if (!total) {
                        return 0;
                    }

                    return Math.round(
                        (value / total) * 1000
                    ) / 10;
                },

                getUtf8Bytes(value) {
                    try {
                        if (
                            typeof TextEncoder !== 'undefined'
                        ) {
                            return new TextEncoder().encode(
                                String(value || '')
                            ).length;
                        }

                        return unescape(
                            encodeURIComponent(
                                String(value || '')
                            )
                        ).length;
                    } catch {
                        return String(value || '').length;
                    }
                },

                formatBytes(bytes) {
                    const value = Number(bytes) || 0;

                    if (value < 1024) {
                        return `${value} B`;
                    }

                    if (value < 1024 * 1024) {
                        return `${(value / 1024).toFixed(1)} KB`;
                    }

                    if (value < 1024 * 1024 * 1024) {
                        return `${(value / (1024 * 1024)).toFixed(1)} MB`;
                    }

                    return `${(
                        value /
                        (1024 * 1024 * 1024)
                    ).toFixed(1)} GB`;
                },

                formatNumber(value) {
                    return Number(
                        value || 0
                    ).toLocaleString();
                },

                updateSelectionStats(event = null) {
                    const textarea =
                        event?.target ||
                        this.$refs.input;

                    if (!textarea) {
                        this.selectionCharacters = 0;
                        this.selectionLines = 0;
                        return;
                    }

                    const start =
                        textarea.selectionStart ?? 0;

                    const end =
                        textarea.selectionEnd ?? 0;

                    if (end <= start) {
                        this.selectionCharacters = 0;
                        this.selectionLines = 0;
                        return;
                    }

                    const selected =
                        this.input.slice(start, end);

                    this.selectionCharacters =
                        selected.length;

                    this.selectionLines =
                        selected
                            ? this.splitLines(selected).length
                            : 0;
                },

                handleEditorKeydown(event) {
                    const modifier =
                        event.ctrlKey ||
                        event.metaKey;

                    if (
                        modifier &&
                        event.shiftKey &&
                        event.key.toLowerCase() === 'c'
                    ) {
                        event.preventDefault();
                        this.copyOutput();
                        return;
                    }

                    if (
                        modifier &&
                        event.shiftKey &&
                        event.key.toLowerCase() === 'k'
                    ) {
                        event.preventDefault();
                        this.copyAnalysisReport();
                        return;
                    }

                    if (
                        modifier &&
                        event.shiftKey &&
                        event.key.toLowerCase() === 'l'
                    ) {
                        event.preventDefault();
                        this.clearAll();
                    }
                },

                async pasteText() {
                    try {
                        if (
                            !navigator.clipboard ||
                            !navigator.clipboard.readText
                        ) {
                            throw new Error('Clipboard unavailable');
                        }

                        const text =
                            await navigator.clipboard.readText();

                        if (!text) {
                            this.showToast(
                                'Clipboard is empty.'
                            );
                            return;
                        }

                        this.originalInput = this.input;
                        this.input = text;

                        this.analyze();

                        this.showToast(
                            'Text pasted.'
                        );
                    } catch {
                        this.showToast(
                            'Clipboard access was blocked. Paste manually.'
                        );
                    }
                },

                async copyOutput() {
                    if (!this.output) {
                        return;
                    }

                    const success =
                        await this.copyText(this.output);

                    if (success) {
                        this.copyOutputSuccess = true;

                        setTimeout(() => {
                            this.copyOutputSuccess = false;
                        }, 1600);

                        this.showToast(
                            'Cleaned result copied.'
                        );
                    } else {
                        this.showToast(
                            'Unable to copy automatically.'
                        );
                    }
                },

                async copyAnalysisReport() {
                    if (!this.duplicateGroups.length) {
                        return;
                    }

                    const lines = [
                        'AabiTech Duplicate Line Analysis',
                        '================================',
                        '',
                        `Input lines: ${this.inputLineCount}`,
                        `Unique lines: ${this.uniqueLineCount}`,
                        `Duplicate groups: ${this.duplicateGroupCount}`,
                        `Duplicates removed: ${this.removedCount}`,
                        `Duplicate rate: ${this.duplicatePercentage}%`,
                        '',
                        'Duplicate Groups',
                        '----------------'
                    ];

                    this.duplicateGroups.forEach(
                        (group, index) => {
                            lines.push(
                                `${index + 1}. ${group.display}`
                            );

                            lines.push(
                                `   Occurrences: ${group.count}`
                            );

                            lines.push(
                                `   Lines: ${group.lineNumbers.join(', ')}`
                            );

                            lines.push('');
                        }
                    );

                    const success =
                        await this.copyText(
                            lines.join('\n')
                        );

                    if (success) {
                        this.copyReportSuccess = true;

                        setTimeout(() => {
                            this.copyReportSuccess = false;
                        }, 1600);

                        this.showToast(
                            'Analysis report copied.'
                        );
                    }
                },

                async copyText(value) {
                    try {
                        if (
                            navigator.clipboard &&
                            navigator.clipboard.writeText
                        ) {
                            await navigator.clipboard.writeText(
                                value
                            );

                            return true;
                        }
                    } catch {
                        // Continue to fallback.
                    }

                    return this.fallbackCopy(value);
                },

                fallbackCopy(value) {
                    const textarea =
                        document.createElement('textarea');

                    textarea.value = value;
                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    textarea.style.top = '0';
                    textarea.setAttribute(
                        'readonly',
                        ''
                    );

                    document.body.appendChild(
                        textarea
                    );

                    textarea.select();
                    textarea.setSelectionRange(
                        0,
                        textarea.value.length
                    );

                    let success = false;

                    try {
                        success =
                            document.execCommand(
                                'copy'
                            );
                    } catch {
                        success = false;
                    }

                    textarea.remove();

                    return success;
                },

                downloadOutput() {
                    if (!this.output) {
                        return;
                    }

                    const blob = new Blob(
                        [this.output],
                        {
                            type: 'text/plain;charset=utf-8'
                        }
                    );

                    const url =
                        URL.createObjectURL(blob);

                    const anchor =
                        document.createElement('a');

                    anchor.href = url;
                    anchor.download =
                        'aabitech-duplicate-lines-cleaned.txt';

                    document.body.appendChild(
                        anchor
                    );

                    anchor.click();
                    anchor.remove();

                    URL.revokeObjectURL(url);

                    this.showToast(
                        'Cleaned result downloaded.'
                    );
                },

                triggerFileInput() {
                    this.$refs.fileInput?.click();
                },

                handleDrop(event) {
                    this.dragging = false;

                    const file =
                        event.dataTransfer?.files?.[0];

                    if (!file) {
                        return;
                    }

                    this.readFile(file);
                },

                handleTextDrop(event) {
                    const files =
                        event.dataTransfer?.files;

                    if (!files || !files.length) {
                        return;
                    }

                    event.preventDefault();

                    this.readFile(files[0]);
                },

                handleFile(event) {
                    const file =
                        event.target?.files?.[0];

                    if (!file) {
                        return;
                    }

                    this.readFile(file);

                    event.target.value = '';
                },

                readFile(file) {
                    if (file.size > this.maxFileSize) {
                        this.showToast(
                            'File is larger than 10 MB.'
                        );
                        return;
                    }

                    const reader =
                        new FileReader();

                    reader.onload = () => {
                        const text =
                            String(reader.result || '');

                        this.originalInput =
                            this.input;

                        this.input = text;

                        this.analyze();

                        this.showToast(
                            `${file.name} imported.`
                        );
                    };

                    reader.onerror = () => {
                        this.showToast(
                            'Unable to read the selected file.'
                        );
                    };

                    reader.readAsText(file);
                },

                swapText() {
                    if (!this.output) {
                        return;
                    }

                    const current =
                        this.input;

                    this.input =
                        this.output;

                    this.originalInput =
                        current;

                    this.analyze();

                    this.showToast(
                        'Input and output swapped.'
                    );
                },

                restoreOriginal() {
                    if (!this.originalInput) {
                        return;
                    }

                    this.input =
                        this.originalInput;

                    this.analyze();

                    this.showToast(
                        'Original input restored.'
                    );
                },

                clearAll() {
                    this.originalInput =
                        this.input || this.originalInput;

                    this.input = '';
                    this.output = '';

                    this.analyze();

                    this.showToast(
                        'Text cleared.'
                    );
                },

                loadExample() {
                    this.originalInput =
                        this.input;

                    this.input = [
                        'Apple',
                        'banana',
                        'Apple',
                        'orange',
                        'banana',
                        'apple',
                        'grape',
                        'orange',
                        'Orange',
                        'item 10',
                        'item 2',
                        'item 10',
                        'banana'
                    ].join('\n');

                    this.analyze();

                    this.showToast(
                        'Example loaded.'
                    );
                },

                saveDraftDebounced() {
                    clearTimeout(
                        this.saveTimer
                    );

                    this.saveTimer =
                        setTimeout(() => {
                            this.saveDraft();
                        }, 500);
                },

                saveDraft() {
                    try {
                        localStorage.setItem(
                            'aabitech_duplicate_line_draft',
                            this.input
                        );

                        this.savedStatus =
                            'Saved locally';

                        setTimeout(() => {
                            this.savedStatus = '';
                        }, 1200);
                    } catch {
                        this.savedStatus = '';
                    }
                },

                loadDraft() {
                    try {
                        const draft =
                            localStorage.getItem(
                                'aabitech_duplicate_line_draft'
                            );

                        if (draft) {
                            this.input = draft;
                        }
                    } catch {
                        // Ignore storage failures.
                    }
                },

                saveSettings() {
                    try {
                        localStorage.setItem(
                            'aabitech_duplicate_line_settings',
                            JSON.stringify({
                                processingMode:
                                    this.processingMode,

                                keepMode:
                                    this.keepMode,

                                ignoreCase:
                                    this.ignoreCase,

                                trimWhitespace:
                                    this.trimWhitespace,

                                ignoreWhitespace:
                                    this.ignoreWhitespace,

                                ignorePunctuation:
                                    this.ignorePunctuation,

                                ignoreDigits:
                                    this.ignoreDigits,

                                removeInvisible:
                                    this.removeInvisible,

                                unicodeNormalize:
                                    this.unicodeNormalize,

                                collapseWhitespace:
                                    this.collapseWhitespace,

                                blankMode:
                                    this.blankMode,

                                sortMode:
                                    this.sortMode,

                                consecutiveOnly:
                                    this.consecutiveOnly
                            })
                        );
                    } catch {
                        // Ignore storage failures.
                    }
                },

                loadSettings() {
                    try {
                        const raw =
                            localStorage.getItem(
                                'aabitech_duplicate_line_settings'
                            );

                        if (!raw) {
                            return;
                        }

                        const settings =
                            JSON.parse(raw);

                        if (
                            [
                                'dedupe',
                                'unique',
                                'duplicates',
                                'consecutive'
                            ].includes(
                                settings.processingMode
                            )
                        ) {
                            this.processingMode =
                                settings.processingMode;
                        }

                        if (
                            ['first', 'last'].includes(
                                settings.keepMode
                            )
                        ) {
                            this.keepMode =
                                settings.keepMode;
                        }

                        [
                            'ignoreCase',
                            'trimWhitespace',
                            'ignoreWhitespace',
                            'ignorePunctuation',
                            'ignoreDigits',
                            'removeInvisible',
                            'unicodeNormalize',
                            'collapseWhitespace',
                            'consecutiveOnly'
                        ].forEach(key => {
                            if (
                                typeof settings[key] ===
                                'boolean'
                            ) {
                                this[key] =
                                    settings[key];
                            }
                        });

                        if (
                            ['remove', 'keep', 'collapse']
                                .includes(settings.blankMode)
                        ) {
                            this.blankMode =
                                settings.blankMode;
                        }

                        if (
                            [
                                'original',
                                'az',
                                'za',
                                'natural',
                                'lengthAsc',
                                'lengthDesc',
                                'reverse'
                            ].includes(settings.sortMode)
                        ) {
                            this.sortMode =
                                settings.sortMode;
                        }
                    } catch {
                        // Keep defaults.
                    }
                },

                showToast(message) {
                    window.dispatchEvent(
                        new CustomEvent(
                            'aabi-toast',
                            {
                                detail: {
                                    message
                                }
                            }
                        )
                    );
                }
            };
        };
    </script>
    @endscript
</div>