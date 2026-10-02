<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiCharacterCounter()"
    x-init="init()"
    x-cloak
    class="w-full"
>
    {{-- {{-- ============================================================
        HEADER
    ============================================================= --}}
    {{-- <div class="mb-8">
        <div class="mb-3 flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                Aa Text Tool
            </span>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Runs in your browser
            </span>
        </div>

        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
            Character Counter
        </h1>

        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-600 dark:text-slate-500">
            Count characters, words, sentences, paragraphs and lines instantly.
            Analyze Unicode characters, emoji, text length, reading time and limits
            with this free online character counter.
        </p>
    </div>  --}}

    {{-- ============================================================
        PRIVACY NOTICE
    ============================================================= --}}
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/60 dark:bg-emerald-950/20">
        <div class="flex gap-3">
            <div class="mt-0.5 text-lg">🔒</div>

            <div class="min-w-0">
                <p class="font-semibold text-emerald-900 dark:text-emerald-200">
                    Your text stays private
                </p>

                <p class="mt-1 text-sm leading-6 text-emerald-800 dark:text-emerald-300">
                    Text analysis happens locally in your browser. Your writing is not
                    uploaded to AabiTech or sent to an external server.
                </p>
            </div>
        </div>
    </div>

    {{-- ============================================================
        MAIN WORKSPACE
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        {{-- TOOLBAR --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Your Text
                </h2>

                <p class="mt-0.5 text-xs text-slate-500">
                    Type, paste, or drop a text file
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
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
                    @click="$refs.fileInput.click()"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
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
                    @click="copyText()"
                    :disabled="!text"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    <span x-text="copyButtonLabel"></span>
                </button>

                <button
                    type="button"
                    @click="downloadText()"
                    :disabled="!text"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Download
                </button>

                <button
                    type="button"
                    @click="clearText()"
                    :disabled="!text"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Clear
                </button>
            </div>
        </div>

        {{-- MAIN TWO-COLUMN WORKSPACE --}}
        <div class="grid lg:grid-cols-2">

            {{-- ====================================================
                LEFT: TEXT EDITOR
            ===================================================== --}}
            <div
                class="min-w-0 border-b border-slate-200 p-5 dark:border-slate-800 lg:border-b-0 lg:border-r"
                @dragover.prevent="dragActive = true"
                @dragleave.prevent="dragActive = false"
                @drop.prevent="handleDrop($event)"
            >
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Input
                        </p>
                    </div>

                    <span
                        x-show="dragActive"
                        x-transition
                        class="rounded-md bg-indigo-50 px-2 py-1 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300"
                    >
                        Drop TXT file
                    </span>
                </div>

                <textarea
                    x-ref="editor"
                    x-model="text"
                    @input="analyze()"
                    @select="updateSelectionStats()"
                    @keyup="updateSelectionStats()"
                    spellcheck="true"
                    class="h-[560px] min-h-[420px] w-full resize-y rounded-xl border border-slate-200 bg-slate-50 p-5 text-base leading-7 text-slate-900 outline-none transition placeholder:text-slate-500 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-600 dark:focus:ring-slate-800"
                    placeholder="Type or paste your text here..."
                ></textarea>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-xs text-slate-500">
                        <span x-text="formatNumber(characters)"></span>
                        characters
                        <span class="mx-1">•</span>
                        <span x-text="formatNumber(words)"></span>
                        words
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            x-show="selectionCharacters > 0"
                            class="text-xs font-medium text-indigo-600 dark:text-indigo-400"
                            x-text="`Selection: ${formatNumber(selectionCharacters)} chars`"
                        ></span>

                        <span
                            class="text-xs text-slate-500"
                            x-text="savedStatus"
                        ></span>
                    </div>
                </div>

                {{-- Drop hint --}}
                <div class="mt-3 text-center text-[11px] text-slate-500">
                    Drag and drop a <strong>.txt</strong> file anywhere into the editor
                </div>
            </div>

            {{-- ====================================================
                RIGHT: BASIC LIVE STATISTICS
            ===================================================== --}}
            <div class="min-w-0 bg-slate-50/70 p-5 dark:bg-slate-950/30">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Live Statistics
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Updates as you type
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="copyReport()"
                        :disabled="!text"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        <span x-text="reportButtonLabel"></span>
                    </button>
                </div>

                {{-- Main count --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-medium text-slate-500">
                        Characters
                    </p>

                    <div class="mt-1 flex items-end justify-between gap-4">
                        <p
                            class="text-4xl font-bold tracking-tight text-slate-950 dark:text-white"
                            x-text="formatNumber(characters)"
                        ></p>

                        <span
                            x-show="graphemes !== characters"
                            class="pb-1 text-xs font-medium text-indigo-600 dark:text-indigo-400"
                            x-text="`${formatNumber(graphemes)} visible`"
                        ></span>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                        <span>
                            No spaces:
                            <strong
                                class="text-slate-700 dark:text-slate-300"
                                x-text="formatNumber(charactersNoSpaces)"
                            ></strong>
                        </span>

                        <span>
                            UTF-8:
                            <strong
                                class="text-slate-700 dark:text-slate-300"
                                x-text="formatBytes(utf8Bytes)"
                            ></strong>
                        </span>
                    </div>
                </div>

                {{-- Basic metrics --}}
                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <template x-for="stat in basicStats" :key="stat.label">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
                            <p
                                class="text-xs font-medium text-slate-500"
                                x-text="stat.label"
                            ></p>

                            <p
                                class="mt-1.5 text-xl font-bold tracking-tight text-slate-900 dark:text-white"
                                x-text="formatNumber(stat.value)"
                            ></p>
                        </div>
                    </template>
                </div>

                {{-- Unicode technical metrics --}}
                <div class="mt-3 rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                    <button
                        type="button"
                        @click="showUnicode = !showUnicode"
                        class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left"
                    >
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Unicode details
                            </p>

                            <p class="mt-0.5 text-[11px] text-slate-500">
                                Useful for emoji and multilingual text
                            </p>
                        </div>

                        <span
                            class="text-xs text-slate-500"
                            x-text="showUnicode ? 'Hide' : 'Show'"
                        ></span>
                    </button>

                    <div
                        x-show="showUnicode"
                        x-collapse
                        class="border-t border-slate-200 px-4 py-3 dark:border-slate-800"
                    >
                        <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                            <div>
                                <p class="text-slate-500">Graphemes</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatNumber(graphemes)"
                                ></p>
                            </div>

                            <div>
                                <p class="text-slate-500">Code points</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatNumber(codePoints)"
                                ></p>
                            </div>

                            <div>
                                <p class="text-slate-500">UTF-16 units</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatNumber(utf16Units)"
                                ></p>
                            </div>

                            <div>
                                <p class="text-slate-500">UTF-8 bytes</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatBytes(utf8Bytes)"
                                ></p>
                            </div>

                            <div>
                                <p class="text-slate-500">Emoji / symbols</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatNumber(emojiCount)"
                                ></p>
                            </div>

                            <div>
                                <p class="text-slate-500">Whitespace</p>
                                <p
                                    class="mt-0.5 font-semibold text-slate-800 dark:text-slate-200"
                                    x-text="formatNumber(spaces)"
                                ></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Limit summary --}}
                <div
                    x-show="characterLimit > 0"
                    x-cloak
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Character limit
                            </p>

                            <p
                                class="mt-0.5 text-xs text-slate-500"
                                x-text="characterLimitExceeded
                                    ? `${formatNumber(characters - characterLimit)} over`
                                    : `${formatNumber(characterLimit - characters)} left`"
                            ></p>
                        </div>

                        <span
                            class="text-xs font-bold"
                            :class="limitStatusClass"
                            x-text="limitStatusLabel"
                        ></span>
                    </div>

                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div
                            class="h-full rounded-full transition-all"
                            :class="characterLimitExceeded ? 'bg-red-500' : characterProgress >= 90 ? 'bg-amber-500' : 'bg-slate-900 dark:bg-white'"
                            :style="`width: ${characterProgress}%`"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        CHARACTER LIMITS
    ============================================================= --}}
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Character & Word Limits
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Check your text against custom or common limits
                    </p>
                </div>

                <div class="flex flex-wrap gap-1.5">
                    <button
                        type="button"
                        @click="setCharacterPreset('X / Short Post', 280)"
                        data-active-group="limit-preset"
                        :class="characterLimit === 280 ? 'is-active' : ''"
                        class="compact-tab border border-slate-200 dark:border-slate-700"
                    >
                        280
                    </button>

                    <button
                        type="button"
                        @click="setCharacterPreset('SEO Title', 60)"
                        data-active-group="limit-preset"
                        :class="characterLimit === 60 ? 'is-active' : ''"
                        class="compact-tab border border-slate-200 dark:border-slate-700"
                    >
                        SEO Title
                    </button>

                    <button
                        type="button"
                        @click="setCharacterPreset('Meta Description', 160)"
                        data-active-group="limit-preset"
                        :class="characterLimit === 160 ? 'is-active' : ''"
                        class="compact-tab border border-slate-200 dark:border-slate-700"
                    >
                        Meta
                    </button>

                    <button
                        type="button"
                        @click="setCharacterPreset('SMS', 160)"
                        data-active-group="limit-preset"
                        :class="characterLimit === 160 ? 'is-active' : ''"
                        class="compact-tab border border-slate-200 dark:border-slate-700"
                    >
                        SMS
                    </button>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            {{-- CHARACTER LIMIT --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Character limit
                </label>

                <div class="flex gap-2">
                    <input
                        type="number"
                        min="0"
                        max="10000000"
                        x-model.number="characterLimit"
                        @input="saveSettings()"
                        placeholder="e.g. 280"
                        class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >

                    <button
                        type="button"
                        @click="characterLimit = 280; saveSettings()"
                        class="rounded-xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        280
                    </button>
                </div>

                <div
                    x-show="characterLimit > 0"
                    class="mt-3"
                >
                    <div class="mb-1 flex justify-between text-xs">
                        <span class="text-slate-500">
                            <span x-text="formatNumber(characters)"></span>
                            /
                            <span x-text="formatNumber(characterLimit)"></span>
                        </span>

                        <span
                            class="font-semibold"
                            :class="characterLimitExceeded ? 'text-red-600' : characterProgress >= 90 ? 'text-amber-600' : 'text-emerald-600'"
                            x-text="characterLimitExceeded
                                ? `${formatNumber(characters - characterLimit)} over`
                                : `${formatNumber(characterLimit - characters)} left`"
                        ></span>
                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div
                            class="h-full rounded-full transition-all"
                            :class="characterLimitExceeded ? 'bg-red-500' : characterProgress >= 90 ? 'bg-amber-500' : 'bg-slate-900 dark:bg-white'"
                            :style="`width: ${characterProgress}%`"
                        ></div>
                    </div>
                </div>
            </div>

            {{-- WORD LIMIT --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Word limit
                </label>

                <div class="flex gap-2">
                    <input
                        type="number"
                        min="0"
                        max="10000000"
                        x-model.number="wordLimit"
                        @input="saveSettings()"
                        placeholder="e.g. 500"
                        class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >

                    <button
                        type="button"
                        @click="wordLimit = 500; saveSettings()"
                        class="rounded-xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        500
                    </button>
                </div>

                <div
                    x-show="wordLimit > 0"
                    class="mt-3"
                >
                    <div class="mb-1 flex justify-between text-xs">
                        <span class="text-slate-500">
                            <span x-text="formatNumber(words)"></span>
                            /
                            <span x-text="formatNumber(wordLimit)"></span>
                        </span>

                        <span
                            class="font-semibold"
                            :class="wordLimitExceeded ? 'text-red-600' : 'text-emerald-600'"
                            x-text="wordLimitExceeded
                                ? `${formatNumber(words - wordLimit)} over`
                                : `${formatNumber(wordLimit - words)} left`"
                        ></span>
                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div
                            class="h-full rounded-full transition-all"
                            :class="wordLimitExceeded ? 'bg-red-500' : 'bg-slate-900 dark:bg-white'"
                            :style="`width: ${wordProgress}%`"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        DETAILED STATISTICS
    ============================================================= --}}
    <section class="mt-6 grid gap-6 lg:grid-cols-2">

        {{-- TEXT STATISTICS --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Detailed Statistics
                </h2>

                <p class="mt-0.5 text-xs text-slate-500">
                    Detailed breakdown of your text
                </p>
            </div>

            <div class="grid grid-cols-2 divide-x divide-y divide-slate-200 dark:divide-slate-800">
                <template x-for="stat in detailedStats" :key="stat.label">
                    <div class="p-5">
                        <p
                            class="text-xs text-slate-500"
                            x-text="stat.label"
                        ></p>

                        <p
                            class="mt-1 text-lg font-bold text-slate-900 dark:text-white"
                            x-text="formatNumber(stat.value)"
                        ></p>
                    </div>
                </template>
            </div>
        </div>

        {{-- READING / WRITING METRICS --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h2 class="font-semibold text-slate-900 dark:text-white">
                    Reading & Writing Metrics
                </h2>

                <p class="mt-0.5 text-xs text-slate-500">
                    Estimated timing and text characteristics
                </p>
            </div>

            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">
                            Reading time
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Based on 200 words per minute
                        </p>
                    </div>

                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white"
                        x-text="readingTime"
                    ></span>
                </div>

                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">
                            Speaking time
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Based on 130 words per minute
                        </p>
                    </div>

                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white"
                        x-text="speakingTime"
                    ></span>
                </div>

                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">
                            Average word length
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Characters without whitespace per word
                        </p>
                    </div>

                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white"
                        x-text="averageWordLength"
                    ></span>
                </div>

                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">
                            Average sentence length
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Words per sentence
                        </p>
                    </div>

                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white"
                        x-text="averageSentenceLength"
                    ></span>
                </div>

                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">
                            Unique words
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Case-insensitive word count
                        </p>
                    </div>

                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white"
                        x-text="formatNumber(uniqueWords)"
                    ></span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        ADVANCED ANALYSIS
    ============================================================= --}}
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">
                        Advanced Analysis
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Frequency, character composition and optional analysis
                    </p>
                </div>

                <button
                    type="button"
                    @click="showAdvanced = !showAdvanced"
                    class="compact-tab border border-slate-200 dark:border-slate-700"
                    :class="showAdvanced ? 'is-active' : ''"
                    data-active-group="advanced"
                >
                    <span x-text="showAdvanced ? 'Hide' : 'Show'"></span>
                </button>
            </div>
        </div>

        <div
            x-show="showAdvanced"
            x-collapse
            class="p-5"
        >
            <div class="grid gap-6 lg:grid-cols-2">

                {{-- CHARACTER BREAKDOWN --}}
                <div>
                    <div class="mb-3">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Character Breakdown
                        </h3>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Composition of the current text
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <template x-for="item in compositionStats" :key="item.label">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950">
                                <p
                                    class="text-[11px] text-slate-500"
                                    x-text="item.label"
                                ></p>

                                <p
                                    class="mt-1 font-semibold text-slate-900 dark:text-white"
                                    x-text="formatNumber(item.value)"
                                ></p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- FREQUENCY --}}
                <div>
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                Character Frequency
                            </h3>

                            <p class="mt-0.5 text-xs text-slate-500">
                                Most frequently used visible characters
                            </p>
                        </div>
                    </div>

                    <div
                        x-show="characterFrequency.length"
                        class="space-y-2"
                    >
                        <template x-for="item in characterFrequency" :key="item.key">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                                    x-text="item.display"
                                ></span>

                                <div class="min-w-0 flex-1">
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span
                                            class="truncate text-slate-500"
                                            x-text="item.name"
                                        ></span>

                                        <span
                                            class="font-semibold text-slate-700 dark:text-slate-300"
                                            x-text="item.count"
                                        ></span>
                                    </div>

                                    <div class="h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                        <div
                                            class="h-full rounded-full bg-slate-700 transition-all dark:bg-slate-300"
                                            :style="`width: ${item.percent}%`"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <p
                            x-show="!characterFrequency.length"
                            class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-xs text-slate-500 dark:border-slate-800"
                        >
                            Start typing to see frequency analysis.
                        </p>
                    </div>
                </div>
            </div>

            {{-- CLEANUP --}}
            <div class="mt-6 border-t border-slate-200 pt-5 dark:border-slate-800">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Quick Text Cleanup
                        </h3>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Creates a cleaned version directly in the editor.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            @click="trimText()"
                            :disabled="!text"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            Trim
                        </button>

                        <button
                            type="button"
                            @click="removeExtraSpaces()"
                            :disabled="!text"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            Extra Spaces
                        </button>

                        <button
                            type="button"
                            @click="removeExtraLineBreaks()"
                            :disabled="!text"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            Extra Lines
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

 

    {{-- ============================================================
        ALPINE LOGIC
    ============================================================= --}}
    @script
    <script>
        window.aabiCharacterCounter = function () {
            return {
                text: '',

                characters: 0,
                charactersNoSpaces: 0,
                graphemes: 0,
                codePoints: 0,
                utf16Units: 0,
                utf8Bytes: 0,

                words: 0,
                uniqueWords: 0,
                sentences: 0,
                paragraphs: 0,
                lines: 0,

                letters: 0,
                numbers: 0,
                spaces: 0,
                punctuation: 0,
                symbols: 0,
                emojiCount: 0,

                readingTime: '0 sec',
                speakingTime: '0 sec',
                averageWordLength: '0',
                averageSentenceLength: '0',

                characterLimit: 0,
                wordLimit: 0,

                selectionCharacters: 0,
                selectionWords: 0,

                showUnicode: false,
                showAdvanced: false,
                dragActive: false,

                savedStatus: '',
                copyButtonLabel: 'Copy',
                reportButtonLabel: 'Copy Report',

                saveTimer: null,
                initialized: false,

                characterFrequency: [],

                init() {
                    this.loadSettings();
                    this.loadDraft();
                    this.analyze();

                    this.$nextTick(() => {
                        this.initialized = true;
                        this.updateSelectionStats();
                    });

                    window.addEventListener('beforeunload', () => {
                        this.saveDraft();
                    });

                    window.addEventListener('keydown', (event) => {
                        const modifier = event.ctrlKey || event.metaKey;

                        if (modifier && event.key.toLowerCase() === 'l') {
                            event.preventDefault();
                            this.$refs.editor?.focus();
                            this.$refs.editor?.select();
                        }

                        if (modifier && event.key.toLowerCase() === 'shift' && event.key.toLowerCase() === 'c') {
                            event.preventDefault();
                        }
                    });
                },

                get basicStats() {
                    return [
                        { label: 'Words', value: this.words },
                        { label: 'Sentences', value: this.sentences },
                        { label: 'Paragraphs', value: this.paragraphs },
                        { label: 'Lines', value: this.lines },
                        { label: 'Spaces', value: this.spaces },
                        { label: 'Letters', value: this.letters },
                        { label: 'Numbers', value: this.numbers },
                        { label: 'Punctuation', value: this.punctuation },
                        { label: 'Symbols', value: this.symbols },
                    ];
                },

                get detailedStats() {
                    return [
                        { label: 'Characters', value: this.characters },
                        { label: 'Without spaces', value: this.charactersNoSpaces },
                        { label: 'Graphemes', value: this.graphemes },
                        { label: 'Code points', value: this.codePoints },
                        { label: 'UTF-16 units', value: this.utf16Units },
                        { label: 'UTF-8 bytes', value: this.utf8Bytes },
                        { label: 'Words', value: this.words },
                        { label: 'Unique words', value: this.uniqueWords },
                        { label: 'Sentences', value: this.sentences },
                        { label: 'Paragraphs', value: this.paragraphs },
                        { label: 'Lines', value: this.lines },
                        { label: 'Emoji / symbols', value: this.emojiCount },
                    ];
                },

                get compositionStats() {
                    return [
                        { label: 'Letters', value: this.letters },
                        { label: 'Numbers', value: this.numbers },
                        { label: 'Spaces', value: this.spaces },
                        { label: 'Punctuation', value: this.punctuation },
                        { label: 'Symbols', value: this.symbols },
                        { label: 'Emoji', value: this.emojiCount },
                    ];
                },

                get characterLimitExceeded() {
                    return (
                        Number(this.characterLimit) > 0 &&
                        this.characters > Number(this.characterLimit)
                    );
                },

                get wordLimitExceeded() {
                    return (
                        Number(this.wordLimit) > 0 &&
                        this.words > Number(this.wordLimit)
                    );
                },

                get characterProgress() {
                    if (!Number(this.characterLimit)) {
                        return 0;
                    }

                    return Math.min(
                        100,
                        Math.round(
                            (this.characters / Number(this.characterLimit)) * 100
                        )
                    );
                },

                get wordProgress() {
                    if (!Number(this.wordLimit)) {
                        return 0;
                    }

                    return Math.min(
                        100,
                        Math.round(
                            (this.words / Number(this.wordLimit)) * 100
                        )
                    );
                },

                get limitStatusLabel() {
                    if (!this.characterLimit) {
                        return '';
                    }

                    if (this.characterLimitExceeded) {
                        return 'Over limit';
                    }

                    if (this.characterProgress >= 90) {
                        return 'Near limit';
                    }

                    return 'Within limit';
                },

                get limitStatusClass() {
                    if (this.characterLimitExceeded) {
                        return 'text-red-600';
                    }

                    if (this.characterProgress >= 90) {
                        return 'text-amber-600';
                    }

                    return 'text-emerald-600';
                },

                analyze() {
                    const value = this.text || '';

                    this.characters = value.length;
                    this.utf16Units = value.length;

                    this.codePoints = this.countCodePoints(value);
                    this.graphemes = this.countGraphemes(value);
                    this.utf8Bytes = this.getUtf8Bytes(value);

                    this.charactersNoSpaces = value
                        .replace(/\s/gu, '')
                        .length;

                    this.spaces = (
                        value.match(/\s/gu) || []
                    ).length;

                    this.letters = (
                        value.match(/\p{L}/gu) || []
                    ).length;

                    this.numbers = (
                        value.match(/\p{N}/gu) || []
                    ).length;

                    this.punctuation = (
                        value.match(/\p{P}/gu) || []
                    ).length;

                    this.symbols = (
                        value.match(/\p{S}/gu) || []
                    ).length;

                    this.emojiCount = this.countEmoji(value);

                    this.words = this.countWords(value);
                    this.uniqueWords = this.countUniqueWords(value);
                    this.sentences = this.countSentences(value);
                    this.paragraphs = this.countParagraphs(value);
                    this.lines = this.countLines(value);

                    this.calculateReadingTime();
                    this.calculateAverages();
                    this.buildCharacterFrequency();

                    this.saveDraftDebounced();

                    this.$nextTick(() => {
                        this.updateSelectionStats();
                    });
                },

                countCodePoints(value) {
                    if (!value) {
                        return 0;
                    }

                    return Array.from(value).length;
                },

                countGraphemes(value) {
                    if (!value) {
                        return 0;
                    }

                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        try {
                            const segmenter = new Intl.Segmenter(
                                undefined,
                                {
                                    granularity: 'grapheme',
                                }
                            );

                            return Array.from(
                                segmenter.segment(value)
                            ).length;
                        } catch {
                            // Fall through.
                        }
                    }

                    return Array.from(value).length;
                },

                getUtf8Bytes(value) {
                    if (!value) {
                        return 0;
                    }

                    if (typeof TextEncoder !== 'undefined') {
                        return new TextEncoder().encode(value).length;
                    }

                    try {
                        return unescape(
                            encodeURIComponent(value)
                        ).length;
                    } catch {
                        return value.length;
                    }
                },

                countEmoji(value) {
                    if (!value) {
                        return 0;
                    }

                    try {
                        const matches = value.match(
                            /\p{Extended_Pictographic}/gu
                        );

                        return matches ? matches.length : 0;
                    } catch {
                        return 0;
                    }
                },

                countWords(value) {
                    if (!value.trim()) {
                        return 0;
                    }

                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        try {
                            const segmenter = new Intl.Segmenter(
                                undefined,
                                {
                                    granularity: 'word',
                                }
                            );

                            let count = 0;

                            for (const item of segmenter.segment(value)) {
                                if (item.isWordLike) {
                                    count++;
                                }
                            }

                            return count;
                        } catch {
                            // Fall through.
                        }
                    }

                    return value
                        .trim()
                        .split(/\s+/u)
                        .filter(Boolean)
                        .length;
                },

                getWordTokens(value) {
                    if (!value.trim()) {
                        return [];
                    }

                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        try {
                            const segmenter = new Intl.Segmenter(
                                undefined,
                                {
                                    granularity: 'word',
                                }
                            );

                            return Array.from(
                                segmenter.segment(value)
                            )
                                .filter(item => item.isWordLike)
                                .map(item => item.segment);
                        } catch {
                            // Fall through.
                        }
                    }

                    return value
                        .trim()
                        .split(/\s+/u)
                        .filter(Boolean);
                },

                countUniqueWords(value) {
                    const tokens = this.getWordTokens(value);

                    if (!tokens.length) {
                        return 0;
                    }

                    const set = new Set(
                        tokens.map(word =>
                            word.toLocaleLowerCase()
                        )
                    );

                    return set.size;
                },

                countSentences(value) {
                    if (!value.trim()) {
                        return 0;
                    }

                    const matches = value.match(
                        /[^.!?…。！？]+[.!?…。！？]+(?=\s|$)|[^.!?…。！？]+$/gu
                    );

                    return matches
                        ? matches.filter(item => item.trim()).length
                        : 0;
                },

                countParagraphs(value) {
                    if (!value.trim()) {
                        return 0;
                    }

                    return value
                        .replace(/\r\n/gu, '\n')
                        .replace(/\r/gu, '\n')
                        .split(/\n\s*\n+/u)
                        .filter(paragraph => paragraph.trim())
                        .length;
                },

                countLines(value) {
                    if (!value) {
                        return 0;
                    }

                    return value.split(/\r\n|\r|\n/gu).length;
                },

                calculateReadingTime() {
                    if (!this.words) {
                        this.readingTime = '0 sec';
                        this.speakingTime = '0 sec';
                        return;
                    }

                    const readingSeconds = Math.ceil(
                        (this.words / 200) * 60
                    );

                    const speakingSeconds = Math.ceil(
                        (this.words / 130) * 60
                    );

                    this.readingTime =
                        this.formatDuration(readingSeconds);

                    this.speakingTime =
                        this.formatDuration(speakingSeconds);
                },

                calculateAverages() {
                    if (!this.words) {
                        this.averageWordLength = '0';
                    } else {
                        this.averageWordLength = (
                            this.charactersNoSpaces / this.words
                        ).toFixed(1);
                    }

                    if (!this.sentences) {
                        this.averageSentenceLength = '0';
                    } else {
                        this.averageSentenceLength = (
                            this.words / this.sentences
                        ).toFixed(1);
                    }
                },

                formatDuration(seconds) {
                    if (seconds < 60) {
                        return `${seconds} sec`;
                    }

                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;

                    if (!remainingSeconds) {
                        return `${minutes} min`;
                    }

                    return `${minutes}m ${remainingSeconds}s`;
                },

                buildCharacterFrequency() {
                    const value = this.text || '';

                    if (!value) {
                        this.characterFrequency = [];
                        return;
                    }

                    const counts = new Map();

                    let segments;

                    if (
                        typeof Intl !== 'undefined' &&
                        typeof Intl.Segmenter === 'function'
                    ) {
                        try {
                            const segmenter = new Intl.Segmenter(
                                undefined,
                                {
                                    granularity: 'grapheme',
                                }
                            );

                            segments = Array.from(
                                segmenter.segment(value),
                                item => item.segment
                            );
                        } catch {
                            segments = Array.from(value);
                        }
                    } else {
                        segments = Array.from(value);
                    }

                    for (const segment of segments) {
                        if (/^\s$/u.test(segment)) {
                            continue;
                        }

                        const key = segment.toLocaleLowerCase();

                        counts.set(
                            key,
                            (counts.get(key) || 0) + 1
                        );
                    }

                    const sorted = Array.from(counts.entries())
                        .sort((a, b) => b[1] - a[1])
                        .slice(0, 8);

                    const max = sorted.length
                        ? sorted[0][1]
                        : 1;

                    this.characterFrequency = sorted.map(
                        ([key, count]) => ({
                            key,
                            display: key.length > 4
                                ? key.slice(0, 4)
                                : key,
                            name: this.describeCharacter(key),
                            count,
                            percent: Math.max(
                                4,
                                Math.round((count / max) * 100)
                            ),
                        })
                    );
                },

                describeCharacter(character) {
                    if (character === ' ') {
                        return 'space';
                    }

                    if (character === '\t') {
                        return 'tab';
                    }

                    if (character.length === 1) {
                        return `U+${character.codePointAt(0)
                            .toString(16)
                            .toUpperCase()
                            .padStart(4, '0')}`;
                    }

                    return 'Unicode sequence';
                },

                updateSelectionStats() {
                    const editor = this.$refs.editor;

                    if (!editor) {
                        return;
                    }

                    const start = editor.selectionStart;
                    const end = editor.selectionEnd;

                    if (start === end) {
                        this.selectionCharacters = 0;
                        this.selectionWords = 0;
                        return;
                    }

                    const selected = this.text.slice(start, end);

                    this.selectionCharacters =
                        this.countGraphemes(selected);

                    this.selectionWords =
                        this.countWords(selected);
                },

                async pasteText() {
                    try {
                        if (
                            !navigator.clipboard ||
                            !navigator.clipboard.readText
                        ) {
                            throw new Error('Clipboard unavailable');
                        }

                        const clipboardText =
                            await navigator.clipboard.readText();

                        if (!clipboardText) {
                            this.showToast('Clipboard is empty.');
                            return;
                        }

                        this.text = clipboardText;
                        this.analyze();

                        this.showToast('Text pasted.');
                    } catch {
                        this.showToast(
                            'Clipboard access was blocked. Paste manually into the editor.'
                        );
                    }
                },

                async copyText() {
                    if (!this.text) {
                        return;
                    }

                    const success =
                        await this.writeClipboard(this.text);

                    if (success) {
                        this.copyButtonLabel = '✓ Copied';

                        setTimeout(() => {
                            this.copyButtonLabel = 'Copy';
                        }, 1600);

                        this.showToast(
                            'Text copied to clipboard.'
                        );
                    } else {
                        this.showToast(
                            'Unable to copy automatically.'
                        );
                    }
                },

                async copyReport() {
                    if (!this.text) {
                        return;
                    }

                    const report = this.getReport();

                    const success =
                        await this.writeClipboard(report);

                    if (success) {
                        this.reportButtonLabel = '✓ Copied';

                        setTimeout(() => {
                            this.reportButtonLabel = 'Copy Report';
                        }, 1600);

                        this.showToast(
                            'Statistics report copied.'
                        );
                    } else {
                        this.showToast(
                            'Unable to copy the report.'
                        );
                    }
                },

                async writeClipboard(value) {
                    try {
                        if (
                            navigator.clipboard &&
                            navigator.clipboard.writeText
                        ) {
                            await navigator.clipboard.writeText(value);
                            return true;
                        }
                    } catch {
                        // Fall through.
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
                    textarea.setAttribute('readonly', '');

                    document.body.appendChild(textarea);

                    textarea.focus();
                    textarea.select();

                    let success = false;

                    try {
                        success =
                            document.execCommand('copy');
                    } catch {
                        success = false;
                    }

                    textarea.remove();

                    return success;
                },

                getReport() {
                    return [
                        'AabiTech Character Counter',
                        '',
                        `Characters: ${this.characters}`,
                        `Characters without spaces: ${this.charactersNoSpaces}`,
                        `Visible characters (graphemes): ${this.graphemes}`,
                        `Unicode code points: ${this.codePoints}`,
                        `UTF-16 units: ${this.utf16Units}`,
                        `UTF-8 bytes: ${this.utf8Bytes}`,
                        `Words: ${this.words}`,
                        `Unique words: ${this.uniqueWords}`,
                        `Sentences: ${this.sentences}`,
                        `Paragraphs: ${this.paragraphs}`,
                        `Lines: ${this.lines}`,
                        `Spaces: ${this.spaces}`,
                        `Letters: ${this.letters}`,
                        `Numbers: ${this.numbers}`,
                        `Punctuation: ${this.punctuation}`,
                        `Symbols: ${this.symbols}`,
                        `Emoji / pictographic characters: ${this.emojiCount}`,
                        `Reading time: ${this.readingTime}`,
                        `Speaking time: ${this.speakingTime}`,
                        `Average word length: ${this.averageWordLength}`,
                        `Average sentence length: ${this.averageSentenceLength}`,
                    ].join('\n');
                },

                downloadText() {
                    if (!this.text) {
                        return;
                    }

                    const blob = new Blob(
                        [this.text],
                        {
                            type: 'text/plain;charset=utf-8',
                        }
                    );

                    const url =
                        URL.createObjectURL(blob);

                    const anchor =
                        document.createElement('a');

                    anchor.href = url;
                    anchor.download =
                        'aabitech-text.txt';

                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();

                    URL.revokeObjectURL(url);

                    this.showToast('Text downloaded.');
                },

                async handleFile(event) {
                    const file =
                        event.target.files?.[0];

                    if (!file) {
                        return;
                    }

                    await this.readTextFile(file);

                    event.target.value = '';
                },

                async handleDrop(event) {
                    this.dragActive = false;

                    const file =
                        event.dataTransfer?.files?.[0];

                    if (!file) {
                        return;
                    }

                    if (
                        file.type &&
                        file.type !== 'text/plain' &&
                        !file.name.toLowerCase().endsWith('.txt')
                    ) {
                        this.showToast(
                            'Please drop a TXT text file.'
                        );
                        return;
                    }

                    await this.readTextFile(file);
                },

                async readTextFile(file) {
                    const maxSize = 10 * 1024 * 1024;

                    if (file.size > maxSize) {
                        this.showToast(
                            'File is too large. Maximum size is 10 MB.'
                        );
                        return;
                    }

                    try {
                        this.text = await file.text();
                        this.analyze();

                        this.showToast(
                            'Text file imported.'
                        );
                    } catch {
                        this.showToast(
                            'Unable to read the text file.'
                        );
                    }
                },

                loadExample() {
                    this.text =
`AabiTech provides simple and useful online tools for developers, students, writers and everyday users.

This character counter analyzes your text instantly. It counts characters, words, sentences, paragraphs and lines while also measuring Unicode characters, emoji and UTF-8 bytes.

آپ اردو، العربية، 中文、日本語、한국어 and emoji 😀 بھی استعمال کر سکتے ہیں.

Your text is processed directly in the browser, so you can analyze content without sending it to a server.`;

                    this.analyze();

                    this.showToast(
                        'Example text loaded.'
                    );
                },

                clearText() {
                    this.text = '';
                    this.analyze();

                    this.showToast('Text cleared.');
                },

                trimText() {
                    if (!this.text) {
                        return;
                    }

                    this.text = this.text.trim();
                    this.analyze();

                    this.showToast(
                        'Leading and trailing whitespace removed.'
                    );
                },

                removeExtraSpaces() {
                    if (!this.text) {
                        return;
                    }

                    this.text = this.text
                        .replace(/[ \t]+/gu, ' ')
                        .replace(/ *\n */gu, '\n');

                    this.analyze();

                    this.showToast(
                        'Extra spaces removed.'
                    );
                },

                removeExtraLineBreaks() {
                    if (!this.text) {
                        return;
                    }

                    this.text = this.text
                        .replace(/\r\n/gu, '\n')
                        .replace(/\r/gu, '\n')
                        .replace(/\n{3,}/gu, '\n\n');

                    this.analyze();

                    this.showToast(
                        'Extra line breaks removed.'
                    );
                },

                setCharacterPreset(label, limit) {
                    this.characterLimit = limit;
                    this.saveSettings();

                    this.showToast(
                        `${label} limit set to ${limit} characters.`
                    );
                },

                saveDraftDebounced() {
                    clearTimeout(this.saveTimer);

                    this.saveTimer = setTimeout(() => {
                        this.saveDraft();
                    }, 500);
                },

                saveDraft() {
                    try {
                        localStorage.setItem(
                            'aabitech_character_counter_draft',
                            this.text
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
                                'aabitech_character_counter_draft'
                            );

                        if (draft !== null) {
                            this.text = draft;
                        }
                    } catch {
                        // Ignore localStorage errors.
                    }
                },

                saveSettings() {
                    try {
                        this.characterLimit =
                            this.sanitizeLimit(
                                this.characterLimit
                            );

                        this.wordLimit =
                            this.sanitizeLimit(
                                this.wordLimit
                            );

                        localStorage.setItem(
                            'aabitech_character_counter_settings',
                            JSON.stringify({
                                characterLimit:
                                    this.characterLimit,
                                wordLimit:
                                    this.wordLimit,
                            })
                        );

                        this.analyze();
                    } catch {
                        // Ignore storage errors.
                    }
                },

                sanitizeLimit(value) {
                    const number =
                        Number(value);

                    if (
                        !Number.isFinite(number) ||
                        number <= 0
                    ) {
                        return 0;
                    }

                    return Math.min(
                        10000000,
                        Math.floor(number)
                    );
                },

                loadSettings() {
                    try {
                        const raw =
                            localStorage.getItem(
                                'aabitech_character_counter_settings'
                            );

                        if (!raw) {
                            return;
                        }

                        const settings =
                            JSON.parse(raw);

                        this.characterLimit =
                            this.sanitizeLimit(
                                settings.characterLimit
                            );

                        this.wordLimit =
                            this.sanitizeLimit(
                                settings.wordLimit
                            );
                    } catch {
                        this.characterLimit = 0;
                        this.wordLimit = 0;
                    }
                },

                formatNumber(value) {
                    return Number(
                        value || 0
                    ).toLocaleString();
                },

                formatBytes(bytes) {
                    const value =
                        Number(bytes || 0);

                    if (value < 1024) {
                        return `${value} B`;
                    }

                    if (value < 1024 * 1024) {
                        return `${(value / 1024).toFixed(1)} KB`;
                    }

                    return `${(value / (1024 * 1024)).toFixed(2)} MB`;
                },

                showToast(message) {
                    window.dispatchEvent(
                        new CustomEvent('aabi-toast', {
                            detail: {
                                message,
                            },
                        })
                    );
                },
            };
        };
    </script>
    @endscript
</div>