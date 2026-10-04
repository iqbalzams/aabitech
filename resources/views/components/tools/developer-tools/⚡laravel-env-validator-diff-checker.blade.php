<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only tool. Environment contents are never sent to the server.
};
?>

<div
    x-data="laravelEnvValidator()"
    x-init="init()"
    class="space-y-4"
>
    {{-- ================================================================
         WORKSPACE
         The main tool page already provides H1, description and SEO.
         This component contains workspace UI only.
    ================================================================= --}}

    <section
        aria-labelledby="env-workspace-title"
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        <h2 id="env-workspace-title" class="sr-only">
            Laravel environment validator workspace
        </h2>

        {{-- Mode tabs --}}
        <div class="border-b border-slate-200 px-3 py-3 sm:px-4">
            <div
                class="flex flex-wrap gap-1.5"
                role="tablist"
                aria-label="Environment analysis mode"
            >
                <button
                    type="button"
                    role="tab"
                    aria-controls="env-workspace"
                    :aria-selected="mode === 'validate'"
                    :class="mode === 'validate' ? 'env-tab-active' : 'env-tab'"
                    class="env-tab"
                    @click="setMode('validate')"
                >
                    Validate
                </button>

                <button
                    type="button"
                    role="tab"
                    aria-controls="env-workspace"
                    :aria-selected="mode === 'diff'"
                    :class="mode === 'diff' ? 'env-tab-active' : 'env-tab'"
                    class="env-tab"
                    @click="setMode('diff')"
                >
                    .env ↔ .env.example
                </button>

                <button
                    type="button"
                    role="tab"
                    aria-controls="env-workspace"
                    :aria-selected="mode === 'three'"
                    :class="mode === 'three' ? 'env-tab-active' : 'env-tab'"
                    class="env-tab"
                    @click="setMode('three')"
                >
                    3-way parity
                </button>

                <button
                    type="button"
                    role="tab"
                    aria-controls="env-workspace"
                    :aria-selected="mode === 'raw'"
                    :class="mode === 'raw' ? 'env-tab-active' : 'env-tab'"
                    class="env-tab"
                    @click="setMode('raw')"
                >
                    Raw diff
                </button>
            </div>
        </div>

        <div id="env-workspace" class="p-3 sm:p-4">
            {{-- Configuration --}}
            <div class="mb-4 grid gap-3 md:grid-cols-3">
                <div class="env-setting">
                    <label
                        for="env-profile"
                        class="env-label"
                    >
                        Environment profile
                    </label>

                    <select
                        id="env-profile"
                        x-model="profile"
                        @change="analyze()"
                        class="env-control"
                    >
                        <option value="auto">Auto-detect</option>
                        <option value="local">Local</option>
                        <option value="testing">Testing</option>
                        <option value="staging">Staging</option>
                        <option value="production">Production</option>
                    </select>
                </div>

                <div class="env-setting">
                    <label
                        for="laravel-version"
                        class="env-label"
                    >
                        Laravel target
                    </label>

                    <select
                        id="laravel-version"
                        x-model="laravelVersion"
                        @change="analyze()"
                        class="env-control"
                    >
                        <option value="13">Laravel 13</option>
                        <option value="12">Laravel 12</option>
                        <option value="11">Laravel 11</option>
                        <option value="10">Laravel 10</option>
                        <option value="9">Laravel 9</option>
                    </select>
                </div>

                <div class="env-setting">
                    <label
                        for="duplicate-behavior"
                        class="env-label"
                    >
                        Duplicate-key behavior
                    </label>

                    <select
                        id="duplicate-behavior"
                        x-model="duplicateBehavior"
                        @change="analyze()"
                        class="env-control"
                    >
                        <option value="last">Use last value</option>
                        <option value="first">Use first value</option>
                    </select>
                </div>
            </div>

            {{-- Editors --}}
            <div class="grid gap-3 lg:grid-cols-2">
                {{-- Primary --}}
                <div class="env-editor-panel">
                    <div class="env-editor-header">
                        <div class="min-w-0">
                            <label
                                for="env-primary"
                                class="block text-xs font-semibold text-white"
                            >
                                Primary .env
                            </label>

                            <span
                                class="mt-0.5 block truncate text-[10px] text-slate-400"
                                x-text="primaryMeta"
                            ></span>
                        </div>

                        <div class="flex shrink-0 items-center gap-1.5">
                            <label
                                for="env-primary-file"
                                class="env-mini-button"
                            >
                                Import
                            </label>

                            <input
                                id="env-primary-file"
                                type="file"
                                accept=".env,.env.example,.env.*,text/plain"
                                class="sr-only"
                                @change="importFile($event, 'primary')"
                            >

                            <button
                                type="button"
                                class="env-mini-button"
                                @click="loadExample('primary')"
                                aria-label="Load primary environment example"
                            >
                                Example
                            </button>

                            <button
                                type="button"
                                class="env-mini-button"
                                @click="clearEditor('primary')"
                                aria-label="Clear primary environment"
                            >
                                Clear
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <textarea
                            id="env-primary"
                            x-model="primary"
                            @input.debounce.180ms="analyze()"
                            @dragover.prevent="dragging = 'primary'"
                            @dragleave="dragging = ''"
                            @drop.prevent="dropFile($event, 'primary')"
                            spellcheck="false"
                            autocapitalize="off"
                            autocomplete="off"
                            wrap="off"
                            aria-describedby="env-primary-help"
                            class="env-editor"
                            :class="dragging === 'primary' ? 'env-editor-dragging' : ''"
                            placeholder="APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:...
