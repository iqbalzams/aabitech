<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div
    x-data="aabiRandomNumberGenerator()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    {{-- Main workspace --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        {{-- Mode tabs --}}
        <div class="border-b border-slate-200 bg-slate-50 px-3 py-2">
            <div
                class="flex gap-1 overflow-x-auto"
                role="tablist"
                aria-label="Randomizer mode"
            >
                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'integer'"
                    :class="{ 'is-active': mode === 'integer' }"
                    @click="setMode('integer')"
                    class="compact-tab"
                >
                    Integers
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'decimal'"
                    :class="{ 'is-active': mode === 'decimal' }"
                    @click="setMode('decimal')"
                    class="compact-tab"
                >
                    Decimals
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'percentage'"
                    :class="{ 'is-active': mode === 'percentage' }"
                    @click="setMode('percentage')"
                    class="compact-tab"
                >
                    Percentage
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'date'"
                    :class="{ 'is-active': mode === 'date' }"
                    @click="setMode('date')"
                    class="compact-tab"
                >
                    Dates
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'time'"
                    :class="{ 'is-active': mode === 'time' }"
                    @click="setMode('time')"
                    class="compact-tab"
                >
                    Times
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'boolean'"
                    :class="{ 'is-active': mode === 'boolean' }"
                    @click="setMode('boolean')"
                    class="compact-tab"
                >
                    Boolean
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="random-mode"
                    :aria-selected="mode === 'list'"
                    :class="{ 'is-active': mode === 'list' }"
                    @click="setMode('list')"
                    class="compact-tab"
                >
                    Random List
                </button>
            </div>
        </div>

        <div class="grid gap-4 p-4 lg:grid-cols-[1.05fr_0.95fr] lg:p-5">
            {{-- Controls --}}
            <div class="min-w-0 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Generator settings
                        </h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Configure the random values you want to generate.
                        </p>
                    </div>

                    <span
                        x-show="secureAvailable"
                        class="shrink-0 rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700"
                    >
                        Secure RNG
                    </span>
                </div>

                {{-- Integer / decimal / percentage settings --}}
                <template x-if="['integer', 'decimal', 'percentage'].includes(mode)">
                    <div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="field-label">
                                    Minimum
                                </label>

                                <input
                                    type="number"
                                    x-model="min"
                                    @input="validate()"
                                    :step="mode === 'integer' ? '1' : 'any'"
                                    class="calc-input"
                                    aria-label="Minimum value"
                                >
                            </div>

                            <div>
                                <label class="field-label">
                                    Maximum
                                </label>

                                <input
                                    type="number"
                                    x-model="max"
                                    @input="validate()"
                                    :step="mode === 'integer' ? '1' : 'any'"
                                    class="calc-input"
                                    aria-label="Maximum value"
                                >
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div>
                                <label class="field-label">
                                    Number of values
                                </label>

                                <input
                                    type="number"
                                    min="1"
                                    max="10000"
                                    x-model.number="count"
                                    @input="validate()"
                                    class="calc-input"
                                >
                            </div>

                            <template x-if="mode === 'decimal' || mode === 'percentage'">
                                <div>
                                    <label class="field-label">
                                        Decimal places
                                    </label>

                                    <input
                                        type="number"
                                        min="0"
                                        max="8"
                                        x-model.number="precision"
                                        @input="validate()"
                                        class="calc-input"
                                    >
                                </div>
                            </template>

                            <template x-if="mode === 'integer'">
                                <div>
                                    <label class="field-label">
                                        Step / increment
                                    </label>

                                    <input
                                        type="number"
                                        min="1"
                                        step="1"
                                        x-model.number="step"
                                        @input="validate()"
                                        class="calc-input"
                                    >
                                </div>
                            </template>
                        </div>

                        {{-- Integer filters --}}
                        <template x-if="mode === 'integer'">
                            <div class="mt-3 space-y-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <button
                                        type="button"
                                        data-active-group="unique-values"
                                        :class="{ 'is-active': unique }"
                                        @click="unique = !unique; validate(); saveSettings()"
                                        class="toggle-tab"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        <span x-text="unique ? 'Unique values' : 'Allow duplicates'"></span>
                                    </button>

                                    <button
                                        type="button"
                                        data-active-group="secure-random"
                                        :class="{ 'is-active': secureRandom }"
                                        @click="secureRandom = !secureRandom"
                                        class="toggle-tab"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        <span x-text="secureRandom ? 'Crypto RNG' : 'Standard RNG'"></span>
                                    </button>
                                </div>

                                <div>
                                    <div class="field-label mb-1.5">
                                        Number filters
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                        <button
                                            type="button"
                                            data-active-group="parity"
                                            :class="{ 'is-active': parity === 'any' }"
                                            @click="parity = 'any'; validate()"
                                            class="compact-tab"
                                        >
                                            Any
                                        </button>

                                        <button
                                            type="button"
                                            data-active-group="parity"
                                            :class="{ 'is-active': parity === 'even' }"
                                            @click="parity = 'even'; validate()"
                                            class="compact-tab"
                                        >
                                            Even
                                        </button>

                                        <button
                                            type="button"
                                            data-active-group="parity"
                                            :class="{ 'is-active': parity === 'odd' }"
                                            @click="parity = 'odd'; validate()"
                                            class="compact-tab"
                                        >
                                            Odd
                                        </button>

                                        <button
                                            type="button"
                                            data-active-group="prime"
                                            :class="{ 'is-active': primeOnly }"
                                            @click="primeOnly = !primeOnly; validate()"
                                            class="compact-tab"
                                        >
                                            Prime only
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="field-label">
                                            Exclude numbers
                                        </label>

                                        <input
                                            type="text"
                                            x-model="excludeInput"
                                            @input="validate()"
                                            class="calc-input"
                                            placeholder="3, 7, 11"
                                        >
                                    </div>

                                    <div>
                                        <label class="field-label">
                                            Divisible by
                                        </label>

                                        <input
                                            type="number"
                                            min="1"
                                            step="1"
                                            x-model.number="divisor"
                                            @input="validate()"
                                            class="calc-input"
                                            placeholder="Optional"
                                        >
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Decimal options --}}
                        <template x-if="mode === 'decimal'">
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="field-label">
                                        Step / increment
                                    </label>

                                    <input
                                        type="number"
                                        min="0.00000001"
                                        step="any"
                                        x-model.number="decimalStep"
                                        @input="validate()"
                                        class="calc-input"
                                        placeholder="0.01"
                                    >
                                </div>

                                <div>
                                    <label class="field-label">
                                        Unique values
                                    </label>

                                    <button
                                        type="button"
                                        data-active-group="decimal-unique"
                                        :class="{ 'is-active': unique }"
                                        @click="unique = !unique; validate()"
                                        class="toggle-tab w-full"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        <span x-text="unique ? 'Enabled' : 'Disabled'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Date --}}
                <template x-if="mode === 'date'">
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="field-label">
                                    Start date
                                </label>

                                <input
                                    type="date"
                                    x-model="startDate"
                                    @input="validate()"
                                    class="calc-input"
                                >
                            </div>

                            <div>
                                <label class="field-label">
                                    End date
                                </label>

                                <input
                                    type="date"
                                    x-model="endDate"
                                    @input="validate()"
                                    class="calc-input"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="field-label">
                                Number of dates
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="10000"
                                x-model.number="count"
                                @input="validate()"
                                class="calc-input"
                            >
                        </div>

                        <button
                            type="button"
                            data-active-group="date-unique"
                            :class="{ 'is-active': unique }"
                            @click="unique = !unique; validate()"
                            class="toggle-tab"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            <span x-text="unique ? 'Unique dates' : 'Allow duplicate dates'"></span>
                        </button>
                    </div>
                </template>

                {{-- Time --}}
                <template x-if="mode === 'time'">
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="field-label">
                                    Start time
                                </label>

                                <input
                                    type="time"
                                    x-model="startTime"
                                    @input="validate()"
                                    class="calc-input"
                                >
                            </div>

                            <div>
                                <label class="field-label">
                                    End time
                                </label>

                                <input
                                    type="time"
                                    x-model="endTime"
                                    @input="validate()"
                                    class="calc-input"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="field-label">
                                Number of times
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="10000"
                                x-model.number="count"
                                @input="validate()"
                                class="calc-input"
                            >
                        </div>

                        <button
                            type="button"
                            data-active-group="time-unique"
                            :class="{ 'is-active': unique }"
                            @click="unique = !unique; validate()"
                            class="toggle-tab"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            <span x-text="unique ? 'Unique times' : 'Allow duplicates'"></span>
                        </button>
                    </div>
                </template>

                {{-- Boolean --}}
                <template x-if="mode === 'boolean'">
                    <div class="space-y-3">
                        <div>
                            <label class="field-label">
                                Number of values
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="10000"
                                x-model.number="count"
                                @input="validate()"
                                class="calc-input"
                            >
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-3 text-xs text-slate-500">
                            Generates cryptographically secure
                            <span class="font-semibold text-slate-700">true</span>
                            or
                            <span class="font-semibold text-slate-700">false</span>
                            values.
                        </div>
                    </div>
                </template>

                {{-- Random list --}}
                <template x-if="mode === 'list'">
                    <div class="space-y-3">
                        <div>
                            <label class="field-label">
                                List values
                            </label>

                            <textarea
                                x-model="listInput"
                                @input="validate()"
                                rows="7"
                                class="calc-textarea"
                                placeholder="Apple&#10;Banana&#10;Orange&#10;Mango"
                            ></textarea>
                        </div>

                        <div>
                            <label class="field-label">
                                Number of selections
                            </label>

                            <input
                                type="number"
                                min="1"
                                max="10000"
                                x-model.number="count"
                                @input="validate()"
                                class="calc-input"
                            >
                        </div>

                        <button
                            type="button"
                            data-active-group="list-unique"
                            :class="{ 'is-active': unique }"
                            @click="unique = !unique; validate()"
                            class="toggle-tab"
                        >
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            <span x-text="unique ? 'Without replacement' : 'With replacement'"></span>
                        </button>
                    </div>
                </template>

                {{-- Seed --}}
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <button
                        type="button"
                        data-active-group="seeded-mode"
                        :class="{ 'is-active': seeded }"
                        @click="seeded = !seeded; if (!seeded) seed = '';"
                        class="toggle-tab w-full"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        <span x-text="seeded ? 'Seeded / reproducible generation' : 'Secure random generation'"></span>
                    </button>

                    <template x-if="seeded">
                        <div class="mt-2">
                            <input
                                type="text"
                                x-model="seed"
                                @input="validate()"
                                class="calc-input"
                                placeholder="Enter a custom seed"
                            >
                        </div>
                    </template>
                </div>

                {{-- Sorting --}}
                <template x-if="['integer', 'decimal', 'percentage', 'date', 'time', 'boolean', 'list'].includes(mode)">
                    <div class="mt-4">
                        <div class="field-label mb-1.5">
                            Output order
                        </div>

                        <div class="flex flex-wrap gap-1">
                            <button
                                type="button"
                                data-active-group="random-sort"
                                :class="{ 'is-active': sort === 'none' }"
                                @click="sort = 'none'"
                                class="compact-tab"
                            >
                                Original
                            </button>

                            <button
                                type="button"
                                data-active-group="random-sort"
                                :class="{ 'is-active': sort === 'ascending' }"
                                @click="sort = 'ascending'"
                                class="compact-tab"
                            >
                                Ascending
                            </button>

                            <button
                                type="button"
                                data-active-group="random-sort"
                                :class="{ 'is-active': sort === 'descending' }"
                                @click="sort = 'descending'"
                                class="compact-tab"
                            >
                                Descending
                            </button>

                            <button
                                type="button"
                                data-active-group="random-sort"
                                :class="{ 'is-active': sort === 'shuffle' }"
                                @click="sort = 'shuffle'"
                                class="compact-tab"
                            >
                                Shuffle
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Quick presets --}}
                <div class="mt-4">
                    <div class="field-label mb-1.5">
                        Quick presets
                    </div>

                    <div class="flex flex-wrap gap-1">
                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'dice' }"
                            @click="applyPreset('dice')"
                            class="compact-tab"
                        >
                            Dice
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'coin' }"
                            @click="applyPreset('coin')"
                            class="compact-tab"
                        >
                            Coin
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'lottery' }"
                            @click="applyPreset('lottery')"
                            class="compact-tab"
                        >
                            Lottery
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'percent' }"
                            @click="applyPreset('percent')"
                            class="compact-tab"
                        >
                            Percent
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'negative-positive' }"
                            @click="applyPreset('negative-positive')"
                            class="compact-tab"
                        >
                            ± Range
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'testing' }"
                            @click="applyPreset('testing')"
                            class="compact-tab"
                        >
                            Testing
                        </button>

                        <button
                            type="button"
                            data-active-group="random-preset"
                            :class="{ 'is-active': activePreset === 'simulation' }"
                            @click="applyPreset('simulation')"
                            class="compact-tab"
                        >
                            Simulation
                        </button>
                    </div>
                </div>

                {{-- Validation --}}
                <div
                    x-show="error"
                    x-transition
                    class="mt-4 rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-[11px] font-medium leading-5 text-red-700"
                    x-text="error"
                ></div>

                {{-- Actions --}}
                <div class="mt-4 flex flex-wrap items-center gap-1.5 border-t border-slate-200 pt-3">
                    <button
                        type="button"
                        data-active-group="random-action"
                        :class="{ 'is-active': lastAction === 'generate' }"
                        @click="generate()"
                        class="compact-action"
                    >
                        Generate
                    </button>

                    <button
                        type="button"
                        @click="generate()"
                        :disabled="!canGenerate"
                        class="compact-action"
                    >
                        Regenerate
                    </button>

                    <button
                        type="button"
                        @click="loadExample()"
                        class="compact-action"
                    >
                        Example
                    </button>

                    <button
                        type="button"
                        @click="reset()"
                        class="compact-action"
                    >
                        Reset
                    </button>
                </div>
            </div>

            {{-- Results --}}
            <div class="flex min-h-[400px] min-w-0 flex-col rounded-xl border border-slate-200 bg-white p-4">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Results
                        </h2>

                        <p class="text-[11px] text-slate-500">
                            <span x-text="results.length"></span>
                            values generated
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        <button
                            type="button"
                            @click="copyResults('comma')"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            Copy comma
                        </button>

                        <button
                            type="button"
                            @click="copyResults('newline')"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            Copy lines
                        </button>

                        <button
                            type="button"
                            @click="copyResults('json')"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            Copy JSON
                        </button>

                        <button
                            type="button"
                            @click="downloadResults('txt')"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            TXT
                        </button>

                        <button
                            type="button"
                            @click="downloadResults('csv')"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            CSV
                        </button>

                        <button
                            type="button"
                            @click="clearResults()"
                            :disabled="!results.length"
                            class="result-action"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                {{-- Copy confirmation --}}
                <div
                    x-show="copyMessage"
                    x-transition
                    class="mb-2 rounded-md bg-emerald-50 px-2.5 py-1.5 text-[11px] font-medium text-emerald-700"
                    x-text="copyMessage"
                ></div>

                {{-- Actual result rendering --}}
                <div class="min-h-0 flex-1 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                    <template x-if="results.length">
                        <div class="h-[270px] overflow-auto p-3">
                            <div class="space-y-1">
                                <template x-for="(result, index) in results" :key="index">
                                    <div
                                        class="flex items-center gap-3 rounded-md border border-slate-200 bg-white px-3 py-2"
                                    >
                                        <span
                                            class="w-7 shrink-0 text-[10px] font-medium text-slate-500"
                                            x-text="index + 1"
                                        ></span>

                                        <span
                                            class="min-w-0 break-all font-mono text-sm font-medium text-slate-800"
                                            x-text="formatResult(result)"
                                        ></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="!results.length">
                        <div class="flex h-[270px] items-center justify-center px-5 text-center">
                            <div>
                                <div class="text-sm font-medium text-slate-500">
                                    No results generated yet
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Configure the generator and click Generate.
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Statistics --}}
                <template x-if="numericResults.length">
                    <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <div class="stat-card">
                            <span class="stat-label">Lowest</span>
                            <span
                                class="stat-value"
                                x-text="formatNumber(Math.min(...numericResults))"
                            ></span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-label">Highest</span>
                            <span
                                class="stat-value"
                                x-text="formatNumber(Math.max(...numericResults))"
                            ></span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-label">Average</span>
                            <span
                                class="stat-value"
                                x-text="formatNumber(average)"
                            ></span>
                        </div>

                        <div class="stat-card">
                            <span class="stat-label">Unique</span>
                            <span
                                class="stat-value"
                                x-text="uniqueResultCount"
                            ></span>
                        </div>
                    </div>
                </template>

                {{-- Distribution --}}
                <template x-if="numericResults.length >= 10">
                    <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-slate-700">
                                Distribution
                            </span>

                            <span class="text-[10px] text-slate-500">
                                <span x-text="distributionBuckets.length"></span>
                                buckets
                            </span>
                        </div>

                        <div class="flex h-20 items-end gap-1">
                            <template x-for="(bucket, index) in distributionBuckets" :key="index">
                                <div
                                    class="min-w-0 flex-1 rounded-t-sm bg-indigo-400 transition-all"
                                    :style="'height:' + distributionHeight(bucket.count) + '%'"
                                    :title="bucket.label + ': ' + bucket.count"
                                ></div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- History --}}
        <template x-if="history.length">
            <div class="border-t border-slate-200 px-4 py-3 sm:px-5">
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-slate-700">
                        Recent generations
                    </h3>

                    <button
                        type="button"
                        @click="clearHistory()"
                        class="text-[11px] font-medium text-slate-500 transition hover:text-red-500"
                    >
                        Clear history
                    </button>
                </div>

                <div class="flex flex-wrap gap-1.5">
                    <template x-for="item in history" :key="item.id">
                        <button
                            type="button"
                            @click="restoreHistory(item)"
                            class="max-w-full truncate rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600"
                            :title="item.summary"
                        >
                            <span x-text="item.summary"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </section>

    {{-- Privacy --}}
    <div class="flex items-start gap-2 rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2.5">
        <svg
            class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                fill-rule="evenodd"
                d="M10 1.75a.75.75 0 01.67.415l1.64 3.324 3.67.533a.75.75 0 01.416 1.28l-2.655 2.588.627 3.655a.75.75 0 01-1.088.79L10 12.61l-3.28 1.725a.75.75 0 01-1.088-.79l.627-3.655L3.604 7.302a.75.75 0 01.416-1.28l3.67-.533 1.64-3.324A.75.75 0 0110 1.75z"
                clip-rule="evenodd"
            />
        </svg>

        <p class="text-[11px] leading-5 text-emerald-800">
            Generation runs entirely in your browser. Secure mode uses
            <code class="font-mono">crypto.getRandomValues()</code>.
            Generated values are not sent to AabiTech.
        </p>
    </div>
