<?php

use Livewire\Component;

new class extends Component
{
    // Conversion is intentionally browser-only. No HTML is submitted to Livewire.
};
?>

<div
    x-data="tailwindEmailConverter()"
    x-cloak
    class="w-full tailwind-email-tool"
>
    <style>
        .tailwind-email-tool .tool-tab {
            min-height: 32px;
            padding: 0 11px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .tailwind-email-tool .tool-tab[aria-selected="true"] {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 2px rgb(15 23 42 / 0.14);
        }

        .tailwind-email-tool .tool-tab[aria-selected="false"] {
            color: #cbd5e1;
        }

        .tailwind-email-tool .tool-tab[aria-selected="false"]:hover {
            background: #334155;
            color: #ffffff;
        }

        .tailwind-email-tool textarea,
        .tailwind-email-tool pre,
        .tailwind-email-tool .code-highlight {
            tab-size: 2;
            font-variant-ligatures: none;
        }

        .tailwind-email-tool .code-editor {
            position: relative;
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr);
            height: 430px;
            overflow: hidden;
            border: 1px solid #334155;
            border-radius: 10px;
            background: #020617;
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 0.03);
        }

        .tailwind-email-tool .code-gutter {
            position: relative;
            z-index: 4;
            overflow: hidden;
            padding: 12px 9px 12px 0;
            border-right: 1px solid #1e293b;
            background: #020617;
            color: #64748b;
            font: 12px/20px ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            text-align: right;
            user-select: none;
        }

        .tailwind-email-tool .code-scroll {
            position: relative;
            min-width: 0;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: #475569 #020617;
        }

        .tailwind-email-tool .code-scroll.input-scroll {
            overflow: hidden;
        }

        .tailwind-email-tool .code-highlight,
        .tailwind-email-tool .code-input {
            position: absolute;
            inset: 0;
            box-sizing: border-box;
            min-width: max-content;
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 12px 14px;
            border: 0;
            outline: 0;
            font: 12px/20px ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            white-space: pre;
            overflow: hidden;
        }

        .tailwind-email-tool .code-highlight {
            z-index: 1;
            pointer-events: none;
            color: #e2e8f0;
        }

        .tailwind-email-tool .code-input {
            z-index: 2;
            resize: none;
            overflow: auto;
            background: transparent;
            color: transparent;
            caret-color: #f8fafc;
            -webkit-text-fill-color: transparent;
            scrollbar-width: none;
        }

        .tailwind-email-tool .code-input::selection {
            background: rgb(99 102 241 / 0.38);
            color: transparent;
            -webkit-text-fill-color: transparent;
        }

        .tailwind-email-tool .code-token-tag { color: #60a5fa; }
        .tailwind-email-tool .code-token-attr { color: #c4b5fd; }
        .tailwind-email-tool .code-token-value { color: #86efac; }
        .tailwind-email-tool .code-token-text { color: #e2e8f0; }
        .tailwind-email-tool .code-token-comment { color: #64748b; font-style: italic; }
        .tailwind-email-tool .code-token-punct { color: #94a3b8; }
        .tailwind-email-tool .code-token-entity { color: #fbbf24; }

        .tailwind-email-tool .code-editor.output-editor {
            height: 430px;
        }

        .tailwind-email-tool .code-editor.output-editor .code-highlight {
            overflow: visible;
        }

        .tailwind-email-tool .code-editor.output-editor .code-scroll {
            overflow: auto;
        }

        .tailwind-email-tool .editor-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            min-height: 28px;
            margin-bottom: 8px;
        }

        .tailwind-email-tool .editor-meta {
            color: #64748b;
            font-size: 10px;
            line-height: 16px;
        }

        .tailwind-email-tool .editor-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            min-height: 24px;
            padding: 0 8px;
            border: 1px solid #334155;
            border-radius: 6px;
            background: #0f172a;
            color: #cbd5e1;
            font-size: 10px;
            font-weight: 600;
        }

        .tailwind-email-tool .editor-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #34d399;
        }

        @media (max-width: 639px) {
            .tailwind-email-tool .code-editor,
            .tailwind-email-tool .code-editor.output-editor {
                height: 360px;
            }
        }

        .tailwind-email-tool textarea:focus,
        .tailwind-email-tool button:focus-visible,
        .tailwind-email-tool input:focus-visible,
        .tailwind-email-tool select:focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
        }

        .tailwind-email-tool .drop-active {
            border-color: #4f46e5;
            background: #eef2ff;
        }

        .tailwind-email-tool .diag-scroll {
            scrollbar-width: thin;
        }

        .tailwind-email-tool .preview-frame {
            width: 100%;
            min-height: 330px;
            border: 0;
            background: #ffffff;
        }
    </style>

    {{-- ============================================================
        TOOL HEADER
    ============================================================= --}}
    <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">
        <div class="flex flex-col gap-3 px-4 py-3 sm:px-5 sm:py-3.5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-slate-100" aria-hidden="true">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 10 4 2-4 2" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="truncate text-base font-bold tracking-tight text-white sm:text-[17px]">
                            Tailwind CSS to Email-Safe Inline Style Converter
                        </h2>
                        <p class="mt-0.5 text-xs leading-5 text-slate-300">
                            Convert Tailwind HTML into email-friendly inline CSS directly in your browser.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex items-center rounded-lg border border-slate-700 bg-slate-800 p-0.5" role="tablist" aria-label="Tailwind CSS version">
                    <button type="button" role="tab" class="tool-tab" :aria-selected="version === 'auto'" @click="version = 'auto'; convert()">Auto</button>
                    <button type="button" role="tab" class="tool-tab" :aria-selected="version === 'v3'" @click="version = 'v3'; convert()">v3</button>
                    <button type="button" role="tab" class="tool-tab" :aria-selected="version === 'v4'" @click="version = 'v4'; convert()">v4</button>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 px-4 py-2.5 sm:px-5">
            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Conversion mode">
                <span class="mr-1 text-[11px] font-medium text-slate-400">Mode</span>
                <button type="button" class="tool-tab" :aria-selected="preset === 'conservative'" @click="preset='conservative'; convert()">Conservative</button>
                <button type="button" class="tool-tab" :aria-selected="preset === 'balanced'" @click="preset='balanced'; convert()">Balanced</button>
                <button type="button" class="tool-tab" :aria-selected="preset === 'modern'" @click="preset='modern'; convert()">Modern Email</button>
            </div>
        </div>
    </div>

    {{-- ============================================================
        WORKSPACE
    ============================================================= --}}
    <section class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-label="Tailwind email conversion workspace">
        <div class="grid min-w-0 lg:grid-cols-2">
            {{-- SOURCE --}}
            <div class="min-w-0 border-b border-slate-200 lg:border-b-0 lg:border-r">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-3 py-2.5 sm:px-4">
                    <div>
                        <label for="tailwind-html-input" class="text-xs font-semibold text-slate-900">Source HTML</label>
                        <p id="tailwind-html-help" class="mt-0.5 text-[11px] leading-4 text-slate-600">Paste HTML containing Tailwind utility classes.</p>
                    </div>
                    <span class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[10px] font-medium text-slate-600">Browser only</span>
                </div>

                <div
                    class="relative p-3 sm:p-4"
                    :class="dragging ? 'drop-active' : ''"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="handleDrop($event)"
                >
                    <div class="editor-toolbar" aria-hidden="true">
                        <span class="editor-badge"><span class="editor-dot"></span>HTML · Syntax highlighted</span>
                        <span class="editor-meta" x-text="editorMeta(input)"></span>
                    </div>

                    <div class="code-editor" :class="dragging ? 'ring-2 ring-indigo-500 ring-offset-2' : ''">
                        <div class="code-gutter" x-ref="inputGutter" aria-hidden="true" x-html="lineNumbers(input)"></div>
                        <div class="code-scroll input-scroll" x-ref="inputScroll">
                            <div class="code-highlight" x-ref="inputHighlight" aria-hidden="true" x-html="highlightHtml(input || 'Paste HTML here...')"></div>
                            <textarea
                                id="tailwind-html-input"
                                name="tailwind_html_input"
                                x-ref="inputEditor"
                                x-model="input"
                                @input.debounce.120ms="convert(); syncInputEditor()"
                                @scroll="syncInputEditor()"
                                aria-describedby="tailwind-html-help"
                                aria-label="Source HTML editor"
                                spellcheck="false"
                                autocomplete="off"
                                autocapitalize="off"
                                wrap="off"
                                class="code-input"
                                placeholder="<table class=\"w-full bg-white p-6\">\n  ...\n</table>"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <label for="tailwind-html-file" class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Open HTML
                        </label>
                        <input id="tailwind-html-file" name="tailwind_html_file" type="file" accept=".html,.htm,text/html" class="sr-only" @change="handleFile($event)">

                        <button type="button" @click="loadExample()" class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Example
                        </button>
                        <button type="button" @click="clearAll()" class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Clear
                        </button>
                        <span class="ml-auto text-[11px] text-slate-500" x-text="formatBytes(inputBytes())"></span>
                    </div>
                </div>
            </div>

            {{-- OUTPUT --}}
            <div class="min-w-0">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-3 py-2.5 sm:px-4">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Converted HTML</h3>
                        <p class="mt-0.5 text-[11px] leading-4 text-slate-600">Static utilities are inlined; responsive and interactive rules remain in style blocks.</p>
                    </div>
                    <span class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[10px] font-medium text-slate-600" x-text="detectedVersionLabel()"></span>
                </div>

                <div class="p-3 sm:p-4">
                    <div class="editor-toolbar" aria-hidden="true">
                        <span class="editor-badge"><span class="editor-dot"></span>Email-safe HTML · Read only</span>
                        <span class="editor-meta" x-text="editorMeta(output)"></span>
                    </div>

                    <div class="code-editor output-editor" role="region" aria-label="Converted HTML output">
                        <div class="code-gutter" x-ref="outputGutter" aria-hidden="true" x-html="lineNumbers(output)"></div>
                        <div class="code-scroll" x-ref="outputScroll" tabindex="0" @scroll="syncOutputEditor()">
                            <div id="tailwind-html-output" class="code-highlight" x-ref="outputHighlight" aria-live="polite" x-html="highlightHtml(output || 'Converted HTML will appear here.')"></div>
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <button type="button" @click="copyOutput()" :disabled="!output" class="inline-flex h-8 cursor-pointer items-center rounded-lg bg-slate-950 px-3 text-xs font-semibold text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-text="copyLabel"></span>
                        </button>
                        <button type="button" @click="downloadOutput()" :disabled="!output" class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50">
                            Download HTML
                        </button>
                        <span class="ml-auto text-[11px] text-slate-500" x-text="formatBytes(outputBytes())"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid border-t border-slate-200 bg-slate-50 sm:grid-cols-4">
            <div class="border-b border-slate-200 px-3 py-2.5 sm:border-b-0 sm:border-r">
                <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Classes</div>
                <div class="mt-0.5 text-sm font-bold text-slate-900" x-text="stats.classes"></div>
            </div>
            <div class="border-b border-slate-200 px-3 py-2.5 sm:border-b-0 sm:border-r">
                <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Converted</div>
                <div class="mt-0.5 text-sm font-bold text-emerald-700" x-text="stats.converted"></div>
            </div>
            <div class="border-b border-slate-200 px-3 py-2.5 sm:border-b-0 sm:border-r">
                <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Warnings</div>
                <div class="mt-0.5 text-sm font-bold text-amber-700" x-text="stats.warnings"></div>
            </div>
            <div class="px-3 py-2.5">
                <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Size change</div>
                <div class="mt-0.5 text-sm font-bold text-slate-900" x-text="stats.reduction"></div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        ADVANCED SETTINGS
    ============================================================= --}}
    <section class="mt-3 rounded-xl border border-slate-200 bg-white shadow-sm" x-data="{ open: false }">
        <button
            type="button"
            class="flex w-full cursor-pointer items-center justify-between gap-4 px-4 py-3 text-left"
            :aria-expanded="open.toString()"
            aria-controls="tailwind-advanced-settings"
            @click="open = !open"
        >
            <span>
                <span class="block text-sm font-semibold text-slate-900">Advanced configuration</span>
                <span class="mt-0.5 block text-xs text-slate-600">Custom theme/config, email safety, class handling, responsive rules and optional CSS.</span>
            </span>
            <svg class="h-4 w-4 shrink-0 text-slate-500" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>

        <div id="tailwind-advanced-settings" x-show="open" x-collapse class="border-t border-slate-200 p-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="tailwind-root-font-size" class="text-xs font-semibold text-slate-800">Root font size</label>
                    <div class="mt-1.5 flex items-center gap-2">
                        <input id="tailwind-root-font-size" name="tailwind_root_font_size" type="number" min="8" max="32" step="1" x-model.number="rootFontSize" @input="convert()" class="h-9 w-24 rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-900">
                        <span class="text-xs text-slate-600">px · used for rem lowering</span>
                    </div>
                </div>

                <div class="space-y-2.5 pt-5">
                    <label class="flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-800">
                        <input id="tailwind-preserve-classes" name="tailwind_preserve_classes" type="checkbox" x-model="preserveClasses" @change="convert()" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Preserve converted utility classes</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-800">
                        <input id="tailwind-remove-unused" name="tailwind_remove_unused" type="checkbox" x-model="removeUnused" @change="convert()" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Remove successfully converted classes</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-800">
                        <input id="tailwind-responsive-important" name="tailwind_responsive_important" type="checkbox" x-model="responsiveImportant" @change="convert()" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Use !important for responsive overrides</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-800">
                        <input id="tailwind-outlook-hints" name="tailwind_outlook_hints" type="checkbox" x-model="outlookHints" @change="convert()" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Add Outlook-safe table hints</span>
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <label for="tailwind-config-json" class="text-xs font-semibold text-slate-800">Tailwind theme config JSON</label>
                <textarea
                    id="tailwind-config-json"
                    name="tailwind_config_json"
                    x-model="configJson"
                    @input.debounce.180ms="convert()"
                    spellcheck="false"
                    class="mt-1.5 h-28 w-full resize-y rounded-lg border border-slate-300 bg-slate-950 p-3 font-mono text-xs leading-5 text-slate-100 placeholder:text-slate-500"
                    placeholder='{"theme":{"extend":{"colors":{"brand":"#3157d5"},"spacing":{"18":"4.5rem"},"screens":{"sm":"640px"}}}}'
                ></textarea>
                <p class="mt-1.5 text-[11px] leading-5 text-slate-600">Optional v3-style <code class="font-mono">theme</code> JSON. Supported colors, spacing, font sizes, line heights and breakpoints are merged with the built-in theme.</p>
            </div>

            <div class="mt-4">
                <label for="tailwind-custom-theme" class="text-xs font-semibold text-slate-800">Custom Tailwind theme / CSS</label>
                <textarea
                    id="tailwind-custom-theme"
                    name="tailwind_custom_theme"
                    x-model="customCss"
                    @input.debounce.180ms="convert()"
                    spellcheck="false"
                    class="mt-1.5 h-32 w-full resize-y rounded-lg border border-slate-300 bg-slate-950 p-3 font-mono text-xs leading-5 text-slate-100 placeholder:text-slate-500"
                    placeholder="@theme { --color-brand-500: #3157d5; --spacing-18: 4.5rem; }"
                ></textarea>
                <p class="mt-1.5 text-[11px] leading-5 text-slate-600">Use Tailwind v4 <code class="font-mono">@theme</code>, simple custom CSS variables, or supported v3-style values. Unsupported rules are reported rather than silently applied.</p>
            </div>
        </div>
    </section>

    {{-- ============================================================
        DIAGNOSTICS
    ============================================================= --}}
    <section class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Conversion diagnostics</h3>
                    <p class="mt-0.5 text-xs text-slate-600">Warnings identify CSS that may need special treatment in email clients.</p>
                </div>
                <span class="rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700" x-show="input.trim()">Client-side processing</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2">
            <div class="min-w-0 border-b border-slate-200 p-4 lg:border-b-0 lg:border-r">
                <h4 class="text-xs font-semibold text-slate-900">Compatibility</h4>
                <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <template x-for="client in compatibility" :key="client.name">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                            <div class="text-[11px] font-semibold text-slate-800" x-text="client.name"></div>
                            <div class="mt-1 text-[10px] font-semibold" :class="client.tone" x-text="client.status"></div>
                        </div>
                    </template>
                </div>

                <div class="mt-3 rounded-lg border border-slate-200 bg-white p-3">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-semibold text-slate-800">Gmail 102 KB check</span>
                        <span class="text-xs font-bold" :class="sizeWarning ? 'text-amber-700' : 'text-emerald-700'" x-text="sizeWarning ? 'Review' : 'Safe'"></span>
                    </div>
                    <p class="mt-1 text-[11px] leading-5 text-slate-600" x-text="sizeMessage"></p>
                </div>
            </div>

            <div class="min-w-0 p-4">
                <div class="flex items-center justify-between gap-3">
                    <h4 class="text-xs font-semibold text-slate-900">Class and CSS diagnostics</h4>
                    <span class="text-[10px] font-medium text-slate-500" x-text="diagnostics.length + ' item(s)'"></span>
                </div>
                <div class="diag-scroll mt-2 max-h-64 overflow-auto rounded-lg border border-slate-200">
                    <template x-if="!diagnostics.length">
                        <div class="p-4 text-xs text-slate-600">No diagnostics yet. Paste HTML to begin.</div>
                    </template>
                    <template x-for="item in diagnostics" :key="item.key">
                        <div class="border-b border-slate-100 p-3 last:border-b-0">
                            <div class="flex items-start gap-2">
                                <span class="mt-0.5 text-xs" :class="item.level === 'error' ? 'text-red-700' : item.level === 'warning' ? 'text-amber-700' : 'text-emerald-700'" x-text="item.level === 'error' ? '✕' : item.level === 'warning' ? '⚠' : '✓'" aria-hidden="true"></span>
                                <div class="min-w-0">
                                    <div class="break-all text-[11px] font-semibold text-slate-800" x-text="item.title"></div>
                                    <div class="mt-0.5 text-[11px] leading-5 text-slate-600" x-text="item.message"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        PREVIEW
    ============================================================= --}}
    <section class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3">
            <h3 class="text-sm font-semibold text-slate-900">Email preview</h3>
            <p class="mt-0.5 text-xs text-slate-600">A browser preview of the generated HTML. Always test final email HTML in your target clients.</p>
        </div>
        <div class="bg-slate-100 p-3 sm:p-4">
            <iframe
                title="Converted email HTML preview"
                class="preview-frame rounded-lg border border-slate-300 shadow-sm"
                sandbox="allow-same-origin"
                :srcdoc="previewHtml"
            ></iframe>
        </div>
    </section>

    {{-- ============================================================
        PRIVACY NOTE
    ============================================================= --}}
    <div class="mt-3 flex items-start gap-2 rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-2.5 text-xs text-indigo-950" role="status">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-indigo-700" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 1.75a.75.75 0 0 1 .75.75v.42a7.25 7.25 0 1 1-1.5 0V2.5a.75.75 0 0 1 .75-.75Zm0 4.5a.75.75 0 0 0-.75.75v4.25a.75.75 0 0 0 1.5 0V7a.75.75 0 0 0-.75-.75Zm0 7.25a.875.875 0 1 0 0 1.75.875.875 0 0 0 0-1.75Z" clip-rule="evenodd" />
        </svg>
        <p><strong>Browser-only processing.</strong> The HTML, classes and custom configuration used by this tool are processed locally in your browser. They are not submitted to AabiTech for conversion.</p>
    </div>

    @script
    <script>
        Alpine.data('tailwindEmailConverter', () => ({
            input: '', output: '', previewHtml: '',
            version: 'auto', detectedVersion: 'v3', preset: 'balanced',
            rootFontSize: 16, preserveClasses: false, removeUnused: true,
            responsiveImportant: true, outlookHints: true,
            customCss: '', configJson: '', dragging: false,
            copyLabel: 'Copy HTML', diagnostics: [],
            compatibility: [],
            stats: { classes: 0, converted: 0, warnings: 0, errors: 0, reduction: '—', inputSize: '0 B', outputSize: '0 B' },
            sizeWarning: false, sizeMessage: 'No output yet.',
            lastRun: 0,

            init() {
                this.loadExample();
                this.$nextTick(() => this.syncInputEditor());
            },
            editorMeta(value) {
                const source = String(value || '');
                const lines = source ? source.split('\n').length : 0;
                const chars = source.length;
                return `${lines} line${lines === 1 ? '' : 's'} · ${chars.toLocaleString()} chars`;
            },
            lineNumbers(value) {
                const count = Math.max(1, String(value || '').split('\n').length);
                return Array.from({ length: count }, (_, i) => `<div>${i + 1}</div>`).join('');
            },
            highlightHtml(source) {
                const value = String(source || '');
                if (!value) return '';
                const parts = [];
                let cursor = 0;
                const tokenRe = /<!--[\s\S]*?-->|<![^>]*>|<\/?[A-Za-z][^>]*>/g;
                let match;
                while ((match = tokenRe.exec(value)) !== null) {
                    if (match.index > cursor) parts.push(this.highlightText(value.slice(cursor, match.index)));
                    const token = match[0];
                    if (token.startsWith('<!--')) {
                        parts.push(`<span class=\"code-token-comment\">${this.escapeHtml(token)}</span>`);
                    } else if (token.startsWith('<!')) {
                        parts.push(`<span class=\"code-token-punct\">${this.escapeHtml(token)}</span>`);
                    } else {
                        parts.push(this.highlightTag(token));
                    }
                    cursor = match.index + token.length;
                }
                if (cursor < value.length) parts.push(this.highlightText(value.slice(cursor)));
                return parts.join('');
            },
            highlightText(value) {
                return this.escapeHtml(value).replace(/(&amp;#?[a-zA-Z0-9]+;|&#x?[0-9a-fA-F]+;)/g, '<span class=\"code-token-entity\">$1</span>');
            },
            highlightTag(token) {
                const closing = token.startsWith('</');
                const match = token.match(/^<(\/?)([A-Za-z][\w:-]*)([\s\S]*?)(\/?)>$/);
                if (!match) return `<span class=\"code-token-punct\">${this.escapeHtml(token)}</span>`;
                const slash = match[1];
                const name = match[2];
                const rest = match[3];
                const ending = match[4];
                if (closing) return `<span class=\"code-token-punct\">&lt;/</span><span class=\"code-token-tag\">${this.escapeHtml(name)}</span><span class=\"code-token-punct\">&gt;</span>`;
                return `<span class=\"code-token-punct\">&lt;${slash}</span><span class=\"code-token-tag\">${this.escapeHtml(name)}</span>${this.highlightAttributes(rest)}<span class=\"code-token-punct\">${ending}/&gt;</span>`;
            },
            highlightAttributes(rest) {
                if (!rest) return '';
                const out = [];
                let cursor = 0;
                const attrRe = /([:\w-]+)(\s*=\s*)(\"[^\"]*\"|'[^']*'|[^\s>]+)/g;
                let match;
                while ((match = attrRe.exec(rest)) !== null) {
                    if (match.index > cursor) out.push(`<span class=\"code-token-punct\">${this.escapeHtml(rest.slice(cursor, match.index))}</span>`);
                    out.push(`<span class=\"code-token-attr\">${this.escapeHtml(match[1])}</span><span class=\"code-token-punct\">${this.escapeHtml(match[2])}</span><span class=\"code-token-value\">${this.escapeHtml(match[3])}</span>`);
                    cursor = match.index + match[0].length;
                }
                if (cursor < rest.length) out.push(`<span class=\"code-token-punct\">${this.escapeHtml(rest.slice(cursor))}</span>`);
                return out.join('');
            },
            syncInputEditor() {
                this.$nextTick(() => {
                    const editor = this.$refs.inputEditor;
                    const scroll = this.$refs.inputScroll;
                    const highlight = this.$refs.inputHighlight;
                    const gutter = this.$refs.inputGutter;
                    if (!editor || !scroll) return;
                    scroll.scrollTop = editor.scrollTop;
                    scroll.scrollLeft = editor.scrollLeft;
                    if (highlight) { highlight.style.transform = `translate(${-editor.scrollLeft}px, ${-editor.scrollTop}px)`; }
                    if (gutter) gutter.style.transform = `translateY(${-editor.scrollTop}px)`;
                });
            },
            syncOutputEditor() {
                const scroll = this.$refs.outputScroll;
                const highlight = this.$refs.outputHighlight;
                const gutter = this.$refs.outputGutter;
                if (!scroll) return;
                if (highlight) { highlight.style.transform = ''; }
                if (gutter) gutter.style.transform = `translateY(${-scroll.scrollTop}px)`;
            },
            example() {
                return `<!doctype html>
<html><head><meta charset="utf-8"></head><body class="bg-slate-100">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
  <tr><td align="center" class="p-6">
    <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" class="w-full bg-white rounded-lg shadow-sm">
      <tr><td class="p-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Welcome to AabiTech</h1>
        <p class="mt-3 text-sm leading-6 text-gray-600">A browser-local Tailwind to email conversion example.</p>
        <a href="https://aabitech.com" class="mt-4 inline-block rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Visit AabiTech</a>
      </td></tr>
    </table>
  </td></tr>
</table></body></html>`;
            },
            loadExample() { this.input = this.example(); this.convert(); },
            clearAll() {
                this.input = ''; this.output = '';
                this.previewHtml = '<div style="font-family:Arial,sans-serif;padding:24px;color:#334155">Paste HTML to preview it here.</div>';
                this.diagnostics = []; this.compatibility = this.baseCompatibility();
                this.stats = { classes: 0, converted: 0, warnings: 0, errors: 0, reduction: '—', inputSize: '0 B', outputSize: '0 B' };
                this.sizeWarning = false; this.sizeMessage = 'No output yet.';
            },
            async handleFile(event) {
                const file = event.target.files?.[0]; if (!file) return;
                if (!/text\/html|application\/xhtml\+xml|\.html?$/i.test(`${file.type}|${file.name}`)) {
                    this.addDiagnostic('error', 'File type', 'Please choose an HTML or HTM file.'); return;
                }
                if (file.size > 2 * 1024 * 1024) { this.addDiagnostic('error', 'File size', 'For browser responsiveness, HTML imports are limited to 2 MB.'); return; }
                this.input = await file.text(); this.convert(); event.target.value = '';
            },
            async handleDrop(event) {
                this.dragging = false; const file = event.dataTransfer?.files?.[0]; if (!file) return;
                if (!/text\/html|application\/xhtml\+xml|\.html?$/i.test(`${file.type}|${file.name}`)) { this.addDiagnostic('error','File type','Please drop an HTML or HTM file.'); return; }
                if (file.size > 2 * 1024 * 1024) { this.addDiagnostic('error','File size','For browser responsiveness, HTML imports are limited to 2 MB.'); return; }
                this.input = await file.text(); this.convert();
            },
            inputBytes() { return new Blob([this.input || '']).size; },
            outputBytes() { return new Blob([this.output || '']).size; },
            formatBytes(bytes) { if (!bytes) return '0 B'; if (bytes < 1024) return `${bytes} B`; if (bytes < 1048576) return `${(bytes/1024).toFixed(1)} KB`; return `${(bytes/1048576).toFixed(2)} MB`; },
            detectedVersionLabel() { return this.version === 'auto' ? `Detected ${this.detectedVersion}` : `Tailwind ${this.version}`; },
            baseCompatibility() { return ['Gmail','Outlook','Apple Mail','Yahoo'].map(name => ({name,status:'Review',tone:'text-amber-700'})); },
            addDiagnostic(level, title, message, detail = '') {
                const key = `${level}|${title}|${message}`;
                if (!this.diagnostics.some(i => i.key === key)) this.diagnostics.push({ key, level, title, message, detail });
            },
            parseConfig() {
                if (!this.configJson.trim()) return { theme: {}, screens: {} };
                try {
                    const parsed = JSON.parse(this.configJson);
                    const theme = parsed?.theme || parsed?.themeConfig || {};
                    const extend = theme?.extend || {};
                    return {
                        theme: { ...theme, ...extend, colors:{...(theme.colors||{}),...(extend.colors||{})}, spacing:{...(theme.spacing||{}),...(extend.spacing||{})}, fontSize:{...(theme.fontSize||{}),...(extend.fontSize||{})}, lineHeight:{...(theme.lineHeight||{}),...(extend.lineHeight||{})}, screens:{...(theme.screens||{}),...(extend.screens||{})} },
                        screens: {...(theme.screens||{}),...(extend.screens||{})}
                    };
                } catch (e) { this.addDiagnostic('error','Tailwind config','Invalid JSON. Fix the config before relying on custom theme values.'); return {theme:{},screens:{}}; }
            },
            flattenTheme(obj, prefix='') {
                const out = {};
                if (!obj || typeof obj !== 'object') return out;
                Object.entries(obj).forEach(([k,v]) => {
                    const key = prefix ? `${prefix}-${k}` : k;
                    if (typeof v === 'string' || typeof v === 'number') out[key] = String(v);
                    else if (Array.isArray(v)) out[key] = v.map(x => typeof x === 'string' ? x : String(x)).join(', ');
                    else if (v && typeof v === 'object') Object.assign(out, this.flattenTheme(v,key));
                });
                return out;
            },
            parseCustomTheme() {
                const values = {};
                const re = /(--[\w-]+)\s*:\s*([^;{}]+)\s*;/g; let m;
                while ((m = re.exec(this.customCss)) !== null) values[m[1]] = m[2].trim();
                return values;
            },
            themeMaps() {
                const cfg = this.parseConfig(); const t = cfg.theme || {};
                const css = this.parseCustomTheme();
                const colors = {...this.flattenTheme(t.colors), ...Object.fromEntries(Object.entries(css).filter(([k]) => k.startsWith('--color-')).map(([k,v])=>[k.slice(8),v]))};
                const spacing = {...this.flattenTheme(t.spacing), ...Object.fromEntries(Object.entries(css).filter(([k]) => k.startsWith('--spacing-')).map(([k,v])=>[k.slice(10),v]))};
                const fontSize = {...this.flattenTheme(t.fontSize)};
                const fontFamily = {...this.flattenTheme(t.fontFamily)};
                const lineHeight = {...this.flattenTheme(t.lineHeight)};
                const screens = {...(t.screens||{}), ...(cfg.screens||{})};
                return { colors, spacing, fontSize, fontFamily, lineHeight, screens, css };
            },
            detectVersion(source) { if (this.version !== 'auto') return this.version; return /@theme\s*\{|@import\s*["']tailwindcss["']|--color-[\w-]+\s*:/.test(source) ? 'v4' : 'v3'; },
            cssEscape(value) { if (window.CSS?.escape) return CSS.escape(value); return String(value).replace(/[^a-zA-Z0-9_-]/g, c => `\\${c.charCodeAt(0).toString(16)} `); },
            decodeClassToken(token) { return token.replace(/\\([\\:\[\]\.\/#%!,])/g,'$1').replace(/\\_/g,'_'); },
            splitVariants(token) {
                const parts=[]; let current=''; let depth=0; let quote='';
                for (const c of token) { if (quote) { current+=c; if(c===quote) quote=''; continue; } if(c==='"'||c==="'"){quote=c; current+=c; continue;} if(c==='['||c==='(') depth++; if(c===']'||c===')') depth=Math.max(0,depth-1); if(c===':'&&depth===0){parts.push(current);current='';} else current+=c; }
                if(current) parts.push(current); return {variants:parts.slice(0,-1),utility:parts.at(-1)||''};
            },
            normalizeValue(value) {
                let v = String(value ?? '').trim().replace(/\s*!important\s*$/i,'');
                v = v.replace(/(-?\d*\.?\d+)rem\b/g,(_,n)=>`${parseFloat(n)*Number(this.rootFontSize||16)}px`);
                v = v.replace(/rgb\(([^)]+)\)/gi,(m,body)=>this.normalizeRgb(m,body));
                v = v.replace(/oklch\(([^)]+)\)/gi,m=>this.oklchToRgb(m));
                return v.replace(/\s*\/\s*/g,' / ');
            },
            normalizeRgb(original,body) {
                if (!body.includes('/')) return original;
                const [rgb,alpha] = body.split('/').map(s=>s.trim()); const nums=rgb.split(/[ ,]+/).filter(Boolean);
                if(nums.length!==3) return original;
                const a=parseFloat(alpha); if(!Number.isFinite(a)) return original;
                return `rgba(${nums.join(', ')}, ${a})`;
            },
            oklchToRgb(value) {
                const m=value.match(/oklch\(\s*([\d.]+)%?\s+([\d.]+)\s+([\d.]+)(?:deg)?(?:\s*\/\s*([\d.]+%?))?\s*\)/i);
                if(!m){this.addDiagnostic('warning','Modern color',`Preserved unsupported color value ${value}.`);return value;}
                const L=Math.max(0,Math.min(1,parseFloat(m[1])/100)), C=parseFloat(m[2]), H=parseFloat(m[3])*Math.PI/180;
                const a=C*Math.cos(H),b=C*Math.sin(H),l_=L+0.3963377774*a+0.2158037573*b,m_=L-0.1055613458*a-0.0638541728*b,s_=L-0.0894841775*a-1.291485548*b;
                const l=l_**3,mm=m_**3,ss=s_**3; let r=4.0767416621*l-3.3077115913*mm+0.2309699292*ss,g=-1.2684380046*l+2.6097574011*mm-0.3413193965*ss,bl=-0.0041960863*l-0.7034186147*mm+1.707614701*ss;
                const f=c=>c<=0.0031308?12.92*c:1.055*Math.pow(Math.max(c,0),1/2.4)-0.055; r=Math.round(Math.max(0,Math.min(1,f(r)))*255);g=Math.round(Math.max(0,Math.min(1,f(g)))*255);bl=Math.round(Math.max(0,Math.min(1,f(bl)))*255);
                const alpha=m[4]?` / ${m[4]}`:''; return `rgb(${r}, ${g}, ${bl})${alpha}`;
            },
            resolveTheme(value) {
                const maps=this.themeMaps(); const all={...maps.css};
                Object.entries(maps.colors).forEach(([k,v])=>all[`--color-${k}`]=v); Object.entries(maps.spacing).forEach(([k,v])=>all[`--spacing-${k}`]=v);
                return String(value).replace(/var\((--[\w-]+)(?:\s*,\s*([^)]*))?\)/g,(_,key,fallback)=>all[key] ?? fallback ?? `var(${key})`);
            },
            props(parts, important=false) {
                const out=[]; for(let i=0;i<parts.length;i+=2){ const name=parts[i], value=this.normalizeValue(parts[i+1]); out.push(`${name}:${value}${important?' !important':''}`); } return out.join(';');
            },
            arbitraryValue(raw){ return this.normalizeValue(raw.replace(/_/g,' ').replace(/\\([\\:\[\]\.\/#%!,])/g,'$1')); },
            colorValue(key) {
                const m=this.themeMaps(); const defaults={
                    white:'#ffffff',black:'#000000',transparent:'transparent',current:'currentColor',inherit:'inherit',
                    'slate-50':'#f8fafc','slate-100':'#f1f5f9','slate-200':'#e2e8f0','slate-300':'#cbd5e1','slate-400':'#94a3b8','slate-500':'#64748b','slate-600':'#475569','slate-700':'#334155','slate-800':'#1e293b','slate-900':'#0f172a','slate-950':'#020617',
                    'gray-50':'#f9fafb','gray-100':'#f3f4f6','gray-200':'#e5e7eb','gray-300':'#d1d5db','gray-400':'#9ca3af','gray-500':'#6b7280','gray-600':'#4b5563','gray-700':'#374151','gray-800':'#1f2937','gray-900':'#111827','gray-950':'#030712',
                    'red-500':'#ef4444','red-600':'#dc2626','red-700':'#b91c1c','orange-500':'#f97316','amber-500':'#f59e0b','amber-600':'#d97706','yellow-500':'#eab308',
                    'green-500':'#22c55e','green-600':'#16a34a','green-700':'#15803d','emerald-500':'#10b981','emerald-600':'#059669','emerald-700':'#047857','blue-500':'#3b82f6','blue-600':'#2563eb','blue-700':'#1d4ed8','indigo-500':'#6366f1','indigo-600':'#4f46e5','indigo-700':'#4338ca','purple-600':'#9333ea','pink-600':'#db2777'
                }; return m.colors[key] || defaults[key] || null;
            },
            spacingValue(key){ const defaults={'0':'0','px':'1px','0.5':'2px','1':'4px','1.5':'6px','2':'8px','2.5':'10px','3':'12px','3.5':'14px','4':'16px','5':'20px','6':'24px','7':'28px','8':'32px','9':'36px','10':'40px','11':'44px','12':'48px','14':'56px','16':'64px','20':'80px','24':'96px','28':'112px','32':'128px','36':'144px','40':'160px','48':'192px','52':'208px','56':'224px','60':'240px','64':'256px','72':'288px','80':'320px','96':'384px'}; const v=this.themeMaps().spacing[key]||defaults[key]; return v?this.normalizeValue(v):null; },
            utilityCss(utility) {
                let important=utility.startsWith('!'); if(important) utility=utility.slice(1);
                const arbitraryProperty=utility.match(/^\[([^:]+):(.+)\]$/); if(arbitraryProperty) return this.props([arbitraryProperty[1],this.arbitraryValue(arbitraryProperty[2])],important);
                const arbitrary=utility.match(/^([\w-]+)-\[(.+)\]$/);
                if(arbitrary){ const p=arbitrary[1],v=this.arbitraryValue(arbitrary[2]); const map={bg:['background-color',v],text:['color',v],w:['width',v],h:['height',v],'min-w':['min-width',v],'max-w':['max-width',v],'min-h':['min-height',v],'max-h':['max-height',v],p:['padding',v],px:['padding-left',v,'padding-right',v],py:['padding-top',v,'padding-bottom',v],pt:['padding-top',v],pr:['padding-right',v],pb:['padding-bottom',v],pl:['padding-left',v],m:['margin',v],mx:['margin-left',v,'margin-right',v],my:['margin-top',v,'margin-bottom',v],mt:['margin-top',v],mr:['margin-right',v],mb:['margin-bottom',v],ml:['margin-left',v],rounded:['border-radius',v],leading:['line-height',v],tracking:['letter-spacing',v],top:['top',v],right:['right',v],bottom:['bottom',v],left:['left',v],basis:['flex-basis',v],grow:['flex-grow',v],shrink:['flex-shrink',v],order:['order',v]}; if(map[p]) return this.props(map[p],important); this.addDiagnostic('warning',utility,'Arbitrary utility detected but its property could not be inferred safely.'); return null; }
                const d={
                    block:['display','block'],inline:['display','inline'], 'inline-block':['display','inline-block'],hidden:['display','none'],table:['display','table'],'inline-table':['display','inline-table'],'table-row':['display','table-row'],'table-cell':['display','table-cell'],flex:['display','flex'],'inline-flex':['display','inline-flex'],grid:['display','grid'],
                    relative:['position','relative'],absolute:['position','absolute'],static:['position','static'],fixed:['position','fixed'],sticky:['position','sticky'],
                    'flex-row':['flex-direction','row'],'flex-row-reverse':['flex-direction','row-reverse'],'flex-col':['flex-direction','column'],'flex-col-reverse':['flex-direction','column-reverse'],'flex-wrap':['flex-wrap','wrap'],'flex-nowrap':['flex-wrap','nowrap'],'flex-1':['flex','1 1 0%'],'flex-auto':['flex','1 1 auto'],'flex-none':['flex','none'],
                    'items-start':['align-items','flex-start'],'items-center':['align-items','center'],'items-end':['align-items','flex-end'],'items-stretch':['align-items','stretch'],'justify-start':['justify-content','flex-start'],'justify-center':['justify-content','center'],'justify-end':['justify-content','flex-end'],'justify-between':['justify-content','space-between'],'justify-around':['justify-content','space-around'],'justify-evenly':['justify-content','space-evenly'],
                    'text-left':['text-align','left'],'text-center':['text-align','center'],'text-right':['text-align','right'],'text-justify':['text-align','justify'],
                    'font-thin':['font-weight','100'],'font-extralight':['font-weight','200'],'font-light':['font-weight','300'],'font-normal':['font-weight','400'],'font-medium':['font-weight','500'],'font-semibold':['font-weight','600'],'font-bold':['font-weight','700'],'font-extrabold':['font-weight','800'],'font-black':['font-weight','900'],
                    uppercase:['text-transform','uppercase'],lowercase:['text-transform','lowercase'],capitalize:['text-transform','capitalize'],underline:['text-decoration','underline'],'no-underline':['text-decoration','none'],'whitespace-nowrap':['white-space','nowrap'],'break-words':['overflow-wrap','break-word'],'overflow-hidden':['overflow','hidden'],'overflow-auto':['overflow','auto'],'box-border':['box-sizing','border-box'],'box-content':['box-sizing','content-box'],
                    'border':['border-width','1px'],'border-0':['border-width','0'],'border-t':['border-top-width','1px'],'border-r':['border-right-width','1px'],'border-b':['border-bottom-width','1px'],'border-l':['border-left-width','1px'],'border-solid':['border-style','solid'],'border-dashed':['border-style','dashed'],'border-collapse':['border-collapse','collapse'],'border-separate':['border-collapse','separate'],
                    rounded:['border-radius','0.25rem'],'rounded-sm':['border-radius','0.125rem'],'rounded-md':['border-radius','0.375rem'],'rounded-lg':['border-radius','0.5rem'],'rounded-xl':['border-radius','0.75rem'],'rounded-2xl':['border-radius','1rem'],'rounded-full':['border-radius','9999px'],
                    'shadow-sm':['box-shadow','0 1px 2px 0 rgb(0 0 0 / 0.05)'],'shadow':['box-shadow','0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)'],'shadow-none':['box-shadow','none'],
                    'align-top':['vertical-align','top'],'align-middle':['vertical-align','middle'],'align-bottom':['vertical-align','bottom'],'object-cover':['object-fit','cover'],'object-contain':['object-fit','contain'],'leading-none':['line-height','1'],'leading-tight':['line-height','1.25'],'leading-snug':['line-height','1.375'],'leading-normal':['line-height','1.5'],'leading-relaxed':['line-height','1.625'],'leading-loose':['line-height','2'],
                    'sr-only':['position','absolute','width','1px','height','1px','padding','0','margin','-1px','overflow','hidden','clip','rect(0,0,0,0)','white-space','nowrap','border-width','0'],
                };
                if(d[utility]) return this.props(d[utility],important);
                if(/^(?:flex|inline-flex|grid)/.test(utility)) this.addDiagnostic('warning',utility,'Layout utility may render inconsistently across email clients, especially Outlook Windows.');
                if(/^(?:transform|translate-|rotate-|scale-|skew-|animate-|transition-|backdrop-|filter|mix-blend-)/.test(utility)){this.addDiagnostic('warning',utility,'Modern visual or motion utility is fragile in email clients and should be tested before sending.'); return null;}
                let m=utility.match(/^(p|px|py|pt|pr|pb|pl|m|mx|my|mt|mr|mb|ml)-(.+)$/); if(m){const v=m[2]==='auto'?'auto':this.spacingValue(m[2]); if(v){const map={p:['padding',v],px:['padding-left',v,'padding-right',v],py:['padding-top',v,'padding-bottom',v],pt:['padding-top',v],pr:['padding-right',v],pb:['padding-bottom',v],pl:['padding-left',v],m:['margin',v],mx:['margin-left',v,'margin-right',v],my:['margin-top',v,'margin-bottom',v],mt:['margin-top',v],mr:['margin-right',v],mb:['margin-bottom',v],ml:['margin-left',v]}; return this.props(map[m[1]],important);} }
                if(/^space-[xy]-/.test(utility)){this.addDiagnostic('warning',utility,'space-* requires a selector and cannot be represented as a single inline declaration.'); return null;}
                m=utility.match(/^text-(.+)$/); if(m){const key=m[1],c=this.colorValue(key); if(c) return this.props(['color',c],important); const sizes={xs:'0.75rem',sm:'0.875rem',base:'1rem',lg:'1.125rem',xl:'1.25rem','2xl':'1.5rem','3xl':'1.875rem','4xl':'2.25rem','5xl':'3rem','6xl':'3.75rem','7xl':'4.5rem','8xl':'6rem','9xl':'8rem'}; const fs=this.themeMaps().fontSize[key] || sizes[key]; if(fs){const val=Array.isArray(fs)?fs[0]:fs; return this.props(['font-size',val],important);} }
                m=utility.match(/^bg-(.+)$/); if(m){const c=this.colorValue(m[1]); if(c) return this.props(['background-color',c],important);}
                m=utility.match(/^border-(.+)$/); if(m){const c=this.colorValue(m[1]); if(c) return this.props(['border-color',c],important);}
                m=utility.match(/^font-(.+)$/); if(m){const fonts=this.themeMaps().fontFamily?.[m[1]]; if(fonts) return this.props(['font-family',Array.isArray(fonts)?fonts.join(', '):fonts],important);}
                m=utility.match(/^(w|h|min-w|max-w|min-h|max-h)-(.+)$/); if(m){const sizes={full:'100%',auto:'auto','screen':'100vw','svw':'100svw','lvw':'100lvw','dvw':'100dvw','1/2':'50%','1/3':'33.333333%','2/3':'66.666667%','1/4':'25%','3/4':'75%'}; const v=sizes[m[2]]||this.spacingValue(m[2]); if(v)return this.props([({'w':'width','h':'height','min-w':'min-width','max-w':'max-width','min-h':'min-height','max-h':'max-height'})[m[1]],v],important); }
                m=utility.match(/^leading-(.+)$/); if(m){const v=this.themeMaps().lineHeight[m[1]] || this.spacingValue(m[1]); if(v)return this.props(['line-height',Array.isArray(v)?v[0]:v],important);}
                m=utility.match(/^tracking-(.+)$/); if(m){const v={tighter:'-0.05em',tight:'-0.025em',normal:'0em',wide:'0.025em',wider:'0.05em',widest:'0.1em'}[m[1]]; if(v)return this.props(['letter-spacing',v],important);}
                m=utility.match(/^opacity-(\d{1,3})$/); if(m){const n=Math.min(100,parseInt(m[1],10));return this.props(['opacity',String(n/100)],important);}
                m=utility.match(/^(top|right|bottom|left|inset)-(.+)$/); if(m){const v=this.spacingValue(m[2]) || (m[2]==='auto'?'auto':null); if(v){const prop=m[1]==='inset'?'inset':m[1]; return this.props([prop,v],important);}}
                if(utility==='italic')return this.props(['font-style','italic'],important); if(utility==='not-italic')return this.props(['font-style','normal'],important);
                if(utility==='antialiased')return this.props(['-webkit-font-smoothing','antialiased','-moz-osx-font-smoothing','grayscale'],important);
                this.addDiagnostic('warning',utility,'Utility was not converted automatically. It was preserved so source behavior is not silently lost.'); return null;
            },
            variantRule(variants, selector, css) {
                let rule=`${selector}{${css}}`;
                const screens={sm:'640px',md:'768px',lg:'1024px',xl:'1280px','2xl':'1536px',...this.themeMaps().screens};
                for(let i=variants.length-1;i>=0;i--){const v=variants[i];
                    if(screens[v]) rule=`@media (min-width:${screens[v]}){${rule}}`;
                    else if(v.startsWith('max-')&&screens[v.slice(4)]) rule=`@media (max-width:calc(${screens[v.slice(4)]} - 0.02px)){${rule}}`;
                    else if(v==='dark') rule=`@media (prefers-color-scheme:dark){${rule}}`;
                    else if(v==='hover') rule=rule.replace('{',':hover{'); else if(v==='focus') rule=rule.replace('{',':focus{'); else if(v==='active') rule=rule.replace('{',':active{'); else if(v==='visited') rule=rule.replace('{',':visited{');
                    else if(v==='motion-safe') rule=`@media (prefers-reduced-motion:no-preference){${rule}}`;
                    else if(v==='motion-reduce') rule=`@media (prefers-reduced-motion:reduce){${rule}}`;
                    else if(/^\[&.*\]$/.test(v)){const sel=v.slice(1,-1).replace(/_/g,' '); rule=rule.replace(selector,sel.replace(/&/g,selector));}
                    else this.addDiagnostic('warning',`${v}: variant`,'Variant was not generated automatically and has been retained through a generated class selector only when possible.');
                }
                if(this.responsiveImportant && variants.some(v=>screens[v]||v.startsWith('max-')||v==='dark')) rule=rule.replace(/\{([^{}]*)\}/g,(m,b)=>`{${b.split(';').filter(Boolean).map(x=>x.includes('!important')?x:`${x} !important`).join(';')}}`);
                return rule;
            },
            classTokens(value){return String(value).trim().split(/\s+/).filter(Boolean).map(t=>this.decodeClassToken(t));},
            mergeStyle(existing, additions){
                const map=new Map(); const parse=t=>{let name='',val='',quote='',depth=0,seenColon=false; for(let i=0;i<String(t||'').length;i++){const c=String(t||'')[i]; if(quote){if(c===quote)quote=''; if(seenColon) val+=c; else name+=c; continue;} if(c==='"'||c==="'"){quote=c;if(seenColon) val+=c; else name+=c; continue;} if(c==='('||c==='[')depth++; if(c===')'||c===']')depth=Math.max(0,depth-1); if(c===':'&&!seenColon&&depth===0){seenColon=true;continue;} if(c===';'&&depth===0){if(name.trim())map.set(name.trim(),val.trim());name='';val='';seenColon=false;continue;} if(seenColon) val+=c; else name+=c;} if(name.trim())map.set(name.trim(),val.trim());}; parse(existing); parse(additions); return [...map.entries()].map(([k,v])=>`${k}:${this.normalizeValue(v)}`).join(';');
            },
            styleObject(style){const map=new Map(); String(style||'').split(';').forEach(part=>{const i=part.indexOf(':');if(i>0)map.set(part.slice(0,i).trim().toLowerCase(),part.slice(i+1).trim());});return map;},
            sanitizeInlineStyles(doc){
                const risky=/^(animation|animation-|transition|transform|filter|backdrop-filter|mix-blend-mode|position)$/;
                doc.querySelectorAll('[style]').forEach(el=>{const map=this.styleObject(el.getAttribute('style')); const out=[]; map.forEach((v,k)=>{const nv=this.resolveTheme(this.normalizeValue(v)); if(/url\s*\(/i.test(nv)&&!/^url\(https?:/i.test(nv)) this.addDiagnostic('warning',`${el.tagName.toLowerCase()} ${k}`,'Inline CSS contains a non-HTTP URL value; review it before sending.'); if(risky.test(k)) this.addDiagnostic('warning',`${el.tagName.toLowerCase()} ${k}`,`CSS property ${k} can be unreliable in email clients.`); out.push(`${k}:${nv}`);}); el.setAttribute('style',out.join(';'));});
            },
            analyzeDocument(doc){
                const selectors=[...doc.querySelectorAll('style:not([data-aabitech-email-rules])')]; let cssText=''; selectors.forEach(s=>cssText+=s.textContent+'\n');
                const text=cssText+'\n'+this.input;
                const props=['display:flex','display: grid','position:absolute','position:fixed','transform:','animation:','@keyframes','backdrop-filter','mix-blend-mode','object-fit:','background-image:','float:','calc(','clamp('];
                props.forEach(p=>{if(new RegExp(p.replace(/[.*+?^${}()|[\]\\]/g,'\\$&'),'i').test(text))this.addDiagnostic('warning',`Email CSS: ${p}`,'This feature is not uniformly supported across email clients; verify the rendered result in real clients.');});
                if(doc.querySelector('video,audio,canvas,svg'))this.addDiagnostic('warning','Rich media','Rich media or SVG content can require client-specific fallbacks.');
                if(doc.querySelectorAll('table').length===0 && doc.querySelector('[style*="display:flex"], [style*="display:grid"]'))this.addDiagnostic('warning','Layout fallback','No table layout was detected while a modern layout model is used. Consider a table-based email structure for Outlook.');
                if(doc.querySelectorAll('img').length){doc.querySelectorAll('img').forEach(img=>{if(!img.getAttribute('alt'))this.addDiagnostic('warning','Image accessibility','An image is missing alt text.'); if(!img.getAttribute('width')&&!img.style.width)this.addDiagnostic('warning','Image dimensions','Images without explicit dimensions can cause layout shifts in some clients.');});}
                doc.querySelectorAll('a').forEach(a=>{if(!a.getAttribute('href'))this.addDiagnostic('warning','Link','An anchor has no href attribute.');});
            },
            processSourceStyles(doc, generatedRules){
                doc.querySelectorAll('style:not([data-aabitech-email-rules])').forEach(style=>{
                    const css=this.lowerCssValues(style.textContent||'');
                    if(/@keyframes|animation|backdrop-filter|mix-blend-mode|transform/i.test(css)) this.addDiagnostic('warning','Source style','The supplied <style> block contains CSS features that can be fragile in email clients.');
                    const rules=this.extractCssRules(css);
                    const kept=[];
                    rules.forEach(r=>{
                        if(r.atRule){
                            if(/@media|@supports/i.test(r.header)){
                                const inner=this.filterUnusedCss(r.body,doc);
                                if(inner.trim()) kept.push(`${r.header}{${inner}}`);
                            } else if(!/^@(?:import|font-face|keyframes|theme|tailwind|config)/i.test(r.header)) kept.push(r.raw);
                            return;
                        }
                        if(this.removeUnused && !this.selectorMatches(doc,r.header)) return;
                        kept.push(`${this.safeSelector(r.header)}{${this.normalizeDeclarations(r.body)}}`);
                    });
                    style.textContent=kept.join('\n');
                    if(!style.textContent.trim()) style.remove();
                });
            },
            processCustomCss(doc, generatedRules){
                if(!this.customCss.trim()) return;
                let css=this.customCss;
                css=css.replace(/@apply\s+([^;{}]+);/g,(_,tokens)=>tokens.trim().split(/\s+/).map(t=>this.utilityCss(t)).filter(Boolean).join(';')+';');
                if(/@apply\s+/.test(this.customCss)) this.addDiagnostic('info','@apply','Supported @apply utilities were expanded locally; unsupported utilities are reported individually.');
                if(/@theme\s*\{/i.test(css)) this.addDiagnostic('info','Tailwind v4 theme','@theme variables are available to the local theme resolver.');
                const cssForEmail=this.lowerCssValues(css);
                const rules=this.extractCssRules(cssForEmail);
                rules.forEach(r=>{if(r.atRule){ if(/@media|@supports/i.test(r.header)){const inner=this.extractCssRules(r.body).map(x=>x.raw).join(''); const kept=this.removeUnused ? this.filterUnusedCss(inner,doc) : inner; if(kept.trim()) generatedRules.push(`${r.header}{${kept}}`);} else if(!/^@(?:theme|import|tailwind|config)/i.test(r.header)) generatedRules.push(r.raw); return; } if(this.removeUnused && !this.selectorMatches(doc,r.header)) {this.addDiagnostic('info',r.header,'Custom CSS rule was omitted because its selector did not match the supplied HTML.'); return;} generatedRules.push(`${r.header}{${this.normalizeDeclarations(r.body)}}`);});
            },
            lowerCssValues(css){return String(css).replace(/(-?\d*\.?\d+)rem\b/g,(_,n)=>`${parseFloat(n)*Number(this.rootFontSize||16)}px`).replace(/oklch\(([^)]+)\)/gi,m=>this.oklchToRgb(m));},
            normalizeDeclarations(body){return String(body).split(';').map(p=>{const i=p.indexOf(':');if(i<1)return '';return `${p.slice(0,i).trim()}:${this.resolveTheme(this.normalizeValue(p.slice(i+1)))}`;}).filter(Boolean).join(';');},
            extractCssRules(css){
                const out=[]; const text=String(css||''); let i=0;
                while(i<text.length){
                    while(i<text.length && /\s/.test(text[i])) i++;
                    if(i>=text.length) break;
                    let quote='',paren=0,brace=-1;
                    for(let j=i;j<text.length;j++){
                        const c=text[j];
                        if(quote){ if(c===quote && text[j-1]!=="\\") quote=''; continue; }
                        if(c==='"'||c==="'"){quote=c;continue;}
                        if(c==='(') paren++; else if(c===')') paren=Math.max(0,paren-1);
                        if(c==='{' && paren===0){brace=j;break;}
                        if(c===';' && paren===0){out.push({raw:text.slice(i,j+1).trim(),header:text.slice(i,j+1).trim(),body:'',atRule:text.slice(i,j+1).trim().startsWith('@')});i=j+1;brace=-1;break;}
                    }
                    if(brace<0) { if(i<text.length && !out.some(r=>r.raw===text.slice(i).trim())) { const tail=text.slice(i).trim(); if(tail) out.push({raw:tail,header:tail,body:'',atRule:tail.startsWith('@')}); } break; }
                    let depth=1,end=brace+1,innerQuote='';
                    for(;end<text.length;end++){
                        const c=text[end];
                        if(innerQuote){ if(c===innerQuote && text[end-1]!=="\\") innerQuote=''; continue; }
                        if(c==='"'||c==="'"){innerQuote=c;continue;}
                        if(c==='{') depth++; else if(c==='}' && --depth===0) break;
                    }
                    const raw=text.slice(i,Math.min(end+1,text.length)).trim(); const header=text.slice(i,brace).trim(); const body=text.slice(brace+1,end);
                    out.push({raw,header,body,atRule:header.startsWith('@')}); i=Math.min(end+1,text.length);
                }
                return out;
            },
            selectorMatches(doc,selector){try{return !!doc.querySelector(selector);}catch{return selector.split(',').some(s=>{try{return !!doc.querySelector(s.trim());}catch{return false;}});}},
            filterUnusedCss(css,doc){return this.extractCssRules(css).filter(r=>r.atRule||this.selectorMatches(doc,r.header)).map(r=>r.raw).join('');},
            safeSelector(selector){return selector.split(',').map(s=>s.trim()).filter(Boolean).map(s=>s.replace(/\\([\w-])/g,'$1')).join(',');},
            generateOutlookHints(doc){
                if(!this.outlookHints)return;
                doc.querySelectorAll('table').forEach(table=>{if(!table.getAttribute('role'))table.setAttribute('role','presentation'); if(!table.hasAttribute('cellspacing'))table.setAttribute('cellspacing','0'); if(!table.hasAttribute('cellpadding'))table.setAttribute('cellpadding','0'); if(!table.hasAttribute('border'))table.setAttribute('border','0'); const style=this.mergeStyle(table.getAttribute('style'),'border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt');table.setAttribute('style',style);});
                doc.querySelectorAll('img').forEach(img=>{const style=this.mergeStyle(img.getAttribute('style'),'display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic');img.setAttribute('style',style);});
                this.addDiagnostic('info','Outlook hints','Table spacing and image rendering hints were normalized. This is not a VML fallback.');
            },
            tableFallbackAnalysis(doc){
                const flex=[...doc.querySelectorAll('*')].filter(el=>/\bdisplay\s*:\s*(flex|grid)\b/i.test(el.getAttribute('style')||''));
                if(flex.length) this.addDiagnostic('warning','Table fallback','Modern layout containers remain in the source. The converter does not guess a visual table fallback because automatic restructuring can change semantics and spacing.');
            },
            validateRoundTrip(source,output){
                const a=new DOMParser().parseFromString(source,'text/html'), b=new DOMParser().parseFromString(output,'text/html');
                const textA=(a.body?.textContent||'').replace(/\s+/g,' ').trim(), textB=(b.body?.textContent||'').replace(/\s+/g,' ').trim();
                if(textA!==textB)this.addDiagnostic('error','Round-trip validation','Text content changed during conversion. The output was not accepted as a safe round trip.');
                const attrs=['href','src','alt','id','name']; attrs.forEach(attr=>{const aa=[...a.querySelectorAll(`[${attr}]`)].map(x=>x.getAttribute(attr));const bb=[...b.querySelectorAll(`[${attr}]`)].map(x=>x.getAttribute(attr)); if(aa.length!==bb.length||aa.some((v,i)=>v!==bb[i]))this.addDiagnostic('error',`Attribute preservation: ${attr}`,`The ${attr} attribute set changed during conversion.`);});
                const ca=a.querySelectorAll('*').length, cb=b.querySelectorAll('*').length; if(cb<ca) this.addDiagnostic('warning','Structure check',`Output contains ${cb} elements versus ${ca} in the source. Review any intentionally removed structural nodes.`);
            },
            serializeDocument(doc){
                const styles=[...doc.head?.querySelectorAll('style')||[]].map(s=>`<style>${s.textContent}</style>`).join('\n');
                const headOriginal=[...doc.head?.children||[]].filter(el=>!el.matches('style'));
                const body=doc.body?.innerHTML?.trim()||'';
                const title=doc.title?`<title>${this.escapeHtml(doc.title)}</title>`:'';
                const meta=[...headOriginal].filter(el=>/^(META|LINK|BASE)$/i.test(el.tagName)).map(el=>el.outerHTML).join('');
                return this.formatHtml(`${title}${meta}${styles}${styles?'\n':''}${body}`);
            },
            escapeHtml(v){return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');},
            formatHtml(html){
                const compact=String(html).replace(/>\s+</g,'><').replace(/\s{2,}/g,' '); const tokens=compact.replace(/</g,'\n<').split('\n').map(x=>x.trim()).filter(Boolean); const voidTags=/^(area|base|br|col|embed|hr|img|input|link|meta|param|source|track|wbr)$/i; let depth=0; const lines=[];
                for(const token of tokens){if(/^<\//.test(token))depth=Math.max(0,depth-1); lines.push(`${'  '.repeat(depth)}${token}`); const m=token.match(/^<([a-z][\w-]*)(?:\s[^>]*)?>$/i); if(m&&!token.endsWith('/>')&&!voidTags.test(m[1])&&!/^<!/.test(token)&&!/^<meta/i.test(token))depth++;} return lines.join('\n').replace(/\n{3,}/g,'\n\n');
            },
            previewDocument(doc){const clone=doc.documentElement.cloneNode(true);clone.querySelectorAll('script,style[data-aabitech-email-rules]').forEach(s=>s.remove());const body=clone.querySelector('body');if(body){body.style.margin='0';body.style.backgroundColor='#f1f5f9';body.style.fontFamily='Arial,Helvetica,sans-serif';}return '<!doctype html>'+clone.outerHTML;},
            convert(){
                const started=performance.now(); this.diagnostics=[]; if(!this.input.trim()){this.clearAll();return;}
                if(this.input.length>2*1024*1024){this.addDiagnostic('error','Input size','HTML input is limited to 2 MB to keep conversion responsive in the browser.');return;}
                this.detectedVersion=this.detectVersion(this.input); const parser=new DOMParser(); const doc=parser.parseFromString(this.input,'text/html');
                if(!doc.body){this.addDiagnostic('error','HTML structure','The browser could not create a document body.');return;}
                const generatedRules=[], counts={classes:0,converted:0}; const sourceClasses=new Set();
                doc.querySelectorAll('[class]').forEach((el,index)=>{
                    const tokens=this.classTokens(el.getAttribute('class')||''); const kept=[]; const inline=[];
                    tokens.forEach((token,tokenIndex)=>{counts.classes++;sourceClasses.add(token);const {variants,utility}=this.splitVariants(token);const css=this.utilityCss(utility);if(!css){kept.push(token);return;}counts.converted++; if(!variants.length){inline.push(css); if(this.preserveClasses)kept.push(token);} else {const safe=`aabi-tw-${this.hash(`${token}-${index}-${tokenIndex}`)}`;kept.push(safe);generatedRules.push(this.variantRule(variants,`.${safe}`,`${css};`));}});
                    if(inline.length)el.setAttribute('style',this.mergeStyle(el.getAttribute('style'),inline.join(';')));
                    if(this.removeUnused){if(kept.length)el.setAttribute('class',kept.join(' '));else el.removeAttribute('class');}
                    else { const sourceTokens=this.classTokens(el.getAttribute('class')||''); const variantSafe=kept.filter(k=>k.startsWith('aabi-tw-')); const classes=[...new Set([...sourceTokens,...variantSafe])]; if(classes.length)el.setAttribute('class',classes.join(' ')); }
                });
                this.processSourceStyles(doc,generatedRules); this.processCustomCss(doc,generatedRules); this.sanitizeInlineStyles(doc); this.analyzeDocument(doc); this.generateOutlookHints(doc); this.tableFallbackAnalysis(doc);
                if(generatedRules.length){const style=doc.createElement('style');style.setAttribute('data-aabitech-email-rules','true');style.textContent=generatedRules.map(r=>r.replace(/\s*;\s*}/g,'}')).join('\n');(doc.head||doc.documentElement).appendChild(style);}
                this.output=this.serializeDocument(doc); this.previewHtml=this.previewDocument(doc); this.validateRoundTrip(this.input,this.output);
                this.stats.classes=counts.classes;this.stats.converted=counts.converted;this.stats.warnings=this.diagnostics.filter(i=>i.level==='warning').length;this.stats.errors=this.diagnostics.filter(i=>i.level==='error').length;
                const before=this.inputBytes(),after=this.outputBytes();this.stats.inputSize=this.formatBytes(before);this.stats.outputSize=this.formatBytes(after);this.stats.reduction=before?`${((before-after)/before*100).toFixed(1)}%`:'—';this.sizeWarning=after>102*1024;this.sizeMessage=this.sizeWarning?`Output is ${this.formatBytes(after)}. Gmail clipping commonly becomes a concern around 102 KB; keep the final message comfortably below that threshold.`:`Output is ${this.formatBytes(after)}, below the 102 KB review threshold.`;this.updateCompatibility();this.lastRun=performance.now()-started;
            },
            hash(value){let h=2166136261;for(let i=0;i<value.length;i++)h=Math.imul(h^value.charCodeAt(i),16777619);return(h>>>0).toString(36);},
            updateCompatibility(){
                const msgs=this.diagnostics.map(i=>`${i.title} ${i.message}`).join(' ').toLowerCase(); const hasLayout=/flex|grid|layout|position:absolute|position:fixed/.test(msgs),modern=/transform|animation|backdrop|mix-blend|rich media/.test(msgs),access=/image accessibility|attribute preservation/.test(msgs);
                this.compatibility=[
                    {name:'Gmail',status:modern?'Review':hasLayout?'Review':'Good',tone:modern||hasLayout?'text-amber-700':'text-emerald-700'},
                    {name:'Outlook',status:hasLayout||modern?'Review':'Good',tone:hasLayout||modern?'text-amber-700':'text-emerald-700'},
                    {name:'Apple Mail',status:modern?'Review':'Good',tone:modern?'text-amber-700':'text-emerald-700'},
                    {name:'Yahoo',status:modern||access?'Review':'Good',tone:modern||access?'text-amber-700':'text-emerald-700'}
                ];
            },
            async copyOutput(){if(!this.output)return;try{await navigator.clipboard.writeText(this.output);this.copyLabel='✓ Copied to clipboard';}catch{this.addDiagnostic('error','Clipboard','Clipboard access failed. Select the output manually and copy it.');this.copyLabel='Copy failed';}setTimeout(()=>this.copyLabel='Copy HTML',1800);},
            downloadOutput(){if(!this.output)return;const blob=new Blob([this.output],{type:'text/html;charset=utf-8'}),url=URL.createObjectURL(blob),a=document.createElement('a');a.href=url;a.download='aabitech-email-safe.html';document.body.appendChild(a);a.click();a.remove();URL.revokeObjectURL(url);},
            exportConfig(){const blob=new Blob([this.configJson||'{\n  "theme": {}\n}'],{type:'application/json'}),url=URL.createObjectURL(blob),a=document.createElement('a');a.href=url;a.download='tailwind-email-config.json';a.click();URL.revokeObjectURL(url);},
        }));
    </script>
    @endscript
</div>