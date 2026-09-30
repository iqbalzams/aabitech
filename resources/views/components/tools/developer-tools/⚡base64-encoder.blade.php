<?php

use Livewire\Component;

new class extends Component
{
    // Base64 processing is intentionally performed entirely in the browser.
};
?>

<div
    x-data="aabiBase64Encoder()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-3"
>
    <style>
        .base64-tool [data-active-group].is-active {
            border-color: rgb(129 140 248) !important;
            background: rgb(238 242 254) !important;
            color: rgb(67 56 202) !important;
            box-shadow: none !important;
        }
        .base64-tool .compact-tab {
            display:inline-flex;height:30px;align-items:center;justify-content:center;
            border:1px solid transparent;border-radius:6px;padding:0 9px;font-size:11px;
            font-weight:600;white-space:nowrap;transition:all 150ms ease;
        }
        .base64-tool button,[data-clickable]{cursor:pointer}
        .base64-tool button:disabled{cursor:not-allowed}
    </style>

    {{-- Interactive workspace only. Page title, SEO, FAQ and related content are rendered globally. --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 sm:px-4">
            <div class="inline-flex items-center rounded-lg border border-slate-200 bg-white p-0.5" role="tablist" aria-label="Base64 mode">
                <button type="button" role="tab" :aria-selected="mode === 'encode'" @click="setMode('encode')" :data-active-group="mode === 'encode'" :class="mode === 'encode' ? 'is-active' : ''" class="compact-tab">Encode</button>
                <button type="button" role="tab" :aria-selected="mode === 'decode'" @click="setMode('decode')" :data-active-group="mode === 'decode'" :class="mode === 'decode' ? 'is-active' : ''" class="compact-tab">Decode</button>
            </div>

            <div class="flex flex-wrap items-center gap-1.5">
                <label class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600">
                    <input type="checkbox" x-model="autoProcess" class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Auto process
                </label>
                <button type="button" @click="loadExample()" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Example</button>
                <button type="button" @click="clearAll()" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Clear</button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-3.5 py-2.5 sm:px-4">
            <div x-show="mode === 'encode'" class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 p-0.5">
                <button type="button" @click="setInputType('text')" :data-active-group="inputType === 'text'" :class="inputType === 'text' ? 'is-active' : ''" class="compact-tab">Text</button>
                <button type="button" @click="setInputType('file')" :data-active-group="inputType === 'file'" :class="inputType === 'file' ? 'is-active' : ''" class="compact-tab">File</button>
            </div>
            <div x-show="mode === 'decode'" class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 p-0.5">
                <button type="button" @click="decodeType='text';clearStatus()" :data-active-group="decodeType === 'text'" :class="decodeType === 'text' ? 'is-active' : ''" class="compact-tab">UTF-8 Text</button>
                <button type="button" @click="decodeType='binary';clearStatus()" :data-active-group="decodeType === 'binary'" :class="decodeType === 'binary' ? 'is-active' : ''" class="compact-tab">Binary</button>
                <button type="button" @click="decodeType='auto';clearStatus()" :data-active-group="decodeType === 'auto'" :class="decodeType === 'auto' ? 'is-active' : ''" class="compact-tab">Auto</button>
            </div>
            <label class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600">
                <input type="checkbox" x-model="urlSafe" class="h-3.5 w-3.5 rounded text-indigo-600 focus:ring-indigo-500"> URL-safe
            </label>
            <label x-show="mode === 'encode'" class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600">
                <input type="checkbox" x-model="keepPadding" class="h-3.5 w-3.5 rounded text-indigo-600 focus:ring-indigo-500"> Padding
            </label>
            <label x-show="mode === 'encode'" class="inline-flex h-8 cursor-pointer items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600">
                <input type="checkbox" x-model="dataUri" class="h-3.5 w-3.5 rounded text-indigo-600 focus:ring-indigo-500"> Data URI
            </label>
            <button type="button" @click="showAdvanced=!showAdvanced" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                <span x-text="showAdvanced ? 'Hide options' : 'Advanced'"></span>
            </button>
            <button type="button" @click="showSmart=!showSmart" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Smart tools</button>
        </div>

        <div x-show="showAdvanced" x-collapse class="grid gap-3 border-b border-slate-200 bg-slate-50/70 p-3 sm:grid-cols-2 lg:grid-cols-4">
            <label class="text-xs font-semibold text-slate-600">Line wrap
                <select x-model.number="wrapAt" class="mt-1 block h-8 w-full rounded-md border-slate-200 bg-white text-xs focus:border-indigo-400 focus:ring-indigo-400">
                    <option value="0">No wrapping</option><option value="64">64 characters</option><option value="76">76 characters (MIME)</option><option value="80">80 characters</option><option value="120">120 characters</option>
                </select>
            </label>
            <label class="text-xs font-semibold text-slate-600">Character encoding
                <select x-model="encoding" class="mt-1 block h-8 w-full rounded-md border-slate-200 bg-white text-xs focus:border-indigo-400 focus:ring-indigo-400">
                    <option value="UTF-8">UTF-8</option><option value="UTF-16LE">UTF-16LE</option><option value="UTF-16BE">UTF-16BE</option><option value="ASCII">ASCII</option><option value="auto">Auto detect text/binary</option>
                </select>
            </label>
            <label class="text-xs font-semibold text-slate-600">Output format
                <select x-model="outputFormat" class="mt-1 block h-8 w-full rounded-md border-slate-200 bg-white text-xs focus:border-indigo-400 focus:ring-indigo-400">
                    <option value="raw">Raw Base64</option><option value="json">JSON string</option><option value="html">HTML</option><option value="css">CSS url()</option>
                </select>
            </label>
            <label class="text-xs font-semibold text-slate-600">Batch mode
                <select x-model="batchMode" class="mt-1 block h-8 w-full rounded-md border-slate-200 bg-white text-xs focus:border-indigo-400 focus:ring-indigo-400">
                    <option value="off">Single value</option><option value="lines">One item per line</option>
                </select>
            </label>
        </div>

        <div x-show="showSmart" x-collapse class="grid gap-2 border-b border-slate-200 bg-white p-3 sm:grid-cols-2 lg:grid-cols-4">
            <button type="button" @click="detectInput()" class="rounded-lg border border-slate-200 px-3 py-2 text-left hover:bg-slate-50">
                <span class="block text-xs font-bold text-slate-800">Smart detect</span>
                <span class="text-[11px] text-slate-500" x-text="detection.label"></span>
            </button>
            <button type="button" @click="decodeJwt()" class="rounded-lg border border-slate-200 px-3 py-2 text-left hover:bg-slate-50">
                <span class="block text-xs font-bold text-slate-800">JWT decoder</span>
                <span class="text-[11px] text-slate-500">Decode header and payload JSON</span>
            </button>
            <button type="button" @click="convertUrlSafe()" class="rounded-lg border border-slate-200 px-3 py-2 text-left hover:bg-slate-50">
                <span class="block text-xs font-bold text-slate-800">Base64 ↔ Base64URL</span>
                <span class="text-[11px] text-slate-500">Normalize URL-safe alphabet</span>
            </button>
            <button type="button" @click="verifyRoundTrip()" class="rounded-lg border border-slate-200 px-3 py-2 text-left hover:bg-slate-50">
                <span class="block text-xs font-bold text-slate-800">Round-trip verify</span>
                <span class="text-[11px] text-slate-500">Encode → decode → compare</span>
            </button>
        </div>

        <div class="grid lg:grid-cols-2">
            <section class="min-w-0 border-b border-slate-200 lg:border-b-0 lg:border-r">
                <div class="flex h-11 items-center justify-between border-b border-slate-200 bg-slate-50 px-3.5 sm:px-4">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900" x-text="inputHeading"></h3>
                        <p class="text-[11px] text-slate-500" x-text="inputDescription"></p>
                    </div>
                    <button type="button" @click="copyText(input,'Input copied')" :disabled="!input" class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 disabled:opacity-40">Copy</button>
                </div>

                <div x-show="!(mode === 'encode' && inputType === 'file')">
                    <textarea x-ref="input" x-model="input" @input="handleInput()" @keydown.ctrl.enter.prevent="process()" @keydown.meta.enter.prevent="process()" spellcheck="false" autocapitalize="off" autocomplete="off"
                        class="block h-[360px] w-full resize-none border-0 p-4 font-mono text-[14px] leading-6 text-slate-900 outline-none focus:ring-0 sm:h-[390px] lg:h-[420px]"
                        :placeholder="mode === 'encode' ? 'Enter text, Base64, Data URI or JWT...' : 'Paste Base64 here...'"></textarea>
                </div>

                <div x-show="mode === 'encode' && inputType === 'file'" class="h-[360px] sm:h-[390px] lg:h-[420px]">
                    <div class="flex h-full cursor-pointer items-center justify-center p-5" @click="$refs.fileInput.click()" @dragover.prevent="dragging=true" @dragleave.prevent="dragging=false" @drop.prevent="handleFileDrop($event)">
                        <div class="flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed p-8 text-center transition" :class="dragging ? 'border-indigo-400 bg-indigo-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300'">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0-4 4m4-4 4 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 14v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4"/></svg>
                            </div>
                            <p class="mt-4 text-[15px] font-bold text-slate-800" x-text="selectedFile ? selectedFile.name : 'Choose a file or drag it here'"></p>
                            <p class="mt-1 text-xs text-slate-500" x-text="selectedFile ? formatBytes(selectedFile.size) + ' · ' + (selectedFile.type || 'application/octet-stream') : 'Images, PDFs and binary files up to 10 MB'"></p>
                            <button type="button" @click.stop="$refs.fileInput.click()" class="mt-4 inline-flex h-8 items-center rounded-md bg-slate-950 px-3.5 text-xs font-bold text-white">Choose File</button>
                            <p class="mt-3 text-[10px] text-slate-400">Maximum file size: 10 MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex min-h-11 flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-slate-50 px-3.5 py-2.5 sm:px-4">
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-500">
                        <span>Characters: <strong class="text-slate-700" x-text="input.length.toLocaleString()"></strong></span>
                        <span>Bytes: <strong class="text-slate-700" x-text="inputBytes.toLocaleString()"></strong></span>
                        <span x-show="selectedFile" x-text="selectedFile ? selectedFile.type || 'binary' : ''"></span>
                    </div>
                    <button type="button" @click="process()" :disabled="!canProcess" class="inline-flex h-8 items-center rounded-md bg-slate-950 px-3.5 text-xs font-bold text-white hover:bg-slate-800 disabled:opacity-40" x-text="mode === 'encode' ? 'Encode' : 'Decode'"></button>
                </div>
            </section>

            <section class="min-w-0">
                <div class="flex h-11 items-center justify-between border-b border-slate-800 bg-slate-900 px-3.5 sm:px-4">
                    <div>
                        <h3 class="text-xs font-bold text-white" x-text="outputHeading"></h3>
                        <p class="text-[11px] text-slate-400">Processed result</p>
                    </div>
                    <button type="button" @click="copyText(output,'Output copied')" :disabled="!output" class="text-[11px] font-semibold text-slate-300 hover:text-white disabled:opacity-40"><span x-text="copied ? '✓ Copied' : 'Copy'"></span></button>
                </div>
                <div class="relative">
                    <textarea x-ref="output" x-model="output" readonly spellcheck="false" class="block h-[360px] w-full resize-none border-0 bg-slate-950 p-4 font-mono text-[14px] leading-6 text-slate-100 outline-none focus:ring-0 sm:h-[390px] lg:h-[420px]" :placeholder="decodeType === 'binary' && mode === 'decode' ? 'Binary decoded successfully — use Download or Preview.' : 'Your result will appear here...'"></textarea>
                    <div x-show="output" class="pointer-events-none absolute bottom-3 right-3 rounded-md border border-slate-700 bg-slate-900/95 px-2 py-1 text-[10px] text-slate-400" x-text="formatBytes(outputBytes)"></div>
                </div>
                <div class="flex min-h-11 flex-wrap items-center justify-between gap-2 border-t border-slate-800 bg-slate-900 px-3.5 py-2.5 sm:px-4">
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-400">
                        <span>Characters: <strong class="text-slate-300" x-text="output.length.toLocaleString()"></strong></span>
                        <span>Bytes: <strong class="text-slate-300" x-text="outputBytes.toLocaleString()"></strong></span>
                        <span x-show="compressionRatio" x-text="compressionRatio"></span>
                    </div>
                    <div class="flex gap-1.5">
                        <button type="button" @click="previewDecoded()" x-show="decodedBlob && decodedMime.startsWith('image/')" class="inline-flex h-8 items-center rounded-md border border-slate-700 bg-slate-800 px-3 text-xs font-semibold text-slate-300 hover:bg-slate-700">Preview</button>
                        <button type="button" @click="downloadOutput()" :disabled="!hasOutput" class="inline-flex h-8 items-center rounded-md border border-slate-700 bg-slate-800 px-3 text-xs font-semibold text-slate-300 hover:bg-slate-700 disabled:opacity-40">Download</button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div x-show="status.message" x-transition class="flex justify-end" role="status" aria-live="polite">
        <div class="max-w-full rounded-lg border px-3 py-2 text-xs" :class="status.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800'">
            <strong x-text="status.title"></strong><span class="mx-1 opacity-50">—</span><span x-text="status.message"></span>
        </div>
    </div>

    <input x-ref="fileInput" type="file" class="hidden" accept="*/*" @change="handleFileSelect($event)">
    <input x-ref="batchFileInput" type="file" class="hidden" accept=".txt,.csv,text/plain,text/csv" @change="handleBatchFile($event)">

    <div class="grid gap-3 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-3.5">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800">Size calculator</h3>
                <span class="text-[11px] text-slate-500" x-text="sizeInfo.encoded"></span>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 text-center">
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-[10px] text-slate-500">Original</div><div class="text-sm font-bold" x-text="formatBytes(sizeInfo.original)"></div></div>
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-[10px] text-slate-500">Encoded</div><div class="text-sm font-bold" x-text="formatBytes(sizeInfo.encodedBytes)"></div></div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-3.5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800">Developer snippets</h3>
                <select x-model="snippetLanguage" class="h-7 rounded-md border-slate-200 text-[11px]">
                    <option>JavaScript</option><option>PHP</option><option>Python</option><option>Java</option><option>Go</option><option>C#</option><option>cURL</option>
                </select>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <pre class="min-w-0 flex-1 overflow-auto rounded-lg bg-slate-950 p-3 text-[11px] leading-5 text-slate-200" x-text="developerSnippet"></pre>
                <button type="button" @click="copyText(developerSnippet,'Snippet copied')" class="shrink-0 rounded-md border border-slate-200 px-2.5 py-2 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">Copy</button>
            </div>
        </div>
    </div>

    <div x-show="batchMode !== 'off'" class="rounded-xl border border-slate-200 bg-white p-3.5">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div><h3 class="text-xs font-bold text-slate-800">Batch results</h3><p class="text-[11px] text-slate-500">One item per line; each row is validated independently.</p></div>
            <div class="flex gap-1.5">
                <button type="button" @click="processBatch()" class="h-8 rounded-md bg-slate-950 px-3 text-xs font-bold text-white">Process batch</button>
                <button type="button" @click="openBatchImport()" class="h-8 rounded-md border border-slate-200 px-3 text-xs font-semibold">Import TXT/CSV</button>
                <button type="button" @click="exportBatch('txt')" :disabled="!batchResults.length" class="h-8 rounded-md border border-slate-200 px-3 text-xs font-semibold disabled:opacity-40">TXT</button>
                <button type="button" @click="exportBatch('csv')" :disabled="!batchResults.length" class="h-8 rounded-md border border-slate-200 px-3 text-xs font-semibold disabled:opacity-40">CSV</button>
            </div>
        </div>
        <div class="mt-3 overflow-auto rounded-lg border border-slate-200">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-2">#</th><th class="px-3 py-2">Status</th><th class="px-3 py-2">Input</th><th class="px-3 py-2">Output</th></tr></thead>
                <tbody><template x-for="(row,index) in batchResults" :key="index"><tr class="border-t border-slate-100"><td class="px-3 py-2" x-text="index+1"></td><td class="px-3 py-2 font-semibold" :class="row.ok ? 'text-emerald-700' : 'text-red-700'" x-text="row.ok ? 'OK' : 'Error'"></td><td class="max-w-xs truncate px-3 py-2 font-mono" x-text="row.input"></td><td class="max-w-xs truncate px-3 py-2 font-mono" x-text="row.output || row.error"></td></tr></template></tbody>
            </table>
        </div>
    </div>

    <div x-show="jwtResult" class="rounded-xl border border-slate-200 bg-white p-3.5">
        <div class="flex items-center justify-between"><h3 class="text-xs font-bold text-slate-800">JWT viewer</h3><button type="button" @click="jwtResult=null" class="text-[11px] text-slate-500">Close</button></div>
        <div class="mt-3 grid gap-3 lg:grid-cols-2">
            <div><div class="mb-1 text-[11px] font-semibold text-slate-500">Header</div><pre class="max-h-64 overflow-auto rounded-lg bg-slate-950 p-3 text-xs text-slate-200" x-text="jwtResult ? JSON.stringify(jwtResult.header,null,2) : ''"></pre></div>
            <div><div class="mb-1 text-[11px] font-semibold text-slate-500">Payload</div><pre class="max-h-64 overflow-auto rounded-lg bg-slate-950 p-3 text-xs text-slate-200" x-text="jwtResult ? JSON.stringify(jwtResult.payload,null,2) : ''"></pre></div>
        </div>
    </div>

    <div x-show="binaryPreviewUrl" class="rounded-xl border border-slate-200 bg-white p-3.5">
        <div class="flex items-center justify-between"><h3 class="text-xs font-bold text-slate-800">Image preview</h3><button type="button" @click="closePreview()" class="text-[11px] text-slate-500">Close</button></div>
        <img :src="binaryPreviewUrl" alt="Decoded image preview" class="mt-3 max-h-80 max-w-full rounded-lg border border-slate-200 object-contain">
    </div>

    <div x-show="roundTrip" class="rounded-xl border border-slate-200 bg-slate-50 p-3.5 text-xs">
        <strong x-text="roundTrip && roundTrip.ok ? 'Round-trip verified' : 'Round-trip failed'" :class="roundTrip && roundTrip.ok ? 'text-emerald-700' : 'text-red-700'"></strong>
        <span class="ml-2 text-slate-600" x-text="roundTrip ? roundTrip.message : ''"></span>
    </div>

    <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-[11px] text-slate-500 sm:px-4">
        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 2.9 8.5 7 10 4.1-1.5 7-5.5 7-10V6l-7-3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
        <span>Runs entirely in your browser. Your data is not sent to AabiTech and no input is persisted by this component.</span>
    </div>
</div>

@script
<script>
window.aabiBase64Encoder = function () {
    return {
        mode: 'encode',
        inputType: 'text',
        decodeType: 'text',
        input: '',
        output: '',
        urlSafe: false,
        keepPadding: true,
        dataUri: false,
        autoProcess: false,
        showAdvanced: false,
        showSmart: false,
        encoding: 'UTF-8',
        wrapAt: 0,
        outputFormat: 'raw',
        batchMode: 'off',
        batchResults: [],
        snippetLanguage: 'JavaScript',
        selectedFile: null,
        decodedBlob: null,
        decodedMime: 'application/octet-stream',
        binaryPreviewUrl: '',
        copied: false,
        dragging: false,
        inputBytes: 0,
        outputBytes: 0,
        detection: {type: 'Unknown', label: 'No input detected'},
        jwtResult: null,
        roundTrip: null,
        status: {type: '', title: '', message: ''},
        autoTimer: null,
        copyTimer: null,
        maxFileSize: 10 * 1024 * 1024,

        init() {
            this.updateStats();
            this.$watch('input', () => {
                this.updateStats();
                this.detectInput();
                if (!this.input) { this.output = ''; this.decodedBlob = null; }
                if (this.autoProcess && this.input) this.scheduleAutoProcess();
            });
            ['urlSafe','keepPadding','dataUri','encoding','wrapAt','outputFormat','batchMode'].forEach((key) => {
                this.$watch(key, () => { if (this.autoProcess && this.input) this.scheduleAutoProcess(); });
            });
            this.$watch('mode', () => { this.output=''; this.jwtResult=null; this.roundTrip=null; this.clearStatus(); });
        },

        destroy() {
            clearTimeout(this.autoTimer);
            clearTimeout(this.copyTimer);
            this.closePreview();
        },

        get inputHeading() {
            return this.mode === 'decode' ? 'Base64 Input' : (this.inputType === 'file' ? 'File Input' : 'Text Input');
        },
        get inputDescription() {
            return this.mode === 'decode' ? 'Base64, Base64URL, Data URI or JWT' : (this.inputType === 'file' ? 'Choose a file to encode' : 'UTF-8 or selected text encoding');
        },
        get outputHeading() {
            return this.mode === 'encode' ? 'Base64 Output' : (this.decodeType === 'binary' ? 'Decoded Binary' : 'Decoded Text');
        },
        get canProcess() {
            return this.mode === 'encode' && this.inputType === 'file' ? !!this.selectedFile : !!this.input.trim();
        },
        get hasOutput() { return !!this.output || !!this.decodedBlob; },
        get compressionRatio() {
            if (!this.inputBytes || !this.outputBytes) return '';
            return `Size: ${((this.outputBytes / this.inputBytes) * 100).toFixed(1)}%`;
        },
        get sizeInfo() {
            const original = this.selectedFile ? this.selectedFile.size : this.inputBytes;
            let encodedBytes = 0;
            if (original) encodedBytes = Math.ceil(original / 3) * 4;
            return { original, encodedBytes, encoded: encodedBytes ? `${this.formatBytes(encodedBytes)} encoded` : '—' };
        },
        get developerSnippet() {
            const v = this.input || 'Hello, AabiTech!';
            const b = this.base64Literal(v);
            const snippets = {
                JavaScript: `const encoded = btoa(unescape(encodeURIComponent(${JSON.stringify(v)})));`,
                PHP: `$encoded = base64_encode(${JSON.stringify(v)});`,
                Python: `import base64\nencoded = base64.b64encode(${JSON.stringify(v)}.encode()).decode()`,
                Java: `String encoded = Base64.getEncoder().encodeToString(${JSON.stringify(v)}.getBytes(StandardCharsets.UTF_8));`,
                Go: `encoded := base64.StdEncoding.EncodeToString([]byte(${JSON.stringify(v)}))`,
                'C#': `string encoded = Convert.ToBase64String(Encoding.UTF8.GetBytes(${JSON.stringify(v)}));`,
                'cURL': `printf '%s' ${JSON.stringify(v)} | base64`
            };
            return snippets[this.snippetLanguage] || b;
        },

        handleShortcut(event) {
            if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                event.preventDefault(); this.process();
            }
            if (event.key === 'Escape') this.clearStatus();
        },

        setMode(mode) {
            if (!['encode','decode'].includes(mode)) return;
            this.mode = mode; this.output=''; this.decodedBlob=null; this.jwtResult=null; this.roundTrip=null; this.clearStatus();
        },
        setInputType(type) {
            this.inputType = type;
            if (type === 'text') { this.selectedFile = null; this.$refs.fileInput.value=''; }
            this.output=''; this.clearStatus();
        },
        handleInput() { this.updateStats(); },

        scheduleAutoProcess() {
            clearTimeout(this.autoTimer);
            this.autoTimer = setTimeout(() => this.process(), 300);
        },

        process() {
            this.clearStatus();
            if (!this.canProcess) {
                this.showError('Nothing to process', this.mode === 'encode' ? 'Enter text or choose a file first.' : 'Enter a Base64 value first.');
                return;
            }
            try {
                if (this.batchMode !== 'off' && this.inputType === 'text') return this.processBatch();
                if (this.mode === 'encode') return this.inputType === 'file' ? this.encodeFile() : this.encodeText();
                return this.decodeBase64();
            } catch (error) {
                this.output=''; this.decodedBlob=null;
                this.showError(this.mode === 'encode' ? 'Encoding failed' : 'Decoding failed', this.getFriendlyError(error));
            }
        },

        encodeText() {
            const bytes = this.encodeTextBytes(this.input);
            let encoded = this.bytesToBase64(bytes);
            if (this.urlSafe) encoded = this.toUrlSafe(encoded);
            if (!this.keepPadding) encoded = encoded.replace(/=+$/,'');
            if (this.dataUri) encoded = `data:text/plain;charset=utf-8;base64,${encoded}`;
            encoded = this.applyWrap(this.applyOutputFormat(encoded));
            this.output=encoded; this.outputBytes=this.getByteLength(encoded);
            this.showSuccess('Encoding complete', 'Text has been converted to Base64.');
        },

        encodeTextBytes(value) {
            if (this.encoding === 'UTF-8') return new TextEncoder().encode(value);
            if (this.encoding === 'ASCII') {
                for (let i=0;i<value.length;i++) if (value.charCodeAt(i)>127) throw new Error(`ASCII encoding does not support character at position ${i+1}.`);
                return Uint8Array.from(value, c => c.charCodeAt(0));
            }
            const little = this.encoding === 'UTF-16LE';
            const bytes = new Uint8Array(value.length * 2);
            for (let i=0;i<value.length;i++) {
                const code=value.charCodeAt(i);
                if (little) { bytes[i*2]=code&255; bytes[i*2+1]=code>>8; }
                else { bytes[i*2]=code>>8; bytes[i*2+1]=code&255; }
            }
            return bytes;
        },

        encodeFile() {
            if (!this.selectedFile) throw new Error('Please choose a file first.');
            if (this.selectedFile.size > this.maxFileSize) throw new Error('The selected file exceeds the 10 MB limit.');
            this.selectedFile.arrayBuffer().then(buffer => {
                let encoded=this.bytesToBase64(new Uint8Array(buffer));
                if (this.urlSafe) encoded=this.toUrlSafe(encoded);
                if (!this.keepPadding) encoded=encoded.replace(/=+$/,'');
                if (this.dataUri) encoded=`data:${this.selectedFile.type || 'application/octet-stream'};base64,${encoded}`;
                this.output=this.applyWrap(encoded);
                this.outputBytes=this.getByteLength(this.output);
                this.showSuccess('Encoding complete', `${this.selectedFile.name} was converted to Base64.`);
            }).catch(() => this.showError('Encoding failed','The selected file could not be read.'));
        },

        bytesToBase64(bytes) {
            let binary='', chunk=0x8000;
            for (let i=0;i<bytes.length;i+=chunk) binary += String.fromCharCode(...bytes.subarray(i,i+chunk));
            return btoa(binary);
        },

        parseBase64(value) {
            let normalized=value.trim(), mime=null, dataUri=false;
            if (!normalized) throw new Error('Please enter a Base64 value.');
            if (normalized.startsWith('data:')) {
                const comma=normalized.indexOf(',');
                if (comma<0) throw new Error('Data URI is incomplete: missing comma at position '+(normalized.length+1)+'.');
                const meta=normalized.slice(5,comma);
                if (!/;base64(?:;|$)/i.test(meta)) throw new Error('Data URI is not Base64 encoded.');
                mime=meta.split(';')[0] || 'application/octet-stream'; normalized=normalized.slice(comma+1); dataUri=true;
            }
            normalized=normalized.replace(/\s+/g,'');
            const firstUrl = normalized.includes('-') || normalized.includes('_');
            normalized=normalized.replace(/-/g,'+').replace(/_/g,'/');
            for (let i=0;i<normalized.length;i++) {
                if (!/[A-Za-z0-9+/=]/.test(normalized[i])) throw new Error(`Invalid Base64 character "${normalized[i]}" at position ${i+1}.`);
            }
            const firstPadding=normalized.indexOf('=');
            if (firstPadding>=0 && !/^=+$/.test(normalized.slice(firstPadding))) throw new Error(`Invalid padding near position ${firstPadding+1}.`);
            if (firstPadding>=0 && normalized.length-firstPadding>2) throw new Error(`Too much Base64 padding starting at position ${firstPadding+1}.`);
            const core=firstPadding>=0 ? normalized.slice(0,firstPadding) : normalized;
            if (core.length % 4 === 1) throw new Error(`Invalid Base64 length at position ${core.length}.`);
            const rem=normalized.length%4;
            if (rem===1) throw new Error(`Invalid Base64 length: ${normalized.length} characters.`);
            if (rem>0) normalized += '='.repeat(4-rem);
            let binary;
            try { binary=atob(normalized); } catch { throw new Error('The value could not be decoded as valid Base64.'); }
            const bytes=new Uint8Array(binary.length);
            for (let i=0;i<binary.length;i++) bytes[i]=binary.charCodeAt(i);
            return {bytes,mime,dataUri,urlSafe:firstUrl};
        },

        decodeBase64() {
            const result=this.parseBase64(this.input);
            this.decodedMime=result.mime || this.detectMime(result.bytes) || 'application/octet-stream';
            const detectedText=this.isProbablyText(result.bytes);
            if (this.decodeType==='binary' || (this.decodeType==='auto' && !detectedText)) {
                this.decodedBlob=new Blob([result.bytes],{type:this.decodedMime});
                this.output=`[Binary data: ${this.formatBytes(result.bytes.length)} · ${this.decodedMime}]`;
                this.outputBytes=result.bytes.length;
                this.showSuccess('Decoding complete',`Binary data detected as ${this.decodedMime} and is ready to download.`);
                return;
            }
            const decoded=this.decodeBytes(result.bytes);
            this.output=decoded; this.decodedBlob=null; this.outputBytes=this.getByteLength(decoded);
            this.showSuccess('Decoding complete',`Decoded ${result.urlSafe ? 'Base64URL' : 'Base64'} successfully.`);
        },

        decodeBytes(bytes) {
            if (this.encoding==='auto') {
                if (!this.isProbablyText(bytes)) throw new Error('Decoded data appears to be binary. Use Auto/Binary output or download the binary data.');
                return new TextDecoder('utf-8',{fatal:true}).decode(bytes);
            }
            if (this.encoding==='UTF-8') {
                try { return new TextDecoder('utf-8',{fatal:true}).decode(bytes); }
                catch { throw new Error('Decoded data is not valid UTF-8. Try Binary mode or another character encoding.'); }
            }
            if (this.encoding==='ASCII') {
                for (let i=0;i<bytes.length;i++) if (bytes[i]>127) throw new Error(`Decoded data contains a non-ASCII byte at position ${i+1}.`);
                return new TextDecoder('ascii').decode(bytes);
            }
            if (bytes.length%2) throw new Error('UTF-16 data must contain an even number of bytes.');
            const little=this.encoding==='UTF-16LE', chars=[];
            for (let i=0;i<bytes.length;i+=2) {
                const code=little ? bytes[i]|(bytes[i+1]<<8) : (bytes[i]<<8)|bytes[i+1];
                chars.push(String.fromCharCode(code));
            }
            return chars.join('');
        },

        toUrlSafe(value) { return value.replace(/\+/g,'-').replace(/\//g,'_'); },
        convertUrlSafe() {
            if (!this.input.trim()) return this.showError('Nothing to convert','Enter Base64 or Base64URL first.');
            try {
                let raw=this.input.trim(), prefix='';
                if (raw.startsWith('data:')) { const i=raw.indexOf(','); if(i<0) throw new Error('Invalid Data URI.'); prefix=raw.slice(0,i+1); raw=raw.slice(i+1); }
                const parsed=this.parseBase64(raw);
                let out=this.toUrlSafe(this.bytesToBase64(parsed.bytes));
                if (this.keepPadding) out += '='.repeat((4-out.length%4)%4);
                else out=out.replace(/=+$/,'');
                this.output=this.applyWrap(prefix+out); this.showSuccess('Conversion complete','Normalized to URL-safe Base64.');
            } catch(e) { this.showError('Conversion failed',this.getFriendlyError(e)); }
        },

        detectInput() {
            const v=this.input.trim();
            if (!v) return this.detection={type:'Unknown',label:'No input detected'};
            if (this.isJwt(v)) return this.detection={type:'JWT',label:'JWT detected — header.payload.signature'};
            if (v.startsWith('data:')) return this.detection={type:'Data URI',label:'Data URI detected'};
            if (/^[A-Za-z0-9+/_-]+={0,2}$/.test(v.replace(/\s+/g,'')) && v.replace(/\s+/g,'').length%4!==1) {
                const url=/[-_]/.test(v), standard=/[+/]/.test(v);
                return this.detection={type:url&&!standard?'Base64URL':'Base64',label:url&&!standard?'Base64URL detected':'Base64-like input detected'};
            }
            return this.detection={type:'Plain text',label:'Plain text detected'};
        },

        isJwt(value) {
            const parts=value.split('.');
            return parts.length===3 && parts.every(p=>p.length>0 && /^[A-Za-z0-9_-]+$/.test(p));
        },

        decodeJwt() {
            const token=this.input.trim();
            if (!this.isJwt(token)) return this.showError('JWT not detected','Enter a compact JWT with three Base64URL segments.');
            try {
                const p=token.split('.');
                const header=JSON.parse(this.decodeBytes(this.parseBase64(p[0]).bytes));
                const payload=JSON.parse(this.decodeBytes(this.parseBase64(p[1]).bytes));
                this.jwtResult={header,payload,signature:p[2]};
                this.showSuccess('JWT decoded','Header and payload were parsed locally. The signature was not verified.');
            } catch(e) { this.showError('JWT decode failed',this.getFriendlyError(e)); }
        },

        verifyRoundTrip() {
            if (!this.input) return this.showError('Nothing to verify','Enter text or Base64 first.');
            try {
                if (this.mode==='encode') {
                    const bytes=this.encodeTextBytes(this.input), b64=this.bytesToBase64(bytes), back=this.decodeBytes(this.parseBase64(b64).bytes);
                    this.roundTrip={ok:back===this.input,message:'Original text and decoded text match exactly.'};
                } else {
                    const parsed=this.parseBase64(this.input), b64=this.bytesToBase64(parsed.bytes), back=this.parseBase64(b64).bytes;
                    this.roundTrip={ok:this.bytesEqual(parsed.bytes,back),message:'Decoded bytes re-encoded and compared exactly.'};
                }
                this.showSuccess('Round-trip checked',this.roundTrip.message);
            } catch(e) { this.roundTrip={ok:false,message:this.getFriendlyError(e)}; this.showError('Round-trip failed',this.roundTrip.message); }
        },

        bytesEqual(a,b) { return a.length===b.length && a.every((v,i)=>v===b[i]); },

        processBatch() {
            const rows=this.input.split(/\r?\n/);
            this.batchResults=rows.map((value,index)=>{
                if (!value.trim()) return {ok:false,input:value,output:'',error:'Empty line'};
                try {
                    if (this.mode==='encode') {
                        let out=this.bytesToBase64(this.encodeTextBytes(value));
                        if(this.urlSafe) out=this.toUrlSafe(out);
                        if(!this.keepPadding) out=out.replace(/=+$/,'');
                        return {ok:true,input:value,output:out,error:''};
                    }
                    const out=this.decodeBytes(this.parseBase64(value).bytes);
                    return {ok:true,input:value,output:out,error:''};
                } catch(e) { return {ok:false,input:value,output:'',error:this.getFriendlyError(e)}; }
            });
            this.output=this.batchResults.map(r=>r.ok?r.output:`ERROR: ${r.error}`).join('\n');
            this.outputBytes=this.getByteLength(this.output);
            const ok=this.batchResults.filter(r=>r.ok).length;
            this.showSuccess('Batch complete',`${ok} of ${rows.length} rows processed successfully.`);
        },

        exportBatch(format) {
            if (!this.batchResults.length) return;
            let body, mime, name;
            if(format==='csv') {
                const esc=v=>`"${String(v).replace(/"/g,'""')}"`;
                body='Index,Status,Input,Output,Error\n'+this.batchResults.map((r,i)=>[i+1,r.ok?'OK':'Error',r.input,r.output,r.error].map(esc).join(',')).join('\n');
                mime='text/csv;charset=utf-8'; name='aabitech-base64-batch.csv';
            } else {
                body=this.batchResults.map(r=>r.ok?r.output:`ERROR: ${r.error}`).join('\n');
                mime='text/plain;charset=utf-8'; name='aabitech-base64-batch.txt';
            }
            this.downloadBlob(new Blob([body],{type:mime}),name);
        },

        applyWrap(value) {
            if(!this.wrapAt) return value;
            const parts=value.split(/\r?\n/), wrap=s=>s.replace(new RegExp(`(.{${this.wrapAt}})`,'g'),'$1\n').replace(/\n$/,'');
            return parts.map(wrap).join('\n');
        },

        applyOutputFormat(value) {
            let formatted=value;
            if (this.outputFormat==='json') return JSON.stringify(value);
            if (this.outputFormat==='html') {
                const src=value.startsWith('data:') ? value : `data:text/plain;charset=utf-8;base64,${value.replace(/\s+/g,'')}`;
                return `<img src="${src}" alt="">`;
            }
            if (this.outputFormat==='css') {
                const src=value.startsWith('data:') ? value : `data:application/octet-stream;base64,${value.replace(/\s+/g,'')}`;
                return `url("${src}")`;
            }
            return formatted;
        },

        base64Literal(v) { return v; },

        handleFileSelect(event) { const file=event.target.files?.[0]; if(file) this.setSelectedFile(file); },
        handleFileDrop(event) { this.dragging=false; const file=event.dataTransfer.files?.[0]; if(file) this.setSelectedFile(file); },
        setSelectedFile(file) {
            if(file.size>this.maxFileSize) return this.showError('File is too large','Please choose a file no larger than 10 MB.');
            this.selectedFile=file; this.output=''; this.clearStatus();
            if(this.autoProcess) this.process();
        },

        swap() {
            if(!this.output || this.mode==='encode'&&this.inputType==='file') return;
            const old=this.output; this.input=old; this.mode=this.mode==='encode'?'decode':'encode'; this.inputType='text'; this.output=''; this.$nextTick(()=>this.process());
        },

        loadExample() {
            this.selectedFile=null; this.inputType='text'; this.dataUri=false; this.keepPadding=true; this.outputFormat='raw';
            this.input=this.mode==='encode' ? 'Hello, AabiTech! 👋\n\nBase64 supports UTF-8 and binary-safe data.' : 'SGVsbG8sIEFhYmlUZWNoISDwn5GLCgpiYXNlNjQgc3VwcG9ydHMgVVRGLTggYW5kIGJpbmFyeS1zYWZlIGRhdGEu';
            this.$nextTick(()=>this.process());
        },

        clearAll() {
            clearTimeout(this.autoTimer); this.input=''; this.output=''; this.selectedFile=null; this.decodedBlob=null; this.batchResults=[]; this.jwtResult=null; this.roundTrip=null; this.clearStatus();
            if(this.$refs.fileInput) this.$refs.fileInput.value='';
            this.$nextTick(()=>this.$refs.input?.focus());
        },

        async copyText(value, message='Copied') {
            if(!value) return;
            try { await navigator.clipboard.writeText(value); }
            catch { this.fallbackCopy(value); }
            this.copied=true; this.showSuccess(message,'The text has been copied to your clipboard.');
            clearTimeout(this.copyTimer); this.copyTimer=setTimeout(()=>this.copied=false,1600);
        },

        fallbackCopy(value) {
            const ta=document.createElement('textarea'); ta.value=value; ta.style.position='fixed'; ta.style.opacity='0'; document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); } finally { ta.remove(); }
        },

        downloadOutput() {
            if(!this.hasOutput) return;
            if(this.decodedBlob) {
                const ext=this.extensionFromMime(this.decodedMime), name=`aabitech-decoded${ext}`;
                return this.downloadBlob(this.decodedBlob,name);
            }
            const ext=this.mode==='encode'?'.txt':'.txt';
            this.downloadBlob(new Blob([this.output],{type:'text/plain;charset=utf-8'}),`aabitech-base64${ext}`);
        },

        downloadBlob(blob,name) {
            const url=URL.createObjectURL(blob), link=document.createElement('a');
            link.href=url; link.download=name; document.body.appendChild(link); link.click(); link.remove();
            setTimeout(()=>URL.revokeObjectURL(url),1000);
        },

        previewDecoded() {
            if(!this.decodedBlob || !this.decodedMime.startsWith('image/')) return;
            this.closePreview(); this.binaryPreviewUrl=URL.createObjectURL(this.decodedBlob);
        },
        closePreview() { if(this.binaryPreviewUrl){URL.revokeObjectURL(this.binaryPreviewUrl);this.binaryPreviewUrl='';} },

        isProbablyText(bytes) {
            if (!bytes.length) return true;
            let control=0;
            for (let i=0;i<bytes.length;i++) {
                const b=bytes[i];
                if (b===0) return false;
                if ((b<7 || (b>14 && b<32)) && b!==9 && b!==10 && b!==13) control++;
            }
            if (control / bytes.length > 0.02) return false;
            try { new TextDecoder('utf-8',{fatal:true}).decode(bytes); return true; } catch { return false; }
        },

        detectMime(bytes) {
            if (bytes.length>=8 && bytes[0]===0x89 && bytes[1]===0x50 && bytes[2]===0x4e && bytes[3]===0x47) return 'image/png';
            if (bytes.length>=3 && bytes[0]===0xff && bytes[1]===0xd8 && bytes[2]===0xff) return 'image/jpeg';
            if (bytes.length>=6 && [0x47,0x49,0x46,0x38,0x39,0x61].every((v,i)=>bytes[i]===v)) return 'image/gif';
            if (bytes.length>=4 && bytes[0]===0x25 && bytes[1]===0x50 && bytes[2]===0x44 && bytes[3]===0x46) return 'application/pdf';
            if (bytes.length>=4 && bytes[0]===0x50 && bytes[1]===0x4b && bytes[2]===0x03 && bytes[3]===0x04) return 'application/zip';
            if (bytes.length>=4 && bytes[0]===0x52 && bytes[1]===0x49 && bytes[2]===0x46 && bytes[3]===0x46) return 'image/webp';
            return this.isProbablyText(bytes) ? 'text/plain;charset=utf-8' : 'application/octet-stream';
        },

        openBatchImport() { this.$refs.batchFileInput.click(); },
        handleBatchFile(event) {
            const file=event.target.files?.[0];
            if (!file) return;
            if (file.size > this.maxFileSize) return this.showError('File is too large','Batch import is limited to 10 MB.');
            file.text().then(text => {
                const lines=file.name.toLowerCase().endsWith('.csv') ? this.parseCsvLines(text) : text.split(/\r?\n/);
                this.input=lines.filter(v=>String(v).length).join('\n');
                this.batchMode='lines';
                this.showSuccess('Batch imported',`${lines.length} item${lines.length===1?'':'s'} loaded from ${file.name}.`);
            }).catch(()=>this.showError('Import failed','The TXT/CSV file could not be read.'));
        },

        parseCsvLines(text) {
            const rows=[];
            for (const line of text.split(/\r?\n/)) {
                if (!line.trim()) continue;
                const first=line.split(',')[0].trim().replace(/^"(.*)"$/,'$1').replace(/""/g,'"');
                rows.push(first);
            }
            return rows;
        },

                extensionFromMime(mime) {
            const map={'image/png':'.png','image/jpeg':'.jpg','image/gif':'.gif','image/webp':'.webp','image/svg+xml':'.svg','application/pdf':'.pdf','application/zip':'.zip','application/json':'.json','text/plain':'.txt','text/html':'.html','text/css':'.css','text/javascript':'.js','application/javascript':'.js'};
            return map[mime]||'.bin';
        },

        getByteLength(value) { return value ? new TextEncoder().encode(value).length : 0; },
        updateStats() { this.inputBytes=this.getByteLength(this.input); this.outputBytes=this.decodedBlob ? this.decodedBlob.size : this.getByteLength(this.output); },
        formatBytes(bytes) {
            if(!Number.isFinite(bytes)||bytes<=0) return '0 B';
            if(bytes<1024) return `${bytes} B`;
            if(bytes<1048576) return `${(bytes/1024).toFixed(1)} KB`;
            return `${(bytes/1048576).toFixed(2)} MB`;
        },
        showSuccess(title,message){this.status={type:'success',title,message};},
        showError(title,message){this.status={type:'error',title,message};},
        clearStatus(){this.status={type:'',title:'',message:''};},
        getFriendlyError(error){return error?.message||'The value could not be processed.';}
    };
};
</script>
@endscript
