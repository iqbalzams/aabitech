<?php

use Livewire\Component;

new class extends Component
{
    // Client-side calculator; no server state is required.
};

?>

<div
    id="aabi-cgpa-converter"
    class="w-full"
>
    {{-- Converter controls --}}
    <section
        aria-labelledby="cgpa-converter-controls-title"
        class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <h2 id="cgpa-converter-controls-title" class="sr-only">
            CGPA percentage converter controls
        </h2>

        <div class="grid gap-3 sm:grid-cols-3">
            <div>
                <label
                    for="cgpa-conversion-direction"
                    class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-200"
                >
                    Conversion
                </label>
                <select
                    id="cgpa-conversion-direction"
                    class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="cgpa-to-percentage">CGPA → Percentage</option>
                    <option value="percentage-to-cgpa">Percentage → CGPA</option>
                </select>
            </div>

            <div>
                <label
                    for="cgpa-scale"
                    class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-200"
                >
                    CGPA scale
                </label>
                <select
                    id="cgpa-scale"
                    class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="4">4.00 scale</option>
                    <option value="5">5.00 scale</option>
                </select>
            </div>

            <div>
                <label
                    for="cgpa-conversion-method"
                    class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-200"
                >
                    Method
                </label>
                <select
                    id="cgpa-conversion-method"
                    class="h-9 w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="hec">HEC segmented formula</option>
                    <option value="linear">Linear estimate</option>
                </select>
            </div>
        </div>
    </section>

    {{-- Main calculator --}}
    <section
        aria-labelledby="cgpa-calculator-title"
        class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <h2 id="cgpa-calculator-title" class="sr-only">CGPA percentage calculator</h2>

        <div class="grid md:grid-cols-2">
            {{-- Input --}}
            <div class="min-w-0 p-4 md:border-r md:border-slate-200 dark:md:border-slate-700">
                <div class="flex items-center justify-between gap-3">
                    <label
                        id="cgpa-input-label"
                        for="cgpa-academic-value"
                        class="block text-sm font-semibold text-slate-900 dark:text-white"
                    >
                        CGPA
                    </label>
                    <span
                        id="cgpa-input-scale"
                        class="shrink-0 text-[11px] font-medium text-slate-500 dark:text-slate-400"
                    >
                        0–4.00
                    </span>
                </div>

                <div class="mt-1.5 flex gap-2">
                    <input
                        id="cgpa-academic-value"
                        name="academic_value"
                        type="number"
                        inputmode="decimal"
                        min="0"
                        max="4"
                        step="0.01"
                        autocomplete="off"
                        placeholder="Enter CGPA"
                        aria-describedby="cgpa-input-help cgpa-input-error"
                        aria-invalid="false"
                        class="h-11 min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-3 text-lg font-semibold text-slate-950 outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500"
                    >
                    <button
                        id="cgpa-reset"
                        type="button"
                        aria-label="Reset converter"
                        class="h-11 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-slate-50 px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    >
                        Reset
                    </button>
                </div>

                <p
                    id="cgpa-input-help"
                    class="mt-1.5 text-xs text-slate-500 dark:text-slate-400"
                >
                    Enter a CGPA from 0 to 4.00.
                </p>
                <p
                    id="cgpa-input-error"
                    class="mt-1 hidden text-xs font-medium text-red-700 dark:text-red-400"
                    role="alert"
                ></p>

                <div class="mt-3">
                    <p class="mb-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                        Examples
                    </p>
                    <div
                        id="cgpa-presets"
                        class="flex flex-wrap gap-1.5"
                        aria-label="Example values"
                    ></div>
                </div>
            </div>

            {{-- Result --}}
            <div class="min-w-0 bg-slate-50 p-4 dark:bg-slate-950">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Result
                        </p>
                        <output
                            id="cgpa-result"
                            for="cgpa-academic-value"
                            aria-live="polite"
                            aria-atomic="true"
                            class="mt-1 block truncate text-3xl font-bold tracking-tight text-slate-950 dark:text-white"
                        >
                            —
                        </output>
                    </div>

                    <button
                        id="cgpa-copy-result"
                        type="button"
                        aria-label="Copy conversion result"
                        disabled
                        class="h-8 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    >
                        Copy
                    </button>
                </div>

                <div
                    id="cgpa-result-details"
                    class="mt-3 hidden grid-cols-2 gap-2"
                >
                    <div class="rounded-lg border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-900">
                        <span class="block text-[11px] font-medium text-slate-500 dark:text-slate-400">
                            Conversion band
                        </span>
                        <strong
                            id="cgpa-band"
                            class="mt-0.5 block text-sm text-slate-900 dark:text-white"
                        >—</strong>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-2.5 dark:border-slate-700 dark:bg-slate-900">
                        <span class="block text-[11px] font-medium text-slate-500 dark:text-slate-400">
                            Classification
                        </span>
                        <strong
                            id="cgpa-classification"
                            class="mt-0.5 block text-sm text-slate-900 dark:text-white"
                        >—</strong>
                    </div>
                </div>

                <div
                    id="cgpa-range-box"
                    class="mt-2 hidden rounded-lg border border-indigo-100 bg-indigo-50 p-2.5 dark:border-indigo-900/60 dark:bg-indigo-950/40"
                >
                    <p class="text-xs font-semibold text-indigo-900 dark:text-indigo-200">
                        Equivalent range
                    </p>
                    <p
                        id="cgpa-range"
                        class="mt-0.5 text-sm leading-5 text-indigo-800 dark:text-indigo-300"
                    ></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Calculation --}}
    <section
        id="cgpa-calculation"
        aria-labelledby="cgpa-calculation-title"
        class="mt-3 hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="p-3.5">
            <h2 id="cgpa-calculation-title" class="text-sm font-semibold text-slate-900 dark:text-white">
                Calculation
            </h2>
            <div class="mt-2 grid gap-2 sm:grid-cols-2">
                <div class="min-w-0 rounded-lg bg-slate-50 p-2.5 dark:bg-slate-800">
                    <span class="block text-[11px] font-medium text-slate-500 dark:text-slate-400">Formula</span>
                    <code
                        id="cgpa-formula"
                        class="mt-1 block break-words text-xs font-medium text-slate-900 dark:text-slate-100"
                    ></code>
                </div>
                <div class="min-w-0 rounded-lg bg-slate-50 p-2.5 dark:bg-slate-800">
                    <span class="block text-[11px] font-medium text-slate-500 dark:text-slate-400">Calculation</span>
                    <code
                        id="cgpa-calculation-text"
                        class="mt-1 block break-words text-xs font-medium text-slate-900 dark:text-slate-100"
                    ></code>
                </div>
            </div>
            <p
                id="cgpa-method-note"
                class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400"
            ></p>
        </div>
    </section>

    {{-- Display options --}}
    <section
        aria-labelledby="cgpa-display-options-title"
        class="mt-3 rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="flex items-center justify-between gap-3">
            <h2 id="cgpa-display-options-title" class="text-sm font-semibold text-slate-900 dark:text-white">
                Display options
            </h2>
            <span class="text-[11px] text-slate-500 dark:text-slate-400">Result formatting</span>
        </div>

        <div class="mt-2 grid gap-3 sm:grid-cols-2">
            <div>
                <label
                    for="cgpa-decimal-places"
                    class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300"
                >
                    Decimal places
                </label>
                <select
                    id="cgpa-decimal-places"
                    class="h-8 w-full cursor-pointer rounded-md border border-slate-300 bg-white px-2 text-xs text-slate-900 outline-none transition focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <div>
                <label
                    for="cgpa-rounding"
                    class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300"
                >
                    Rounding
                </label>
                <select
                    id="cgpa-rounding"
                    class="h-8 w-full cursor-pointer rounded-md border border-slate-300 bg-white px-2 text-xs text-slate-900 outline-none transition focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="round">Round</option>
                    <option value="floor">Round down</option>
                    <option value="ceil">Round up</option>
                </select>
            </div>
        </div>
    </section>

    {{-- Comparison --}}
    <section
        id="cgpa-comparison"
        aria-labelledby="cgpa-comparison-title"
        class="mt-3 hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="p-3.5">
            <h2 id="cgpa-comparison-title" class="text-sm font-semibold text-slate-900 dark:text-white">
                HEC formula vs linear estimate
            </h2>
            <div class="mt-2 grid grid-cols-3 gap-2 text-center">
                <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800">
                    <span class="block text-[11px] text-slate-500 dark:text-slate-400">HEC</span>
                    <strong id="cgpa-hec-comparison" class="mt-0.5 block text-sm text-slate-900 dark:text-white">—</strong>
                </div>
                <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800">
                    <span class="block text-[11px] text-slate-500 dark:text-slate-400">Linear</span>
                    <strong id="cgpa-linear-comparison" class="mt-0.5 block text-sm text-slate-900 dark:text-white">—</strong>
                </div>
                <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800">
                    <span class="block text-[11px] text-slate-500 dark:text-slate-400">Difference</span>
                    <strong id="cgpa-difference" class="mt-0.5 block text-sm text-slate-900 dark:text-white">—</strong>
                </div>
            </div>
        </div>
    </section>

    {{-- Reference --}}
    <section
        aria-labelledby="cgpa-reference-title"
        class="mt-3 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="p-3.5">
            <div class="flex items-center justify-between gap-2">
                <h2 id="cgpa-reference-title" class="text-sm font-semibold text-slate-900 dark:text-white">
                    HEC conversion reference
                </h2>
                <button
                    id="cgpa-toggle-reference"
                    type="button"
                    aria-expanded="false"
                    aria-controls="cgpa-reference-table"
                    class="h-8 cursor-pointer rounded-md border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                >
                    Show table
                </button>
            </div>

            <div id="cgpa-reference-table" class="mt-3 hidden overflow-x-auto">
                <table class="w-full min-w-[480px] border-collapse text-xs">
                    <caption class="sr-only">CGPA and percentage conversion reference</caption>
                    <thead>
                        <tr class="border-b border-slate-200 text-left dark:border-slate-700">
                            <th scope="col" class="px-2 py-2 font-semibold text-slate-700 dark:text-slate-200">CGPA range</th>
                            <th scope="col" class="px-2 py-2 font-semibold text-slate-700 dark:text-slate-200">Percentage</th>
                            <th scope="col" class="px-2 py-2 font-semibold text-slate-700 dark:text-slate-200">Scale</th>
                        </tr>
                    </thead>
                    <tbody id="cgpa-reference-body"></tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Actions --}}
    <div class="mt-3 flex flex-wrap gap-2">
        <button
            id="cgpa-copy-formula"
            type="button"
            aria-label="Copy conversion formula"
            class="h-8 cursor-pointer rounded-md border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
        >
            Copy formula
        </button>
        <button
            id="cgpa-download"
            type="button"
            aria-label="Download conversion result"
            disabled
            class="h-8 cursor-pointer rounded-md border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
        >
            Download
        </button>
        <button
            id="cgpa-share-settings"
            type="button"
            aria-label="Share converter settings"
            class="h-8 cursor-pointer rounded-md border border-slate-300 bg-white px-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
        >
            Share settings
        </button>
    </div>

    <p
        id="cgpa-privacy-note"
        class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400"
    >
        Calculations run locally in your browser. Academic values are not sent to a server by this converter.
    </p>