APP_URL=http://localhost"
                        ></textarea>

                        <div
                            x-show="dragging === 'primary'"
                            x-cloak
                            class="pointer-events-none absolute inset-0 grid place-items-center bg-indigo-500/10 text-xs font-semibold text-indigo-200"
                        >
                            Drop .env file here
                        </div>
                    </div>

                    <div
                        id="env-primary-help"
                        class="border-t border-slate-800 px-3 py-2 text-[10px] leading-4 text-slate-400"
                    >
                        Browser-only analysis. Environment contents are not uploaded.
                    </div>
                </div>

                {{-- Secondary --}}
                <div class="env-editor-panel">
                    <div class="env-editor-header">
                        <div class="min-w-0">
                            <label
                                for="env-secondary"
                                class="block text-xs font-semibold text-white"
                                x-text="secondaryLabel"
                            ></label>

                            <span
                                class="mt-0.5 block truncate text-[10px] text-slate-400"
                                x-text="secondaryMeta"
                            ></span>
                        </div>

                        <div class="flex shrink-0 items-center gap-1.5">
                            <label
                                for="env-secondary-file"
                                class="env-mini-button"
                            >
                                Import
                            </label>

                            <input
                                id="env-secondary-file"
                                type="file"
                                accept=".env,.env.example,.env.*,text/plain"
                                class="sr-only"
                                @change="importFile($event, 'secondary')"
                            >

                            <button
                                type="button"
                                class="env-mini-button"
                                @click="loadExample('secondary')"
                                aria-label="Load comparison environment example"
                            >
                                Example
                            </button>

                            <button
                                type="button"
                                class="env-mini-button"
                                @click="clearEditor('secondary')"
                                aria-label="Clear comparison environment"
                            >
                                Clear
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <textarea
                            id="env-secondary"
                            x-model="secondary"
                            @input.debounce.180ms="analyze()"
                            @dragover.prevent="dragging = 'secondary'"
                            @dragleave="dragging = ''"
                            @drop.prevent="dropFile($event, 'secondary')"
                            spellcheck="false"
                            autocapitalize="off"
                            autocomplete="off"
                            wrap="off"
                            aria-describedby="env-secondary-help"
                            class="env-editor"
                            :class="dragging === 'secondary' ? 'env-editor-dragging' : ''"
                            placeholder="APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com"
                        ></textarea>

                        <div
                            x-show="dragging === 'secondary'"
                            x-cloak
                            class="pointer-events-none absolute inset-0 grid place-items-center bg-indigo-500/10 text-xs font-semibold text-indigo-200"
                        >
                            Drop .env file here
                        </div>
                    </div>

                    <div
                        id="env-secondary-help"
                        class="border-t border-slate-800 px-3 py-2 text-[10px] leading-4 text-slate-400"
                    >
                        Secret values are masked in diagnostics, diffs and reports.
                    </div>
                </div>
            </div>

            {{-- Third environment --}}
            <div
                x-show="mode === 'three'"
                x-cloak
                class="env-editor-panel mt-3"
            >
                <div class="env-editor-header">
                    <div>
                        <label
                            for="env-third"
                            class="block text-xs font-semibold text-white"
                        >
                            Third environment
                        </label>

                        <span
                            class="mt-0.5 block text-[10px] text-slate-400"
                            x-text="thirdMeta"
                        ></span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <label
                            for="env-third-file"
                            class="env-mini-button"
                        >
                            Import
                        </label>

                        <input
                            id="env-third-file"
                            type="file"
                            accept=".env,.env.example,.env.*,text/plain"
                            class="sr-only"
                            @change="importFile($event, 'third')"
                        >

                        <button
                            type="button"
                            class="env-mini-button"
                            @click="loadExample('third')"
                        >
                            Example
                        </button>

                        <button
                            type="button"
                            class="env-mini-button"
                            @click="clearEditor('third')"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <textarea
                    id="env-third"
                    x-model="third"
                    @input.debounce.180ms="analyze()"
                    @dragover.prevent="dragging = 'third'"
                    @dragleave="dragging = ''"
                    @drop.prevent="dropFile($event, 'third')"
                    spellcheck="false"
                    autocapitalize="off"
                    autocomplete="off"
                    wrap="off"
                    aria-describedby="env-third-help"
                    class="env-editor"
                    :class="dragging === 'third' ? 'env-editor-dragging' : ''"
                    placeholder="APP_NAME=Laravel
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.example.com"
                ></textarea>

                <div
                    id="env-third-help"
                    class="border-t border-slate-800 px-3 py-2 text-[10px] leading-4 text-slate-400"
                >
                    Third environment is compared against the primary environment.
                </div>
            </div>

            {{-- Analysis options --}}
            <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                <label class="env-check">
                    <input
                        type="checkbox"
                        x-model="options.ignoreComments"
                        @change="analyze()"
                    >
                    <span>Ignore comments</span>
                </label>

                <label class="env-check">
                    <input
                        type="checkbox"
                        x-model="options.ignoreWhitespace"
                        @change="analyze()"
                    >
                    <span>Ignore harmless whitespace</span>
                </label>

                <label class="env-check">
                    <input
                        type="checkbox"
                        x-model="options.caseSensitive"
                        @change="analyze()"
                    >
                    <span>Case-sensitive keys</span>
                </label>

                <label class="env-check">
                    <input
                        type="checkbox"
                        x-model="options.sorted"
                        @change="analyze()"
                    >
                    <span>Sort semantic diff</span>
                </label>
            </div>

            {{-- Main actions --}}
            <div class="mt-3 flex flex-wrap gap-2">
                <button
                    type="button"
                    class="env-action env-action-primary"
                    :class="activeAction === 'analyze' ? 'env-action-active' : ''"
                    :aria-pressed="activeAction === 'analyze'"
                    @click="runAction('analyze')"
                >
                    Validate &amp; analyze
                </button>

                <button
                    type="button"
                    class="env-action"
                    :class="activeAction === 'generate' ? 'env-action-active' : ''"
                    :aria-pressed="activeAction === 'generate'"
                    @click="runAction('generate')"
                >
                    Generate sanitized .env.example
                </button>

                <button
                    type="button"
                    class="env-action"
                    :class="activeAction === 'sync' ? 'env-action-active' : ''"
                    :aria-pressed="activeAction === 'sync'"
                    @click="runAction('sync')"
                >
                    Preview safe key sync
                </button>

                <button
                    type="button"
                    class="env-action"
                    :class="activeAction === 'copy-report' ? 'env-action-active' : ''"
                    :aria-pressed="activeAction === 'copy-report'"
                    @click="runAction('copy-report')"
                >
                    Copy report
                </button>

                <button
                    type="button"
                    class="env-action"
                    :class="activeAction === 'download' ? 'env-action-active' : ''"
                    :aria-pressed="activeAction === 'download'"
                    @click="runAction('download')"
                >
                    Download JSON report
                </button>
            </div>
        </div>
    </section>

    {{-- ================================================================
         SUMMARY
    ================================================================= --}}

    <section
        aria-labelledby="env-summary-title"
        class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4"
    >
        <h2 id="env-summary-title" class="sr-only">
            Validation summary
        </h2>

        <div class="env-stat">
            <span>Errors</span>
            <strong x-text="summary.errors"></strong>
        </div>

        <div class="env-stat">
            <span>Warnings</span>
            <strong x-text="summary.warnings"></strong>
        </div>

        <div class="env-stat">
            <span>Keys</span>
            <strong x-text="summary.keys"></strong>
        </div>

        <div class="env-stat">
            <span>Changed / missing</span>
            <strong x-text="summary.changed + ' / ' + summary.missing"></strong>
        </div>
    </section>

    {{-- ================================================================
         DIAGNOSTICS + AUDIT
    ================================================================= --}}

    <section class="grid gap-3 lg:grid-cols-[1.1fr_.9fr]">
        <div class="env-card">
            <div class="env-card-header">
                <div>
                    <h2 class="env-card-title">Diagnostics</h2>
                    <p class="env-card-description">
                        Configuration issues are shown without exposing secret values.
                    </p>
                </div>

                <span
                    class="env-card-count"
                    x-text="diagnostics.length + ' issues'"
                ></span>
            </div>

            <div class="max-h-[520px] overflow-auto p-3">
                <template x-if="!diagnostics.length">
                    <div class="env-empty">
                        No issues detected yet. Paste an environment file to begin.
                    </div>
                </template>

                <div class="space-y-2">
                    <template
                        x-for="(issue, index) in diagnostics"
                        :key="issue.id + '-' + index"
                    >
                        <article
                            class="rounded-xl border p-3"
                            :class="
                                issue.severity === 'error'
                                    ? 'border-red-200 bg-red-50/60'
                                    : issue.severity === 'warning'
                                        ? 'border-amber-200 bg-amber-50/60'
                                        : 'border-slate-200 bg-slate-50'
                            "
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                                            :class="
                                                issue.severity === 'error'
                                                    ? 'bg-red-100 text-red-700'
                                                    : issue.severity === 'warning'
                                                        ? 'bg-amber-100 text-amber-700'
                                                        : 'bg-slate-200 text-slate-700'
                                            "
                                            x-text="issue.severity"
                                        ></span>

                                        <span
                                            class="text-xs font-semibold text-slate-900"
                                            x-text="issue.title"
                                        ></span>
                                    </div>

                                    <p
                                        class="mt-1 text-xs leading-5 text-slate-600"
                                        x-text="issue.message"
                                    ></p>

                                    <p
                                        x-show="issue.line"
                                        class="mt-1 text-[10px] text-slate-500"
                                        x-text="issue.file + ' · line ' + issue.line"
                                    ></p>
                                </div>

                                <code
                                    x-show="issue.key"
                                    class="shrink-0 rounded bg-white/80 px-1.5 py-1 text-[10px] text-slate-500"
                                    x-text="issue.key"
                                ></code>
                            </div>
                        </article>
                    </template>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="env-card">
                <div class="env-card-header">
                    <div>
                        <h2 class="env-card-title">Environment audit</h2>
                    </div>
                </div>

                <div class="grid gap-2 p-3 sm:grid-cols-2">
                    <template
                        x-for="item in audit"
                        :key="item.id"
                    >
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-xs font-semibold text-slate-800"
                                    x-text="item.label"
                                ></span>

                                <span
                                    class="text-[10px] font-bold uppercase"
                                    :class="
                                        item.status === 'pass'
                                            ? 'text-emerald-600'
                                            : item.status === 'fail'
                                                ? 'text-red-600'
                                                : 'text-amber-600'
                                    "
                                    x-text="item.status"
                                ></span>
                            </div>

                            <p
                                class="mt-1 text-[11px] leading-4 text-slate-500"
                                x-text="item.detail"
                            ></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="env-card">
                <div class="env-card-header">
                    <h2 class="env-card-title">Semantic key diff</h2>

                    <button
                        type="button"
                        class="env-text-button"
                        @click="copySemanticDiff()"
                    >
                        Copy
                    </button>
                </div>

                <div class="max-h-[280px] overflow-auto p-3">
                    <template x-if="!semanticDiff.length">
                        <p class="text-xs text-slate-500">
                            No semantic differences detected.
                        </p>
                    </template>

                    <div class="space-y-1.5">
                        <template
                            x-for="(item, index) in semanticDiff"
                            :key="index"
                        >
                            <div
                                class="rounded-lg border px-2.5 py-2 text-[11px]"
                                :class="
                                    item.type === 'missing'
                                        ? 'border-red-100 bg-red-50 text-red-700'
                                        : item.type === 'extra'
                                            ? 'border-blue-100 bg-blue-50 text-blue-700'
                                            : 'border-amber-100 bg-amber-50 text-amber-700'
                                "
                            >
                                <span
                                    class="font-bold uppercase"
                                    x-text="item.type"
                                ></span>

                                <span
                                    class="ml-1 font-mono"
                                    x-text="item.key"
                                ></span>

                                <span
                                    class="ml-1"
                                    x-text="item.display"
                                ></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         PARSED VARIABLES
    ================================================================= --}}

    <section class="env-card">
        <div class="env-card-header">
            <div>
                <h2 class="env-card-title">
                    Parsed environment variables
                </h2>

                <p class="env-card-description">
                    Sensitive values are masked automatically.
                </p>
            </div>

            <button
                type="button"
                class="env-text-button"
                @click="copyParsed()"
            >
                Copy sanitized table
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-4 py-2.5">Key</th>
                        <th scope="col" class="px-4 py-2.5">Value</th>
                        <th scope="col" class="px-4 py-2.5">Line</th>
                        <th scope="col" class="px-4 py-2.5">Status</th>
                        <th scope="col" class="px-4 py-2.5">References</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <template
                        x-for="row in parsedRows"
                        :key="row.key + '-' + row.line"
                    >
                        <tr>
                            <td
                                class="px-4 py-2.5 font-mono font-semibold text-slate-800"
                                x-text="row.key"
                            ></td>

                            <td
                                class="max-w-[360px] truncate px-4 py-2.5 font-mono text-slate-500"
                                x-text="row.displayValue"
                            ></td>

                            <td
                                class="px-4 py-2.5 text-slate-500"
                                x-text="row.line"
                            ></td>

                            <td class="px-4 py-2.5">
                                <span
                                    class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600"
                                    x-text="row.status"
                                ></span>
                            </td>

                            <td
                                class="px-4 py-2.5 text-slate-500"
                                x-text="row.refs || '—'"
                            ></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>

    {{-- ================================================================
         DEPENDENCIES + GENERATED EXAMPLE
    ================================================================= --}}

    <section class="grid gap-3 lg:grid-cols-2">
        <div class="env-card">
            <div class="env-card-header">
                <h2 class="env-card-title">
                    Variable dependency graph
                </h2>

                <span
                    class="env-card-count"
                    x-text="dependencies.length + ' references'"
                ></span>
            </div>

            <div class="max-h-[300px] overflow-auto p-3">
                <template x-if="!dependencies.length">
                    <p class="text-xs text-slate-500">
                        No variable references detected.
                    </p>
                </template>

                <div class="space-y-2">
                    <template
                        x-for="dep in dependencies"
                        :key="dep.from + '-' + dep.to + '-' + dep.line"
                    >
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[11px]">
                            <code
                                class="font-semibold text-indigo-700"
                                x-text="dep.from"
                            ></code>

                            <span class="mx-2 text-slate-400">→</span>

                            <code
                                class="text-slate-700"
                                x-text="dep.to"
                            ></code>

                            <span
                                x-show="dep.missing"
                                class="ml-2 text-red-600"
                            >
                                undefined
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="env-card">
            <div class="env-card-header">
                <div>
                    <h2 class="env-card-title">
                        Safe .env.example preview
                    </h2>
                </div>

                <button
                    type="button"
                    class="env-text-button"
                    @click="copyGeneratedExample()"
                >
                    Copy
                </button>
            </div>

            <pre
                class="max-h-[300px] overflow-auto whitespace-pre-wrap break-words bg-slate-950 p-4 text-[11px] leading-5 text-slate-200"
                x-text="generatedExample || 'Generate a sanitized .env.example to preview it here.'"
            ></pre>
        </div>
    </section>

    {{-- Toast --}}
    <div
        x-show="toast"
        x-cloak
        x-transition.opacity
        role="status"
        aria-live="polite"
        class="fixed bottom-5 left-1/2 z-50 -translate-x-1/2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-medium text-white shadow-lg"
        x-text="toast"
    ></div>

    {{-- ================================================================
         COMPONENT STYLES
    ================================================================= --}}
