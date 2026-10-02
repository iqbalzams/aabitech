<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only tool. No server-side state is required.
};
?>

<div
    x-data="aabiUnixTimestampConverter()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/60 px-4 py-3 sm:px-5">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap gap-1" role="tablist" aria-label="Timestamp converter mode">
                    <button type="button" role="tab" data-active-group="timestamp-mode" :aria-selected="mode === 'timestamp'" :class="{ 'is-active': mode === 'timestamp' }" @click="setMode('timestamp')" class="compact-tab">Timestamp → Date</button>
                    <button type="button" role="tab" data-active-group="timestamp-mode" :aria-selected="mode === 'date'" :class="{ 'is-active': mode === 'date' }" @click="setMode('date')" class="compact-tab">Date → Timestamp</button>
                    <button type="button" role="tab" data-active-group="timestamp-mode" :aria-selected="mode === 'duration'" :class="{ 'is-active': mode === 'duration' }" @click="setMode('duration')" class="compact-tab">Duration</button>
                    <button type="button" role="tab" data-active-group="timestamp-mode" :aria-selected="mode === 'bulk'" :class="{ 'is-active': mode === 'bulk' }" @click="setMode('bulk')" class="compact-tab">Bulk</button>
                </div>

                <div class="flex items-center gap-2">
                    <span class="rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-semibold text-indigo-700">
                        Client-side
                    </span>

                    <button
                        type="button"
                        @click="reset()"
                        class="result-action"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="grid gap-4 p-4 lg:grid-cols-[1.02fr_0.98fr] lg:p-5">
            <div class="min-w-0 space-y-4">

                <template x-if="mode === 'timestamp'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900">
                                    Convert Unix timestamp
                                </h2>

                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    Enter seconds, milliseconds, microseconds, or nanoseconds.
                                </p>
                            </div>

                            <span
                                x-show="detectedUnit"
                                x-text="'Detected: ' + detectedUnitLabel"
                                class="rounded-md bg-white px-2 py-1 text-[10px] font-semibold text-indigo-600 shadow-sm"
                            ></span>
                        </div>

                        <label class="field-label">
                            Timestamp
                        </label>

                        <div class="mt-1.5 flex gap-2">
                            <input
                                x-model="timestampInput"
                                @input="convertTimestamp()"
                                @keydown.enter.prevent="convertTimestamp()"
                                type="text"
                                inputmode="numeric"
                                class="calc-input"
                                placeholder="e.g. 1750000000"
                                aria-label="Unix timestamp"
                            >

                            <button
                                type="button"
                                @click="timestampInput = String(currentTimestampSeconds); convertTimestamp()"
                                class="compact-action shrink-0"
                            >
                                Now
                            </button>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-1">
                            <button
                                type="button"
                                data-active-group="timestamp-unit"
                                :class="{ 'is-active': timestampUnit === 'auto' }"
                                @click="timestampUnit='auto'; convertTimestamp()"
                                class="compact-tab"
                            >
                                Auto
                            </button>

                            <button
                                type="button"
                                data-active-group="timestamp-unit"
                                :class="{ 'is-active': timestampUnit === 's' }"
                                @click="timestampUnit='s'; convertTimestamp()"
                                class="compact-tab"
                            >
                                Seconds
                            </button>

                            <button
                                type="button"
                                data-active-group="timestamp-unit"
                                :class="{ 'is-active': timestampUnit === 'ms' }"
                                @click="timestampUnit='ms'; convertTimestamp()"
                                class="compact-tab"
                            >
                                Milliseconds
                            </button>

                            <button
                                type="button"
                                data-active-group="timestamp-unit"
                                :class="{ 'is-active': timestampUnit === 'us' }"
                                @click="timestampUnit='us'; convertTimestamp()"
                                class="compact-tab"
                            >
                                Microseconds
                            </button>

                            <button
                                type="button"
                                data-active-group="timestamp-unit"
                                :class="{ 'is-active': timestampUnit === 'ns' }"
                                @click="timestampUnit='ns'; convertTimestamp()"
                                class="compact-tab"
                            >
                                Nanoseconds
                            </button>
                        </div>

                        <div class="mt-3 grid gap-3 sm:grid-cols-[1fr_auto]">
                            <div>
                                <label class="field-label">
                                    Display timezone
                                </label>

                                <select
                                    x-model="selectedTimezone"
                                    @change="convertTimestamp(); saveSettings()"
                                    class="calc-input mt-1.5"
                                >
                                    <option value="local">
                                        Local timezone
                                    </option>

                                    <option value="UTC">
                                        UTC
                                    </option>

                                    <template x-for="zone in timezoneOptions" :key="zone">
                                        <option
                                            :value="zone"
                                            x-text="zone"
                                        ></option>
                                    </template>
                                </select>
                            </div>

                            <div class="flex items-end gap-1">
                                <button
                                    type="button"
                                    @click="useExample('unix')"
                                    class="compact-tab"
                                >
                                    Example
                                </button>

                                <button
                                    type="button"
                                    @click="timestampInput=''; clearConversion()"
                                    class="compact-tab"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'date'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3">
                            <h2 class="text-sm font-semibold text-slate-900">
                                Convert date/time
                            </h2>

                            <p class="mt-0.5 text-[11px] text-slate-500">
                                Convert a local date/time in the selected timezone to Unix time.
                            </p>
                        </div>

                        <label class="field-label">
                            Date and time
                        </label>

                        <input
                            x-model="dateInput"
                            @input="convertDate()"
                            type="datetime-local"
                            step="1"
                            class="calc-input mt-1.5"
                            aria-label="Date and time"
                        >

                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="field-label">
                                    Timezone
                                </label>

                                <select
                                    x-model="selectedTimezone"
                                    @change="convertDate(); saveSettings()"
                                    class="calc-input mt-1.5"
                                >
                                    <option value="local">
                                        Local timezone
                                    </option>

                                    <option value="UTC">
                                        UTC
                                    </option>

                                    <template x-for="zone in timezoneOptions" :key="zone">
                                        <option
                                            :value="zone"
                                            x-text="zone"
                                        ></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="field-label">
                                    Custom format
                                </label>

                                <select
                                    x-model="formatPreset"
                                    @change="refreshOutput(); saveSettings()"
                                    class="calc-input mt-1.5"
                                >
                                    <option value="standard">
                                        Standard
                                    </option>

                                    <option value="iso">
                                        ISO 8601
                                    </option>

                                    <option value="rfc">
                                        RFC 2822
                                    </option>

                                    <option value="utc">
                                        UTC
                                    </option>

                                    <option value="custom">
                                        Custom
                                    </option>
                                </select>
                            </div>
                        </div>

                        <template x-if="formatPreset === 'custom'">
                            <div class="mt-3">
                                <label class="field-label">
                                    Format pattern
                                </label>

                                <input
                                    x-model="customFormat"
                                    @input="refreshOutput()"
                                    type="text"
                                    class="calc-input mt-1.5"
                                    placeholder="YYYY-MM-DD HH:mm:ss"
                                >

                                <p class="mt-1 text-[10px] text-slate-500">
                                    Tokens: YYYY MM DD HH hh mm ss SSS.
                                </p>
                            </div>
                        </template>

                        <div class="mt-3 flex flex-wrap gap-1">
                            <button
                                type="button"
                                data-active-group="hour-format"
                                :class="{ 'is-active': hour12 === false }"
                                @click="hour12=false; refreshOutput(); saveSettings()"
                                class="compact-tab"
                            >
                                24-hour
                            </button>

                            <button
                                type="button"
                                data-active-group="hour-format"
                                :class="{ 'is-active': hour12 === true }"
                                @click="hour12=true; refreshOutput(); saveSettings()"
                                class="compact-tab"
                            >
                                12-hour
                            </button>

                            <button
                                type="button"
                                @click="useExample('date')"
                                class="compact-tab"
                            >
                                Example
                            </button>

                            <button
                                type="button"
                                @click="dateInput=''; clearConversion()"
                                class="compact-tab"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'duration'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3">
                            <h2 class="text-sm font-semibold text-slate-900">
                                Timestamp difference
                            </h2>

                            <p class="mt-0.5 text-[11px] text-slate-500">
                                Calculate the exact difference between two Unix timestamps.
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="field-label">
                                    Start timestamp
                                </label>

                                <input
                                    x-model="durationStart"
                                    @input="calculateDuration()"
                                    type="text"
                                    class="calc-input mt-1.5"
                                    placeholder="1750000000"
                                >
                            </div>

                            <div>
                                <label class="field-label">
                                    End timestamp
                                </label>

                                <input
                                    x-model="durationEnd"
                                    @input="calculateDuration()"
                                    type="text"
                                    class="calc-input mt-1.5"
                                    placeholder="1750086400"
                                >
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-1">
                            <button
                                type="button"
                                @click="durationStart=String(currentTimestampSeconds); durationEnd=String(currentTimestampSeconds+86400); calculateDuration()"
                                class="compact-tab"
                            >
                                24 hours
                            </button>

                            <button
                                type="button"
                                @click="useExample('duration')"
                                class="compact-tab"
                            >
                                Example
                            </button>

                            <button
                                type="button"
                                @click="durationStart=''; durationEnd=''; calculateDuration()"
                                class="compact-tab"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'bulk'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3">
                            <h2 class="text-sm font-semibold text-slate-900">
                                Bulk timestamp conversion
                            </h2>

                            <p class="mt-0.5 text-[11px] text-slate-500">
                                One timestamp per line. Units are detected automatically.
                            </p>
                        </div>

                        <textarea
                            x-model="bulkInput"
                            @input="convertBulk()"
                            rows="8"
                            class="calc-textarea"
                            placeholder="1750000000&#10;1750000000000&#10;0"
                        ></textarea>

                        <div class="mt-2 flex flex-wrap gap-1">
                            <button
                                type="button"
                                @click="useExample('bulk')"
                                class="compact-tab"
                            >
                                Load examples
                            </button>

                            <label class="compact-tab cursor-pointer">
                                <input
                                    type="file"
                                    accept=".txt,.csv,text/plain,text/csv"
                                    class="sr-only"
                                    @change="importBulk($event)"
                                >
                                Import TXT/CSV
                            </label>

                            <button
                                type="button"
                                @click="bulkInput=''; bulkResults=[]; hasResult=false"
                                class="compact-tab"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </template>

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Current Unix timestamp
                            </h2>

                            <p class="text-[11px] text-slate-500">
                                Live clock based on your browser.
                            </p>
                        </div>

                        <span
                            class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-500"
                            x-text="localTimezone"
                        ></span>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2">
                        <button
                            type="button"
                            @click="copyText(String(currentTimestampSeconds), $event)"
                            class="live-value text-left"
                        >
                            <span class="stat-label">
                                Seconds
                            </span>

                            <span
                                class="mt-1 block text-lg font-bold text-slate-900"
                                x-text="currentTimestampSeconds"
                            ></span>
                        </button>

                        <button
                            type="button"
                            @click="copyText(String(currentTimestampMilliseconds), $event)"
                            class="live-value text-left"
                        >
                            <span class="stat-label">
                                Milliseconds
                            </span>

                            <span
                                class="mt-1 block text-lg font-bold text-slate-900"
                                x-text="currentTimestampMilliseconds"
                            ></span>
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <div class="mb-2 flex items-center justify-between">
                        <h2 class="text-xs font-semibold text-slate-700">
                            Options
                        </h2>

                        <button
                            type="button"
                            @click="showAdvanced=!showAdvanced"
                            class="compact-tab"
                            x-text="showAdvanced ? 'Hide' : 'Show'"
                        ></button>
                    </div>

                    <div
                        x-show="showAdvanced"
                        x-collapse
                        class="grid gap-3 sm:grid-cols-2"
                    >
                        <div>
                            <label class="field-label">
                                Epoch
                            </label>

                            <select
                                x-model="epochMode"
                                @change="refreshOutput(); saveSettings()"
                                class="calc-input mt-1.5"
                            >
                                <option value="unix">
                                    Unix — 1970-01-01
                                </option>

                                <option value="custom">
                                    Custom epoch
                                </option>
                            </select>
                        </div>

                        <div x-show="epochMode === 'custom'">
                            <label class="field-label">
                                Custom epoch ISO
                            </label>

                            <input
                                x-model="customEpoch"
                                @input="refreshOutput()"
                                type="text"
                                class="calc-input mt-1.5"
                                placeholder="2000-01-01T00:00:00Z"
                            >
                        </div>

                        <div>
                            <label class="field-label">
                                Relative time
                            </label>

                            <button
                                type="button"
                                data-active-group="relative-time"
                                :class="{ 'is-active': relativeEnabled }"
                                @click="relativeEnabled=!relativeEnabled; refreshOutput(); saveSettings()"
                                class="toggle-tab w-full"
                            >
                                <span x-text="relativeEnabled ? 'Enabled' : 'Disabled'"></span>
                            </button>
                        </div>

                        <div>
                            <label class="field-label">
                                Timezone comparison
                            </label>

                            <button
                                type="button"
                                data-active-group="zone-compare"
                                :class="{ 'is-active': zoneCompareEnabled }"
                                @click="zoneCompareEnabled=!zoneCompareEnabled; refreshOutput(); saveSettings()"
                                class="toggle-tab w-full"
                            >
                                <span x-text="zoneCompareEnabled ? 'Enabled' : 'Disabled'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="min-w-0 space-y-4">

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Converted result
                            </h2>

                            <p
                                class="text-[11px] text-slate-500"
                                x-text="resultMeta"
                            ></p>
                        </div>

                        <div class="flex gap-1.5">
                            <button
                                type="button"
                                @click="copyText(primaryCopyValue, $event)"
                                :disabled="!hasResult"
                                class="result-action"
                            >
                                Copy result
                            </button>

                            <button
                                type="button"
                                @click="downloadResult()"
                                :disabled="!hasResult"
                                class="result-action"
                            >
                                Export
                            </button>
                        </div>
                    </div>

                    <template x-if="hasResult && mode !== 'bulk'">
                        <div class="space-y-2">
                            <div class="result-box">
                                <span class="result-label">
                                    Human-readable
                                </span>

                                <span
                                    class="result-main"
                                    x-text="humanReadable"
                                ></span>
                            </div>

                            <div class="grid gap-2 sm:grid-cols-2">
                                <div class="stat-card">
                                    <span class="stat-label">
                                        Unix
                                    </span>

                                    <span
                                        class="stat-value break-all"
                                        x-text="unixOutput"
                                    ></span>
                                </div>

                                <div class="stat-card">
                                    <span class="stat-label">
                                        ISO 8601
                                    </span>

                                    <span
                                        class="stat-value break-all"
                                        x-text="isoOutput"
                                    ></span>
                                </div>

                                <div class="stat-card">
                                    <span class="stat-label">
                                        UTC
                                    </span>

                                    <span
                                        class="stat-value break-all"
                                        x-text="utcOutput"
                                    ></span>
                                </div>

                                <div class="stat-card">
                                    <span class="stat-label">
                                        Timezone
                                    </span>

                                    <span
                                        class="stat-value break-all"
                                        x-text="timezoneOutput"
                                    ></span>
                                </div>

                                <div class="stat-card">
                                    <span class="stat-label">
                                        RFC 2822
                                    </span>

                                    <span
                                        class="stat-value break-all"
                                        x-text="rfcOutput"
                                    ></span>
                                </div>

                                <div class="stat-card">
                                    <span class="stat-label">
                                        Calendar details
                                    </span>

                                    <span
                                        class="stat-value"
                                        x-text="calendarDetails"
                                    ></span>
                                </div>
                            </div>

                            <div
                                x-show="relativeEnabled"
                                class="rounded-lg border border-indigo-100 bg-indigo-50/60 px-3 py-2 text-xs text-indigo-800"
                            >
                                <span class="font-semibold">
                                    Relative:
                                </span>

                                <span x-text="relativeOutput"></span>
                            </div>
                        </div>
                    </template>

                    <template x-if="hasResult && mode === 'bulk'">
                        <div class="overflow-auto rounded-xl border border-slate-200">
                            <table class="min-w-full text-left text-[11px]">
                                <thead class="bg-slate-50 text-slate-500">
                                    <tr>
                                        <th class="px-3 py-2">
                                            Input
                                        </th>

                                        <th class="px-3 py-2">
                                            Unit
                                        </th>

                                        <th class="px-3 py-2">
                                            Date / time
                                        </th>

                                        <th class="px-3 py-2">
                                            Status
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    <template
                                        x-for="(row,index) in bulkResults"
                                        :key="index"
                                    >
                                        <tr>
                                            <td
                                                class="px-3 py-2 font-mono"
                                                x-text="row.input"
                                            ></td>

                                            <td
                                                class="px-3 py-2"
                                                x-text="row.unit"
                                            ></td>

                                            <td
                                                class="px-3 py-2"
                                                x-text="row.date"
                                            ></td>

                                            <td
                                                class="px-3 py-2"
                                                :class="row.ok ? 'text-emerald-600' : 'text-red-600'"
                                                x-text="row.ok ? 'Valid' : row.error"
                                            ></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    <template x-if="!hasResult">
                        <div class="flex min-h-[300px] items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50/60 px-5 text-center">
                            <div>
                                <div class="text-sm font-medium text-slate-500">
                                    No conversion yet
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Enter a value or choose an example.
                                </div>
                            </div>
                        </div>
                    </template>

                    <div
                        x-show="error"
                        class="mt-3 rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-xs font-medium text-red-700"
                        x-text="error"
                    ></div>
                </div>

                <template x-if="mode !== 'bulk' && hasResult">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <h2 class="text-xs font-semibold text-slate-700">
                                Developer output
                            </h2>

                            <button
                                type="button"
                                @click="copyText(codeSnippet, $event)"
                                class="result-action"
                            >
                                Copy code
                            </button>
                        </div>

                        <select
                            x-model="codeLanguage"
                            @change="refreshOutput()"
                            class="calc-input mb-2"
                        >
                            <option value="javascript">
                                JavaScript
                            </option>

                            <option value="php">
                                PHP
                            </option>

                            <option value="python">
                                Python
                            </option>

                            <option value="java">
                                Java
                            </option>

                            <option value="go">
                                Go
                            </option>

                            <option value="csharp">
                                C#
                            </option>

                            <option value="sql">
                                SQL
                            </option>
                        </select>

                        <pre
                            class="max-h-36 overflow-auto rounded-lg bg-slate-900 p-3 font-mono text-[11px] leading-5 text-slate-100"
                            x-text="codeSnippet"
                        ></pre>
                    </div>
                </template>

                <template x-if="zoneCompareEnabled && hasResult">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-2 flex items-center justify-between">
                            <h2 class="text-xs font-semibold text-slate-700">
                                Timezone comparison
                            </h2>

                            <button
                                type="button"
                                @click="copyText(zoneComparisonText, $event)"
                                class="result-action"
                            >
                                Copy
                            </button>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-2">
                            <template
                                x-for="zone in comparisonZones"
                                :key="zone"
                            >
                                <div class="stat-card">
                                    <span
                                        class="stat-label"
                                        x-text="zone"
                                    ></span>

                                    <span
                                        class="stat-value whitespace-normal"
                                        x-text="formatDateInZone(resultDate, zone)"
                                    ></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'duration' && durationResult">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <h2 class="mb-2 text-xs font-semibold text-slate-700">
                            Duration
                        </h2>

                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <div class="stat-card">
                                <span class="stat-label">
                                    Days
                                </span>

                                <span
                                    class="stat-value"
                                    x-text="durationResult.days"
                                ></span>
                            </div>

                            <div class="stat-card">
                                <span class="stat-label">
                                    Hours
                                </span>

                                <span
                                    class="stat-value"
                                    x-text="durationResult.hours"
                                ></span>
                            </div>

                            <div class="stat-card">
                                <span class="stat-label">
                                    Minutes
                                </span>

                                <span
                                    class="stat-value"
                                    x-text="durationResult.minutes"
                                ></span>
                            </div>

                            <div class="stat-card">
                                <span class="stat-label">
                                    Seconds
                                </span>

                                <span
                                    class="stat-value"
                                    x-text="durationResult.seconds"
                                ></span>
                            </div>
                        </div>

                        <p
                            class="mt-2 text-xs text-slate-500"
                            x-text="durationResult.human"
                        ></p>
                    </div>
                </template>

                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-2 flex items-center justify-between">
                        <h2 class="text-xs font-semibold text-slate-700">
                            Recent conversions
                        </h2>

                        <button
                            type="button"
                            @click="clearHistory()"
                            class="text-[11px] font-medium text-slate-500 hover:text-red-500"
                        >
                            Clear history
                        </button>
                    </div>

                    <template x-if="history.length">
                        <div class="space-y-1.5">
                            <template
                                x-for="item in history"
                                :key="item.id"
                            >
                                <button
                                    type="button"
                                    @click="restoreHistory(item)"
                                    class="flex w-full items-center justify-between gap-2 rounded-md border border-slate-200 bg-white px-2.5 py-2 text-left text-[11px] hover:border-indigo-200 hover:bg-indigo-50"
                                >
                                    <span
                                        class="truncate text-slate-600"
                                        x-text="item.summary"
                                    ></span>

                                    <span
                                        class="shrink-0 text-slate-500"
                                        x-text="item.mode"
                                    ></span>
                                </button>
                            </template>
                        </div>
                    </template>

                    <template x-if="!history.length">
                        <p class="text-[11px] text-slate-500">
                            Your recent local conversions will appear here.
                        </p>
                    </template>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 px-4 py-3 sm:px-5">
            <div class="grid gap-2 sm:grid-cols-3">
                <button
                    type="button"
                    @click="useExample('unix')"
                    class="compact-action"
                >
                    Unix example
                </button>

                <button
                    type="button"
                    @click="shareState()"
                    class="compact-action"
                >
                    Share configuration
                </button>

                <button
                    type="button"
                    @click="verifyRoundTrip()"
                    :disabled="!hasResult || mode === 'duration' || mode === 'bulk'"
                    class="compact-action"
                >
                    Verify round trip
                </button>
            </div>

            <p
                x-show="shareMessage"
                x-text="shareMessage"
                class="mt-2 text-center text-[11px] font-medium text-emerald-600"
            ></p>
        </div>
    </section>

    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2.5">
        <p class="text-[11px] leading-5 text-emerald-800">
            Runs entirely in your browser. Timestamp conversion, timezone formatting,
            bulk processing, and history stay local and are not sent to AabiTech.
        </p>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

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

    .field-label {
        display: block;
        font-size: 11px;
        line-height: 1;
        font-weight: 600;
        color: rgb(71 85 105);
    }

    .calc-input,
    .calc-textarea {
        width: 100%;
        border: 1px solid rgb(203 213 225);
        border-radius: 9px;
        background: white;
        color: rgb(15 23 42);
        outline: none;
        transition:
            border-color 150ms ease,
            box-shadow 150ms ease;
    }

    .calc-input {
        height: 42px;
        padding: 0 12px;
        font-size: 13px;
        font-weight: 500;
    }

    .calc-textarea {
        padding: 10px 12px;
        font: 500 12px/1.6 ui-monospace, SFMono-Regular, Menlo, monospace;
        resize: vertical;
    }

    .calc-input:hover,
    .calc-textarea:hover {
        border-color: rgb(148 163 184);
    }

    .calc-input:focus,
    .calc-textarea:focus {
        border-color: rgb(99 102 241);
        box-shadow: 0 0 0 3px rgb(224 231 255);
    }

    .compact-tab,
    .compact-action,
    .result-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 6px;
        white-space: nowrap;
        transition:
            background-color 150ms ease,
            border-color 150ms ease,
            color 150ms ease;
        cursor: pointer;
    }

    .compact-tab {
        height: 30px;
        border: 1px solid transparent;
        background: transparent;
        padding: 0 9px;
        font-size: 11px;
        font-weight: 500;
        color: rgb(71 85 105);
    }

    .compact-tab:hover {
        border-color: rgb(203 213 225);
        background: white;
        color: rgb(67 56 202);
    }

    .compact-action {
        height: 32px;
        border: 1px solid rgb(203 213 225);
        background: white;
        padding: 0 11px;
        font-size: 11px;
        font-weight: 600;
        color: rgb(71 85 105);
    }

    .compact-action:hover,
    .result-action:hover {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    .result-action {
        height: 30px;
        border: 1px solid rgb(226 232 240);
        background: white;
        padding: 0 9px;
        font-size: 11px;
        font-weight: 600;
        color: rgb(71 85 105);
    }

    .result-action:disabled,
    .compact-action:disabled {
        cursor: not-allowed;
        opacity: .4;
    }

    [data-active-group].is-active {
        border-color: rgb(129 140 248) !important;
        background: rgb(238 242 254) !important;
        color: rgb(67 56 202) !important;
        box-shadow: none !important;
    }

    .result-box {
        border: 1px solid rgb(199 210 254);
        border-radius: 10px;
        background: rgb(238 242 255);
        padding: 12px;
    }

    .result-label,
    .stat-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: rgb(100 116 139);
    }

    .result-main {
        display: block;
        margin-top: 4px;
        overflow-wrap: anywhere;
        font-size: 14px;
        font-weight: 650;
        line-height: 1.55;
        color: rgb(30 41 59);
    }

    .stat-card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        border: 1px solid rgb(226 232 240);
        border-radius: 8px;
        background: white;
        padding: 8px 10px;
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

    .live-value {
        display: block;
        border: 1px solid rgb(226 232 240);
        border-radius: 9px;
        background: rgb(248 250 252);
        padding: 10px 12px;
        transition:
            border-color 150ms ease,
            background-color 150ms ease;
    }

    .live-value:hover {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
    }

    @media (max-width: 640px) {
        .calc-input {
            height: 40px;
            font-size: 13px;
        }
    }
