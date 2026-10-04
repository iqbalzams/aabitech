<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only tool. SQL parsing and generation happen client-side.
};
?>

<div
    x-data="sqlLaravelMigrationConverter()"
    x-init="init()"
    class="space-y-4"
>
    <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="sql-input-heading">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-3 py-2">
                <div>
                    <h2 id="sql-input-heading" class="text-sm font-semibold text-slate-900">SQL schema</h2>
                    <p class="text-xs text-slate-500">Paste DDL or import a .sql file.</p>
                </div>
                <div class="flex flex-wrap items-center gap-1.5">
                    <label for="sql-dialect" class="sr-only">SQL dialect</label>
                    <select id="sql-dialect" x-model="options.dialect" @change="convert()" class="h-8 cursor-pointer rounded-lg border border-slate-300 bg-white px-2 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="auto">Auto detect</option>
                        <option value="mysql">MySQL / MariaDB</option>
                        <option value="postgresql">PostgreSQL</option>
                        <option value="sqlite">SQLite</option>
                        <option value="sqlserver">SQL Server</option>
                    </select>
                    <label for="sql-file" class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Import .sql</label>
                    <input id="sql-file" type="file" multiple accept=".sql,text/sql,text/plain" class="sr-only" @change="importFiles($event)" aria-label="Import SQL files">
                </div>
            </div>

            <div
                class="relative"
                @dragover.prevent="dragActive = true"
                @dragleave.prevent="dragActive = false"
                @drop.prevent="dropFile($event)"
                :class="dragActive ? 'ring-2 ring-inset ring-indigo-500' : ''"
            >
                <label for="sql-source" class="sr-only">SQL input</label>
                <div class="relative h-[440px] lg:h-[500px] overflow-hidden bg-slate-950">
                    <pre aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-auto whitespace-pre p-4 font-mono text-[12px] leading-5 text-slate-100"><code x-html="highlightedSql"></code></pre>
                    <textarea
                        id="sql-source"
                        x-model="sql"
                        @input.debounce.250ms="convert()"
                        @scroll="syncEditorScroll($event, 'sql')"
                        spellcheck="false"
                        autocomplete="off"
                        autocapitalize="off"
                        wrap="off"
                        class="absolute inset-0 h-full w-full resize-none overflow-auto border-0 bg-transparent p-4 font-mono text-[12px] leading-5 text-transparent caret-white outline-none placeholder:text-slate-500 focus:ring-0 selection:bg-indigo-500/30"
                        placeholder="CREATE TABLE users (\n  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n  name VARCHAR(255) NOT NULL,\n  email VARCHAR(255) NOT NULL UNIQUE,\n  created_at TIMESTAMP NULL,\n  updated_at TIMESTAMP NULL\n);"
                        aria-describedby="sql-editor-help"
                    ></textarea>
                </div>
                <span id="sql-editor-help" class="sr-only">SQL syntax is highlighted for readability. The editor remains fully editable.</span>
                <div x-show="dragActive" x-cloak class="pointer-events-none absolute inset-0 grid place-items-center bg-indigo-500/10 text-sm font-semibold text-indigo-700">Drop SQL file here</div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-slate-50 px-3 py-2 text-[11px] text-slate-500">
                <span x-text="sourceStats"></span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="loadExample()" class="cursor-pointer rounded-md px-2 py-1 font-medium text-slate-700 hover:bg-white">Example</button>
                    <button type="button" @click="clearAll()" class="cursor-pointer rounded-md px-2 py-1 font-medium text-slate-700 hover:bg-white">Clear</button>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="migration-output-heading">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-3 py-2">
                <div>
                    <h2 id="migration-output-heading" class="text-sm font-semibold text-slate-900">Laravel output</h2>
                    <p class="text-xs text-slate-500">Generated migration preview.</p>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" @click="copyOutput()" :disabled="!output" class="h-8 cursor-pointer rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Copy generated Laravel migration">
                        <span x-text="copyLabel"></span>
                    </button>
                    <button type="button" @click="downloadOutput()" :disabled="!output" class="h-8 cursor-pointer rounded-lg bg-indigo-600 px-2.5 text-xs font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Download generated Laravel migration">Download</button>
                    <button type="button" @click="downloadZip()" :disabled="!tables.length" class="h-8 cursor-pointer rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Download Laravel schema bundle as ZIP">ZIP</button>
                </div>
            </div>

            <div class="relative h-[440px] lg:h-[500px] overflow-hidden bg-slate-950">
                <pre id="laravel-output-code" aria-label="Generated Laravel migration" class="h-full overflow-auto whitespace-pre p-4 font-mono text-[12px] leading-5 text-slate-100" @scroll="syncEditorScroll($event, 'output')"><code x-html="highlightedOutput"></code></pre>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-slate-50 px-3 py-2 text-[11px] text-slate-500">
                <span x-text="outputStats"></span>
                <span x-show="statusMessage" x-text="statusMessage" class="font-medium text-indigo-700" aria-live="polite"></span>
            </div>
        </section>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="sql-options-heading">
        <div class="flex items-center justify-between border-b border-slate-200 px-3 py-2">
            <h2 id="sql-options-heading" class="text-sm font-semibold text-slate-900">Conversion options</h2>
            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-600">Browser-only processing</span>
        </div>
        <div class="grid gap-3 p-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2 lg:col-span-4">
                <label for="exclude-tables" class="mb-1 block text-xs font-medium text-slate-700">Exclude tables</label>
                <input id="exclude-tables" x-model="options.exclude" @input.debounce.200ms="convert()" placeholder="e.g. migrations, jobs" class="h-9 w-full rounded-lg border border-slate-300 px-2 text-xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="laravel-version" class="mb-1 block text-xs font-medium text-slate-700">Laravel target</label>
                <select id="laravel-version" x-model="options.laravel" @change="convert()" class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 px-2 text-xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="13">Laravel 13</option><option value="12">Laravel 12</option><option value="11">Laravel 11</option><option value="10">Laravel 10</option><option value="9">Laravel 9</option>
                </select>
            </div>
            <div>
                <label for="output-style" class="mb-1 block text-xs font-medium text-slate-700">Output style</label>
                <select id="output-style" x-model="options.style" @change="convert()" class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 px-2 text-xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="idiomatic">Idiomatic</option><option value="explicit">Explicit</option><option value="compatibility">Compatibility-focused</option>
                </select>
            </div>
            <div>
                <label for="naming-transform" class="mb-1 block text-xs font-medium text-slate-700">Naming transformation</label>
                <select id="naming-transform" x-model="options.naming" @change="convert()" class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 px-2 text-xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="preserve">Preserve names</option><option value="snake">snake_case</option><option value="lower">lowercase</option>
                </select>
            </div>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.smart" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Smart Laravel idioms</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.separate" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Separate FK phase</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.timestamps" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Collapse timestamps</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.softDeletes" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Collapse soft deletes</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.dbFallback" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Preserve unsupported SQL</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.generateModels" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Generate models</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.generateFactories" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Generate factories</label>
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700"><input type="checkbox" x-model="options.generateSeeders" @change="convert()" class="cursor-pointer rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Generate INSERT seeders</label>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[1.2fr_.8fr]">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-3 py-2"><h2 class="text-sm font-semibold text-slate-900">Conversion report</h2></div>
            <div class="grid grid-cols-2 gap-2 p-3 sm:grid-cols-4">
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-lg font-semibold text-slate-900" x-text="report.tables"></div><div class="text-[10px] text-slate-500">Tables</div></div>
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-lg font-semibold text-slate-900" x-text="report.columns"></div><div class="text-[10px] text-slate-500">Columns</div></div>
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-lg font-semibold text-slate-900" x-text="report.converted"></div><div class="text-[10px] text-slate-500">Converted</div></div>
                <div class="rounded-lg bg-slate-50 p-2"><div class="text-lg font-semibold text-amber-700" x-text="report.warnings"></div><div class="text-[10px] text-slate-500">Warnings</div></div>
            </div>
            <div class="max-h-72 overflow-auto border-t border-slate-100 px-3 py-2" aria-live="polite">
                <template x-if="!diagnostics.length"><p class="py-4 text-xs text-slate-500">Diagnostics will appear after conversion.</p></template>
                <template x-for="(item, index) in diagnostics" :key="index">
                    <div class="flex gap-2 border-b border-slate-100 py-2 last:border-0">
                        <span class="mt-0.5 text-[10px] font-semibold" :class="item.level === 'error' ? 'text-red-600' : item.level === 'warning' ? 'text-amber-600' : 'text-emerald-600'" x-text="item.level.toUpperCase()"></span>
                        <div class="min-w-0"><p class="text-xs text-slate-700" x-text="item.message"></p><p x-show="item.detail" class="mt-0.5 text-[10px] text-slate-500" x-text="item.detail"></p></div>
                    </div>
                </template>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-3 py-2"><h2 class="text-sm font-semibold text-slate-900">Detected schema</h2></div>
            <div class="max-h-72 overflow-auto p-3">
                <template x-if="!tables.length"><p class="text-xs text-slate-500">No CREATE TABLE statements detected.</p></template>
                <template x-for="table in tables" :key="table.name">
                    <div class="mb-2 rounded-lg border border-slate-200 p-2 last:mb-0">
                        <div class="flex items-center justify-between gap-2"><span class="font-mono text-xs font-semibold text-slate-800" x-text="table.name"></span><span class="text-[10px] text-slate-500" x-text="table.columns.length + ' columns'"></span></div>
                        <div class="mt-1 flex flex-wrap gap-1"><template x-for="col in table.columns" :key="col.name"><span class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] text-slate-600" x-text="col.name + ': ' + col.type.raw"></span></template></div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <section x-show="comparison.length" x-cloak class="rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="mapping-heading">
        <div class="border-b border-slate-200 px-3 py-2"><h2 id="mapping-heading" class="text-sm font-semibold text-slate-900">Column mapping</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                    <tr><th scope="col" class="px-3 py-2">Table</th><th scope="col" class="px-3 py-2">SQL</th><th scope="col" class="px-3 py-2">Laravel Blueprint</th><th scope="col" class="px-3 py-2">Notes</th></tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in comparison" :key="index">
                        <tr class="border-t border-slate-100"><td class="px-3 py-2 font-mono" x-text="row.table"></td><td class="px-3 py-2 font-mono" x-text="row.sql"></td><td class="px-3 py-2 font-mono text-indigo-700" x-text="row.laravel"></td><td class="px-3 py-2 text-slate-500" x-text="row.notes"></td></tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>

    <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
        <span><strong>Privacy:</strong> SQL is parsed and converted in your browser. It is not submitted to AabiTech.</span>
        <span>Generated migrations target Laravel <span x-text="options.laravel"></span>.</span>
    </div>