</div>

<style>
    .field-label {
        display: block;
        font-size: 11px;
        line-height: 1;
        font-weight: 600;
        color: rgb(71 85 105);
    }

    .calc-input {
        width: 100%;
        height: 42px;
        border-radius: 9px;
        border: 1px solid rgb(203 213 225);
        background: white;
        padding: 0 12px;
        font-size: 14px;
        font-weight: 500;
        color: rgb(15 23 42);
        outline: none;
        transition:
            border-color 150ms ease,
            box-shadow 150ms ease,
            background-color 150ms ease;
    }

    .calc-input:hover {
        border-color: rgb(148 163 184);
    }

    .calc-input:focus {
        border-color: rgb(99 102 241);
        box-shadow: 0 0 0 3px rgb(224 231 255);
    }

    .calc-textarea {
        width: 100%;
        resize: vertical;
        border-radius: 9px;
        border: 1px solid rgb(203 213 225);
        background: white;
        padding: 10px 12px;
        font-size: 13px;
        line-height: 1.6;
        color: rgb(15 23 42);
        outline: none;
        transition:
            border-color 150ms ease,
            box-shadow 150ms ease;
    }

    .calc-textarea:focus {
        border-color: rgb(99 102 241);
        box-shadow: 0 0 0 3px rgb(224 231 255);
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
        border-color: rgb(203 213 225);
        background: white;
        color: rgb(67 56 202);
    }

    .toggle-tab {
        display: inline-flex;
        height: 42px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 9px;
        border: 1px solid rgb(203 213 225);
        background: white;
        padding: 0 10px;
        font-size: 11px;
        font-weight: 600;
        color: rgb(71 85 105);
        transition:
            background-color 150ms ease,
            border-color 150ms ease,
            color 150ms ease;
        cursor: pointer;
    }

    .toggle-tab:hover {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    .compact-action,
    .result-action {
        display: inline-flex;
        height: 32px;
        width: auto;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid rgb(203 213 225);
        background: white;
        padding: 0 11px;
        font-size: 11px;
        font-weight: 600;
        color: rgb(71 85 105);
        transition:
            background-color 150ms ease,
            border-color 150ms ease,
            color 150ms ease;
        cursor: pointer;
    }

    .result-action {
        height: 30px;
        border-radius: 6px;
        padding: 0 9px;
    }

    .compact-action:hover,
    .result-action:hover {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    button:disabled,
    .result-action:disabled,
    .compact-action:disabled {
        cursor: not-allowed;
        opacity: .4;
    }

    .stat-card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        border-radius: 8px;
        border: 1px solid rgb(226 232 240);
        background: white;
        padding: 8px 10px;
    }

    .stat-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: rgb(148 163 184);
    }

    .stat-value {
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12px;
        font-weight: 600;
        color: rgb(51 65 85);
    }

    button,
    [role="button"],
    a,
    summary,
    [data-clickable],
    [data-active-group] {
        cursor: pointer;
    }

    [data-active-group].is-active {
        border-color: rgb(129 140 248) !important;
        background: rgb(238 242 254) !important;
        color: rgb(67 56 202) !important;
        box-shadow: none !important;
    }

    @media (max-width: 640px) {
        .calc-input {
            height: 40px;
            font-size: 13px;
        }

        .compact-tab {
            padding-left: 8px;
            padding-right: 8px;
        }
    }
