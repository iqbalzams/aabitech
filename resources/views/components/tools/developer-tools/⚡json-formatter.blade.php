<?php

use Livewire\Component;

new class extends Component
{
    // JSON processing is intentionally performed entirely in the browser.
    // No JSON content is submitted to Laravel and no JSON content is persisted.
};
?>

<div
    x-data="aabiJsonFormatter()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-3"
>
    <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2.5 shadow-sm sm:px-4">
        <div class="flex flex-wrap items-center gap-1.5" role="tablist" aria-label="JSON operation">
            <button type="button" role="tab" :aria-selected="mode === 'format'" @click="setMode('format')"
                :data-active-group="true" :class="{ 'is-active': mode === 'format' }"
                class="compact-tab">Format</button>
            <button type="button" role="tab" :aria-selected="mode === 'minify'" @click="setMode('minify')"
                :data-active-group="true" :class="{ 'is-active': mode === 'minify' }"
                class="compact-tab">Minify</button>
            <button type="button" role="tab" :aria-selected="mode === 'repair'" @click="setMode('repair')"
                :data-active-group="true" :class="{ 'is-active': mode === 'repair' }"
                class="compact-tab">Repair</button>
            <button type="button" role="tab" :aria-selected="mode === 'tree'" @click="setMode('tree')"
                :data-active-group="true" :class="{ 'is-active': mode === 'tree' }"
                class="compact-tab">Tree</button>
            <button type="button" role="tab" :aria-selected="mode === 'diff'" @click="setMode('diff')"
                :data-active-group="true" :class="{ 'is-active': mode === 'diff' }"
                class="compact-tab">Compare</button>
            <button type="button" role="tab" :aria-selected="mode === 'convert'" @click="setMode('convert')"
                :data-active-group="true" :class="{ 'is-active': mode === 'convert' }"
                class="compact-tab">Convert</button>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">
            <label class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2 text-[11px] font-medium text-slate-600">
                <span>Indent</span>
                <select x-model="indentMode" @change="process(false)"
                    class="h-6 border-0 bg-transparent py-0 pl-1 pr-5 text-[11px] font-semibold text-slate-800 outline-none focus:ring-0">
                    <option value="2">2 spaces</option>
                    <option value="4">4 spaces</option>
                    <option value="tab">Tabs</option>
                </select>
            </label>
            <label class="inline-flex h-8 items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2 text-[11px] font-medium text-slate-600">
                <input type="checkbox" x-model="autoProcess" class="h-3.5 w-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span>Auto</span>
            </label>
            <button type="button" @click="loadExample()" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Example</button>
            <button type="button" @click="clearAll()" class="inline-flex h-8 items-center rounded-md border border-slate-200 bg-white px-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">Clear</button>
        </div>
    </div>

    <div x-show="status.message" x-transition class="flex justify-end" role="status" aria-live="polite">
        <div class="max-w-full rounded-lg border px-3 py-2 text-xs"
            :class="status.type === 'success'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
                : 'border-red-200 bg-red-50 text-red-800'">
            <span class="font-bold" x-text="status.title"></span>
            <span class="mx-1 opacity-50">—</span>
            <span x-text="status.message"></span>
        </div>
    </div>

    <input x-ref="fileInput" type="file" accept=".json,.jsonc,application/json" class="hidden" @change="handleFile($event)">

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="grid lg:grid-cols-2">
            <section class="min-w-0 border-b border-slate-200 lg:border-b-0 lg:border-r">
                <div class="flex h-11 items-center justify-between border-b border-slate-200 bg-slate-50 px-3.5 sm:px-4">
                    <div>
                        <h2 class="text-xs font-bold text-slate-900">JSON Input</h2>
                        <p class="text-[11px] text-slate-500">Paste JSON, JSONC, or upload a file</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="$refs.fileInput.click()" class="inline-flex h-7 items-center rounded-md px-2 text-[11px] font-semibold text-slate-600 hover:bg-slate-200">Upload</button>
                        <button type="button" @click="copyText(input)" :disabled="!input" class="inline-flex h-7 items-center rounded-md px-2 text-[11px] font-semibold text-slate-600 hover:bg-slate-200 disabled:opacity-40">
                            <span x-text="copiedInput ? '✓ Copied' : 'Copy'"></span>
                        </button>
                    </div>
                </div>

                <div class="relative h-[400px] sm:h-[450px]">
                    <textarea
                        x-ref="editor"
                        x-model="input"
                        @input="handleInput()"
                        spellcheck="false"
                        autocomplete="off"
                        autocapitalize="off"
                        class="absolute inset-0 z-10 h-full w-full resize-none border-0 bg-transparent p-4 font-mono text-[13px] leading-6 text-slate-900 caret-slate-900 outline-none focus:ring-0"
                        placeholder='{"name":"AabiTech","tools":["JSON","Base64"]}'
                    ></textarea>
                    {{-- <pre x-show="showHighlight" aria-hidden="true"
                        class="pointer-events-none absolute inset-0 overflow-hidden whitespace-pre-wrap break-words p-4 font-mono text-[13px] leading-6 text-slate-700"
                        x-html="highlightedInput"></pre> --}}
                </div>

                <div class="flex min-h-11 flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-slate-50 px-3.5 py-2.5 sm:px-4">
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-500">
                        <span>Characters: <strong class="text-slate-700" x-text="input.length.toLocaleString()"></strong></span>
                        <span>Bytes: <strong class="text-slate-700" x-text="inputBytes.toLocaleString()"></strong></span>
                        <span x-show="detectedType" x-text="detectedType"></span>
                    </div>
                    <button type="button" @click="process(true)" class="inline-flex h-8 items-center rounded-md bg-slate-950 px-3.5 text-xs font-bold text-white hover:bg-slate-800">Process</button>
                </div>
            </section>

            <section class="min-w-0">
                <div class="flex h-11 items-center justify-between border-b border-slate-800 bg-slate-900 px-3.5 sm:px-4">
                    <div>
                        <h2 class="text-xs font-bold text-white" x-text="outputTitle"></h2>
                        <p class="text-[11px] text-slate-400">Derived result</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="useOutputAsInput()" :disabled="!outputText" class="inline-flex h-7 items-center rounded-md px-2 text-[11px] font-semibold text-slate-300 hover:bg-slate-800 disabled:opacity-40">Use as input</button>
                        <button type="button" @click="copyText(outputText, 'output')" :disabled="!outputText" class="inline-flex h-7 items-center rounded-md px-2 text-[11px] font-semibold text-slate-300 hover:bg-slate-800 disabled:opacity-40">
                            <span x-text="copiedOutput ? '✓ Copied' : 'Copy'"></span>
                        </button>
                    </div>
                </div>

                <div class="h-[400px] overflow-auto bg-slate-950 p-4 sm:h-[450px]">
                    <pre x-show="mode !== 'tree'" class="whitespace-pre-wrap break-words font-mono text-[13px] leading-6 text-slate-100" x-text="outputText || 'Your result will appear here.'"></pre>

                    <div x-show="mode === 'tree'" class="space-y-1 text-[12px]">
                        <template x-if="treeRoot">
                            <div>
                                <div class="mb-3 flex flex-wrap items-center gap-1.5">
                                    <button type="button" @click="expandAll()" class="rounded-md border border-slate-700 px-2 py-1 text-[11px] text-slate-300 hover:bg-slate-800">Expand all</button>
                                    <button type="button" @click="collapseAll()" class="rounded-md border border-slate-700 px-2 py-1 text-[11px] text-slate-300 hover:bg-slate-800">Collapse all</button>
                                </div>
                                <template x-for="node in flatTree" :key="node.id">
                                    <div x-show="node.visible" :style="'padding-left:' + (node.depth * 16) + 'px'" class="flex min-w-max items-center gap-1 py-0.5 font-mono">
                                        <button type="button" @click="toggleNode(node.id)" class="h-5 w-5 text-slate-400" :class="{ 'invisible': !node.expandable }" x-text="node.expanded ? '▾' : '▸'"></button>
                                        <button type="button" @click="selectTreeNode(node)" class="rounded px-1.5 py-0.5 text-left hover:bg-slate-800" :class="selectedPath === node.path ? 'bg-indigo-950 text-indigo-300' : ''">
                                            <span class="text-slate-400" x-text="node.label"></span>
                                            <span class="text-slate-600" x-show="node.expandable">:</span>
                                            <span :class="node.valueClass" x-text="node.valueText"></span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="!treeRoot" class="text-slate-500">Validate JSON to inspect its tree.</p>
                    </div>
                </div>

                <div class="flex min-h-11 flex-wrap items-center justify-between gap-2 border-t border-slate-800 bg-slate-900 px-3.5 py-2.5 sm:px-4">
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-slate-400">
                        <span>Characters: <strong class="text-slate-300" x-text="outputText.length.toLocaleString()"></strong></span>
                        <span>Bytes: <strong class="text-slate-300" x-text="outputBytes.toLocaleString()"></strong></span>
                    </div>
                    <button type="button" @click="downloadOutput()" :disabled="!outputText" class="inline-flex h-8 items-center rounded-md border border-slate-700 bg-slate-800 px-3 text-xs font-semibold text-slate-300 hover:bg-slate-700 disabled:opacity-40">Download</button>
                </div>
            </section>
        </div>
    </div>

    <div x-show="mode === 'repair'" class="rounded-xl border border-amber-200 bg-amber-50 p-3.5">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-xs font-bold text-amber-900">Safe repair preview</h3>
                <p class="mt-0.5 text-[11px] text-amber-800">Only proposed changes are shown. The original input is never overwritten automatically.</p>
            </div>
            <button type="button" @click="applyRepair()" :disabled="!repairPreview || repairChanges.length === 0" class="inline-flex h-8 items-center rounded-md bg-amber-700 px-3 text-xs font-bold text-white hover:bg-amber-800 disabled:opacity-40">Apply proposed repair</button>
        </div>
        <div class="mt-3 space-y-1">
            <template x-for="change in repairChanges" :key="change.id">
                <div class="rounded-md border border-amber-200 bg-white px-2.5 py-2 text-[11px] text-slate-700">
                    <span class="font-bold" x-text="change.type"></span>
                    <span class="mx-1 text-slate-400">•</span>
                    <span x-text="change.description"></span>
                </div>
            </template>
            <p x-show="repairChanges.length === 0" class="text-[11px] text-amber-800">No safe repair proposal was detected.</p>
        </div>
        <pre x-show="repairPreview" class="mt-3 max-h-64 overflow-auto rounded-lg border border-amber-200 bg-white p-3 font-mono text-[11px] leading-5 text-slate-700" x-text="repairPreview"></pre>
    </div>

    <div x-show="mode === 'diff'" class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-xs font-bold text-slate-900">JSON comparison</h3>
                <p class="text-[11px] text-slate-500">Compare parsed JSON structurally or compare source text.</p>
            </div>
            <div class="flex gap-1.5">
                <button type="button" @click="compareMode='structural'; compare()" :class="compareMode === 'structural' ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-600'" class="rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-semibold">Structural</button>
                <button type="button" @click="compareMode='source'; compare()" :class="compareMode === 'source' ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-600'" class="rounded-md border border-slate-200 px-2.5 py-1.5 text-[11px] font-semibold">Source</button>
            </div>
        </div>
        <textarea x-model="compareInput" @input="compare()" class="mt-3 h-32 w-full resize-y rounded-lg border border-slate-200 p-3 font-mono text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" placeholder="Paste the second JSON document here..."></textarea>
        <div class="mt-3 rounded-lg bg-slate-50 p-3 text-xs text-slate-700">
            <p class="font-semibold" x-text="compareResult.summary || 'Enter a second JSON document to compare.'"></p>
            <div class="mt-2 space-y-1">
                <template x-for="item in compareResult.items" :key="item.id">
                    <div class="font-mono text-[11px]" :class="item.type === 'same' ? 'text-slate-500' : item.type === 'removed' ? 'text-red-700' : 'text-emerald-700'">
                        <span x-text="item.prefix"></span> <span x-text="item.text"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div x-show="mode === 'convert'" class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-xs font-bold text-slate-900">Developer conversions</h3>
                <p class="text-[11px] text-slate-500">Convert validated JSON to common data and type formats.</p>
            </div>
            <select x-model="convertType" @change="convert()" class="h-8 rounded-md border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-700 outline-none">
                <option value="yaml">YAML</option>
                <option value="xml">XML</option>
                <option value="csv">CSV</option>
                <option value="typescript">TypeScript</option>
                <option value="json-string">JSON string</option>
                <option value="escape">Escape JSON</option>
                <option value="unescape">Unescape JSON string</option>
            </select>
        </div>
    </div>

    <div x-show="diagnosis.message || stats.totalNodes" class="grid gap-3 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <h3 class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Diagnosis</h3>
            <p class="mt-1 text-sm font-semibold text-slate-900" x-text="diagnosis.message || 'Valid JSON'"></p>
            <p x-show="errorPosition" class="mt-1 text-[11px] text-red-600" x-text="errorPosition"></p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <h3 class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Structure</h3>
            <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-[11px] text-slate-600">
                <span>Depth <strong x-text="stats.depth"></strong></span>
                <span>Objects <strong x-text="stats.objects"></strong></span>
                <span>Arrays <strong x-text="stats.arrays"></strong></span>
                <span>Keys <strong x-text="stats.keys"></strong></span>
                <span>Values <strong x-text="stats.values"></strong></span>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm">
            <h3 class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Tools</h3>
            <div class="mt-1 flex flex-wrap gap-1.5">
                <button type="button" @click="sortKeys('asc')" class="rounded-md border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">Sort A–Z</button>
                <button type="button" @click="sortKeys('desc')" class="rounded-md border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">Sort Z–A</button>
                <button type="button" @click="detectStringified()" class="rounded-md border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-50">Detect stringified</button>
            </div>
        </div>
    </div>

    <div x-show="searchTerm || selectedPath" class="rounded-xl border border-indigo-100 bg-indigo-50 px-3.5 py-2.5 text-[11px] text-indigo-900">
        <div class="flex flex-wrap items-center gap-2">
            <label class="font-semibold">Search</label>
            <input x-model="searchTerm" @input="runSearch()" class="h-7 min-w-[180px] rounded-md border border-indigo-200 bg-white px-2 text-xs outline-none focus:ring-2 focus:ring-indigo-100" placeholder="Keys or values">
            <span x-show="selectedPath" class="font-mono" x-text="'Selected: ' + selectedPath"></span>
            <button type="button" @click="searchTerm=''; runSearch()" class="ml-auto rounded-md px-2 py-1 font-semibold hover:bg-indigo-100">Clear</button>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-[11px] text-slate-500">
        <div class="flex flex-wrap items-center gap-3">
            <span class="font-semibold text-slate-600">Privacy-first</span>
            <span>All processing runs in your browser. JSON is not transmitted or stored by this tool.</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="undo()" :disabled="!canUndo" class="rounded-md border border-slate-200 bg-white px-2 py-1 font-semibold disabled:opacity-40">Undo</button>
            <button type="button" @click="redo()" :disabled="!canRedo" class="rounded-md border border-slate-200 bg-white px-2 py-1 font-semibold disabled:opacity-40">Redo</button>
            <kbd class="hidden rounded border border-slate-200 bg-white px-1.5 py-0.5 sm:inline">Ctrl/Cmd + Enter</kbd>
        </div>
    </div>