@assets
    <style>
        .aabi-code-keyword { color: #93c5fd; font-weight: 600; }
        .aabi-code-type { color: #c4b5fd; }
        .aabi-code-string { color: #86efac; }
        .aabi-code-number { color: #fcd34d; }
        .aabi-code-comment { color: #64748b; font-style: italic; }
        .aabi-code-variable { color: #f9a8d4; }
        .aabi-code-muted { color: #64748b; }
    </style>
    @endassets
@script
    <script>
function sqlLaravelMigrationConverter() {
    const EXAMPLE = `CREATE TABLE users (\n  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n  name VARCHAR(255) NOT NULL,\n  email VARCHAR(255) NOT NULL UNIQUE,\n  status ENUM('active','disabled') NOT NULL DEFAULT 'active',\n  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,\n  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\nCREATE TABLE posts (\n  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n  user_id BIGINT UNSIGNED NOT NULL,\n  title VARCHAR(200) NOT NULL,\n  body TEXT,\n  published_at TIMESTAMP NULL,\n  CONSTRAINT posts_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,\n  INDEX posts_user_idx (user_id)\n);`;

    const state = {
        sql: '', output: '', dragActive: false, copyLabel: 'Copy', statusMessage: '',
        diagnostics: [], tables: [], comparison: [],
        report: { tables: 0, columns: 0, converted: 0, warnings: 0 },
        options: { dialect: 'auto', laravel: '13', style: 'idiomatic', naming: 'preserve', smart: true, separate: true, timestamps: true, softDeletes: true, dbFallback: true, generateModels: false, generateFactories: false, generateSeeders: false, exclude: '' },
        get sourceStats() { const s = this.sql || ''; return `${s.length.toLocaleString()} chars · ${(s.match(/CREATE\s+TABLE/gi) || []).length} table statements`; },
        get outputStats() { return `${this.output.length.toLocaleString()} chars · ${this.output ? this.output.split('\n').length : 0} lines`; },
        get highlightedSql() { return highlightSql(this.sql || ''); },
        get highlightedOutput() { return highlightPhp(this.output || '// Your Laravel migration will appear here.'); },
        syncEditorScroll(event, target) {
            const source = event.target;
            if (target !== 'sql') return;
            const targetEl = source.previousElementSibling;
            if (!targetEl) return;
            targetEl.scrollTop = source.scrollTop;
            targetEl.scrollLeft = source.scrollLeft;
        },
        init() { this.loadExample(); },
        loadExample() { this.sql = EXAMPLE; this.convert(); },
        clearAll() { this.sql = ''; this.output = ''; this.tables = []; this.comparison = []; this.diagnostics = []; this.report = { tables: 0, columns: 0, converted: 0, warnings: 0 }; this.statusMessage = ''; },
        async importFiles(event) { const files = [...(event.target.files || [])]; if (files.length) await this.readFiles(files); event.target.value = ''; },
        async dropFile(event) { this.dragActive = false; const files = [...(event.dataTransfer?.files || [])].filter(file => /\.sql$/i.test(file.name)); if (files.length) await this.readFiles(files); },
        async readFiles(files) {
            const total = files.reduce((sum, file) => sum + file.size, 0);
            if (total > 10 * 1024 * 1024) { this.setDiagnostics([{ level: 'error', message: 'Selected SQL files exceed 10 MB total.', detail: 'Split very large dumps for predictable browser performance.' }]); return; }
            try { const chunks = []; for (const file of files) chunks.push(`-- SOURCE FILE: ${file.name}\n${await file.text()}`); this.sql = chunks.join('\n\n'); this.convert(); this.statusMessage = `Imported ${files.length} SQL file${files.length === 1 ? '' : 's'}.`; }
            catch (error) { this.setDiagnostics([{ level: 'error', message: 'Could not read the SQL file set.', detail: error?.message || 'Unknown file-reading error.' }]); }
        },
        async copyOutput() {
            if (!this.output) return;
            try { if (!navigator.clipboard?.writeText) throw new Error('Clipboard API unavailable.'); await navigator.clipboard.writeText(this.output); this.copyLabel = 'Copied'; setTimeout(() => this.copyLabel = 'Copy', 1400); }
            catch { this.copyLabel = 'Copy failed'; setTimeout(() => this.copyLabel = 'Copy', 1600); }
        },
        downloadOutput() {
            if (!this.output) return;
            const blob = new Blob([this.output], { type: 'text/x-php;charset=utf-8' }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = this.filenameFor(this.tables[0]?.name || 'schema'); document.body.appendChild(a); a.click(); a.remove(); setTimeout(() => URL.revokeObjectURL(url), 1000);
        },
        downloadZip() {
            const files = buildBundleFiles(this.sql, this.options); if (!files.length) return;
            const blob = new Blob([makeZip(files)], { type: 'application/zip' }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = 'laravel-schema-bundle.zip'; document.body.appendChild(a); a.click(); a.remove(); setTimeout(() => URL.revokeObjectURL(url), 1000);
        },
        filenameFor(name) { const stamp = new Date().toISOString().replace(/[-:TZ.]/g, '').slice(0, 14); return `${stamp}_create_${sanitizeName(name)}_table.php`; },
        setDiagnostics(items) { this.diagnostics = items; this.report.warnings = items.filter(item => item.level === 'warning').length; },
        convert() {
            const source = this.sql || '';
            if (!source.trim()) { this.output = ''; this.tables = []; this.comparison = []; this.diagnostics = []; this.report = { tables: 0, columns: 0, converted: 0, warnings: 0 }; this.statusMessage = ''; return; }
            const effective = { ...this.options, smart: this.options.style === 'explicit' ? false : this.options.smart, separate: this.options.style === 'compatibility' ? true : this.options.separate };
            const result = parseSqlSchema(source, effective);
            this.tables = result.tables; this.diagnostics = result.diagnostics; this.comparison = result.comparison;
            this.report = { tables: result.tables.length, columns: result.tables.reduce((sum, table) => sum + table.columns.length, 0), converted: result.converted, warnings: result.diagnostics.filter(item => item.level === 'warning').length };
            if (result.diagnostics.some(item => item.level === 'error')) { this.output = ''; this.statusMessage = 'Conversion stopped because the SQL could not be parsed.'; return; }
            this.output = generateLaravel(result, this.options);
            this.statusMessage = `Converted ${result.tables.length} table${result.tables.length === 1 ? '' : 's'}.`;
        }
    };

    const PHP_OPEN = '<' + '?php';
    if (typeof window !== 'undefined') window.sqlLaravelMigrationConverterEngine = { parseSqlSchema, generateLaravel, buildBundleFiles, makeZip };
    return state;

    function escapeHtml(value) {
        return String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function highlightSql(value) {
        let html = escapeHtml(value);
        const strings = [];
        html = html.replace(/(?:&#039;|&quot;).*?(?:&#039;|&quot;)/g, match => { strings.push(match); return `\u0000S${strings.length - 1}\u0000`; });
        html = html.replace(/(\/\*[\s\S]*?\*\/|--[^\n]*|#[^\n]*)/g, '<span class="aabi-code-comment">$1</span>');
        html = html.replace(/\b(CREATE|TABLE|ALTER|DROP|PRIMARY|KEY|FOREIGN|REFERENCES|CONSTRAINT|UNIQUE|INDEX|CHECK|NOT|NULL|DEFAULT|AUTO_INCREMENT|IDENTITY|GENERATED|ALWAYS|AS|INSERT|INTO|VALUES|SELECT|FROM|WHERE|AND|OR|ON|DELETE|UPDATE|CASCADE|RESTRICT|SET|CURRENT_TIMESTAMP|ENGINE|CHARSET|COLLATE|IF|EXISTS)\b/gi, '<span class="aabi-code-keyword">$1</span>');
        html = html.replace(/\b(BIGINT|INT|INTEGER|SMALLINT|TINYINT|DECIMAL|NUMERIC|FLOAT|DOUBLE|REAL|BOOLEAN|BOOL|VARCHAR|CHAR|TEXT|MEDIUMTEXT|LONGTEXT|DATE|DATETIME|TIMESTAMP|TIME|JSON|JSONB|UUID|SERIAL|BIGSERIAL|ENUM|BLOB|BYTEA|VARBINARY)\b/gi, '<span class="aabi-code-type">$1</span>');
        html = html.replace(/\b(\d+(?:\.\d+)?)\b/g, '<span class="aabi-code-number">$1</span>');
        html = html.replace(/\u0000S(\d+)\u0000/g, (_, index) => `<span class="aabi-code-string">${strings[Number(index)]}</span>`);
        return html || '<span class="aabi-code-muted">&nbsp;</span>';
    }

    function highlightPhp(value) {
        let html = escapeHtml(value);
        const strings = [];
        html = html.replace(/(?:&#039;|&quot;).*?(?:&#039;|&quot;)/g, match => { strings.push(match); return `\u0000S${strings.length - 1}\u0000`; });
        html = html.replace(/(\/\*[\s\S]*?\*\/|\/\/[^\n]*|#[^\n]*)/g, '<span class="aabi-code-comment">$1</span>');
        html = html.replace(/\b(php|namespace|use|class|extends|implements|public|protected|private|function|return|new|if|else|foreach|as|true|false|null|void|array|declare|strict_types)\b/gi, '<span class="aabi-code-keyword">$1</span>');
        html = html.replace(/\b(Schema|Blueprint|DB|Factory|Model|Seeder|Str)\b/g, '<span class="aabi-code-type">$1</span>');
        html = html.replace(/(\$[A-Za-z_][A-Za-z0-9_]*)/g, '<span class="aabi-code-variable">$1</span>');
        html = html.replace(/\b(\d+(?:\.\d+)?)\b/g, '<span class="aabi-code-number">$1</span>');
        html = html.replace(/\u0000S(\d+)\u0000/g, (_, index) => `<span class="aabi-code-string">${strings[Number(index)]}</span>`);
        return html || '<span class="aabi-code-muted">&nbsp;</span>';
    }

    function stripComments(sql) { return String(sql).replace(/\/\*[\s\S]*?\*\//g, ' ').replace(/--[^\r\n]*/g, ' ').replace(/(^|\s)#[^\r\n]*/g, '$1 '); }
    function splitTopLevel(text, separator = ',') {
        const out = []; let start = 0, depth = 0, quote = null;
        for (let i = 0; i < text.length; i++) { const c = text[i];
            if (quote) { if (c === quote) { if (text[i + 1] === quote) i++; else quote = null; } else if (c === '\\' && quote !== ']') i++; continue; }
            if (c === "'" || c === '"' || c === '`' || c === '[') { quote = c === '[' ? ']' : c; continue; }
            if (c === '(') depth++; else if (c === ')') depth = Math.max(0, depth - 1); else if (c === separator && depth === 0) { out.push(text.slice(start, i).trim()); start = i + 1; }
        }
        if (text.slice(start).trim()) out.push(text.slice(start).trim()); return out;
    }
    function findCreateStatements(sql) {
        const source = stripComments(sql), out = [], re = /CREATE\s+(?:TEMPORARY\s+)?TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?/gi; let match;
        while ((match = re.exec(source))) {
            let i = match.index + match[0].length; while (/\s/.test(source[i] || '')) i++; let name = '';
            if (source[i] === '`' || source[i] === '"' || source[i] === '[') { const q = source[i], end = q === '[' ? ']' : q, j = source.indexOf(end, i + 1); if (j < 0) continue; name = source.slice(i + 1, j); i = j + 1; }
            else { const nm = source.slice(i).match(/^[^\s(]+/); if (!nm) continue; name = nm[0].replace(/["`\[\]]/g, ''); i += nm[0].length; }
            while (/\s/.test(source[i] || '')) i++; if (source[i] !== '(') continue;
            const bodyStart = i + 1; let depth = 1, quote = null, j = bodyStart;
            for (; j < source.length && depth; j++) { const c = source[j]; if (quote) { if (c === quote) { if (source[j + 1] === quote) j++; else quote = null; } continue; } if (c === "'" || c === '"' || c === '`' || c === '[') quote = c === '[' ? ']' : c; else if (c === '(') depth++; else if (c === ')') depth--; }
            if (depth !== 0) continue; let tail = j; while (tail < source.length && source[tail] !== ';' && source[tail] !== '\n') tail++;
            out.push({ name: name.replace(/\s+/g, ''), body: source.slice(bodyStart, j - 1), tail: source.slice(j, tail), start: match.index });
        }
        return out;
    }
    function unquote(value) { if (value == null) return value; const v = String(value).trim(); if ((v.startsWith("'") && v.endsWith("'")) || (v.startsWith('"') && v.endsWith('"')) || (v.startsWith('`') && v.endsWith('`'))) return v.slice(1, -1).replace(/''/g, "'"); return v; }
    function parseColumn(part) {
        const match = part.match(/^(?:`([^`]+)`|"([^"]+)"|\[([^\]]+)\]|([^\s]+))\s+([A-Za-z]+(?:\s+WITH\s+TIME\s+ZONE)?)(?:\s*\(([^)]*)\))?([\s\S]*)$/i); if (!match) return null;
        const name = match[1] || match[2] || match[3] || match[4], rawType = match[5].toLowerCase(), args = (match[6] || '').trim(), rest = match[7] || '', col = { name, type: { raw: rawType, args }, rest, nullable: !/\bNOT\s+NULL\b/i.test(rest), primary: /\bPRIMARY\s+KEY\b/i.test(rest), unique: /\bUNIQUE\b/i.test(rest), autoIncrement: /\bAUTO_INCREMENT\b|\bIDENTITY(?:\s*\([^)]*\))?|\bGENERATED\s+ALWAYS\s+AS\s+IDENTITY\b/i.test(rest) || ['serial','bigserial','smallserial'].includes(rawType), unsigned: /\bUNSIGNED\b/i.test(rest), default: null, comment: null, generated: null, position: 0 };
        const dm = rest.match(/\bDEFAULT\s+((?:'(?:''|[^'])*')|(?:"(?:""|[^"])*")|(?:CURRENT_TIMESTAMP(?:\s*\([^)]*\))?)|(?:NULL)|(?:TRUE|FALSE)|(?:[+-]?\d+(?:\.\d+)?)|(?:[^\s]+))/i); if (dm) col.default = dm[1];
        const cm = rest.match(/\bCOMMENT\s+('(?:''|[^'])*'|"(?:""|[^"])*")/i); if (cm) col.comment = unquote(cm[1]);
        const gm = rest.match(/\b(?:GENERATED\s+(?:ALWAYS\s+)?AS|AS)\s*\(([^)]*)\)/i); if (gm) col.generated = gm[1];
        return col;
    }
    function parseTable(statement, diagnostics) {
        const table = { name: statement.name, columns: [], primaryKeys: [], indexes: [], uniques: [], foreignKeys: [], checks: [], options: statement.tail || '', sourceStart: statement.start };
        splitTopLevel(statement.body).forEach((part, index) => {
            const p = part.trim().replace(/\s+/g, ' '); let match;
            if (/^(?:CONSTRAINT\s+[^\s]+\s+)?PRIMARY\s+KEY/i.test(p)) { match = p.match(/PRIMARY\s+KEY\s*\(([^)]+)\)/i); if (match) table.primaryKeys = splitTopLevel(match[1]).map(unquote); return; }
            if (/^(?:CONSTRAINT\s+[^\s]+\s+)?(?:UNIQUE|UNIQUE\s+KEY)/i.test(p)) { match = p.match(/(?:UNIQUE(?:\s+KEY)?|UNIQUE)\s*(?:`([^`]+)`|"([^"]+)"|([A-Za-z0-9_]+))?\s*\(([^)]+)\)/i); if (!match) match = p.match(/UNIQUE\s*\(([^)]+)\)/i), match && (table.uniques.push({ name: null, columns: splitTopLevel(match[1]).map(unquote) }), match = null); if (match) table.uniques.push({ name: match[1] || match[2] || match[3] || null, columns: splitTopLevel(match[4]).map(unquote) }); return; }
            if (/^(?:CONSTRAINT\s+[^\s]+\s+)?FOREIGN\s+KEY/i.test(p)) { match = p.match(/^(?:CONSTRAINT\s+([^\s]+)\s+)?FOREIGN\s+KEY\s*\(([^)]+)\)\s+REFERENCES\s+([^\s(]+)\s*\(([^)]+)\)([\s\S]*)$/i); if (match) table.foreignKeys.push({ name: match[1] || null, columns: splitTopLevel(match[2]).map(unquote), refTable: unquote(match[3]), refColumns: splitTopLevel(match[4]).map(unquote), onDelete: (match[5].match(/ON\s+DELETE\s+(CASCADE|SET\s+NULL|SET\s+DEFAULT|RESTRICT|NO\s+ACTION)/i) || [])[1] || null, onUpdate: (match[5].match(/ON\s+UPDATE\s+(CASCADE|SET\s+NULL|SET\s+DEFAULT|RESTRICT|NO\s+ACTION)/i) || [])[1] || null }); return; }
            if (/^(?:CONSTRAINT\s+)?CHECK\b/i.test(p)) { table.checks.push(p); return; }
            if (/^(?:KEY|INDEX)\b/i.test(p)) { match = p.match(/^(?:KEY|INDEX)\s*(?:`([^`]+)`|"([^"]+)"|([A-Za-z0-9_]+))?\s*\(([^)]+)\)/i); if (match) table.indexes.push({ name: match[1] || match[2] || match[3] || null, columns: splitTopLevel(match[4]).map(unquote), unique: false }); return; }
            if (/^(?:FULLTEXT|SPATIAL)\s+(?:KEY|INDEX)\b/i.test(p)) { match = p.match(/^(?:FULLTEXT|SPATIAL)\s+(?:KEY|INDEX)\s*(?:`([^`]+)`|"([^"]+)"|([A-Za-z0-9_]+))?\s*\(([^)]+)\)/i); if (match) table.indexes.push({ name: match[1] || match[2] || match[3] || null, columns: splitTopLevel(match[4]).map(unquote), unique: false, special: true }); return; }
            const col = parseColumn(p); if (col) { col.position = index; table.columns.push(col); if (col.primary) table.primaryKeys.push(col.name); if (col.unique) table.uniques.push({ name: null, columns: [col.name] }); } else diagnostics.push({ level: 'warning', message: `Unrecognized table definition in ${table.name}.`, detail: p.slice(0, 180) });
        });
        if (!table.columns.length) diagnostics.push({ level: 'error', message: `Table ${table.name} contains no recognizable columns.`, detail: 'Check the CREATE TABLE syntax.' });
        return table;
    }
    function detectDialect(source) { const u = source.toUpperCase(); if (/\bGO\s*$/m.test(u) || /\bIDENTITY\s*\(/.test(u) || /\bNVARCHAR\b/.test(u)) return 'sqlserver'; if (/\bSERIAL\b|\bJSONB\b|\bILIKE\b|DOUBLE\s+PRECISION/.test(u)) return 'postgresql'; if (/AUTOINCREMENT/.test(u) && !/AUTO_INCREMENT/.test(u)) return 'sqlite'; return 'mysql'; }
    function phpString(value) { return "'" + String(value ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/\r?\n/g, '\\n') + "'"; }
    function mapType(col, options) {
        const t = col.type.raw.toLowerCase(), args = col.type.args ? splitTopLevel(col.type.args).map(x => x.trim()) : [], name = phpString(col.name); let method = null, notes = '';
        if (col.primary && col.autoIncrement && ['bigint','bigserial','int','integer','smallint','tinyint','serial'].includes(t) && col.name.toLowerCase() === 'id') return { method: ['bigint','bigserial'].includes(t) ? '$table->id()' : "$table->increments('id')", notes: 'Auto-increment primary key' };
        switch (t) {
            case 'bigint': case 'bigserial': method = col.unsigned ? `$table->unsignedBigInteger(${name})` : `$table->bigInteger(${name})`; break;
            case 'int': case 'integer': case 'serial': method = col.unsigned ? `$table->unsignedInteger(${name})` : `$table->integer(${name})`; break;
            case 'smallint': case 'smallserial': method = col.unsigned ? `$table->unsignedSmallInteger(${name})` : `$table->smallInteger(${name})`; break;
            case 'tinyint': method = /^tinyint\s*\(1\)$/i.test(col.type.raw) || args[0] === '1' ? `$table->boolean(${name})` : col.unsigned ? `$table->unsignedTinyInteger(${name})` : `$table->tinyInteger(${name})`; break;
            case 'mediumint': method = col.unsigned ? `$table->unsignedMediumInteger(${name})` : `$table->mediumInteger(${name})`; break;
            case 'varchar': case 'nvarchar': method = `$table->string(${name}, ${Number(args[0]) || 255})`; if (t === 'nvarchar') notes = 'NVARCHAR mapped to string(); verify Unicode/collation requirements.'; break;
            case 'char': case 'nchar': method = `$table->char(${name}, ${Number(args[0]) || 255})`; break;
            case 'text': method = `$table->text(${name})`; break; case 'tinytext': method = `$table->tinyText(${name})`; break; case 'mediumtext': method = `$table->mediumText(${name})`; break; case 'longtext': method = `$table->longText(${name})`; break;
            case 'decimal': case 'numeric': method = `$table->decimal(${name}, ${Number(args[0]) || 8}, ${Number(args[1]) || 2})`; break;
            case 'float': method = `$table->float(${name}${args[0] ? `, ${Number(args[0])}` : ''}${args[1] ? `, ${Number(args[1])}` : ''})`; break;
            case 'double': case 'double precision': method = `$table->double(${name})`; break;
            case 'real': method = `$table->double(${name})`; notes = 'REAL mapped to double().'; break;
            case 'boolean': case 'bool': method = `$table->boolean(${name})`; break;
            case 'date': method = `$table->date(${name})`; break; case 'datetime': case 'datetime2': method = `$table->dateTime(${name}${args[0] ? `, ${Number(args[0])}` : ''})`; break; case 'datetimeoffset': method = `$table->dateTimeTz(${name}${args[0] ? `, ${Number(args[0])}` : ''})`; break; case 'timestamp': case 'timestamptz': method = `$table->timestamp(${name}${args[0] ? `, ${Number(args[0])}` : ''})`; break; case 'time': method = `$table->time(${name})`; break;
            case 'json': case 'jsonb': method = `$table->json(${name})`; if (t === 'jsonb') notes = 'JSONB mapped to json(); verify PostgreSQL-specific behavior.'; break;
            case 'uuid': method = `$table->uuid(${name})`; break; case 'binary': case 'varbinary': method = `$table->binary(${name})`; break; case 'blob': case 'tinyblob': case 'mediumblob': case 'longblob': method = `$table->binary(${name})`; notes = `${t.toUpperCase()} mapped to binary().`; break;
            case 'enum': method = `$table->enum(${name}, [${args.map(unquote).map(phpString).join(', ')}])`; break; case 'set': method = `$table->set(${name}, [${args.map(unquote).map(phpString).join(', ')}])`; notes = 'SET is database-specific; review generated migration.'; break;
            case 'money': case 'smallmoney': method = `$table->decimal(${name}, 19, 4)`; notes = 'Money type approximated as decimal(19,4).'; break;
            default: method = `$table->string(${name})`; notes = `${t.toUpperCase()} is not directly portable; mapped to string().`;
        }
        return { method, notes };
    }
    function applyColumnModifiers(col, base) {
        let s = base; if (col.unsigned && !/unsigned/i.test(s) && /integer|bigInteger|smallInteger|tinyInteger|mediumInteger|decimal|float|double/i.test(s)) s += '->unsigned()';
        if (col.nullable && !col.autoIncrement) s += '->nullable()'; else if (!col.autoIncrement && !/->(increments|id)\(/.test(s)) s += '->nullable(false)';
        if (col.default !== null) { const d = col.default.trim(); if (/^CURRENT_TIMESTAMP/i.test(d)) s += '->useCurrent()'; else if (/^NULL$/i.test(d)) s += '->default(null)'; else if (/^(TRUE|FALSE)$/i.test(d)) s += `->default(${d.toLowerCase()})`; else if (/^[+-]?\d+(?:\.\d+)?$/.test(d)) s += `->default(${d})`; else s += `->default(${phpString(unquote(d))})`; }
        if (col.comment) s += `->comment(${phpString(col.comment)})`; if (col.unique) s += '->unique()'; return s;
    }
    function constraintLines(table) { const lines = []; for (const u of table.uniques) lines.push(u.columns.length === 1 ? `$table->unique(${phpString(u.columns[0])}${u.name ? `, ${phpString(u.name)}` : ''});` : `$table->unique([${u.columns.map(phpString).join(', ')}]${u.name ? `, ${phpString(u.name)}` : ''});`); for (const idx of table.indexes) lines.push(idx.special ? `// ${idx.name || 'Special'} index on ${idx.columns.join(', ')} requires database-specific review.` : idx.columns.length === 1 ? `$table->index(${phpString(idx.columns[0])}${idx.name ? `, ${phpString(idx.name)}` : ''});` : `$table->index([${idx.columns.map(phpString).join(', ')}]${idx.name ? `, ${phpString(idx.name)}` : ''});`); return lines; }
    function foreignLine(f, options) { const conventional = f.columns.length === 1 && f.refColumns.length === 1 && f.refColumns[0] === 'id' && /_id$/i.test(f.columns[0]); let s; if (options.smart && conventional) s = `$table->foreignId(${phpString(f.columns[0])})->constrained(${phpString(f.refTable)})`; else s = f.columns.length === 1 ? `$table->foreign(${phpString(f.columns[0])})` : `$table->foreign([${f.columns.map(phpString).join(', ')}])`, s += f.refColumns.length === 1 ? `->references(${phpString(f.refColumns[0])})` : `->references([${f.refColumns.map(phpString).join(', ')}])`, s += `->on(${phpString(f.refTable)})`;
        if (f.onDelete) { const x = f.onDelete.toUpperCase(); if (x === 'CASCADE') s += '->cascadeOnDelete()'; else if (x === 'SET NULL') s += '->nullOnDelete()'; else if (x === 'RESTRICT') s += '->restrictOnDelete()'; }
        if (f.onUpdate) { const x = f.onUpdate.toUpperCase(); if (x === 'CASCADE') s += '->cascadeOnUpdate()'; else if (x === 'RESTRICT') s += '->restrictOnUpdate()'; }
        if (f.name) s += `->name(${phpString(f.name)})`; return s + ';';
    }
    function tableOptions(table) { const lines = [], s = table.options || ''; const engine = s.match(/ENGINE\s*=\s*([A-Za-z0-9_]+)/i), charset = s.match(/(?:CHARSET|CHARACTER\s+SET)\s*=\s*([A-Za-z0-9_]+)/i), collate = s.match(/COLLATE\s*=\s*([A-Za-z0-9_]+)/i), comment = s.match(/COMMENT\s*=\s*['"]([^'"]+)['"]/i); if (engine) lines.push(`// SQL engine: ${engine[1]}`); if (charset) lines.push(`// Character set: ${charset[1]}`); if (collate) lines.push(`// Collation: ${collate[1]}`); if (comment) lines.push(`$table->comment(${phpString(comment[1])});`); return lines; }
    function generateTableBody(table, options, diagnostics, comparison) {
        const lines = [], timestampNames = ['created_at', 'updated_at'];
        const cols = table.columns.filter(col => !(options.timestamps && timestampNames.includes(col.name.toLowerCase())) && !(options.softDeletes && col.name.toLowerCase() === 'deleted_at'));
        for (const col of cols) { const mapped = mapType(col, options); let base = mapped.method; if (options.smart && col.name.toLowerCase() === 'id' && col.primary && col.autoIncrement && /bigint|bigserial/i.test(col.type.raw)) base = '$table->id()'; else if (options.smart && /_id$/i.test(col.name) && col.unsigned && col.type.raw === 'bigint' && table.foreignKeys.some(f => f.columns.length === 1 && f.columns[0] === col.name && f.refColumns[0] === 'id')) base = `$table->foreignId(${phpString(col.name)})`;
            if (col.primary && !(col.name.toLowerCase() === 'id' && col.autoIncrement)) base += '->primary()'; const final = applyColumnModifiers({ ...col, unique: false }, base); if (mapped.notes) diagnostics.push({ level: 'warning', message: `${table.name}.${col.name}: ${mapped.notes}`, detail: 'Review the generated Blueprint for exact database semantics.' }); lines.push(final + ';'); if (col.generated) lines.push(`// Generated expression for ${col.name}: ${col.generated}`); comparison.push({ table: table.name, sql: `${col.type.raw}${col.type.args ? `(${col.type.args})` : ''}`, laravel: final, notes: (mapped.notes || 'Direct mapping') + (col.generated ? ' · generated expression requires review' : '') }); }
        if (options.timestamps && hasCol(table, 'created_at') && hasCol(table, 'updated_at')) lines.push('$table->timestamps();'); else for (const name of timestampNames.filter(n => hasCol(table, n))) lines.push(`$table->timestamp(${phpString(name)})->nullable();`);
        if (options.softDeletes && hasCol(table, 'deleted_at')) lines.push('$table->softDeletes();'); if (table.primaryKeys.length > 1) lines.push(`$table->primary([${table.primaryKeys.map(phpString).join(', ')}]);`); lines.push(...constraintLines(table)); if (!options.separate) for (const f of table.foreignKeys) lines.push(foreignLine(f, options)); lines.push(...tableOptions(table)); return lines;
    }
    function dependencyOrder(tables) { const names = new Map(tables.map(t => [t.name.toLowerCase(), t])), indeg = new Map(tables.map(t => [t.name.toLowerCase(), 0])), adj = new Map(tables.map(t => [t.name.toLowerCase(), []])); for (const t of tables) for (const f of t.foreignKeys) { const target = f.refTable.toLowerCase(); if (names.has(target) && target !== t.name.toLowerCase()) { adj.get(target).push(t.name.toLowerCase()); indeg.set(t.name.toLowerCase(), indeg.get(t.name.toLowerCase()) + 1); } } const queue = tables.filter(t => indeg.get(t.name.toLowerCase()) === 0).map(t => t.name.toLowerCase()), order = []; while (queue.length) { const n = queue.shift(); order.push(names.get(n)); for (const child of adj.get(n) || []) { indeg.set(child, indeg.get(child) - 1); if (indeg.get(child) === 0) queue.push(child); } } const cycle = tables.filter(t => !order.includes(t)); return { order: order.concat(cycle), cycle }; }
    function migrationForTable(table, options, diagnostics, comparison) { const body = generateTableBody(table, options, diagnostics, comparison), lines = [PHP_OPEN, '', 'use Illuminate\\Database\\Migrations\\Migration;', 'use Illuminate\\Database\\Schema\\Blueprint;', 'use Illuminate\\Support\\Facades\\Schema;', '', 'return new class extends Migration', '{', '    public function up(): void', '    {', `        Schema::create(${phpString(table.name)}, function (Blueprint $table): void`, '        {', ...body.map(line => '            ' + line), '        });']; if (options.separate && table.foreignKeys.length) { lines.push('', '        // Foreign keys are added after table creation for dependency safety.'); for (const f of table.foreignKeys) lines.push('        ' + foreignLine(f, options)); } lines.push('    }', '', '    public function down(): void', '    {', `        Schema::dropIfExists(${phpString(table.name)});`, '    }', '};', ''); return lines.join('\n'); }
    function generateLaravel(result, options) { const ordered = dependencyOrder(result.tables); if (ordered.cycle.length) result.diagnostics.push({ level: 'warning', message: 'Circular foreign-key dependency detected.', detail: 'Foreign keys are generated in a separate phase. Review cyclic relationships before migrating.' }); const chunks = [], comparison = result.comparison || []; for (const table of ordered.order) chunks.push(`// database/migrations/${timestampFor(table, ordered.order)}_create_${snake(table.name)}_table.php\n` + migrationForTable(table, { ...options, smart: options.style === 'explicit' ? false : options.smart, separate: options.style === 'compatibility' ? true : options.separate }, result.diagnostics, comparison)); if (options.dbFallback) for (const table of result.tables) for (const check of table.checks) chunks.push(`// ${table.name}: CHECK constraint preserved for review.\n// DB::statement(${phpString(check)});`); return chunks.join('\n\n'); }
    function timestampFor(table, order) { const now = new Date(), y = now.getFullYear(), m = String(now.getMonth() + 1).padStart(2, '0'), d = String(now.getDate()).padStart(2, '0'), i = order.indexOf(table); return `${y}_${m}_${d}_${String(Math.floor(i / 3600)).padStart(2, '0')}${String(Math.floor((i % 3600) / 60)).padStart(2, '0')}${String(i % 60).padStart(2, '0')}`; }
    function snake(value) { return String(value).replace(/([a-z0-9])([A-Z])/g, '$1_$2').replace(/[^A-Za-z0-9]+/g, '_').replace(/^_+|_+$/g, '').toLowerCase(); }
    function sanitizeName(name) { return snake(name) || 'table'; }
    function studly(value) { return snake(value).split('_').filter(Boolean).map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(''); }
    function modelForTable(table) { const excluded = new Set(['id','created_at','updated_at','deleted_at']), fillable = table.columns.filter(c => !excluded.has(c.name)).map(c => phpString(c.name)); const casts = table.columns.filter(c => ['json','jsonb','boolean','bool','date','datetime','datetime2','timestamp','timestamptz'].includes(c.type.raw)).map(c => `        '${c.name}' => '${c.type.raw.startsWith('json') ? 'array' : c.type.raw.startsWith('bool') ? 'boolean' : c.type.raw === 'date' ? 'date' : 'datetime'}',`); return [PHP_OPEN, '', 'namespace App\\Models;', '', 'use Illuminate\\Database\\Eloquent\\Model;', '', `class ${studly(table.name)} extends Model`, '{', `    protected $table = ${phpString(table.name)};`, '', '    protected $fillable = [', ...fillable.map((x, i) => `        ${x}${i < fillable.length - 1 ? ',' : ''}`), '    ];', ...(casts.length ? ['', '    protected $casts = [', ...casts, '    ];'] : []), '}', ''].join('\n'); }
    function fakerFor(col) { const t = col.type.raw, n = col.name.toLowerCase(); if (n.includes('email')) return '$this->faker->safeEmail()'; if (n.includes('name')) return '$this->faker->name()'; if (t.includes('bool')) return '$this->faker->boolean()'; if (['int','integer','smallint','tinyint','bigint'].includes(t)) return '$this->faker->numberBetween(1, 1000)'; if (t === 'date') return '$this->faker->date()'; if (t.includes('timestamp') || t.includes('datetime')) return '$this->faker->dateTime()'; if (t === 'json' || t === 'jsonb') return '[]'; if (t === 'uuid') return '(string) Str::uuid()'; return '$this->faker->sentence()'; }
    function factoryForTable(table) { const lines = table.columns.filter(c => !['id','created_at','updated_at','deleted_at'].includes(c.name)).map(c => `            ${phpString(c.name)} => ${fakerFor(c)},`); const needsStr = lines.some(line => line.includes('Str::uuid()')); return [PHP_OPEN, '', 'namespace Database\\Factories;', '', 'use App\\Models\\' + studly(table.name) + ';', 'use Illuminate\\Database\\Eloquent\\Factories\\Factory;', ...(needsStr ? ['use Illuminate\\Support\\Str;', ''] : ['']), `class ${studly(table.name)}Factory extends Factory`, '{', `    protected $model = ${studly(table.name)}::class;`, '', '    public function definition(): array', '    {', '        return [', ...lines, '        ];', '    }', '}', ''].join('\n'); }
    function parseInsertStatements(source) { const map = new Map(), clean = stripComments(source), re = /INSERT\s+INTO\s+(?:`([^`]+)`|"([^"]+)"|([A-Za-z0-9_.]+))\s*(?:\(([^)]*)\))?\s*VALUES\s*/gi; let match; while ((match = re.exec(clean))) { let i = re.lastIndex, depth = 0, quote = null, start = i, rows = []; for (; i < clean.length; i++) { const c = clean[i]; if (quote) { if (c === quote) { if (clean[i + 1] === quote) i++; else quote = null; } continue; } if (c === "'" || c === '"' || c === '`') { quote = c; continue; } if (c === '(') { if (depth === 0) start = i; depth++; } else if (c === ')') { depth--; if (depth === 0) { rows.push(clean.slice(start + 1, i)); if (clean[i + 1] !== ',') break; i++; } } } const table = (match[1] || match[2] || match[3] || '').trim(); const columns = match[4] ? splitTopLevel(match[4]).map(unquote) : []; const parsedRows = rows.map(row => splitTopLevel(row)); if (table) map.set(table.toLowerCase(), { columns, rows: parsedRows }); re.lastIndex = Math.max(re.lastIndex, i); } return map; }
    function phpValue(value) { const x = String(value ?? '').trim(); if (/^NULL$/i.test(x)) return 'null'; if (/^(TRUE|FALSE)$/i.test(x)) return x.toLowerCase(); if (/^-?\d+(?:\.\d+)?$/.test(x)) return x; if (/^CURRENT_TIMESTAMP/i.test(x)) return 'now()'; return phpString(unquote(x)); }
    function seederForTable(table, data) { const cols = data.columns.length ? data.columns : table.columns.map(c => c.name); const rows = data.rows.map(row => `            [${cols.map((col, i) => `${phpString(col)} => ${phpValue(row[i] ?? 'NULL')}`).join(', ')}],`); return [PHP_OPEN, '', 'namespace Database\\Seeders;', '', 'use Illuminate\\Database\\Seeder;', 'use Illuminate\\Support\\Facades\\DB;', '', `class ${studly(table.name)}Seeder extends Seeder`, '{', '    public function run(): void', '    {', `        DB::table(${phpString(table.name)})->insert([`, ...rows, '        ]);', '    }', '}', ''].join('\n'); }
    function buildBundleFiles(source, options) { const effective = { ...options, smart: options.style === 'explicit' ? false : options.smart, separate: options.style === 'compatibility' ? true : options.separate }, result = parseSqlSchema(source, effective); if (result.diagnostics.some(d => d.level === 'error')) return []; const ordered = dependencyOrder(result.tables).order, files = [], comparison = []; for (const table of ordered) files.push({ name: `database/migrations/${timestampFor(table, ordered)}_create_${snake(table.name)}_table.php`, data: migrationForTable(table, effective, result.diagnostics, comparison) }); if (options.generateModels) for (const table of ordered) files.push({ name: `app/Models/${studly(table.name)}.php`, data: modelForTable(table) }); if (options.generateFactories) for (const table of ordered) files.push({ name: `database/factories/${studly(table.name)}Factory.php`, data: factoryForTable(table) }); if (options.generateSeeders) { const seeders = parseInsertStatements(source); for (const table of ordered.filter(t => seeders.has(t.name.toLowerCase()))) files.push({ name: `database/seeders/${studly(table.name)}Seeder.php`, data: seederForTable(table, seeders.get(table.name.toLowerCase())) }); } return files; }
    function makeZip(files) { const enc = new TextEncoder(), parts = [], central = []; let offset = 0; for (const file of files) { const name = enc.encode(file.name), data = enc.encode(file.data), crc = crc32(data), local = new Uint8Array(30 + name.length + data.length), dv = new DataView(local.buffer); dv.setUint32(0, 0x04034b50, true); dv.setUint16(4, 20, true); dv.setUint16(8, 0, true); dv.setUint32(14, crc, true); dv.setUint32(18, data.length, true); dv.setUint32(22, data.length, true); dv.setUint16(26, name.length, true); local.set(name, 30); local.set(data, 30 + name.length); parts.push(local); const c = new Uint8Array(46 + name.length), cd = new DataView(c.buffer); cd.setUint32(0, 0x02014b50, true); cd.setUint16(4, 20, true); cd.setUint16(6, 20, true); cd.setUint32(16, crc, true); cd.setUint32(20, data.length, true); cd.setUint32(24, data.length, true); cd.setUint16(28, name.length, true); cd.setUint32(42, offset, true); c.set(name, 46); central.push(c); offset += local.length; } const centralSize = central.reduce((n, c) => n + c.length, 0), end = new Uint8Array(22), ed = new DataView(end.buffer); ed.setUint32(0, 0x06054b50, true); ed.setUint16(8, files.length, true); ed.setUint16(10, files.length, true); ed.setUint32(12, centralSize, true); ed.setUint32(16, offset, true); const all = [...parts, ...central, end], blob = new Uint8Array(all.reduce((n, a) => n + a.length, 0)); let pos = 0; for (const part of all) { blob.set(part, pos); pos += part.length; } return blob; }
    function crc32(data) { let crc = 0xffffffff; for (const byte of data) { crc ^= byte; for (let i = 0; i < 8; i++) crc = (crc >>> 1) ^ ((crc & 1) ? 0xedb88320 : 0); } return (crc ^ 0xffffffff) >>> 0; }
    function hasCol(table, name) { return table.columns.some(c => c.name.toLowerCase() === name.toLowerCase()); }
    function parseSqlSchema(source, options) {
        const diagnostics = [], dialect = options.dialect === 'auto' ? detectDialect(source) : options.dialect, statements = findCreateStatements(source), tables = [];
        if (!statements.length) diagnostics.push({ level: 'error', message: 'No CREATE TABLE statement was found.', detail: 'Paste SQL DDL containing one or more CREATE TABLE statements.' });
        for (const statement of statements) { const table = parseTable(statement, diagnostics); table.name = options.naming === 'snake' ? snake(table.name) : options.naming === 'lower' ? table.name.toLowerCase() : table.name; for (const col of table.columns) col.name = options.naming === 'snake' ? snake(col.name) : options.naming === 'lower' ? col.name.toLowerCase() : col.name; tables.push(table); }
        const excluded = new Set(String(options.exclude || '').split(',').map(x => x.trim().toLowerCase()).filter(Boolean)); for (let i = tables.length - 1; i >= 0; i--) if (excluded.has(tables[i].name.toLowerCase())) tables.splice(i, 1);
        const names = new Set(tables.map(t => t.name.toLowerCase())); for (const table of tables) { for (const fk of table.foreignKeys) { if (!names.has(fk.refTable.toLowerCase())) diagnostics.push({ level: 'warning', message: `${table.name}: referenced table ${fk.refTable} was not included in the input.`, detail: 'The foreign key is preserved but cannot be dependency-ordered against the missing table.' }); if (fk.columns.length !== fk.refColumns.length) diagnostics.push({ level: 'error', message: `Foreign key column count mismatch in ${table.name}.`, detail: `${fk.columns.length} local columns vs ${fk.refColumns.length} referenced columns.` }); } for (const col of table.columns.filter(c => c.generated)) diagnostics.push({ level: 'warning', message: `${table.name}.${col.name}: generated/computed column needs review.`, detail: `Expression: ${col.generated}` }); if (table.checks.length) diagnostics.push({ level: 'warning', message: `${table.name}: CHECK constraints are database-specific.`, detail: 'Review the generated DB::statement fallback.' }); }
        const comparison = [], converted = tables.reduce((sum, table) => sum + table.columns.length, 0); return { tables, diagnostics, comparison, converted, dialect };
    }
}

if (typeof window !== 'undefined') window.sqlLaravelMigrationConverter = sqlLaravelMigrationConverter;

    </script>
    @endscript
</div>
