<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiSlugGenerator()"
    x-cloak
    class="w-full"
>
    {{-- Privacy / processing notice --}}
    <div class="mb-4 flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
        <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
            <path
                fill-rule="evenodd"
                d="M10 1.75a.75.75 0 0 1 .75.75v.57a7.25 7.25 0 0 1 5.93 5.93h.57a.75.75 0 0 1 0 1.5h-.57a7.25 7.25 0 0 1-5.93 5.93v.57a.75.75 0 0 1-1.5 0v-.57a7.25 7.25 0 0 1-5.93-5.93h-.57a.75.75 0 0 1 0-1.5h.57a7.25 7.25 0 0 1 5.93-5.93V2.5A.75.75 0 0 1 10 1.75ZM6.25 10a3.75 3.75 0 1 0 7.5 0 3.75 3.75 0 0 0-7.5 0Z"
                clip-rule="evenodd"
            />
        </svg>

        <span>
            Your text is processed locally in your browser. Nothing is uploaded to AabiTech.
        </span>
    </div>

    {{-- Top controls --}}
    <div class="mb-4 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 items-center gap-1 overflow-x-auto rounded-lg bg-slate-100 p-1">
            <button
                type="button"
                data-active-group
                :class="{ 'is-active': mode === 'single' }"
                class="compact-tab"
                @click="setMode('single')"
            >
                Single Slug
            </button>

            <button
                type="button"
                data-active-group
                :class="{ 'is-active': mode === 'bulk' }"
                class="compact-tab"
                @click="setMode('bulk')"
            >
                Bulk Generator
            </button>
        </div>

        <div class="flex items-center gap-2">
            <label class="text-xs font-medium text-slate-500">
                Preset
            </label>

            <select
                x-model="preset"
                @change="applyPreset()"
                class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
            >
                <option value="generic">Generic</option>
                <option value="wordpress">WordPress</option>
                <option value="laravel">Laravel</option>
                <option value="drupal">Drupal</option>
                <option value="shopify">Shopify</option>
            </select>
        </div>
    </div>

    {{-- SINGLE MODE --}}
    <template x-if="mode === 'single'">
        <div class="space-y-4">

            {{-- Main workspace --}}
            <div class="grid gap-4 lg:grid-cols-2">

                {{-- Input --}}
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Original Text</h2>
                            <p class="mt-0.5 text-[11px] text-slate-500">
                                Enter a title, heading, URL or phrase.
                            </p>
                        </div>

                        <div class="text-[11px] text-slate-500">
                            <span x-text="input.length"></span> chars
                        </div>
                    </div>

                    <div class="p-4">
                        <textarea
                            x-model="input"
                            @input="generate()"
                            @keydown.meta.enter.prevent="copySlug()"
                            @keydown.ctrl.enter.prevent="copySlug()"
                            rows="11"
                            maxlength="10000"
                            placeholder="Example: How to Learn Python Programming in 2026"
                            class="block w-full resize-y rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm leading-6 text-slate-800 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                        ></textarea>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                @click="useExample()"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
                            >
                                Example
                            </button>

                            <button
                                type="button"
                                @click="clearAll()"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                            >
                                Clear
                            </button>

                            <span
                                x-show="smartDetection"
                                x-transition
                                class="ml-auto rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-medium text-indigo-700"
                                x-text="smartDetection"
                            ></span>
                        </div>
                    </div>
                </section>

                {{-- Output + stats --}}
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Generated Slug</h2>
                            <p class="mt-0.5 text-[11px] text-slate-500">
                                SEO-friendly URL slug
                            </p>
                        </div>

                        <span
                            class="rounded-full px-2 py-1 text-[10px] font-medium"
                            :class="qualityClass()"
                            x-text="qualityLabel()"
                        ></span>
                    </div>

                    <div class="p-4">
                        <div class="rounded-lg border border-indigo-100 bg-indigo-50/50 p-3">
                            <div class="flex items-start gap-2">
                                <code
                                    class="min-w-0 flex-1 break-all text-sm font-medium leading-6 text-indigo-800"
                                    x-text="output || 'Your generated slug will appear here'"
                                ></code>

                                <button
                                    type="button"
                                    :disabled="!output"
                                    @click="copySlug()"
                                    class="shrink-0 rounded-md border border-indigo-200 bg-white px-2.5 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-50 disabled:cursor-not-allowed disabled:opacity-40"
                                >
                                    <span x-show="copiedAction !== 'slug'">Copy</span>
                                    <span x-show="copiedAction === 'slug'" class="inline-flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.25 7.25a1 1 0 0 1-1.42 0l-3.25-3.25a1 1 0 1 1 1.414-1.414l2.543 2.543 6.543-6.543a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                                        </svg>
                                        Copied
                                    </span>
                                </button>
                            </div>
                        </div>

                        {{-- Basic stats --}}
                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Characters</div>
                                <div class="mt-1 text-sm font-semibold text-slate-800" x-text="output.length"></div>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Words</div>
                                <div class="mt-1 text-sm font-semibold text-slate-800" x-text="slugWordCount"></div>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Separator</div>
                                <div class="mt-1 text-sm font-semibold text-slate-800" x-text="settings.separator"></div>
                            </div>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                                <div class="text-[10px] uppercase tracking-wide text-slate-500">Score</div>
                                <div class="mt-1 text-sm font-semibold text-slate-800" x-text="qualityScore"></div>
                            </div>
                        </div>

                        {{-- URL preview --}}
                        <div class="mt-4">
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-xs font-semibold text-slate-700">
                                    URL Preview
                                </label>

                                <button
                                    type="button"
                                    @click="copyUrl()"
                                    :disabled="!output"
                                    class="text-[11px] font-medium text-indigo-600 hover:text-indigo-800 disabled:opacity-40"
                                >
                                    <span x-show="copiedAction !== 'url'">Copy URL</span>
                                    <span x-show="copiedAction === 'url'">✓ Copied</span>
                                </button>
                            </div>

                            <div class="grid gap-2 sm:grid-cols-2">
                                <input
                                    type="text"
                                    x-model="baseUrl"
                                    @input="saveDraft()"
                                    placeholder="https://example.com"
                                    class="h-9 rounded-md border border-slate-200 bg-white px-3 text-xs text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                />

                                <input
                                    type="text"
                                    x-model="urlPath"
                                    @input="saveDraft()"
                                    placeholder="/blog"
                                    class="h-9 rounded-md border border-slate-200 bg-white px-3 text-xs text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                />
                            </div>

                            <div class="mt-2 rounded-md bg-slate-50 px-3 py-2">
                                <code
                                    class="block break-all text-[11px] text-slate-600"
                                    x-text="fullUrl || 'https://example.com/your-slug'"
                                ></code>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Quality analysis --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <h2 class="text-sm font-semibold text-slate-900">Slug Analysis</h2>
                </div>

                <div class="grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">Length</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800">
                            <span x-text="output.length"></span>
                            <span class="font-normal text-slate-500">characters</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500" x-text="lengthMessage"></p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">Readability</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800" x-text="readabilityLabel"></div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            <span x-text="slugWordCount"></span> meaningful words
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">Characters</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800">
                            <span x-text="settings.transliterationMode === 'unicode' ? 'Unicode' : 'ASCII'"></span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500" x-text="characterMessage"></p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">Suggestions</div>
                        <div class="mt-1 text-sm font-semibold text-slate-800" x-text="suggestionCount"></div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            optimization checks
                        </p>
                    </div>
                </div>

                <div
                    x-show="warnings.length"
                    class="border-t border-slate-200 px-4 py-3"
                >
                    <div class="space-y-1.5">
                        <template x-for="warning in warnings" :key="warning">
                            <div class="flex items-start gap-2 text-xs text-amber-700">
                                <span class="mt-0.5">•</span>
                                <span x-text="warning"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            {{-- Variants --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Slug Variants</h2>
                            <p class="mt-0.5 text-[11px] text-slate-500">
                                Compare common URL styles.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="copySlug()"
                            :disabled="!output"
                            class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50 disabled:opacity-40"
                        >
                            Copy Main
                        </button>
                    </div>
                </div>

                <div class="grid gap-3 p-4 md:grid-cols-3">
                    <template x-for="variant in variants" :key="variant.label">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500" x-text="variant.label"></span>

                                <button
                                    type="button"
                                    @click="copyText(variant.value, 'variant-' + variant.label)"
                                    :disabled="!variant.value"
                                    class="text-[10px] font-medium text-indigo-600 hover:text-indigo-800 disabled:opacity-40"
                                >
                                    Copy
                                </button>
                            </div>

                            <code
                                class="block break-all text-xs text-slate-700"
                                x-text="variant.value || '—'"
                            ></code>
                        </div>
                    </template>
                </div>
            </section>

            {{-- Settings --}}
            <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
                <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Slug Settings</h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Customize separators, transliteration, stop words and limits.
                        </p>
                    </div>

                    <svg
                        class="h-4 w-4 text-slate-500 transition group-open:rotate-180"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </summary>

                <div class="border-t border-slate-200 p-4">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

                        {{-- Separator --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">
                                Separator
                            </label>

                            <div class="flex flex-wrap gap-1">
                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': settings.separator === '-' }"
                                    class="compact-tab border border-slate-200"
                                    @click="setSeparator('-')"
                                >
                                    Hyphen -
                                </button>

                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': settings.separator === '_' }"
                                    class="compact-tab border border-slate-200"
                                    @click="setSeparator('_')"
                                >
                                    Underscore _
                                </button>

                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': settings.separator === '.' }"
                                    class="compact-tab border border-slate-200"
                                    @click="setSeparator('.')"
                                >
                                    Dot .
                                </button>
                            </div>

                            <input
                                type="text"
                                maxlength="3"
                                x-model="settings.customSeparator"
                                @input="setCustomSeparator()"
                                placeholder="Custom"
                                class="mt-2 h-8 w-full rounded-md border border-slate-200 px-2.5 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            />
                        </div>

                        {{-- Character mode --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">
                                Character Mode
                            </label>

                            <div class="flex flex-wrap gap-1">
                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': settings.transliterationMode === 'ascii' }"
                                    class="compact-tab border border-slate-200"
                                    @click="settings.transliterationMode = 'ascii'; generate();"
                                >
                                    ASCII
                                </button>

                                <button
                                    type="button"
                                    data-active-group
                                    :class="{ 'is-active': settings.transliterationMode === 'unicode' }"
                                    class="compact-tab border border-slate-200"
                                    @click="settings.transliterationMode = 'unicode'; generate();"
                                >
                                    Unicode
                                </button>
                            </div>

                            <p class="mt-1.5 text-[10px] text-slate-500">
                                Unicode preserves non-Latin characters. ASCII transliterates supported scripts.
                            </p>
                        </div>

                        {{-- Length --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">
                                Maximum Length
                            </label>

                            <input
                                type="number"
                                min="0"
                                max="1000"
                                x-model.number="settings.maxLength"
                                @input="generate()"
                                placeholder="0 = unlimited"
                                class="h-8 w-full rounded-md border border-slate-200 px-2.5 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            />

                            <label class="mt-2 flex items-center gap-2 text-[11px] text-slate-600">
                                <input
                                    type="checkbox"
                                    x-model="settings.wholeWordLimit"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Keep complete words when truncating
                            </label>
                        </div>

                        {{-- Options --}}
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.removeStopWords"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Remove stop words
                            </label>

                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.removeDuplicateWords"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Remove duplicate words
                            </label>

                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.removeNumbers"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Remove numbers
                            </label>
                        </div>

                        {{-- More options --}}
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.preserveCase"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Preserve original case
                            </label>

                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.stripEmoji"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Remove emoji and symbols
                            </label>

                            <label class="flex items-center gap-2 text-xs text-slate-700">
                                <input
                                    type="checkbox"
                                    x-model="settings.smartMode"
                                    @change="generate()"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                Smart input detection
                            </label>
                        </div>

                        {{-- Custom stop words --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-slate-700">
                                Custom Stop Words
                            </label>

                            <textarea
                                x-model="settings.customStopWords"
                                @input="generate()"
                                rows="3"
                                placeholder="the, a, an, and..."
                                class="w-full resize-none rounded-md border border-slate-200 px-2.5 py-2 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                            ></textarea>
                        </div>
                    </div>

                    {{-- Replacement rules --}}
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-semibold text-slate-800">Custom Replacement Rules</h3>
                                <p class="mt-0.5 text-[10px] text-slate-500">
                                    Replace specific characters or phrases before slug generation.
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="addRule()"
                                class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                            >
                                + Add Rule
                            </button>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(rule, index) in settings.customRules" :key="rule.id">
                                <div class="grid grid-cols-[1fr_1fr_auto] gap-2">
                                    <input
                                        type="text"
                                        x-model="rule.find"
                                        @input="generate()"
                                        placeholder="Find"
                                        class="h-8 rounded-md border border-slate-200 px-2.5 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                    />

                                    <input
                                        type="text"
                                        x-model="rule.replace"
                                        @input="generate()"
                                        placeholder="Replace"
                                        class="h-8 rounded-md border border-slate-200 px-2.5 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                                    />

                                    <button
                                        type="button"
                                        @click="removeRule(index)"
                                        class="h-8 rounded-md border border-slate-200 px-2.5 text-xs text-slate-500 hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                    >
                                        ×
                                    </button>
                                </div>
                            </template>

                            <div
                                x-show="settings.customRules.length === 0"
                                class="rounded-md border border-dashed border-slate-200 px-3 py-3 text-center text-[11px] text-slate-500"
                            >
                                No custom replacement rules.
                            </div>
                        </div>
                    </div>
                </div>
            </details>

        </div>
    </template>

    {{-- BULK MODE --}}
    <template x-if="mode === 'bulk'">
        <div class="space-y-4">

            <div class="grid gap-4 lg:grid-cols-2">

                {{-- Bulk input --}}
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Bulk Titles</h2>
                            <p class="mt-0.5 text-[11px] text-slate-500">
                                One title per line, or import CSV/TXT.
                            </p>
                        </div>

                        <span class="text-[11px] text-slate-500">
                            <span x-text="bulkCount"></span> items
                        </span>
                    </div>

                    <div class="p-4">
                        <textarea
                            x-model="bulkInput"
                            @input="processBulk()"
                            rows="12"
                            maxlength="50000"
                            placeholder="How to Learn Python&#10;Best Computer Science Tools&#10;Introduction to Web Development"
                            class="block w-full resize-y rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm leading-6 text-slate-800 outline-none focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                        ></textarea>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <button
                                type="button"
                                @click="bulkExample()"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"
                            >
                                Example
                            </button>

                            <label class="cursor-pointer rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                                Import TXT / CSV
                                <input
                                    type="file"
                                    accept=".txt,.csv,text/plain,text/csv"
                                    class="hidden"
                                    @change="importBulkFile($event)"
                                />
                            </label>

                            <button
                                type="button"
                                @click="clearBulk()"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </section>

                {{-- Bulk summary --}}
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-4 py-3">
                        <h2 class="text-sm font-semibold text-slate-900">Bulk Summary</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-2 p-4 sm:grid-cols-4">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">Total</div>
                            <div class="mt-1 text-lg font-semibold text-slate-800" x-text="bulkResults.length"></div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">Valid</div>
                            <div class="mt-1 text-lg font-semibold text-emerald-600" x-text="bulkValid"></div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">Warnings</div>
                            <div class="mt-1 text-lg font-semibold text-amber-600" x-text="bulkWarnings"></div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">Duplicates</div>
                            <div class="mt-1 text-lg font-semibold text-indigo-600" x-text="bulkDuplicates"></div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 p-4">
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                @click="downloadBulk('csv')"
                                :disabled="!bulkResults.length"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                Export CSV
                            </button>

                            <button
                                type="button"
                                @click="downloadBulk('txt')"
                                :disabled="!bulkResults.length"
                                class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                Export TXT
                            </button>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Bulk result table --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Generated Slugs</h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Duplicate slugs receive automatic suffixes when enabled.
                        </p>
                    </div>

                    <label class="flex items-center gap-2 text-[11px] text-slate-600">
                        <input
                            type="checkbox"
                            x-model="settings.duplicateSuffix"
                            @change="processBulk()"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        Auto suffix duplicates
                    </label>
                </div>

                <div
                    x-show="bulkResults.length"
                    class="overflow-x-auto"
                >
                    <table class="min-w-full text-left text-xs">
                        <thead class="border-b border-slate-200 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-2.5 font-semibold">#</th>
                                <th class="px-4 py-2.5 font-semibold">Original</th>
                                <th class="px-4 py-2.5 font-semibold">Slug</th>
                                <th class="px-4 py-2.5 font-semibold">Status</th>
                                <th class="px-4 py-2.5 font-semibold"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <template x-for="item in bulkResults" :key="item.index">
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-slate-500" x-text="item.index"></td>

                                    <td class="max-w-[280px] px-4 py-3">
                                        <div class="truncate text-slate-700" x-text="item.original"></div>
                                    </td>

                                    <td class="max-w-[320px] px-4 py-3">
                                        <code class="break-all text-indigo-700" x-text="item.slug || '—'"></code>
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full px-2 py-1 text-[10px] font-medium"
                                            :class="item.valid ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'"
                                            x-text="item.status"
                                        ></span>
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <button
                                            type="button"
                                            @click="copyText(item.slug, 'bulk-' + item.index)"
                                            :disabled="!item.slug"
                                            class="text-[11px] font-medium text-indigo-600 hover:text-indigo-800 disabled:opacity-40"
                                        >
                                            Copy
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div
                    x-show="!bulkResults.length"
                    class="px-4 py-12 text-center"
                >
                    <div class="text-sm font-medium text-slate-500">No bulk results yet</div>
                    <p class="mt-1 text-xs text-slate-500">
                        Enter titles on the left to generate multiple slugs.
                    </p>
                </div>
            </section>

        </div>
    </template>

    {{-- Toast --}}
    <div
        x-show="toast.visible"
        x-transition
        class="fixed bottom-5 right-5 z-50 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-medium text-slate-700 shadow-lg"
        x-text="toast.message"
    ></div>
</div>

@script
<script>
window.aabiSlugGenerator = function () {
    return {
        mode: 'single',

        input: '',
        output: '',

        bulkInput: '',
        bulkResults: [],

        preset: 'generic',

        baseUrl: 'https://example.com',
        urlPath: '',

        copiedAction: '',

        smartDetection: '',

        qualityScore: 0,
        readabilityLabel: '—',
        lengthMessage: 'No slug generated yet.',
        characterMessage: 'No characters yet.',

        warnings: [],

        toast: {
            visible: false,
            message: '',
            timer: null
        },

        settings: {
            separator: '-',
            customSeparator: '-',

            preserveCase: false,

            removeStopWords: false,
            customStopWords: '',

            maxLength: 0,
            wholeWordLimit: true,

            transliterationMode: 'ascii',

            removeNumbers: false,
            removeDuplicateWords: false,
            stripEmoji: true,

            smartMode: true,

            duplicateSuffix: true,

            customRules: []
        },

        defaultStopWords: [
            'a',
            'an',
            'and',
            'are',
            'as',
            'at',
            'be',
            'by',
            'for',
            'from',
            'in',
            'into',
            'is',
            'it',
            'of',
            'on',
            'or',
            'that',
            'the',
            'this',
            'to',
            'was',
            'were',
            'with',
            'your',
            'you'
        ],

        init() {
            this.loadDraft();
            this.generate();

            this.$watch('settings', () => {
                this.generate();
                this.saveDraft();
            }, { deep: true });

            this.$watch('baseUrl', () => this.saveDraft());
            this.$watch('urlPath', () => this.saveDraft());
        },

        get slugWordCount() {
            if (!this.output) {
                return 0;
            }

            const separator = this.settings.separator || '-';

            return this.output
                .split(separator)
                .filter(Boolean)
                .length;
        },

        get fullUrl() {
            if (!this.output) {
                return '';
            }

            let domain = String(this.baseUrl || '').trim();

            if (!domain) {
                domain = 'https://example.com';
            }

            if (!/^https?:\/\//i.test(domain)) {
                domain = 'https://' + domain;
            }

            domain = domain.replace(/\/+$/, '');

            let path = String(this.urlPath || '').trim();

            if (path) {
                path = '/' + path.replace(/^\/+|\/+$/g, '');
            }

            return domain + path + '/' + this.output;
        },

        get variants() {
            if (!this.input.trim()) {
                return [
                    { label: 'Hyphen', value: '' },
                    { label: 'Underscore', value: '' },
                    { label: 'Unicode', value: '' }
                ];
            }

            return [
                {
                    label: 'Hyphen',
                    value: this.slugify(this.input, {
                        separator: '-',
                        transliterationMode: 'ascii'
                    })
                },
                {
                    label: 'Underscore',
                    value: this.slugify(this.input, {
                        separator: '_',
                        transliterationMode: 'ascii'
                    })
                },
                {
                    label: 'Unicode',
                    value: this.slugify(this.input, {
                        separator: '-',
                        transliterationMode: 'unicode'
                    })
                }
            ];
        },

        get suggestionCount() {
            let count = 0;

            if (this.output.length > 75) {
                count++;
            }

            if (this.slugWordCount > 8) {
                count++;
            }

            if (this.settings.removeStopWords && this.input !== this.output) {
                count++;
            }

            if (this.settings.transliterationMode === 'ascii') {
                count++;
            }

            return count;
        },

        get bulkCount() {
            return this.parseBulkInput(this.bulkInput).length;
        },

        get bulkValid() {
            return this.bulkResults.filter(item => item.valid).length;
        },

        get bulkWarnings() {
            return this.bulkResults.filter(item => item.warnings && item.warnings.length).length;
        },

        get bulkDuplicates() {
            return this.bulkResults.filter(item => item.duplicate).length;
        },

        setMode(value) {
            this.mode = value;

            if (value === 'bulk') {
                this.processBulk();
            } else {
                this.generate();
            }
        },

        setSeparator(value) {
            this.settings.separator = value;
            this.settings.customSeparator = value;
            this.generate();
        },

        setCustomSeparator() {
            let value = String(this.settings.customSeparator || '');

            value = value.replace(/\s/g, '');

            if (!value) {
                value = '-';
            }

            if (value.length > 3) {
                value = value.slice(0, 3);
            }

            this.settings.customSeparator = value;
            this.settings.separator = value;

            this.generate();
        },

        applyPreset() {
            const presets = {
                generic: {
                    separator: '-',
                    maxLength: 0,
                    transliterationMode: 'ascii',
                    preserveCase: false
                },

                wordpress: {
                    separator: '-',
                    maxLength: 75,
                    transliterationMode: 'ascii',
                    preserveCase: false
                },

                laravel: {
                    separator: '-',
                    maxLength: 0,
                    transliterationMode: 'ascii',
                    preserveCase: false
                },

                drupal: {
                    separator: '-',
                    maxLength: 0,
                    transliterationMode: 'ascii',
                    preserveCase: false
                },

                shopify: {
                    separator: '-',
                    maxLength: 255,
                    transliterationMode: 'ascii',
                    preserveCase: false
                }
            };

            const selected = presets[this.preset] || presets.generic;

            Object.assign(this.settings, selected);

            this.settings.customSeparator = selected.separator;

            this.generate();
        },

        generate() {
            const source = String(this.input || '');

            if (!source.trim()) {
                this.output = '';
                this.smartDetection = '';
                this.qualityScore = 0;
                this.readabilityLabel = '—';
                this.lengthMessage = 'No slug generated yet.';
                this.characterMessage = 'No characters yet.';
                this.warnings = [];
                return;
            }

            const prepared = this.prepareSmartInput(source);

            this.smartDetection = prepared.detection;

            this.output = this.slugify(prepared.text);

            this.analyzeSlug();
            this.saveDraft();
        },

        prepareSmartInput(value) {
            let text = String(value || '').trim();
            let detection = 'Title detected';

            if (this.settings.smartMode) {
                if (/^https?:\/\//i.test(text)) {
                    try {
                        const url = new URL(text);

                        const parts = url.pathname
                            .split('/')
                            .filter(Boolean);

                        if (parts.length) {
                            text = decodeURIComponent(parts[parts.length - 1]);
                            detection = 'URL detected';
                        }
                    } catch (error) {
                        detection = 'URL-like text';
                    }
                } else if (/<[^>]+>/.test(text)) {
                    text = text.replace(/<[^>]*>/g, ' ');
                    detection = 'HTML text detected';
                } else if (/^[#>\-*]\s+/.test(text)) {
                    text = text.replace(/^[#>\-*]\s+/, '');
                    detection = 'Heading detected';
                } else if (/[\u0600-\u06FF]/.test(text)) {
                    detection = 'Arabic / Urdu detected';
                } else if (/[\u0400-\u04FF]/.test(text)) {
                    detection = 'Cyrillic detected';
                } else if (/[\u4E00-\u9FFF\u3040-\u30FF\uAC00-\uD7AF]/.test(text)) {
                    detection = 'CJK text detected';
                }
            }

            return {
                text,
                detection
            };
        },

        slugify(value, overrides = {}) {
            let text = String(value || '').trim();

            if (!text) {
                return '';
            }

            const options = {
                separator: overrides.separator ?? this.settings.separator,
                transliterationMode: overrides.transliterationMode ?? this.settings.transliterationMode,
                preserveCase: overrides.preserveCase ?? this.settings.preserveCase
            };

            let separator = String(options.separator || '-');

            if (!separator.trim()) {
                separator = '-';
            }

            separator = separator.replace(/\s/g, '');

            if (separator.length > 3) {
                separator = separator.slice(0, 3);
            }

            if (this.settings.smartMode) {
                text = this.prepareSmartInput(text).text;
            }

            for (const rule of this.settings.customRules) {
                if (!rule.find) {
                    continue;
                }

                text = text.split(rule.find).join(rule.replace || '');
            }

            text = this.transliterate(text, options.transliterationMode);

            text = text.replace(/['’`]/g, '');

            if (this.settings.stripEmoji) {
                text = text.replace(
                    /[\p{Extended_Pictographic}\p{Emoji_Presentation}\p{Emoji_Modifier}\uFE0F]/gu,
                    ' '
                );
            }

            if (this.settings.removeNumbers) {
                text = text.replace(/[0-9０-９]+/g, ' ');
            }

            if (this.settings.removeStopWords) {
                text = this.removeStopWords(text);
            }

            if (this.settings.removeDuplicateWords) {
                text = this.removeDuplicateWords(text);
            }

            if (options.transliterationMode === 'unicode') {
                text = text.normalize('NFKC');
                text = text.replace(/[^\p{L}\p{N}\s]+/gu, ' ');
            } else {
                text = text.normalize('NFKD');
                text = text.replace(/[\u0300-\u036f]/g, '');
                text = text.replace(/[^A-Za-z0-9\s]+/g, ' ');
            }

            text = text.replace(/\s+/g, separator);

            const escapedSeparator = this.escapeRegExp(separator);

            text = text.replace(
                new RegExp('(?:' + escapedSeparator + ')+', 'g'),
                separator
            );

            text = text.replace(
                new RegExp('^' + escapedSeparator + '+|' + escapedSeparator + '+$', 'g'),
                ''
            );

            if (!options.preserveCase) {
                text = text.toLowerCase();
            }

            if (this.settings.maxLength > 0 && text.length > this.settings.maxLength) {
                text = this.truncateSlug(
                    text,
                    this.settings.maxLength,
                    separator
                );
            }

            return text;
        },

        transliterate(value, mode) {
            let text = String(value || '');

            if (mode === 'unicode') {
                return text.normalize('NFKC');
            }

            text = text.normalize('NFKD');
            text = text.replace(/[\u0300-\u036f]/g, '');

            const maps = {
                'А': 'A',
                'Б': 'B',
                'В': 'V',
                'Г': 'G',
                'Д': 'D',
                'Е': 'E',
                'Ё': 'Yo',
                'Ж': 'Zh',
                'З': 'Z',
                'И': 'I',
                'Й': 'Y',
                'К': 'K',
                'Л': 'L',
                'М': 'M',
                'Н': 'N',
                'О': 'O',
                'П': 'P',
                'Р': 'R',
                'С': 'S',
                'Т': 'T',
                'У': 'U',
                'Ф': 'F',
                'Х': 'Kh',
                'Ц': 'Ts',
                'Ч': 'Ch',
                'Ш': 'Sh',
                'Щ': 'Shch',
                'Ъ': '',
                'Ы': 'Y',
                'Ь': '',
                'Э': 'E',
                'Ю': 'Yu',
                'Я': 'Ya',

                'а': 'a',
                'б': 'b',
                'в': 'v',
                'г': 'g',
                'д': 'd',
                'е': 'e',
                'ё': 'yo',
                'ж': 'zh',
                'з': 'z',
                'и': 'i',
                'й': 'y',
                'к': 'k',
                'л': 'l',
                'м': 'm',
                'н': 'n',
                'о': 'o',
                'п': 'p',
                'р': 'r',
                'с': 's',
                'т': 't',
                'у': 'u',
                'ф': 'f',
                'х': 'kh',
                'ц': 'ts',
                'ч': 'ch',
                'ш': 'sh',
                'щ': 'shch',
                'ъ': '',
                'ы': 'y',
                'ь': '',
                'э': 'e',
                'ю': 'yu',
                'я': 'ya',

                'ا': 'a',
                'آ': 'a',
                'أ': 'a',
                'إ': 'i',
                'ٱ': 'a',
                'ب': 'b',
                'پ': 'p',
                'ت': 't',
                'ٹ': 't',
                'ث': 'th',
                'ج': 'j',
                'چ': 'ch',
                'ح': 'h',
                'خ': 'kh',
                'د': 'd',
                'ڈ': 'd',
                'ذ': 'dh',
                'ر': 'r',
                'ڑ': 'r',
                'ز': 'z',
                'ژ': 'zh',
                'س': 's',
                'ش': 'sh',
                'ص': 's',
                'ض': 'z',
                'ط': 't',
                'ظ': 'z',
                'ع': 'a',
                'غ': 'gh',
                'ف': 'f',
                'ق': 'q',
                'ک': 'k',
                'گ': 'g',
                'ل': 'l',
                'م': 'm',
                'ن': 'n',
                'ں': 'n',
                'و': 'w',
                'ہ': 'h',
                'ھ': 'h',
                'ء': '',
                'ی': 'y',
                'ے': 'e'
            };

            text = Array.from(text)
                .map(character => maps[character] ?? character)
                .join('');

            return text;
        },

        removeStopWords(value) {
            const custom = String(this.settings.customStopWords || '')
                .split(/[\s,]+/)
                .map(word => word.trim().toLowerCase())
                .filter(Boolean);

            const stopWords = new Set([
                ...this.defaultStopWords,
                ...custom
            ]);

            const words = String(value)
                .split(/\s+/)
                .filter(Boolean);

            const filtered = words.filter(word => {
                const normalized = word.toLowerCase();

                return !stopWords.has(normalized);
            });

            if (filtered.length < 1) {
                return words.slice(0, 1).join(' ');
            }

            return filtered.join(' ');
        },

        removeDuplicateWords(value) {
            const words = String(value)
                .split(/\s+/)
                .filter(Boolean);

            const seen = new Set();
            const result = [];

            for (const word of words) {
                const key = word.toLocaleLowerCase();

                if (seen.has(key)) {
                    continue;
                }

                seen.add(key);
                result.push(word);
            }

            return result.join(' ');
        },

        truncateSlug(value, maxLength, separator) {
            if (value.length <= maxLength) {
                return value;
            }

            let result = value.slice(0, maxLength);

            if (this.settings.wholeWordLimit) {
                const lastSeparator = result.lastIndexOf(separator);

                if (lastSeparator > 0) {
                    result = result.slice(0, lastSeparator);
                }
            }

            result = result.replace(
                new RegExp(this.escapeRegExp(separator) + '+$', 'g'),
                ''
            );

            return result;
        },

        analyzeSlug() {
            const slug = this.output;

            if (!slug) {
                this.qualityScore = 0;
                this.readabilityLabel = 'No slug';
                this.lengthMessage = 'Unable to generate a slug.';
                this.characterMessage = 'No usable characters.';
                this.warnings = ['Enter text containing letters or numbers.'];
                return;
            }

            let score = 100;
            const warnings = [];

            if (slug.length > 75) {
                score -= 15;
                warnings.push('The slug is longer than 75 characters.');
                this.lengthMessage = 'Consider shortening it for readability.';
            } else if (slug.length > 50) {
                score -= 5;
                this.lengthMessage = 'Moderately long slug.';
            } else {
                this.lengthMessage = 'Good URL length.';
            }

            if (this.slugWordCount > 8) {
                score -= 10;
                warnings.push('The slug contains many words.');
                this.readabilityLabel = 'Long';
            } else if (this.slugWordCount > 5) {
                score -= 4;
                this.readabilityLabel = 'Moderate';
            } else {
                this.readabilityLabel = 'Good';
            }

            if (this.settings.transliterationMode === 'ascii') {
                if (!/^[A-Za-z0-9._~-]+$/.test(slug)) {
                    score -= 20;
                    warnings.push('Some characters may not be URL-safe ASCII.');
                }

                this.characterMessage = 'ASCII-friendly URL characters.';
            } else {
                this.characterMessage = 'Unicode characters are preserved.';
            }

            if (this.settings.separator === '_') {
                warnings.push('Hyphens are generally easier to read in public URLs.');
                score -= 2;
            }

            if (this.settings.removeStopWords === false && this.slugWordCount > 6) {
                warnings.push('Consider removing unnecessary stop words.');
            }

            if (this.settings.maxLength > 0 && slug.length > this.settings.maxLength) {
                score -= 20;
                warnings.push('The slug exceeds the configured maximum length.');
            }

            this.qualityScore = Math.max(0, Math.min(100, score));
            this.warnings = [...new Set(warnings)];
        },

        qualityLabel() {
            if (!this.output) {
                return 'Waiting';
            }

            if (this.qualityScore >= 90) {
                return 'Excellent';
            }

            if (this.qualityScore >= 75) {
                return 'Good';
            }

            if (this.qualityScore >= 55) {
                return 'Review';
            }

            return 'Needs work';
        },

        qualityClass() {
            if (this.qualityScore >= 90) {
                return 'bg-emerald-50 text-emerald-700';
            }

            if (this.qualityScore >= 75) {
                return 'bg-indigo-50 text-indigo-700';
            }

            if (this.qualityScore >= 55) {
                return 'bg-amber-50 text-amber-700';
            }

            return 'bg-red-50 text-red-700';
        },

        escapeRegExp(value) {
            return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        },

        useExample() {
            this.input = 'How to Learn Python Programming in 2026';
            this.generate();
        },

        clearAll() {
            this.input = '';
            this.output = '';
            this.smartDetection = '';
            this.warnings = [];
            this.qualityScore = 0;
            this.readabilityLabel = '—';
            this.lengthMessage = 'No slug generated yet.';
            this.characterMessage = 'No characters yet.';
            this.copiedAction = '';
            this.saveDraft();
        },

        copySlug() {
            if (!this.output) {
                return;
            }

            this.copyText(this.output, 'slug');
        },

        copyUrl() {
            if (!this.fullUrl) {
                return;
            }

            this.copyText(this.fullUrl, 'url');
        },

        async copyText(value, action) {
            if (!value) {
                return;
            }

            try {
                await navigator.clipboard.writeText(value);

                this.copiedAction = action;

                this.showToast('Copied to clipboard');

                window.setTimeout(() => {
                    if (this.copiedAction === action) {
                        this.copiedAction = '';
                    }
                }, 1500);
            } catch (error) {
                this.fallbackCopy(value);
                this.copiedAction = action;
                this.showToast('Copied to clipboard');

                window.setTimeout(() => {
                    if (this.copiedAction === action) {
                        this.copiedAction = '';
                    }
                }, 1500);
            }
        },

        fallbackCopy(value) {
            const textarea = document.createElement('textarea');

            textarea.value = value;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';

            document.body.appendChild(textarea);

            textarea.focus();
            textarea.select();

            try {
                document.execCommand('copy');
            } finally {
                document.body.removeChild(textarea);
            }
        },

        showToast(message) {
            if (this.toast.timer) {
                clearTimeout(this.toast.timer);
            }

            this.toast.message = message;
            this.toast.visible = true;

            this.toast.timer = window.setTimeout(() => {
                this.toast.visible = false;
            }, 1800);
        },

        addRule() {
            this.settings.customRules.push({
                id: Date.now() + Math.random(),
                find: '',
                replace: ''
            });
        },

        removeRule(index) {
            this.settings.customRules.splice(index, 1);
            this.generate();
        },

        processBulk() {
            const titles = this.parseBulkInput(this.bulkInput);

            if (!titles.length) {
                this.bulkResults = [];
                return;
            }

            const seen = new Map();

            this.bulkResults = titles.map((original, index) => {
                let slug = this.slugify(original);
                const baseSlug = slug;

                let duplicate = false;

                if (slug) {
                    const key = slug.toLowerCase();
                    const count = seen.get(key) || 0;

                    if (count > 0) {
                        duplicate = true;

                        if (this.settings.duplicateSuffix) {
                            let suffixNumber = count + 1;
                            let candidate = this.addDuplicateSuffix(
                                baseSlug,
                                suffixNumber
                            );

                            while (seen.has(candidate.toLowerCase())) {
                                suffixNumber++;
                                candidate = this.addDuplicateSuffix(
                                    baseSlug,
                                    suffixNumber
                                );
                            }

                            slug = candidate;
                            seen.set(candidate.toLowerCase(), 1);
                        }
                    }

                    seen.set(key, count + 1);
                }

                const itemWarnings = [];

                if (!slug) {
                    itemWarnings.push('Empty slug');
                }

                if (slug.length > 75) {
                    itemWarnings.push('Long slug');
                }

                if (
                    this.settings.transliterationMode === 'ascii' &&
                    !/^[A-Za-z0-9._~-]+$/.test(slug)
                ) {
                    itemWarnings.push('Non-ASCII characters');
                }

                return {
                    index: index + 1,
                    original,
                    slug,
                    baseSlug,
                    duplicate,
                    warnings: itemWarnings,
                    valid: slug.length > 0 && itemWarnings.indexOf('Non-ASCII characters') === -1,
                    status: slug.length
                        ? itemWarnings.length
                            ? 'Review'
                            : 'Valid'
                        : 'Invalid'
                };
            });
        },

        addDuplicateSuffix(slug, number) {
            const separator = this.settings.separator || '-';
            const suffix = separator + String(number);

            if (
                this.settings.maxLength > 0 &&
                slug.length + suffix.length > this.settings.maxLength
            ) {
                const available = Math.max(
                    1,
                    this.settings.maxLength - suffix.length
                );

                slug = this.truncateSlug(
                    slug,
                    available,
                    separator
                );
            }

            return slug + suffix;
        },

        parseBulkInput(value) {
            const text = String(value || '').trim();

            if (!text) {
                return [];
            }

            if (text.includes(',') && /(^|\n)\s*["']?title["']?\s*,/i.test(text)) {
                const rows = this.parseCSV(text);

                return rows
                    .slice(1)
                    .map(row => row[0] || '')
                    .map(value => String(value).trim())
                    .filter(Boolean);
            }

            return text
                .split(/\r?\n/)
                .map(line => line.trim())
                .filter(Boolean);
        },

        parseCSV(text) {
            const rows = [];
            let row = [];
            let field = '';
            let quoted = false;

            for (let i = 0; i < text.length; i++) {
                const char = text[i];
                const next = text[i + 1];

                if (char === '"') {
                    if (quoted && next === '"') {
                        field += '"';
                        i++;
                    } else {
                        quoted = !quoted;
                    }

                    continue;
                }

                if (char === ',' && !quoted) {
                    row.push(field);
                    field = '';
                    continue;
                }

                if ((char === '\n' || char === '\r') && !quoted) {
                    if (char === '\r' && next === '\n') {
                        i++;
                    }

                    row.push(field);
                    rows.push(row);

                    row = [];
                    field = '';

                    continue;
                }

                field += char;
            }

            if (field.length || row.length) {
                row.push(field);
                rows.push(row);
            }

            return rows;
        },

        bulkExample() {
            this.bulkInput = [
                'How to Learn Python Programming',
                'Best Computer Science Tools for Students',
                'Introduction to Web Development',
                'How to Learn Python Programming',
                'AI Tools for Education in Pakistan'
            ].join('\n');

            this.processBulk();
        },

        clearBulk() {
            this.bulkInput = '';
            this.bulkResults = [];
        },

        importBulkFile(event) {
            const file = event.target.files && event.target.files[0];

            if (!file) {
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                this.showToast('File is larger than 5 MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = () => {
                this.bulkInput = String(reader.result || '');
                this.processBulk();
                event.target.value = '';
            };

            reader.onerror = () => {
                this.showToast('Unable to read the file');
                event.target.value = '';
            };

            reader.readAsText(file);
        },

        downloadBulk(type) {
            if (!this.bulkResults.length) {
                return;
            }

            let content = '';
            let mime = 'text/plain';
            let filename = 'aabitech-slugs.txt';

            if (type === 'csv') {
                const escapeCSV = value => {
                    const string = String(value ?? '');

                    if (/[",\n\r]/.test(string)) {
                        return '"' + string.replace(/"/g, '""') + '"';
                    }

                    return string;
                };

                const lines = [
                    ['Original', 'Slug', 'Status'].map(escapeCSV).join(',')
                ];

                this.bulkResults.forEach(item => {
                    lines.push([
                        item.original,
                        item.slug,
                        item.status
                    ].map(escapeCSV).join(','));
                });

                content = lines.join('\r\n');
                mime = 'text/csv;charset=utf-8';
                filename = 'aabitech-slugs.csv';
            } else {
                content = this.bulkResults
                    .map(item => item.slug)
                    .filter(Boolean)
                    .join('\n');
            }

            const blob = new Blob([content], { type: mime });
            const url = URL.createObjectURL(blob);

            const anchor = document.createElement('a');

            anchor.href = url;
            anchor.download = filename;
            document.body.appendChild(anchor);
            anchor.click();
            anchor.remove();

            URL.revokeObjectURL(url);
        },

        saveDraft() {
            try {
                const data = {
                    input: String(this.input || '').slice(0, 10000),
                    baseUrl: String(this.baseUrl || '').slice(0, 500),
                    urlPath: String(this.urlPath || '').slice(0, 300),
                    preset: this.preset,
                    settings: this.settings
                };

                localStorage.setItem(
                    'aabitech_slug_generator',
                    JSON.stringify(data)
                );
            } catch (error) {
                // Local storage may be unavailable.
            }
        },

        loadDraft() {
            try {
                const raw = localStorage.getItem('aabitech_slug_generator');

                if (!raw) {
                    return;
                }

                const data = JSON.parse(raw);

                if (data.input) {
                    this.input = String(data.input).slice(0, 10000);
                }

                if (data.baseUrl) {
                    this.baseUrl = String(data.baseUrl).slice(0, 500);
                }

                if (data.urlPath) {
                    this.urlPath = String(data.urlPath).slice(0, 300);
                }

                if (data.preset && typeof data.preset === 'string') {
                    this.preset = data.preset;
                }

                if (data.settings && typeof data.settings === 'object') {
                    Object.assign(this.settings, data.settings);

                    if (!Array.isArray(this.settings.customRules)) {
                        this.settings.customRules = [];
                    }
                }
            } catch (error) {
                // Ignore malformed local draft.
            }
        }
    };
};
</script>
@endscript