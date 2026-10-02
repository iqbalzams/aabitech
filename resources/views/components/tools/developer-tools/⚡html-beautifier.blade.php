<?php

use Livewire\Component;

new class extends Component
{
    // All HTML processing is performed locally in the browser.
};
?>

<div x-data="aabiHtmlBeautifier()" x-init="init()" x-cloak @keydown.window="handleShortcut($event)" class="w-full">
    <div class="w-full overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
        {{-- Toolbar --}}
        <div class="border-b border-zinc-200 bg-zinc-50/80 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="beautify()" :disabled="processing || !input.trim()" class="inline-flex items-center gap-2 rounded-lg bg-zinc-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-200">
                    <span x-text="processing ? 'Formatting…' : 'Format HTML'"></span>
                </button>
                <button type="button" @click="minify()" :disabled="processing || !input.trim()" class="rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">Minify</button>
                <button type="button" @click="validateHtml()" :disabled="processing || !input.trim()" class="rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Validate</button>
                <button type="button" @click="inspect()" :disabled="processing || !input.trim()" class="rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Inspect</button>
                <button type="button" @click="formatInline()" :disabled="processing || !input.trim()" class="rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Inline</button>
                <button type="button" @click="undo()" :disabled="!canUndo" class="rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Undo</button>
                <button type="button" @click="redo()" :disabled="!canRedo" class="rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Redo</button>
                <button type="button" @click="swap()" class="rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Swap</button>
                <button type="button" @click="copyOutput()" :disabled="!output" class="rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200"><span x-text="copied ? '✓ Copied to clipboard' : 'Copy'">Copy</span></button>
                <button type="button" @click="downloadOutput()" :disabled="!output" class="rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm font-semibold text-zinc-700 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">Download</button>
                <div class="flex-1"></div>
                <button type="button" @click="loadExample()" class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">Example</button>
                <button type="button" @click="triggerUpload()" class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">Upload</button>
                <button type="button" @click="clearAll()" class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800">Clear</button>
                <button type="button" @click="settingsOpen = !settingsOpen" :data-active-group="true" :class="{'is-active': settingsOpen}" class="compact-tab border border-zinc-300 bg-white dark:border-zinc-700 dark:bg-zinc-900">Settings</button>
                <input x-ref="fileInput" type="file" accept=".html,.htm,text/html" class="hidden" @change="handleFile($event)">
            </div>

            <div x-show="settingsOpen" x-transition @click.outside="settingsOpen = false" class="mt-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div><div class="text-sm font-semibold text-zinc-900 dark:text-white">Formatter settings</div><div class="text-xs text-zinc-500">Saved locally in this browser. HTML is never sent to AabiTech.</div></div>
                    <button type="button" @click="resetSettings()" class="text-xs font-medium text-zinc-500 hover:text-zinc-950 dark:hover:text-white">Reset</button>
                </div>
                <div class="mb-4 flex flex-wrap gap-1.5">
                    <template x-for="preset in presets" :key="preset.key"><button type="button" @click="applyPreset(preset.key)" :data-active-group="true" :class="{'is-active': activePreset === preset.key}" class="compact-tab border border-zinc-200 dark:border-zinc-700" x-text="preset.label"></button></template>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Indentation
                        <select x-model="settings.indentMode" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                            <option value="2">2 spaces</option><option value="4">4 spaces</option><option value="tab">Tabs</option><option value="custom">Custom</option>
                        </select>
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Custom size
                        <input type="number" min="1" max="12" x-model.number="settings.customIndent" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Wrap width
                        <input type="number" min="0" max="500" x-model.number="settings.wrapWidth" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white">
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Unformatted / inline tags
                        <input x-model="settings.unformatted" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white" placeholder="a,span,strong">
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Attribute wrapping
                        <select x-model="settings.attributeWrap" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"><option value="auto">Auto</option><option value="force">Force</option><option value="preserve">Preserve</option></select>
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Quote style
                        <select x-model="settings.quoteStyle" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"><option value="preserve">Preserve</option><option value="double">Double</option><option value="single">Single</option></select>
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Embedded CSS
                        <select x-model="settings.cssMode" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"><option value="preserve">Preserve</option><option value="format">Format</option><option value="inspect">Inspect</option></select>
                    </label>
                    <label class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Embedded JavaScript
                        <select x-model="settings.jsMode" @change="saveSettings()" class="mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-normal dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"><option value="preserve">Preserve</option><option value="format">Format</option><option value="inspect">Inspect</option></select>
                    </label>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.preserveNewlines" @change="saveSettings()"> Preserve line breaks</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.preserveIndent" @change="saveSettings()"> Preserve existing indentation</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.removeExtraLines" @change="saveSettings()"> Remove extra blank lines</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.preserveComments" @change="saveSettings()"> Preserve comments</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.sortAttributes" @change="saveSettings()"> Sort attributes</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.removeComments" @change="saveSettings()"> Remove comments</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.preserveDoctype" @change="saveSettings()"> Preserve DOCTYPE</label>
                    <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" x-model="settings.autoFormat" @change="saveSettings()"> Real-time formatting</label>
                </div>
            </div>
        </div>

        <div x-show="status || error" class="border-b border-zinc-200 px-4 py-3 dark:border-zinc-800">
            <div x-show="status" class="text-sm text-emerald-700 dark:text-emerald-400" x-text="status"></div>
            <div x-show="error" class="text-sm text-red-700 dark:text-red-400" x-text="error"></div>
        </div>

        {{-- Editors --}}
        <div class="grid lg:grid-cols-2">
            <section class="border-b border-zinc-200 lg:border-b-0 lg:border-r dark:border-zinc-800">
                <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-800">
                    <div><span class="text-sm font-semibold text-zinc-900 dark:text-white">Input HTML</span><span class="ml-2 text-xs text-zinc-500" x-text="inputStats.characters + ' chars · ' + inputStats.lines + ' lines · ' + inputStats.tags + ' tags'"></span></div>
                    <span class="text-xs text-zinc-500">Ctrl/Cmd+Enter</span>
                </div>
                <div class="relative" @dragover.prevent="dragging=true" @dragleave.prevent="dragging=false" @drop.prevent="handleDrop($event)">
                    <textarea x-ref="inputEditor" x-model="input" @input="onInput()" spellcheck="false" autocapitalize="off" autocomplete="off" autocorrect="off" placeholder="Paste or type your HTML here…" class="h-[650px] min-h-[520px] w-full resize-y border-0 bg-white p-5 font-mono text-[13px] leading-6 text-zinc-800 outline-none focus:ring-0 dark:bg-zinc-950 dark:text-zinc-200"></textarea>
                    <div x-show="dragging" class="pointer-events-none absolute inset-0 flex items-center justify-center bg-white/90 dark:bg-zinc-950/90"><div class="rounded-xl border-2 border-dashed border-zinc-400 px-8 py-6 text-center text-sm font-semibold dark:border-zinc-600">Drop .html or .htm file here</div></div>
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-2 border-t border-zinc-200 px-4 py-2.5 text-xs text-zinc-500 dark:border-zinc-800"><span>Lines <b x-text="inputStats.lines"></b></span><span>Bytes <b x-text="formatBytes(inputStats.bytes)"></b></span><span>Tags <b x-text="inputStats.tags"></b></span></div>
            </section>
            <section>
                <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-800"><div><span class="text-sm font-semibold text-zinc-900 dark:text-white">Output</span><span class="ml-2 text-xs text-zinc-500" x-text="outputStats.characters + ' chars · ' + outputStats.lines + ' lines · ' + outputStats.tags + ' tags'"></span></div><span x-show="output" class="text-xs font-semibold text-zinc-500" x-text="outputMode"></span></div>
                <div class="relative">
                    <textarea x-ref="outputEditor" x-model="output" readonly spellcheck="false" class="h-[650px] min-h-[520px] w-full resize-y border-0 bg-zinc-50 p-5 font-mono text-[13px] leading-6 text-zinc-800 outline-none focus:ring-0 dark:bg-zinc-900/50 dark:text-zinc-200" placeholder="Formatted HTML will appear here…"></textarea>
                    <div x-show="!output" class="pointer-events-none absolute inset-0 flex items-center justify-center"><div class="max-w-xs px-6 text-center"><div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">&lt;/&gt;</div><p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Your output will appear here</p><p class="mt-1 text-xs leading-5 text-zinc-500">Format, minify, or inspect the HTML from the toolbar.</p></div></div>
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-2 border-t border-zinc-200 px-4 py-2.5 text-xs text-zinc-500 dark:border-zinc-800"><span>Lines <b x-text="outputStats.lines"></b></span><span>Bytes <b x-text="formatBytes(outputStats.bytes)"></b></span><span x-show="sizeChange !== null" :class="sizeChange > 0 ? 'text-amber-600' : 'text-emerald-600'" x-text="(sizeChange >= 0 ? '+' : '') + sizeChange + '%'"> </span></div>
            </section>
        </div>

        {{-- Analysis / highlighting / comparison --}}
        <div class="border-t border-zinc-200 dark:border-zinc-800">
            <div class="flex flex-wrap items-center gap-1 border-b border-zinc-200 bg-zinc-50/60 p-2 dark:border-zinc-800 dark:bg-zinc-900/30">
                <template x-for="tab in analysisTabs" :key="tab.key"><button type="button" @click="analysisTab=tab.key" :data-active-group="true" :class="{'is-active': analysisTab===tab.key}" class="compact-tab" x-text="tab.label"></button></template>
            </div>

            <div x-show="analysisTab === 'highlight'" class="p-4">
                <div class="mb-2 text-xs text-zinc-500">Safe client-side syntax highlighting of the current output.</div>
                <pre class="max-h-[430px] overflow-auto rounded-xl border border-zinc-200 bg-zinc-950 p-4 font-mono text-xs leading-6 text-zinc-100 dark:border-zinc-800" x-html="highlightedOutput || '<span class=\'text-zinc-400\'>No output yet.</span>'"></pre>
            </div>

            <div x-show="analysisTab === 'tree'" class="p-4">
                <div class="mb-3 flex flex-wrap items-center gap-2"><button type="button" @click="inspect()" class="compact-tab border border-zinc-200 dark:border-zinc-700">Build tree</button><button type="button" @click="expandTree(true)" class="compact-tab">Expand all</button><button type="button" @click="expandTree(false)" class="compact-tab">Collapse all</button></div>
                <div x-show="tree.length" class="max-h-[460px] overflow-auto rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-800 dark:bg-zinc-900/40"><template x-for="node in tree" :key="node.id"><div class="mb-1"><div class="flex items-center gap-2 rounded-md px-2 py-1.5 text-xs hover:bg-white dark:hover:bg-zinc-800" :style="'padding-left:' + (node.depth * 18 + 8) + 'px'"><button type="button" @click="node.open=!node.open" class="h-5 w-5 rounded text-zinc-500" x-text="node.children.length ? (node.open ? '−' : '+') : '·'"></button><button type="button" @click="jumpToNode(node)" class="font-mono text-left text-zinc-800 hover:text-indigo-600 dark:text-zinc-200" x-text="node.label"></button><span class="ml-auto text-[10px] text-zinc-400" x-text="node.line ? 'L'+node.line : ''"></span></div><template x-if="node.open && node.children.length"><div><template x-for="child in node.children" :key="child.id"><div class="flex items-center gap-2 rounded-md px-2 py-1.5 text-xs hover:bg-white dark:hover:bg-zinc-800" :style="'margin-left:' + ((node.depth + 1) * 18 + 8) + 'px'"><span class="h-5 w-5 text-center text-zinc-400">·</span><button type="button" @click="jumpToNode(child)" class="font-mono text-left text-zinc-700 dark:text-zinc-300" x-text="child.label"></button><span class="ml-auto text-[10px] text-zinc-400" x-text="child.line ? 'L'+child.line : ''"></span></div></template></div></template></div></template></div><div x-show="!tree.length" class="rounded-xl border border-dashed border-zinc-300 p-8 text-center text-sm text-zinc-500 dark:border-zinc-700">Run Inspect to build the structure tree.</div>
            </div>

            <div x-show="analysisTab === 'validation'" class="p-4">
                <div class="grid gap-4 lg:grid-cols-[1fr_320px]">
                    <div><div class="mb-2 flex items-center justify-between"><span class="text-sm font-semibold text-zinc-900 dark:text-white">Diagnostics</span><button type="button" @click="validateHtml()" class="compact-tab border border-zinc-200 dark:border-zinc-700">Validate now</button></div><div class="space-y-2"><template x-for="(item,index) in diagnostics" :key="index"><button type="button" @click="jumpToDiagnostic(item)" class="flex w-full items-start gap-3 rounded-lg border border-zinc-200 bg-white p-3 text-left hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950 dark:hover:bg-zinc-900"><span class="mt-0.5 rounded-full px-2 py-0.5 text-[10px] font-semibold" :class="item.severity==='error' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700'" x-text="item.severity"></span><span class="min-w-0 flex-1"><span class="block text-xs font-medium text-zinc-800 dark:text-zinc-200" x-text="item.message"></span><span class="mt-1 block text-[11px] text-zinc-500" x-text="'Line ' + item.line + ', column ' + item.column"></span><span x-show="item.suggestion" class="mt-1 block text-[11px] text-indigo-600" x-text="item.suggestion"></span></span></button></template><div x-show="!diagnostics.length" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">No structural issues detected by the local analyzer.</div></div></div>
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/40"><div class="text-sm font-semibold text-zinc-900 dark:text-white">Structure summary</div><div class="mt-3 grid grid-cols-2 gap-2 text-xs"><div>Elements <b x-text="analysis.elements"></b></div><div>Depth <b x-text="analysis.maxDepth"></b></div><div>Comments <b x-text="analysis.comments"></b></div><div>Images <b x-text="analysis.images"></b></div><div>Links <b x-text="analysis.links"></b></div><div>Forms <b x-text="analysis.forms"></b></div></div></div>
                </div>
            </div>

            <div x-show="analysisTab === 'accessibility'" class="p-4"><div class="grid gap-4 lg:grid-cols-2"><div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-800"><div class="text-sm font-semibold text-zinc-900 dark:text-white">Accessibility checks</div><ul class="mt-3 space-y-2 text-xs text-zinc-600 dark:text-zinc-300"><template x-for="(item,index) in accessibilityIssues" :key="index"><li class="flex gap-2"><span class="text-amber-600">●</span><span x-text="item"></span></li></template><li x-show="!accessibilityIssues.length" class="text-emerald-600">No issues detected by these heuristic checks.</li></ul></div><div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-800"><div class="text-sm font-semibold text-zinc-900 dark:text-white">Semantic analysis</div><ul class="mt-3 space-y-2 text-xs text-zinc-600 dark:text-zinc-300"><template x-for="(item,index) in semanticIssues" :key="index"><li class="flex gap-2"><span class="text-amber-600">●</span><span x-text="item"></span></li></template><li x-show="!semanticIssues.length" class="text-emerald-600">No semantic warnings detected by these heuristic checks.</li></ul></div></div></div>

            <div x-show="analysisTab === 'embedded'" class="p-4"><div class="grid gap-4 lg:grid-cols-2"><div class="rounded-xl border border-zinc-200 dark:border-zinc-800"><div class="border-b border-zinc-200 px-4 py-3 text-sm font-semibold dark:border-zinc-800">Embedded CSS <span class="text-xs font-normal text-zinc-500" x-text="embedded.css.length + ' block(s)'"></span></div><pre class="max-h-[360px] overflow-auto p-4 font-mono text-xs leading-6 text-zinc-700 dark:text-zinc-300" x-text="embedded.css.join('\n\n') || 'No style blocks found.'"></pre></div><div class="rounded-xl border border-zinc-200 dark:border-zinc-800"><div class="border-b border-zinc-200 px-4 py-3 text-sm font-semibold dark:border-zinc-800">Embedded JavaScript <span class="text-xs font-normal text-zinc-500" x-text="embedded.js.length + ' block(s)'"></span></div><pre class="max-h-[360px] overflow-auto p-4 font-mono text-xs leading-6 text-zinc-700 dark:text-zinc-300" x-text="embedded.js.join('\n\n') || 'No script blocks found.'"></pre></div></div></div>

            <div x-show="analysisTab === 'compare'" class="p-4"><div class="grid gap-4 lg:grid-cols-2"><div><div class="mb-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300">Original</div><pre class="max-h-[420px] overflow-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-6 dark:border-zinc-800 dark:bg-zinc-900/40" x-text="input || 'No input.'"></pre></div><div><div class="mb-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300">Formatted</div><pre class="max-h-[420px] overflow-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-6 dark:border-zinc-800 dark:bg-zinc-900/40" x-text="output || 'Format the HTML first.'"></pre></div></div></div>
        </div>

        <div class="border-t border-zinc-200 bg-zinc-50/60 p-3 text-xs text-zinc-500 dark:border-zinc-800 dark:bg-zinc-900/30">
            <div class="flex flex-wrap items-center justify-between gap-2"><span>Fully client-side processing · HTML is not transmitted to AabiTech.</span><span>Max document size: <b>5 MB</b></span><button type="button" @click="copyShareLink()" class="compact-tab border border-zinc-200 dark:border-zinc-700"><span x-text="shareCopied ? '✓ Copied to clipboard' : 'Share settings'"></span></button></div>
        </div>
    </div>