@assets
    <style>
        .env-tab {
            height: 30px;
            padding: 0 9px;
            border: 1px solid rgb(226 232 240);
            border-radius: 8px;
            background: #fff;
            color: rgb(71 85 105);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition:
                border-color .15s ease,
                background-color .15s ease,
                color .15s ease;
        }

        .env-tab:hover {
            border-color: rgb(165 180 252);
            color: rgb(67 56 202);
        }

        .env-tab-active {
            height: 30px;
            padding: 0 9px;
            border: 1px solid rgb(99 102 241);
            border-radius: 8px;
            background: rgb(238 242 255);
            color: rgb(67 56 202);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        .env-tab:focus-visible,
        .env-tab-active:focus-visible,
        .env-mini-button:focus-visible,
        .env-action:focus-visible,
        .env-control:focus-visible,
        .env-editor:focus-visible,
        .env-text-button:focus-visible {
            outline: 2px solid rgb(99 102 241);
            outline-offset: 2px;
        }

        .env-setting {
            border: 1px solid rgb(226 232 240);
            border-radius: 10px;
            background: rgb(248 250 252);
            padding: 10px;
        }

        .env-label {
            display: block;
            margin-bottom: 6px;
            color: rgb(51 65 85);
            font-size: 11px;
            font-weight: 700;
        }

        .env-control {
            width: 100%;
            height: 34px;
            border: 1px solid rgb(203 213 225);
            border-radius: 8px;
            background: #fff;
            padding: 0 9px;
            color: rgb(30 41 59);
            font-size: 12px;
            cursor: pointer;
        }

        .env-editor-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid rgb(30 41 59);
            border-radius: 14px;
            background: rgb(2 6 23);
        }

        .env-editor-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 48px;
            padding: 9px 10px;
            border-bottom: 1px solid rgb(30 41 59);
        }

        .env-editor {
            display: block;
            width: 100%;
            height: 300px;
            resize: vertical;
            border: 0;
            background: rgb(2 6 23);
            padding: 13px;
            color: rgb(226 232 240);
            caret-color: #fff;
            font: 12px/1.65 ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            outline: none;
            tab-size: 4;
        }

        .env-editor::placeholder {
            color: rgb(100 116 139);
        }

        .env-editor-dragging {
            box-shadow: inset 0 0 0 2px rgb(129 140 248);
        }

        .env-mini-button {
            display: inline-flex;
            height: 27px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgb(51 65 85);
            border-radius: 7px;
            background: rgb(15 23 42);
            padding: 0 8px;
            color: rgb(203 213 225);
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .env-mini-button:hover {
            background: rgb(30 41 59);
            color: #fff;
        }

        .env-check {
            display: flex;
            min-height: 34px;
            align-items: center;
            gap: 7px;
            border: 1px solid rgb(226 232 240);
            border-radius: 9px;
            background: rgb(248 250 252);
            padding: 7px 9px;
            color: rgb(71 85 105);
            font-size: 11px;
            cursor: pointer;
        }

        .env-check input {
            width: 14px;
            height: 14px;
            margin: 0;
            cursor: pointer;
            accent-color: rgb(79 70 229);
        }

        .env-action {
            min-height: 34px;
            border: 1px solid rgb(203 213 225);
            border-radius: 9px;
            background: #fff;
            padding: 7px 11px;
            color: rgb(51 65 85);
            font-size: 11px;
            font-weight: 700;
            line-height: 1.3;
            cursor: pointer;
            transition:
                border-color .15s ease,
                background-color .15s ease,
                color .15s ease,
                box-shadow .15s ease;
        }

        .env-action:hover {
            border-color: rgb(148 163 184);
            background: rgb(248 250 252);
        }

        .env-action-primary {
            border-color: rgb(79 70 229);
            background: rgb(79 70 229);
            color: #fff;
        }

        .env-action-primary:hover {
            border-color: rgb(67 56 202);
            background: rgb(67 56 202);
        }

        .env-action-active {
            border-color: rgb(99 102 241);
            background: rgb(238 242 255);
            color: rgb(67 56 202);
            box-shadow: 0 0 0 2px rgb(224 231 255);
        }

        .env-action-primary.env-action-active {
            border-color: rgb(67 56 202);
            background: rgb(67 56 202);
            color: #fff;
            box-shadow: 0 0 0 2px rgb(224 231 255);
        }

        .env-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid rgb(226 232 240);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 1px 2px rgb(15 23 42 / .04);
        }

        .env-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid rgb(226 232 240);
            padding: 11px 13px;
        }

        .env-card-title {
            color: rgb(15 23 42);
            font-size: 12px;
            font-weight: 700;
        }

        .env-card-description {
            margin-top: 2px;
            color: rgb(100 116 139);
            font-size: 10px;
            line-height: 1.5;
        }

        .env-card-count {
            flex-shrink: 0;
            color: rgb(100 116 139);
            font-size: 10px;
            font-weight: 600;
        }

        .env-text-button {
            border: 0;
            background: transparent;
            padding: 2px;
            color: rgb(79 70 229);
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .env-text-button:hover {
            text-decoration: underline;
        }

        .env-stat {
            border: 1px solid rgb(226 232 240);
            border-radius: 12px;
            background: #fff;
            padding: 10px 12px;
            box-shadow: 0 1px 2px rgb(15 23 42 / .04);
        }

        .env-stat span {
            display: block;
            color: rgb(100 116 139);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .env-stat strong {
            display: block;
            margin-top: 3px;
            color: rgb(15 23 42);
            font-size: 19px;
            line-height: 1.1;
        }

        .env-empty {
            border: 1px dashed rgb(203 213 225);
            border-radius: 10px;
            background: rgb(248 250 252);
            padding: 24px 16px;
            text-align: center;
            color: rgb(100 116 139);
            font-size: 11px;
        }

        @media (max-width: 640px) {
            .env-editor {
                height: 240px;
                font-size: 11px;
            }

            .env-editor-header {
                align-items: flex-start;
            }

            .env-action {
                flex: 1 1 145px;
            }

            .env-mini-button {
                padding-inline: 7px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .env-tab,
            .env-action,
            .env-mini-button {
                transition: none;
            }
        }
    </style>
@endassets
    {{-- ================================================================
         ALPINE LOGIC
    ================================================================= --}}
@script
    <script>
        function laravelEnvValidator() {
            const secretPatterns = [
                /^(APP_KEY|AWS_SECRET_ACCESS_KEY|AWS_SESSION_TOKEN|DB_PASSWORD|REDIS_PASSWORD|MAIL_PASSWORD|PUSHER_APP_SECRET|REVERB_APP_SECRET|NOVA_LICENSE_KEY|STRIPE_SECRET|STRIPE_WEBHOOK_SECRET|GITHUB_TOKEN|GITLAB_TOKEN|SENTRY_AUTH_TOKEN|GOOGLE_APPLICATION_CREDENTIALS|PRIVATE_KEY|SECRET|PASSWORD|PASS|TOKEN|API_KEY|ACCESS_KEY)$/i,
                /(?:_SECRET|_PASSWORD|_TOKEN|_API_KEY|_PRIVATE_KEY|_ACCESS_KEY)$/i
            ];

            const state = {
                mode: 'validate',
                profile: 'auto',
                laravelVersion: '13',
                duplicateBehavior: 'last',

                primary: '',
                secondary: '',
                third: '',

                dragging: '',
                activeAction: '',
                toast: '',
                toastTimer: null,

                options: {
                    ignoreComments: false,
                    ignoreWhitespace: true,
                    caseSensitive: true,
                    sorted: true
                },

                summary: {
                    errors: 0,
                    warnings: 0,
                    keys: 0,
                    changed: 0,
                    missing: 0
                },

                diagnostics: [],
                audit: [],
                semanticDiff: [],
                parsedRows: [],
                dependencies: [],
                generatedExample: '',
                report: {}
            };

            const uid = () => {
                if (
                    typeof crypto !== 'undefined' &&
                    typeof crypto.randomUUID === 'function'
                ) {
                    return crypto.randomUUID();
                }

                return Math.random().toString(36).slice(2);
            };

            return {
                ...state,

                get primaryMeta() {
                    return this.meta(this.primary);
                },

                get secondaryMeta() {
                    return this.meta(this.secondary);
                },

                get thirdMeta() {
                    return this.meta(this.third);
                },

                get secondaryLabel() {
                    return this.mode === 'three'
                        ? 'Staging / comparison environment'
                        : '.env.example / comparison file';
                },

                init() {
                    this.loadExample('primary');
                    this.loadExample('secondary');
                    this.analyze();
                },

                meta(text) {
                    const value = String(text ?? '');

                    const assignments = (
                        value.match(
                            /^\s*(?:export\s+)?[A-Za-z_][A-Za-z0-9_]*\s*=/gm
                        ) || []
                    ).length;

                    return `${value.length.toLocaleString()} chars · ${assignments} assignments`;
                },

                setMode(mode) {
                    this.mode = mode;
                    this.analyze();
                },

                normalizeNewlines(value) {
                    return String(value ?? '')
                        .replace(/\r\n/g, '\n')
                        .replace(/\r/g, '\n');
                },

                addIssue(
                    severity,
                    title,
                    message,
                    file = '',
                    line = null,
                    key = ''
                ) {
                    this.diagnostics.push({
                        id: uid(),
                        severity,
                        title,
                        message,
                        file,
                        line,
                        key
                    });
                },

                isSecret(key) {
                    return secretPatterns.some((pattern) =>
                        pattern.test(String(key))
                    );
                },

                mask(value, key) {
                    if (!this.isSecret(key)) {
                        return value;
                    }

                    if (!value) {
                        return '';
                    }

                    return '••••••••';
                },

                parse(text, fileName = 'environment') {
                    const original = String(text ?? '');

                    const raw = this.normalizeNewlines(original)
                        .replace(/^\uFEFF/, '');

                    const lines = raw.split('\n');
                    const entries = [];
                    const map = new Map();
                    const refs = [];

                    if (original.charCodeAt(0) === 0xFEFF) {
                        this.addIssue(
                            'info',
                            'UTF-8 BOM detected',
                            `${fileName} starts with a byte-order mark. It is ignored during parsing.`,
                            fileName,
                            1
                        );
                    }

                    const assignmentPattern =
                        /^(?:export\s+)?([A-Za-z_][A-Za-z0-9_]*)\s*=\s?(.*)$/;

                    const parseValue = (source, line, key) => {
                        let value = source.trimStart();

                        if (!value) {
                            return '';
                        }

                        if (value[0] === '"' || value[0] === "'") {
                            const quote = value[0];
                            let output = '';
                            let closed = false;

                            for (let i = 1; i < value.length; i++) {
                                if (
                                    value[i] === quote &&
                                    value[i - 1] !== '\\'
                                ) {
                                    closed = true;

                                    const remainder =
                                        value.slice(i + 1).trim();

                                    if (
                                        remainder &&
                                        !remainder.startsWith('#')
                                    ) {
                                        this.addIssue(
                                            'error',
                                            'Unexpected characters after quoted value',
                                            'Only a comment may follow a closed quoted value.',
                                            fileName,
                                            line,
                                            key
                                        );
                                    }

                                    break;
                                }

                                output += value[i];
                            }

                            if (!closed) {
                                this.addIssue(
                                    'error',
                                    'Unclosed quote',
                                    'The quoted value does not have a matching closing quote.',
                                    fileName,
                                    line,
                                    key
                                );
                            }

                            return output;
                        }

                        let commentPosition = -1;
                        let escaped = false;

                        for (let i = 0; i < value.length; i++) {
                            const char = value[i];

                            if (char === '\\' && !escaped) {
                                escaped = true;
                                continue;
                            }

                            if (
                                char === '#' &&
                                !escaped &&
                                (i === 0 || /\s/.test(value[i - 1]))
                            ) {
                                commentPosition = i;
                                break;
                            }

                            escaped = false;
                        }

                        return (
                            commentPosition >= 0
                                ? value.slice(0, commentPosition)
                                : value
                        ).trimEnd();
                    };

                    lines.forEach((line, index) => {
                        const lineNumber = index + 1;
                        const trimmed = line.trim();

                        if (!trimmed) {
                            return;
                        }

                        if (trimmed.startsWith('#')) {
                            return;
                        }

                        const match = trimmed.match(assignmentPattern);

                        if (!match) {
                            if (!this.options.ignoreComments) {
                                this.addIssue(
                                    'error',
                                    'Malformed environment assignment',
                                    'Expected KEY=VALUE or export KEY=VALUE.',
                                    fileName,
                                    lineNumber
                                );
                            }

                            return;
                        }

                        const key = match[1];
                        const value = parseValue(
                            match[2],
                            lineNumber,
                            key
                        );

                        const duplicate = map.has(key);

                        const entry = {
                            key,
                            value,
                            line: lineNumber,
                            raw: line,
                            duplicate,
                            secret: this.isSecret(key)
                        };

                        if (duplicate) {
                            this.addIssue(
                                'warning',
                                'Duplicate environment key',
                                `Key appears more than once. The ${
                                    this.duplicateBehavior === 'last'
                                        ? 'last'
                                        : 'first'
                                } value is used for semantic checks.`,
                                fileName,
                                lineNumber,
                                key
                            );
                        }

                        entries.push(entry);

                        if (
                            !map.has(key) ||
                            this.duplicateBehavior === 'last'
                        ) {
                            map.set(key, entry);
                        }

                        for (
                            const reference of value.matchAll(
                                /\$\{([A-Za-z_][A-Za-z0-9_]*)\}|\$([A-Za-z_][A-Za-z0-9_]*)/g
                            )
                        ) {
                            refs.push({
                                from: key,
                                to: reference[1] || reference[2],
                                line: lineNumber
                            });
                        }

                        if (value === '') {
                            this.addIssue(
                                'warning',
                                'Empty environment value',
                                'This key has an empty value.',
                                fileName,
                                lineNumber,
                                key
                            );
                        }

                        if (/\s+$/.test(line)) {
                            this.addIssue(
                                'info',
                                'Trailing whitespace',
                                'Trailing whitespace can make exact configuration diffs confusing.',
                                fileName,
                                lineNumber,
                                key
                            );
                        }
                    });

                    return {
                        raw,
                        lines,
                        entries,
                        map,
                        refs
                    };
                },

                detectProfile(environment) {
                    const value =
                        environment.map.get('APP_ENV')?.value?.toLowerCase();

                    return [
                        'local',
                        'testing',
                        'staging',
                        'production'
                    ].includes(value)
                        ? value
                        : 'unknown';
                },

                analyze() {
                    this.diagnostics = [];
                    this.audit = [];
                    this.semanticDiff = [];
                    this.parsedRows = [];
                    this.dependencies = [];
                    this.generatedExample = '';

                    const primary = this.parse(
                        this.primary,
                        'primary .env'
                    );

                    const secondary = this.parse(
                        this.secondary,
                        this.mode === 'three'
                            ? 'staging .env'
                            : '.env.example'
                    );

                    const third =
                        this.mode === 'three'
                            ? this.parse(this.third, 'third .env')
                            : null;

                    const profile =
                        this.profile === 'auto'
                            ? this.detectProfile(primary)
                            : this.profile;

                    this.laravelAudit(primary, profile);

                    if (this.mode !== 'validate') {
                        this.compare(primary, secondary);
                    }

                    if (third) {
                        this.compare(primary, third);
                    }

                    this.referenceAudit(primary);

                    this.parsedRows = primary.entries.map((entry) => ({
                        key: entry.key,
                        displayValue:
                            this.mask(entry.value, entry.key) ||
                            '(empty)',
                        line: entry.line,
                        status: entry.duplicate
                            ? 'duplicate'
                            : entry.value === ''
                                ? 'empty'
                                : entry.secret
                                    ? 'sensitive'
                                    : 'ok',
                        refs: primary.refs
                            .filter((reference) =>
                                reference.from === entry.key
                            )
                            .map((reference) => reference.to)
                            .join(', ')
                    }));

                    this.dependencies = primary.refs.map((reference) => ({
                        from: reference.from,
                        to: reference.to,
                        line: reference.line,
                        missing: !primary.map.has(reference.to)
                    }));

                    this.dependencies
                        .filter((dependency) => dependency.missing)
                        .forEach((dependency) => {
                            this.addIssue(
                                'error',
                                'Undefined variable reference',
                                `${dependency.from} references ${dependency.to}, which is not defined.`,
                                'primary .env',
                                dependency.line,
                                dependency.from
                            );
                        });

                    this.summary = {
                        errors: this.diagnostics.filter(
                            (issue) => issue.severity === 'error'
                        ).length,

                        warnings: this.diagnostics.filter(
                            (issue) => issue.severity === 'warning'
                        ).length,

                        keys: primary.map.size,

                        changed: this.semanticDiff.filter(
                            (item) => item.type === 'changed'
                        ).length,

                        missing: this.semanticDiff.filter(
                            (item) => item.type === 'missing'
                        ).length
                    };

                    this.audit.push({
                        id: 'profile',
                        label: 'Environment profile',
                        status:
                            profile === 'unknown'
                                ? 'warn'
                                : 'pass',
                        detail:
                            profile === 'unknown'
                                ? 'Could not confidently infer APP_ENV.'
                                : `Detected ${profile}.`
                    });

                    const debug =
                        primary.map.get('APP_DEBUG')?.value?.toLowerCase();

                    this.audit.push({
                        id: 'debug',
                        label: 'Production debug safety',
                        status:
                            profile === 'production'
                                ? debug === 'false'
                                    ? 'pass'
                                    : 'fail'
                                : 'info',
                        detail:
                            profile === 'production'
                                ? 'APP_DEBUG should be false in production.'
                                : 'Checked when production profile is selected.'
                    });

                    const appKey =
                        primary.map.get('APP_KEY')?.value;

                    this.audit.push({
                        id: 'key',
                        label: 'APP_KEY',
                        status: appKey ? 'pass' : 'fail',
                        detail: appKey
                            ? 'APP_KEY is present.'
                            : 'APP_KEY is missing or empty.'
                    });

                    const appUrl =
                        primary.map.get('APP_URL')?.value;

                    this.audit.push({
                        id: 'url',
                        label: 'APP_URL',
                        status: appUrl
                            ? this.validUrl(appUrl)
                                ? 'pass'
                                : 'warn'
                            : 'warn',
                        detail: appUrl
                            ? this.validUrl(appUrl)
                                ? 'APP_URL looks like an absolute HTTP(S) URL.'
                                : 'APP_URL is not a valid absolute HTTP(S) URL.'
                            : 'APP_URL is missing.'
                    });

                    this.audit.push({
                        id: 'db',
                        label: 'Database configuration',
                        status: this.databaseStatus(primary),
                        detail: this.databaseDetail(primary)
                    });

                    this.audit.push({
                        id: 'mail',
                        label: 'Mail configuration',
                        status: this.mailStatus(primary),
                        detail: this.mailDetail(primary)
                    });

                    this.audit.push({
                        id: 'cache',
                        label: 'Cache / session / queue',
                        status: this.cacheStatus(primary),
                        detail: this.cacheDetail(primary)
                    });

                    this.audit.push({
                        id: 'vite',
                        label: 'Vite public variables',
                        status: 'info',
                        detail:
                            'VITE_* values are exposed to browser bundles; never place private credentials in them.'
                    });

                    this.report = this.buildReport(
                        primary,
                        profile
                    );
                },

                validUrl(value) {
                    try {
                        const text = String(value || '');

                        return (
                            /^https?:\/\//i.test(text) &&
                            Boolean(new URL(text).hostname)
                        );
                    } catch {
                        return false;
                    }
                },

                laravelAudit(environment, profile) {
                    const get = (key) =>
                        environment.map.get(key)?.value ?? '';

                    if (!get('APP_ENV')) {
                        this.addIssue(
                            'warning',
                            'Missing APP_ENV',
                            'Laravel projects normally define an application environment explicitly.',
                            'primary .env',
                            null,
                            'APP_ENV'
                        );
                    }

                    if (!get('APP_KEY')) {
                        this.addIssue(
                            'error',
                            'Missing APP_KEY',
                            'Laravel encryption configuration depends on APP_KEY.',
                            'primary .env',
                            null,
                            'APP_KEY'
                        );
                    }

                    if (
                        get('APP_KEY') &&
                        !/^base64:[A-Za-z0-9+/=]+$/.test(
                            get('APP_KEY')
                        ) &&
                        !/^\$/.test(get('APP_KEY'))
                    ) {
                        this.addIssue(
                            'warning',
                            'APP_KEY format review',
                            'APP_KEY is present but does not look like a standard base64 Laravel key.',
                            'primary .env',
                            environment.map.get('APP_KEY')?.line,
                            'APP_KEY'
                        );
                    }

                    if (
                        profile === 'production' &&
                        get('APP_DEBUG').toLowerCase() !== 'false'
                    ) {
                        this.addIssue(
                            'error',
                            'APP_DEBUG enabled in production',
                            'APP_DEBUG should be false in production to reduce the risk of exposing debug information.',
                            'primary .env',
                            environment.map.get('APP_DEBUG')?.line,
                            'APP_DEBUG'
                        );
                    }

                    if (
                        get('APP_DEBUG') &&
                        !['true', 'false', '1', '0'].includes(
                            get('APP_DEBUG').toLowerCase()
                        )
                    ) {
                        this.addIssue(
                            'warning',
                            'Non-standard APP_DEBUG value',
                            'Use true/false or 1/0 consistently.',
                            'primary .env',
                            environment.map.get('APP_DEBUG')?.line,
                            'APP_DEBUG'
                        );
                    }

                    if (
                        get('APP_URL') &&
                        !this.validUrl(get('APP_URL'))
                    ) {
                        this.addIssue(
                            'warning',
                            'Invalid APP_URL',
                            'APP_URL should be an absolute HTTP(S) URL.',
                            'primary .env',
                            environment.map.get('APP_URL')?.line,
                            'APP_URL'
                        );
                    }

                    this.standardKeyAudit(environment);
                    this.databaseAudit(environment);
                    this.mailAudit(environment);
                    this.cacheAudit(environment);
                    this.redisAudit(environment);
                    this.viteAudit(environment);

                    for (const entry of environment.entries) {
                        if (
                            this.isSecret(entry.key) &&
                            entry.value &&
                            /^(test|example|change[-_ ]?me|your[-_ ])/i.test(
                                entry.value
                            )
                        ) {
                            this.addIssue(
                                'warning',
                                'Placeholder secret value',
                                'Sensitive key appears to contain a placeholder value.',
                                'primary .env',
                                entry.line,
                                entry.key
                            );
                        }
                    }
                },

                standardKeyAudit(environment) {
                    for (const key of [
                        'APP_ENV',
                        'APP_DEBUG',
                        'APP_KEY',
                        'APP_URL'
                    ]) {
                        if (!environment.map.has(key)) {
                            this.addIssue(
                                'info',
                                'Laravel standard variable absent',
                                `${key} is not defined; confirm this is intentional.`,
                                'primary .env',
                                null,
                                key
                            );
                        }
                    }
                },

                databaseAudit(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    if (
                        !get('DB_URL') &&
                        get('DB_CONNECTION') &&
                        !get('DB_DATABASE')
                    ) {
                        this.addIssue(
                            'warning',
                            'Incomplete database configuration',
                            'DB_CONNECTION is set but DB_DATABASE is missing.',
                            'primary .env',
                            environment.map.get('DB_CONNECTION')?.line,
                            'DB_DATABASE'
                        );
                    }
                },

                mailAudit(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    const mailer = get('MAIL_MAILER').toLowerCase();

                    if (
                        mailer &&
                        !['log', 'array', 'failover', 'roundrobin'].includes(
                            mailer
                        ) &&
                        !get('MAIL_HOST') &&
                        !get('MAIL_URL')
                    ) {
                        this.addIssue(
                            'warning',
                            'Mail configuration incomplete',
                            'The selected mailer has no MAIL_HOST or MAIL_URL detected; review provider configuration.',
                            'primary .env',
                            environment.map.get('MAIL_MAILER')?.line,
                            'MAIL_HOST'
                        );
                    }
                },

                cacheAudit(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    if (
                        !get('CACHE_STORE') &&
                        !get('CACHE_DRIVER')
                    ) {
                        this.addIssue(
                            'info',
                            'Cache store not explicit',
                            'No CACHE_STORE/CACHE_DRIVER was detected. Confirm the intended Laravel default.',
                            'primary .env'
                        );
                    }

                    if (!get('SESSION_DRIVER')) {
                        this.addIssue(
                            'info',
                            'Session driver not explicit',
                            'No SESSION_DRIVER was detected.',
                            'primary .env'
                        );
                    }

                    if (!get('QUEUE_CONNECTION')) {
                        this.addIssue(
                            'info',
                            'Queue connection not explicit',
                            'No QUEUE_CONNECTION was detected.',
                            'primary .env'
                        );
                    }
                },

                redisAudit(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    if (
                        get('REDIS_HOST') &&
                        !get('REDIS_PORT')
                    ) {
                        this.addIssue(
                            'info',
                            'Redis port not specified',
                            'REDIS_HOST is present without REDIS_PORT; verify the intended default.',
                            'primary .env',
                            environment.map.get('REDIS_HOST')?.line,
                            'REDIS_PORT'
                        );
                    }
                },

                viteAudit(environment) {
                    for (const entry of environment.entries) {
                        if (
                            entry.key.startsWith('VITE_') &&
                            this.isSecret(entry.key)
                        ) {
                            this.addIssue(
                                'error',
                                'Potential client-exposed secret',
                                'VITE_* variables are exposed to browser bundles; do not place private secrets here.',
                                'primary .env',
                                entry.line,
                                entry.key
                            );
                        }
                    }
                },

                referenceAudit(environment) {
                    for (const reference of environment.refs) {
                        if (!environment.map.has(reference.to)) {
                            this.addIssue(
                                'error',
                                'Undefined variable reference',
                                `${reference.from} references ${reference.to}, which is not defined.`,
                                'primary .env',
                                reference.line,
                                reference.from
                            );
                        }
                    }
                },

                databaseStatus(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    return get('DB_URL') ||
                        (get('DB_CONNECTION') &&
                            get('DB_DATABASE'))
                        ? 'pass'
                        : 'warn';
                },

                databaseDetail(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    if (get('DB_URL')) {
                        return 'DB_URL is configured.';
                    }

                    if (
                        get('DB_CONNECTION') &&
                        get('DB_DATABASE')
                    ) {
                        return `Detected ${get('DB_CONNECTION')} with a database name.`;
                    }

                    return 'Database variables are incomplete.';
                },

                mailStatus(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    const mailer = get('MAIL_MAILER').toLowerCase();

                    return !mailer ||
                        ['log', 'array'].includes(mailer) ||
                        Boolean(get('MAIL_HOST')) ||
                        Boolean(get('MAIL_URL'))
                        ? 'pass'
                        : 'warn';
                },

                mailDetail(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    const mailer = get('MAIL_MAILER');

                    if (!mailer) {
                        return 'MAIL_MAILER not specified.';
                    }

                    if (
                        get('MAIL_HOST') ||
                        get('MAIL_URL')
                    ) {
                        return `Mail provider configuration detected for ${mailer}.`;
                    }

                    return `${mailer} mailer needs provider configuration review.`;
                },

                cacheStatus(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    return get('CACHE_STORE') ||
                        get('CACHE_DRIVER')
                        ? 'pass'
                        : 'warn';
                },

                cacheDetail(environment) {
                    const get = (key) =>
                        environment.map.get(key)?.value || '';

                    if (get('CACHE_STORE')) {
                        return `CACHE_STORE=${get('CACHE_STORE')}.`;
                    }

                    if (get('CACHE_DRIVER')) {
                        return `CACHE_DRIVER=${get('CACHE_DRIVER')}.`;
                    }

                    return 'No cache store/driver detected.';
                },

                compare(primary, comparison) {
                    if (this.mode === 'validate') {
                        return;
                    }

                    const normalizeKey = (key) =>
                        this.options.caseSensitive
                            ? key
                            : key.toLowerCase();

                    const primaryMap = primary.map;
                    const comparisonMap = comparison.map;

                    const findEntry = (map, normalizedKey) => {
                        for (const [key, entry] of map) {
                            if (
                                normalizeKey(key) === normalizedKey
                            ) {
                                return [key, entry];
                            }
                        }

                        return null;
                    };

                    const allKeys = new Set([
                        ...primaryMap.keys(),
                        ...comparisonMap.keys()
                    ].map(normalizeKey));

                    let keys = [...allKeys];

                    if (this.options.sorted) {
                        keys.sort();
                    }

                    for (const normalizedKey of keys) {
                        const primaryEntry =
                            findEntry(primaryMap, normalizedKey);

                        const comparisonEntry =
                            findEntry(
                                comparisonMap,
                                normalizedKey
                            );

                        if (!primaryEntry && comparisonEntry) {
                            this.semanticDiff.push({
                                type: 'extra',
                                key: comparisonEntry[0],
                                display:
                                    'present only in comparison file'
                            });

                            continue;
                        }

                        if (primaryEntry && !comparisonEntry) {
                            this.semanticDiff.push({
                                type: 'missing',
                                key: primaryEntry[0],
                                display:
                                    'missing from comparison file'
                            });

                            continue;
                        }

                        if (!primaryEntry || !comparisonEntry) {
                            continue;
                        }

                        const clean = (value) =>
                            this.options.ignoreWhitespace
                                ? value.trim()
                                : value;

                        if (
                            clean(primaryEntry[1].value) !==
                            clean(comparisonEntry[1].value)
                        ) {
                            this.semanticDiff.push({
                                type: 'changed',
                                key: primaryEntry[0],
                                display:
                                    this.isSecret(primaryEntry[0]) ||
                                    this.isSecret(comparisonEntry[0])
                                        ? 'sensitive value changed'
                                        : 'value differs'
                            });
                        }
                    }

                    if (this.mode === 'raw') {
                        this.rawDiff(
                            primary.raw,
                            comparison.raw
                        );
                    }
                },

                rawDiff(primary, comparison) {
                    const primaryLines =
                        this.normalizeNewlines(primary).split('\n');

                    const comparisonLines =
                        this.normalizeNewlines(comparison).split('\n');

                    const count = Math.max(
                        primaryLines.length,
                        comparisonLines.length
                    );

                    let differences = 0;

                    for (let i = 0; i < count; i++) {
                        if (
                            primaryLines[i] !==
                            comparisonLines[i]
                        ) {
                            differences++;
                        }
                    }

                    if (differences) {
                        this.addIssue(
                            'info',
                            'Raw text differences',
                            `${differences} line(s) differ in exact text mode.`
                        );
                    }
                },

                generateExample() {
                    const environment = this.parse(
                        this.primary,
                        'primary .env'
                    );

                    const lines = [];
                    const seen = new Set();

                    for (const entry of environment.entries) {
                        if (seen.has(entry.key)) {
                            continue;
                        }

                        seen.add(entry.key);

                        lines.push(
                            `${entry.key}=${
                                this.isSecret(entry.key)
                                    ? ''
                                    : entry.value
                            }`
                        );
                    }

                    this.generatedExample = lines.join('\n');

                    this.notify(
                        'Sanitized .env.example generated'
                    );
                },

                syncExamplePreview() {
                    const primary = this.parse(
                        this.primary,
                        'primary .env'
                    );

                    const comparison = this.parse(
                        this.secondary,
                        '.env.example'
                    );

                    const existingKeys = new Set(
                        comparison.map.keys()
                    );

                    const existingLines =
                        this.normalizeNewlines(
                            this.secondary
                        )
                            .split('\n')
                            .filter((line) => line.length > 0);

                    const missing = [];

                    for (const entry of primary.entries) {
                        if (!existingKeys.has(entry.key)) {
                            missing.push(`${entry.key}=`);
                        }
                    }

                    this.generatedExample = [
                        ...existingLines,
                        ...missing
                    ].join('\n');

                    this.notify(
                        `Safe sync preview: ${missing.length} key(s) would be added`
                    );
                },

                buildReport(environment, profile) {
                    return {
                        tool:
                            'AabiTech Laravel .env Validator & Diff Checker',

                        generatedAt:
                            new Date().toISOString(),

                        profile,

                        laravelVersion:
                            Number(this.laravelVersion),

                        privacy:
                            'Analysis is performed locally in the browser; environment contents are not transmitted.',

                        options: {
                            ...this.options
                        },

                        summary: {
                            ...this.summary
                        },

                        diagnostics:
                            this.diagnostics.map((issue) => ({
                                ...issue,
                                key: issue.key || ''
                            })),

                        semanticDiff:
                            this.semanticDiff.map((item) => ({
                                ...item
                            })),

                        keys:
                            [...environment.map.keys()].map(
                                (key) => ({
                                    key,
                                    sensitive:
                                        this.isSecret(key)
                                })
                            ),

                        dependencies:
                            this.dependencies.map(
                                (dependency) => ({
                                    ...dependency
                                })
                            )
                    };
                },

                sanitizedReportText() {
                    return JSON.stringify(
                        this.report,
                        null,
                        2
                    );
                },

                async copyText(text) {
                    try {
                        if (
                            navigator.clipboard &&
                            window.isSecureContext
                        ) {
                            await navigator.clipboard.writeText(
                                text
                            );

                            return;
                        }
                    } catch {
                        // Fall through to legacy clipboard method.
                    }

                    const textarea =
                        document.createElement('textarea');

                    textarea.value = text;
                    textarea.setAttribute(
                        'readonly',
                        ''
                    );

                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    textarea.style.top = '0';

                    document.body.appendChild(
                        textarea
                    );

                    textarea.select();

                    try {
                        document.execCommand('copy');
                    } finally {
                        textarea.remove();
                    }
                },

                async copyReport() {
                    await this.copyText(
                        this.sanitizedReportText()
                    );

                    this.notify(
                        'Sanitized validation report copied'
                    );
                },

                async copySemanticDiff() {
                    const text =
                        this.semanticDiff
                            .map(
                                (item) =>
                                    `${item.type.toUpperCase()} ${item.key}: ${item.display}`
                            )
                            .join('\n') ||
                        'No semantic differences detected.';

                    await this.copyText(text);

                    this.notify(
                        'Semantic diff copied'
                    );
                },

                async copyParsed() {
                    const text =
                        this.parsedRows
                            .map(
                                (row) =>
                                    `${row.key}=${row.displayValue}`
                            )
                            .join('\n');

                    await this.copyText(text);

                    this.notify(
                        'Sanitized table copied'
                    );
                },

                async copyGeneratedExample() {
                    if (!this.generatedExample) {
                        return;
                    }

                    await this.copyText(
                        this.generatedExample
                    );

                    this.notify(
                        'Sanitized .env.example copied'
                    );
                },

                notify(message) {
                    this.toast = message;

                    clearTimeout(this.toastTimer);

                    this.toastTimer = setTimeout(() => {
                        this.toast = '';
                    }, 1900);
                },

                runAction(action) {
                    this.activeAction = action;

                    if (action === 'analyze') {
                        this.analyze();

                        this.notify(
                            'Environment analysis completed'
                        );

                        return;
                    }

                    if (action === 'generate') {
                        this.generateExample();

                        return;
                    }

                    if (action === 'sync') {
                        this.syncExamplePreview();

                        return;
                    }

                    if (action === 'copy-report') {
                        this.copyReport();

                        return;
                    }

                    if (action === 'download') {
                        this.downloadReport();

                        this.notify(
                            'JSON report downloaded'
                        );
                    }
                },

                downloadReport() {
                    const blob = new Blob(
                        [
                            this.sanitizedReportText()
                        ],
                        {
                            type:
                                'application/json;charset=utf-8'
                        }
                    );

                    const url =
                        URL.createObjectURL(blob);

                    const anchor =
                        document.createElement('a');

                    anchor.href = url;
                    anchor.download =
                        'laravel-env-validation-report.json';

                    document.body.appendChild(anchor);

                    anchor.click();

                    anchor.remove();

                    setTimeout(() => {
                        URL.revokeObjectURL(url);
                    }, 1000);
                },

                clearEditor(which) {
                    if (which === 'primary') {
                        this.primary = '';
                    } else if (which === 'secondary') {
                        this.secondary = '';
                    } else {
                        this.third = '';
                    }

                    this.analyze();
                },

                loadExample(which) {
                    const primary = `APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:example-key
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="\${APP_NAME}"

VITE_APP_NAME="\${APP_NAME}"`;

                    const secondary = `APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="\${APP_NAME}"`;

                    const third = `APP_NAME=Laravel
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_staging
DB_USERNAME=laravel
DB_PASSWORD=

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database`;

                    if (
                        which === 'primary' &&
                        !this.primary
                    ) {
                        this.primary = primary;
                    }

                    if (
                        which === 'secondary' &&
                        !this.secondary
                    ) {
                        this.secondary = secondary;
                    }

                    if (
                        which === 'third' &&
                        !this.third
                    ) {
                        this.third = third;
                    }

                    this.analyze();
                },

                async importFile(event, which) {
                    const file =
                        event.target.files?.[0];

                    if (!file) {
                        return;
                    }

                    if (file.size > 1024 * 1024) {
                        this.addIssue(
                            'error',
                            'File too large',
                            'Environment files are limited to 1 MiB for browser-side analysis.'
                        );

                        event.target.value = '';

                        this.refreshSummary();

                        return;
                    }

                    try {
                        const text =
                            await file.text();

                        if (which === 'primary') {
                            this.primary = text;
                        } else if (
                            which === 'secondary'
                        ) {
                            this.secondary = text;
                        } else {
                            this.third = text;
                        }

                        this.analyze();
                    } catch {
                        this.addIssue(
                            'error',
                            'Could not read file',
                            'The selected file could not be read by the browser.'
                        );

                        this.refreshSummary();
                    } finally {
                        event.target.value = '';
                    }
                },

                async dropFile(event, which) {
                    this.dragging = '';

                    const file =
                        event.dataTransfer.files?.[0];

                    if (!file) {
                        return;
                    }

                    if (file.size > 1024 * 1024) {
                        this.addIssue(
                            'error',
                            'File too large',
                            'Environment files are limited to 1 MiB for browser-side analysis.'
                        );

                        this.refreshSummary();

                        return;
                    }

                    try {
                        const text =
                            await file.text();

                        if (which === 'primary') {
                            this.primary = text;
                        } else if (
                            which === 'secondary'
                        ) {
                            this.secondary = text;
                        } else {
                            this.third = text;
                        }

                        this.analyze();
                    } catch {
                        this.addIssue(
                            'error',
                            'Could not read file',
                            'The dropped file could not be read by the browser.'
                        );

                        this.refreshSummary();
                    }
                },

                refreshSummary() {
                    this.summary.errors =
                        this.diagnostics.filter(
                            (issue) =>
                                issue.severity === 'error'
                        ).length;

                    this.summary.warnings =
                        this.diagnostics.filter(
                            (issue) =>
                                issue.severity === 'warning'
                        ).length;
                }
            };
        }

        if (typeof window !== 'undefined') {
            window.laravelEnvValidator =
                laravelEnvValidator;
        }
    </script>
    @endscript
</div>