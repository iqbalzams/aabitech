<?php

use Livewire\Component;

new class extends Component
{
    // Regex processing is performed entirely in the browser.
    // Pattern and test data are never submitted to Laravel.
};

?>

<div
    x-data="aabiRegexTester()"
    x-init="init()"
    x-cloak
    class="w-full space-y-4"
    @keydown.window="handleGlobalShortcut($event)"
>
    {{-- Main workspace --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        {{-- Compact toolbar --}}
        <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-1.5">
                    <button type="button" @click="openExamples = !openExamples" class="compact-tab border border-slate-200 dark:border-slate-700" :class="{ 'is-active': openExamples }">Examples</button>
                    <input x-ref="fileInput" type="file" class="hidden" accept=".txt,.log,.csv,.json,.html,.xml,.md,.js,.css" @change="handleFile($event)">
                    <button type="button" @click="openFile()" class="compact-tab border border-slate-200 dark:border-slate-700">Open File</button>
                    <button type="button" @click="openBuilder = !openBuilder" class="compact-tab border border-slate-200 dark:border-slate-700" :class="{ 'is-active': openBuilder }">Builder</button>
                    <button type="button" @click="openSettings = !openSettings" class="compact-tab border border-slate-200 dark:border-slate-700" :class="{ 'is-active': openSettings }">Settings</button>
                    <button type="button" @click="undo()" :disabled="historyIndex <= 0" class="compact-tab border border-slate-200 dark:border-slate-700 disabled:cursor-not-allowed disabled:opacity-40">Undo</button>
                    <button type="button" @click="redo()" :disabled="historyIndex >= history.length - 1" class="compact-tab border border-slate-200 dark:border-slate-700 disabled:cursor-not-allowed disabled:opacity-40">Redo</button>
                    <button type="button" @click="clearAll()" class="compact-tab border border-slate-200 dark:border-slate-700">Clear</button>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-500">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Browser-only
                    </span>
                    <span x-show="workerBusy">Testing…</span>
                </div>
            </div>

            <div x-show="openSettings" x-transition class="mt-3 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 sm:grid-cols-2 lg:grid-cols-4 dark:border-slate-700 dark:bg-slate-950/60">
                <label class="flex items-center justify-between gap-3 text-xs text-slate-700 dark:text-slate-300">
                    <span>Live testing</span>
                    <input type="checkbox" x-model="autoRun" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                </label>
                <label class="flex items-center justify-between gap-3 text-xs text-slate-700 dark:text-slate-300">
                    <span>Highlight matches</span>
                    <input type="checkbox" x-model="highlightMatches" @change="renderHighlight()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                </label>
                <label class="text-xs text-slate-600 dark:text-slate-500">
                    <span class="mb-1 block">Maximum matches</span>
                    <select x-model.number="maxMatches" @change="runTest()" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <option value="1000">1,000</option>
                        <option value="5000">5,000</option>
                        <option value="10000">10,000</option>
                    </select>
                </label>
                <label class="text-xs text-slate-600 dark:text-slate-500">
                    <span class="mb-1 block">Execution timeout</span>
                    <select x-model.number="requestTimeout" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <option value="1000">1 second</option>
                        <option value="2500">2.5 seconds</option>
                        <option value="5000">5 seconds</option>
                    </select>
                </label>
            </div>
        </div>

        {{-- Examples --}}
        <div x-show="openExamples" x-transition class="border-b border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-950/50">
            <div class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">Common regex patterns</div>
            <div class="flex flex-wrap gap-1.5">
                <template x-for="example in examples" :key="example.key">
                    <button type="button" @click="loadExample(example.key)" class="compact-tab border border-slate-200 dark:border-slate-700" :class="{ 'is-active': selectedExample === example.key }" x-text="example.name"></button>
                </template>
            </div>
        </div>

        {{-- Visual builder --}}
        <div x-show="openBuilder" x-transition class="border-b border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/50">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                <div>
                    <div class="text-sm font-semibold text-slate-900 dark:text-white">Visual Regex Builder</div>
                    <div class="text-xs text-slate-500 dark:text-slate-500">Add common tokens without memorizing their syntax.</div>
                </div>
                <button type="button" @click="builderTokens = []" class="compact-tab border border-slate-200 dark:border-slate-700">Reset builder</button>
            </div>
            <div class="flex flex-wrap gap-1.5">
                <template x-for="token in builderOptions" :key="token.value">
                    <button type="button" @click="appendBuilderToken(token.value)" class="compact-tab border border-slate-200 dark:border-slate-700" :title="token.description">
                        <span x-text="token.name"></span>
                    </button>
                </template>
            </div>
            <div class="mt-3 rounded-lg border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Builder pattern</div>
                <code class="block break-all font-mono text-sm text-slate-800 dark:text-slate-200" x-text="builderTokens.join('') || 'Add tokens above'" ></code>
                <button type="button" x-show="builderTokens.length" @click="useBuilderPattern()" class="mt-2 compact-tab border border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-900 dark:bg-indigo-950/30 dark:text-indigo-300">Use pattern</button>
            </div>
        </div>

        {{-- Pattern row --}}
        <div class="border-b border-slate-200 p-4 dark:border-slate-700 sm:p-5">
            <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_auto_auto] xl:items-end">
                <div class="min-w-0">
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label class="text-sm font-semibold text-slate-800 dark:text-slate-200">Regular Expression</label>
                        <span class="text-xs text-slate-500" x-text="formatNumber(pattern.length) + ' characters'"></span>
                    </div>
                    <div class="flex overflow-hidden rounded-xl border border-slate-300 bg-slate-50 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100 dark:border-slate-600 dark:bg-slate-950 dark:focus-within:border-indigo-500">
                        <span class="flex items-center px-3 font-mono text-xl text-slate-500">/</span>
                        <input x-ref="pattern" x-model="pattern" @input="handleInput('pattern')" type="text" spellcheck="false" autocomplete="off" placeholder="e.g. ^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" class="min-w-0 flex-1 border-0 bg-transparent px-1 py-3 font-mono text-[15px] text-slate-900 outline-none focus:ring-0 dark:text-slate-100">
                        <span class="flex items-center px-3 font-mono text-xl text-slate-500">/</span>
                    </div>
                </div>

                <div>
                    <label for="select-flavor" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">Flavor</label>
                    <select id="select-flavor" x-model="flavor" @change="onFlavorChange()" class="h-9 min-w-44 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <option value="javascript">JavaScript RegExp</option>
                        <option value="php">PHP / PCRE</option>
                        <option value="python">Python</option>
                        <option value="java">Java</option>
                        <option value="csharp">C# / .NET</option>
                        <option value="go">Go / RE2</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">Flags</label>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="flag in availableFlags" :key="flag.value">
                            <button type="button" @click="toggleFlag(flag.value)" :title="flag.description" :class="flags.includes(flag.value) ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-500'" class="flex h-8 w-8 items-center justify-center rounded-lg border font-mono text-sm font-bold transition" x-text="flag.value"></button>
                        </template>
                    </div>
                </div>

                <div class="flex gap-1.5 xl:col-start-3 xl:row-start-2">
                    <button type="button" @click="runTest()" class="inline-flex h-9 items-center justify-center rounded-lg bg-slate-900 px-3 text-xs font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Test Regex</button>
                    <button type="button" @click="copyPattern()" class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300"><span x-text="copiedPattern ? '✓ Copied' : 'Copy Regex'"></span></button>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 text-xs text-slate-500 dark:text-slate-500">
                <template x-for="flag in availableFlags" :key="'help-' + flag.value">
                    <div x-show="flags.includes(flag.value)"><span class="font-mono font-bold text-slate-700 dark:text-slate-200" x-text="flag.value"></span> — <span x-text="flag.description"></span></div>
                </template>
            </div>
        </div>

        {{-- Status --}}
        <div class="border-b border-slate-200 px-4 py-2.5 dark:border-slate-700">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex min-w-0 flex-wrap items-center gap-2">
                    <span x-show="error" class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 dark:bg-red-950/30 dark:text-red-400"><span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Invalid</span>
                    <span x-show="!error && pattern" class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Valid</span>
                    <span x-show="!pattern" class="text-xs font-medium text-slate-500">Enter a pattern to begin</span>
                    <span x-show="error" class="truncate text-xs text-red-600 dark:text-red-400" x-text="error"></span>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-500">
                    <span x-text="statusMessage"></span>
                    <span x-show="flavor !== 'javascript'" class="rounded-full bg-amber-50 px-2 py-1 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">Browser execution: JavaScript</span>
                    <label class="inline-flex cursor-pointer items-center gap-2 font-medium"><input type="checkbox" x-model="autoRun" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Live Test</label>
                </div>
            </div>
        </div>

        {{-- Test / preview --}}
        <div class="grid grid-cols-1 xl:grid-cols-2">
            <div class="border-b border-slate-200 xl:border-b-0 xl:border-r dark:border-slate-700">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                    <div><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Test String</h3><p class="text-xs text-slate-500 dark:text-slate-500">Enter or paste text to test.</p></div>
                    <div class="flex items-center gap-2"><span class="text-xs text-slate-500" x-text="formatNumber(testText.length) + ' characters'"></span><button type="button" @click="selectAllText()" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white">Select All</button></div>
                </div>
                <textarea x-ref="testText" x-model="testText" @input="handleInput('testText')" @drop.prevent="handleDrop($event)" @dragover.prevent="dragActive = true" @dragleave="dragActive = false" spellcheck="false" placeholder="Paste or type your test text here…" :class="dragActive ? 'ring-2 ring-indigo-400' : ''" class="min-h-[390px] w-full resize-y border-0 bg-white p-5 font-mono text-[14px] leading-7 text-slate-900 outline-none focus:ring-0 dark:bg-slate-950 dark:text-slate-100"></textarea>
                <div class="flex items-center justify-between border-t border-slate-200 px-4 py-2.5 dark:border-slate-700"><span class="text-[11px] text-slate-500">Ctrl/Cmd + Enter to test</span><button type="button" @click="copyTestText()" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white"><span x-text="copiedText ? '✓ Copied' : 'Copy Text'"></span></button></div>
            </div>

            <div>
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                    <div><h3 class="text-sm font-semibold text-slate-900 dark:text-white">Match Preview</h3><p class="text-xs text-slate-500 dark:text-slate-500">Matching portions are highlighted.</p></div>
                    <div class="flex items-center gap-2"><button type="button" @click="copyMatches()" :disabled="!matches.length" class="text-xs font-semibold text-slate-500 disabled:opacity-40 dark:hover:text-white"><span x-text="copiedMatches ? '✓ Copied' : 'Copy Matches'"></span></button><button type="button" @click="exportMatches('json')" :disabled="!matches.length" class="text-xs font-semibold text-slate-500 disabled:opacity-40">Export</button></div>
                </div>
                <div class="min-h-[390px] overflow-auto whitespace-pre-wrap break-words bg-slate-50 p-5 font-mono text-[14px] leading-7 text-slate-800 dark:bg-slate-950/60 dark:text-slate-200" x-html="highlightedText || '<span class=&quot;text-slate-500&quot;>Matches will appear here…</span>'"></div>
                <div class="border-t border-slate-200 px-4 py-2.5 dark:border-slate-700"><div class="flex flex-wrap items-center gap-4 text-xs text-slate-500"><span>Matches: <strong class="text-slate-800 dark:text-slate-200" x-text="formatNumber(matchCount)"></strong></span><span>Groups: <strong class="text-slate-800 dark:text-slate-200" x-text="captureGroupCount"></strong></span><span>Pattern: <strong class="text-slate-800 dark:text-slate-200" x-text="pattern.length"></strong></span><span>Flags: <strong class="font-mono text-slate-800 dark:text-slate-200" x-text="flags || '—'"></strong></span></div></div>
            </div>
        </div>
    </section>

    {{-- Secondary modes --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex overflow-x-auto border-b border-slate-200 px-2 dark:border-slate-700">
            <template x-for="tab in modeTabs" :key="tab.value"><button type="button" @click="setMode(tab.value)" class="compact-tab" data-active-group :class="{ 'is-active': mode === tab.value }" x-text="tab.label"></button></template>
        </div>

        {{-- Match details --}}
        <div x-show="mode === 'matches'" class="p-4 sm:p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div><h3 class="font-semibold text-slate-900 dark:text-white">Match Inspector</h3><p class="text-xs text-slate-500">Indexes, line/column positions, capture groups and named groups.</p></div><div class="flex gap-2"><button type="button" @click="previousMatch()" :disabled="selectedMatchIndex <= 0" class="compact-tab border border-slate-200 disabled:opacity-40">← Previous</button><button type="button" @click="nextMatch()" :disabled="selectedMatchIndex >= matches.length - 1" class="compact-tab border border-slate-200 disabled:opacity-40">Next →</button></div></div>
            <div x-show="!matches.length" class="rounded-xl border border-dashed border-slate-300 p-8 text-center dark:border-slate-700"><div class="text-sm font-medium text-slate-700 dark:text-slate-300">No matches</div><p class="mt-1 text-xs text-slate-500">Run a valid pattern against a test string.</p></div>
            <div x-show="matches.length" class="space-y-2"><template x-for="(match,index) in matches" :key="match.id"><details :open="index === selectedMatchIndex" @toggle="if ($event.target.open) selectedMatchIndex = index" class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50"><div class="flex min-w-0 items-center gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300" x-text="index + 1"></span><code class="truncate font-mono text-sm text-slate-900 dark:text-slate-100" x-text="match.value || '(empty match)'"></code></div><div class="shrink-0 text-xs text-slate-500" x-text="match.index + ' – ' + match.end"></div></summary><div class="border-t border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/50"><div class="grid gap-3 sm:grid-cols-4"><div class="rounded-lg bg-white p-3 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Match</div><code class="mt-1 block break-all font-mono text-sm" x-text="match.value || '(empty)'"></code></div><div class="rounded-lg bg-white p-3 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Position</div><div class="mt-1 text-sm font-semibold" x-text="match.index + ' → ' + match.end"></div></div><div class="rounded-lg bg-white p-3 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Line / Column</div><div class="mt-1 text-sm font-semibold" x-text="match.line + ' / ' + match.column"></div></div><div class="rounded-lg bg-white p-3 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Length</div><div class="mt-1 text-sm font-semibold" x-text="match.value.length"></div></div></div><div x-show="match.groups.length" class="mt-4"><div class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Capture Groups</div><div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700"><template x-for="group in match.groups" :key="group.key"><div class="grid grid-cols-[auto_1fr] border-b border-slate-200 last:border-b-0 dark:border-slate-700"><div class="bg-slate-100 px-3 py-2 font-mono text-xs font-semibold text-slate-600 dark:bg-slate-800" x-text="group.label"></div><div class="break-all bg-white px-3 py-2 font-mono text-xs dark:bg-slate-900" x-text="group.value ?? 'undefined'"></div></div></template></div></div><div x-show="Object.keys(match.namedGroups || {}).length" class="mt-4"><div class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Named Groups</div><div class="grid gap-2 sm:grid-cols-2"><template x-for="(value,name) in match.namedGroups" :key="name"><div class="rounded-lg border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900"><div class="font-mono text-xs font-bold text-indigo-700 dark:text-indigo-300" x-text="'(?<' + name + '>…)'"></div><div class="mt-1 break-all font-mono text-sm" x-text="value ?? 'undefined'"></div></div></template></div></div></div></details></template></div>
        </div>

        {{-- Replace --}}
        <div x-show="mode === 'replace'" class="p-4 sm:p-5">
            <div class="grid gap-5 lg:grid-cols-2"><div><div class="mb-2 flex items-center justify-between"><label class="text-sm font-semibold text-slate-800 dark:text-slate-200">Replacement</label><button type="button" @click="replacement = ''; runReplacement()" class="text-xs text-slate-500">Clear</button></div><input x-model="replacement" @input="runReplacement()" type="text" spellcheck="false" placeholder="e.g. $1 or $<name>" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"><div class="mt-3 flex flex-wrap gap-1.5"><template x-for="item in replacementTokens" :key="item"><button type="button" @click="setReplacement(item)" class="compact-tab border border-slate-200 dark:border-slate-700" x-text="item"></button></template></div><p class="mt-3 text-xs leading-5 text-slate-500">Supports JavaScript replacement tokens such as $1, $2, $&, $`, $' and $&lt;name&gt;.</p></div><div><div class="mb-2 flex items-center justify-between"><label class="text-sm font-semibold text-slate-800 dark:text-slate-200">Replacement Preview</label><button type="button" @click="copyReplacement()" :disabled="!replacementResult" class="text-xs font-semibold text-slate-500 disabled:opacity-40"><span x-text="copiedReplacement ? '✓ Copied' : 'Copy Result'"></span></button></div><textarea readonly :value="replacementResult" class="min-h-[220px] w-full resize-y rounded-xl border border-slate-200 bg-slate-50 p-4 font-mono text-sm leading-6 outline-none dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-200"></textarea></div></div>
        </div>

        {{-- Split --}}
        <div x-show="mode === 'split'" class="p-4 sm:p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3"><div><h3 class="font-semibold text-slate-900 dark:text-white">Regex Split</h3><p class="text-xs text-slate-500">Split the test string using the current pattern as a delimiter.</p></div><button type="button" @click="exportSplit()" :disabled="!splitResults.length" class="compact-tab border border-slate-200 disabled:opacity-40">Export CSV</button></div>
            <div x-show="splitError" class="mb-3 rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700 dark:bg-red-950/30 dark:text-red-400" x-text="splitError"></div>
            <div x-show="!splitResults.length" class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500 dark:border-slate-700">No split results yet.</div>
            <div x-show="splitResults.length" class="overflow-auto rounded-xl border border-slate-200 dark:border-slate-700"><table class="min-w-full text-left text-xs"><thead class="bg-slate-50 dark:bg-slate-950"><tr><th class="px-3 py-2">#</th><th class="px-3 py-2">Value</th><th class="px-3 py-2">Length</th></tr></thead><tbody><template x-for="(item,index) in splitResults" :key="index"><tr class="border-t border-slate-200 dark:border-slate-700"><td class="px-3 py-2 text-slate-500" x-text="index + 1"></td><td class="whitespace-pre-wrap break-all px-3 py-2 font-mono" x-text="item"></td><td class="px-3 py-2" x-text="item.length"></td></tr></template></tbody></table></div>
        </div>

        {{-- Test cases --}}
        <div x-show="mode === 'tests'" class="p-4 sm:p-5">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><div><h3 class="font-semibold text-slate-900 dark:text-white">Regex Test Suite</h3><p class="text-xs text-slate-500">Define strings that should match or should not match.</p></div><div class="flex gap-2"><button type="button" @click="runTestCases()" class="compact-tab border border-slate-200 dark:border-slate-700">Run Tests</button><button type="button" @click="addTestCase()" class="inline-flex h-[30px] items-center rounded-md bg-slate-900 px-3 text-[11px] font-semibold text-white dark:bg-white dark:text-slate-900">+ Add Test</button></div></div>
            <div class="space-y-2"><template x-for="(test,index) in testCases" :key="test.id"><div class="rounded-xl border border-slate-200 p-3 dark:border-slate-700"><div class="flex flex-col gap-2 lg:flex-row lg:items-center"><input x-model="test.value" @input="runTestCases()" type="text" placeholder="Test string" class="min-w-0 flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2.5 font-mono text-sm outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"><select x-model="test.expected" @change="runTestCases()" class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"><option value="match">Should match</option><option value="no-match">Should not match</option></select><span class="inline-flex min-w-[65px] items-center justify-center rounded-lg px-3 py-2 text-xs font-bold" :class="test.result === null ? 'bg-slate-100 text-slate-500 dark:bg-slate-800' : test.result ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400'" x-text="test.result === null ? 'Not tested' : test.result ? 'PASS' : 'FAIL'"></span><button type="button" @click="removeTestCase(index)" class="rounded-lg p-2 text-slate-500 hover:bg-red-50 hover:text-red-600">×</button></div></div></template></div>
            <div x-show="testCases.length" class="mt-4 flex flex-wrap items-center gap-4 rounded-xl bg-slate-50 p-4 text-xs dark:bg-slate-950/60"><strong>Test summary</strong><span class="text-emerald-600" x-text="passedTests + ' passed'"></span><span class="text-red-600" x-text="failedTests + ' failed'"></span><span class="text-slate-500" x-text="testCases.length + ' total'"></span></div>
        </div>

        {{-- Reference / explanation --}}
        <div x-show="mode === 'reference'" class="p-4 sm:p-5">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3"><template x-for="section in referenceSections" :key="section.title"><div><h3 class="mb-3 text-sm font-bold text-slate-900 dark:text-white" x-text="section.title"></h3><div class="space-y-1.5"><template x-for="item in section.items" :key="item.syntax"><button type="button" @click="insertToken(item.syntax)" class="flex w-full gap-3 rounded-lg bg-slate-50 p-3 text-left hover:bg-indigo-50 dark:bg-slate-950/60 dark:hover:bg-indigo-950/20"><code class="w-28 shrink-0 font-mono text-xs font-bold text-slate-900 dark:text-white" x-text="item.syntax"></code><span class="text-xs text-slate-600 dark:text-slate-500" x-text="item.description"></span></button></template></div></div></template></div>
        </div>

        {{-- Diagnosis --}}
        <div x-show="mode === 'diagnosis'" class="p-4 sm:p-5">
            <div class="grid gap-4 lg:grid-cols-2"><div><h3 class="font-semibold text-slate-900 dark:text-white">Pattern Diagnosis</h3><p class="mt-1 text-xs text-slate-500">Actionable checks for common mistakes and expensive patterns.</p><div class="mt-4 space-y-2"><template x-for="item in diagnostics" :key="item.id"><div class="rounded-xl border p-3" :class="item.level === 'warning' ? 'border-amber-200 bg-amber-50/60 dark:border-amber-900/50 dark:bg-amber-950/20' : item.level === 'error' ? 'border-red-200 bg-red-50/60 dark:border-red-900/50 dark:bg-red-950/20' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-950/50'"><div class="text-xs font-semibold" x-text="item.title"></div><div class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-500" x-text="item.message"></div></div></template></div></div><div><h3 class="font-semibold text-slate-900 dark:text-white">Pattern Explanation</h3><div class="mt-4 flex flex-wrap gap-1.5"><template x-for="token in explainedTokens" :key="token.id"><button type="button" @click="insertToken(token.syntax)" class="rounded-lg border border-slate-200 bg-white px-2.5 py-2 font-mono text-xs hover:border-indigo-300 dark:border-slate-700 dark:bg-slate-900" :title="token.description" x-text="token.syntax"></button></template></div><div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/50"><template x-if="selectedExplanation"><div><div class="font-mono text-sm font-bold text-indigo-700 dark:text-indigo-300" x-text="selectedExplanation.syntax"></div><div class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-500" x-text="selectedExplanation.description"></div></div></template><template x-if="!selectedExplanation"><div class="text-sm text-slate-500">Select a token above to inspect it.</div></template></div></div></div>
        </div>

        {{-- Comparison --}}
        <div x-show="mode === 'compare'" class="p-4 sm:p-5">
            <div class="grid gap-4 lg:grid-cols-2"><div><label class="mb-2 block text-sm font-semibold">Pattern A</label><input x-model="compareA" @input="comparePatterns()" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 font-mono text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></div><div><label class="mb-2 block text-sm font-semibold">Pattern B</label><input x-model="compareB" @input="comparePatterns()" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 font-mono text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></div></div><div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm dark:border-slate-700 dark:bg-slate-950/50" x-text="compareResult"></div>
        </div>

        {{-- Export --}}
        <div x-show="mode === 'export'" class="p-4 sm:p-5">
            <div class="mb-4"><h3 class="font-semibold text-slate-900 dark:text-white">Export Match Results</h3><p class="text-xs text-slate-500">Export only the locally computed results.</p></div><div class="flex flex-wrap gap-2"><template x-for="format in exportFormats" :key="format"><button type="button" @click="exportMatches(format)" :disabled="!matches.length" class="compact-tab border border-slate-200 disabled:opacity-40 dark:border-slate-700" x-text="format.toUpperCase()"></button></template></div>
        </div>
    </section>

    {{-- Compact stats / safety --}}
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Matches</div><div class="mt-1 text-lg font-bold" x-text="formatNumber(matchCount)"></div></div>
        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Capture groups</div><div class="mt-1 text-lg font-bold" x-text="captureGroupCount"></div></div>
        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Test length</div><div class="mt-1 text-lg font-bold" x-text="formatNumber(testText.length)"></div></div>
        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900"><div class="text-[11px] uppercase tracking-wide text-slate-500">Execution</div><div class="mt-1 text-lg font-bold" x-text="lastElapsed === null ? '—' : lastElapsed + ' ms'"></div></div>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-3 dark:border-emerald-900/50 dark:bg-emerald-950/20"><div class="text-[11px] uppercase tracking-wide text-emerald-700 dark:text-emerald-400">Privacy</div><div class="mt-1 text-sm font-semibold text-emerald-800 dark:text-emerald-300">Runs in browser</div></div>
    </div>

    <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 dark:border-emerald-900/50 dark:bg-emerald-950/20"><div class="flex items-start gap-3"><span class="mt-0.5 text-emerald-600 dark:text-emerald-400">✓</span><div><div class="text-xs font-semibold text-emerald-800 dark:text-emerald-300">Browser-based processing</div><p class="mt-0.5 text-xs leading-5 text-emerald-700 dark:text-emerald-400">Regex execution, matching and replacement happen locally. Your pattern and test text are not sent to AabiTech.</p></div></div></div>
</div>

@script
<script>
window.aabiRegexTester = function () {
    return {
        pattern: '',
        flags: 'gm',
        testText: '',
        replacement: '',
        flavor: 'javascript',
        mode: 'matches',
        autoRun: true,
        highlightMatches: true,
        workerBusy: false,
        dragActive: false,
        error: '',
        statusMessage: '',
        highlightedText: '',
        replacementResult: '',
        splitResults: [],
        splitError: '',
        matches: [],
        matchCount: 0,
        captureGroupCount: 0,
        selectedMatchIndex: 0,
        selectedExample: 'email',
        openExamples: false,
        openBuilder: false,
        openSettings: false,
        copiedPattern: false,
        copiedMatches: false,
        copiedReplacement: false,
        copiedText: false,
        maxMatches: 10000,
        maxPatternLength: 10000,
        maxTextLength: 200000,
        requestTimeout: 2500,
        lastElapsed: null,
        nextTestId: 3,
        scheduleTimer: null,
        latestTestRequest: 0,
        requestSequence: 0,
        worker: null,
        workerUrl: null,
        workerRequests: new Map(),
        builderTokens: [],
        compareA: '',
        compareB: '',
        compareResult: 'Enter two patterns to compare.',
        history: [],
        historyIndex: -1,
        historyTimer: null,
        examples: [
            { key:'email', name:'Email', pattern:'[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}', flags:'gi', text:'Contact hello@example.com or support@aabitech.com.' },
            { key:'phone', name:'Phone', pattern:'\\+?[0-9][0-9\\-\\s]{7,}', flags:'g', text:'Call +92 300 1234567 or 0300-7654321.' },
            { key:'url', name:'URL', pattern:'https?:\\/\\/[^\\s]+', flags:'gi', text:'Visit https://aabitech.com and https://example.com/tools.' },
            { key:'number', name:'Number', pattern:'\\b\\d+(?:\\.\\d+)?\\b', flags:'g', text:'Prices are 125, 99.50 and 1000 rupees.' },
            { key:'date', name:'Date', pattern:'\\b\\d{4}-\\d{2}-\\d{2}\\b', flags:'g', text:'Important dates: 2026-09-21 and 2026-10-01.' },
            { key:'hashtag', name:'Hashtag', pattern:'#[A-Za-z0-9_]+', flags:'g', text:'Learn #Python and #WebDevelopment with #AabiTech.' },
            { key:'ipv4', name:'IPv4', pattern:'\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b', flags:'g', text:'Servers: 192.168.1.10 and 10.0.0.25.' },
            { key:'uuid', name:'UUID', pattern:'\\b[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}\\b', flags:'gi', text:'IDs: 550e8400-e29b-41d4-a716-446655440000.' },
            { key:'html', name:'HTML Tag', pattern:'<([A-Za-z][A-Za-z0-9]*)\\b[^>]*>', flags:'g', text:'<div class="card">Hello</div><span>World</span>' },
            { key:'username', name:'Username', pattern:'@[A-Za-z0-9_]{3,20}', flags:'g', text:'Follow @aabitech and @cswithsiriqbal.' }
        ],
        availableFlags: [
            {value:'g', description:'Global — find all matches'},
            {value:'i', description:'Ignore case'},
            {value:'m', description:'Multiline anchors'},
            {value:'s', description:'DotAll — dot matches line terminators'},
            {value:'u', description:'Unicode mode'},
            {value:'y', description:'Sticky — match only at lastIndex'},
            {value:'d', description:'Indices — expose match index pairs'}
        ],
        modeTabs: [
            {value:'matches', label:'Match Details'},
            {value:'replace', label:'Replace'},
            {value:'split', label:'Split'},
            {value:'tests', label:'Test Cases'},
            {value:'reference', label:'Regex Reference'},
            {value:'diagnosis', label:'Diagnosis'},
            {value:'compare', label:'Compare'},
            {value:'export', label:'Export'}
        ],
        replacementTokens: ['$1','$2','$&','$<name>','$`',"$'"],
        builderOptions: [
            {name:'Digit',value:'\\d',description:'One digit'},
            {name:'Word',value:'\\w',description:'Word character'},
            {name:'Whitespace',value:'\\s',description:'Whitespace'},
            {name:'Any',value:'.',description:'Any character'},
            {name:'One+',value:'+',description:'One or more'},
            {name:'Zero+',value:'*',description:'Zero or more'},
            {name:'Optional',value:'?',description:'Zero or one'},
            {name:'Start',value:'^',description:'Start of input/line'},
            {name:'End',value:'$',description:'End of input/line'},
            {name:'Capture',value:'(…)',description:'Capturing group placeholder'}
        ],
        testCases: [
            {id:1,value:'student@example.com',expected:'match',result:null},
            {id:2,value:'not-an-email',expected:'no-match',result:null}
        ],
        referenceSections: [
            {title:'Character Classes',items:[
                {syntax:'.',description:'Any character except line terminators'},
                {syntax:'\\d',description:'Digit'}, {syntax:'\\D',description:'Not a digit'},
                {syntax:'\\w',description:'Word character'}, {syntax:'\\W',description:'Not a word character'},
                {syntax:'\\s',description:'Whitespace'}, {syntax:'\\S',description:'Not whitespace'},
                {syntax:'[abc]',description:'One character from the set'}, {syntax:'[^abc]',description:'One character outside the set'}
            ]},
            {title:'Quantifiers',items:[
                {syntax:'*',description:'Zero or more'}, {syntax:'+',description:'One or more'}, {syntax:'?',description:'Zero or one'},
                {syntax:'{n}',description:'Exactly n'}, {syntax:'{n,}',description:'n or more'}, {syntax:'{n,m}',description:'Between n and m'}
            ]},
            {title:'Groups & Assertions',items:[
                {syntax:'(...)',description:'Capturing group'}, {syntax:'(?:...)',description:'Non-capturing group'},
                {syntax:'(?<name>...)',description:'Named capture group'}, {syntax:'(?=...)',description:'Positive lookahead'},
                {syntax:'(?!...)',description:'Negative lookahead'}, {syntax:'(?<=...)',description:'Positive lookbehind'}
            ]},
            {title:'Anchors',items:[{syntax:'^',description:'Beginning of string or line'},{syntax:'$',description:'End of string or line'},{syntax:'\\b',description:'Word boundary'},{syntax:'\\B',description:'Non-word boundary'}]},
            {title:'Escapes',items:[{syntax:'\\n',description:'Line feed'},{syntax:'\\r',description:'Carriage return'},{syntax:'\\t',description:'Tab'},{syntax:'\\.',description:'Literal dot'},{syntax:'\\/',description:'Literal slash'}]},
            {title:'Replacement',items:[{syntax:'$1',description:'First capture group'},{syntax:'$2',description:'Second capture group'},{syntax:'$&',description:'Entire match'},{syntax:'$<name>',description:'Named capture group'},{syntax:'$`',description:'Text before match'},{syntax:"$'",description:'Text after match'}]}
        ],
        explainedTokens: [],
        selectedExplanation: null,
        diagnostics: [],
        exportFormats: ['json','jsonl','csv','txt'],

        init() {
            this.createWorker();
            this.explainedTokens = this.referenceSections.flatMap(section => section.items);
            this.loadExample('email', false);
            this.compareA = this.pattern;
            this.compareB = '';
            this.pushHistory(true);
            this.$watch('pattern', () => { this.explainPattern(); this.scheduleHistory(); if (this.autoRun) this.scheduleTest(); });
            this.$watch('testText', () => { this.scheduleHistory(); if (this.autoRun) this.scheduleTest(); });
            this.$watch('flags', () => { this.scheduleHistory(); if (this.autoRun) this.scheduleTest(); });
            this.$nextTick(() => this.runTest());
        },

        createWorker() {
            this.destroyWorker();
            const workerCode = `
                'use strict';
                function advanceIndex(s,i,u){ if(!u || i+1>=s.length)return i+1; const a=s.charCodeAt(i),b=s.charCodeAt(i+1); return a>=55296&&a<=56319&&b>=56320&&b<=57343?i+2:i+1; }
                function serialize(m){
                    const groups=[]; for(let i=1;i<m.length;i++) groups.push({number:i,name:'',value:m[i]===undefined?null:m[i]});
                    if(m.groups) Object.entries(m.groups).forEach(([name,value])=>groups.push({number:null,name,value:value===undefined?null:value}));
                    return {value:m[0],index:m.index,end:m.index+m[0].length,groups,namedGroups:m.groups?Object.fromEntries(Object.entries(m.groups)): {},indices:m.indices?m.indices.map(x=>x?[x[0],x[1]]:null):null};
                }
                function test(pattern,flags,text,limit){ const started=performance.now(); const re=new RegExp(pattern,flags),out=[]; if(re.global||re.sticky){re.lastIndex=0; while(true){const m=re.exec(text); if(!m)break; out.push(serialize(m)); if(out.length>=limit)return {matches:out,limited:true,elapsed:Math.round(performance.now()-started),source:re.source,flags:re.flags}; if(m[0]===''){re.lastIndex=advanceIndex(text,re.lastIndex,re.unicode); if(re.lastIndex>text.length)break;}}}else{const m=re.exec(text);if(m)out.push(serialize(m));} return {matches:out,limited:false,elapsed:Math.round(performance.now()-started),source:re.source,flags:re.flags}; }
                function replace(pattern,flags,text,replacement){const started=performance.now();const re=new RegExp(pattern,flags);return {result:text.replace(re,replacement),elapsed:Math.round(performance.now()-started)};}
                function tests(pattern,flags,items){const re=new RegExp(pattern,flags);return items.map(t=>{re.lastIndex=0;const matched=re.test(t.value);re.lastIndex=0;return {id:t.id,result:t.expected==='match'?matched:!matched};});}
                self.onmessage=e=>{const d=e.data||{};try{let result;if(d.action==='test')result=test(d.pattern,d.flags,d.text,d.limit);else if(d.action==='replace')result=replace(d.pattern,d.flags,d.text,d.replacement);else if(d.action==='tests')result=tests(d.pattern,d.flags,d.items);else throw new Error('Unknown regex operation.');self.postMessage({id:d.id,ok:true,result});}catch(err){self.postMessage({id:d.id,ok:false,error:err?.message||'Regex processing failed.'});}};
            `;
            const blob = new Blob([workerCode], {type:'application/javascript'});
            this.workerUrl = URL.createObjectURL(blob);
            this.worker = new Worker(this.workerUrl);
            this.worker.onmessage = event => {
                const data = event.data || {};
                const pending = this.workerRequests.get(data.id);
                if (!pending) return;
                clearTimeout(pending.timeout);
                this.workerRequests.delete(data.id);
                data.ok ? pending.resolve(data.result) : pending.reject(new Error(data.error || 'Regex processing failed.'));
            };
            this.worker.onerror = event => {
                const error = new Error(event.message || 'Regex worker failed.');
                const pending = [...this.workerRequests.values()];
                this.workerRequests.clear();
                pending.forEach(item => { clearTimeout(item.timeout); item.reject(error); });
                this.restartWorker();
            };
        },

        destroyWorker() {
            if (this.worker) this.worker.terminate();
            if (this.workerUrl) URL.revokeObjectURL(this.workerUrl);
            this.worker = null; this.workerUrl = null;
            this.workerRequests.forEach(item => clearTimeout(item.timeout));
            this.workerRequests.clear();
        },

        restartWorker() { this.destroyWorker(); this.createWorker(); },

        workerRequest(action,payload={}) {
            if (!this.worker) this.createWorker();
            const id = ++this.requestSequence;
            return new Promise((resolve,reject) => {
                const timeout = setTimeout(() => {
                    if (!this.workerRequests.has(id)) return;
                    this.workerRequests.delete(id);
                    reject(new Error('Regex processing timed out. The expression or input may be too complex.'));
                    this.restartWorker();
                }, this.requestTimeout);
                this.workerRequests.set(id,{resolve,reject,timeout});
                this.worker.postMessage({id,action,...payload});
            });
        },

        scheduleTest() { clearTimeout(this.scheduleTimer); this.scheduleTimer=setTimeout(()=>this.runTest(),120); },

        async runTest() {
            const requestId = ++this.latestTestRequest;
            this.error=''; this.statusMessage=''; this.matches=[]; this.matchCount=0; this.captureGroupCount=0; this.lastElapsed=null;
            this.selectedMatchIndex=0; this.splitResults=[]; this.splitError=''; this.highlightedText=this.escapeHtml(this.testText);
            if (!this.pattern) { this.runTestCases(); this.updateDiagnostics(); return; }
            if (this.pattern.length > this.maxPatternLength) { this.error='Pattern is too long. Keep it below 10,000 characters.'; return; }
            if (this.testText.length > this.maxTextLength) { this.error='Test text is too large for interactive testing. Keep it below 200,000 characters.'; return; }
            let flags;
            try { flags=this.normalizeFlags(this.flags); new RegExp(this.pattern,flags); } catch(error) { this.error=this.friendlyError(error); this.updateDiagnostics(); return; }
            this.workerBusy=true; this.statusMessage='Testing…';
            try {
                const result=await this.workerRequest('test',{pattern:this.pattern,flags,text:this.testText,limit:this.maxMatches});
                if(requestId!==this.latestTestRequest)return;
                this.matches=(result.matches||[]).map((match,index)=>({...match,id:index+1,line:this.lineNumber(match.index),column:this.columnNumber(match.index),groups:(match.groups||[]).map((g,i)=>({...g,key:(g.name||'group')+'-'+(g.number??i),label:g.name?`$<${g.name}>`:`$${g.number}`}))}));
                this.matchCount=this.matches.length; this.captureGroupCount=this.matches[0]?.groups?.length||0; this.lastElapsed=result.elapsed;
                if(result.limited)this.error=`Testing stopped after ${this.formatNumber(this.maxMatches)} matches.`;
                this.renderHighlight(); this.statusMessage=`${this.formatNumber(this.matchCount)} match${this.matchCount===1?'':'es'} found in ${result.elapsed} ms`;
                this.runReplacement(); this.runTestCases(); this.runSplit(); this.updateDiagnostics();
            } catch(error) { if(requestId===this.latestTestRequest)this.error=this.friendlyError(error); }
            finally { if(requestId===this.latestTestRequest)this.workerBusy=false; }
        },

        renderHighlight() { this.highlightedText=this.highlightMatches?this.buildHighlightedText(this.testText,this.matches):this.escapeHtml(this.testText); },

        buildHighlightedText(text,matches) {
            if(!text)return '';
            if(!matches.length)return this.escapeHtml(text);
            let output='',cursor=0;
            matches.forEach((match,index)=>{const start=Number(match.index),value=String(match.value??''),end=start+value.length;if(start<cursor)return;output+=this.escapeHtml(text.slice(cursor,start));if(value.length===0)output+='<mark class="inline-block h-4 w-1 rounded bg-amber-400 align-middle" title="Zero-length match"></mark>';else output+='<mark class="rounded bg-amber-200 px-0.5 text-slate-900 dark:bg-amber-400 dark:text-slate-950" data-match-index="'+index+'">'+this.escapeHtml(value)+'</mark>';cursor=end;});
            output+=this.escapeHtml(text.slice(cursor)); return output;
        },

        async runReplacement() {
            if(!this.pattern){this.replacementResult=this.testText;return;}
            try { const result=await this.workerRequest('replace',{pattern:this.pattern,flags:this.normalizeFlags(this.flags),text:this.testText,replacement:this.replacement}); this.replacementResult=result.result; } catch { this.replacementResult=''; }
        },

        async runTestCases() {
            if(!this.pattern){this.testCases.forEach(t=>t.result=null);return;}
            try { const flags=this.normalizeFlags(this.flags); new RegExp(this.pattern,flags); const result=await this.workerRequest('tests',{pattern:this.pattern,flags,items:this.testCases.map(t=>({id:t.id,value:t.value,expected:t.expected}))}); const byId=new Map(result.map(x=>[x.id,x.result])); this.testCases.forEach(t=>t.result=byId.has(t.id)?byId.get(t.id):null); } catch { this.testCases.forEach(t=>t.result=null); }
        },

        runSplit() {
            if(!this.pattern){this.splitResults=[];return;}
            try { const re=new RegExp(this.pattern,this.normalizeFlags(this.flags).replace('y','')); this.splitResults=this.testText.split(re); this.splitError=''; } catch(error) { this.splitResults=[]; this.splitError=this.friendlyError(error); }
        },

        normalizeFlags(value) {
            const supported=new Set(this.availableFlags.map(x=>x.value));
            return [...new Set(String(value||'').split('').filter(x=>supported.has(x)))].join('');
        },

        toggleFlag(flag) { const set=new Set(this.flags); set.has(flag)?set.delete(flag):set.add(flag); this.flags=this.availableFlags.map(x=>x.value).filter(x=>set.has(x)).join(''); this.runTest(); },

        onFlavorChange() { if(this.flavor!=='javascript')this.statusMessage='This browser executes JavaScript RegExp; the selected flavor is used for guidance and code generation.'; this.updateDiagnostics(); },

        loadExample(key,close=true) { const item=this.examples.find(x=>x.key===key); if(!item)return; this.selectedExample=key; this.pattern=item.pattern; this.flags=item.flags; this.testText=item.text; this.replacement=''; if(close)this.openExamples=false; this.$nextTick(()=>this.runTest()); },

        setMode(mode) { this.mode=mode; if(mode==='split')this.runSplit(); if(mode==='diagnosis')this.updateDiagnostics(); if(mode==='compare'){this.compareA=this.pattern;this.comparePatterns();} },

        insertToken(token) { const input=this.$refs.pattern; const start=input?.selectionStart ?? this.pattern.length; const end=input?.selectionEnd ?? start; this.pattern=this.pattern.slice(0,start)+token+this.pattern.slice(end); this.$nextTick(()=>{input?.focus(); const position=start+token.length; input?.setSelectionRange(position,position);}); },

        appendBuilderToken(token) { if(token==='(…)')token='()'; this.builderTokens.push(token); },
        useBuilderPattern() { this.pattern=this.builderTokens.join(''); this.openBuilder=false; this.runTest(); },

        explainPattern() {
            const found=[]; const source=this.pattern||''; const candidates=this.referenceSections.flatMap(s=>s.items);
            candidates.forEach(item=>{if(source.includes(item.syntax)&&!found.some(x=>x.syntax===item.syntax))found.push(item);});
            this.explainedTokens=found.length?found:candidates.slice(0,8);
            this.selectedExplanation=found[0]||null;
        },

        updateDiagnostics() {
            const p=this.pattern||''; const items=[];
            const add=(id,level,title,message)=>items.push({id,level,title,message});
            if(!p){this.diagnostics=[];return;}
            if(/\(\.\*\)[+*]|\(\[.*\]\+\)[+*]|\(\.\+\)[+*]/.test(p))add('nested','warning','Potential catastrophic backtracking','Nested greedy quantifiers can cause very expensive backtracking on hostile input. Consider bounded quantifiers or more specific character classes.');
            if(/\.(\*|\+)/.test(p)&&!this.flags.includes('s'))add('dot','warning','Greedy dot pattern','A greedy .*, especially near another quantifier or delimiter, may scan much more text than expected.');
            if(/\\d\+.*\\d\+|\\w\+.*\\w\+/.test(p))add('greedy','warning','Multiple greedy sections','Multiple unbounded sections can increase backtracking work.');
            if(/\[[^\]]*$/.test(p))add('class','error','Unclosed character class','A character class appears to be missing its closing ].');
            if((p.match(/\(/g)||[]).length!==(p.match(/\)/g)||[]).length)add('group','error','Unbalanced parentheses','Check opening and closing group delimiters.');
            if(/(?<!\\)\{\d+,?\d*\}/.test(p)===false && /\{\d+/.test(p))add('quantifier','warning','Check quantifier syntax','A numeric quantifier may be incomplete or malformed.');
            if(this.flavor==='go' && /\(\?<|\(\?<=|\(\?!|\(\?=/.test(p))add('go','warning','Go / RE2 compatibility','RE2 does not support several JavaScript features such as lookaround and named-group syntax.');
            if(this.flavor==='python' && /\\p\{|\\k</.test(p))add('python','warning','Python compatibility','Some Unicode-property and backreference syntax differs between Python regex engines and JavaScript.');
            if(!items.length)add('ok','info','No obvious hazards detected','The static safety checks did not find a common issue. This is not a proof that a pattern is safe for every input.');
            this.diagnostics=items;
        },

        comparePatterns() {
            if(this.compareA===this.compareB){this.compareResult='The two patterns are identical.';return;}
            try { const a=new RegExp(this.compareA,this.normalizeFlags(this.flags)); const b=new RegExp(this.compareB,this.normalizeFlags(this.flags)); const sample=this.testText; const am=[...sample.matchAll(new RegExp(a.source,a.flags.includes('g')?'g':a.flags+'g'))].map(x=>x[0]); const bm=[...sample.matchAll(new RegExp(b.source,b.flags.includes('g')?'g':b.flags+'g'))].map(x=>x[0]); const same=JSON.stringify(am)===JSON.stringify(bm); this.compareResult=same?'Different source patterns produced the same matches for the current test text.':'The patterns produce different matches for the current test text.'; } catch(error) { this.compareResult='Comparison could not execute: '+this.friendlyError(error); }
        },

        async copyPattern() { await this.copyText('/'+this.pattern+'/'+this.normalizeFlags(this.flags)); this.copiedPattern=true; setTimeout(()=>this.copiedPattern=false,1500); },
        async copyMatches() { if(!this.matches.length)return; await this.copyText(this.matches.map((m,i)=>`${i+1}. ${m.value}`).join('\n')); this.copiedMatches=true; setTimeout(()=>this.copiedMatches=false,1500); },
        async copyReplacement() { if(!this.replacementResult)return; await this.copyText(this.replacementResult); this.copiedReplacement=true; setTimeout(()=>this.copiedReplacement=false,1500); },
        async copyTestText() { await this.copyText(this.testText); this.copiedText=true; setTimeout(()=>this.copiedText=false,1500); },
        async copyText(value) { try { await navigator.clipboard.writeText(String(value??'')); } catch { const el=document.createElement('textarea');el.value=String(value??'');el.style.position='fixed';el.style.opacity='0';document.body.appendChild(el);el.select();document.execCommand('copy');el.remove(); } },

        exportMatches(format) {
            if(!this.matches.length)return;
            const rows=this.matches.map((m,i)=>({number:i+1,match:m.value,index:m.index,end:m.end,line:m.line,column:m.column,groups:m.groups.map(g=>({name:g.name||null,number:g.number,value:g.value}))}));
            let content='',mime='text/plain',name='regex-matches.txt';
            if(format==='json'){content=JSON.stringify(rows,null,2);mime='application/json';name='regex-matches.json';}
            else if(format==='jsonl'){content=rows.map(x=>JSON.stringify(x)).join('\n');mime='application/x-ndjson';name='regex-matches.jsonl';}
            else if(format==='csv'){const esc=v=>'"'+String(v??'').replace(/"/g,'""')+'"';content='number,match,index,end,line,column\n'+rows.map(x=>[x.number,x.match,x.index,x.end,x.line,x.column].map(esc).join(',')).join('\n');mime='text/csv';name='regex-matches.csv';}
            else content=rows.map(x=>`${x.number}. ${x.match}`).join('\n');
            this.downloadBlob(content,mime,name);
        },
        exportSplit() { if(this.splitResults.length)this.downloadBlob(this.splitResults.map(x=>'"'+String(x).replace(/"/g,'""')+'"').join('\n'),'text/csv','regex-split.csv'); },
        downloadBlob(content,mime,name) { const url=URL.createObjectURL(new Blob([content],{type:mime+';charset=utf-8'}));const a=document.createElement('a');a.href=url;a.download=name;document.body.appendChild(a);a.click();a.remove();URL.revokeObjectURL(url); },

        async pasteTestText() { try {this.testText=await navigator.clipboard.readText();this.runTest();} catch {this.error='Clipboard access was not available. Paste the text manually.';} },
        openFile() { this.$refs.fileInput?.click(); },
        handleFile(event) { const file=event.target.files?.[0];if(!file)return;if(file.size>2*1024*1024){this.error='File is too large. Please choose a file up to 2 MB.';return;}const reader=new FileReader();reader.onload=()=>{this.testText=String(reader.result||'');this.runTest();};reader.onerror=()=>{this.error='The file could not be read.';};reader.readAsText(file); },
        handleDrop(event) { this.dragActive=false;const file=event.dataTransfer?.files?.[0];if(!file)return;if(file.size>2*1024*1024){this.error='File is too large. Please choose a file up to 2 MB.';return;}const reader=new FileReader();reader.onload=()=>{this.testText=String(reader.result||'');this.runTest();};reader.readAsText(file); },

        selectAllText() { const el=this.$refs.testText;if(el){el.focus();el.select();} },
        swapPatternAndText() { const old=this.pattern;this.pattern=this.testText;this.testText=old;this.runTest(); },
        clearAll() { this.pattern='';this.flags='gm';this.testText='';this.replacement='';this.matches=[];this.matchCount=0;this.captureGroupCount=0;this.highlightedText='';this.replacementResult='';this.error='';this.statusMessage='';this.lastElapsed=null;this.testCases=[{id:1,value:'',expected:'match',result:null}];this.$nextTick(()=>this.$refs.pattern?.focus()); },
        addTestCase() { this.testCases.push({id:this.nextTestId++,value:'',expected:'match',result:null}); },
        removeTestCase(index) { if(this.testCases.length===1){this.testCases[0].value='';this.testCases[0].result=null;return;}this.testCases.splice(index,1); },
        get passedTests() { return this.testCases.filter(test => test.result === true).length; },
        get failedTests() { return this.testCases.filter(test => test.result === false).length; },

        setReplacement(value) { this.replacement=value;this.runReplacement(); },
        previousMatch(){this.selectedMatchIndex=Math.max(0,this.selectedMatchIndex-1);},
        nextMatch(){this.selectedMatchIndex=Math.min(Math.max(0,this.matches.length-1),this.selectedMatchIndex+1);},
        lineNumber(index){return this.testText.slice(0,index).split('\n').length;},
        columnNumber(index){const last=this.testText.lastIndexOf('\n',Math.max(0,index-1));return index-(last+1)+1;},
        friendlyError(error){return String(error?.message||'Invalid regular expression.').replace(/^Invalid regular expression:\s*/i,'').replace(/^Invalid regular expression pattern:\s*/i,'');},
        escapeHtml(value){return String(value??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');},
        formatNumber(value){return new Intl.NumberFormat().format(value);},

        handleInput(){},
        handleGlobalShortcut(event){if(!(event.ctrlKey||event.metaKey))return;if(event.key==='Enter'){event.preventDefault();this.runTest();}if((event.ctrlKey||event.metaKey)&&event.key.toLowerCase()==='z'&&!event.shiftKey){event.preventDefault();this.undo();}if((event.ctrlKey||event.metaKey)&&(event.key.toLowerCase()==='y'||(event.key.toLowerCase()==='z'&&event.shiftKey))){event.preventDefault();this.redo();}},

        snapshot(){return {pattern:this.pattern,flags:this.flags,testText:this.testText,replacement:this.replacement};},
        pushHistory(initial=false){const value=this.snapshot();const serialized=JSON.stringify(value);if(!initial&&this.history[this.historyIndex]&&JSON.stringify(this.history[this.historyIndex])===serialized)return;if(this.historyIndex<this.history.length-1)this.history=this.history.slice(0,this.historyIndex+1);this.history.push(value);if(this.history.length>50)this.history.shift();this.historyIndex=this.history.length-1;},
        scheduleHistory(){clearTimeout(this.historyTimer);this.historyTimer=setTimeout(()=>this.pushHistory(),400);},
        restoreSnapshot(value){if(!value)return;this.pattern=value.pattern;this.flags=value.flags;this.testText=value.testText;this.replacement=value.replacement;this.$nextTick(()=>this.runTest());},
        undo(){if(this.historyIndex<=0)return;this.historyIndex--;this.restoreSnapshot(this.history[this.historyIndex]);},
        redo(){if(this.historyIndex>=this.history.length-1)return;this.historyIndex++;this.restoreSnapshot(this.history[this.historyIndex]);},

        destroy(){this.destroyWorker();clearTimeout(this.scheduleTimer);clearTimeout(this.historyTimer);}
    };
};
</script>
@endscript