</style>

@script
<script>
window.aabiRandomNumberGenerator = function () {
    return {
        mode: 'integer',
        min: 1,
        max: 100,
        count: 5,
        precision: 2,

        unique: false,
        step: 1,
        decimalStep: 0.01,

        parity: 'any',
        primeOnly: false,
        divisor: '',
        excludeInput: '',

        startDate: '',
        endDate: '',
        startTime: '00:00',
        endTime: '23:59',

        listInput: '',

        secureRandom: true,
        secureAvailable: false,

        seeded: false,
        seed: '',

        sort: 'none',

        results: [],
        error: '',
        lastAction: '',
        activePreset: '',

        history: [],
        copyMessage: '',

        init() {
            this.secureAvailable =
                typeof window.crypto !== 'undefined' &&
                typeof window.crypto.getRandomValues === 'function';

            this.setDefaultDates();
            this.restoreSettings();
            this.restoreHistory();
            this.validate();

            if (!this.secureAvailable) {
                this.secureRandom = false;
            }
        },

        setMode(mode) {
            this.mode = mode;
            this.results = [];
            this.error = '';
            this.activePreset = '';
            this.lastAction = '';
            this.validate();
            this.saveSettings();
        },

        validate() {
            this.error = '';

            const count = Number(this.count);

            if (!Number.isInteger(count) || count < 1 || count > 10000) {
                this.error = 'Number of values must be a whole number between 1 and 10,000.';
                return false;
            }

            if (['integer', 'decimal', 'percentage'].includes(this.mode)) {
                const minimum = Number(this.min);
                const maximum = Number(this.max);

                if (!Number.isFinite(minimum) || !Number.isFinite(maximum)) {
                    this.error = 'Minimum and maximum values are required.';
                    return false;
                }

                if (minimum > maximum) {
                    this.error = 'Minimum cannot be greater than maximum.';
                    return false;
                }

                if (this.mode === 'integer') {
                    if (!Number.isSafeInteger(minimum) || !Number.isSafeInteger(maximum)) {
                        this.error = 'Integer limits must be safe JavaScript integers.';
                        return false;
                    }

                    if (!Number.isInteger(Number(this.step)) || Number(this.step) < 1) {
                        this.error = 'Step must be a positive whole number.';
                        return false;
                    }

                    const candidates = this.getIntegerCandidates();

                    if (!candidates.length) {
                        this.error = 'No numbers satisfy the selected filters.';
                        return false;
                    }

                    if (this.unique && count > candidates.length) {
                        this.error =
                            'There are not enough eligible unique values for this quantity.';
                        return false;
                    }
                }

                if (this.mode === 'decimal') {
                    if (!Number.isInteger(Number(this.precision)) ||
                        Number(this.precision) < 0 ||
                        Number(this.precision) > 8
                    ) {
                        this.error = 'Decimal places must be between 0 and 8.';
                        return false;
                    }

                    if (!Number.isFinite(Number(this.decimalStep)) ||
                        Number(this.decimalStep) <= 0
                    ) {
                        this.error = 'Decimal step must be greater than zero.';
                        return false;
                    }
                }

                if (this.mode === 'percentage') {
                    if (minimum < 0 || maximum > 100) {
                        this.error = 'Percentage values must remain between 0 and 100.';
                        return false;
                    }

                    if (!Number.isInteger(Number(this.precision)) ||
                        Number(this.precision) < 0 ||
                        Number(this.precision) > 8
                    ) {
                        this.error = 'Decimal places must be between 0 and 8.';
                        return false;
                    }
                }
            }

            if (this.mode === 'date') {
                if (!this.startDate || !this.endDate) {
                    this.error = 'Start and end dates are required.';
                    return false;
                }

                if (this.startDate > this.endDate) {
                    this.error = 'Start date cannot be after end date.';
                    return false;
                }

                if (this.unique && count > this.dateRangeDays()) {
                    this.error = 'There are not enough unique dates in this range.';
                    return false;
                }
            }

            if (this.mode === 'time') {
                if (!this.startTime || !this.endTime) {
                    this.error = 'Start and end times are required.';
                    return false;
                }

                if (this.startTime > this.endTime) {
                    this.error = 'Start time cannot be after end time.';
                    return false;
                }

                if (this.unique && count > this.timeRangeSeconds() + 1) {
                    this.error = 'There are not enough unique times in this range.';
                    return false;
                }
            }

            if (this.mode === 'list') {
                if (!this.listValues.length) {
                    this.error = 'Enter at least one list value.';
                    return false;
                }

                if (this.unique && count > this.listValues.length) {
                    this.error = 'Quantity cannot exceed the number of list values.';
                    return false;
                }
            }

            if (this.seeded && !String(this.seed).length) {
                this.error = 'Enter a seed or disable seeded generation.';
                return false;
            }

            return true;
        },

        get listValues() {
            return String(this.listInput || '')
                .split(/\r?\n/)
                .map(value => value.trim())
                .filter(Boolean);
        },

        get excludeValues() {
            return String(this.excludeInput || '')
                .split(',')
                .map(value => Number(value.trim()))
                .filter(value => Number.isFinite(value));
        },

        get canGenerate() {
            return !this.error;
        },

        get numericResults() {
            return this.results
                .map(value => Number(value))
                .filter(value => Number.isFinite(value));
        },

        get average() {
            if (!this.numericResults.length) {
                return 0;
            }

            return this.numericResults.reduce(
                (sum, value) => sum + value,
                0
            ) / this.numericResults.length;
        },

        get uniqueResultCount() {
            return new Set(
                this.results.map(value => String(value))
            ).size;
        },

        get distributionBuckets() {
            const values = this.numericResults;

            if (values.length < 10) {
                return [];
            }

            let minimum = Math.min(...values);
            let maximum = Math.max(...values);

            if (minimum === maximum) {
                return [
                    {
                        label: this.formatNumber(minimum),
                        count: values.length
                    }
                ];
            }

            const bucketCount = Math.min(
                12,
                Math.max(5, Math.ceil(Math.sqrt(values.length)))
            );

            const width = (maximum - minimum) / bucketCount;
            const buckets = Array.from(
                { length: bucketCount },
                (_, index) => ({
                    label: this.formatNumber(
                        minimum + width * index
                    ),
                    count: 0
                })
            );

            values.forEach(value => {
                let index = Math.floor(
                    (value - minimum) / width
                );

                if (index >= bucketCount) {
                    index = bucketCount - 1;
                }

                buckets[index].count += 1;
            });

            return buckets;
        },

        distributionHeight(value) {
            const maximum = Math.max(
                ...this.distributionBuckets.map(
                    bucket => bucket.count
                )
            );

            if (!maximum) {
                return 5;
            }

            return Math.max(
                5,
                Math.round((value / maximum) * 100)
            );
        },

        generate() {
            if (!this.validate()) {
                return;
            }

            this.lastAction = 'generate';

            try {
                let values = [];

                if (this.mode === 'integer') {
                    values = this.generateIntegers();
                } else if (this.mode === 'decimal') {
                    values = this.generateDecimals();
                } else if (this.mode === 'percentage') {
                    values = this.generatePercentages();
                } else if (this.mode === 'date') {
                    values = this.generateDates();
                } else if (this.mode === 'time') {
                    values = this.generateTimes();
                } else if (this.mode === 'boolean') {
                    values = this.generateBooleans();
                } else if (this.mode === 'list') {
                    values = this.generateList();
                }

                if (this.sort === 'ascending') {
                    values.sort(this.compareAscending.bind(this));
                }

                if (this.sort === 'descending') {
                    values.sort(this.compareDescending.bind(this));
                }

                if (this.sort === 'shuffle') {
                    this.shuffle(values);
                }

                this.results = values;

                this.addHistory();
                this.saveSettings();
            } catch (error) {
                this.results = [];
                this.error = error instanceof Error
                    ? error.message
                    : 'Unable to generate results.';
            }
        },

        generateIntegers() {
            const candidates = this.getIntegerCandidates();

            if (!candidates.length) {
                throw new Error(
                    'No eligible integer exists in the selected range.'
                );
            }

            if (this.unique) {
                return this.sampleWithoutReplacement(
                    candidates,
                    Number(this.count)
                );
            }

            return Array.from(
                { length: Number(this.count) },
                () => candidates[
                    this.randomIndex(candidates.length)
                ]
            );
        },

        generateDecimals() {
            const minimum = Number(this.min);
            const maximum = Number(this.max);
            const step = Number(this.decimalStep);
            const precision = Number(this.precision);

            const scale = 10 ** precision;

            const first = Math.ceil(
                minimum * scale / (step * scale)
            ) * step;

            const values = [];
            const maxIterations = 1000000;

            for (
                let value = first, iterations = 0;
                value <= maximum + (step / 100000000);
                value += step, iterations++
            ) {
                if (iterations > maxIterations) {
                    break;
                }

                values.push(
                    Number(value.toFixed(precision))
                );
            }

            if (!values.length) {
                throw new Error(
                    'No decimal values exist for the selected range and step.'
                );
            }

            if (this.unique) {
                if (Number(this.count) > values.length) {
                    throw new Error(
                        'There are not enough unique decimal values available.'
                    );
                }

                return this.sampleWithoutReplacement(
                    values,
                    Number(this.count)
                );
            }

            return Array.from(
                { length: Number(this.count) },
                () => values[this.randomIndex(values.length)]
            );
        },

        generatePercentages() {
            return this.generateDecimals();
        },

        generateDates() {
            const start = this.parseDateToUtc(this.startDate);
            const end = this.parseDateToUtc(this.endDate);

            const days = Math.floor(
                (end - start) / 86400000
            ) + 1;

            if (this.unique) {
                const indices = this.sampleIntegerRange(
                    0,
                    days - 1,
                    Number(this.count),
                    true
                );

                return indices.map(
                    index => this.formatDate(
                        new Date(start + index * 86400000)
                    )
                );
            }

            return Array.from(
                { length: Number(this.count) },
                () => {
                    const offset = this.randomInteger(
                        0,
                        days - 1
                    );

                    return this.formatDate(
                        new Date(start + offset * 86400000)
                    );
                }
            );
        },

        generateTimes() {
            const start = this.timeToSeconds(this.startTime);
            const end = this.timeToSeconds(this.endTime);

            if (this.unique) {
                const seconds = this.sampleIntegerRange(
                    start,
                    end,
                    Number(this.count),
                    true
                );

                return seconds.map(
                    value => this.secondsToTime(value)
                );
            }

            return Array.from(
                { length: Number(this.count) },
                () => this.secondsToTime(
                    this.randomInteger(start, end)
                )
            );
        },

        generateBooleans() {
            return Array.from(
                { length: Number(this.count) },
                () => this.randomInteger(0, 1) === 1
            );
        },

        generateList() {
            const values = [...this.listValues];

            if (this.unique) {
                return this.sampleWithoutReplacement(
                    values,
                    Number(this.count)
                );
            }

            return Array.from(
                { length: Number(this.count) },
                () => values[this.randomIndex(values.length)]
            );
        },

        getIntegerCandidates() {
            const minimum = Number(this.min);
            const maximum = Number(this.max);
            const increment = Number(this.step);
            const excluded = new Set(this.excludeValues);

            const estimatedCount =
                Math.floor(
                    (maximum - minimum) / increment
                ) + 1;

            if (estimatedCount > 1000000) {
                return this.generateFilteredCandidatesLazy(
                    minimum,
                    maximum,
                    increment,
                    excluded
                );
            }

            const candidates = [];

            for (
                let value = minimum;
                value <= maximum;
                value += increment
            ) {
                if (excluded.has(value)) {
                    continue;
                }

                if (
                    this.parity === 'even' &&
                    value % 2 !== 0
                ) {
                    continue;
                }

                if (
                    this.parity === 'odd' &&
                    value % 2 === 0
                ) {
                    continue;
                }

                if (
                    this.primeOnly &&
                    !this.isPrime(value)
                ) {
                    continue;
                }

                if (
                    this.divisor &&
                    value % Number(this.divisor) !== 0
                ) {
                    continue;
                }

                candidates.push(value);
            }

            return candidates;
        },

        generateFilteredCandidatesLazy(
            minimum,
            maximum,
            increment,
            excluded
        ) {
            const candidates = [];

            if (
                maximum - minimum > 100000000 &&
                (
                    this.primeOnly ||
                    this.excludeValues.length > 100
                )
            ) {
                throw new Error(
                    'The selected filtered range is too large. Reduce the range.'
                );
            }

            for (
                let value = minimum;
                value <= maximum;
                value += increment
            ) {
                if (excluded.has(value)) {
                    continue;
                }

                if (
                    this.parity === 'even' &&
                    value % 2 !== 0
                ) {
                    continue;
                }

                if (
                    this.parity === 'odd' &&
                    value % 2 === 0
                ) {
                    continue;
                }

                if (
                    this.primeOnly &&
                    !this.isPrime(value)
                ) {
                    continue;
                }

                if (
                    this.divisor &&
                    value % Number(this.divisor) !== 0
                ) {
                    continue;
                }

                candidates.push(value);

                if (candidates.length >= 1000000) {
                    break;
                }
            }

            return candidates;
        },

        sampleIntegerRange(minimum, maximum, count, unique) {
            if (unique) {
                const values = [];

                const available = maximum - minimum + 1;

                if (count > available) {
                    throw new Error(
                        'Not enough unique values are available.'
                    );
                }

                if (available <= 1000000) {
                    const pool = Array.from(
                        { length: available },
                        (_, index) => minimum + index
                    );

                    return this.sampleWithoutReplacement(
                        pool,
                        count
                    );
                }
            }

            return Array.from(
                { length: count },
                () => this.randomInteger(minimum, maximum)
            );
        },

        sampleWithoutReplacement(values, count) {
            const pool = [...values];
            const result = [];

            for (let index = 0; index < count; index++) {
                const randomIndex = this.randomIndex(pool.length);

                result.push(pool[randomIndex]);

                const lastIndex = pool.length - 1;

                pool[randomIndex] = pool[lastIndex];
                pool.pop();
            }

            return result;
        },

        shuffle(values) {
            for (let index = values.length - 1; index > 0; index--) {
                const randomIndex = this.randomIndex(index + 1);

                [values[index], values[randomIndex]] = [
                    values[randomIndex],
                    values[index]
                ];
            }

            return values;
        },

        randomIndex(length) {
            if (!Number.isInteger(length) || length <= 0) {
                throw new Error('Invalid random selection size.');
            }

            return this.randomInteger(0, length - 1);
        },

        randomInteger(minimum, maximum) {
            if (this.seeded) {
                return this.seededInteger(
                    minimum,
                    maximum
                );
            }

            if (
                this.secureRandom &&
                this.secureAvailable
            ) {
                return this.secureRandomInteger(
                    minimum,
                    maximum
                );
            }

            return Math.floor(
                Math.random() * (maximum - minimum + 1)
            ) + minimum;
        },

        secureRandomInteger(minimum, maximum) {
            if (
                !Number.isSafeInteger(minimum) ||
                !Number.isSafeInteger(maximum) ||
                minimum > maximum
            ) {
                throw new Error(
                    'Invalid secure random integer range.'
                );
            }

            const range = maximum - minimum + 1;

            if (!Number.isSafeInteger(range)) {
                throw new Error(
                    'The selected integer range is too large.'
                );
            }

            if (range === 1) {
                return minimum;
            }

            const maxUint32 = 0x100000000;
            const limit =
                Math.floor(maxUint32 / range) * range;

            let random;

            do {
                const buffer = new Uint32Array(1);
                window.crypto.getRandomValues(buffer);
                random = buffer[0];
            } while (random >= limit);

            return minimum + (random % range);
        },

        seededInteger(minimum, maximum) {
            const range = maximum - minimum + 1;

            if (range <= 0) {
                throw new Error('Invalid seeded range.');
            }

            return minimum + Math.floor(
                this.seededRandom() * range
            );
        },

        seededRandom() {
            let hash = 2166136261 >>> 0;
            const text = String(this.seed);

            for (let index = 0; index < text.length; index++) {
                hash ^= text.charCodeAt(index);
                hash = Math.imul(
                    hash,
                    16777619
                );
            }

            hash += this.seedCounter++;

            let value = hash >>> 0;

            value ^= value << 13;
            value ^= value >>> 17;
            value ^= value << 5;

            return (value >>> 0) / 4294967296;
        },

        get seedCounter() {
            if (!this._seedCounter) {
                this._seedCounter = 0;
            }

            return this._seedCounter;
        },

        set seedCounter(value) {
            this._seedCounter = value;
        },

        isPrime(value) {
            if (!Number.isSafeInteger(value) || value < 2) {
                return false;
            }

            if (value === 2) {
                return true;
            }

            if (value % 2 === 0) {
                return false;
            }

            const limit = Math.floor(
                Math.sqrt(value)
            );

            for (
                let divisor = 3;
                divisor <= limit;
                divisor += 2
            ) {
                if (value % divisor === 0) {
                    return false;
                }
            }

            return true;
        },

        formatResult(value) {
            if (this.mode === 'boolean') {
                return value ? 'true' : 'false';
            }

            if (
                this.mode === 'decimal' ||
                this.mode === 'percentage'
            ) {
                return Number(value).toFixed(
                    Number(this.precision)
                );
            }

            return String(value);
        },

        formatNumber(value) {
            if (!Number.isFinite(Number(value))) {
                return '—';
            }

            if (
                this.mode === 'decimal' ||
                this.mode === 'percentage'
            ) {
                return Number(value).toFixed(
                    Number(this.precision)
                );
            }

            return Number(value).toLocaleString();
        },

        compareAscending(a, b) {
            const numberA = Number(a);
            const numberB = Number(b);

            if (
                Number.isFinite(numberA) &&
                Number.isFinite(numberB)
            ) {
                return numberA - numberB;
            }

            return String(a).localeCompare(
                String(b)
            );
        },

        compareDescending(a, b) {
            return this.compareAscending(b, a);
        },

        parseDateToUtc(value) {
            const parts = String(value)
                .split('-')
                .map(Number);

            return Date.UTC(
                parts[0],
                parts[1] - 1,
                parts[2]
            );
        },

        formatDate(date) {
            return [
                date.getUTCFullYear(),
                String(
                    date.getUTCMonth() + 1
                ).padStart(2, '0'),
                String(
                    date.getUTCDate()
                ).padStart(2, '0')
            ].join('-');
        },

        dateRangeDays() {
            if (!this.startDate || !this.endDate) {
                return 0;
            }

            return Math.floor(
                (
                    this.parseDateToUtc(this.endDate) -
                    this.parseDateToUtc(this.startDate)
                ) / 86400000
            ) + 1;
        },

        timeToSeconds(value) {
            const parts = String(value)
                .split(':')
                .map(Number);

            return (
                parts[0] * 3600 +
                parts[1] * 60
            );
        },

        secondsToTime(seconds) {
            const hours = Math.floor(
                seconds / 3600
            );

            const minutes = Math.floor(
                (seconds % 3600) / 60
            );

            const remainingSeconds =
                seconds % 60;

            return [
                String(hours).padStart(2, '0'),
                String(minutes).padStart(2, '0'),
                String(remainingSeconds).padStart(2, '0')
            ].join(':');
        },

        timeRangeSeconds() {
            return (
                this.timeToSeconds(this.endTime) -
                this.timeToSeconds(this.startTime)
            );
        },

        setDefaultDates() {
            const today = new Date();

            const end = new Date(today);
            end.setDate(
                end.getDate() + 30
            );

            this.startDate = this.formatDate(today);
            this.endDate = this.formatDate(end);
        },

        applyPreset(preset) {
            this.activePreset = preset;
            this.seeded = false;
            this.seed = '';

            switch (preset) {
                case 'dice':
                    this.mode = 'integer';
                    this.min = 1;
                    this.max = 6;
                    this.count = 1;
                    this.step = 1;
                    this.unique = false;
                    this.parity = 'any';
                    this.primeOnly = false;
                    this.divisor = '';
                    this.excludeInput = '';
                    break;

                case 'coin':
                    this.mode = 'integer';
                    this.min = 0;
                    this.max = 1;
                    this.count = 1;
                    this.step = 1;
                    this.unique = false;
                    break;

                case 'lottery':
                    this.mode = 'integer';
                    this.min = 1;
                    this.max = 49;
                    this.count = 6;
                    this.step = 1;
                    this.unique = true;
                    this.parity = 'any';
                    this.primeOnly = false;
                    this.divisor = '';
                    this.excludeInput = '';
                    break;

                case 'percent':
                    this.mode = 'percentage';
                    this.min = 0;
                    this.max = 100;
                    this.count = 5;
                    this.precision = 2;
                    this.decimalStep = 0.01;
                    this.unique = false;
                    break;

                case 'negative-positive':
                    this.mode = 'integer';
                    this.min = -100;
                    this.max = 100;
                    this.count = 10;
                    this.step = 1;
                    this.unique = false;
                    break;

                case 'testing':
                    this.mode = 'integer';
                    this.min = 1;
                    this.max = 1000;
                    this.count = 100;
                    this.step = 1;
                    this.unique = false;
                    this.sort = 'none';
                    break;

                case 'simulation':
                    this.mode = 'decimal';
                    this.min = 0;
                    this.max = 1;
                    this.count = 100;
                    this.precision = 4;
                    this.decimalStep = 0.0001;
                    this.unique = false;
                    this.sort = 'none';
                    break;
            }

            this.validate();
            this.saveSettings();
        },

        loadExample() {
            this.mode = 'integer';
            this.min = 1;
            this.max = 100;
            this.count = 10;
            this.precision = 2;
            this.step = 1;
            this.decimalStep = 0.01;
            this.unique = false;
            this.parity = 'any';
            this.primeOnly = false;
            this.divisor = '';
            this.excludeInput = '';
            this.sort = 'none';
            this.seeded = false;
            this.seed = '';

            this.activePreset = '';
            this.results = [];
            this.error = '';
        },

        reset() {
            this.mode = 'integer';
            this.min = 1;
            this.max = 100;
            this.count = 5;
            this.precision = 2;
            this.step = 1;
            this.decimalStep = 0.01;
            this.unique = false;
            this.parity = 'any';
            this.primeOnly = false;
            this.divisor = '';
            this.excludeInput = '';

            this.setDefaultDates();

            this.startTime = '00:00';
            this.endTime = '23:59';

            this.listInput = '';

            this.seeded = false;
            this.seed = '';

            this.sort = 'none';

            this.results = [];
            this.error = '';
            this.lastAction = 'reset';
            this.activePreset = '';
            this.copyMessage = '';

            this.saveSettings();
        },

        clearResults() {
            this.results = [];
            this.error = '';
            this.lastAction = '';
        },

        async copyResults(format) {
            if (!this.results.length) {
                return;
            }

            let text = '';

            if (format === 'json') {
                text = JSON.stringify(
                    this.results,
                    null,
                    2
                );
            } else if (format === 'comma') {
                text = this.results
                    .map(value => this.formatResult(value))
                    .join(', ');
            } else {
                text = this.results
                    .map(value => this.formatResult(value))
                    .join('\n');
            }

            try {
                await this.copyText(text);

                this.copyMessage = 'Copied to clipboard';

                window.setTimeout(() => {
                    this.copyMessage = '';
                }, 1800);
            } catch {
                this.copyMessage =
                    'Unable to copy. Please copy the results manually.';

                window.setTimeout(() => {
                    this.copyMessage = '';
                }, 2500);
            }
        },

        async copyText(text) {
            if (
                navigator.clipboard &&
                typeof navigator.clipboard.writeText === 'function'
            ) {
                await navigator.clipboard.writeText(text);
                return;
            }

            const textarea =
                document.createElement('textarea');

            textarea.value = text;
            textarea.setAttribute(
                'readonly',
                ''
            );

            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';

            document.body.appendChild(textarea);
            textarea.select();

            const copied =
                document.execCommand('copy');

            textarea.remove();

            if (!copied) {
                throw new Error(
                    'Clipboard operation failed.'
                );
            }
        },

        downloadResults(format) {
            if (!this.results.length) {
                return;
            }

            let content;
            let mime;
            let extension;

            if (format === 'csv') {
                content =
                    'index,value\n' +
                    this.results
                        .map(
                            (value, index) =>
                                (index + 1) +
                                ',' +
                                JSON.stringify(
                                    this.formatResult(value)
                                )
                        )
                        .join('\n');

                mime = 'text/csv;charset=utf-8';
                extension = 'csv';
            } else {
                content = this.results
                    .map(value => this.formatResult(value))
                    .join('\n');

                mime = 'text/plain;charset=utf-8';
                extension = 'txt';
            }

            const blob = new Blob(
                [content],
                { type: mime }
            );

            const url =
                URL.createObjectURL(blob);

            const link =
                document.createElement('a');

            link.href = url;
            link.download =
                'aabitech-random-results.' +
                extension;

            document.body.appendChild(link);
            link.click();
            link.remove();

            URL.revokeObjectURL(url);
        },

        addHistory() {
            if (!this.results.length) {
                return;
            }

            const item = {
                id:
                    typeof crypto !== 'undefined' &&
                    typeof crypto.randomUUID === 'function'
                        ? crypto.randomUUID()
                        : String(Date.now()) +
                          '-' +
                          String(this.history.length),

                mode: this.mode,
                min: this.min,
                max: this.max,
                count: this.count,
                precision: this.precision,
                unique: this.unique,
                step: this.step,
                decimalStep: this.decimalStep,
                sort: this.sort,
                parity: this.parity,
                primeOnly: this.primeOnly,
                divisor: this.divisor,
                excludeInput: this.excludeInput,
                startDate: this.startDate,
                endDate: this.endDate,
                startTime: this.startTime,
                endTime: this.endTime,
                listInput: this.listInput,
                results: [...this.results],

                summary:
                    this.mode +
                    ' · ' +
                    this.count +
                    ' value' +
                    (this.count === 1 ? '' : 's'),

                timestamp: Date.now()
            };

            this.history = [
                item,
                ...this.history.filter(existing =>
                    JSON.stringify(existing.results) !==
                    JSON.stringify(item.results)
                )
            ].slice(0, 6);

            this.persistHistory();
        },

        restoreHistory() {
            try {
                const saved =
                    localStorage.getItem(
                        'aabi_random_number_history'
                    );

                if (!saved) {
                    this.history = [];
                    return;
                }

                const parsed =
                    JSON.parse(saved);

                this.history =
                    Array.isArray(parsed)
                        ? parsed.slice(0, 6)
                        : [];
            } catch {
                this.history = [];
            }
        },

        restoreHistoryItem(item) {
            this.mode = item.mode || 'integer';
            this.min = item.min ?? 1;
            this.max = item.max ?? 100;
            this.count = item.count ?? 5;
            this.precision = item.precision ?? 2;
            this.unique = item.unique ?? false;
            this.step = item.step ?? 1;
            this.decimalStep = item.decimalStep ?? 0.01;
            this.sort = item.sort || 'none';
            this.parity = item.parity || 'any';
            this.primeOnly = item.primeOnly ?? false;
            this.divisor = item.divisor ?? '';
            this.excludeInput = item.excludeInput ?? '';
            this.startDate = item.startDate || this.startDate;
            this.endDate = item.endDate || this.endDate;
            this.startTime = item.startTime || '00:00';
            this.endTime = item.endTime || '23:59';
            this.listInput = item.listInput || '';

            this.results = Array.isArray(item.results)
                ? [...item.results]
                : [];

            this.error = '';
            this.activePreset = '';
            this.lastAction = '';

            this.validate();
        },

        clearHistory() {
            this.history = [];

            localStorage.removeItem(
                'aabi_random_number_history'
            );
        },

        persistHistory() {
            try {
                localStorage.setItem(
                    'aabi_random_number_history',
                    JSON.stringify(this.history)
                );
            } catch {
                // History is optional; generation must continue.
            }
        },

        saveSettings() {
            try {
                const settings = {
                    mode: this.mode,
                    min: this.min,
                    max: this.max,
                    count: this.count,
                    precision: this.precision,
                    unique: this.unique,
                    step: this.step,
                    decimalStep: this.decimalStep,
                    parity: this.parity,
                    primeOnly: this.primeOnly,
                    divisor: this.divisor,
                    excludeInput: this.excludeInput,
                    startDate: this.startDate,
                    endDate: this.endDate,
                    startTime: this.startTime,
                    endTime: this.endTime,
                    listInput: this.listInput,
                    secureRandom: this.secureRandom,
                    sort: this.sort
                };

                localStorage.setItem(
                    'aabi_random_number_settings',
                    JSON.stringify(settings)
                );
            } catch {
                // Local settings are optional.
            }
        },

        restoreSettings() {
            try {
                const saved =
                    localStorage.getItem(
                        'aabi_random_number_settings'
                    );

                if (!saved) {
                    return;
                }

                const settings =
                    JSON.parse(saved);

                Object.keys(settings).forEach(key => {
                    if (
                        Object.prototype.hasOwnProperty.call(
                            this,
                            key
                        )
                    ) {
                        this[key] = settings[key];
                    }
                });
            } catch {
                // Ignore invalid local settings.
            }
        },

        handleShortcut(event) {
            const target =
                event.target;

            const tag =
                target && target.tagName
                    ? target.tagName.toLowerCase()
                    : '';

            const isTyping =
                tag === 'input' ||
                tag === 'textarea' ||
                tag === 'select' ||
                target?.isContentEditable;

            if (
                event.key === 'Escape'
            ) {
                this.error = '';
                this.copyMessage = '';
                return;
            }

            if (
                event.key === 'Enter' &&
                (event.ctrlKey || event.metaKey)
            ) {
                event.preventDefault();
                this.generate();
                return;
            }

            if (
                event.key.toLowerCase() === 'k' &&
                (event.ctrlKey || event.metaKey) &&
                !isTyping
            ) {
                event.preventDefault();

                const input =
                    this.$root.querySelector(
                        'input:not([type="hidden"]), textarea'
                    );

                if (input) {
                    input.focus();
                }
            }
        }
    };
};
</script>
@endscript