</div>

@script
<script>
window.aabiHtmlBeautifier = function () {
    return {
        input: '', output: '', outputMode: 'Formatted', processing: false, dragging: false,
        status: '', error: '', copied: false, shareCopied: false, settingsOpen: false,
        analysisTab: 'highlight', highlightedOutput: '', sizeChange: null,
        diagnostics: [], tree: [], accessibilityIssues: [], semanticIssues: [],
        embedded: { css: [], js: [] },
        analysis: { elements: 0, maxDepth: 0, comments: 0, images: 0, links: 0, forms: 0 },
        history: [], historyIndex: -1, historyTimer: null, realtimeTimer: null,
        activePreset: 'balanced',
        maxDocumentBytes: 5 * 1024 * 1024,
        inputStats: { characters: 0, lines: 0, bytes: 0, tags: 0 },
        outputStats: { characters: 0, lines: 0, bytes: 0, tags: 0 },
        defaults: {
            indentMode: '2', customIndent: 2, wrapWidth: 0, unformatted: 'a,span,strong,em,b,i,small,label',
            attributeWrap: 'auto', quoteStyle: 'preserve', preserveNewlines: true, preserveIndent: false,
            removeExtraLines: true, preserveComments: true, removeComments: false, preserveDoctype: true,
            sortAttributes: false, cssMode: 'preserve', jsMode: 'preserve', autoFormat: false,
        },
        settings: {},
        presets: [
            { key: 'balanced', label: 'Balanced' }, { key: 'compact', label: 'Compact' },
            { key: 'readable', label: 'Readable' }, { key: 'strict', label: 'Strict' },
        ],
        analysisTabs: [
            { key: 'highlight', label: 'Highlight' }, { key: 'tree', label: 'Structure Tree' },
            { key: 'validation', label: 'Validation' }, { key: 'accessibility', label: 'Accessibility' },
            { key: 'embedded', label: 'CSS / JS' }, { key: 'compare', label: 'Compare' },
        ],

        init() {
            this.settings = { ...this.defaults };
            this.loadSettings();
            this.loadShareSettings();
            this.updateStats();
            this.$nextTick(() => this.$refs.inputEditor?.focus());
        },

        onInput() {
            this.clearMessages();
            this.updateStats();
            this.pushHistoryDebounced(this.input);
            if (this.settings.autoFormat) {
                clearTimeout(this.realtimeTimer);
                this.realtimeTimer = setTimeout(() => { if (this.input.trim()) this.beautify(true); }, 350);
            }
        },

        async beautify(silent = false) {
            if (!silent) this.clearMessages();
            if (!this.guardInput()) return;
            this.processing = true;
            try {
                let source = this.input;
                if (this.settings.removeComments) source = this.removeHtmlComments(source);
                source = this.normalizeSource(source);
                let result = this.formatHtml(source);
                result = this.processEmbedded(result);
                this.output = this.settings.endNewline === false ? result.replace(/\s+$/, '') : result.replace(/\s+$/, '') + '\n';
                this.outputMode = 'Beautified';
                this.updateStats();
                this.highlightedOutput = this.highlightHtml(this.output);
                this.runAnalysis(this.output || this.input);
                if (!silent) this.status = 'HTML formatted successfully.';
            } catch (e) {
                console.error(e);
                if (!silent) this.showError(e?.message || 'Unable to format the supplied HTML.');
            } finally { this.processing = false; }
        },

        minify() {
            this.clearMessages();
            if (!this.guardInput()) return;
            try {
                let source = this.input;
                const protectedBlocks = [];
                source = this.protectSensitiveBlocks(source, protectedBlocks);
                if (this.settings.removeComments || !this.settings.preserveComments) source = this.removeHtmlComments(source);
                source = source.replace(/>\s+</g, '><').replace(/[ \t\r\n]+/g, ' ').trim();
                source = this.restoreSensitiveBlocks(source, protectedBlocks).trim();
                if (!this.settings.preserveDoctype) source = source.replace(/<!doctype[^>]*>/i, '').trim();
                this.output = source + (source && this.settings.endNewline !== false ? '\n' : '');
                this.outputMode = 'Minified';
                this.updateStats();
                this.highlightedOutput = this.highlightHtml(this.output);
                this.status = 'HTML minified successfully.';
            } catch (e) { console.error(e); this.showError('Unable to minify the supplied HTML.'); }
        },

        formatInline() {
            this.clearMessages();
            if (!this.guardInput()) return;
            try {
                const saved = this.settings.keepInline;
                this.settings.keepInline = true;
                const source = this.normalizeSource(this.input);
                let result = this.formatHtml(source).replace(/\n\s+/g, ' ').replace(/\s{2,}/g, ' ').replace(/>\s+</g, '><').trim();
                this.settings.keepInline = saved;
                this.output = result + (this.settings.endNewline !== false ? '\n' : '');
                this.outputMode = 'Inline'; this.updateStats(); this.highlightedOutput = this.highlightHtml(this.output);
                this.status = 'Compact inline HTML generated.';
            } catch (e) { console.error(e); this.showError('Unable to create inline HTML output.'); }
        },

        formatHtml(source) {
            const indentUnit = this.getIndentUnit();
            const inline = new Set(this.csvTags(this.settings.unformatted));
            const voidTags = new Set(['area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr']);
            const rawTags = new Set(['pre','textarea']);
            const tokens = this.tokenize(source);
            const lines = []; let depth = 0; let i = 0;
            const push = (text, level = depth) => { if (text !== '') lines.push(indentUnit.repeat(Math.max(0, level)) + text); };
            while (i < tokens.length) {
                const token = tokens[i];
                if (token.type === 'text') {
                    const value = token.value.replace(/\s+/g, ' ').trim();
                    if (!value) { i++; continue; }
                    if (inline.size && lines.length && this.settings.keepInline !== false && this.isInsideInlineContext(tokens, i, inline)) {
                        lines[lines.length - 1] += ' ' + value;
                    } else push(value);
                    i++; continue;
                }
                if (token.type === 'comment') { if (this.settings.preserveComments && !this.settings.removeComments) push(token.value); i++; continue; }
                if (token.type === 'doctype') { if (this.settings.preserveDoctype) push(token.value); i++; continue; }
                if (token.type === 'close') { depth = Math.max(0, depth - 1); push(token.value); i++; continue; }
                if (token.type === 'open') {
                    const tag = token.name;
                    let opening = this.formatOpeningTag(token.value);
                    const next = tokens[i + 1];
                    const isVoid = token.selfClosing || voidTags.has(tag);
                    const isInline = inline.has(tag);
                    if (rawTags.has(tag) && next?.type === 'text') {
                        push(opening + next.value + (tokens[i + 2]?.type === 'close' ? tokens[i + 2].value : ''));
                        i += tokens[i + 2]?.type === 'close' ? 3 : 2; continue;
                    }
                    if (isInline && next?.type === 'text' && tokens[i + 2]?.type === 'close' && inline.has(tokens[i + 2].name)) {
                        const content = next.value.replace(/\s+/g, ' ').trim();
                        push(opening + content + tokens[i + 2].value); i += 3; continue;
                    }
                    if (this.settings.attributeWrap === 'force' && this.shouldWrapAttributes(opening)) {
                        opening = this.wrapAttributes(opening, indentUnit.repeat(depth));
                    }
                    push(opening);
                    if (!isVoid) depth++;
                }
                i++;
            }
            let result = lines.join('\n');
            if (this.settings.preserveNewlines && !this.settings.removeExtraLines) result = this.restoreSomeBlankLines(source, result);
            if (this.settings.removeExtraLines) result = result.replace(/\n{3,}/g, '\n\n');
            return result;
        },

        tokenize(source) {
            const tokens = []; let pos = 0;
            const tagRe = /<!--[\s\S]*?-->|<!doctype\b[\s\S]*?>|<\/?[A-Za-z][^>]*>/gi;
            let m;
            while ((m = tagRe.exec(source))) {
                if (m.index > pos) tokens.push({ type: 'text', value: source.slice(pos, m.index) });
                const raw = m[0];
                if (/^<!--/.test(raw)) tokens.push({ type: 'comment', value: raw });
                else if (/^<!doctype/i.test(raw)) tokens.push({ type: 'doctype', value: raw });
                else if (/^<\//.test(raw)) tokens.push({ type: 'close', value: raw, name: this.tagName(raw) });
                else tokens.push({ type: 'open', value: raw, name: this.tagName(raw), selfClosing: /\/\s*>$/.test(raw) });
                pos = m.index + raw.length;
            }
            if (pos < source.length) tokens.push({ type: 'text', value: source.slice(pos) });
            return tokens;
        },

        formatOpeningTag(raw) {
            let value = raw.replace(/[\t\r\n ]+/g, ' ').replace(/\s+>/, '>').trim();
            if (this.settings.quoteStyle !== 'preserve') {
                const quote = this.settings.quoteStyle === 'single' ? "'" : '"';
                value = value.replace(/=\s*(["'])(.*?)\1/g, (m, q, v) => '=' + quote + v.replace(new RegExp(quote, 'g'), quote === '"' ? '&quot;' : '&#39;') + quote);
            }
            if (this.settings.sortAttributes) value = this.sortTagAttributes(value);
            return value;
        },

        sortTagAttributes(tag) {
            const match = tag.match(/^<\s*([^\s/>]+)/); if (!match) return tag;
            const name = match[1]; const body = tag.slice(match[0].length, -1).trim(); if (!body) return '<' + name + '>';
            const attrs = []; const re = /([^\s=]+)(?:\s*=\s*("[^"]*"|'[^']*'|[^\s]+))?/g; let m;
            while ((m = re.exec(body))) attrs.push(m[0]);
            attrs.sort((a,b) => a.toLowerCase().localeCompare(b.toLowerCase()));
            return '<' + name + ' ' + attrs.join(' ') + (tag.endsWith('/>') ? '/>' : '>');
        },

        shouldWrapAttributes(tag) { return this.settings.wrapWidth > 0 && tag.length > this.settings.wrapWidth; },
        wrapAttributes(tag, baseIndent) {
            const match = tag.match(/^<\s*([^\s/>]+)\s+(.*?)(\/?)>$/); if (!match) return tag;
            const attrs = []; const re = /([^\s=]+)(?:\s*=\s*("[^"]*"|'[^']*'|[^\s]+))?/g; let m; while ((m = re.exec(match[2]))) attrs.push(m[0]);
            return '<' + match[1] + '\n' + attrs.map((a) => baseIndent + this.getIndentUnit() + a).join('\n') + '\n' + baseIndent + match[3] + '>';
        },

        isInsideInlineContext(tokens, index, inline) {
            let depth = 0;
            for (let i = 0; i < index; i++) { if (tokens[i].type === 'open' && inline.has(tokens[i].name)) depth++; if (tokens[i].type === 'close' && inline.has(tokens[i].name)) depth--; }
            return depth > 0;
        },

        restoreSomeBlankLines(source, result) {
            const blanks = (source.match(/\n\s*\n/g) || []).length; if (!blanks) return result;
            return result.replace(/\n/g, '\n');
        },

        protectSensitiveBlocks(source, storage) {
            const names = ['pre','textarea','script','style']; let result = source;
            names.forEach(name => {
                const re = new RegExp('<' + name + '\\b[^>]*>[\\s\\S]*?<' + '[\\/]'+ name + '\\s*>', 'gi');
                result = result.replace(re, block => { const token = '__AABI_PROTECTED_' + storage.length + '__'; storage.push({token, block}); return token; });
            });
            return result;
        },
        restoreSensitiveBlocks(source, storage) { let result = source; storage.forEach(x => { result = result.split(x.token).join(x.block); }); return result; },
        removeHtmlComments(source) { return source.replace(/<!--(?!\s*\[if)[\s\S]*?-->/gi, ''); },
        normalizeSource(source) { return source.replace(/\r\n?/g, '\n').trim(); },

        processEmbedded(html) {
            let result = html;
            if (this.settings.cssMode === 'format' && typeof window.aabiCssBeautify === 'function') {
                result = result.replace(new RegExp('<style\\b([^>]*)>([\\s\\S]*?)<' + '[\\/]style\\s*>', 'gi'), (all, attrs, body) => '<style' + attrs + '>' + window.aabiCssBeautify(body) + '<' + '/style>');
            }
            if (this.settings.jsMode === 'format' && typeof window.aabiJsBeautify === 'function') {
                result = result.replace(new RegExp('<script\\b([^>]*)>([\\s\\S]*?)<' + '[\\/]script\\s*>', 'gi'), (all, attrs, body) => '<script' + attrs + '>' + window.aabiJsBeautify(body) + '<' + '/script>');
            }
            return result;
        },

        validateHtml() {
            this.clearMessages(); if (!this.guardInput()) return;
            const result = this.runValidation(this.input); this.diagnostics = result.errors;
            this.analysisTab = 'validation'; this.runAnalysis(this.input);
            if (!result.errors.length) this.status = 'HTML structure looks balanced. ' + result.tags + ' tags checked.';
            else this.showError(result.errors.length + ' structural issue(s) detected. First: ' + result.errors[0].message);
        },

        runValidation(html) {
            const errors = [], stack = [], seenAt = new Map(); const voids = new Set(['area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr']);
            const tagRe = /<!--[\s\S]*?-->|<!doctype\b[\s\S]*?>|<\/?[A-Za-z][^>]*>/gi; let m, tags = 0;
            while ((m = tagRe.exec(html))) {
                const raw = m[0]; if (/^<!--|^<!doctype/i.test(raw)) continue;
                const lineInfo = this.positionAt(html, m.index); const name = this.tagName(raw); tags++;
                if (!/^<\//.test(raw)) {
                    const attrs = this.parseAttributes(raw); const duplicates = this.duplicateAttributes(attrs);
                    duplicates.forEach(a => errors.push({severity:'error', message:'Duplicate attribute "' + a + '" on <' + name + '>.', line:lineInfo.line, column:lineInfo.column, suggestion:'Remove or merge the duplicate attribute.'}));
                    if (!voids.has(name) && !/\/\s*>$/.test(raw)) stack.push({name, index:m.index, line:lineInfo.line, column:lineInfo.column});
                } else {
                    const top = stack[stack.length - 1];
                    if (!top) errors.push({severity:'error', message:'Unexpected closing tag </' + name + '>.', line:lineInfo.line, column:lineInfo.column, suggestion:'Remove the closing tag or add its matching opening tag.'});
                    else if (top.name !== name) {
                        const found = stack.findIndex(x => x.name === name);
                        errors.push({severity:'error', message:'Incorrect nesting: </' + name + '> closes while <' + top.name + '> is still open.', line:lineInfo.line, column:lineInfo.column, suggestion:found >= 0 ? 'Close <' + top.name + '> before </' + name + '>.' : 'Check the opening and closing tags.'});
                        if (found >= 0) stack.splice(found); else stack.pop();
                    } else stack.pop();
                }
            }
            stack.reverse().forEach(x => errors.push({severity:'error', message:'Unclosed tag <' + x.name + '>.', line:x.line, column:x.column, suggestion:'Add </' + x.name + '> or remove the unmatched opening tag.'}));
            if (!/<!doctype\s+html/i.test(html) && /<html\b/i.test(html)) { const p=this.positionAt(html, Math.max(0, html.search(/<html\b/i))); errors.push({severity:'warning', message:'HTML document has no HTML5 DOCTYPE.', line:p.line, column:p.column, suggestion:'Consider adding <!DOCTYPE html> at the beginning.'}); }
            return { errors, tags };
        },

        duplicateAttributes(attrs) { const seen = new Set(), dup = []; attrs.forEach(a => { const k=a.name.toLowerCase(); if (seen.has(k)) dup.push(k); else seen.add(k); }); return [...new Set(dup)]; },
        parseAttributes(raw) { const body=raw.replace(/^<\/?[^\s/>]+|\/?>$/g,''); const out=[]; const re=/([^\s=]+)(?:\s*=\s*("[^"]*"|'[^']*'|[^\s]+))?/g; let m; while((m=re.exec(body))) out.push({name:m[1]}); return out; },
        positionAt(text,index) { const before=text.slice(0,index); const line=(before.match(/\n/g)||[]).length+1; const last=before.lastIndexOf('\n'); return {line,column:index-(last<0?-1:last)}; },
        tagName(raw) { const m=raw.match(/^<\/?\s*([A-Za-z][\w:-]*)/); return m ? m[1].toLowerCase() : ''; },

        inspect() {
            if (!this.guardInput()) return;
            this.analysisTab='tree'; this.runAnalysis(this.input); this.tree=this.buildTree(this.input); this.status='HTML structure inspected.';
        },

        buildTree(html) {
            const root=[]; const stack=[{children:root}]; let id=0; const re=/<!--[\s\S]*?-->|<\/?[A-Za-z][^>]*>/gi; let m;
            while((m=re.exec(html))){ const raw=m[0]; if(/^<!--/.test(raw)){ stack[stack.length-1].children.push({id:id++,label:'<!-- comment -->',children:[],open:false,depth:stack.length-1,line:this.positionAt(html,m.index).line}); continue; } const name=this.tagName(raw); if(/^<\//.test(raw)){ if(stack.length>1) stack.pop(); continue; } const node={id:id++,label:'<' + name + '>',children:[],open:true,depth:stack.length-1,line:this.positionAt(html,m.index).line}; stack[stack.length-1].children.push(node); if(!/\/>$/.test(raw) && !['area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr'].includes(name)) stack.push(node); }
            return root;
        },
        expandTree(open) { const walk=nodes=>nodes.forEach(n=>{n.open=open;if(n.children.length)walk(n.children);}); walk(this.tree); this.tree=[...this.tree]; },
        jumpToNode(node) { this.analysisTab='validation'; const item=this.diagnostics.find(x=>x.line===node.line); if(item) this.jumpToDiagnostic(item); else this.jumpToLine(node.line); },
        jumpToDiagnostic(item) { this.jumpToLine(item.line, item.column); },
        jumpToLine(line,column=1) { const editor=this.$refs.inputEditor; if(!editor) return; const lines=this.input.split('\n'); let pos=0; for(let i=0;i<Math.max(0,line-1);i++)pos+=lines[i].length+1; pos+=Math.max(0,column-1); editor.focus(); editor.setSelectionRange(pos,Math.min(this.input.length,pos+1)); editor.scrollTop=Math.max(0,(line-4)*editor.clientHeight/10); },

        runAnalysis(html) {
            this.analysis={elements:0,maxDepth:0,comments:(html.match(/<!--/g)||[]).length,images:0,links:0,forms:0};
            const counts={}; let depth=0; const voids=new Set(['area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr']); const re=/<!--[\s\S]*?-->|<\/?([A-Za-z][\w:-]*)\b[^>]*>/gi; let m;
            while((m=re.exec(html))){ if(!m[1])continue; const raw=m[0],name=m[1].toLowerCase(); if(/^<\//.test(raw)){depth=Math.max(0,depth-1);continue;} this.analysis.elements++; counts[name]=(counts[name]||0)+1; if(name==='img')this.analysis.images++; if(name==='a')this.analysis.links++; if(name==='form')this.analysis.forms++; if(!voids.has(name)&&!/\/\s*>$/.test(raw)){depth++;this.analysis.maxDepth=Math.max(this.analysis.maxDepth,depth);} }
            this.analyzeSemantics(html); this.extractEmbedded(html); this.highlightedOutput=this.highlightHtml(this.output||html);
        },

        analyzeSemantics(html) {
            const issues=[]; const headings=[...html.matchAll(/<h([1-6])\b[^>]*>/gi)].map(m=>Number(m[1])); for(let i=1;i<headings.length;i++) if(headings[i]>headings[i-1]+1) issues.push('Heading hierarchy jumps from h'+headings[i-1]+' to h'+headings[i]+'.');
            if(/<div[^>]*onclick=/i.test(html)) issues.push('Clickable behavior appears to rely on a non-semantic div.'); if(!/<main\b/i.test(html)&&/<body\b/i.test(html)) issues.push('No <main> landmark detected.'); this.semanticIssues=issues;
            const a=[]; const imgRe=/<img\b([^>]*)>/gi; let m; while((m=imgRe.exec(html))) if(!/\balt\s*=/i.test(m[1])) a.push('Image near line '+this.positionAt(html,m.index).line+' is missing an alt attribute.');
            const labelIds=new Set([...html.matchAll(/<label\b[^>]*\bfor\s*=\s*["']([^"']+)["']/gi)].map(x=>x[1])); const inputs=[...html.matchAll(/<(input|select|textarea)\b([^>]*)>/gi)]; inputs.forEach(x=>{if(/type\s*=\s*["'](hidden|submit|button|reset|image)["']/i.test(x[2]))return; const id=(x[2].match(/\bid\s*=\s*["']([^"']+)["']/i)||[])[1]; if(!id||!labelIds.has(id)) a.push(x[1]+' near line '+this.positionAt(html,x.index).line+' may be missing an associated label.');}); this.accessibilityIssues=a;
        },

        extractEmbedded(html) {
            this.embedded.css=[]; this.embedded.js=[]; let m; const cssRe=new RegExp('<style\\b[^>]*>([\\s\\S]*?)<'+'[\\/]style\\s*>','gi'); while((m=cssRe.exec(html)))this.embedded.css.push(m[1].trim()); const jsRe=new RegExp('<script\\b[^>]*>([\\s\\S]*?)<'+'[\\/]script\\s*>','gi'); while((m=jsRe.exec(html)))this.embedded.js.push(m[1].trim());
        },

        highlightHtml(html) {
            if(!html)return ''; let s=this.escapeHtml(html); s=s.replace(/(&lt;!--[\s\S]*?--&gt;)/g,'<span class="text-zinc-500">$1</span>'); s=s.replace(/(&lt;\/?)([A-Za-z][\w:-]*)([^&]*?)(\/?>)/g,'$1<span class="text-indigo-300">$2</span>$3$4'); s=s.replace(/([A-Za-z_:][\w:.-]*)(=)(&quot;.*?&quot;|&#39;.*?&#39;)/g,'<span class="text-sky-300">$1</span>$2<span class="text-emerald-300">$3</span>'); return s;
        },
        escapeHtml(value){return String(value).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');},

        loadExample() {
            const closeScript='<' + '/script>';
            this.input='<!DOCTYPE html>\n<html lang="en">\n<head>\n<meta charset="UTF-8">\n<meta name="viewport" content="width=device-width, initial-scale=1.0">\n<title>AabiTech Example</title>\n<style>body { font-family: Arial, sans-serif; margin: 0; padding: 2rem; } .container { max-width: 800px; margin: auto; }</style>\n</head>\n<body>\n<div class="container" id="main-content">\n<h1>HTML Beautifier</h1>\n<p class="intro">Format and inspect HTML locally in your browser.</p>\n<img src="example.jpg">\n<form><input id="email" name="email" type="email"><button type="submit">Send</button></form>\n<script>const message = "Hello AabiTech"; console.log(message);' + closeScript + '\n</div>\n</body>\n</html>';
            this.output=''; this.updateStats(); this.pushHistory(this.input); this.clearMessages(); this.status='Example HTML loaded.'; this.$nextTick(()=>this.$refs.inputEditor?.focus());
        },
        clearAll(){this.input='';this.output='';this.outputMode='Formatted';this.diagnostics=[];this.tree=[];this.highlightedOutput='';this.sizeChange=null;this.clearMessages();this.updateStats();this.pushHistory('');this.$nextTick(()=>this.$refs.inputEditor?.focus());},
        swap(){if(!this.input&&!this.output)return;const x=this.input;this.input=this.output;this.output=x;this.outputMode='Swapped';this.updateStats();this.highlightedOutput=this.highlightHtml(this.output);this.status='Input and output swapped.';},

        async copyOutput(){if(!this.output)return;try{await this.copyText(this.output);this.copied=true;this.status='Copied to clipboard.';setTimeout(()=>this.copied=false,1600);}catch(e){this.showError('Unable to copy the output.');}},
        async copyShareLink(){try{const payload={settings:this.settings};const encoded=btoa(unescape(encodeURIComponent(JSON.stringify(payload))));const url=location.origin+location.pathname+'#html-settings='+encoded;await this.copyText(url);this.shareCopied=true;this.status='Shareable settings link copied. HTML content was not included.';setTimeout(()=>this.shareCopied=false,1600);}catch(e){this.showError('Unable to create the settings link.');}},
        async copyText(text){if(navigator.clipboard&&window.isSecureContext){await navigator.clipboard.writeText(text);return;}const t=document.createElement('textarea');t.value=text;t.style.position='fixed';t.style.opacity='0';document.body.appendChild(t);t.focus();t.select();if(!document.execCommand('copy'))throw new Error('Copy failed');t.remove();},
        downloadOutput(){if(!this.output)return;const blob=new Blob([this.output],{type:'text/html;charset=utf-8'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download=this.outputMode==='Minified'?'minified.html':'beautified.html';document.body.appendChild(a);a.click();a.remove();setTimeout(()=>URL.revokeObjectURL(url),1000);this.status='HTML file downloaded successfully.';},

        triggerUpload(){this.$refs.fileInput?.click();},
        async handleFile(event){const file=event?.target?.files?.[0];if(file)await this.readFile(file);event.target.value='';},
        async handleDrop(event){this.dragging=false;const file=event?.dataTransfer?.files?.[0];if(file)await this.readFile(file);},
        async readFile(file){this.clearMessages();if(file.size>this.maxDocumentBytes){this.showError('File is too large. Maximum allowed size is 5 MB.');return;}if(!file.name.toLowerCase().endsWith('.html')&&!file.name.toLowerCase().endsWith('.htm')&&file.type!=='text/html'){this.showError('Please select an HTML file.');return;}try{this.input=await file.text();this.output='';this.updateStats();this.pushHistory(this.input);this.status=file.name+' loaded successfully.';}catch(e){this.showError('Unable to read the selected file.');}},

        pushHistory(value){if(this.history[this.historyIndex]===value)return;this.history=this.history.slice(0,this.historyIndex+1);this.history.push(value);if(this.history.length>50)this.history.shift();this.historyIndex=this.history.length-1;},
        pushHistoryDebounced(value){clearTimeout(this.historyTimer);this.historyTimer=setTimeout(()=>this.pushHistory(value),300);},
        undo(){if(this.historyIndex<=0)return;this.historyIndex--;this.input=this.history[this.historyIndex];this.updateStats();this.clearMessages();},
        redo(){if(this.historyIndex>=this.history.length-1)return;this.historyIndex++;this.input=this.history[this.historyIndex];this.updateStats();this.clearMessages();},
        get canUndo(){return this.historyIndex>0;}, get canRedo(){return this.historyIndex>=0&&this.historyIndex<this.history.length-1;},

        getIndentUnit(){if(this.settings.indentMode==='tab')return '\t';const n=this.settings.indentMode==='custom'?Number(this.settings.customIndent):Number(this.settings.indentMode);return ' '.repeat(Math.max(1,Math.min(12,n||2)));},
        csvTags(value){return String(value||'').split(',').map(x=>x.trim().toLowerCase()).filter(Boolean);},
        saveSettings(){try{localStorage.setItem('aabitech_html_beautifier_settings',JSON.stringify(this.settings));}catch(e){}},
        loadSettings(){try{const saved=JSON.parse(localStorage.getItem('aabitech_html_beautifier_settings')||'null');if(saved&&typeof saved==='object')this.settings={...this.defaults,...saved};}catch(e){this.settings={...this.defaults};}},
        resetSettings(){this.settings={...this.defaults};this.activePreset='balanced';this.saveSettings();this.status='Formatter settings reset.';},
        applyPreset(key){this.activePreset=key;if(key==='compact')this.settings={...this.settings,indentMode:'2',wrapWidth:0,preserveNewlines:false,preserveIndent:false,removeExtraLines:true};else if(key==='readable')this.settings={...this.settings,indentMode:'4',wrapWidth:100,preserveNewlines:true,preserveIndent:false,removeExtraLines:false};else if(key==='strict')this.settings={...this.settings,indentMode:'2',wrapWidth:100,preserveNewlines:false,preserveIndent:false,removeExtraLines:true,sortAttributes:true,quoteStyle:'double'};else this.settings={...this.defaults};this.saveSettings();this.status=key.charAt(0).toUpperCase()+key.slice(1)+' preset applied.';},
        loadShareSettings(){try{const prefix='#html-settings=';if(!location.hash.startsWith(prefix))return;const json=decodeURIComponent(escape(atob(location.hash.slice(prefix.length))));const data=JSON.parse(json);if(data.settings)this.settings={...this.settings,...data.settings};}catch(e){}},

        updateStats(){this.inputStats=this.calculateStats(this.input);this.outputStats=this.calculateStats(this.output);this.sizeChange=this.input&&this.output?Math.round(((this.outputStats.bytes-this.inputStats.bytes)/this.inputStats.bytes)*100):null;},
        calculateStats(value){if(!value)return{characters:0,lines:0,bytes:0,tags:0};return{characters:value.length,lines:value.split(/\r\n|\r|\n/).length,bytes:new TextEncoder().encode(value).length,tags:(value.match(/<\s*\/?\s*[A-Za-z][^>]*>/g)||[]).length};},
        formatBytes(bytes){if(bytes<1024)return bytes+' B';if(bytes<1048576)return(bytes/1024).toFixed(1)+' KB';return(bytes/1048576).toFixed(2)+' MB';},
        guardInput(){if(!this.input.trim()){this.showError('Please enter some HTML code first.');return false;}if(new TextEncoder().encode(this.input).length>this.maxDocumentBytes){this.showError('Document is larger than the 5 MB processing limit.');return false;}return true;},
        clearMessages(){this.status='';this.error='';},
        showError(message){this.status='';this.error=message;clearTimeout(this.errorTimer);this.errorTimer=setTimeout(()=>{if(this.error===message)this.error='';},7000);},

        handleShortcut(e){if(!(e.ctrlKey||e.metaKey))return;const k=e.key.toLowerCase();if(k==='enter'){e.preventDefault();this.beautify();}else if(k==='m'){e.preventDefault();this.minify();}else if(k==='z'){e.preventDefault();e.shiftKey?this.redo():this.undo();}else if(k==='y'){e.preventDefault();this.redo();}},
    };
};
</script>
@endscript