</div>

@script
<script>
(() => {
    const root = document.getElementById('aabi-cgpa-converter');

    if (!root || root.dataset.initialized === 'true') {
        return;
    }

    root.dataset.initialized = 'true';

    const $ = (id) => root.querySelector(`#${id}`);

    const els = {
        direction: $('cgpa-conversion-direction'),
        scale: $('cgpa-scale'),
        method: $('cgpa-conversion-method'),
        input: $('cgpa-academic-value'),
        inputLabel: $('cgpa-input-label'),
        inputScale: $('cgpa-input-scale'),
        inputHelp: $('cgpa-input-help'),
        inputError: $('cgpa-input-error'),
        presets: $('cgpa-presets'),
        result: $('cgpa-result'),
        resultDetails: $('cgpa-result-details'),
        band: $('cgpa-band'),
        classification: $('cgpa-classification'),
        rangeBox: $('cgpa-range-box'),
        range: $('cgpa-range'),
        calculation: $('cgpa-calculation'),
        formula: $('cgpa-formula'),
        calculationText: $('cgpa-calculation-text'),
        methodNote: $('cgpa-method-note'),
        decimals: $('cgpa-decimal-places'),
        rounding: $('cgpa-rounding'),
        comparison: $('cgpa-comparison'),
        hecComparison: $('cgpa-hec-comparison'),
        linearComparison: $('cgpa-linear-comparison'),
        difference: $('cgpa-difference'),
        referenceTable: $('cgpa-reference-table'),
        referenceBody: $('cgpa-reference-body'),
        referenceToggle: $('cgpa-toggle-reference'),
        reset: $('cgpa-reset'),
        copyResult: $('cgpa-copy-result'),
        copyFormula: $('cgpa-copy-formula'),
        download: $('cgpa-download'),
        share: $('cgpa-share-settings'),
    };

    if (Object.values(els).some((element) => !element)) {
        root.dataset.initialized = 'false';
        return;
    }

    const defaults = Object.freeze({
        direction: 'cgpa-to-percentage',
        scale: 4,
        method: 'hec',
        precision: 2,
        rounding: 'round',
    });

    const state = {
        ...defaults,
        input: '',
        result: null,
        segment: null,
    };

    const tables = {
        4: [
            { min: 3.63, max: 4.00, percentage: '90–100' },
            { min: 3.25, max: 3.62, percentage: '80–89' },
            { min: 2.88, max: 3.24, percentage: '70–79' },
            { min: 2.50, max: 2.87, percentage: '60–69' },
            { min: 1.80, max: 2.49, percentage: '50–59' },
            { min: 1.00, max: 1.79, percentage: '40–49' },
            { min: 0.00, max: 0.99, percentage: 'Below 40' },
        ],
        5: [
            { min: 4.63, max: 5.00, percentage: '90–100' },
            { min: 4.25, max: 4.62, percentage: '80–89' },
            { min: 3.88, max: 4.24, percentage: '70–79' },
            { min: 3.50, max: 3.87, percentage: '60–69' },
            { min: 2.80, max: 3.49, percentage: '50–59' },
            { min: 2.00, max: 2.79, percentage: '40–49' },
            { min: 1.00, max: 1.99, percentage: 'Below 40' },
        ],
    };

    const presets = {
        cgpa: {
            4: ['2.50', '3.00', '3.25', '3.63', '4.00'],
            5: ['2.00', '2.80', '3.50', '4.25', '5.00'],
        },
        percentage: ['40', '50', '60', '70', '80', '90', '100'],
    };

    const segments = {
        4: [
            { min: 3.63, max: 4, pMin: 90, pMax: 100, formula: '(CGPA − 0.30) ÷ 0.037' },
            { min: 3.25, max: 3.629999, pMin: 80, pMax: 89, formula: '(CGPA − 0.29) ÷ 0.037' },
            { min: 2.88, max: 3.249999, pMin: 70, pMax: 79, formula: '(CGPA − 0.36) ÷ 0.036' },
            { min: 2.50, max: 2.879999, pMin: 60, pMax: 69, formula: '(CGPA − 0.28) ÷ 0.037' },
            { min: 1.80, max: 2.499999, pMin: 50, pMax: 59, formula: '(CGPA + 1.65) ÷ 0.069' },
            { min: 1.00, max: 1.799999, pMin: 40, pMax: 49, formula: '(CGPA + 2.16) ÷ 0.079' },
            { min: 0, max: 0.999999, pMin: 0, pMax: 39, formula: 'CGPA ÷ 0.0248' },
        ],
        5: [
            { min: 4.63, max: 5, pMin: 90, pMax: 100, formula: '(CGPA − 1.30) ÷ 0.037' },
            { min: 4.25, max: 4.629999, pMin: 80, pMax: 89, formula: '(CGPA − 1.29) ÷ 0.037' },
            { min: 3.88, max: 4.249999, pMin: 70, pMax: 79, formula: '(CGPA − 1.36) ÷ 0.036' },
            { min: 3.50, max: 3.879999, pMin: 60, pMax: 69, formula: '(CGPA − 1.28) ÷ 0.037' },
            { min: 2.80, max: 3.499999, pMin: 50, pMax: 59, formula: '(CGPA + 0.65) ÷ 0.069' },
            { min: 2.00, max: 2.799999, pMin: 40, pMax: 49, formula: '(CGPA + 1.16) ÷ 0.079' },
            { min: 0, max: 1.999999, pMin: 0, pMax: 39, formula: 'max(0, (CGPA − 1) ÷ 0.0248)' },
        ],
    };

    const percentageSegments = {
        4: [
            { min: 90, max: 100, cgpaMin: 3.63, cgpaMax: 4 },
            { min: 80, max: 89.999999, cgpaMin: 3.25, cgpaMax: 3.63 },
            { min: 70, max: 79.999999, cgpaMin: 2.88, cgpaMax: 3.25 },
            { min: 60, max: 69.999999, cgpaMin: 2.50, cgpaMax: 2.88 },
            { min: 50, max: 59.999999, cgpaMin: 1.80, cgpaMax: 2.50 },
            { min: 40, max: 49.999999, cgpaMin: 1.00, cgpaMax: 1.80 },
            { min: 0, max: 39.999999, cgpaMin: 0, cgpaMax: 1.00 },
        ],
        5: [
            { min: 90, max: 100, cgpaMin: 4.63, cgpaMax: 5 },
            { min: 80, max: 89.999999, cgpaMin: 4.25, cgpaMax: 4.63 },
            { min: 70, max: 79.999999, cgpaMin: 3.88, cgpaMax: 4.25 },
            { min: 60, max: 69.999999, cgpaMin: 3.50, cgpaMax: 3.88 },
            { min: 50, max: 59.999999, cgpaMin: 2.80, cgpaMax: 3.50 },
            { min: 40, max: 49.999999, cgpaMin: 2.00, cgpaMax: 2.80 },
            { min: 0, max: 39.999999, cgpaMin: 0, cgpaMax: 2.00 },
        ],
    };

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function roundValue(value) {
        const factor = 10 ** state.precision;

        if (state.rounding === 'floor') {
            return Math.floor(value * factor) / factor;
        }

        if (state.rounding === 'ceil') {
            return Math.ceil(value * factor) / factor;
        }

        return Math.round(value * factor) / factor;
    }

    function format(value) {
        const rounded = roundValue(Number(value));
        const safeValue = Object.is(rounded, -0) ? 0 : rounded;
        return safeValue.toFixed(state.precision);
    }

    function setSelectValue(select, value, fallback) {
        const stringValue = String(value);
        const valid = Array.from(select.options).some(
            (option) => option.value === stringValue
        );

        select.value = valid ? stringValue : String(fallback);
    }

    function syncControlsFromState() {
        setSelectValue(els.direction, state.direction, defaults.direction);
        setSelectValue(els.scale, state.scale, defaults.scale);
        setSelectValue(els.method, state.method, defaults.method);
        setSelectValue(els.decimals, state.precision, defaults.precision);
        setSelectValue(els.rounding, state.rounding, defaults.rounding);
    }

    function resetResult() {
        state.result = null;
        state.segment = null;

        els.result.textContent = '—';
        els.resultDetails.classList.add('hidden');
        els.resultDetails.classList.remove('grid');
        els.rangeBox.classList.add('hidden');
        els.calculation.classList.add('hidden');
        els.comparison.classList.add('hidden');
        els.copyResult.disabled = true;
        els.download.disabled = true;
        clearError();
    }

    function showError(message) {
        state.result = null;
        state.segment = null;

        els.result.textContent = '—';
        els.resultDetails.classList.add('hidden');
        els.resultDetails.classList.remove('grid');
        els.rangeBox.classList.add('hidden');
        els.calculation.classList.add('hidden');
        els.comparison.classList.add('hidden');
        els.copyResult.disabled = true;
        els.download.disabled = true;
        els.inputError.textContent = message;
        els.inputError.classList.remove('hidden');
        els.input.setAttribute('aria-invalid', 'true');
    }

    function clearError() {
        els.inputError.textContent = '';
        els.inputError.classList.add('hidden');
        els.input.setAttribute('aria-invalid', 'false');
    }

    function validate() {
        const raw = els.input.value.trim();

        if (raw === '') {
            resetResult();
            return null;
        }

        const value = Number(raw);

        if (!Number.isFinite(value)) {
            showError('Enter a valid number.');
            return null;
        }

        if (state.direction === 'cgpa-to-percentage') {
            if (value < 0 || value > state.scale) {
                showError(`CGPA must be between 0 and ${state.scale.toFixed(2)}.`);
                return null;
            }
        } else if (value < 0 || value > 100) {
            showError('Percentage must be between 0 and 100.');
            return null;
        }

        clearError();
        return value;
    }

    function calculateHec(value) {
        if (state.direction === 'cgpa-to-percentage') {
            const list = segments[state.scale];
            state.segment = list.find(
                (segment) => value >= segment.min && value <= segment.max
            ) || null;

            if (!state.segment) {
                return 0;
            }

            let result;

            if (state.scale === 4) {
                if (value >= 3.63) result = (value - 0.30) / 0.037;
                else if (value >= 3.25) result = (value - 0.29) / 0.037;
                else if (value >= 2.88) result = (value - 0.36) / 0.036;
                else if (value >= 2.50) result = (value - 0.28) / 0.037;
                else if (value >= 1.80) result = (value + 1.65) / 0.069;
                else if (value >= 1.00) result = (value + 2.16) / 0.079;
                else result = value / 0.0248;
            } else {
                if (value >= 4.63) result = (value - 1.30) / 0.037;
                else if (value >= 4.25) result = (value - 1.29) / 0.037;
                else if (value >= 3.88) result = (value - 1.36) / 0.036;
                else if (value >= 3.50) result = (value - 1.28) / 0.037;
                else if (value >= 2.80) result = (value + 0.65) / 0.069;
                else if (value >= 2.00) result = (value + 1.16) / 0.079;
                else result = Math.max(0, (value - 1) / 0.0248);
            }

            return clamp(result, 0, 100);
        }

        state.segment = percentageSegments[state.scale].find(
            (segment) => value >= segment.min && value <= segment.max
        ) || null;

        if (value === 0) {
            return 0;
        }

        if (state.scale === 4) {
            if (value >= 90) return 3.63 + (value - 90) * 0.037;
            if (value >= 80) return 3.25 + (value - 80) * 0.037;
            if (value >= 70) return 2.88 + (value - 70) * 0.036;
            if (value >= 60) return 2.50 + (value - 60) * 0.037;
            if (value >= 50) return 1.80 + (value - 50) * 0.069;
            if (value >= 40) return 1.00 + (value - 40) * 0.079;
            return value * 0.0248;
        }

        if (value >= 90) return 4.63 + (value - 90) * 0.037;
        if (value >= 80) return 4.25 + (value - 80) * 0.037;
        if (value >= 70) return 3.88 + (value - 70) * 0.036;
        if (value >= 60) return 3.50 + (value - 60) * 0.037;
        if (value >= 50) return 2.80 + (value - 50) * 0.069;
        if (value >= 40) return 2.00 + (value - 40) * 0.079;
        return 1 + value * 0.0248;
    }

    function calculateLinear(value) {
        if (state.direction === 'cgpa-to-percentage') {
            return clamp((value / state.scale) * 100, 0, 100);
        }

        return clamp((value / 100) * state.scale, 0, state.scale);
    }

    function getFormula(value) {
        if (state.method === 'linear') {
            return state.direction === 'cgpa-to-percentage'
                ? 'CGPA ÷ Scale × 100'
                : 'Percentage ÷ 100 × Scale';
        }

        if (state.direction === 'cgpa-to-percentage') {
            return state.segment?.formula || '';
        }

        if (state.scale === 4) {
            if (value >= 90) return '3.63 + (Percentage − 90) × 0.037';
            if (value >= 80) return '3.25 + (Percentage − 80) × 0.037';
            if (value >= 70) return '2.88 + (Percentage − 70) × 0.036';
            if (value >= 60) return '2.50 + (Percentage − 60) × 0.037';
            if (value >= 50) return '1.80 + (Percentage − 50) × 0.069';
            if (value >= 40) return '1.00 + (Percentage − 40) × 0.079';
            return 'Percentage × 0.0248';
        }

        if (value >= 90) return '4.63 + (Percentage − 90) × 0.037';
        if (value >= 80) return '4.25 + (Percentage − 80) × 0.037';
        if (value >= 70) return '3.88 + (Percentage − 70) × 0.036';
        if (value >= 60) return '3.50 + (Percentage − 60) × 0.037';
        if (value >= 50) return '2.80 + (Percentage − 50) × 0.069';
        if (value >= 40) return '2.00 + (Percentage − 40) × 0.079';
        return '1 + Percentage × 0.0248';
    }

    function getCalculation(value, result) {
        if (state.method === 'linear') {
            return state.direction === 'cgpa-to-percentage'
                ? `${value.toFixed(2)} ÷ ${state.scale} × 100 = ${format(result)}%`
                : `${value.toFixed(2)} ÷ 100 × ${state.scale} = ${format(result)}`;
        }

        if (state.direction === 'cgpa-to-percentage' && state.segment) {
            const formula = state.segment.formula;
            let calculation;

            switch (state.segment.formula) {
                case '(CGPA − 0.30) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 0.30) ÷ 0.037`;
                    break;
                case '(CGPA − 0.29) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 0.29) ÷ 0.037`;
                    break;
                case '(CGPA − 0.36) ÷ 0.036':
                    calculation = `(${value.toFixed(2)} − 0.36) ÷ 0.036`;
                    break;
                case '(CGPA − 0.28) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 0.28) ÷ 0.037`;
                    break;
                case '(CGPA + 1.65) ÷ 0.069':
                    calculation = `(${value.toFixed(2)} + 1.65) ÷ 0.069`;
                    break;
                case '(CGPA + 2.16) ÷ 0.079':
                    calculation = `(${value.toFixed(2)} + 2.16) ÷ 0.079`;
                    break;
                case 'CGPA ÷ 0.0248':
                    calculation = `${value.toFixed(2)} ÷ 0.0248`;
                    break;
                case '(CGPA − 1.30) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 1.30) ÷ 0.037`;
                    break;
                case '(CGPA − 1.29) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 1.29) ÷ 0.037`;
                    break;
                case '(CGPA − 1.36) ÷ 0.036':
                    calculation = `(${value.toFixed(2)} − 1.36) ÷ 0.036`;
                    break;
                case '(CGPA − 1.28) ÷ 0.037':
                    calculation = `(${value.toFixed(2)} − 1.28) ÷ 0.037`;
                    break;
                case '(CGPA + 0.65) ÷ 0.069':
                    calculation = `(${value.toFixed(2)} + 0.65) ÷ 0.069`;
                    break;
                case '(CGPA + 1.16) ÷ 0.079':
                    calculation = `(${value.toFixed(2)} + 1.16) ÷ 0.079`;
                    break;
                case 'max(0, (CGPA − 1) ÷ 0.0248)':
                    calculation = `max(0, (${value.toFixed(2)} − 1) ÷ 0.0248)`;
                    break;
                default:
                    calculation = formula;
            }

            return `${calculation} = ${format(result)}%`;
        }

        return `${getFormula(value)} = ${format(result)}`;
    }

    function getClassification(value, result) {
        const percentage = state.direction === 'cgpa-to-percentage' ? result : value;

        if (percentage >= 90) return 'A / Distinction';
        if (percentage >= 80) return 'B / 1st Division';
        if (percentage >= 70) return 'B / 1st Division';
        if (percentage >= 60) return 'C / 1st Division';
        if (percentage >= 50) return 'C / 2nd Division';
        if (percentage >= 40) return 'D / 3rd Division';
        return 'F / Below passing band';
    }

    function getBand(value) {
        if (state.direction === 'cgpa-to-percentage') {
            return state.segment
                ? `${state.segment.pMin}%–${state.segment.pMax}%`
                : '—';
        }

        const segment = percentageSegments[state.scale].find(
            (item) => value >= item.min && value <= item.max
        );

        if (!segment) return '—';

        return `${segment.cgpaMin.toFixed(2)}–${Math.min(segment.cgpaMax, state.scale).toFixed(2)} CGPA`;
    }

    function getRange(value) {
        if (state.direction === 'cgpa-to-percentage') {
            if (!state.segment) return '';
            return `CGPA ${state.segment.min.toFixed(2)}–${state.segment.max.toFixed(2)} corresponds to approximately ${state.segment.pMin}%–${state.segment.pMax}%.`;
        }

        const segment = percentageSegments[state.scale].find(
            (item) => value >= item.min && value <= item.max
        );

        if (!segment) return '';

        const max = Math.min(segment.cgpaMax, state.scale);
        return `${segment.min}%–${segment.max}% corresponds to approximately CGPA ${segment.cgpaMin.toFixed(2)}–${max.toFixed(2)}.`;
    }

    function updateComparison(value) {
        if (state.method !== 'hec') {
            els.comparison.classList.add('hidden');
            return;
        }

        const hec = calculateHec(value);
        const linear = calculateLinear(value);

        els.hecComparison.textContent = state.direction === 'cgpa-to-percentage'
            ? `${format(hec)}%`
            : format(hec);

        els.linearComparison.textContent = state.direction === 'cgpa-to-percentage'
            ? `${format(linear)}%`
            : format(linear);

        els.difference.textContent = state.direction === 'cgpa-to-percentage'
            ? `${format(Math.abs(hec - linear))} percentage points`
            : format(Math.abs(hec - linear));

        els.comparison.classList.remove('hidden');
    }

    function calculate() {
        const value = validate();

        if (value === null) return;

        const rawResult = state.method === 'hec'
            ? calculateHec(value)
            : calculateLinear(value);

        state.result = clamp(
            rawResult,
            0,
            state.direction === 'cgpa-to-percentage' ? 100 : state.scale
        );

        const formatted = format(state.result);

        els.result.textContent = state.direction === 'cgpa-to-percentage'
            ? `${formatted}%`
            : formatted;

        els.band.textContent = getBand(value);
        els.classification.textContent = getClassification(value, state.result);
        els.range.textContent = getRange(value);
        els.resultDetails.classList.remove('hidden');
        els.resultDetails.classList.add('grid');
        els.rangeBox.classList.remove('hidden');
        els.calculation.classList.remove('hidden');
        els.formula.textContent = getFormula(value);
        els.calculationText.textContent = getCalculation(value, state.result);
        els.methodNote.textContent = state.method === 'hec'
            ? 'Uses the selected segmented conversion formula. Check your university or application instructions for any institution-specific conversion rule.'
            : 'Linear conversion is a proportional estimate and is not the segmented HEC formula.';
        els.copyResult.disabled = false;
        els.download.disabled = false;

        updateComparison(value);
    }

    function updateInputUI() {
        const cgpaMode = state.direction === 'cgpa-to-percentage';

        els.inputLabel.textContent = cgpaMode ? 'CGPA' : 'Percentage';
        els.inputScale.textContent = cgpaMode ? `0–${state.scale.toFixed(2)}` : '0–100';
        els.input.placeholder = cgpaMode ? 'Enter CGPA' : 'Enter percentage';
        els.inputHelp.textContent = cgpaMode
            ? `Enter a CGPA from 0 to ${state.scale.toFixed(2)}.`
            : 'Enter a percentage from 0 to 100.';
        els.input.min = '0';
        els.input.max = cgpaMode ? String(state.scale) : '100';
        els.input.step = cgpaMode ? '0.01' : '0.01';
        els.input.setAttribute(
            'aria-label',
            cgpaMode ? `CGPA from 0 to ${state.scale.toFixed(2)}` : 'Percentage from 0 to 100'
        );

        renderPresets();
    }

    function renderPresets() {
        const values = state.direction === 'cgpa-to-percentage'
            ? presets.cgpa[state.scale]
            : presets.percentage;

        els.presets.replaceChildren();

        values.forEach((value) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'h-7 cursor-pointer rounded-md border border-slate-300 bg-white px-2 text-xs font-medium text-slate-700 transition hover:border-indigo-400 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-600/30 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-indigo-500 dark:hover:bg-slate-700';
            button.textContent = value;
            button.setAttribute('aria-label', `Use example value ${value}`);

            button.addEventListener('click', () => {
                els.input.value = value;
                calculate();
                els.input.focus();
            });

            els.presets.appendChild(button);
        });
    }

    function renderReferenceTable() {
        els.referenceBody.replaceChildren();

        [4, 5].forEach((scale) => {
            tables[scale].forEach((row) => {
                const tr = document.createElement('tr');
                tr.className = 'border-b border-slate-100 dark:border-slate-800';

                const cgpa = document.createElement('td');
                cgpa.className = 'px-2 py-2 font-medium text-slate-900 dark:text-slate-100';
                cgpa.textContent = `${row.min.toFixed(2)}–${row.max.toFixed(2)}`;

                const percentage = document.createElement('td');
                percentage.className = 'px-2 py-2 text-slate-700 dark:text-slate-300';
                percentage.textContent = row.percentage;

                const scaleCell = document.createElement('td');
                scaleCell.className = 'px-2 py-2 text-slate-500 dark:text-slate-400';
                scaleCell.textContent = `${scale}.00`;

                tr.append(cgpa, percentage, scaleCell);
                els.referenceBody.appendChild(tr);
            });
        });
    }

    function reset() {
        Object.assign(state, defaults);
        state.input = '';
        state.result = null;
        state.segment = null;

        syncControlsFromState();
        els.input.value = '';
        resetResult();
        updateInputUI();
        els.input.focus();
    }

    async function copyText(text) {
        if (!navigator.clipboard || typeof navigator.clipboard.writeText !== 'function') {
            return false;
        }

        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch {
            return false;
        }
    }

    async function copyResult() {
        if (state.result === null) return;

        const text = state.direction === 'cgpa-to-percentage'
            ? `${format(state.result)}%`
            : format(state.result);

        const copied = await copyText(text);

        if (copied) {
            const original = els.copyResult.textContent;
            els.copyResult.textContent = 'Copied';
            window.setTimeout(() => {
                els.copyResult.textContent = original;
            }, 1500);
        }
    }

    async function copyFormula() {
        const raw = els.input.value.trim();

        if (raw === '' || !Number.isFinite(Number(raw))) {
            await copyText('HEC segmented CGPA conversion formula');
            return;
        }

        const value = Number(raw);
        const formula = getFormula(value);
        const calculation = state.result !== null
            ? getCalculation(value, state.result)
            : '';

        await copyText(calculation ? `${formula}\n${calculation}` : formula);
    }

    function downloadResult() {
        if (state.result === null) return;

        const value = Number(els.input.value);
        const text = [
            'AabiTech CGPA Percentage Converter',
            '',
            `Conversion: ${state.direction === 'cgpa-to-percentage' ? 'CGPA to Percentage' : 'Percentage to CGPA'}`,
            `Scale: ${state.scale.toFixed(2)}`,
            `Method: ${state.method === 'hec' ? 'HEC segmented formula' : 'Linear estimate'}`,
            `Decimal places: ${state.precision}`,
            `Rounding: ${state.rounding}`,
            `Input: ${value}`,
            `Result: ${els.result.textContent}`,
            `Formula: ${getFormula(value)}`,
            `Calculation: ${getCalculation(value, state.result)}`,
            '',
            'Calculation performed locally in the browser.',
        ].join('\n');

        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const anchor = document.createElement('a');

        anchor.href = url;
        anchor.download = 'aabitech-cgpa-conversion.txt';
        document.body.appendChild(anchor);
        anchor.click();
        anchor.remove();
        window.setTimeout(() => URL.revokeObjectURL(url), 0);
    }

    async function shareSettings() {
        const params = new URLSearchParams({
            direction: state.direction,
            scale: String(state.scale),
            method: state.method,
            precision: String(state.precision),
            rounding: state.rounding,
        });

        const url = `${window.location.origin}${window.location.pathname}?${params.toString()}`;

        try {
            if (typeof navigator.share === 'function') {
                await navigator.share({
                    title: 'AabiTech CGPA Percentage Converter',
                    url,
                });
                return;
            }

            const copied = await copyText(url);

            if (copied) {
                const original = els.share.textContent;
                els.share.textContent = 'Copied';
                window.setTimeout(() => {
                    els.share.textContent = original;
                }, 1500);
            }
        } catch {
            // User cancellation is intentionally ignored.
        }
    }

    function loadSettings() {
        // Start from known defaults. Never derive precision from the DOM.
        Object.assign(state, defaults);

        const params = new URLSearchParams(window.location.search);
        const direction = params.get('direction');
        const scaleParam = params.get('scale');
        const method = params.get('method');
        const precisionParam = params.get('precision');
        const rounding = params.get('rounding');

        if (direction === 'cgpa-to-percentage' || direction === 'percentage-to-cgpa') {
            state.direction = direction;
        }

        const scale = Number(scaleParam);
        if (scaleParam !== null && (scale === 4 || scale === 5)) {
            state.scale = scale;
        }

        if (method === 'hec' || method === 'linear') {
            state.method = method;
        }

        // CRITICAL FIX:
        // Number(null) === 0. The old implementation therefore turned the
        // missing precision query parameter into precision 0. Only parse it
        // when the parameter actually exists and contains a valid integer.
        if (precisionParam !== null && /^\d+$/.test(precisionParam)) {
            const precision = Number(precisionParam);
            if (precision >= 0 && precision <= 4) {
                state.precision = precision;
            }
        }

        if (rounding === 'round' || rounding === 'floor' || rounding === 'ceil') {
            state.rounding = rounding;
        }

        syncControlsFromState();
    }

    function bindEvents() {
        els.direction.addEventListener('change', () => {
            state.direction = els.direction.value;
            els.input.value = '';
            resetResult();
            updateInputUI();
        });

        els.scale.addEventListener('change', () => {
            const selectedScale = Number(els.scale.value);
            state.scale = selectedScale === 4 || selectedScale === 5
                ? selectedScale
                : defaults.scale;

            setSelectValue(els.scale, state.scale, defaults.scale);
            els.input.value = '';
            resetResult();
            updateInputUI();
        });

        els.method.addEventListener('change', () => {
            state.method = els.method.value === 'linear' ? 'linear' : 'hec';
            setSelectValue(els.method, state.method, defaults.method);

            if (els.input.value.trim() !== '') {
                calculate();
            }
        });

        els.input.addEventListener('input', calculate);

        els.decimals.addEventListener('change', () => {
            const precision = Number(els.decimals.value);
            state.precision = Number.isInteger(precision) && precision >= 0 && precision <= 4
                ? precision
                : defaults.precision;

            setSelectValue(els.decimals, state.precision, defaults.precision);

            if (els.input.value.trim() !== '') {
                calculate();
            }
        });

        els.rounding.addEventListener('change', () => {
            state.rounding = ['round', 'floor', 'ceil'].includes(els.rounding.value)
                ? els.rounding.value
                : defaults.rounding;

            setSelectValue(els.rounding, state.rounding, defaults.rounding);

            if (els.input.value.trim() !== '') {
                calculate();
            }
        });

        els.reset.addEventListener('click', reset);
        els.copyResult.addEventListener('click', copyResult);
        els.copyFormula.addEventListener('click', copyFormula);
        els.download.addEventListener('click', downloadResult);
        els.share.addEventListener('click', shareSettings);

        els.referenceToggle.addEventListener('click', () => {
            const willShow = els.referenceTable.classList.contains('hidden');
            els.referenceTable.classList.toggle('hidden', !willShow);
            els.referenceToggle.setAttribute('aria-expanded', String(willShow));
            els.referenceToggle.textContent = willShow ? 'Hide table' : 'Show table';
        });
    }

    // Initialization: state → URL overrides → DOM → events → rendering.
    loadSettings();
    bindEvents();
    renderReferenceTable();
    updateInputUI();
    resetResult();
})();
</script>
@endscript