</style>

@script
<script>
window.aabiUnixTimestampConverter = function () {
    return {
        mode: 'timestamp',

        timestampInput: '',
        timestampUnit: 'auto',
        detectedUnit: '',

        dateInput: '',
        selectedTimezone: 'local',
        formatPreset: 'standard',
        customFormat: 'YYYY-MM-DD HH:mm:ss',
        hour12: false,

        durationStart: '',
        durationEnd: '',
        durationResult: null,

        bulkInput: '',
        bulkResults: [],

        resultDate: null,
        unixOutput: '',
        isoOutput: '',
        utcOutput: '',
        rfcOutput: '',
        timezoneOutput: '',
        humanReadable: '',
        calendarDetails: '',
        relativeOutput: '',
        resultMeta: '',
        error: '',

        primaryCopyValue: '',
        hasResult: false,

        codeLanguage: 'javascript',
        codeSnippet: '',

        currentTimestampSeconds: 0,
        currentTimestampMilliseconds: 0,
        clockTimer: null,

        history: [],
        showAdvanced: false,

        relativeEnabled: true,
        zoneCompareEnabled: false,

        epochMode: 'unix',
        customEpoch: '1970-01-01T00:00:00Z',

        shareMessage: '',

        localTimezone: 'UTC',
        timezoneOptions: [],

        comparisonZones: [
            'UTC',
            'Asia/Karachi',
            'America/New_York',
            'Europe/London',
            'Asia/Tokyo'
        ],

        init() {
            this.localTimezone =
                Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';

            this.selectedTimezone = this.localTimezone;

            this.timezoneOptions = this.getTimezones();

            this.restoreSettings();

            this.updateClock();

            this.clockTimer = setInterval(() => {
                this.updateClock();
            }, 250);

            this.restoreHistoryFromStorage();

            this.loadFromHash();

            this.useExample('unix');
        },

        destroy() {
            if (this.clockTimer) {
                clearInterval(this.clockTimer);
            }
        },

        getTimezones() {
            try {
                if (typeof Intl.supportedValuesOf === 'function') {
                    return Intl
                        .supportedValuesOf('timeZone')
                        .filter(zone => zone !== 'UTC');
                }
            } catch (_) {}

            return [
                'Africa/Cairo',
                'America/Chicago',
                'America/Denver',
                'America/Los_Angeles',
                'America/New_York',
                'Asia/Dubai',
                'Asia/Karachi',
                'Asia/Kolkata',
                'Asia/Tokyo',
                'Australia/Sydney',
                'Europe/Berlin',
                'Europe/London',
                'Pacific/Auckland'
            ];
        },

        updateClock() {
            const now = Date.now();

            this.currentTimestampMilliseconds = now;
            this.currentTimestampSeconds = Math.floor(now / 1000);

            if (this.mode === 'timestamp' && !this.timestampInput) {
                this.refreshOutput();
            }
        },

        setMode(value) {
            this.mode = value;
            this.error = '';
            this.shareMessage = '';

            if (
                value === 'timestamp' &&
                !this.timestampInput
            ) {
                this.useExample('unix');
            }

            if (
                value === 'date' &&
                !this.dateInput
            ) {
                this.useExample('date');
            }

            if (
                value === 'duration' &&
                (!this.durationStart || !this.durationEnd)
            ) {
                this.useExample('duration');
            }

            if (
                value === 'bulk' &&
                !this.bulkInput
            ) {
                this.useExample('bulk');
            }

            this.saveSettings();
        },

        epochMilliseconds() {
            if (this.epochMode !== 'custom') {
                return 0;
            }

            const epoch = Date.parse(this.customEpoch);

            if (!Number.isFinite(epoch)) {
                throw new Error(
                    'Custom epoch must be a valid ISO date/time.'
                );
            }

            return epoch;
        },

        timestampFromMilliseconds(milliseconds, unit) {
            const relative =
                milliseconds - this.epochMilliseconds();

            if (unit === 's') {
                return relative / 1000;
            }

            if (unit === 'ms') {
                return relative;
            }

            if (unit === 'us') {
                return relative * 1000;
            }

            return relative * 1000000;
        },

        parseTimestamp(raw, forcedUnit) {
            const text = String(raw ?? '').trim();

            if (
                !text ||
                !/^[+-]?\d+(?:\.\d+)?$/.test(text)
            ) {
                throw new Error(
                    'Enter a valid numeric Unix timestamp.'
                );
            }

            let unit = forcedUnit || 'auto';

            const absDigits = text
                .replace(/^[+-]/, '')
                .split('.')[0]
                .length;

            if (unit === 'auto') {
                if (absDigits <= 10) {
                    unit = 's';
                } else if (absDigits <= 13) {
                    unit = 'ms';
                } else if (absDigits <= 16) {
                    unit = 'us';
                } else {
                    unit = 'ns';
                }
            }

            const factors = {
                s: 1e3,
                ms: 1,
                us: 1e-3,
                ns: 1e-6
            };

            const milliseconds =
                Number(text) * factors[unit] +
                this.epochMilliseconds();

            if (
                !Number.isFinite(milliseconds) ||
                Math.abs(milliseconds) > 8640000000000000
            ) {
                throw new Error(
                    'Timestamp is outside the supported JavaScript Date range.'
                );
            }

            const date = new Date(milliseconds);

            if (Number.isNaN(date.getTime())) {
                throw new Error('Invalid timestamp.');
            }

            return {
                date,
                unit,
                milliseconds
            };
        },

        convertTimestamp() {
            this.error = '';

            if (!String(this.timestampInput).trim()) {
                this.clearConversion();
                return;
            }

            try {
                const parsed = this.parseTimestamp(
                    this.timestampInput,
                    this.timestampUnit
                );

                this.detectedUnit = parsed.unit;
                this.resultDate = parsed.date;

                this.buildOutputs(
                    parsed.date,
                    parsed.milliseconds
                );

                this.resultMeta =
                    'Timestamp → date/time · ' +
                    this.detectedUnitLabel +
                    (
                        this.epochMode === 'custom'
                            ? ' · custom epoch'
                            : ''
                    );

                this.hasResult = true;
                this.primaryCopyValue = this.humanReadable;

                this.addHistory();
            } catch (e) {
                this.clearConversion(false);
                this.error = e.message;
            }
        },

        get detectedUnitLabel() {
            return {
                s: 'seconds',
                ms: 'milliseconds',
                us: 'microseconds',
                ns: 'nanoseconds'
            }[this.detectedUnit] || '';
        },

        convertDate() {
            this.error = '';

            if (!this.dateInput) {
                this.clearConversion();
                return;
            }

            try {
                const ms = this.parseDateTimeInZone(
                    this.dateInput,
                    this.selectedTimezone
                );

                const date = new Date(ms);

                if (Number.isNaN(date.getTime())) {
                    throw new Error('Invalid date/time.');
                }

                this.resultDate = date;

                this.buildOutputs(date, ms);

                this.resultMeta =
                    'Date/time → Unix timestamp · ' +
                    this.zoneLabel(this.selectedTimezone) +
                    (
                        this.epochMode === 'custom'
                            ? ' · custom epoch'
                            : ''
                    );

                this.hasResult = true;
                this.primaryCopyValue = this.unixOutput;

                this.addHistory();
            } catch (e) {
                this.clearConversion(false);
                this.error = e.message;
            }
        },

        parseDateTimeInZone(value, zone) {
            const match = String(value).match(
                /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(?::(\d{2})(?:\.(\d{1,3}))?)?$/
            );

            if (!match) {
                throw new Error(
                    'Enter a valid date and time.'
                );
            }

            const year = Number(match[1]);
            const month = Number(match[2]);
            const day = Number(match[3]);
            const hour = Number(match[4]);
            const minute = Number(match[5]);
            const second = Number(match[6] || 0);
            const millisecond = Number(
                (match[7] || '').padEnd(3, '0') || 0
            );

            if (
                month < 1 ||
                month > 12 ||
                day < 1 ||
                day > 31 ||
                hour > 23 ||
                minute > 59 ||
                second > 59
            ) {
                throw new Error(
                    'Invalid date/time value.'
                );
            }

            const assumed = Date.UTC(
                year,
                month - 1,
                day,
                hour,
                minute,
                second,
                millisecond
            );

            if (zone === 'UTC') {
                return assumed;
            }

            if (zone === 'local') {
                const local = new Date(
                    year,
                    month - 1,
                    day,
                    hour,
                    minute,
                    second,
                    millisecond
                );

                if (Number.isNaN(local.getTime())) {
                    throw new Error(
                        'Invalid local date/time.'
                    );
                }

                return local.getTime();
            }

            let candidate = assumed;

            for (let index = 0; index < 4; index++) {
                const parts = this.getZonedParts(
                    new Date(candidate),
                    zone
                );

                const asUtc = Date.UTC(
                    parts.year,
                    parts.month - 1,
                    parts.day,
                    parts.hour,
                    parts.minute,
                    parts.second,
                    parts.millisecond
                );

                candidate += assumed - asUtc;
            }

            return candidate;
        },

        getZonedParts(date, zone) {
            const formatter = new Intl.DateTimeFormat(
                'en-US',
                {
                    timeZone: zone,
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hourCycle: 'h23',
                    fractionalSecondDigits: 3
                }
            );

            const parts = Object.fromEntries(
                formatter
                    .formatToParts(date)
                    .filter(part => part.type !== 'literal')
                    .map(part => [
                        part.type,
                        part.value
                    ])
            );

            return {
                year: Number(parts.year),
                month: Number(parts.month),
                day: Number(parts.day),
                hour: Number(parts.hour),
                minute: Number(parts.minute),
                second: Number(parts.second),
                millisecond: Number(
                    parts.fractionalSecond || 0
                )
            };
        },

        buildOutputs(date, milliseconds) {
            const ms = Math.trunc(milliseconds);

            const iso = date.toISOString();

            const seconds = Math.floor(
                this.timestampFromMilliseconds(ms, 's')
            );

            const millis = Math.floor(
                this.timestampFromMilliseconds(ms, 'ms')
            );

            this.unixOutput =
                String(seconds) +
                ' s  ·  ' +
                String(millis) +
                ' ms';

            this.isoOutput = iso;

            this.utcOutput =
                this.formatDate(date, 'UTC');

            const zone =
                this.selectedTimezone === 'local'
                    ? this.localTimezone
                    : this.selectedTimezone;

            this.timezoneOutput =
                this.formatDate(date, zone);

            this.rfcOutput =
                date.toUTCString();

            this.humanReadable =
                this.formatDate(date, zone);

            this.calendarDetails =
                this.dayName(date, zone) +
                ' · Day ' +
                this.dayOfYear(date, zone) +
                ' · Week ' +
                this.isoWeek(date, zone) +
                ' · ' +
                this.zoneOffset(date, zone);

            this.relativeOutput =
                this.relativeTime(
                    date.getTime(),
                    Date.now()
                );

            this.codeSnippet =
                this.makeCodeSnippet(
                    ms,
                    seconds
                );
        },

        formatDate(date, zone) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: this.hour12,
                timeZone: zone,
                timeZoneName: 'short'
            };

            if (this.formatPreset === 'iso') {
                return date.toISOString();
            }

            if (this.formatPreset === 'rfc') {
                return date.toUTCString();
            }

            if (this.formatPreset === 'utc') {
                return new Intl.DateTimeFormat(
                    'en-GB',
                    {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false,
                        timeZone: 'UTC',
                        timeZoneName: 'short'
                    }
                ).format(date);
            }

            if (this.formatPreset === 'custom') {
                return this.customFormatDate(
                    date,
                    zone
                );
            }

            return new Intl.DateTimeFormat(
                'en-US',
                options
            ).format(date);
        },

        customFormatDate(date, zone) {
            const parts = this.getZonedParts(
                date,
                zone
            );

            const pad = value =>
                String(value).padStart(2, '0');

            const milliseconds =
                String(parts.millisecond)
                    .padStart(3, '0');

            const hour = parts.hour;

            const twelveHour =
                hour % 12 || 12;

            const period =
                hour < 12 ? 'AM' : 'PM';

            return this.customFormat
                .replaceAll(
                    'YYYY',
                    String(parts.year)
                )
                .replaceAll(
                    'MM',
                    pad(parts.month)
                )
                .replaceAll(
                    'DD',
                    pad(parts.day)
                )
                .replaceAll(
                    'HH',
                    pad(hour)
                )
                .replaceAll(
                    'hh',
                    pad(twelveHour)
                )
                .replaceAll(
                    'mm',
                    pad(parts.minute)
                )
                .replaceAll(
                    'ss',
                    pad(parts.second)
                )
                .replaceAll(
                    'SSS',
                    milliseconds
                )
                .replaceAll(
                    'A',
                    period
                );
        },

        zoneOffset(date, zone) {
            const text =
                new Intl.DateTimeFormat(
                    'en-US',
                    {
                        timeZone: zone,
                        timeZoneName: 'longOffset',
                        hour: '2-digit'
                    }
                ).format(date);

            const match =
                text.match(/GMT([+-]\d{2}:?\d{2})?/);

            if (!match) {
                return text;
            }

            return match[1]
                ? 'UTC' + match[1]
                : 'UTC';
        },

        zoneLabel(zone) {
            return zone === 'local'
                ? this.localTimezone
                : zone;
        },

        dayName(date, zone) {
            return new Intl.DateTimeFormat(
                'en-US',
                {
                    weekday: 'long',
                    timeZone: zone
                }
            ).format(date);
        },

        dayOfYear(date, zone) {
            const parts =
                this.getZonedParts(date, zone);

            const start =
                Date.UTC(parts.year, 0, 1);

            const current =
                Date.UTC(
                    parts.year,
                    parts.month - 1,
                    parts.day
                );

            return Math.floor(
                (current - start) / 86400000
            ) + 1;
        },

        isoWeek(date, zone) {
            const parts =
                this.getZonedParts(date, zone);

            const utc =
                Date.UTC(
                    parts.year,
                    parts.month - 1,
                    parts.day
                );

            const day =
                (new Date(utc).getUTCDay() + 6) % 7;

            const thursday =
                utc + (3 - day) * 86400000;

            const januaryFourth =
                Date.UTC(
                    new Date(thursday)
                        .getUTCFullYear(),
                    0,
                    4
                );

            return 1 + Math.round(
                (thursday - januaryFourth) /
                604800000
            );
        },

        relativeTime(target, now) {
            const difference = target - now;
            const absolute = Math.abs(difference);

            const units = [
                [31536000000, 'year'],
                [2592000000, 'month'],
                [604800000, 'week'],
                [86400000, 'day'],
                [3600000, 'hour'],
                [60000, 'minute'],
                [1000, 'second']
            ];

            for (const [milliseconds, name] of units) {
                if (absolute >= milliseconds) {
                    const amount =
                        Math.round(
                            absolute / milliseconds
                        );

                    const plural =
                        amount === 1 ? '' : 's';

                    if (difference < 0) {
                        return (
                            amount +
                            ' ' +
                            name +
                            plural +
                            ' ago'
                        );
                    }

                    return (
                        'in ' +
                        amount +
                        ' ' +
                        name +
                        plural
                    );
                }
            }

            return 'just now';
        },

        refreshOutput() {
            if (this.mode === 'timestamp') {
                this.convertTimestamp();
            } else if (this.mode === 'date') {
                this.convertDate();
            } else if (this.mode === 'duration') {
                this.calculateDuration();
            } else {
                this.convertBulk();
            }
        },

        clearConversion(clearError = true) {
            this.resultDate = null;
            this.hasResult = false;

            this.unixOutput = '';
            this.isoOutput = '';
            this.utcOutput = '';
            this.rfcOutput = '';
            this.timezoneOutput = '';
            this.humanReadable = '';
            this.calendarDetails = '';
            this.relativeOutput = '';
            this.codeSnippet = '';
            this.primaryCopyValue = '';

            if (clearError) {
                this.error = '';
            }
        },

        calculateDuration() {
            this.error = '';
            this.durationResult = null;

            if (
                !this.durationStart &&
                !this.durationEnd
            ) {
                return;
            }

            try {
                const start =
                    this.parseTimestamp(
                        this.durationStart,
                        'auto'
                    ).milliseconds;

                const end =
                    this.parseTimestamp(
                        this.durationEnd,
                        'auto'
                    ).milliseconds;

                const difference = end - start;

                const sign =
                    difference < 0 ? '-' : '';

                let remaining =
                    Math.abs(difference);

                const days =
                    Math.floor(
                        remaining / 86400000
                    );

                remaining %= 86400000;

                const hours =
                    Math.floor(
                        remaining / 3600000
                    );

                remaining %= 3600000;

                const minutes =
                    Math.floor(
                        remaining / 60000
                    );

                remaining %= 60000;

                const seconds =
                    Math.floor(
                        remaining / 1000
                    );

                this.durationResult = {
                    days,
                    hours,
                    minutes,
                    seconds,
                    human:
                        sign +
                        days +
                        ' days, ' +
                        hours +
                        ' hours, ' +
                        minutes +
                        ' minutes, ' +
                        seconds +
                        ' seconds',
                    milliseconds: difference
                };

                this.hasResult = true;

                this.resultMeta =
                    'Timestamp difference';

                this.primaryCopyValue =
                    this.durationResult.human;

                this.codeSnippet =
                    'Duration = endTimestamp - startTimestamp';

                this.addHistory();
            } catch (e) {
                this.hasResult = false;
                this.error = e.message;
            }
        },

        convertBulk() {
            this.error = '';
            this.bulkResults = [];

            const lines =
                String(this.bulkInput || '')
                    .split(/[\r\n,]+/)
                    .map(value => value.trim())
                    .filter(Boolean);

            if (!lines.length) {
                this.hasResult = false;
                return;
            }

            this.bulkResults =
                lines
                    .slice(0, 1000)
                    .map(input => {
                        try {
                            const parsed =
                                this.parseTimestamp(
                                    input,
                                    'auto'
                                );

                            return {
                                input,
                                unit: parsed.unit,
                                date: this.formatDate(
                                    parsed.date,
                                    this.localTimezone
                                ),
                                ok: true,
                                error: ''
                            };
                        } catch (e) {
                            return {
                                input,
                                unit: '—',
                                date: '—',
                                ok: false,
                                error: e.message
                            };
                        }
                    });

            this.hasResult =
                this.bulkResults.length > 0;

            this.resultMeta =
                this.bulkResults.length +
                ' timestamp' +
                (
                    this.bulkResults.length === 1
                        ? ''
                        : 's'
                ) +
                ' processed';

            this.primaryCopyValue =
                this.bulkResults
                    .map(row =>
                        row.input +
                        '\t' +
                        row.date
                    )
                    .join('\n');

            this.addHistory();
        },

        importBulk(event) {
            const file =
                event.target.files &&
                event.target.files[0];

            if (!file) {
                return;
            }

            const reader =
                new FileReader();

            reader.onload = () => {
                this.bulkInput =
                    String(reader.result || '');

                this.convertBulk();
            };

            reader.readAsText(file);

            event.target.value = '';
        },

        formatDateInZone(date, zone) {
            return (
                this.formatDate(date, zone) +
                ' · ' +
                this.zoneOffset(date, zone)
            );
        },

        get zoneComparisonText() {
            if (!this.resultDate) {
                return '';
            }

            return this.comparisonZones
                .map(zone =>
                    zone +
                    ': ' +
                    this.formatDateInZone(
                        this.resultDate,
                        zone
                    )
                )
                .join('\n');
        },

        makeCodeSnippet(milliseconds, seconds) {
            const value = seconds;

            switch (this.codeLanguage) {
                case 'php':
                    return (
                        '$date = new DateTimeImmutable(\'@' +
                        value +
                        '\');\n' +
                        '$date = $date->setTimezone(new DateTimeZone(\'' +
                        this.zoneLabel(
                            this.selectedTimezone
                        ) +
                        '\'));'
                    );

                case 'python':
                    return (
                        'from datetime import datetime, timezone\n' +
                        'dt = datetime.fromtimestamp(' +
                        value +
                        ', tz=timezone.utc)'
                    );

                case 'java':
                    return (
                        'Instant instant = Instant.ofEpochSecond(' +
                        value +
                        'L);'
                    );

                case 'go':
                    return (
                        't := time.Unix(' +
                        value +
                        ', 0).UTC()'
                    );

                case 'csharp':
                    return (
                        'DateTimeOffset.FromUnixTimeSeconds(' +
                        value +
                        ').UtcDateTime;'
                    );

                case 'sql':
                    return (
                        'FROM_UNIXTIME(' +
                        value +
                        ')'
                    );

                default:
                    return (
                        'const date = new Date(' +
                        milliseconds +
                        ');\n' +
                        'console.log(date.toISOString());'
                    );
            }
        },

        verifyRoundTrip() {
            if (!this.resultDate) {
                return;
            }

            const original =
                this.resultDate.getTime();

            const round =
                Math.floor(original / 1000) * 1000;

            this.shareMessage =
                Math.abs(original - round) < 1000
                    ? 'Round-trip verified within second precision.'
                    : 'Round-trip differs because the Unix seconds representation drops sub-second precision.';

            setTimeout(() => {
                this.shareMessage = '';
            }, 4000);
        },

        useExample(kind) {
            if (kind === 'unix') {
                this.mode = 'timestamp';
                this.timestampUnit = 'auto';
                this.timestampInput = '1750000000';
                this.convertTimestamp();
            }

            if (kind === 'date') {
                this.mode = 'date';
                this.dateInput =
                    '2025-06-15T12:00:00';
                this.selectedTimezone =
                    this.localTimezone;
                this.convertDate();
            }

            if (kind === 'duration') {
                this.mode = 'duration';
                this.durationStart =
                    '1750000000';
                this.durationEnd =
                    '1750086400';
                this.calculateDuration();
            }

            if (kind === 'bulk') {
                this.mode = 'bulk';

                this.bulkInput =
                    '0\n' +
                    '946684800\n' +
                    '1750000000\n' +
                    '1750000000000\n' +
                    'invalid';

                this.convertBulk();
            }
        },

        copyText(value, event) {
            if (!value) {
                return;
            }

            const button =
                event &&
                event.currentTarget;

            const original =
                button
                    ? button.textContent
                    : '';

            const done = () => {
                if (button) {
                    button.textContent =
                        '✓ Copied';

                    setTimeout(() => {
                        button.textContent =
                            original;
                    }, 1400);
                }

                this.shareMessage =
                    'Copied to clipboard';

                setTimeout(() => {
                    this.shareMessage = '';
                }, 1800);
            };

            if (
                navigator.clipboard &&
                window.isSecureContext
            ) {
                navigator.clipboard
                    .writeText(String(value))
                    .then(done)
                    .catch(() =>
                        this.copyFallback(
                            value,
                            done
                        )
                    );
            } else {
                this.copyFallback(
                    value,
                    done
                );
            }
        },

        copyFallback(value, done) {
            const textarea =
                document.createElement('textarea');

            textarea.value =
                String(value);

            textarea.style.position =
                'fixed';

            textarea.style.opacity = '0';

            document.body.appendChild(
                textarea
            );

            textarea.select();

            try {
                document.execCommand('copy');
                done();
            } finally {
                textarea.remove();
            }
        },

        downloadResult() {
            if (!this.hasResult) {
                return;
            }

            let content;

            if (this.mode === 'bulk') {
                content = [
                    'Input,Unit,Date,Status',
                    ...this.bulkResults.map(row =>
                        [
                            row.input,
                            row.unit,
                            '"' +
                            String(row.date)
                                .replaceAll(
                                    '"',
                                    '""'
                                ) +
                            '"',
                            row.ok
                                ? 'Valid'
                                : row.error
                        ].join(',')
                    )
                ].join('\n');
            } else {
                content =
                    this.primaryCopyValue;
            }

            const type =
                this.mode === 'bulk'
                    ? 'text/csv'
                    : 'text/plain';

            const extension =
                this.mode === 'bulk'
                    ? 'csv'
                    : 'txt';

            const blob =
                new Blob(
                    [content],
                    {
                        type:
                            type +
                            ';charset=utf-8'
                    }
                );

            const url =
                URL.createObjectURL(blob);

            const link =
                document.createElement('a');

            link.href = url;

            link.download =
                'aabitech-unix-timestamp.' +
                extension;

            document.body.appendChild(link);

            link.click();

            link.remove();

            URL.revokeObjectURL(url);
        },

        shareState() {
            const state = {
                mode: this.mode,
                timestampInput:
                    this.timestampInput,
                timestampUnit:
                    this.timestampUnit,
                dateInput:
                    this.dateInput,
                selectedTimezone:
                    this.selectedTimezone,
                formatPreset:
                    this.formatPreset,
                hour12:
                    this.hour12,
                durationStart:
                    this.durationStart,
                durationEnd:
                    this.durationEnd
            };

            try {
                location.hash =
                    'timestamp=' +
                    btoa(
                        unescape(
                            encodeURIComponent(
                                JSON.stringify(state)
                            )
                        )
                    );

                history.replaceState(
                    null,
                    '',
                    location.href
                );

                this.shareMessage =
                    'Shareable configuration created. Generated data is not included.';

                setTimeout(() => {
                    this.shareMessage = '';
                }, 3500);
            } catch (e) {
                this.shareMessage =
                    'Unable to create shareable state.';
            }
        },

        loadFromHash() {
            if (
                !location.hash.startsWith(
                    '#timestamp='
                )
            ) {
                return;
            }

            try {
                const raw =
                    decodeURIComponent(
                        escape(
                            atob(
                                location.hash.slice(
                                    11
                                )
                            )
                        )
                    );

                const state =
                    JSON.parse(raw);

                Object.assign(
                    this,
                    state
                );

                this.refreshOutput();
            } catch (_) {}
        },

        addHistory() {
            if (!this.hasResult) {
                return;
            }

            const item = {
                id:
                    (
                        typeof crypto !== 'undefined' &&
                        crypto.randomUUID
                    )
                        ? crypto.randomUUID()
                        : String(Date.now()),

                mode: this.mode,

                summary:
                    this.resultMeta ||
                    this.humanReadable.slice(
                        0,
                        70
                    ),

                state: {
                    mode: this.mode,
                    timestampInput:
                        this.timestampInput,
                    timestampUnit:
                        this.timestampUnit,
                    dateInput:
                        this.dateInput,
                    selectedTimezone:
                        this.selectedTimezone,
                    formatPreset:
                        this.formatPreset,
                    hour12:
                        this.hour12,
                    durationStart:
                        this.durationStart,
                    durationEnd:
                        this.durationEnd,
                    bulkInput:
                        this.bulkInput
                },

                timestamp: Date.now()
            };

            this.history = [
                item,
                ...this.history.filter(
                    existing =>
                        JSON.stringify(
                            existing.state
                        ) !==
                        JSON.stringify(
                            item.state
                        )
                )
            ].slice(0, 10);

            try {
                localStorage.setItem(
                    'aabitech_unix_timestamp_history',
                    JSON.stringify(
                        this.history
                    )
                );
            } catch (_) {}
        },

        restoreHistory(item) {
            Object.assign(
                this,
                item.state
            );

            this.refreshOutput();
        },

        clearHistory() {
            this.history = [];

            try {
                localStorage.removeItem(
                    'aabitech_unix_timestamp_history'
                );
            } catch (_) {}
        },

        restoreHistoryFromStorage() {
            try {
                const saved =
                    localStorage.getItem(
                        'aabitech_unix_timestamp_history'
                    );

                this.history =
                    saved
                        ? JSON.parse(saved)
                        : [];
            } catch (_) {
                this.history = [];
            }
        },

        saveSettings() {
            try {
                localStorage.setItem(
                    'aabitech_unix_timestamp_settings',
                    JSON.stringify({
                        selectedTimezone:
                            this.selectedTimezone,
                        formatPreset:
                            this.formatPreset,
                        customFormat:
                            this.customFormat,
                        hour12:
                            this.hour12,
                        relativeEnabled:
                            this.relativeEnabled,
                        zoneCompareEnabled:
                            this.zoneCompareEnabled,
                        epochMode:
                            this.epochMode,
                        customEpoch:
                            this.customEpoch
                    })
                );
            } catch (_) {}
        },

        restoreSettings() {
            try {
                const saved =
                    localStorage.getItem(
                        'aabitech_unix_timestamp_settings'
                    );

                if (saved) {
                    Object.assign(
                        this,
                        JSON.parse(saved)
                    );
                }
            } catch (_) {}
        },

        reset() {
            this.mode = 'timestamp';
            this.timestampInput = '';
            this.timestampUnit = 'auto';
            this.dateInput = '';
            this.selectedTimezone =
                this.localTimezone;
            this.formatPreset = 'standard';
            this.hour12 = false;

            this.epochMode = 'unix';
            this.customEpoch =
                '1970-01-01T00:00:00Z';

            this.durationStart = '';
            this.durationEnd = '';

            this.bulkInput = '';
            this.bulkResults = [];

            this.durationResult = null;

            this.error = '';

            this.clearConversion(false);

            this.shareMessage = '';

            this.saveSettings();
        },

        handleShortcut(event) {
            if (
                (event.ctrlKey || event.metaKey) &&
                event.key === 'Enter'
            ) {
                event.preventDefault();
                this.refreshOutput();
            }

            if (event.key === 'Escape') {
                this.error = '';
                this.shareMessage = '';
            }
        }
    };
};
</script>
@endscript