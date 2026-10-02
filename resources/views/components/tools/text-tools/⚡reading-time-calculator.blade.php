<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div
    x-data="aabiReadingTimeCalculator()"
    x-cloak
    class="w-full"
>
    <div class="space-y-4">

        {{-- Privacy / local processing --}}
        <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2.5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex min-w-0 items-center gap-2">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 4.5-3 7.8-7 9-4-1.2-7-4.5-7-9V7l7-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l1.7 1.7 3.5-3.5"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                        100% browser-based
                    </p>
                    <p class="truncate text-[11px] text-slate-500 dark:text-slate-500">
                        Your text stays on your device.
                    </p>
                </div>
            </div>

            <div class="hidden shrink-0 text-[11px] text-slate-500 sm:block">
                Live calculation
            </div>
        </div>


        {{-- =========================================================
             PRIMARY WORKSPACE
             50% INPUT + 50% BASIC STATS
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            {{-- LEFT: TEXT INPUT --}}
            <section class="flex min-h-0 flex-col rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                {{-- Input header --}}
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-3 py-2.5 dark:border-slate-700">

                    <div class="flex items-center gap-1 rounded-lg bg-slate-100 p-0.5 dark:bg-slate-800">
                        <button
                            type="button"
                            data-active-group
                            :class="{ 'is-active': inputMode === 'text' }"
                            class="compact-tab"
                            @click="setInputMode('text')"
                        >
                            Text
                        </button>

                        <button
                            type="button"
                            data-active-group
                            :class="{ 'is-active': inputMode === 'words' }"
                            class="compact-tab"
                            @click="setInputMode('words')"
                        >
                            Word Count
                        </button>
                    </div>

                    <div class="flex items-center gap-1">
                        <button
                            type="button"
                            class="inline-flex h-7 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-[11px] font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="loadExample()"
                            title="Load example"
                        >
                            Example
                        </button>

                        <button
                            type="button"
                            class="inline-flex h-7 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-2.5 text-[11px] font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="$refs.fileInput.click()"
                            title="Import TXT file"
                        >
                            Import
                        </button>

                        <input
                            x-ref="fileInput"
                            type="file"
                            accept=".txt,text/plain"
                            class="hidden"
                            @change="handleFile($event)"
                        >

                        <button
                            type="button"
                            class="inline-flex h-7 items-center justify-center rounded-md border border-slate-200 bg-white px-2 text-slate-500 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="clearAll()"
                            title="Clear"
                            aria-label="Clear"
                        >
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5h6v2m-8 0 .7 12h8.6L17 7M10 11v5m4-5v5"/>
                            </svg>
                        </button>
                    </div>
                </div>


                {{-- Text mode --}}
                <div
                    x-show="inputMode === 'text'"
                    class="flex min-h-0 flex-1 flex-col"
                >
                    <div class="relative flex-1 p-3">
                        <textarea
                            x-ref="editor"
                            x-model="input"
                            @input="calculate()"
                            @paste="handlePaste()"
                            @dragover.prevent
                            @drop.prevent="handleDrop($event)"
                            @keydown.ctrl.enter.prevent="calculate()"
                            @keydown.meta.enter.prevent="calculate()"
                            @keydown.ctrl.shift.c.prevent="copyResult()"
                            @keydown.meta.shift.c.prevent="copyResult()"
                            spellcheck="true"
                            autocomplete="off"
                            class="min-h-[340px] w-full resize-y rounded-lg border border-slate-200 bg-slate-50/60 p-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-500 focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950/50 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:bg-slate-950 dark:focus:ring-indigo-950"
                            placeholder="Paste or type your article, blog post, speech, script, study material, or any text here..."
                        ></textarea>

                        <div
                            x-show="isDragging"
                            x-transition
                            class="pointer-events-none absolute inset-3 flex items-center justify-center rounded-lg border-2 border-dashed border-indigo-400 bg-indigo-50/90 dark:border-indigo-500 dark:bg-indigo-950/80"
                        >
                            <div class="text-center">
                                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L7 9m5-5l5 5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 14v4a2 2 0 002 2h10a2 2 0 002-2v-4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">
                                    Drop TXT file here
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 px-3 py-2 dark:border-slate-700">
                        <p class="text-[10px] text-slate-500">
                            Ctrl/Cmd + Enter to refresh · Ctrl/Cmd + Shift + C to copy result
                        </p>

                        <button
                            type="button"
                            class="text-[11px] font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400"
                            @click="pasteFromClipboard()"
                        >
                            Paste from clipboard
                        </button>
                    </div>
                </div>


                {{-- Word count mode --}}
                <div
                    x-show="inputMode === 'words'"
                    x-cloak
                    class="flex min-h-[410px] flex-1 flex-col justify-center p-6"
                >
                    <label class="mb-2 block text-xs font-semibold text-slate-700 dark:text-slate-200">
                        Enter total word count
                    </label>

                    <div class="relative">
                        <input
                            type="number"
                            min="0"
                            max="100000000"
                            x-model.number="wordInput"
                            @input="calculate()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 pr-20 text-2xl font-semibold tabular-nums text-slate-800 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"
                            placeholder="1000"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-500">
                            words
                        </span>
                    </div>

                    <p class="mt-3 text-xs leading-5 text-slate-500 dark:text-slate-500">
                        Direct word-count mode is useful when you already know the number of words.
                        Text-specific statistics such as characters and sentences are unavailable in this mode.
                    </p>
                </div>

            </section>


            {{-- RIGHT: BASIC STATS --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                            Basic Statistics
                        </h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Live content analysis
                        </p>
                    </div>

                    <div
                        class="rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-semibold text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-300"
                        x-text="formatNumber(effectiveWordCount()) + ' words'"
                    ></div>
                </div>


                <div class="grid grid-cols-2 gap-px bg-slate-200 dark:bg-slate-700">

                    {{-- Reading time --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Reading Time</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400"
                            x-text="formatDuration(readingSeconds())"
                        ></p>
                        <p
                            class="mt-1 text-[10px] text-slate-500"
                            x-text="readingWpm + ' WPM'"
                        ></p>
                    </div>

                    {{-- Characters --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Characters</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="formatNumber(characterCount())"
                        ></p>
                        <p class="mt-1 text-[10px] text-slate-500">
                            including spaces
                        </p>
                    </div>

                    {{-- Characters without spaces --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">No Spaces</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="formatNumber(characterCountNoSpaces())"
                        ></p>
                        <p class="mt-1 text-[10px] text-slate-500">
                            characters
                        </p>
                    </div>

                    {{-- Sentences --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Sentences</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="formatNumber(sentenceCount())"
                        ></p>
                        <p class="mt-1 text-[10px] text-slate-500">
                            detected sentences
                        </p>
                    </div>

                    {{-- Paragraphs --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Paragraphs</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="formatNumber(paragraphCount())"
                        ></p>
                        <p class="mt-1 text-[10px] text-slate-500">
                            content blocks
                        </p>
                    </div>

                    {{-- Average sentence --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Avg. Sentence</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="averageWordsPerSentence().toFixed(1)"
                        ></p>
                        <p class="mt-1 text-[10px] text-slate-500">
                            words / sentence
                        </p>
                    </div>

                    {{-- Pages --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Pages</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="pageEstimate()"
                        ></p>
                        <p
                            class="mt-1 text-[10px] text-slate-500"
                            x-text="pageWords + ' words/page'"
                        ></p>
                    </div>

                    {{-- Readability --}}
                    <div class="bg-white p-4 dark:bg-slate-900">
                        <p class="text-[11px] font-medium text-slate-500">Readability</p>
                        <p
                            class="mt-1 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100"
                            x-text="readabilityLabel()"
                        ></p>
                        <p
                            class="mt-1 truncate text-[10px] text-slate-500"
                            x-text="readabilityDescription()"
                        ></p>
                    </div>

                </div>


                {{-- Basic result footer --}}
                <div class="border-t border-slate-200 p-3 dark:border-slate-700">
                    <div class="flex flex-wrap gap-2">

                        <button
                            type="button"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md bg-indigo-600 px-3 text-xs font-semibold text-white transition hover:bg-indigo-700"
                            @click="copyResult()"
                        >
                            <template x-if="copiedAction !== 'result'">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="11" height="11" rx="2"/>
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                </svg>
                            </template>

                            <template x-if="copiedAction === 'result'">
                                <span>✓</span>
                            </template>

                            <span x-text="copiedAction === 'result' ? 'Copied' : 'Copy Result'"></span>
                        </button>

                        <button
                            type="button"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="copyBadge()"
                        >
                            <span x-text="copiedAction === 'badge' ? '✓ Copied' : 'Copy Badge'"></span>
                        </button>

                        <button
                            type="button"
                            class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="downloadResults()"
                        >
                            Download
                        </button>
                    </div>
                </div>

            </section>

        </div>


        {{-- =========================================================
             SPEED PRESETS
        ========================================================== --}}
        <section class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-900">

            <div class="mb-2.5 flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                        Reading Speed
                    </h2>
                    <p class="text-[10px] text-slate-500">
                        Choose a preset or adjust your own speed.
                    </p>
                </div>

                <div class="flex flex-wrap gap-1">
                    <button
                        type="button"
                        data-active-group
                        :class="{ 'is-active': speedPreset === 'slow' }"
                        class="compact-tab"
                        @click="applySpeedPreset('slow')"
                    >
                        Slow · 130
                    </button>

                    <button
                        type="button"
                        data-active-group
                        :class="{ 'is-active': speedPreset === 'average' }"
                        class="compact-tab"
                        @click="applySpeedPreset('average')"
                    >
                        Average · 200
                    </button>

                    <button
                        type="button"
                        data-active-group
                        :class="{ 'is-active': speedPreset === 'fast' }"
                        class="compact-tab"
                        @click="applySpeedPreset('fast')"
                    >
                        Fast · 300
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">

                <div class="rounded-lg border border-slate-200 p-2.5 dark:border-slate-700">
                    <label for="readingWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Reading WPM
                    </label>
                    <input
                        id="readingWpm"
                        type="number"
                        min="30"
                        max="1500"
                        x-model.number="readingWpm"
                        @input="settingsChanged()"
                        class="h-8 w-full rounded-md border border-slate-200 bg-slate-50 px-2 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div class="rounded-lg border border-slate-200 p-2.5 dark:border-slate-700">
                    <label for="slowWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Slow WPM
                    </label>
                    <input
                        id="slowWpm"
                        type="number"
                        min="30"
                        max="1500"
                        x-model.number="slowWpm"
                        @input="settingsChanged()"
                        class="h-8 w-full rounded-md border border-slate-200 bg-slate-50 px-2 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div class="rounded-lg border border-slate-200 p-2.5 dark:border-slate-700">
                    <label for="fastWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Fast WPM
                    </label>
                    <input
                        id="fastWpm"
                        type="number"
                        min="30"
                        max="2000"
                        x-model.number="fastWpm"
                        @input="settingsChanged()"
                        class="h-8 w-full rounded-md border border-slate-200 bg-slate-50 px-2 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div class="rounded-lg border border-slate-200 p-2.5 dark:border-slate-700">
                    <label for="skimWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Skimming WPM
                    </label>
                    <input
                        id="skimWpm"
                        type="number"
                        min="30"
                        max="2500"
                        x-model.number="skimWpm"
                        @input="settingsChanged()"
                        class="h-8 w-full rounded-md border border-slate-200 bg-slate-50 px-2 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

            </div>
        </section>


        {{-- =========================================================
             SIDE-BY-SIDE ESTIMATES
        ========================================================== --}}
        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

            <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                    Reading Time Estimates
                </h2>
                <p class="mt-0.5 text-[11px] text-slate-500">
                    Compare how long the same content takes at different speeds.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-px bg-slate-200 sm:grid-cols-3 lg:grid-cols-6 dark:bg-slate-700">

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-500">Slow</p>
                    <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100"
                       x-text="formatDuration(readingSecondsAt(slowWpm))"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="slowWpm + ' WPM'"></p>
                </div>

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-500">Average</p>
                    <p class="mt-1 text-sm font-bold text-indigo-600 dark:text-indigo-400"
                       x-text="formatDuration(readingSecondsAt(readingWpm))"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="readingWpm + ' WPM'"></p>
                </div>

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-500">Fast</p>
                    <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100"
                       x-text="formatDuration(readingSecondsAt(fastWpm))"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="fastWpm + ' WPM'"></p>
                </div>

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-500">Skimming</p>
                    <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100"
                       x-text="formatDuration(readingSecondsAt(skimWpm, false))"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="skimWpm + ' WPM'"></p>
                </div>

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-800 dark:text-slate-100">Speaking</p>
                    <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100"
                       x-text="formatDuration(speakingSeconds())"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="speakingWpm + ' WPM'"></p>
                </div>

                <div class="bg-white p-3 dark:bg-slate-900">
                    <p class="text-[10px] font-medium text-slate-800 dark:text-slate-100">Presentation</p>
                    <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-100"
                       x-text="formatDuration(presentationSeconds())"></p>
                    <p class="mt-1 text-[10px] text-slate-500"
                       x-text="presentationWpm + ' WPM'"></p>
                </div>

            </div>

        </section>


        {{-- =========================================================
             CONTENT TYPE + SPEAKING / PRESENTATION
        ========================================================== --}}
        <section class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="mb-3">
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Content Type
                    </h2>
                    <p class="mt-0.5 text-[11px] text-slate-500">
                        Apply a practical speed profile for your content.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-1.5 sm:grid-cols-3">
                    <template x-for="preset in contentPresets" :key="preset.id">
                        <button
                            type="button"
                            data-active-group
                            :class="{ 'is-active': contentType === preset.id }"
                            class="rounded-md border border-transparent px-2 py-2 text-left text-[11px] font-medium text-slate-600 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                            @click="applyContentPreset(preset.id)"
                        >
                            <span x-text="preset.label"></span>
                        </button>
                    </template>
                </div>

            </div>


            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="mb-3">
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Speaking & Presentation
                    </h2>
                    <p class="mt-0.5 text-[11px] text-slate-500">
                        Useful for speeches, lectures, presentations and scripts.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2">

                    <div>
                        <label for="speakingWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                            Speaking WPM
                        </label>
                        <input
                            id="speakingWpm"
                            type="number"
                            min="40"
                            max="500"
                            x-model.number="speakingWpm"
                            @input="settingsChanged()"
                            class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                        >
                    </div>

                    <div>
                        <label for="presentationWpm" class="mb-1 block text-[10px] font-medium text-slate-500">
                            Presentation WPM
                        </label>
                        <input
                            id="presentationWpm"
                            type="number"
                            min="40"
                            max="400"
                            x-model.number="presentationWpm"
                            @input="settingsChanged()"
                            class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                        >
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
             TARGET / BUDGET
        ========================================================== --}}
        <section class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            {{-- Target calculator --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="mb-3 flex items-start justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                            Target Reading Calculator
                        </h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Plan your content around a desired reading duration.
                        </p>
                    </div>

                    <div class="flex rounded-md bg-slate-100 p-0.5 dark:bg-slate-800">
                        <button
                            type="button"
                            data-active-group
                            :class="{ 'is-active': targetMode === 'duration' }"
                            class="compact-tab"
                            @click="targetMode = 'duration'"
                        >
                            Time
                        </button>

                        <button
                            type="button"
                            data-active-group
                            :class="{ 'is-active': targetMode === 'words' }"
                            class="compact-tab"
                            @click="targetMode = 'words'"
                        >
                            Words
                        </button>
                    </div>
                </div>


                <div x-show="targetMode === 'duration'">
                    <label for="targetMinutes" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Desired reading time
                    </label>

                    <div class="flex gap-2">
                        <input
                            id="targetMinutes"
                            type="number"
                            min="1"
                            max="1440"
                            x-model.number="targetMinutes"
                            class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                        >

                        <div class="flex h-9 shrink-0 items-center rounded-md bg-slate-100 px-3 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-500">
                            minutes
                        </div>
                    </div>

                    <div class="mt-3 rounded-lg bg-indigo-50 p-3 dark:bg-indigo-950/40">
                        <p class="text-[10px] font-medium text-indigo-700 dark:text-indigo-400">
                            Recommended word count
                        </p>
                        <p class="mt-1 text-lg font-bold text-indigo-700 dark:text-indigo-300"
                           x-text="formatNumber(targetWordCount()) + ' words'"></p>
                    </div>
                </div>


                <div x-show="targetMode === 'words'">
                    <label class="mb-1 block text-[10px] font-medium text-slate-500">
                        Target word count
                    </label>

                    <div class="flex gap-2">
                        <input
                            type="number"
                            min="1"
                            max="100000000"
                            x-model.number="targetWords"
                            class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                        >

                        <div class="flex h-9 shrink-0 items-center rounded-md bg-slate-100 px-3 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-500">
                            words
                        </div>
                    </div>

                    <div class="mt-3 rounded-lg bg-indigo-50 p-3 dark:bg-indigo-950/40">
                        <p class="text-[10px] font-medium text-indigo-700 dark:text-indigo-400">
                            Estimated reading time
                        </p>
                        <p class="mt-1 text-lg font-bold text-indigo-700 dark:text-indigo-300"
                           x-text="formatDuration(targetDurationSeconds())"></p>
                    </div>
                </div>

            </div>


            {{-- Time budget --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">

                <div class="mb-3">
                    <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Time Budget
                    </h2>
                    <p class="mt-0.5 text-[11px] text-slate-500">
                        Check whether your content fits within available time.
                    </p>
                </div>

                <label for="budgetMinutes" class="mb-1 block text-[10px] font-medium text-slate-500">
                    Available reading time
                </label>

                <div class="flex gap-2">
                    <input
                        id="budgetMinutes"
                        type="number"
                        min="1"
                        max="1440"
                        x-model.number="budgetMinutes"
                        class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >

                    <div class="flex h-9 shrink-0 items-center rounded-md bg-slate-100 px-3 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-500">
                        minutes
                    </div>
                </div>

                <div
                    class="mt-3 rounded-lg p-3"
                    :class="timeBudgetFits()
                        ? 'bg-emerald-50 dark:bg-emerald-950/30'
                        : 'bg-amber-50 dark:bg-amber-950/30'"
                >
                    <p
                        class="text-xs font-semibold"
                        :class="timeBudgetFits()
                            ? 'text-emerald-700 dark:text-emerald-300'
                            : 'text-amber-700 dark:text-amber-300'"
                        x-text="timeBudgetFits() ? 'Fits within your time budget' : 'Content exceeds your time budget'"
                    ></p>

                    <p
                        class="mt-1 text-[11px]"
                        :class="timeBudgetFits()
                            ? 'text-emerald-700 dark:text-emerald-400'
                            : 'text-amber-600 dark:text-amber-400'"
                        x-text="budgetDifferenceText()"
                    ></p>
                </div>

            </div>

        </section>


        {{-- =========================================================
             VISUAL PAUSES + PAGE SETTINGS
        ========================================================== --}}
        <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">

            <div class="mb-3">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                    Calculation Settings
                </h2>
                <p class="mt-0.5 text-[11px] text-slate-500">
                    Fine-tune estimates for your document.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">

                <div>
                    <label for="visualCount" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Visual pauses
                    </label>
                    <input
                        id="visualCount"
                        type="number"
                        min="0"
                        max="500"
                        x-model.number="visualCount"
                        @input="settingsChanged()"
                        class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div>
                    <label for="visualPauseSeconds" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Seconds / visual
                    </label>
                    <input
                        id="visualPauseSeconds"
                        type="number"
                        min="0"
                        max="300"
                        x-model.number="visualPauseSeconds"
                        @input="settingsChanged()"
                        class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div>
                    <label for="pageWords" class="mb-1 block text-[10px] font-medium text-slate-500">
                        Words / page
                    </label>
                    <input
                        id="pageWords"
                        type="number"
                        min="50"
                        max="2000"
                        x-model.number="pageWords"
                        @input="settingsChanged()"
                        class="h-9 w-full rounded-md border border-slate-200 bg-slate-50 px-2.5 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                    >
                </div>

                <div class="flex items-end">
                    <button
                        type="button"
                        class="h-9 w-full rounded-md border border-slate-200 bg-white px-3 text-[11px] font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                        @click="shareSettings()"
                    >
                        <span x-text="copiedAction === 'share' ? '✓ Settings Copied' : 'Share Settings'"></span>
                    </button>
                </div>

            </div>

            <p class="mt-2 text-[10px] text-slate-500">
                Visual pauses are added to silent reading and skimming estimates. Text itself is never uploaded.
            </p>

        </section>


        {{-- =========================================================
             SECTION ANALYSIS
        ========================================================== --}}
        <section
            x-show="inputMode === 'text' && sectionBreakdown().length"
            x-cloak
            class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
        >

            <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                    Section-by-Section Analysis
                </h2>
                <p class="mt-0.5 text-[11px] text-slate-500">
                    Estimated reading time for each content section.
                </p>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                <template x-for="(section, index) in sectionBreakdown()" :key="index">
                    <div class="flex items-center justify-between gap-4 px-4 py-3">
                        <div class="min-w-0">
                            <p
                                class="truncate text-xs font-medium text-slate-700 dark:text-slate-200"
                                x-text="section.title"
                            ></p>

                            <p
                                class="mt-0.5 text-[10px] text-slate-500"
                                x-text="formatNumber(section.words) + ' words'"
                            ></p>
                        </div>

                        <div class="shrink-0 text-right">
                            <p
                                class="text-xs font-semibold text-indigo-600 dark:text-indigo-400"
                                x-text="formatDuration(section.seconds)"
                            ></p>
                        </div>
                    </div>
                </template>
            </div>

        </section>


        {{-- =========================================================
             QUICK RESULT
        ========================================================== --}}
        <section class="rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 dark:border-indigo-900/50 dark:bg-indigo-950/30">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-indigo-700 dark:text-indigo-400">
                        Reading Time Label
                    </p>

                    <p
                        class="mt-1 text-lg font-bold text-indigo-800 dark:text-indigo-200"
                        x-text="badgeText()"
                    ></p>
                </div>

                <button
                    type="button"
                    class="inline-flex h-9 items-center justify-center rounded-md bg-indigo-600 px-4 text-xs font-semibold text-white transition hover:bg-indigo-700"
                    @click="copyBadge()"
                >
                    <span x-text="copiedAction === 'badge' ? '✓ Copied to clipboard' : 'Copy X min read label'"></span>
                </button>

            </div>

        </section>

    </div>
</div>


@script
<script>
window.aabiReadingTimeCalculator = function () {
    return {
        input: '',
        inputMode: 'text',
        wordInput: 0,

        contentType: 'article',
        speedPreset: 'average',

        readingWpm: 200,
        slowWpm: 130,
        fastWpm: 300,
        skimWpm: 450,

        speakingWpm: 150,
        presentationWpm: 130,

        visualCount: 0,
        visualPauseSeconds: 10,
        pageWords: 250,

        targetMode: 'duration',
        targetMinutes: 5,
        targetWords: 1000,

        budgetMinutes: 5,

        copiedAction: '',
        isDragging: false,

        contentPresets: [
            {
                id: 'article',
                label: 'Article',
                reading: 200,
                speaking: 150,
                presentation: 130,
                skim: 450
            },
            {
                id: 'blog',
                label: 'Blog',
                reading: 220,
                speaking: 150,
                presentation: 130,
                skim: 500
            },
            {
                id: 'speech',
                label: 'Speech',
                reading: 180,
                speaking: 130,
                presentation: 110,
                skim: 350
            },
            {
                id: 'presentation',
                label: 'Presentation',
                reading: 180,
                speaking: 120,
                presentation: 100,
                skim: 350
            },
            {
                id: 'podcast',
                label: 'Podcast',
                reading: 170,
                speaking: 130,
                presentation: 115,
                skim: 350
            },
            {
                id: 'video',
                label: 'Video Script',
                reading: 180,
                speaking: 140,
                presentation: 120,
                skim: 400
            }
        ],

        exampleText: `Reading is a skill that improves with practice. The more clearly we understand a text, the easier it becomes to remember important ideas.

A good reading strategy begins by identifying the purpose of the text. Some material needs careful study, while other material can be reviewed quickly.

Reading speed is also affected by sentence length, vocabulary, formatting, and the amount of visual information on a page. This calculator provides practical estimates so you can plan your reading, study, speeches, presentations, articles, and scripts.`,

        init() {
            this.loadSavedState();
            this.loadShareSettings();
            this.calculate();

            window.addEventListener('dragenter', () => {
                this.isDragging = true;
            });

            window.addEventListener('dragend', () => {
                this.isDragging = false;
            });

            window.addEventListener('drop', () => {
                this.isDragging = false;
            });
        },

        setInputMode(mode) {
            if (mode === this.inputMode) {
                return;
            }

            if (mode === 'words' && this.input.trim()) {
                this.wordInput = this.countWords(this.input);
            }

            this.inputMode = mode;
            this.calculate();
            this.saveState();
        },

        effectiveWordCount() {
            if (this.inputMode === 'words') {
                return this.safeNumber(this.wordInput);
            }

            return this.countWords(this.input);
        },

        textAvailable() {
            return this.inputMode === 'text' && this.input.trim().length > 0;
        },

        safeNumber(value, fallback = 0) {
            const number = Number(value);

            if (!Number.isFinite(number) || number < 0) {
                return fallback;
            }

            return number;
        },

        clamp(value, min, max) {
            return Math.min(max, Math.max(min, this.safeNumber(value, min)));
        },

        countWords(text) {
            if (!text || !text.trim()) {
                return 0;
            }

            if (
                typeof Intl !== 'undefined' &&
                Intl.Segmenter
            ) {
                try {
                    const segmenter = new Intl.Segmenter(
                        undefined,
                        { granularity: 'word' }
                    );

                    let count = 0;

                    for (const part of segmenter.segment(text)) {
                        if (part.isWordLike) {
                            count++;
                        }
                    }

                    return count;
                } catch (error) {
                    // Fall through to Unicode-aware fallback.
                }
            }

            const matches = text.match(
                /[\p{L}\p{M}\p{N}]+(?:['’\-][\p{L}\p{M}\p{N}]+)*/gu
            );

            return matches ? matches.length : 0;
        },

        countSentences(text) {
            if (!text || !text.trim()) {
                return 0;
            }

            if (
                typeof Intl !== 'undefined' &&
                Intl.Segmenter
            ) {
                try {
                    const segmenter = new Intl.Segmenter(
                        undefined,
                        { granularity: 'sentence' }
                    );

                    let count = 0;

                    for (const part of segmenter.segment(text)) {
                        if (part.segment.trim()) {
                            count++;
                        }
                    }

                    return count;
                } catch (error) {
                    // Fall through.
                }
            }

            const matches = text.match(
                /[^.!?。！？…]+(?:[.!?。！？…]+|$)/gu
            );

            return matches
                ? matches.filter(item => item.trim().length > 0).length
                : 0;
        },

        countParagraphs(text) {
            if (!text || !text.trim()) {
                return 0;
            }

            return text
                .split(/\n\s*\n+/u)
                .map(item => item.trim())
                .filter(Boolean)
                .length;
        },

        characterCount() {
            if (this.inputMode === 'words') {
                return 0;
            }

            return [...this.input].length;
        },

        characterCountNoSpaces() {
            if (this.inputMode === 'words') {
                return 0;
            }

            return [...this.input.replace(/\s/gu, '')].length;
        },

        sentenceCount() {
            if (this.inputMode === 'words') {
                return 0;
            }

            return this.countSentences(this.input);
        },

        paragraphCount() {
            if (this.inputMode === 'words') {
                return 0;
            }

            return this.countParagraphs(this.input);
        },

        averageWordsPerSentence() {
            const sentences = this.sentenceCount();

            if (!sentences) {
                return 0;
            }

            return this.effectiveWordCount() / sentences;
        },

        visualPauseTotal() {
            return this.clamp(this.visualCount, 0, 500) *
                this.clamp(this.visualPauseSeconds, 0, 300);
        },

        readingSecondsAt(wpm, includeVisualPause = true) {
            const words = this.effectiveWordCount();
            const speed = this.clamp(wpm, 30, 2500);

            if (!words) {
                return 0;
            }

            const base = (words / speed) * 60;

            return Math.ceil(
                base + (
                    includeVisualPause
                        ? this.visualPauseTotal()
                        : 0
                )
            );
        },

        readingSeconds() {
            return this.readingSecondsAt(this.readingWpm);
        },

        speakingSeconds() {
            const words = this.effectiveWordCount();
            const speed = this.clamp(this.speakingWpm, 40, 500);

            if (!words) {
                return 0;
            }

            return Math.ceil((words / speed) * 60);
        },

        presentationSeconds() {
            const words = this.effectiveWordCount();
            const speed = this.clamp(this.presentationWpm, 40, 400);

            if (!words) {
                return 0;
            }

            return Math.ceil((words / speed) * 60);
        },

        targetWordCount() {
            return Math.round(
                this.clamp(this.targetMinutes, 1, 1440) *
                this.clamp(this.readingWpm, 30, 2500)
            );
        },

        targetDurationSeconds() {
            const words = this.clamp(this.targetWords, 0, 100000000);

            if (!words) {
                return 0;
            }

            return Math.ceil(
                (words / this.clamp(this.readingWpm, 30, 2500)) * 60
            );
        },

        budgetSeconds() {
            return this.clamp(this.budgetMinutes, 1, 1440) * 60;
        },

        timeBudgetFits() {
            return this.readingSeconds() <= this.budgetSeconds();
        },

        budgetDifferenceText() {
            const difference = Math.abs(
                this.readingSeconds() - this.budgetSeconds()
            );

            if (difference === 0) {
                return 'Exactly matches your available time.';
            }

            if (this.timeBudgetFits()) {
                return `${this.formatDuration(difference)} remaining.`;
            }

            return `${this.formatDuration(difference)} over your available time.`;
        },

        pageEstimate() {
            const words = this.effectiveWordCount();

            if (!words) {
                return '0';
            }

            const pages = words / this.clamp(this.pageWords, 50, 2000);

            return pages < 1
                ? pages.toFixed(1)
                : Math.ceil(pages).toLocaleString();
        },

        detectEnglishForReadability() {
            if (!this.input || this.inputMode === 'words') {
                return false;
            }

            const latin = (
                this.input.match(/[A-Za-z]/g) || []
            ).length;

            const arabic = (
                this.input.match(/[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/gu) || []
            ).length;

            const letters = latin + arabic;

            if (!letters) {
                return false;
            }

            return latin / letters >= 0.7;
        },

        estimateSyllables(word) {
            const clean = word
                .toLowerCase()
                .replace(/[^a-z]/g, '');

            if (!clean) {
                return 0;
            }

            if (clean.length <= 3) {
                return 1;
            }

            let syllables = (
                clean.match(/[aeiouy]{1,2}/g) || []
            ).length;

            if (
                clean.endsWith('e') &&
                !clean.endsWith('le') &&
                syllables > 1
            ) {
                syllables--;
            }

            if (
                clean.endsWith('es') &&
                syllables > 1
            ) {
                syllables--;
            }

            return Math.max(1, syllables);
        },

        readabilityScore() {
            if (!this.detectEnglishForReadability()) {
                return null;
            }

            const words = this.input.match(
                /[A-Za-z]+(?:['’][A-Za-z]+)?/g
            ) || [];

            const sentences = Math.max(
                1,
                this.sentenceCount()
            );

            if (words.length < 20) {
                return null;
            }

            const syllables = words.reduce(
                (total, word) =>
                    total + this.estimateSyllables(word),
                0
            );

            const score =
                206.835
                - 1.015 * (words.length / sentences)
                - 84.6 * (syllables / words.length);

            return Math.max(
                0,
                Math.min(100, score)
            );
        },

        readabilityLabel() {
            const score = this.readabilityScore();

            if (score === null) {
                return 'N/A';
            }

            if (score >= 90) {
                return 'Very Easy';
            }

            if (score >= 80) {
                return 'Easy';
            }

            if (score >= 70) {
                return 'Fairly Easy';
            }

            if (score >= 60) {
                return 'Standard';
            }

            if (score >= 50) {
                return 'Fairly Difficult';
            }

            if (score >= 30) {
                return 'Difficult';
            }

            return 'Very Difficult';
        },

        readabilityDescription() {
            const score = this.readabilityScore();

            if (score === null) {
                return 'English text required';
            }

            return `Flesch ${score.toFixed(0)}`;
        },

        buildSections() {
            if (!this.textAvailable()) {
                return [];
            }

            const raw = this.input
                .replace(/\r\n?/gu, '\n')
                .trim();

            if (!raw) {
                return [];
            }

            const blocks = raw
                .split(/\n\s*\n+/u)
                .map(block => block.trim())
                .filter(Boolean);

            return blocks.map((block, index) => {
                const lines = block
                    .split('\n')
                    .map(line => line.trim())
                    .filter(Boolean);

                const firstLine = lines[0] || '';

                const markdownHeading = firstLine.match(
                    /^#{1,6}\s+(.+)$/u
                );

                const htmlHeading = firstLine.match(
                    /^<h[1-6][^>]*>(.*?)<\/h[1-6]>$/iu
                );

                let title = `Paragraph ${index + 1}`;

                if (markdownHeading) {
                    title = markdownHeading[1].trim();
                } else if (htmlHeading) {
                    title = htmlHeading[1]
                        .replace(/<[^>]+>/gu, '')
                        .trim();
                } else if (
                    lines.length === 1 &&
                    firstLine.length <= 90 &&
                    !/[.!?。！？…]$/u.test(firstLine)
                ) {
                    title = firstLine;
                }

                const words = this.countWords(
                    block.replace(/^#{1,6}\s+/u, '')
                );

                return {
                    title,
                    words,
                    seconds: this.readingSecondsForWords(words)
                };
            });
        },

        sectionBreakdown() {
            return this.buildSections();
        },

        readingSecondsForWords(words) {
            if (!words) {
                return 0;
            }

            return Math.ceil(
                (words / this.clamp(this.readingWpm, 30, 2500)) * 60
            );
        },

        badgeText() {
            const seconds = this.readingSeconds();

            if (!seconds) {
                return '0 min read';
            }

            const minutes = Math.max(
                1,
                Math.ceil(seconds / 60)
            );

            return `${minutes} min read`;
        },

        formatDuration(totalSeconds) {
            const seconds = Math.max(
                0,
                Math.round(this.safeNumber(totalSeconds))
            );

            if (seconds === 0) {
                return '0 sec';
            }

            if (seconds < 60) {
                return `${seconds} sec`;
            }

            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor(
                (seconds % 3600) / 60
            );
            const remainingSeconds = seconds % 60;

            if (hours > 0) {
                return remainingSeconds > 0
                    ? `${hours} hr ${minutes} min ${remainingSeconds} sec`
                    : `${hours} hr ${minutes} min`;
            }

            return remainingSeconds > 0
                ? `${minutes} min ${remainingSeconds} sec`
                : `${minutes} min`;
        },

        formatNumber(value) {
            return Math.round(
                this.safeNumber(value)
            ).toLocaleString();
        },

        applySpeedPreset(preset) {
            this.speedPreset = preset;

            const speeds = {
                slow: 130,
                average: 200,
                fast: 300
            };

            this.readingWpm = speeds[preset] || 200;

            this.calculate();
            this.saveState();
        },

        applyContentPreset(id) {
            const preset = this.contentPresets.find(
                item => item.id === id
            );

            if (!preset) {
                return;
            }

            this.contentType = id;
            this.readingWpm = preset.reading;
            this.speakingWpm = preset.speaking;
            this.presentationWpm = preset.presentation;
            this.skimWpm = preset.skim;

            this.speedPreset = '';

            this.calculate();
            this.saveState();
        },

        settingsChanged() {
            this.normaliseSettings();
            this.calculate();
            this.saveState();
        },

        normaliseSettings() {
            this.readingWpm = this.clamp(
                this.readingWpm,
                30,
                2500
            );

            this.slowWpm = this.clamp(
                this.slowWpm,
                30,
                1500
            );

            this.fastWpm = this.clamp(
                this.fastWpm,
                30,
                2000
            );

            this.skimWpm = this.clamp(
                this.skimWpm,
                30,
                2500
            );

            this.speakingWpm = this.clamp(
                this.speakingWpm,
                40,
                500
            );

            this.presentationWpm = this.clamp(
                this.presentationWpm,
                40,
                400
            );

            this.visualCount = this.clamp(
                this.visualCount,
                0,
                500
            );

            this.visualPauseSeconds = this.clamp(
                this.visualPauseSeconds,
                0,
                300
            );

            this.pageWords = this.clamp(
                this.pageWords,
                50,
                2000
            );
        },

        calculate() {
            this.normaliseSettings();
            this.saveState();
        },

        async pasteFromClipboard() {
            try {
                if (
                    !navigator.clipboard ||
                    !navigator.clipboard.readText
                ) {
                    this.showToast(
                        'Clipboard access is not available.'
                    );
                    return;
                }

                const text =
                    await navigator.clipboard.readText();

                if (!text) {
                    this.showToast(
                        'Clipboard is empty.'
                    );
                    return;
                }

                this.inputMode = 'text';
                this.input = text;
                this.calculate();

                this.$nextTick(() => {
                    this.$refs.editor?.focus();
                });

                this.showToast(
                    'Text pasted from clipboard.'
                );
            } catch (error) {
                this.showToast(
                    'Clipboard permission was denied.'
                );
            }
        },

        handlePaste() {
            requestAnimationFrame(() => {
                this.calculate();
            });
        },

        handleDrop(event) {
            this.isDragging = false;

            const file =
                event.dataTransfer?.files?.[0];

            if (!file) {
                return;
            }

            if (
                file.type !== 'text/plain' &&
                !file.name.toLowerCase().endsWith('.txt')
            ) {
                this.showToast(
                    'Please drop a TXT file.'
                );
                return;
            }

            this.readTextFile(file);
        },

        handleFile(event) {
            const file =
                event.target?.files?.[0];

            if (!file) {
                return;
            }

            this.readTextFile(file);

            event.target.value = '';
        },

        readTextFile(file) {
            const maxBytes = 5 * 1024 * 1024;

            if (file.size > maxBytes) {
                this.showToast(
                    'TXT file must be 5 MB or smaller.'
                );
                return;
            }

            const reader = new FileReader();

            reader.onload = () => {
                this.inputMode = 'text';
                this.input = String(
                    reader.result || ''
                );

                this.calculate();

                this.showToast(
                    `${file.name} imported successfully.`
                );
            };

            reader.onerror = () => {
                this.showToast(
                    'Unable to read the TXT file.'
                );
            };

            reader.readAsText(file);
        },

        loadExample() {
            this.inputMode = 'text';
            this.input = this.exampleText;
            this.calculate();

            this.$nextTick(() => {
                this.$refs.editor?.focus();
            });

            this.showToast(
                'Example text loaded.'
            );
        },

        clearAll() {
            this.input = '';
            this.wordInput = 0;
            this.inputMode = 'text';

            this.calculate();
            this.saveState();

            this.$nextTick(() => {
                this.$refs.editor?.focus();
            });
        },

        resultSummary() {
            const words = this.effectiveWordCount();

            return [
                'AabiTech Reading Time Calculator',
                '',
                `Reading time: ${this.formatDuration(this.readingSeconds())}`,
                `Reading speed: ${this.readingWpm} WPM`,
                `Words: ${this.formatNumber(words)}`,
                `Characters: ${this.formatNumber(this.characterCount())}`,
                `Characters without spaces: ${this.formatNumber(this.characterCountNoSpaces())}`,
                `Sentences: ${this.formatNumber(this.sentenceCount())}`,
                `Paragraphs: ${this.formatNumber(this.paragraphCount())}`,
                `Average words per sentence: ${this.averageWordsPerSentence().toFixed(1)}`,
                `Estimated pages: ${this.pageEstimate()}`,
                `Speaking time: ${this.formatDuration(this.speakingSeconds())}`,
                `Presentation time: ${this.formatDuration(this.presentationSeconds())}`,
                `Skimming time: ${this.formatDuration(this.readingSecondsAt(this.skimWpm, false))}`,
                `Reading label: ${this.badgeText()}`
            ].join('\n');
        },

        async copyResult() {
            await this.copyToClipboard(
                this.resultSummary()
            );

            this.setCopied('result');
        },

        async copyBadge() {
            await this.copyToClipboard(
                this.badgeText()
            );

            this.setCopied('badge');
        },

        async copyToClipboard(text) {
            try {
                if (
                    navigator.clipboard &&
                    navigator.clipboard.writeText
                ) {
                    await navigator.clipboard.writeText(text);
                    return true;
                }

                const textarea =
                    document.createElement('textarea');

                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';

                document.body.appendChild(textarea);
                textarea.select();

                const success =
                    document.execCommand('copy');

                textarea.remove();

                if (!success) {
                    throw new Error('Copy failed.');
                }

                return true;
            } catch (error) {
                this.showToast(
                    'Unable to copy to clipboard.'
                );

                return false;
            }
        },

        setCopied(action) {
            this.copiedAction = action;

            window.clearTimeout(
                this.copyTimer
            );

            this.copyTimer =
                window.setTimeout(() => {
                    this.copiedAction = '';
                }, 1800);

            this.showToast(
                action === 'share'
                    ? 'Settings URL copied to clipboard.'
                    : action === 'badge'
                        ? 'Reading-time badge copied to clipboard.'
                        : 'Result copied to clipboard.'
            );
        },

        downloadResults() {
            const blob = new Blob(
                [this.resultSummary()],
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
                'aabitech-reading-time-result.txt';

            document.body.appendChild(anchor);
            anchor.click();
            anchor.remove();

            URL.revokeObjectURL(url);

            this.showToast(
                'Results downloaded.'
            );
        },

        buildShareUrl() {
            const url =
                new URL(window.location.href);

            const params = url.searchParams;

            const settings = {
                rt: this.readingWpm,
                slow: this.slowWpm,
                fast: this.fastWpm,
                skim: this.skimWpm,
                speak: this.speakingWpm,
                present: this.presentationWpm,
                type: this.contentType,
                pages: this.pageWords,
                visuals: this.visualCount,
                pause: this.visualPauseSeconds
            };

            Object.entries(settings).forEach(
                ([key, value]) => {
                    params.set(key, String(value));
                }
            );

            /*
             * Deliberately do not put the user's text
             * or word content into the URL.
             */
            params.delete('text');
            params.delete('words');

            url.search = params.toString();

            return url.toString();
        },

        async shareSettings() {
            const url =
                this.buildShareUrl();

            const copied =
                await this.copyToClipboard(url);

            if (copied) {
                this.setCopied('share');
            }
        },

        loadShareSettings() {
            try {
                const params =
                    new URLSearchParams(
                        window.location.search
                    );

                const values = {
                    rt: 'readingWpm',
                    slow: 'slowWpm',
                    fast: 'fastWpm',
                    skim: 'skimWpm',
                    speak: 'speakingWpm',
                    present: 'presentationWpm',
                    pages: 'pageWords',
                    visuals: 'visualCount',
                    pause: 'visualPauseSeconds'
                };

                Object.entries(values).forEach(
                    ([parameter, property]) => {
                        if (!params.has(parameter)) {
                            return;
                        }

                        const value =
                            Number(
                                params.get(parameter)
                            );

                        if (
                            Number.isFinite(value) &&
                            value >= 0
                        ) {
                            this[property] = value;
                        }
                    }
                );

                if (params.has('type')) {
                    const type =
                        params.get('type');

                    if (
                        this.contentPresets.some(
                            item => item.id === type
                        )
                    ) {
                        this.contentType = type;
                    }
                }

                this.normaliseSettings();
            } catch (error) {
                // Ignore malformed share parameters.
            }
        },

        saveState() {
            try {
                const state = {
                    input: this.input,
                    inputMode: this.inputMode,
                    wordInput: this.wordInput,

                    contentType: this.contentType,
                    speedPreset: this.speedPreset,

                    readingWpm: this.readingWpm,
                    slowWpm: this.slowWpm,
                    fastWpm: this.fastWpm,
                    skimWpm: this.skimWpm,

                    speakingWpm: this.speakingWpm,
                    presentationWpm: this.presentationWpm,

                    visualCount: this.visualCount,
                    visualPauseSeconds: this.visualPauseSeconds,
                    pageWords: this.pageWords,

                    targetMode: this.targetMode,
                    targetMinutes: this.targetMinutes,
                    targetWords: this.targetWords,
                    budgetMinutes: this.budgetMinutes
                };

                /*
                 * Avoid filling localStorage with
                 * unexpectedly huge content.
                 */
                if (
                    typeof state.input === 'string' &&
                    state.input.length > 500000
                ) {
                    state.input =
                        state.input.slice(0, 500000);
                }

                localStorage.setItem(
                    'aabitech_reading_time_calculator',
                    JSON.stringify(state)
                );
            } catch (error) {
                // Private browsing/storage restrictions
                // should never break the calculator.
            }
        },

        loadSavedState() {
            try {
                const raw =
                    localStorage.getItem(
                        'aabitech_reading_time_calculator'
                    );

                if (!raw) {
                    return;
                }

                const state =
                    JSON.parse(raw);

                const allowedKeys = [
                    'input',
                    'inputMode',
                    'wordInput',
                    'contentType',
                    'speedPreset',
                    'readingWpm',
                    'slowWpm',
                    'fastWpm',
                    'skimWpm',
                    'speakingWpm',
                    'presentationWpm',
                    'visualCount',
                    'visualPauseSeconds',
                    'pageWords',
                    'targetMode',
                    'targetMinutes',
                    'targetWords',
                    'budgetMinutes'
                ];

                allowedKeys.forEach(key => {
                    if (
                        Object.prototype.hasOwnProperty.call(
                            state,
                            key
                        )
                    ) {
                        this[key] = state[key];
                    }
                });

                if (
                    !['text', 'words'].includes(
                        this.inputMode
                    )
                ) {
                    this.inputMode = 'text';
                }

                this.normaliseSettings();
            } catch (error) {
                // Ignore corrupted local state.
            }
        },

        showToast(message) {
            try {
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
            } catch (error) {
                // Toast system is optional.
            }
        }
    };
};
</script>
@endscript