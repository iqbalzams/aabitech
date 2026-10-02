<?php

use Livewire\Component;

new class extends Component
{
    // All URL processing is intentionally performed in the browser.
    // No URL/text input is submitted to Laravel.
};
?>

<div
    x-data="aabiUrlEncoder()"
    x-cloak
    class="w-full"
>
    <div class="space-y-5">

        {{-- Primary mode tabs --}}
        <div class="overflow-x-auto border-b border-slate-200">
            <div class="flex min-w-max items-center gap-1">
                <button
                    type="button"
                    @click="setOperation('encode')"
                    :data-active-group="operation === 'encode' ? 'url-operation' : null"
                    :class="operation === 'encode' ? 'is-active' : ''"
                    class="compact-tab"
                    aria-label="Encode"
                >
                    Encode
                </button>

                <button
                    type="button"
                    @click="setOperation('decode')"
                    :data-active-group="operation === 'decode' ? 'url-operation' : null"
                    :class="operation === 'decode' ? 'is-active' : ''"
                    class="compact-tab"
                    aria-label="Decode"
                >
                    Decode
                </button>

                <button
                    type="button"
                    @click="setOperation('inspect')"
                    :data-active-group="operation === 'inspect' ? 'url-operation' : null"
                    :class="operation === 'inspect' ? 'is-active' : ''"
                    class="compact-tab"
                    aria-label="Inspect URL"
                >
                    Inspect
                </button>

                <button
                    type="button"
                    @click="setOperation('parse')"
                    :data-active-group="operation === 'parse' ? 'url-operation' : null"
                    :class="operation === 'parse' ? 'is-active' : ''"
                    class="compact-tab"
                    aria-label="Parse query string"
                >
                    Parse
                </button>
                <button
                    type="button"
                    @click="loadExample()"
                    class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                >
                    Example
                </button>

                <button
                    type="button"
                    @click="clearAll()"
                    class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-3 text-xs font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                >
                    Clear
                </button>
            </div>
        </div>

        {{-- Settings --}}
        <div class="rounded-xl border border-slate-200 bg-white">
            <div class="flex flex-col gap-3 p-3 sm:flex-row sm:flex-wrap sm:items-center">

                {{-- Encoding mode --}}
                <div class="flex min-w-0 items-center gap-2">
                    <label for="encoding-mode" class="shrink-0 text-[11px] font-medium text-slate-500">
                        URL Encoding Mode
                    </label>

                    <select
                        id="encoding-mode"
                        x-model="encodingMode"
                        @change="processIfAuto()"
                        class="h-8 min-w-[165px] rounded-md border border-slate-200 bg-white px-2 text-xs text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="component">EncodeURIComponent / Component</option>
                        <option value="uri">EncodeURI / Full URL</option>
                        <option value="rfc3986">RFC 3986</option>
                        <option value="form">Form URL Encoded</option>
                        <option value="path">Path Segment</option>
                        <option value="query">Query Value</option>
                    </select>
                </div>

                {{-- Batch toggle --}}
                <button
                    type="button"
                    @click="batchMode = !batchMode; processIfAuto()"
                    :data-active-group="batchMode ? 'url-options' : null"
                    :class="batchMode ? 'is-active' : ''"
                    class="inline-flex h-8 items-center rounded-md border border-slate-200 px-2.5 text-xs font-medium text-slate-600 transition"
                >
                    Batch / Lines
                </button>

                {{-- Auto --}}
                <label class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 px-2.5 text-xs text-slate-600">
                    <input
                        type="checkbox"
                        x-model="autoProcess"
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    Auto process
                </label>

                {{-- Plus handling --}}
                <label
                    x-show="operation === 'decode' || encodingMode === 'form'"
                    x-transition
                    class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 px-2.5 text-xs text-slate-600"
                >
                    <input
                        type="checkbox"
                        x-model="plusAsSpace"
                        @change="processIfAuto()"
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>+ = space</span>
                </label>

                {{-- Decode passes --}}
                <div
                    x-show="operation === 'decode'"
                    class="flex h-8 items-center gap-2"
                >
                    <label class="text-[11px] font-medium text-slate-500">
                        Decode passes
                    </label>

                    <select
                        x-model.number="decodePasses"
                        @change="processIfAuto()"
                        class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs text-slate-700 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>

                <div class="ml-auto flex items-center gap-2">
                    <input
                        x-ref="fileInput"
                        type="file"
                        accept=".txt,.text,text/plain"
                        class="hidden"
                        @change="importFile($event)"
                    >

                    <button
                        type="button"
                        @click="$refs.fileInput.click()"
                        class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        Import TXT
                    </button>

                    <button
                        type="button"
                        @click="downloadBatch('txt')"
                        x-show="batchMode && batchResults.length"
                        class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        TXT
                    </button>

                    <button
                        type="button"
                        @click="downloadBatch('csv')"
                        x-show="batchMode && batchResults.length"
                        class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        CSV
                    </button>
                </div>
            </div>

            {{-- Context mode --}}
            <div class="border-t border-slate-100 px-3 py-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-medium uppercase tracking-wide text-slate-500">
                        Context
                    </span>

                    <template x-for="context in contextModes" :key="context.value">
                        <button
                            type="button"
                            @click="contextMode = context.value; processIfAuto()"
                            :data-active-group="contextMode === context.value ? 'url-context' : null"
                            :class="contextMode === context.value ? 'is-active' : ''"
                            class="compact-tab"
                            x-text="context.label"
                        ></button>
                    </template>

                    <span class="ml-1 text-[10px] text-slate-500">
                        Context-aware encoding preserves URL structure where appropriate.
                    </span>
                </div>
            </div>
        </div>

        {{-- Drag/drop area --}}
        <div
            @dragover.prevent="dragActive = true"
            @dragleave.prevent="dragActive = false"
            @drop.prevent="handleDrop($event)"
            :class="dragActive ? 'border-indigo-400 bg-indigo-50/40' : 'border-slate-200 bg-slate-50/50'"
            class="rounded-lg border border-dashed px-3 py-2 text-center transition"
        >
            <p class="text-[11px] text-slate-500">
                Drag & drop a <strong>.txt</strong> file here for batch processing
                <span class="text-slate-500">· maximum <span x-text="formatBytes(maxInputBytes)"></span></span>
            </p>
        </div>

        {{-- Status --}}
        <div
            x-show="status.message"
            x-transition
            :class="status.type === 'error'
                ? 'border-red-200 bg-red-50 text-red-700'
                : status.type === 'success'
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                    : 'border-blue-200 bg-blue-50 text-blue-700'"
            class="rounded-lg border px-3 py-2 text-xs"
        >
            <div class="flex items-start justify-between gap-3">
                <span x-text="status.message"></span>

                <button
                    type="button"
                    @click="clearStatus()"
                    class="shrink-0 text-current opacity-60 hover:opacity-100"
                    aria-label="Dismiss"
                >
                    ×
                </button>
            </div>
        </div>

        {{-- Main editor --}}
        <div
            class="grid gap-4 lg:grid-cols-2"
            @keydown.ctrl.enter.prevent="process()"
            @keydown.meta.enter.prevent="process()"
        >

            {{-- Input --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-3 py-2.5">
                    <div>
                        <h2 class="text-xs font-semibold text-slate-800">
                            Input
                        </h2>

                        <p class="text-[10px] text-slate-500">
                            <span x-text="batchMode ? 'One value per line' : 'URL or text'"></span>
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button
                            type="button"
                            @click="copyText(input)"
                            :disabled="!input"
                            class="inline-flex h-7 items-center rounded-md border border-slate-200 px-2 text-[11px] font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            Copy
                        </button>

                        <button
                            type="button"
                            @click="loadExample()"
                            class="inline-flex h-7 items-center rounded-md border border-slate-200 px-2 text-[11px] font-medium text-slate-600 transition hover:bg-slate-50"
                        >
                            Example
                        </button>
                    </div>
                </div>

                <div
                    class="relative"
                    @paste="handlePaste($event)"
                >
                    <textarea
                        x-model="input"
                        @input="handleInput()"
                        @drop.prevent="handleDrop($event)"
                        rows="15"
                        spellcheck="false"
                        maxlength="500000"
                        placeholder="Paste or type a URL, text, query string, or one value per line..."
                        class="block min-h-[330px] w-full resize-y border-0 bg-white p-3 font-mono text-[13px] leading-6 text-slate-800 outline-none placeholder:text-slate-300 focus:ring-0"
                    ></textarea>

                    {{-- Smart detection --}}
                    <div
                        x-show="input"
                        class="absolute bottom-2 left-2 right-2 pointer-events-none"
                    >
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span
                                x-show="detection.isUrl"
                                class="rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-medium text-indigo-600"
                            >
                                URL detected
                            </span>

                            <span
                                x-show="detection.hasEncoding"
                                class="rounded bg-amber-50 px-1.5 py-0.5 text-[9px] font-medium text-amber-700"
                            >
                                %XX encoding detected
                            </span>

                            <span
                                x-show="detection.doubleEncoded"
                                class="rounded bg-purple-50 px-1.5 py-0.5 text-[9px] font-medium text-purple-700"
                            >
                                Possible double encoding
                            </span>

                            <span
                                x-show="detection.malformed"
                                class="rounded bg-red-50 px-1.5 py-0.5 text-[9px] font-medium text-red-700"
                            >
                                Invalid percent sequence
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 px-3 py-2">
                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-slate-500">
                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatNumber(inputStats.characters)"></strong>
                            chars
                        </span>

                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatBytes(inputStats.bytes)"></strong>
                        </span>

                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatNumber(inputStats.encodedTokens)"></strong>
                            %XX
                        </span>
                    </div>

                    <span
                        x-show="inputBytesExceeded"
                        class="text-[10px] font-medium text-red-600"
                    >
                        Input exceeds limit
                    </span>
                </div>
            </section>

            {{-- Output --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 px-3 py-2.5">
                    <div>
                        <h2 class="text-xs font-semibold text-slate-800">
                            Output
                        </h2>

                        <p class="text-[10px] text-slate-500">
                            <span x-text="operation === 'inspect' ? 'URL structure' : operation === 'parse' ? 'Query parameters' : 'Processed result'"></span>
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button
                            type="button"
                            @click="copyOutput()"
                            :disabled="!output"
                            class="inline-flex h-7 items-center gap-1 rounded-md border border-slate-200 px-2 text-[11px] font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            <span x-show="!copyState">Copy</span>
                            <span x-show="copyState" class="text-emerald-700">✓ Copied to clipboard</span>
                        </button>

                        <button
                            type="button"
                            @click="downloadOutput()"
                            :disabled="!output"
                            class="inline-flex h-7 items-center rounded-md border border-slate-200 px-2 text-[11px] font-medium text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            Download
                        </button>
                    </div>
                </div>

                {{-- Normal output --}}
                <div x-show="operation !== 'inspect' && operation !== 'parse'">
                    <label for="url-encoded-output" class="sr-only">
                        Encoded or decoded URL output
                    </label>
                    <textarea
                        x-model="output"
                        readonly
                        rows="15"
                        spellcheck="false"
                        class="block min-h-[330px] w-full resize-y border-0 bg-slate-50/50 p-3 font-mono text-[13px] leading-6 text-slate-800 outline-none"
                    ></textarea>
                </div>

                {{-- Inspect --}}
                <div
                    x-show="operation === 'inspect'"
                    class="min-h-[330px] bg-slate-50/40 p-3"
                >
                    <div
                        x-show="!inspection.valid"
                        class="rounded-lg border border-slate-200 bg-white p-4 text-center text-xs text-slate-500"
                    >
                        Enter a valid absolute URL to inspect its structure.
                    </div>

                    <div
                        x-show="inspection.valid"
                        class="grid gap-2 sm:grid-cols-2"
                    >
                        <template x-for="item in inspectionRows" :key="item.label">
                            <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                                <div
                                    class="mb-1 text-[9px] font-semibold uppercase tracking-wide text-slate-500"
                                    x-text="item.label"
                                ></div>

                                <div
                                    class="break-all font-mono text-[11px] text-slate-700"
                                    x-text="item.value || '—'"
                                ></div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Parse --}}
                <div
                    x-show="operation === 'parse'"
                    class="min-h-[330px] bg-slate-50/40 p-3"
                >
                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                        <div class="text-[10px] text-slate-500">
                            <span x-text="queryParameters.length"></span>
                            parameter<span x-show="queryParameters.length !== 1">s</span>
                        </div>

                        <button
                            type="button"
                            @click="addQueryParameter()"
                            class="inline-flex h-7 items-center rounded-md border border-slate-200 bg-white px-2 text-[11px] font-medium text-slate-600 hover:bg-slate-50"
                        >
                            + Add parameter
                        </button>
                    </div>

                    <div
                        x-show="!queryParameters.length"
                        class="rounded-lg border border-slate-200 bg-white p-6 text-center text-xs text-slate-500"
                    >
                        Enter a URL or query string to parse parameters.
                    </div>

                    <div
                        x-show="queryParameters.length"
                        class="overflow-x-auto rounded-lg border border-slate-200 bg-white"
                    >
                        <table class="w-full min-w-[620px] border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-left">
                                    <th class="px-2 py-2 text-[10px] font-semibold text-slate-500">Key</th>
                                    <th class="px-2 py-2 text-[10px] font-semibold text-slate-500">Value</th>
                                    <th class="w-20 px-2 py-2 text-[10px] font-semibold text-slate-500">State</th>
                                    <th class="w-10 px-2 py-2"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template x-for="(parameter, index) in queryParameters" :key="parameter.id">
                                    <tr class="border-b border-slate-100 last:border-b-0">
                                        <td class="p-1.5">
                                            <input
                                                type="text"
                                                x-model="parameter.key"
                                                @input="queryDirty = true"
                                                class="h-8 w-full rounded border border-slate-200 px-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100"
                                            >
                                        </td>

                                        <td class="p-1.5">
                                            <input
                                                type="text"
                                                x-model="parameter.value"
                                                @input="queryDirty = true"
                                                class="h-8 w-full rounded border border-slate-200 px-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100"
                                            >
                                        </td>

                                        <td class="p-1.5">
                                            <span
                                                x-show="parameter.bare"
                                                class="rounded bg-amber-50 px-1.5 py-1 text-[9px] font-medium text-amber-700"
                                            >
                                                bare key
                                            </span>

                                            <span
                                                x-show="!parameter.bare"
                                                class="rounded bg-slate-100 px-1.5 py-1 text-[9px] text-slate-500"
                                            >
                                                =
                                            </span>
                                        </td>

                                        <td class="p-1.5 text-center">
                                            <button
                                                type="button"
                                                @click="removeQueryParameter(index)"
                                                class="text-xs text-slate-500 hover:text-red-600"
                                                aria-label="Remove parameter"
                                            >
                                                ×
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div
                        x-show="queryParameters.length"
                        class="mt-2 flex justify-end"
                    >
                        <button
                            type="button"
                            @click="rebuildUrlFromParameters()"
                            class="inline-flex h-8 items-center rounded-md bg-indigo-600 px-3 text-xs font-medium text-white transition hover:bg-indigo-700"
                        >
                            Rebuild URL
                        </button>
                    </div>
                </div>

                <div
                    x-show="operation !== 'inspect' && operation !== 'parse'"
                    class="border-t border-slate-100 px-3 py-2"
                >
                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-slate-500">
                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatNumber(outputStats.characters)"></strong>
                            chars
                        </span>

                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatBytes(outputStats.bytes)"></strong>
                        </span>

                        <span>
                            <strong class="font-medium text-slate-600" x-text="formatNumber(outputStats.encodedTokens)"></strong>
                            %XX
                        </span>

                        <span
                            x-show="outputStats.byteDifference !== 0"
                            :class="outputStats.byteDifference > 0 ? 'text-indigo-600' : 'text-emerald-700'"
                        >
                            <span x-text="outputStats.byteDifference > 0 ? '+' : ''"></span>
                            <span x-text="formatBytes(Math.abs(outputStats.byteDifference))"></span>
                        </span>
                    </div>
                </div>
            </section>
        </div>

        {{-- Main actions --}}
        <div class="flex flex-wrap items-center justify-center gap-2">
            <button
                type="button"
                @click="swap()"
                :disabled="!input && !output"
                class="inline-flex h-9 items-center rounded-md border border-slate-200 bg-white px-4 text-xs font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40"
            >
                ⇄ Swap
            </button>

            <button
                type="button"
                @click="process()"
                :disabled="inputBytesExceeded"
                class="inline-flex h-9 items-center rounded-md bg-indigo-600 px-5 text-xs font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-40"
            >
                <span x-text="operation === 'encode' ? 'Encode' : operation === 'decode' ? 'Decode' : operation === 'inspect' ? 'Inspect URL' : 'Parse Query'"></span>
            </button>

            <button
                type="button"
                @click="clearAll()"
                class="inline-flex h-9 items-center rounded-md border border-slate-200 bg-white px-4 text-xs font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Clear
            </button>

            <span class="hidden text-[10px] text-slate-500 sm:inline">
                Ctrl/Cmd + Enter
            </span>
        </div>

        {{-- Query / URL utilities --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-3 py-2.5">
                <h2 class="text-xs font-semibold text-slate-800">
                    URL Utilities
                </h2>
            </div>

            <div class="grid gap-px bg-slate-100 sm:grid-cols-2 lg:grid-cols-4">
                <button
                    type="button"
                    @click="normalizeUrl()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Normalize URL</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Normalize protocol, host, path and query structure.
                    </div>
                </button>

                <button
                    type="button"
                    @click="sortQueryParameters()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Sort Query</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Sort query parameters alphabetically while preserving values.
                    </div>
                </button>

                <button
                    type="button"
                    @click="extractQuery()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Extract Query</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Extract the complete query string from a URL.
                    </div>
                </button>

                <button
                    type="button"
                    @click="removeQuery()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Remove Query</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Remove query parameters while keeping the rest of the URL.
                    </div>
                </button>

                <button
                    type="button"
                    @click="removeFragment()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Remove Fragment</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Remove the URL hash fragment.
                    </div>
                </button>

                <button
                    type="button"
                    @click="decodeQuery()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Decode Query</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Decode query keys and values without changing URL structure.
                    </div>
                </button>

                <button
                    type="button"
                    @click="copyQuery()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Copy Query</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Copy only the query portion of the current URL.
                    </div>
                </button>

                <button
                    type="button"
                    @click="encodeParametersOnly()"
                    class="bg-white p-3 text-left transition hover:bg-slate-50"
                >
                    <div class="text-xs font-semibold text-slate-700">Encode Parameters</div>
                    <div class="mt-1 text-[10px] leading-4 text-slate-500">
                        Encode query parameters while preserving URL structure.
                    </div>
                </button>
            </div>
        </section>

        {{-- Diagnostics --}}
        <section class="grid gap-4 lg:grid-cols-3">

            {{-- Detection --}}
            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-slate-800">
                        Smart Detection
                    </h2>

                    <span class="text-[9px] text-slate-500">
                        Automatic
                    </span>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Input type</span>
                        <span
                            class="font-medium text-slate-700"
                            x-text="detection.type"
                        ></span>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Encoded content</span>
                        <span
                            :class="detection.hasEncoding ? 'text-amber-600' : 'text-slate-500'"
                            x-text="detection.hasEncoding ? 'Detected' : 'Not detected'"
                        ></span>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Malformed encoding</span>
                        <span
                            :class="detection.malformed ? 'text-red-600' : 'text-emerald-700'"
                            x-text="detection.malformed ? 'Found' : 'None'"
                        ></span>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500">Double encoding</span>
                        <span
                            :class="detection.doubleEncoded ? 'text-purple-600' : 'text-slate-500'"
                            x-text="detection.doubleEncoded ? 'Possible' : 'Not detected'"
                        ></span>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-slate-800">
                        Encoding Statistics
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg bg-slate-50 p-2">
                        <div class="text-[9px] uppercase tracking-wide text-slate-500">Input bytes</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700" x-text="formatBytes(inputStats.bytes)"></div>
                    </div>

                    <div class="rounded-lg bg-slate-50 p-2">
                        <div class="text-[9px] uppercase tracking-wide text-slate-500">Output bytes</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700" x-text="formatBytes(outputStats.bytes)"></div>
                    </div>

                    <div class="rounded-lg bg-slate-50 p-2">
                        <div class="text-[9px] uppercase tracking-wide text-slate-500">Encoded tokens</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700" x-text="formatNumber(outputStats.encodedTokens)"></div>
                    </div>

                    <div class="rounded-lg bg-slate-50 p-2">
                        <div class="text-[9px] uppercase tracking-wide text-slate-500">Parameters</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700" x-text="queryParameters.length"></div>
                    </div>
                </div>
            </div>

            {{-- Round trip --}}
            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-xs font-semibold text-slate-800">
                        Round-Trip Verification
                    </h2>

                    <button
                        type="button"
                        @click="verifyRoundTrip()"
                        :disabled="!input"
                        class="inline-flex h-7 items-center rounded-md border border-slate-200 px-2 text-[10px] font-medium text-slate-600 hover:bg-slate-50 disabled:opacity-40"
                    >
                        Verify
                    </button>
                </div>

                <div
                    x-show="roundTrip.message"
                    class="rounded-lg border p-2.5 text-[11px]"
                    :class="roundTrip.ok
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                        : 'border-amber-200 bg-amber-50 text-amber-700'"
                >
                    <div class="font-medium" x-text="roundTrip.message"></div>

                    <div
                        x-show="roundTrip.details"
                        class="mt-1 text-[10px] opacity-80"
                        x-text="roundTrip.details"
                    ></div>
                </div>

                <div
                    x-show="!roundTrip.message"
                    class="text-[11px] leading-5 text-slate-500"
                >
                    Encode/decode the current value and compare the result with the original.
                </div>
            </div>
        </section>

        {{-- Encoded token mapping --}}
        <section
            x-show="tokenMap.length"
            class="rounded-xl border border-slate-200 bg-white"
        >
            <div class="flex items-center justify-between border-b border-slate-100 px-3 py-2.5">
                <div>
                    <h2 class="text-xs font-semibold text-slate-800">
                        Encoded Character Mapping
                    </h2>

                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Visual %XX representation of encoded bytes.
                    </p>
                </div>

                <span
                    class="text-[10px] text-slate-500"
                    x-text="tokenMap.length + ' token' + (tokenMap.length === 1 ? '' : 's')"
                ></span>
            </div>

            <div class="max-h-48 overflow-auto p-3">
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="token in tokenMap" :key="token.id">
                        <div class="inline-flex items-center overflow-hidden rounded-md border border-slate-200 bg-white font-mono text-[10px]">
                            <span
                                class="bg-slate-50 px-1.5 py-1 text-slate-500"
                                x-text="token.token"
                            ></span>

                            <span class="px-1.5 py-1 text-indigo-600">
                                →
                            </span>

                            <span
                                class="px-1.5 py-1 text-slate-700"
                                x-text="token.character"
                            ></span>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        {{-- Multi-level inspector --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="flex flex-col gap-2 border-b border-slate-100 px-3 py-2.5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xs font-semibold text-slate-800">
                        Multi-Level Encoding Inspector
                    </h2>

                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Decode each layer separately to identify nested or double-encoded URLs.
                    </p>
                </div>

                <button
                    type="button"
                    @click="inspectLevels()"
                    :disabled="!input"
                    class="inline-flex h-7 items-center rounded-md border border-slate-200 px-2.5 text-[10px] font-medium text-slate-600 hover:bg-slate-50 disabled:opacity-40"
                >
                    Analyze Layers
                </button>
            </div>

            <div
                x-show="decodeLevels.length"
                class="divide-y divide-slate-100"
            >
                <template x-for="level in decodeLevels" :key="level.level">
                    <div class="grid gap-2 px-3 py-2.5 sm:grid-cols-[55px_1fr]">
                        <div class="text-[10px] font-semibold text-slate-500">
                            Level <span x-text="level.level"></span>
                        </div>

                        <div class="break-all rounded-md bg-slate-50 px-2.5 py-2 font-mono text-[11px] text-slate-700">
                            <span x-text="level.value"></span>

                            <span
                                x-show="level.changed"
                                class="ml-2 rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-medium text-indigo-600"
                            >
                                decoded
                            </span>
                        </div>
                    </div>
                </template>
            </div>

            <div
                x-show="!decodeLevels.length"
                class="px-3 py-4 text-[11px] text-slate-500"
            >
                Run the layer analyzer to inspect recursive URL encoding.
            </div>
        </section>

        {{-- Developer snippets --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="flex flex-col gap-2 border-b border-slate-100 px-3 py-2.5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xs font-semibold text-slate-800">
                        Developer Code Snippets
                    </h2>

                    <p cltext-slate-500ass="mt-0.5 text-[10px] text-slate-500">
                        Equivalent encoding or decoding operations for common languages.
                    </p>
                </div>
                <label for="snippet-language" class="sr-only">
                    Snippet programming language
                </label>
                <select
                    id="snippet-language"
                    x-model="snippetLanguage"
                    class="h-7 rounded-md border border-slate-200 bg-white px-2 text-[10px] text-slate-600 outline-none focus:border-indigo-400"
                >
                    <option value="javascript">JavaScript</option>
                    <option value="php">PHP</option>
                    <option value="python">Python</option>
                    <option value="java">Java</option>
                    <option value="go">Go</option>
                    <option value="curl">cURL</option>
                    <option value="csharp">C#</option>
                </select>
            </div>

            <div class="relative p-3">
                <pre class="overflow-x-auto rounded-lg bg-slate-900 p-3 font-mono text-[11px] leading-5 text-slate-200"><code x-text="developerSnippet"></code></pre>

                <button
                    type="button"
                    @click="copyText(developerSnippet)"
                    class="absolute right-5 top-5 inline-flex h-7 items-center rounded-md border border-slate-700 bg-slate-800 px-2 text-[10px] font-medium text-slate-300 hover:bg-slate-700"
                >
                    Copy
                </button>
            </div>
        </section>

        {{-- Batch results --}}
        <section
            x-show="batchMode && batchResults.length"
            class="rounded-xl border border-slate-200 bg-white"
        >
            <div class="flex items-center justify-between border-b border-slate-100 px-3 py-2.5">
                <div>
                    <h2 class="text-xs font-semibold text-slate-800">
                        Batch Results
                    </h2>

                    <p class="mt-0.5 text-[10px] text-slate-500">
                        Per-line processing status.
                    </p>
                </div>

                <div class="text-[10px] text-slate-500">
                    <span x-text="batchSuccessCount"></span>
                    successful ·
                    <span x-text="batchErrorCount"></span>
                    errors
                </div>
            </div>

            <div class="max-h-72 overflow-auto">
                <table class="w-full min-w-[700px] border-collapse">
                    <thead class="sticky top-0 bg-slate-50">
                        <tr class="border-b border-slate-200 text-left">
                            <th class="w-12 px-3 py-2 text-[10px] font-semibold text-slate-500">#</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500">Input</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500">Output</th>
                            <th class="w-20 px-3 py-2 text-[10px] font-semibold text-slate-500">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="(row, index) in batchResults" :key="row.id">
                            <tr class="border-b border-slate-100 last:border-0">
                                <td class="px-3 py-2 text-[10px] text-slate-500" x-text="index + 1"></td>

                                <td class="max-w-[240px] px-3 py-2">
                                    <div
                                        class="truncate font-mono text-[10px] text-slate-600"
                                        :title="row.input"
                                        x-text="row.input"
                                    ></div>
                                </td>

                                <td class="max-w-[300px] px-3 py-2">
                                    <div
                                        class="truncate font-mono text-[10px] text-slate-700"
                                        :title="row.output"
                                        x-text="row.output"
                                    ></div>

                                    <div
                                        x-show="row.error"
                                        class="mt-1 truncate text-[9px] text-red-600"
                                        x-text="row.error"
                                    ></div>
                                </td>

                                <td class="px-3 py-2">
                                    <span
                                        :class="row.success
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : 'bg-red-50 text-red-700'"
                                        class="rounded px-1.5 py-1 text-[9px] font-medium"
                                        x-text="row.success ? 'Success' : 'Error'"
                                    ></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Privacy --}}
        <section class="rounded-xl border border-emerald-100 bg-emerald-50/50 px-3 py-3">
            <div class="flex items-start gap-2.5">
                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    ✓
                </div>

                <div>
                    <h2 class="text-xs font-semibold text-emerald-800">
                        Your data stays in your browser
                    </h2>

                    <p class="mt-1 text-[11px] leading-5 text-emerald-700">
                        URL and text processing is performed locally in your browser.
                        AabiTech does not need to receive, fetch, execute or store the URL content.
                        Imported files are processed locally as well.
                    </p>
                </div>
            </div>
        </section>

        {{-- Information --}}
        <section class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <h3 class="text-xs font-semibold text-slate-800">
                    EncodeURIComponent
                </h3>

                <p class="mt-1.5 text-[11px] leading-5 text-slate-500">
                    Suitable for individual URL components such as query parameter names,
                    values and path segments.
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <h3 class="text-xs font-semibold text-slate-800">
                    EncodeURI / Full URL
                </h3>

                <p class="mt-1.5 text-[11px] leading-5 text-slate-500">
                    Designed for complete URLs while preserving structural characters such as
                    protocol separators, query delimiters and fragments.
                </p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-3">
                <h3 class="text-xs font-semibold text-slate-800">
                    Query Parser
                </h3>

                <p class="mt-1.5 text-[11px] leading-5 text-slate-500">
                    Inspect, edit and rebuild query parameters while retaining repeated,
                    empty and bare parameters.
                </p>
            </div>
        </section>

    </div>

    @script
    <script>
        window.aabiUrlEncoder = function () {
            return {
                operation: 'encode',

                encodingMode: 'component',
                contextMode: 'auto',

                autoProcess: true,
                plusAsSpace: true,
                batchMode: false,
                decodePasses: 1,

                input: '',
                output: '',

                copyState: false,
                dragActive: false,

                maxInputBytes: 5 * 1024 * 1024,

                status: {
                    type: '',
                    message: '',
                },

                inputStats: {
                    characters: 0,
                    bytes: 0,
                    encodedTokens: 0,
                },

                outputStats: {
                    characters: 0,
                    bytes: 0,
                    encodedTokens: 0,
                    byteDifference: 0,
                },

                detection: {
                    type: 'Unknown',
                    isUrl: false,
                    hasEncoding: false,
                    malformed: false,
                    doubleEncoded: false,
                },

                inspection: {
                    valid: false,
                    protocol: '',
                    username: '',
                    password: '',
                    host: '',
                    hostname: '',
                    port: '',
                    path: '',
                    query: '',
                    fragment: '',
                    origin: '',
                    href: '',
                },

                queryParameters: [],
                queryDirty: false,

                roundTrip: {
                    ok: false,
                    message: '',
                    details: '',
                },

                tokenMap: [],
                decodeLevels: [],

                batchResults: [],

                snippetLanguage: 'javascript',

                contextModes: [
                    { value: 'auto', label: 'Smart' },
                    { value: 'text', label: 'Text' },
                    { value: 'url', label: 'Full URL' },
                    { value: 'query', label: 'Query Parameter' },
                    { value: 'path', label: 'Path Segment' },
                ],

                inspectionRows: [],

                examples: {
                    encode: 'https://example.com/search?q=hello world & language=اردو 😊',
                    decode: 'https%3A%2F%2Fexample.com%2Fsearch%3Fq%3Dhello%2520world',
                    inspect: 'https://user:password@example.com:8080/products/item?id=123&lang=en#details',
                    parse: 'https://example.com/search?q=hello%20world&lang=en&tag=python&tag=web',
                },

                init() {
                    this.restoreDraft();
                    this.updateAll();

                    if (!this.input) {
                        this.input = this.examples.encode;
                        this.updateAll();
                    }

                    this.$watch('snippetLanguage', () => {
                        this.updateSnippet();
                    });

                    this.$watch('operation', () => {
                        this.updateAll();
                    });

                    this.$watch('input', () => {
                        this.persistDraft();
                        this.updateDetection();
                        this.updateStats();
                        this.updateTokenMap();

                        if (this.operation === 'parse') {
                            this.parseQuery();
                        }

                        if (this.autoProcess && this.input) {
                            this.process(false);
                        }
                    });

                    this.$watch('output', () => {
                        this.updateStats();
                        this.updateSnippet();
                    });
                },

                setOperation(operation) {
                    this.operation = operation;

                    if (operation === 'inspect') {
                        this.inspectUrl();
                    } else if (operation === 'parse') {
                        this.parseQuery();
                    } else if (this.input) {
                        this.process(false);
                    }

                    this.persistDraft();
                },

                handleInput() {
                    this.clearStatus();

                    if (this.input.length > 500000) {
                        this.input = this.input.slice(0, 500000);
                    }

                    this.updateAll();
                },

                processIfAuto() {
                    if (this.autoProcess && this.input) {
                        this.process();
                    }
                },

                process(showMessage = true) {
                    if (this.inputBytesExceeded) {
                        this.showError(
                            'Input is too large. Please reduce it to ' +
                            this.formatBytes(this.maxInputBytes) +
                            ' or less.'
                        );
                        return;
                    }

                    this.clearStatus();

                    try {
                        if (this.operation === 'encode') {
                            this.processEncode();
                        } else if (this.operation === 'decode') {
                            this.processDecode();
                        } else if (this.operation === 'inspect') {
                            this.inspectUrl();
                        } else if (this.operation === 'parse') {
                            this.parseQuery();
                        }

                        if (showMessage && this.operation !== 'inspect' && this.operation !== 'parse') {
                            this.showSuccess(
                                this.operation === 'encode'
                                    ? 'Encoding completed.'
                                    : 'Decoding completed.'
                            );
                        }

                        this.updateAll();
                    } catch (error) {
                        this.output = '';
                        this.showError(this.getFriendlyError(error));
                    }
                },

                processEncode() {
                    if (this.batchMode) {
                        this.processBatch('encode');
                        return;
                    }

                    this.output = this.encodeContextAware(this.input);

                    this.updateTokenMap();
                    this.updateStats();
                    this.updateSnippet();
                },

                processDecode() {
                    if (this.batchMode) {
                        this.processBatch('decode');
                        return;
                    }

                    const result = this.decodeRecursive(
                        this.input,
                        Math.max(1, Math.min(5, Number(this.decodePasses) || 1))
                    );

                    this.output = result.value;

                    if (result.errors.length) {
                        throw new Error(result.errors[0]);
                    }

                    this.updateTokenMap();
                    this.updateStats();
                    this.updateSnippet();
                },

                encodeContextAware(value) {
                    if (!value) {
                        return '';
                    }

                    if (this.contextMode === 'text') {
                        return this.encodeValue(value);
                    }

                    if (this.contextMode === 'query') {
                        return this.encodeQueryValue(value);
                    }

                    if (this.contextMode === 'path') {
                        return this.encodePathSegment(value);
                    }

                    if (this.contextMode === 'url') {
                        return this.encodeFullUrl(value);
                    }

                    // Smart detection.
                    if (this.contextMode === 'auto') {
                        if (this.looksLikeAbsoluteUrl(value)) {
                            return this.encodeFullUrl(value);
                        }

                        return this.encodeValue(value);
                    }

                    return this.encodeValue(value);
                },

                encodeValue(value) {
                    switch (this.encodingMode) {
                        case 'uri':
                            return encodeURI(value);

                        case 'rfc3986':
                            return encodeURIComponent(value)
                                .replace(/[!'()*]/g, character =>
                                    '%' + character.charCodeAt(0).toString(16).toUpperCase()
                                );

                        case 'form':
                            return encodeURIComponent(value)
                                .replace(/%20/g, '+');

                        case 'path':
                            return this.encodePathSegment(value);

                        case 'query':
                            return this.encodeQueryValue(value);

                        case 'component':
                        default:
                            return encodeURIComponent(value);
                    }
                },

                encodeQueryValue(value) {
                    let encoded = encodeURIComponent(value);

                    if (this.encodingMode === 'form') {
                        encoded = encoded.replace(/%20/g, '+');
                    }

                    return encoded;
                },

                encodePathSegment(value) {
                    return encodeURIComponent(value)
                        .replace(/%2F/gi, '%2F');
                },

                encodeFullUrl(value) {
                    try {
                        const url = new URL(value);

                        const protocol = url.protocol;
                        const username = url.username
                            ? encodeURIComponent(this.safeDecode(url.username))
                            : '';

                        const password = url.password
                            ? encodeURIComponent(this.safeDecode(url.password))
                            : '';

                        const auth =
                            username || password
                                ? username +
                                  (password ? ':' + password : '') +
                                  '@'
                                : '';

                        const host = url.host;

                        const pathname = url.pathname
                            .split('/')
                            .map(segment => this.encodePathSegment(this.safeDecode(segment)))
                            .join('/');

                        const query = url.search
                            ? '?' + this.encodeQueryPreservingStructure(url.search.slice(1))
                            : '';

                        const hash = url.hash
                            ? '#' + encodeURIComponent(this.safeDecode(url.hash.slice(1)))
                            : '';

                        return protocol + '//' + auth + host + pathname + query + hash;
                    } catch {
                        return encodeURI(value);
                    }
                },

                encodeQueryPreservingStructure(query) {
                    return query
                        .split('&')
                        .map(part => {
                            if (!part) {
                                return '';
                            }

                            const equalIndex = part.indexOf('=');

                            if (equalIndex === -1) {
                                return this.encodeQueryValue(
                                    this.safeDecode(part)
                                );
                            }

                            const key = part.slice(0, equalIndex);
                            const value = part.slice(equalIndex + 1);

                            return (
                                this.encodeQueryValue(this.safeDecode(key)) +
                                '=' +
                                this.encodeQueryValue(this.safeDecode(value))
                            );
                        })
                        .join('&');
                },

                decodeValue(value) {
                    let prepared = value;

                    if (this.plusAsSpace) {
                        prepared = prepared.replace(/\+/g, ' ');
                    }

                    if (this.containsMalformedPercentEncoding(prepared)) {
                        throw new Error(
                            'Malformed percent-encoding detected. Every % must be followed by two hexadecimal characters.'
                        );
                    }

                    try {
                        return decodeURIComponent(prepared);
                    } catch (error) {
                        throw new Error(
                            'Unable to decode the input. Check for incomplete or invalid percent-encoded UTF-8 sequences.'
                        );
                    }
                },

                decodeRecursive(value, passes) {
                    let current = value;
                    const errors = [];

                    for (let i = 0; i < passes; i++) {
                        if (!this.containsPercentEncoding(current)) {
                            break;
                        }

                        try {
                            const next = this.decodeValue(current);

                            if (next === current) {
                                break;
                            }

                            current = next;
                        } catch (error) {
                            errors.push(error.message || 'Decoding failed.');
                            break;
                        }
                    }

                    return {
                        value: current,
                        errors,
                    };
                },

                processBatch(operation) {
                    const lines = this.input
                        .replace(/\r\n/g, '\n')
                        .replace(/\r/g, '\n')
                        .split('\n');

                    const results = [];

                    lines.forEach((line, index) => {
                        if (!line.trim()) {
                            return;
                        }

                        try {
                            const result =
                                operation === 'encode'
                                    ? this.encodeContextAware(line)
                                    : this.decodeRecursive(
                                        line,
                                        Math.max(1, Math.min(5, Number(this.decodePasses) || 1))
                                    ).value;

                            results.push({
                                id: 'row-' + Date.now() + '-' + index,
                                input: line,
                                output: result,
                                success: true,
                                error: '',
                            });
                        } catch (error) {
                            results.push({
                                id: 'row-' + Date.now() + '-' + index,
                                input: line,
                                output: '',
                                success: false,
                                error: this.getFriendlyError(error),
                            });
                        }
                    });

                    this.batchResults = results;

                    this.output = results
                        .map(row => row.success ? row.output : '')
                        .join('\n');

                    if (results.length) {
                        const errors = results.filter(row => !row.success).length;

                        if (errors) {
                            this.showError(
                                `${errors} of ${results.length} batch item(s) could not be processed.`
                            );
                        } else {
                            this.showSuccess(
                                `${results.length} batch item(s) processed successfully.`
                            );
                        }
                    }
                },

                inspectUrl() {
                    const value = this.input.trim();

                    this.inspection = {
                        valid: false,
                        protocol: '',
                        username: '',
                        password: '',
                        host: '',
                        hostname: '',
                        port: '',
                        path: '',
                        query: '',
                        fragment: '',
                        origin: '',
                        href: '',
                    };

                    if (!value) {
                        this.inspectionRows = [];
                        return;
                    }

                    try {
                        const url = new URL(value);

                        this.inspection = {
                            valid: true,
                            protocol: url.protocol,
                            username: url.username,
                            password: url.password ? '••••••••' : '',
                            host: url.host,
                            hostname: url.hostname,
                            port: url.port,
                            path: url.pathname,
                            query: url.search,
                            fragment: url.hash,
                            origin: url.origin,
                            href: url.href,
                        };

                        this.inspectionRows = [
                            { label: 'Protocol', value: url.protocol },
                            { label: 'Username', value: url.username },
                            { label: 'Password', value: url.password ? '••••••••' : '' },
                            { label: 'Host', value: url.host },
                            { label: 'Hostname', value: url.hostname },
                            { label: 'Port', value: url.port },
                            { label: 'Path', value: url.pathname },
                            { label: 'Query', value: url.search },
                            { label: 'Fragment', value: url.hash },
                            { label: 'Origin', value: url.origin },
                            { label: 'Href', value: url.href },
                        ];

                        this.output = url.href;
                        this.updateStats();
                    } catch {
                        this.inspectionRows = [];
                        this.output = '';

                        this.showError(
                            'The input is not a valid absolute URL. Include a protocol such as https://.'
                        );
                    }
                },

                parseQuery() {
                    const source = this.input.trim();

                    this.queryParameters = [];

                    if (!source) {
                        return;
                    }

                    let query = source;

                    try {
                        if (this.looksLikeAbsoluteUrl(source)) {
                            const url = new URL(source);
                            query = url.search.slice(1);
                        } else if (query.startsWith('?')) {
                            query = query.slice(1);
                        }
                    } catch {
                        // Continue as raw query string.
                    }

                    if (!query) {
                        return;
                    }

                    query.split('&').forEach((part, index) => {
                        if (part === '') {
                            return;
                        }

                        const equalIndex = part.indexOf('=');

                        const rawKey =
                            equalIndex === -1
                                ? part
                                : part.slice(0, equalIndex);

                        const rawValue =
                            equalIndex === -1
                                ? ''
                                : part.slice(equalIndex + 1);

                        let key = rawKey;
                        let value = rawValue;
                        let error = '';

                        try {
                            key = this.decodeQueryPart(rawKey);
                            value = this.decodeQueryPart(rawValue);
                        } catch (exception) {
                            error = exception.message || 'Invalid encoding';
                        }

                        this.queryParameters.push({
                            id: 'parameter-' + Date.now() + '-' + index,
                            rawKey,
                            rawValue,
                            key,
                            value,
                            bare: equalIndex === -1,
                            error,
                            repeated: false,
                            doubleEncoded:
                                this.containsPercentEncoding(rawKey) &&
                                this.containsPercentEncoding(value),
                        });
                    });

                    const keyCounts = {};

                    this.queryParameters.forEach(parameter => {
                        keyCounts[parameter.key] =
                            (keyCounts[parameter.key] || 0) + 1;
                    });

                    this.queryParameters.forEach(parameter => {
                        parameter.repeated =
                            keyCounts[parameter.key] > 1;
                    });

                    this.output = this.queryParameters
                        .map(parameter =>
                            `${parameter.key}=${parameter.value}`
                        )
                        .join('\n');
                },

                decodeQueryPart(value) {
                    let prepared = value;

                    if (this.plusAsSpace) {
                        prepared = prepared.replace(/\+/g, ' ');
                    }

                    if (this.containsMalformedPercentEncoding(prepared)) {
                        throw new Error('Invalid %XX sequence');
                    }

                    return decodeURIComponent(prepared);
                },

                addQueryParameter() {
                    this.queryParameters.push({
                        id: 'parameter-' + Date.now() + '-' + Math.random(),
                        rawKey: '',
                        rawValue: '',
                        key: '',
                        value: '',
                        bare: false,
                        error: '',
                        repeated: false,
                        doubleEncoded: false,
                    });

                    this.queryDirty = true;
                },

                removeQueryParameter(index) {
                    this.queryParameters.splice(index, 1);
                    this.queryDirty = true;
                },

                rebuildUrlFromParameters() {
                    const source = this.input.trim();

                    let base = '';
                    let fragment = '';

                    try {
                        if (this.looksLikeAbsoluteUrl(source)) {
                            const url = new URL(source);

                            base =
                                url.origin +
                                url.pathname;

                            fragment = url.hash;
                        }
                    } catch {
                        base = '';
                    }

                    const encodedQuery = this.queryParameters
                        .map(parameter => {
                            const key = this.encodeQueryValue(parameter.key);
                            const value = this.encodeQueryValue(parameter.value);

                            return parameter.bare
                                ? key
                                : key + '=' + value;
                        })
                        .join('&');

                    if (base) {
                        this.output =
                            base +
                            (encodedQuery ? '?' + encodedQuery : '') +
                            fragment;
                    } else {
                        this.output =
                            encodedQuery
                                ? '?' + encodedQuery
                                : '';
                    }

                    this.showSuccess('URL rebuilt from query parameters.');
                    this.updateStats();
                },

                normalizeUrl() {
                    const source = this.input.trim();

                    try {
                        const url = new URL(source);

                        url.protocol = url.protocol.toLowerCase();
                        url.hostname = url.hostname.toLowerCase();

                        if (
                            (url.protocol === 'https:' && url.port === '443') ||
                            (url.protocol === 'http:' && url.port === '80')
                        ) {
                            url.port = '';
                        }

                        this.output = url.toString();

                        this.showSuccess('URL normalized.');
                        this.updateStats();
                    } catch {
                        this.showError('Enter a valid absolute URL to normalize.');
                    }
                },

                sortQueryParameters() {
                    const source = this.input.trim();

                    try {
                        const url = new URL(source);

                        const entries = [];

                        for (const [key, value] of url.searchParams.entries()) {
                            entries.push([key, value]);
                        }

                        entries.sort((a, b) =>
                            a[0].localeCompare(b[0]) ||
                            a[1].localeCompare(b[1])
                        );

                        url.search = '';

                        entries.forEach(([key, value]) => {
                            url.searchParams.append(key, value);
                        });

                        this.output = url.toString();

                        this.showSuccess('Query parameters sorted.');
                        this.updateStats();
                    } catch {
                        this.showError('Enter a valid absolute URL to sort its query parameters.');
                    }
                },

                extractQuery() {
                    try {
                        const url = new URL(this.input.trim());

                        this.output = url.search
                            ? url.search
                            : '';

                        if (!this.output) {
                            this.showSuccess('The URL has no query parameters.');
                        } else {
                            this.showSuccess('Query string extracted.');
                        }

                        this.updateStats();
                    } catch {
                        this.showError('Enter a valid absolute URL.');
                    }
                },

                removeQuery() {
                    try {
                        const url = new URL(this.input.trim());

                        url.search = '';

                        this.output = url.toString();

                        this.showSuccess('Query string removed.');
                        this.updateStats();
                    } catch {
                        this.showError('Enter a valid absolute URL.');
                    }
                },

                removeFragment() {
                    try {
                        const url = new URL(this.input.trim());

                        url.hash = '';

                        this.output = url.toString();

                        this.showSuccess('Fragment removed.');
                        this.updateStats();
                    } catch {
                        this.showError('Enter a valid absolute URL.');
                    }
                },

                decodeQuery() {
                    const source = this.input.trim();

                    try {
                        if (this.looksLikeAbsoluteUrl(source)) {
                            const url = new URL(source);

                            const decodedQuery = url.search
                                .slice(1)
                                .split('&')
                                .map(part => {
                                    const equalIndex = part.indexOf('=');

                                    if (equalIndex === -1) {
                                        return this.decodeQueryPart(part);
                                    }

                                    const key = this.decodeQueryPart(
                                        part.slice(0, equalIndex)
                                    );

                                    const value = this.decodeQueryPart(
                                        part.slice(equalIndex + 1)
                                    );

                                    return key + '=' + value;
                                })
                                .join('&');

                            this.output = decodedQuery
                                ? '?' + decodedQuery
                                : '';
                        } else {
                            this.output = source
                                .split('&')
                                .map(part => {
                                    const equalIndex = part.indexOf('=');

                                    if (equalIndex === -1) {
                                        return this.decodeQueryPart(part);
                                    }

                                    return (
                                        this.decodeQueryPart(part.slice(0, equalIndex)) +
                                        '=' +
                                        this.decodeQueryPart(part.slice(equalIndex + 1))
                                    );
                                })
                                .join('&');
                        }

                        this.showSuccess('Query parameters decoded.');
                        this.updateStats();
                    } catch (error) {
                        this.showError(this.getFriendlyError(error));
                    }
                },

                copyQuery() {
                    const source = this.output || this.input;

                    try {
                        const url = new URL(source);

                        this.copyText(url.search);

                        this.showSuccess('Query string copied to clipboard.');
                    } catch {
                        this.showError('Enter a valid absolute URL.');
                    }
                },

                encodeParametersOnly() {
                    const source = this.input.trim();

                    try {
                        const url = new URL(source);

                        const encodedQuery = url.search
                            .slice(1)
                            .split('&')
                            .map(part => {
                                const equalIndex = part.indexOf('=');

                                if (equalIndex === -1) {
                                    return this.encodeQueryValue(
                                        this.safeDecode(part)
                                    );
                                }

                                const key = this.safeDecode(
                                    part.slice(0, equalIndex)
                                );

                                const value = this.safeDecode(
                                    part.slice(equalIndex + 1)
                                );

                                return (
                                    this.encodeQueryValue(key) +
                                    '=' +
                                    this.encodeQueryValue(value)
                                );
                            })
                            .join('&');

                        url.search = encodedQuery
                            ? '?' + encodedQuery
                            : '';

                        this.output = url.toString();

                        this.showSuccess(
                            'Query parameters encoded while preserving the URL structure.'
                        );

                        this.updateStats();
                    } catch {
                        this.showError(
                            'Enter a valid absolute URL to encode its parameters.'
                        );
                    }
                },

                swap() {
                    const oldInput = this.input;

                    this.input = this.output;
                    this.output = oldInput;

                    if (this.operation === 'inspect') {
                        this.inspectUrl();
                    }

                    if (this.operation === 'parse') {
                        this.parseQuery();
                    }

                    this.updateAll();
                    this.showSuccess('Input and output swapped.');
                },

                loadExample() {
                    this.input = this.examples[this.operation] || this.examples.encode;

                    if (this.operation === 'inspect') {
                        this.inspectUrl();
                    } else if (this.operation === 'parse') {
                        this.parseQuery();
                    } else {
                        this.process(false);
                    }

                    this.updateAll();
                    this.showSuccess('Example loaded.');
                },

                clearAll() {
                    this.input = '';
                    this.output = '';
                    this.batchResults = [];
                    this.queryParameters = [];
                    this.queryDirty = false;
                    this.tokenMap = [];
                    this.decodeLevels = [];

                    this.inspection = {
                        valid: false,
                        protocol: '',
                        username: '',
                        password: '',
                        host: '',
                        hostname: '',
                        port: '',
                        path: '',
                        query: '',
                        fragment: '',
                        origin: '',
                        href: '',
                    };

                    this.inspectionRows = [];

                    this.roundTrip = {
                        ok: false,
                        message: '',
                        details: '',
                    };

                    this.clearStatus();
                    this.persistDraft();
                    this.updateAll();
                },

                verifyRoundTrip() {
                    if (!this.input) {
                        this.roundTrip = {
                            ok: false,
                            message: 'Enter a value first.',
                            details: '',
                        };

                        return;
                    }

                    try {
                        const encoded = this.encodeContextAware(this.input);

                        const decoded = this.decodeRecursive(
                            encoded,
                            1
                        ).value;

                        const equal = decoded === this.input;

                        this.roundTrip = {
                            ok: equal,
                            message: equal
                                ? 'Round-trip verified successfully.'
                                : 'Round-trip differs from the original.',
                            details: equal
                                ? 'Encode → decode returned the original input.'
                                : 'The encoding context or existing percent-encoding may change the result.',
                        };
                    } catch (error) {
                        this.roundTrip = {
                            ok: false,
                            message: 'Round-trip verification failed.',
                            details: this.getFriendlyError(error),
                        };
                    }
                },

                inspectLevels() {
                    if (!this.input) {
                        this.decodeLevels = [];
                        return;
                    }

                    const levels = [];
                    let current = this.input;

                    levels.push({
                        level: 0,
                        value: current,
                        changed: false,
                    });

                    for (let i = 1; i <= 5; i++) {
                        if (!this.containsPercentEncoding(current)) {
                            break;
                        }

                        try {
                            const decoded = this.decodeValue(current);

                            if (decoded === current) {
                                break;
                            }

                            levels.push({
                                level: i,
                                value: decoded,
                                changed: true,
                            });

                            current = decoded;
                        } catch {
                            levels.push({
                                level: i,
                                value: 'Decoding stopped: malformed percent-encoding.',
                                changed: false,
                            });

                            break;
                        }
                    }

                    this.decodeLevels = levels;
                },

                updateDetection() {
                    const value = this.input;

                    const hasEncoding = this.containsPercentEncoding(value);
                    const malformed = this.containsMalformedPercentEncoding(value);

                    let doubleEncoded = false;

                    if (hasEncoding) {
                        try {
                            const once = this.decodeValue(value);

                            doubleEncoded =
                                /%25[0-9A-Fa-f]{2}/.test(value) ||
                                this.containsPercentEncoding(once);
                        } catch {
                            doubleEncoded =
                                /%25[0-9A-Fa-f]{2}/.test(value);
                        }
                    }

                    const isUrl = this.looksLikeAbsoluteUrl(value);

                    let type = 'Text';

                    if (!value.trim()) {
                        type = 'Empty';
                    } else if (isUrl) {
                        type = 'URL';
                    } else if (value.includes('&') && value.includes('=')) {
                        type = 'Query String';
                    } else if (hasEncoding) {
                        type = 'Encoded Text';
                    }

                    this.detection = {
                        type,
                        isUrl,
                        hasEncoding,
                        malformed,
                        doubleEncoded,
                    };
                },

                updateStats() {
                    this.inputStats = this.getStats(this.input);
                    this.outputStats = this.getStats(this.output);

                    this.outputStats.byteDifference =
                        this.outputStats.bytes -
                        this.inputStats.bytes;
                },

                getStats(value) {
                    const text = value || '';

                    return {
                        characters: Array.from(text).length,
                        bytes: this.getByteLength(text),
                        encodedTokens:
                            (text.match(/%[0-9A-Fa-f]{2}/g) || []).length,
                    };
                },

                updateTokenMap() {
                    const source =
                        this.operation === 'decode'
                            ? this.input
                            : this.output;

                    const tokens = [];
                    const regex = /%([0-9A-Fa-f]{2})/g;

                    let match;
                    let id = 0;

                    while ((match = regex.exec(source)) !== null) {
                        const hex = match[1];

                        let character = 'Invalid byte';

                        try {
                            character = decodeURIComponent('%' + hex);
                        } catch {
                            try {
                                character = String.fromCharCode(
                                    parseInt(hex, 16)
                                );
                            } catch {
                                character = 'Invalid byte';
                            }
                        }

                        tokens.push({
                            id: ++id,
                            token: '%' + hex.toUpperCase(),
                            character:
                                character === ' '
                                    ? 'space'
                                    : character,
                        });

                        if (tokens.length >= 500) {
                            break;
                        }
                    }

                    this.tokenMap = tokens;
                },

                updateAll() {
                    this.updateDetection();
                    this.updateStats();
                    this.updateTokenMap();
                    this.updateSnippet();
                },

                updateSnippet() {
                    const value =
                        this.input ||
                        'https://example.com/search?q=hello world';

                    const encoded =
                        this.output ||
                        this.encodeContextAware(value);

                    const mode =
                        this.operation === 'decode'
                            ? 'decode'
                            : 'encode';

                    if (this.snippetLanguage === 'javascript') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `const encoded = encodeURIComponent(${JSON.stringify(value)});`
                                : `const decoded = decodeURIComponent(${JSON.stringify(value)});`;
                        return;
                    }

                    if (this.snippetLanguage === 'php') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `$encoded = rawurlencode(${this.phpQuote(value)});`
                                : `$decoded = rawurldecode(${this.phpQuote(value)});`;
                        return;
                    }

                    if (this.snippetLanguage === 'python') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `from urllib.parse import quote\\n\\nencoded = quote(${this.pythonQuote(value)}, safe='')`
                                : `from urllib.parse import unquote\\n\\ndecoded = unquote(${this.pythonQuote(value)})`;
                        return;
                    }

                    if (this.snippetLanguage === 'java') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `import java.net.URLEncoder;\\n\\nString encoded = URLEncoder.encode(${this.javaQuote(value)}, java.nio.charset.StandardCharsets.UTF_8);`
                                : `import java.net.URLDecoder;\\n\\nString decoded = URLDecoder.decode(${this.javaQuote(value)}, java.nio.charset.StandardCharsets.UTF_8);`;
                        return;
                    }

                    if (this.snippetLanguage === 'go') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `package main\\n\\nimport "net/url"\\n\\nencoded := url.QueryEscape(${this.goQuote(value)})`
                                : `package main\\n\\nimport "net/url"\\n\\ndecoded, err := url.QueryUnescape(${this.goQuote(value)})`;
                        return;
                    }

                    if (this.snippetLanguage === 'curl') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `curl --get --data-urlencode "value=${this.shellEscape(value)}" https://example.com/`
                                : `printf '%s' '${this.shellEscape(value)}' | python3 -c "import sys, urllib.parse; print(urllib.parse.unquote(sys.stdin.read()))"`;
                        return;
                    }

                    if (this.snippetLanguage === 'csharp') {
                        this.developerSnippet =
                            mode === 'encode'
                                ? `using System.Net;\\n\\nstring encoded = WebUtility.UrlEncode(${this.csharpQuote(value)});`
                                : `using System.Net;\\n\\nstring decoded = WebUtility.UrlDecode(${this.csharpQuote(value)});`;
                        return;
                    }

                    this.developerSnippet = encoded;
                },

                get developerSnippet() {
                    return this._developerSnippet || '';
                },

                set developerSnippet(value) {
                    this._developerSnippet = value;
                },

                phpQuote(value) {
                    return "'" +
                        String(value)
                            .replace(/\\/g, '\\\\')
                            .replace(/'/g, "\\'") +
                        "'";
                },

                pythonQuote(value) {
                    return JSON.stringify(value);
                },

                javaQuote(value) {
                    return JSON.stringify(value);
                },

                goQuote(value) {
                    return JSON.stringify(value);
                },

                csharpQuote(value) {
                    return JSON.stringify(value);
                },

                shellEscape(value) {
                    return String(value).replace(/'/g, `'\\''`);
                },

                handlePaste(event) {
                    if (!this.batchMode) {
                        return;
                    }

                    setTimeout(() => {
                        this.processIfAuto();
                    }, 0);
                },

                handleDrop(event) {
                    this.dragActive = false;

                    const files = event.dataTransfer?.files;

                    if (files && files.length) {
                        const file = files[0];

                        if (
                            file.type === 'text/plain' ||
                            /\.txt$/i.test(file.name)
                        ) {
                            this.readTextFile(file);
                            return;
                        }

                        this.showError('Please drop a TXT file.');
                        return;
                    }

                    const droppedText =
                        event.dataTransfer?.getData('text/plain');

                    if (droppedText) {
                        this.input = droppedText;
                        this.batchMode = droppedText.includes('\n');
                        this.processIfAuto();
                    }
                },

                importFile(event) {
                    const file = event.target.files?.[0];

                    if (!file) {
                        return;
                    }

                    this.readTextFile(file);

                    event.target.value = '';
                },

                readTextFile(file) {
                    if (file.size > this.maxInputBytes) {
                        this.showError(
                            'The selected file is too large. Maximum size is ' +
                            this.formatBytes(this.maxInputBytes) +
                            '.'
                        );
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = () => {
                        this.input = String(reader.result || '');

                        this.batchMode =
                            this.input.includes('\n');

                        this.showSuccess(
                            `${file.name} imported successfully.`
                        );

                        if (this.autoProcess) {
                            this.process();
                        }
                    };

                    reader.onerror = () => {
                        this.showError('Unable to read the selected text file.');
                    };

                    reader.readAsText(file);
                },

                downloadOutput() {
                    if (!this.output) {
                        return;
                    }

                    this.downloadText(
                        this.output,
                        this.operation === 'encode'
                            ? 'url-encoded.txt'
                            : this.operation === 'decode'
                                ? 'url-decoded.txt'
                                : 'url-result.txt',
                        'text/plain;charset=utf-8'
                    );
                },

                downloadBatch(type) {
                    if (!this.batchResults.length) {
                        return;
                    }

                    if (type === 'txt') {
                        const text = this.batchResults
                            .map(row => row.output)
                            .join('\n');

                        this.downloadText(
                            text,
                            'url-batch-results.txt',
                            'text/plain;charset=utf-8'
                        );

                        return;
                    }

                    const csv = [
                        ['Input', 'Output', 'Status', 'Error'],
                        ...this.batchResults.map(row => [
                            row.input,
                            row.output,
                            row.success ? 'Success' : 'Error',
                            row.error || '',
                        ]),
                    ]
                        .map(row => row.map(value => this.csvEscape(value)).join(','))
                        .join('\r\n');

                    this.downloadText(
                        csv,
                        'url-batch-results.csv',
                        'text/csv;charset=utf-8'
                    );
                },

                csvEscape(value) {
                    const text = String(value ?? '');

                    return '"' +
                        text.replace(/"/g, '""') +
                        '"';
                },

                downloadText(content, filename, mime) {
                    const blob = new Blob(
                        [content],
                        { type: mime }
                    );

                    const url = URL.createObjectURL(blob);
                    const anchor = document.createElement('a');

                    anchor.href = url;
                    anchor.download = filename;

                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();

                    setTimeout(() => {
                        URL.revokeObjectURL(url);
                    }, 1000);
                },

                async copyOutput() {
                    if (!this.output) {
                        return;
                    }

                    const copied = await this.copyText(this.output);

                    if (copied) {
                        this.copyState = true;

                        setTimeout(() => {
                            this.copyState = false;
                        }, 1800);
                    }
                },

                async copyText(value) {
                    if (!value) {
                        return false;
                    }

                    try {
                        await navigator.clipboard.writeText(String(value));

                        return true;
                    } catch {
                        return this.copyFallback(String(value));
                    }
                },

                copyFallback(value) {
                    try {
                        const textarea =
                            document.createElement('textarea');

                        textarea.value = value;
                        textarea.setAttribute('readonly', '');
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        textarea.style.pointerEvents = 'none';

                        document.body.appendChild(textarea);

                        textarea.select();
                        textarea.setSelectionRange(
                            0,
                            textarea.value.length
                        );

                        const success =
                            document.execCommand('copy');

                        textarea.remove();

                        if (success) {
                            this.showSuccess('Copied to clipboard.');
                        }

                        return success;
                    } catch {
                        this.showError(
                            'Clipboard access is unavailable in this browser.'
                        );

                        return false;
                    }
                },

                containsPercentEncoding(value) {
                    return /%[0-9A-Fa-f]{2}/.test(value || '');
                },

                containsMalformedPercentEncoding(value) {
                    const text = value || '';

                    let index = 0;

                    while ((index = text.indexOf('%', index)) !== -1) {
                        const sequence = text.slice(index, index + 3);

                        if (!/^%[0-9A-Fa-f]{2}$/.test(sequence)) {
                            return true;
                        }

                        index += 3;
                    }

                    return false;
                },

                looksLikeAbsoluteUrl(value) {
                    try {
                        const url = new URL(String(value).trim());

                        return Boolean(
                            url.protocol &&
                            url.hostname
                        );
                    } catch {
                        return false;
                    }
                },

                safeDecode(value) {
                    if (!value) {
                        return '';
                    }

                    if (this.containsMalformedPercentEncoding(value)) {
                        return value;
                    }

                    try {
                        return decodeURIComponent(
                            this.plusAsSpace
                                ? value.replace(/\+/g, ' ')
                                : value
                        );
                    } catch {
                        return value;
                    }
                },

                get inputBytesExceeded() {
                    return this.inputStats.bytes > this.maxInputBytes;
                },

                get batchSuccessCount() {
                    return this.batchResults.filter(
                        row => row.success
                    ).length;
                },

                get batchErrorCount() {
                    return this.batchResults.filter(
                        row => !row.success
                    ).length;
                },

                getByteLength(value) {
                    if (!value) {
                        return 0;
                    }

                    try {
                        return new Blob([value]).size;
                    } catch {
                        return unescape(
                            encodeURIComponent(value)
                        ).length;
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

                    return `${(value / (1024 * 1024)).toFixed(2)} MB`;
                },

                formatNumber(value) {
                    return new Intl.NumberFormat().format(
                        Number(value) || 0
                    );
                },

                persistDraft() {
                    try {
                        sessionStorage.setItem(
                            'aabitech_url_encoder_draft',
                            JSON.stringify({
                                operation: this.operation,
                                encodingMode: this.encodingMode,
                                contextMode: this.contextMode,
                                autoProcess: this.autoProcess,
                                plusAsSpace: this.plusAsSpace,
                                batchMode: this.batchMode,
                                decodePasses: this.decodePasses,
                                input: this.input,
                                output: this.output,
                            })
                        );
                    } catch {
                        // Storage can be unavailable in privacy-restricted contexts.
                    }
                },

                restoreDraft() {
                    try {
                        const stored =
                            sessionStorage.getItem(
                                'aabitech_url_encoder_draft'
                            );

                        if (!stored) {
                            return;
                        }

                        const draft = JSON.parse(stored);

                        if (typeof draft.operation === 'string') {
                            this.operation = draft.operation;
                        }

                        if (typeof draft.encodingMode === 'string') {
                            this.encodingMode = draft.encodingMode;
                        }

                        if (typeof draft.contextMode === 'string') {
                            this.contextMode = draft.contextMode;
                        }

                        if (typeof draft.autoProcess === 'boolean') {
                            this.autoProcess = draft.autoProcess;
                        }

                        if (typeof draft.plusAsSpace === 'boolean') {
                            this.plusAsSpace = draft.plusAsSpace;
                        }

                        if (typeof draft.batchMode === 'boolean') {
                            this.batchMode = draft.batchMode;
                        }

                        if (draft.decodePasses) {
                            this.decodePasses =
                                Number(draft.decodePasses) || 1;
                        }

                        if (typeof draft.input === 'string') {
                            this.input = draft.input;
                        }

                        if (typeof draft.output === 'string') {
                            this.output = draft.output;
                        }
                    } catch {
                        // Ignore invalid or unavailable session data.
                    }
                },

                showSuccess(message) {
                    this.status = {
                        type: 'success',
                        message,
                    };
                },

                showError(message) {
                    this.status = {
                        type: 'error',
                        message,
                    };
                },

                clearStatus() {
                    this.status = {
                        type: '',
                        message: '',
                    };
                },

                getFriendlyError(error) {
                    if (!error) {
                        return 'An unknown processing error occurred.';
                    }

                    const message =
                        error.message ||
                        String(error);

                    if (
                        message.includes('URI malformed') ||
                        message.includes('malformed')
                    ) {
                        return 'Invalid URL encoding detected. Check that every percent sign (%) is followed by two hexadecimal characters and that UTF-8 sequences are complete.';
                    }

                    if (
                        message.includes('Invalid URL') ||
                        message.includes('URL')
                    ) {
                        return message;
                    }

                    return message;
                },
            };
        };
    </script>
    @endscript
@assets
    <style>
        button,
        [role="button"],
        a,
        summary,
        [data-clickable],
        [data-active-group] {
            cursor: pointer;
        }

        button:disabled,
        [role="button"][aria-disabled="true"],
        [data-clickable][aria-disabled="true"] {
            cursor: not-allowed;
        }

        [data-active-group].is-active {
            border-color: rgb(129 140 248) !important;
            background: rgb(238 242 254) !important;
            color: rgb(67 56 202) !important;
            box-shadow: none !important;
        }

        .compact-tab {
            display: inline-flex;
            height: 30px;
            width: auto;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid transparent;
            background: transparent;
            padding: 0 9px;
            font-size: 11px;
            font-weight: 500;
            color: rgb(71 85 105);
            white-space: nowrap;
            transition:
                background-color 150ms ease,
                border-color 150ms ease,
                color 150ms ease;
            cursor: pointer;
        }

        .compact-tab:hover {
            background: rgb(248 250 252);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @endassets
</div>