</div>

@script
<script>
window.aabiJsonFormatter = function () {
    return {
        mode: 'format',
        input: '',
        outputText: '',
        compareInput: '',
        compareMode: 'structural',
        indentMode: '2',
        autoProcess: false,
        showHighlight: false,
        copiedInput: false,
        copiedOutput: false,
        detectedType: '',
        convertType: 'yaml',
        searchTerm: '',
        selectedPath: '',
        treeRoot: null,
        flatTree: [],
        repairPreview: '',
        repairChanges: [],
        diagnosis: { message: '', type: '' },
        errorPosition: '',
        status: { type: '', title: '', message: '' },
        stats: { depth: 0, objects: 0, arrays: 0, keys: 0, values: 0, totalNodes: 0 },
        compareResult: { summary: '', items: [] },
        inputBytes: 0,
        outputBytes: 0,
        autoTimer: null,
        history: [],
        historyIndex: -1,
        lastSnapshot: '',
        storageKey: 'aabitech.json-formatter.ui.v1',

        init() {
            this.updateStats();
            this.pushHistory();
            this.$watch('input', () => {
                this.updateStats();
                this.detectInput();
                this.highlight();
                if (this.autoProcess) this.scheduleAutoProcess();
            });
            this.$watch('indentMode', () => {
                if (this.autoProcess && this.input.trim()) this.scheduleAutoProcess();
            });
            this.highlight();
        },

        get canUndo() { return this.historyIndex > 0; },
        get canRedo() { return this.historyIndex < this.history.length - 1; },
        get outputTitle() {
            if (this.mode === 'tree') return 'JSON Tree';
            if (this.mode === 'diff') return 'Comparison';
            if (this.mode === 'convert') return this.convertType.toUpperCase();
            if (this.mode === 'repair') return 'Repair Preview';
            return this.mode === 'minify' ? 'Minified JSON' : 'Formatted JSON';
        },

        handleShortcut(event) {
            const key = String(event.key || '').toLowerCase();
            const mod = event.ctrlKey || event.metaKey;
            if (mod && key === 'enter') {
                event.preventDefault();
                this.process(true);
            } else if (mod && key === 'z' && !event.shiftKey) {
                event.preventDefault();
                this.undo();
            } else if ((mod && key === 'y') || (mod && event.shiftKey && key === 'z')) {
                event.preventDefault();
                this.redo();
            } else if (event.key === 'Escape') {
                this.clearStatus();
            }
        },

        setMode(mode) {
            if (!['format', 'minify', 'repair', 'tree', 'diff', 'convert'].includes(mode)) {
                return;
            }

            this.mode = mode;
            this.clearStatus();

            if (mode === 'tree') {
                this.buildTree();
                return;
            }

            if (mode === 'diff') {
                this.compare();
                return;
            }

            if (mode === 'convert') {
                this.convert();
                return;
            }

            if (mode === 'repair') {
                this.buildRepair();
                return;
            }

            // Format and Minify are processing modes. Switching between them
            // immediately refreshes the result using the existing input.
            if (this.input.trim()) {
                this.process(true);
            } else {
                this.outputText = '';
                this.updateStats();
            }
        },

        handleInput() {
            this.updateStats();
            this.detectInput();
            this.highlight();
            if (this.autoProcess) this.scheduleAutoProcess();
            else this.clearStatus();
            this.pushHistory();
        },

        scheduleAutoProcess() {
            clearTimeout(this.autoTimer);
            this.autoTimer = setTimeout(() => this.process(false), 350);
        },

        process(showStatus = true) {
            this.clearStatus();
            const source = this.input;
            if (!source.trim()) {
                this.outputText = '';
                this.treeRoot = null;
                this.flatTree = [];
                this.diagnosis = { message: 'Enter JSON to begin.', type: 'info' };
                return false;
            }

            if (this.mode === 'repair') return this.buildRepair(showStatus);
            if (this.mode === 'diff') return this.compare();
            if (this.mode === 'convert') return this.convert();
            if (this.mode === 'tree') return this.buildTree(showStatus);

            const parsed = this.parseJson(source);
            if (!parsed.ok) {
                this.outputText = '';
                this.treeRoot = null;
                this.flatTree = [];
                this.diagnosis = { message: parsed.message, type: 'error' };
                this.errorPosition = parsed.position;
                this.showError('Invalid JSON', parsed.message + (parsed.position ? ' ' + parsed.position + '.' : '.'));
                return false;
            }

            this.diagnosis = { message: 'Valid JSON', type: 'success' };
            this.errorPosition = '';
            this.detectDuplicateKeys(source);

            if (this.mode === 'minify') {
                this.outputText = JSON.stringify(parsed.value);
            } else {
                this.outputText = JSON.stringify(parsed.value, null, this.indentMode === 'tab' ? '\t' : Number(this.indentMode));
            }

            this.updateStatsFromValue(parsed.value);
            this.outputBytes = this.getByteLength(this.outputText);
            if (showStatus) this.showSuccess('JSON processed', this.mode === 'minify' ? 'Whitespace was removed safely.' : 'JSON was formatted successfully.');
            if (this.mode === 'tree') this.buildTree();
            return true;
        },

        parseJson(source) {
            try {
                return { ok: true, value: JSON.parse(source) };
            } catch (error) {
                const message = String(error && error.message ? error.message : 'Invalid JSON syntax.');
                const positionMatch = message.match(/position\s+(\d+)/i);
                let position = positionMatch ? Number(positionMatch[1]) : null;
                if (position === null) {
                    const tokenMatch = message.match(/line\s+(\d+)\s+column\s+(\d+)/i);
                    if (tokenMatch) return { ok: false, message: this.cleanParseMessage(message), position: `Line ${tokenMatch[1]}, column ${tokenMatch[2]}` };
                }
                if (position !== null) {
                    const loc = this.positionFromOffset(source, position);
                    return { ok: false, message: this.cleanParseMessage(message), position: `Line ${loc.line}, column ${loc.column}` };
                }
                return { ok: false, message: this.cleanParseMessage(message), position: '' };
            }
        },

        cleanParseMessage(message) {
            return message.replace(/\s+at position\s+\d+/i, '').replace(/\s+at line\s+\d+\s+column\s+\d+/i, '').trim();
        },

        positionFromOffset(source, offset) {
            const safe = Math.max(0, Math.min(offset, source.length));
            const before = source.slice(0, safe);
            const lines = before.split('\n');
            return { line: lines.length, column: lines[lines.length - 1].length + 1 };
        },

        detectInput() {
            const source = this.input.trim();
            if (!source) {
                this.detectedType = '';
                return;
            }
            if (this.looksLikeJsonc(source)) this.detectedType = 'JSONC detected';
            else if (this.isStringifiedJson(source)) this.detectedType = 'Stringified JSON detected';
            else if (/^\s*[\[{]/.test(source)) this.detectedType = 'JSON candidate';
            else this.detectedType = 'JSON candidate / invalid';
        },

        looksLikeJsonc(source) {
            return /(^|[^:\\])\/\/|\/\*[\s\S]*?\*\//.test(source);
        },

        isStringifiedJson(source) {
            if (!(source.startsWith('"') && source.endsWith('"'))) return false;
            try {
                const unwrapped = JSON.parse(source);
                if (typeof unwrapped !== 'string') return false;
                const parsed = JSON.parse(unwrapped);
                return parsed !== null && typeof parsed === 'object';
            } catch {
                return false;
            }
        },

        detectStringified() {
            if (!this.isStringifiedJson(this.input.trim())) {
                this.showError('Not stringified JSON', 'The input does not appear to contain a JSON document inside a JSON string.');
                return;
            }
            const value = JSON.parse(this.input);
            this.recordInputChange(value);
            this.showSuccess('Stringified JSON parsed', 'The embedded JSON is now available as normal JSON.');
        },

        buildRepair(showStatus = true) {
            const source = this.input;
            if (!source.trim()) {
                this.repairPreview = '';
                this.repairChanges = [];
                this.outputText = '';
                return false;
            }
            let repaired = source;
            const changes = [];
            const add = (type, description, next) => {
                if (next !== repaired) {
                    repaired = next;
                    changes.push({ id: changes.length + 1, type, description });
                }
            };

            add('Comments', 'Removed JavaScript-style // and /* */ comments outside strings.', this.removeJsonComments(repaired));
            add('Trailing commas', 'Removed commas immediately before closing ] or }.', repaired.replace(/,\s*([}\]])/g, '$1'));
            add('Unquoted keys', 'Quoted simple object keys such as name: and converted them to "name":.', repaired.replace(/([{\s,])([A-Za-z_$][\w$-]*)\s*:/g, '$1"$2":'));
            add('Single quotes', 'Converted simple single-quoted strings to JSON double-quoted strings.', this.convertSimpleSingleQuotes(repaired));

            const parsed = this.parseJson(repaired);
            this.repairPreview = repaired;
            this.repairChanges = changes;
            this.outputText = repaired;
            if (parsed.ok) {
                this.diagnosis = { message: 'Repair preview is valid JSON', type: 'success' };
                if (showStatus) this.showSuccess('Repair preview ready', changes.length ? `${changes.length} safe proposal${changes.length === 1 ? '' : 's'} detected.` : 'No repair was required.');
            } else {
                this.diagnosis = { message: 'Repair preview still needs review', type: 'error' };
                if (showStatus) this.showError('Repair incomplete', 'The proposed changes do not yet produce valid JSON. Review the preview before applying.');
            }
            return parsed.ok;
        },

        removeJsonComments(source) {
            let out = '';
            let inString = false;
            let escaped = false;
            for (let i = 0; i < source.length; i++) {
                const c = source[i], n = source[i + 1];
                if (inString) {
                    out += c;
                    if (escaped) escaped = false;
                    else if (c === '\\') escaped = true;
                    else if (c === '"') inString = false;
                    continue;
                }
                if (c === '"') { inString = true; out += c; continue; }
                if (c === '/' && n === '/') {
                    while (i < source.length && source[i] !== '\n') i++;
                    out += '\n';
                    continue;
                }
                if (c === '/' && n === '*') {
                    i += 2;
                    while (i < source.length && !(source[i] === '*' && source[i + 1] === '/')) i++;
                    i++;
                    continue;
                }
                out += c;
            }
            return out;
        },

        convertSimpleSingleQuotes(source) {
            return source.replace(/'([^'\\]*(?:\\.[^'\\]*)*)'/g, (match, body) => {
                const converted = body.replace(/\\"/g, '"').replace(/"/g, '\\"').replace(/\\'/g, "'");
                return '"' + converted + '"';
            });
        },

        applyRepair() {
            if (!this.repairPreview || !this.repairChanges.length) return;
            if (!this.parseJson(this.repairPreview).ok) {
                this.showError('Repair not applied', 'The preview is not valid JSON, so the original input was preserved.');
                return;
            }
            this.recordInputChange(this.repairPreview);
            this.outputText = JSON.stringify(JSON.parse(this.repairPreview), null, this.indentMode === 'tab' ? '\t' : Number(this.indentMode));
            this.showSuccess('Repair applied', 'The validated repair has replaced the input. The previous version remains available through Undo.');
        },

        sortKeys(direction = 'asc') {
            const parsed = this.parseJson(this.input);
            if (!parsed.ok) {
                this.showError('Cannot sort JSON', 'Fix the JSON syntax before sorting keys.');
                return;
            }
            const sorted = this.sortValue(parsed.value, direction);
            this.recordInputChange(JSON.stringify(sorted, null, this.indentMode === 'tab' ? '\t' : Number(this.indentMode)));
            this.outputText = JSON.stringify(sorted, null, this.indentMode === 'tab' ? '\t' : Number(this.indentMode));
            this.showSuccess('Keys sorted', direction === 'asc' ? 'Object keys sorted ascending.' : 'Object keys sorted descending.');
        },

        sortValue(value, direction) {
            if (Array.isArray(value)) return value.map(item => this.sortValue(item, direction));
            if (value && typeof value === 'object') {
                const out = {};
                Object.keys(value).sort((a, b) => direction === 'asc' ? a.localeCompare(b) : b.localeCompare(a))
                    .forEach(key => out[key] = this.sortValue(value[key], direction));
                return out;
            }
            return value;
        },

        buildTree(showStatus = true) {
            const parsed = this.parseJson(this.input);
            if (!parsed.ok) {
                this.treeRoot = null;
                this.flatTree = [];
                this.diagnosis = { message: parsed.message, type: 'error' };
                this.errorPosition = parsed.position;
                if (showStatus) this.showError('Invalid JSON', 'Fix the syntax before opening the tree.');
                return false;
            }
            this.treeRoot = this.makeTreeNode('$', parsed.value, '', 0, true);
            this.rebuildFlatTree();
            this.updateStatsFromValue(parsed.value);
            if (showStatus) this.showSuccess('Tree ready', 'Click any node to inspect its JSON path.');
            return true;
        },

        makeTreeNode(label, value, path, depth, expanded = true) {
            const isObject = value !== null && typeof value === 'object' && !Array.isArray(value);
            const isArray = Array.isArray(value);
            const expandable = isObject || isArray;
            const node = {
                id: 'node-' + Math.random().toString(36).slice(2),
                label,
                path: path || '$',
                depth,
                expanded,
                expandable,
                value,
                children: [],
                valueText: expandable ? (isArray ? `[${value.length}]` : `{${Object.keys(value).length}}`) : this.formatTreeValue(value),
                valueClass: this.valueClass(value),
            };
            if (expandable) {
                const entries = isArray ? value.map((v, i) => [String(i), v]) : Object.entries(value);
                node.children = entries.map(([key, child]) => {
                    const childPath = isArray ? `${path || '$'}[${key}]` : `${path || '$'}.${key}`;
                    return this.makeTreeNode(key, child, childPath, depth + 1, depth < 1);
                });
            }
            return node;
        },

        formatTreeValue(value) {
            if (value === null) return 'null';
            if (typeof value === 'string') return '"' + (value.length > 120 ? value.slice(0, 117) + '...' : value) + '"';
            if (typeof value === 'boolean') return String(value);
            return String(value);
        },

        valueClass(value) {
            if (value === null) return 'text-slate-500';
            if (typeof value === 'string') return 'text-emerald-400';
            if (typeof value === 'number') return 'amber-400';
            if (typeof value === 'boolean') return 'sky-400';
            return 'slate-300';
        },

        rebuildFlatTree() {
            const result = [];
            const walk = (node, parentVisible = true) => {
                node.visible = parentVisible;
                result.push(node);
                if (node.expandable) node.children.forEach(child => walk(child, parentVisible && node.expanded));
            };
            if (this.treeRoot) walk(this.treeRoot);
            this.flatTree = result;
        },

        toggleNode(id) {
            const node = this.flatTree.find(item => item.id === id);
            if (!node || !node.expandable) return;
            node.expanded = !node.expanded;
            this.rebuildFlatTree();
        },

        expandAll() {
            this.flatTree.forEach(node => { if (node.expandable) node.expanded = true; });
            this.rebuildFlatTree();
        },

        collapseAll() {
            this.flatTree.forEach(node => { if (node.expandable) node.expanded = false; });
            if (this.treeRoot) this.treeRoot.expanded = true;
            this.rebuildFlatTree();
        },

        selectTreeNode(node) {
            this.selectedPath = node.path;
            this.searchTerm = '';
            this.showSuccess('JSON path', node.path);
        },

        runSearch() {
            if (!this.treeRoot) return;
            const term = this.searchTerm.trim().toLowerCase();
            if (!term) {
                this.rebuildFlatTree();
                return;
            }
            const matches = this.flatTree.filter(node =>
                String(node.label).toLowerCase().includes(term) ||
                String(node.valueText).toLowerCase().includes(term) ||
                String(node.path).toLowerCase().includes(term)
            ).map(node => node.id);
            const result = [];
            const walk = node => {
                const direct = matches.includes(node.id);
                let descendant = false;
                node.children.forEach(child => { if (walk(child)) descendant = true; });
                node.visible = direct || descendant;
                if (node.visible) result.push(node);
                return node.visible;
            };
            walk(this.treeRoot);
            this.flatTree = result;
        },

        detectDuplicateKeys(source) {
            const duplicates = [];
            const objectRegex = /{([\s\S]*?)}/g;
            let match;
            while ((match = objectRegex.exec(source))) {
                const keys = [...match[1].matchAll(/"([^"\\]*(?:\\.[^"\\]*)*)"\s*:/g)].map(m => m[1]);
                const seen = new Set();
                keys.forEach(key => {
                    if (seen.has(key) && !duplicates.includes(key)) duplicates.push(key);
                    seen.add(key);
                });
            }
            if (duplicates.length) {
                this.showError('Duplicate-key warning', `Duplicate object keys detected: ${duplicates.slice(0, 5).join(', ')}${duplicates.length > 5 ? '…' : ''}. JSON.parse keeps the last occurrence.`);
            }
        },

        convert() {
            const parsed = this.parseJson(this.input);
            if (!parsed.ok) {
                this.outputText = '';
                this.showError('Cannot convert', 'Fix the JSON syntax before converting.');
                return false;
            }
            let result = '';
            switch (this.convertType) {
                case 'yaml': result = this.toYaml(parsed.value); break;
                case 'xml': result = this.toXml(parsed.value, 'root'); break;
                case 'csv': result = this.toCsv(parsed.value); break;
                case 'typescript': result = this.toTypeScript(parsed.value, 'Root'); break;
                case 'json-string': result = JSON.stringify(JSON.stringify(parsed.value)); break;
                case 'escape': result = JSON.stringify(this.input); break;
                case 'unescape':
                    try {
                        const unwrapped = JSON.parse(this.input);
                        result = typeof unwrapped === 'string' ? unwrapped : JSON.stringify(unwrapped, null, 2);
                    } catch { result = 'Input is not a valid JSON-encoded string.'; }
                    break;
            }
            this.outputText = result;
            this.outputBytes = this.getByteLength(result);
            return true;
        },

        yamlScalar(value) {
            if (value === null) return 'null';
            if (typeof value === 'string') {
                if (!value || /[:#\-\[\]{},&*!|>'"%@`]/.test(value) || /^\s|\s$/.test(value)) return JSON.stringify(value);
                return value;
            }
            return String(value);
        },

        toYaml(value, depth = 0) {
            const pad = '  '.repeat(depth);
            if (Array.isArray(value)) {
                if (!value.length) return '[]';
                return value.map(item => {
                    if (item && typeof item === 'object') return pad + '-\n' + this.toYaml(item, depth + 1).split('\n').map((line, i) => i === 0 ? '  ' + line : '  ' + line).join('\n');
                    return pad + '- ' + this.yamlScalar(item);
                }).join('\n');
            }
            if (value && typeof value === 'object') {
                const keys = Object.keys(value);
                if (!keys.length) return '{}';
                return keys.map(key => {
                    const child = value[key];
                    if (child && typeof child === 'object') {
                        return pad + JSON.stringify(key) + ':\n' + this.toYaml(child, depth + 1);
                    }
                    return pad + JSON.stringify(key) + ': ' + this.yamlScalar(child);
                }).join('\n');
            }
            return pad + this.yamlScalar(value);
        },

        xmlEscape(value) {
            return String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        },

        toXml(value, tag) {
            const safeTag = String(tag).replace(/[^A-Za-z0-9_.-]/g, '_') || 'item';
            if (Array.isArray(value)) return value.map(item => this.toXml(item, 'item')).join('');
            if (value && typeof value === 'object') {
                return `<${safeTag}>${Object.entries(value).map(([k, v]) => this.toXml(v, k)).join('')}</${safeTag}>`;
            }
            return `<${safeTag}>${this.xmlEscape(value === null ? '' : value)}</${safeTag}>`;
        },

        toCsv(value) {
            const rows = Array.isArray(value) ? value : [value];
            if (!rows.length || rows.some(row => !row || typeof row !== 'object' || Array.isArray(row))) {
                return 'CSV conversion requires an object or an array of objects.';
            }
            const headers = [...new Set(rows.flatMap(row => Object.keys(row)))];
            const esc = v => {
                const text = v === null || v === undefined ? '' : typeof v === 'object' ? JSON.stringify(v) : String(v);
                return /[",\n]/.test(text) ? '"' + text.replace(/"/g, '""') + '"' : text;
            };
            return [headers.map(esc).join(','), ...rows.map(row => headers.map(h => esc(row[h])).join(','))].join('\n');
        },

        toTypeScript(value, name) {
            if (Array.isArray(value)) {
                return `type ${name} = ${value.length ? this.inferTs(value[0], name + 'Item') : 'unknown'}[];`;
            }
            return `interface ${name} ${this.inferTsObject(value, 0)}`;
        },

        inferTs(value, name) {
            if (value === null) return 'unknown';
            if (Array.isArray(value)) return `Array<${value.length ? this.inferTs(value[0], name + 'Item') : 'unknown'}>`;
            if (typeof value === 'object') return this.inferTsObject(value, 0);
            if (typeof value === 'string') return 'string';
            if (typeof value === 'number') return 'number';
            if (typeof value === 'boolean') return 'boolean';
            return 'unknown';
        },

        inferTsObject(value, depth) {
            if (!value || typeof value !== 'object' || Array.isArray(value)) return '{ [key: string]: unknown }';
            const pad = '  '.repeat(depth);
            const next = '  '.repeat(depth + 1);
            const lines = Object.entries(value).map(([key, val]) => {
                const safe = /^[A-Za-z_$][\w$]*$/.test(key) ? key : JSON.stringify(key);
                return `${next}${safe}: ${this.inferTs(val, key)};`;
            });
            return `{\n${lines.join('\n')}\n${pad}}`;
        },

        compare() {
            if (!this.compareInput.trim()) {
                this.compareResult = { summary: '', items: [] };
                return;
            }
            if (this.compareMode === 'source') {
                const a = this.input.split('\n'), b = this.compareInput.split('\n');
                const max = Math.max(a.length, b.length), items = [];
                for (let i = 0; i < max; i++) {
                    if (a[i] === b[i]) items.push({ id: i, type: 'same', prefix: ' ', text: a[i] || '' });
                    else {
                        if (a[i] !== undefined) items.push({ id: 'a' + i, type: 'removed', prefix: '-', text: a[i] });
                        if (b[i] !== undefined) items.push({ id: 'b' + i, type: 'added', prefix: '+', text: b[i] });
                    }
                }
                this.compareResult = { summary: a.join('\n') === b.join('\n') ? 'Source documents are identical.' : 'Source differences detected.', items: items.slice(0, 500) };
                return;
            }
            const a = this.parseJson(this.input), b = this.parseJson(this.compareInput);
            if (!a.ok || !b.ok) {
                this.compareResult = { summary: 'Both documents must be valid JSON for structural comparison.', items: [] };
                return;
            }
            const items = [];
            this.diffValues(a.value, b.value, '$', items);
            this.compareResult = {
                summary: items.length ? `${items.length} structural difference${items.length === 1 ? '' : 's'} detected.` : 'The parsed JSON structures are equivalent.',
                items: items.slice(0, 500)
            };
        },

        diffValues(a, b, path, items) {
            if (Object.is(a, b)) return;
            if (typeof a !== typeof b || Array.isArray(a) !== Array.isArray(b) || (a && b && typeof a === 'object' && typeof b !== 'object')) {
                items.push({ id: items.length, type: 'changed', prefix: '~', text: `${path}: ${this.formatTreeValue(a)} → ${this.formatTreeValue(b)}` });
                return;
            }
            if (Array.isArray(a)) {
                const max = Math.max(a.length, b.length);
                for (let i = 0; i < max; i++) {
                    if (i >= a.length) items.push({ id: items.length, type: 'added', prefix: '+', text: `${path}[${i}]: ${this.formatTreeValue(b[i])}` });
                    else if (i >= b.length) items.push({ id: items.length, type: 'removed', prefix: '-', text: `${path}[${i}]: ${this.formatTreeValue(a[i])}` });
                    else this.diffValues(a[i], b[i], `${path}[${i}]`, items);
                }
                return;
            }
            if (a && typeof a === 'object') {
                const keys = new Set([...Object.keys(a), ...Object.keys(b)]);
                keys.forEach(key => {
                    const p = `${path}.${key}`;
                    if (!(key in a)) items.push({ id: items.length, type: 'added', prefix: '+', text: `${p}: ${this.formatTreeValue(b[key])}` });
                    else if (!(key in b)) items.push({ id: items.length, type: 'removed', prefix: '-', text: `${p}: ${this.formatTreeValue(a[key])}` });
                    else this.diffValues(a[key], b[key], p, items);
                });
                return;
            }
            items.push({ id: items.length, type: 'changed', prefix: '~', text: `${path}: ${this.formatTreeValue(a)} → ${this.formatTreeValue(b)}` });
        },

        highlight() {
            // The textarea remains the authoritative editable layer.
            // Highlighting is intentionally disabled while editing to avoid
            // cursor/scroll desynchronization and expensive DOM work on large JSON.
            this.showHighlight = false;
        },

        useOutputAsInput() {
            if (!this.outputText) return;
            this.recordInputChange(this.outputText);
            this.showSuccess('Output moved to input', 'The original input was replaced; use Undo to restore it.');
        },

        loadExample() {
            this.recordInputChange(JSON.stringify({
                name: 'AabiTech',
                active: true,
                tools: ['JSON Formatter', 'Base64 Encoder', 'Unix Timestamp'],
                config: { theme: 'light', version: 1, limits: { maxSize: '10MB' } }
            }, null, 2));
            this.process(true);
        },

        clearAll() {
            this.input = '';
            this.outputText = '';
            this.compareInput = '';
            this.treeRoot = null;
            this.flatTree = [];
            this.repairPreview = '';
            this.repairChanges = [];
            this.searchTerm = '';
            this.selectedPath = '';
            this.diagnosis = { message: '', type: '' };
            this.errorPosition = '';
            this.clearStatus();
            this.updateStats();
            this.history = [];
            this.historyIndex = -1;
            this.pushHistory();
            this.$nextTick(() => this.$refs.editor && this.$refs.editor.focus());
        },

        updateStats() {
            this.inputBytes = this.getByteLength(this.input);
            this.outputBytes = this.getByteLength(this.outputText);
        },

        updateStatsFromValue(value) {
            const stats = { depth: 0, objects: 0, arrays: 0, keys: 0, values: 0, totalNodes: 0 };
            const walk = (node, depth) => {
                stats.depth = Math.max(stats.depth, depth);
                stats.totalNodes++;
                if (Array.isArray(node)) {
                    stats.arrays++;
                    node.forEach(item => walk(item, depth + 1));
                } else if (node && typeof node === 'object') {
                    stats.objects++;
                    const keys = Object.keys(node);
                    stats.keys += keys.length;
                    keys.forEach(key => walk(node[key], depth + 1));
                } else {
                    stats.values++;
                }
            };
            walk(value, 0);
            this.stats = stats;
        },

        getByteLength(value) {
            return value ? new TextEncoder().encode(value).length : 0;
        },

        getIndent() {
            return this.indentMode === 'tab' ? '\t' : Number(this.indentMode);
        },

        recordInputChange(value) {
            this.input = value;
            this.pushHistory(true);
            this.updateStats();
            this.detectInput();
        },

        pushHistory(force = false) {
            const value = this.input;
            if (!force && value === this.lastSnapshot) return;
            if (this.historyIndex < this.history.length - 1) this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(value);
            if (this.history.length > 100) this.history.shift();
            this.historyIndex = this.history.length - 1;
            this.lastSnapshot = value;
        },

        undo() {
            if (!this.canUndo) return;
            this.historyIndex--;
            this.input = this.history[this.historyIndex];
            this.lastSnapshot = this.input;
            this.process(false);
        },

        redo() {
            if (!this.canRedo) return;
            this.historyIndex++;
            this.input = this.history[this.historyIndex];
            this.lastSnapshot = this.input;
            this.process(false);
        },

        async copyText(value, target = 'input') {
            if (!value) return;
            try {
                await navigator.clipboard.writeText(value);
            } catch {
                const textarea = document.createElement('textarea');
                textarea.value = value;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                textarea.remove();
            }
            if (target === 'output') {
                this.copiedOutput = true;
                setTimeout(() => this.copiedOutput = false, 1600);
            } else {
                this.copiedInput = true;
                setTimeout(() => this.copiedInput = false, 1600);
            }
            this.showSuccess('Copied', 'The JSON text was copied to your clipboard.');
        },

        downloadOutput() {
            if (!this.outputText) return;
            const blob = new Blob([this.outputText], { type: 'application/json;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = this.mode === 'minify' ? 'aabitech-minified.json' : 'aabitech-json-output.json';
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        },

        async handleFile(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;
            if (file.size > 25 * 1024 * 1024) {
                this.showError('File too large', 'JSON files are limited to 25 MB for responsive browser processing.');
                return;
            }
            try {
                this.recordInputChange(await file.text());
                this.process(true);
                this.showSuccess('File loaded', `${file.name} was loaded locally.`);
            } catch {
                this.showError('File error', 'The JSON file could not be read.');
            } finally {
                event.target.value = '';
            }
        },

        showSuccess(title, message) {
            this.status = { type: 'success', title, message };
        },

        showError(title, message) {
            this.status = { type: 'error', title, message };
        },

        clearStatus() {
            this.status = { type: '', title: '', message: '' };
        }
    };
};
</script>
@endscript

<style>
    [x-cloak] { display: none !important; }
    .compact-tab {
        display: inline-flex;
        height: 30px;
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
        transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        cursor: pointer;
    }
    [data-active-group].is-active {
        border-color: rgb(129 140 248) !important;
        background: rgb(238 242 254) !important;
        color: rgb(67 56 202) !important;
        box-shadow: none !important;
    }
    button:disabled { cursor: not-allowed; }
</style>
