<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only tool. No server-side conversion state is required.
};
?>

<div
    x-data="aabiTimestampConverter()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    <style>
        [x-cloak] { display: none !important; }
        button, [role="button"], a, summary, [data-clickable] { cursor: pointer; }
        button:disabled, [aria-disabled="true"] { cursor: not-allowed; }
        [data-active-group].is-active {
            border-color: rgb(129 140 248) !important;
            background: rgb(238 242 254) !important;
            color: rgb(67 56 202) !important;
            box-shadow: none !important;
        }
        .compact-tab { display:inline-flex; height:30px; align-items:center; justify-content:center; border-radius:6px; border:1px solid transparent; background:transparent; padding:0 9px; font-size:11px; font-weight:500; color:rgb(71 85 105); white-space:nowrap; transition:background-color .15s,border-color .15s,color .15s; }
        .compact-action { display:inline-flex; height:34px; align-items:center; justify-content:center; border-radius:7px; border:1px solid rgb(226 232 240); background:white; padding:0 11px; font-size:11px; font-weight:600; color:rgb(51 65 85); }
        .result-action { display:inline-flex; height:30px; align-items:center; justify-content:center; border-radius:7px; border:1px solid rgb(226 232 240); background:white; padding:0 9px; font-size:10px; font-weight:600; color:rgb(71 85 105); }
        .result-action:disabled { opacity:.45; }
        .toggle-tab { display:inline-flex; min-height:34px; align-items:center; justify-content:center; border-radius:7px; border:1px solid rgb(226 232 240); background:white; padding:0 10px; font-size:11px; font-weight:600; color:rgb(71 85 105); }
        .calc-input, .calc-textarea { width:100%; border:1px solid rgb(203 213 225); border-radius:8px; background:white; color:rgb(15 23 42); font-size:12px; outline:none; transition:border-color .15s,box-shadow .15s; }
        .calc-input { min-height:36px; padding:7px 10px; }
        .calc-textarea { padding:9px 10px; resize:vertical; }
        .calc-input:focus, .calc-textarea:focus { border-color:rgb(129 140 248); box-shadow:0 0 0 3px rgb(238 242 255); }
        .field-label { display:block; font-size:10px; font-weight:600; color:rgb(71 85 105); }
        .live-value { display:block; width:100%; border:1px solid rgb(226 232 240); border-radius:10px; background:rgb(248 250 252); padding:10px 12px; transition:border-color .15s,background-color .15s; }
        .live-value:hover { border-color:rgb(165 180 252); background:rgb(248 250 252); }
        .stat-label { display:block; font-size:9px; font-weight:600; color:rgb(100 116 139); text-transform:uppercase; letter-spacing:.04em; }
        .stat-value { display:block; margin-top:3px; font-size:11px; font-weight:600; color:rgb(30 41 59); line-height:1.45; }
        .result-box { border:1px solid rgb(224 231 255); border-radius:10px; background:rgb(248 250 252); padding:12px; }
        .result-label { display:block; font-size:9px; font-weight:700; color:rgb(79 70 229); text-transform:uppercase; letter-spacing:.04em; }
        .result-main { display:block; margin-top:4px; overflow-wrap:anywhere; font-size:15px; font-weight:700; color:rgb(15 23 42); line-height:1.45; }
    </style>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/60 px-4 py-3 sm:px-5">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap gap-1" role="tablist" aria-label="Timestamp converter modes">
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'timestamp'" :class="{ 'is-active': mode === 'timestamp' }" @click="setMode('timestamp')" class="compact-tab">Timestamp → Date</button>
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'date'" :class="{ 'is-active': mode === 'date' }" @click="setMode('date')" class="compact-tab">Date → Timestamp</button>
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'duration'" :class="{ 'is-active': mode === 'duration' }" @click="setMode('duration')" class="compact-tab">Duration</button>
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'bulk'" :class="{ 'is-active': mode === 'bulk' }" @click="setMode('bulk')" class="compact-tab">Bulk</button>
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'json'" :class="{ 'is-active': mode === 'json' }" @click="setMode('json')" class="compact-tab">JSON</button>
                    <button type="button" role="tab" data-active-group="mode" :aria-selected="mode === 'range'" :class="{ 'is-active': mode === 'range' }" @click="setMode('range')" class="compact-tab">Range</button>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-semibold text-indigo-700">Client-side</span>
                    <button type="button" @click="reset()" class="result-action">Reset</button>
                </div>
            </div>
        </div>

        <div class="grid gap-4 p-4 lg:grid-cols-[1.02fr_0.98fr] lg:p-5">
            <div class="min-w-0 space-y-4">
                <template x-if="mode === 'timestamp'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900">Timestamp to date/time</h2>
                                <p class="mt-0.5 text-[11px] text-slate-500">Enter seconds, milliseconds, microseconds, or nanoseconds.</p>
                            </div>
                            <span x-show="detectedUnit" class="rounded-md bg-white px-2 py-1 text-[10px] font-semibold text-indigo-600 shadow-sm" x-text="'Detected: ' + detectedUnitLabel"></span>
                        </div>
                        <label class="field-label">Timestamp</label>
                        <div class="mt-1.5 flex gap-2">
                            <input x-model="timestampInput" @input="convertTimestamp()" @keydown.enter.prevent="convertTimestamp()" type="text" inputmode="decimal" class="calc-input" placeholder="e.g. 1750000000" aria-label="Unix timestamp">
                            <button type="button" @click="timestampInput = String(currentTimestampSeconds); convertTimestamp()" class="compact-action shrink-0">Now</button>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <button type="button" data-active-group="unit" :class="{ 'is-active': timestampUnit === 'auto' }" @click="timestampUnit='auto'; convertTimestamp()" class="compact-tab">Auto</button>
                            <button type="button" data-active-group="unit" :class="{ 'is-active': timestampUnit === 's' }" @click="timestampUnit='s'; convertTimestamp()" class="compact-tab">Seconds</button>
                            <button type="button" data-active-group="unit" :class="{ 'is-active': timestampUnit === 'ms' }" @click="timestampUnit='ms'; convertTimestamp()" class="compact-tab">Milliseconds</button>
                            <button type="button" data-active-group="unit" :class="{ 'is-active': timestampUnit === 'us' }" @click="timestampUnit='us'; convertTimestamp()" class="compact-tab">Microseconds</button>
                            <button type="button" data-active-group="unit" :class="{ 'is-active': timestampUnit === 'ns' }" @click="timestampUnit='ns'; convertTimestamp()" class="compact-tab">Nanoseconds</button>
                        </div>
                        <div class="mt-3 grid gap-3 sm:grid-cols-[1fr_auto]">
                            <div>
                                <label class="field-label">Display timezone</label>
                                <select x-model="selectedTimezone" @change="convertTimestamp(); saveSettings()" class="calc-input mt-1.5">
                                    <option value="local">Local timezone</option>
                                    <option value="UTC">UTC</option>
                                    <template x-for="zone in timezoneOptions" :key="zone"><option :value="zone" x-text="zone"></option></template>
                                </select>
                            </div>
                            <div class="flex items-end gap-1">
                                <button type="button" @click="useExample('unix')" class="compact-tab">Example</button>
                                <button type="button" @click="timestampInput=''; clearConversion()" class="compact-tab">Clear</button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'date'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3">
                            <h2 class="text-sm font-semibold text-slate-900">Date/time to timestamp</h2>
                            <p class="mt-0.5 text-[11px] text-slate-500">Enter a date/time and interpret it in the selected timezone.</p>
                        </div>
                        <label class="field-label">Date and time</label>
                        <input x-model="dateInput" @input="convertDate()" type="datetime-local" step="0.001" class="calc-input mt-1.5" aria-label="Date and time">
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="field-label">Timezone</label>
                                <select x-model="selectedTimezone" @change="convertDate(); saveSettings()" class="calc-input mt-1.5">
                                    <option value="local">Local timezone</option>
                                    <option value="UTC">UTC</option>
                                    <template x-for="zone in timezoneOptions" :key="zone"><option :value="zone" x-text="zone"></option></template>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Output format</label>
                                <select x-model="formatPreset" @change="refreshOutput(); saveSettings()" class="calc-input mt-1.5">
                                    <option value="standard">Standard</option>
                                    <option value="iso">ISO 8601</option>
                                    <option value="rfc">RFC 2822</option>
                                    <option value="utc">UTC</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                        <template x-if="formatPreset === 'custom'">
                            <div class="mt-3">
                                <label class="field-label">Custom format</label>
                                <input x-model="customFormat" @input="refreshOutput()" type="text" class="calc-input mt-1.5" placeholder="YYYY-MM-DD HH:mm:ss.SSS">
                                <p class="mt-1 text-[10px] text-slate-500">Tokens: YYYY MM DD HH hh mm ss SSS Z z.</p>
                            </div>
                        </template>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <button type="button" data-active-group="hour-format" :class="{ 'is-active': hour12 === false }" @click="hour12=false; refreshOutput(); saveSettings()" class="compact-tab">24-hour</button>
                            <button type="button" data-active-group="hour-format" :class="{ 'is-active': hour12 === true }" @click="hour12=true; refreshOutput(); saveSettings()" class="compact-tab">12-hour</button>
                            <button type="button" @click="useExample('date')" class="compact-tab">Example</button>
                            <button type="button" @click="dateInput=''; clearConversion()" class="compact-tab">Clear</button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'duration'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3"><h2 class="text-sm font-semibold text-slate-900">Timestamp difference & duration</h2><p class="mt-0.5 text-[11px] text-slate-500">Compare two Unix timestamps. Units can be detected independently.</p></div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div><label class="field-label">Start timestamp</label><input x-model="durationStart" @input="calculateDuration()" type="text" inputmode="decimal" class="calc-input mt-1.5" placeholder="1750000000"></div>
                            <div><label class="field-label">End timestamp</label><input x-model="durationEnd" @input="calculateDuration()" type="text" inputmode="decimal" class="calc-input mt-1.5" placeholder="1750086400"></div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <button type="button" @click="durationStart=String(currentTimestampSeconds); durationEnd=String(currentTimestampSeconds+86400); calculateDuration()" class="compact-tab">24 hours</button>
                            <button type="button" @click="useExample('duration')" class="compact-tab">Example</button>
                            <button type="button" @click="durationStart=''; durationEnd=''; calculateDuration()" class="compact-tab">Clear</button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'bulk'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3"><h2 class="text-sm font-semibold text-slate-900">Bulk timestamp conversion</h2><p class="mt-0.5 text-[11px] text-slate-500">One timestamp per line, or CSV rows. Each item gets its own validation status.</p></div>
                        <textarea x-model="bulkInput" @input="convertBulk()" rows="9" class="calc-textarea" placeholder="1750000000&#10;1750000000000&#10;0"></textarea>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <button type="button" @click="useExample('bulk')" class="compact-tab">Load examples</button>
                            <label class="compact-tab"><input type="file" accept=".txt,.csv,text/plain,text/csv" class="sr-only" @change="importBulk($event)">Import TXT/CSV</label>
                            <button type="button" @click="bulkInput=''; bulkResults=[]; hasResult=false; error=''" class="compact-tab">Clear</button>
                            <button type="button" @click="downloadResult('csv')" :disabled="!bulkResults.length" class="compact-tab">Export CSV</button>
                            <button type="button" @click="downloadResult('txt')" :disabled="!bulkResults.length" class="compact-tab">Export TXT</button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'json'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3"><h2 class="text-sm font-semibold text-slate-900">JSON timestamp conversion</h2><p class="mt-0.5 text-[11px] text-slate-500">Paste JSON containing timestamp/date values. The tool recursively converts timestamp-like numeric fields.</p></div>
                        <textarea x-model="jsonInput" @input="convertJson()" rows="11" class="calc-textarea font-mono" placeholder='{"created_at":1750000000,"updated_at":1750000000000}'></textarea>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <button type="button" @click="useExample('json')" class="compact-tab">Example</button>
                            <button type="button" @click="jsonInput=''; convertJson()" class="compact-tab">Clear</button>
                            <button type="button" @click="copyText(jsonOutput, $event)" :disabled="!jsonOutput" class="compact-tab">Copy JSON</button>
                        </div>
                    </div>
                </template>

                <template x-if="mode === 'range'">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-3"><h2 class="text-sm font-semibold text-slate-900">Timestamp range explorer</h2><p class="mt-0.5 text-[11px] text-slate-500">Explore dates before or after the epoch with a configurable range and step.</p></div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div><label class="field-label">Center timestamp</label><input x-model="rangeCenter" @input="exploreRange()" type="text" inputmode="decimal" class="calc-input mt-1.5" placeholder="0"></div>
                            <div><label class="field-label">Unit</label><select x-model="rangeUnit" @change="exploreRange()" class="calc-input mt-1.5"><option value="s">Seconds</option><option value="ms">Milliseconds</option></select></div>
                            <div><label class="field-label">Before / after count</label><input x-model.number="rangeCount" @input="exploreRange()" type="number" min="1" max="25" class="calc-input mt-1.5"></div>
                            <div><label class="field-label">Step</label><input x-model.number="rangeStep" @input="exploreRange()" type="number" min="1" max="3153600000" class="calc-input mt-1.5"></div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-1">
                            <button type="button" @click="rangeCenter='0'; rangeUnit='s'; rangeCount=5; rangeStep=86400; exploreRange()" class="compact-tab">Epoch ± 5 days</button>
                            <button type="button" @click="rangeCenter=String(currentTimestampSeconds); rangeUnit='s'; rangeCount=5; rangeStep=86400; exploreRange()" class="compact-tab">Now ± 5 days</button>
                            <button type="button" @click="rangeRows=[]; hasResult=false; error=''" class="compact-tab">Clear</button>
                        </div>
                    </div>
                </template>

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <div><h2 class="text-sm font-semibold text-slate-900">Live Unix timestamp</h2><p class="text-[11px] text-slate-500">Generated locally from your browser clock.</p></div>
                        <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-500" x-text="localTimezone"></span>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2">
                        <button type="button" @click="copyText(String(currentTimestampSeconds), $event)" class="live-value text-left"><span class="stat-label">Seconds</span><span class="mt-1 block text-lg font-bold text-slate-900" x-text="currentTimestampSeconds"></span></button>
                        <button type="button" @click="copyText(String(currentTimestampMilliseconds), $event)" class="live-value text-left"><span class="stat-label">Milliseconds</span><span class="mt-1 block text-lg font-bold text-slate-900" x-text="currentTimestampMilliseconds"></span></button>
                    </div>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        <div class="stat-card"><span class="stat-label">Epoch mode</span><span class="stat-value" x-text="epochMode === 'unix' ? 'Unix epoch' : customEpoch"></span></div>
                        <div class="stat-card"><span class="stat-label">Epoch counter</span><span class="stat-value" x-text="epochCounterText"></span></div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <div class="mb-2 flex items-center justify-between"><div><h2 class="text-xs font-semibold text-slate-700">Advanced options</h2><p class="text-[10px] text-slate-500">Epoch, relative time, comparison, and countdown/count-up.</p></div><button type="button" @click="showAdvanced=!showAdvanced" class="compact-tab" x-text="showAdvanced ? 'Hide' : 'Show'"></button></div>
                    <div x-show="showAdvanced" class="grid gap-3 sm:grid-cols-2">
                        <div><label class="field-label">Epoch</label><select x-model="epochMode" @change="refreshOutput(); updateClock(); saveSettings()" class="calc-input mt-1.5"><option value="unix">Unix — 1970-01-01</option><option value="custom">Custom epoch</option></select></div>
                        <div x-show="epochMode === 'custom'"><label class="field-label">Custom epoch ISO</label><input x-model="customEpoch" @input="refreshOutput(); updateClock()" type="text" class="calc-input mt-1.5" placeholder="2000-01-01T00:00:00Z"></div>
                        <div><label class="field-label">Relative time</label><button type="button" data-active-group="relative" :class="{ 'is-active': relativeEnabled }" @click="relativeEnabled=!relativeEnabled; refreshOutput(); saveSettings()" class="toggle-tab w-full" x-text="relativeEnabled ? 'Enabled' : 'Disabled'"></button></div>
                        <div><label class="field-label">Timezone comparison</label><button type="button" data-active-group="compare" :class="{ 'is-active': zoneCompareEnabled }" @click="zoneCompareEnabled=!zoneCompareEnabled; refreshOutput(); saveSettings()" class="toggle-tab w-full" x-text="zoneCompareEnabled ? 'Enabled' : 'Disabled'"></button></div>
                        <div><label class="field-label">Epoch counter</label><button type="button" data-active-group="counter" :class="{ 'is-active': epochCounterEnabled }" @click="epochCounterEnabled=!epochCounterEnabled; saveSettings()" class="toggle-tab w-full" x-text="epochCounterEnabled ? 'Live count-up/countdown' : 'Off'"></button></div>
                        <div><label class="field-label">Comparison zones</label><div class="mt-1.5 grid grid-cols-2 gap-1"><template x-for="zone in comparisonZones" :key="zone"><button type="button" data-active-group="zones" :class="{ 'is-active': selectedComparisonZones.includes(zone) }" @click="toggleComparisonZone(zone)" class="compact-tab w-full" x-text="zone.split('/').pop().replaceAll('_',' ')"></button></template></div></div>
                    </div>
                </div>
            </div>

            <div class="min-w-0 space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                        <div><h2 class="text-sm font-semibold text-slate-900">Converted result</h2><p class="text-[11px] text-slate-500" x-text="resultMeta"></p></div>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="copyText(primaryCopyValue, $event)" :disabled="!hasResult || !primaryCopyValue" class="result-action">Copy result</button>
                            <button type="button" @click="copyStructuredOutput($event)" :disabled="!hasResult" class="result-action">Copy structured</button>
                            <button type="button" @click="shareState()" class="result-action" x-text="shareMessage || 'Share state'"></button>
                        </div>
                    </div>

                    <template x-if="hasResult && mode !== 'bulk' && mode !== 'range' && mode !== 'json' && mode !== 'duration'">
                        <div class="space-y-2">
                            <div class="result-box"><span class="result-label">Human-readable</span><span class="result-main" x-text="humanReadable"></span></div>
                            <div class="grid gap-2 sm:grid-cols-2">
                                <div class="stat-card"><span class="stat-label">Unix seconds</span><span class="stat-value break-all" x-text="unixSecondsOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">Unix milliseconds</span><span class="stat-value break-all" x-text="unixMillisecondsOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">ISO 8601</span><span class="stat-value break-all" x-text="isoOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">UTC</span><span class="stat-value break-all" x-text="utcOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">Selected timezone</span><span class="stat-value break-all" x-text="timezoneOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">RFC 2822</span><span class="stat-value break-all" x-text="rfcOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">Timezone offset</span><span class="stat-value" x-text="timezoneOffsetOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">Timezone abbreviation</span><span class="stat-value" x-text="timezoneAbbreviation"></span></div>
                                <div class="stat-card"><span class="stat-label">Day / day of year</span><span class="stat-value" x-text="calendarDetails"></span></div>
                                <div class="stat-card"><span class="stat-label">ISO week</span><span class="stat-value" x-text="isoWeekOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">JavaScript Date</span><span class="stat-value break-all" x-text="javascriptDateOutput"></span></div>
                                <div class="stat-card"><span class="stat-label">Round-trip</span><span class="stat-value" x-text="roundTripOutput"></span></div>
                            </div>
                            <div x-show="relativeEnabled" class="rounded-lg border border-indigo-100 bg-indigo-50/60 px-3 py-2 text-xs text-indigo-800"><span class="font-semibold">Relative:</span> <span x-text="relativeOutput"></span></div>
                        </div>
                    </template>

                    <template x-if="hasResult && mode === 'duration'">
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div class="result-box sm:col-span-2"><span class="result-label">Duration</span><span class="result-main" x-text="durationResult ? durationResult.human : ''"></span></div>
                            <div class="stat-card"><span class="stat-label">Total seconds</span><span class="stat-value" x-text="durationResult ? durationResult.seconds : ''"></span></div>
                            <div class="stat-card"><span class="stat-label">Total milliseconds</span><span class="stat-value" x-text="durationResult ? durationResult.milliseconds : ''"></span></div>
                            <div class="stat-card"><span class="stat-label">Direction</span><span class="stat-value" x-text="durationResult ? durationResult.direction : ''"></span></div>
                            <div class="stat-card"><span class="stat-label">Difference</span><span class="stat-value" x-text="durationResult ? durationResult.start + ' → ' + durationResult.end : ''"></span></div>
                        </div>
                    </template>

                    <template x-if="hasResult && mode === 'bulk'">
                        <div class="overflow-auto rounded-xl border border-slate-200">
                            <table class="min-w-full text-left text-[11px]"><thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-2">Input</th><th class="px-3 py-2">Unit</th><th class="px-3 py-2">Date / time</th><th class="px-3 py-2">Status</th></tr></thead><tbody class="divide-y divide-slate-100"><template x-for="(row,index) in bulkResults" :key="index"><tr><td class="px-3 py-2 font-mono" x-text="row.input"></td><td class="px-3 py-2" x-text="row.unit"></td><td class="px-3 py-2" x-text="row.date"></td><td class="px-3 py-2" :class="row.ok ? 'text-emerald-600' : 'text-red-600'" x-text="row.ok ? 'Valid' : row.error"></td></tr></template></tbody></table>
                        </div>
                    </template>

                    <template x-if="hasResult && mode === 'json'">
                        <div class="space-y-2"><div class="rounded-xl border border-slate-200 bg-slate-50 p-3"><pre class="max-h-[420px] overflow-auto whitespace-pre-wrap break-words font-mono text-[11px] leading-5 text-slate-700" x-text="jsonOutput"></pre></div><div class="text-[10px] text-slate-500" x-text="jsonMeta"></div></div>
                    </template>

                    <template x-if="hasResult && mode === 'range'">
                        <div class="overflow-auto rounded-xl border border-slate-200"><table class="min-w-full text-left text-[11px]"><thead class="bg-slate-50 text-slate-500"><tr><th class="px-3 py-2">Offset</th><th class="px-3 py-2">Timestamp</th><th class="px-3 py-2">UTC</th><th class="px-3 py-2">Local</th></tr></thead><tbody class="divide-y divide-slate-100"><template x-for="row in rangeRows" :key="row.offset"><tr><td class="px-3 py-2" x-text="row.offset === 0 ? 'Center' : (row.offset > 0 ? '+' + row.offset : row.offset)"></td><td class="px-3 py-2 font-mono" x-text="row.timestamp"></td><td class="px-3 py-2" x-text="row.utc"></td><td class="px-3 py-2" x-text="row.local"></td></tr></template></tbody></table></div>
                    </template>

                    <template x-if="!hasResult">
                        <div class="flex min-h-[300px] items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50/60 px-5 text-center"><div><div class="text-sm font-medium text-slate-500">No conversion yet</div><div class="mt-1 text-xs text-slate-500">Enter a value or choose an example.</div></div></div>
                    </template>
                    <div x-show="error" class="mt-3 rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-xs font-medium text-red-700" x-text="error"></div>
                </div>

                <template x-if="mode !== 'bulk' && mode !== 'range' && mode !== 'json' && hasResult">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-3 flex items-center justify-between"><div><h2 class="text-xs font-semibold text-slate-700">Developer output</h2><p class="text-[10px] text-slate-500">Copy-ready conversion code.</p></div><button type="button" @click="copyText(codeSnippet, $event)" class="result-action">Copy code</button></div>
                        <select x-model="codeLanguage" @change="refreshOutput()" class="calc-input mb-2"><option value="javascript">JavaScript</option><option value="php">PHP</option><option value="python">Python</option><option value="java">Java</option><option value="go">Go</option><option value="csharp">C#</option><option value="sql">SQL</option></select>
                        <pre class="max-h-40 overflow-auto rounded-lg bg-slate-900 p-3 font-mono text-[11px] leading-5 text-slate-100" x-text="codeSnippet"></pre>
                    </div>
                </template>

                <template x-if="zoneCompareEnabled && hasResult && mode !== 'bulk' && mode !== 'range' && mode !== 'json'">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-3"><h2 class="text-xs font-semibold text-slate-700">Timezone comparison</h2><p class="text-[10px] text-slate-500">The same instant formatted in selected zones.</p></div>
                        <div class="space-y-1.5"><template x-for="item in zoneComparison" :key="item.zone"><div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2"><span class="text-[10px] font-semibold text-slate-600" x-text="item.zone"></span><span class="text-[11px] text-slate-800" x-text="item.value"></span></div></template></div>
                    </div>
                </template>

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex items-center justify-between"><div><h2 class="text-xs font-semibold text-slate-700">Conversion history</h2><p class="text-[10px] text-slate-500">Stored locally in this browser.</p></div><button type="button" @click="clearHistory()" class="result-action" :disabled="!history.length">Clear history</button></div>
                    <div x-show="history.length" class="space-y-1.5"><template x-for="(item,index) in history" :key="item.id"><button type="button" @click="restoreHistory(item)" class="flex w-full items-center justify-between gap-3 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-left hover:border-indigo-100"><span class="min-w-0"><span class="block truncate text-[10px] font-semibold text-slate-700" x-text="item.label"></span><span class="block truncate font-mono text-[10px] text-slate-500" x-text="item.value"></span></span><span class="shrink-0 text-[9px] text-slate-500" x-text="item.mode"></span></button></template></div>
                    <div x-show="!history.length" class="text-[11px] text-slate-500">No local conversion history yet.</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[10px] leading-5 text-slate-500">
                    <strong class="font-semibold text-slate-600">Privacy:</strong> Runs entirely in your browser. Conversion, timezone formatting, bulk processing, JSON processing, and history are not sent to AabiTech.
                </div>
            </div>
        </div>
    </section>
</div>

@script
<script>
window.aabiTimestampConverter = function () {
    return {
        mode: 'timestamp',
        timestampInput: '', timestampUnit: 'auto', detectedUnit: '', detectedUnitLabel: '',
        dateInput: '', selectedTimezone: 'local', formatPreset: 'standard', customFormat: 'YYYY-MM-DD HH:mm:ss.SSS', hour12: false,
        durationStart: '', durationEnd: '', durationResult: null,
        bulkInput: '', bulkResults: [],
        jsonInput: '', jsonOutput: '', jsonMeta: '',
        rangeCenter: '0', rangeUnit: 's', rangeCount: 5, rangeStep: 86400, rangeRows: [],
        resultDate: null, unixSecondsOutput: '', unixMillisecondsOutput: '', isoOutput: '', utcOutput: '', timezoneOutput: '', rfcOutput: '', humanReadable: '',
        timezoneOffsetOutput: '', timezoneAbbreviation: '', calendarDetails: '', isoWeekOutput: '', relativeOutput: '', javascriptDateOutput: '', roundTripOutput: '', resultMeta: '',
        primaryCopyValue: '', structuredOutput: '', hasResult: false, error: '', codeLanguage: 'javascript', codeSnippet: '',
        currentTimestampSeconds: 0, currentTimestampMilliseconds: 0, clockTimer: null,
        history: [], showAdvanced: false, relativeEnabled: true, zoneCompareEnabled: false,
        epochCounterEnabled: true, epochCounterText: '', epochMode: 'unix', customEpoch: '2000-01-01T00:00:00Z',
        localTimezone: 'UTC', timezoneOptions: [],
        comparisonZones: ['UTC', 'Asia/Karachi', 'America/New_York', 'Europe/London', 'Asia/Tokyo', 'Australia/Sydney'],
        selectedComparisonZones: ['UTC', 'Asia/Karachi', 'America/New_York', 'Europe/London'],
        zoneComparison: [], shareMessage: '',

        init() {
            this.localTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
            this.timezoneOptions = typeof Intl.supportedValuesOf === 'function' ? Intl.supportedValuesOf('timeZone') : [];
            this.restoreSettings();
            this.restoreHistoryFromStorage();
            this.loadFromHash();
            this.updateClock();
            this.clockTimer = setInterval(() => this.updateClock(), 250);
            if (this.mode === 'range' && this.rangeRows.length) this.exploreRange();
        },

        destroy() { if (this.clockTimer) clearInterval(this.clockTimer); },

        setMode(mode) {
            this.mode = mode;
            this.error = '';
            this.shareMessage = '';
            if (mode === 'timestamp' && this.timestampInput) this.convertTimestamp();
            else if (mode === 'date' && this.dateInput) this.convertDate();
            else if (mode === 'duration' && this.durationStart && this.durationEnd) this.calculateDuration();
            else if (mode === 'bulk' && this.bulkInput) this.convertBulk();
            else if (mode === 'json' && this.jsonInput) this.convertJson();
            else if (mode === 'range') this.exploreRange();
            else this.clearConversion(false);
            this.saveSettings();
        },

        updateClock() {
            const now = Date.now();
            const epoch = this.epochMilliseconds();
            const relative = now - epoch;
            this.currentTimestampMilliseconds = Math.trunc(relative);
            this.currentTimestampSeconds = Math.trunc(relative / 1000);
            this.epochCounterText = this.formatDuration(Math.abs(relative), true) + (relative >= 0 ? ' since epoch' : ' until epoch');
        },

        epochMilliseconds() {
            if (this.epochMode !== 'custom') return 0;
            const value = Date.parse(this.customEpoch);
            return Number.isFinite(value) ? value : 0;
        },

        parseNumeric(value) {
            const text = String(value ?? '').trim().replace(/,/g, '');
            if (!text || !/^[+-]?(?:\d+\.?\d*|\.\d+)(?:e[+-]?\d+)?$/i.test(text)) return null;
            const number = Number(text);
            return Number.isFinite(number) ? number : null;
        },

        detectTimestampUnit(value) {
            const n = this.parseNumeric(value);
            if (n === null) return null;
            const a = Math.abs(n);
            if (a >= 1e17) return 'ns';
            if (a >= 1e14) return 'us';
            if (a >= 1e11) return 'ms';
            return 's';
        },

        unitDivisor(unit) { return unit === 'ns' ? 1e9 : unit === 'us' ? 1e6 : unit === 'ms' ? 1e3 : 1; },

        parseTimestamp(value, requestedUnit = 'auto') {
            const n = this.parseNumeric(value);
            if (n === null) return { ok: false, error: 'Enter a valid numeric timestamp.' };
            const unit = requestedUnit === 'auto' ? this.detectTimestampUnit(value) : requestedUnit;
            if (!unit) return { ok: false, error: 'Unable to detect timestamp unit.' };
            const epoch = this.epochMilliseconds();
            const ms = epoch + (n / this.unitDivisor(unit)) * 1000;
            if (!Number.isFinite(ms) || Math.abs(ms) > 8.64e15) return { ok: false, error: 'Timestamp is outside the supported JavaScript date range.' };
            const date = new Date(ms);
            if (Number.isNaN(date.getTime())) return { ok: false, error: 'Invalid timestamp.' };
            return { ok: true, number: n, unit, ms, date };
        },

        timestampFromMilliseconds(ms, unit = 's') {
            const relative = ms - this.epochMilliseconds();
            return relative / 1000 * this.unitDivisor(unit);
        },

        convertTimestamp() {
            this.error = '';
            const raw = String(this.timestampInput).trim();
            if (!raw) { this.clearConversion(false); return; }
            const parsed = this.parseTimestamp(raw, this.timestampUnit);
            if (!parsed.ok) { this.hasResult = false; this.error = parsed.error; return; }
            this.detectedUnit = parsed.unit;
            this.detectedUnitLabel = { s: 'seconds', ms: 'milliseconds', us: 'microseconds', ns: 'nanoseconds' }[parsed.unit];
            this.resultDate = parsed.date;
            this.buildOutputs(parsed.date, parsed.ms, 'timestamp');
            this.addHistory('Timestamp → Date', raw, 'timestamp');
        },

        parseDateTimeInZone(value, zone) {
            const m = String(value).match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(?::(\d{2})(?:\.(\d{1,9}))?)?$/);
            if (!m) return { ok: false, error: 'Enter a valid date and time.' };
            const year = Number(m[1]), month = Number(m[2]), day = Number(m[3]), hour = Number(m[4]), minute = Number(m[5]), second = Number(m[6] || 0);
            const fraction = (m[7] || '').padEnd(3, '0').slice(0, 3);
            const msPart = Number(fraction || 0);
            if (month < 1 || month > 12 || day < 1 || day > 31 || hour > 23 || minute > 59 || second > 59) return { ok: false, error: 'Date/time contains an invalid calendar value.' };
            const targetZone = zone === 'local' ? this.localTimezone : zone;
            const wallClockMs = Date.UTC(year, month - 1, day, hour, minute, second, msPart);
            if (!Number.isFinite(wallClockMs)) return { ok: false, error: 'Invalid date/time.' };
            let guess = wallClockMs;
            for (let i = 0; i < 3; i++) {
                const offset = this.zoneOffset(new Date(guess), targetZone);
                const next = wallClockMs - offset;
                if (next === guess) break;
                guess = next;
            }
            const check = this.getZonedParts(new Date(guess), targetZone);
            if (check.year !== year || check.month !== month || check.day !== day || check.hour !== hour || check.minute !== minute || check.second !== second) {
                return { ok: false, error: 'This local time is ambiguous or does not exist in the selected timezone (DST transition).' };
            }
            return { ok: true, ms: guess, date: new Date(guess), zone: targetZone };
        },

        convertDate() {
            this.error = '';
            if (!this.dateInput) { this.clearConversion(false); return; }
            const parsed = this.parseDateTimeInZone(this.dateInput, this.selectedTimezone);
            if (!parsed.ok) { this.hasResult = false; this.error = parsed.error; return; }
            this.resultDate = parsed.date;
            this.buildOutputs(parsed.date, parsed.ms, 'date');
            this.addHistory('Date → Timestamp', this.dateInput, 'date');
        },

        getZonedParts(date, zone) {
            const target = zone === 'local' ? this.localTimezone : zone;
            const parts = new Intl.DateTimeFormat('en-CA', {
                timeZone: target, year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23'
            }).formatToParts(date);
            const out = {};
            parts.forEach(p => { if (p.type !== 'literal') out[p.type] = p.value; });
            return { year:Number(out.year), month:Number(out.month), day:Number(out.day), hour:Number(out.hour), minute:Number(out.minute), second:Number(out.second) };
        },

        zoneOffset(date, zone) {
            const target = zone === 'local' ? this.localTimezone : zone;
            const parts = new Intl.DateTimeFormat('en-US', { timeZone: target, timeZoneName: 'longOffset', year:'numeric', month:'2-digit', day:'2-digit', hour:'2-digit', minute:'2-digit', second:'2-digit', hourCycle:'h23' }).formatToParts(date);
            const name = parts.find(p => p.type === 'timeZoneName')?.value || 'GMT';
            if (name === 'GMT' || name === 'UTC') return 0;
            const match = name.match(/GMT([+-])(\d{2}):(\d{2})/);
            if (!match) return 0;
            const minutes = Number(match[2]) * 60 + Number(match[3]);
            return (match[1] === '+' ? 1 : -1) * minutes * 60000;
        },

        formatDate(date, zone, options = {}) {
            const target = zone === 'local' ? this.localTimezone : zone;
            return new Intl.DateTimeFormat('en-US', {
                timeZone: target, year:'numeric', month:'short', day:'2-digit', weekday:'short',
                hour:'2-digit', minute:'2-digit', second:'2-digit', fractionalSecondDigits:3,
                hour12: options.hour12 ?? this.hour12, timeZoneName: options.timeZoneName || 'short'
            }).format(date);
        },

        customFormatDate(date, zone) {
            const p = this.getZonedParts(date, zone);
            const ms = String(date.getUTCMilliseconds()).padStart(3, '0');
            const hour12 = p.hour % 12 || 12;
            const ampm = p.hour >= 12 ? 'PM' : 'AM';
            const offset = this.formatOffset(this.zoneOffset(date, zone));
            const abbr = this.zoneAbbreviation(date, zone);
            let value = this.customFormat || 'YYYY-MM-DD HH:mm:ss';
            const tokens = {
                YYYY:String(p.year).padStart(4,'0'), MM:String(p.month).padStart(2,'0'), DD:String(p.day).padStart(2,'0'),
                HH:String(p.hour).padStart(2,'0'), hh:String(hour12).padStart(2,'0'), mm:String(p.minute).padStart(2,'0'), ss:String(p.second).padStart(2,'0'),
                SSS:ms, Z:offset, z:abbr
            };
            return value.replace(/YYYY|SSS|MM|DD|HH|hh|mm|ss|Z|z/g, token => tokens[token] ?? token).replace(/A/g, ampm);
        },

        formatOffset(ms) {
            const sign = ms < 0 ? '-' : '+';
            const total = Math.abs(Math.trunc(ms / 60000));
            return sign + String(Math.floor(total / 60)).padStart(2,'0') + ':' + String(total % 60).padStart(2,'0');
        },

        zoneAbbreviation(date, zone) {
            const target = zone === 'local' ? this.localTimezone : zone;
            try {
                const parts = new Intl.DateTimeFormat('en-US', { timeZone:target, timeZoneName:'short' }).formatToParts(date);
                return parts.find(p => p.type === 'timeZoneName')?.value || target;
            } catch { return target; }
        },

        dayOfYear(date, zone) {
            const p = this.getZonedParts(date, zone);
            const start = Date.UTC(p.year, 0, 1);
            const current = Date.UTC(p.year, p.month - 1, p.day);
            return Math.floor((current - start) / 86400000) + 1;
        },

        isoWeek(date, zone) {
            const p = this.getZonedParts(date, zone);
            const d = new Date(Date.UTC(p.year, p.month - 1, p.day));
            const day = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - day);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        },

        relativeTime(ms) {
            const diff = Date.now() - ms;
            const future = diff < 0;
            const seconds = Math.round(Math.abs(diff) / 1000);
            if (seconds < 5) return 'just now';
            const units = [[31536000,'year'],[2592000,'month'],[604800,'week'],[86400,'day'],[3600,'hour'],[60,'minute'],[1,'second']];
            for (const [size,name] of units) if (seconds >= size) { const n = Math.floor(seconds / size); return future ? `in ${n} ${name}${n === 1 ? '' : 's'}` : `${n} ${name}${n === 1 ? '' : 's'} ago`; }
            return 'just now';
        },

        buildOutputs(date, ms, source) {
            const zone = this.selectedTimezone === 'local' ? this.localTimezone : this.selectedTimezone;
            const relativeTimestamp = this.timestampFromMilliseconds(ms, 's');
            const seconds = this.timestampFromMilliseconds(ms, 's');
            const milliseconds = this.timestampFromMilliseconds(ms, 'ms');
            this.unixSecondsOutput = this.cleanNumber(seconds);
            this.unixMillisecondsOutput = this.cleanNumber(milliseconds);
            this.isoOutput = date.toISOString();
            this.utcOutput = date.toUTCString();
            this.timezoneOutput = this.formatDate(date, zone);
            this.rfcOutput = new Intl.DateTimeFormat('en-US', { timeZone: zone, weekday:'short', day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false, timeZoneName:'short' }).format(date);
            this.humanReadable = this.formatPreset === 'iso' ? this.isoOutput : this.formatPreset === 'rfc' ? this.rfcOutput : this.formatPreset === 'utc' ? this.utcOutput : this.formatPreset === 'custom' ? this.customFormatDate(date, zone) : this.formatDate(date, zone);
            this.timezoneOffsetOutput = this.formatOffset(this.zoneOffset(date, zone));
            this.timezoneAbbreviation = this.zoneAbbreviation(date, zone);
            this.calendarDetails = `${new Intl.DateTimeFormat('en-US', { timeZone:zone, weekday:'long' }).format(date)} • Day ${this.dayOfYear(date, zone)}`;
            this.isoWeekOutput = `Week ${String(this.isoWeek(date, zone)).padStart(2,'0')}`;
            this.relativeOutput = this.relativeTime(ms);
            this.javascriptDateOutput = `new Date(${Math.trunc(ms)})`;
            this.roundTripOutput = this.verifyRoundTrip(ms);
            this.primaryCopyValue = source === 'date' ? this.unixSecondsOutput : this.humanReadable;
            this.resultMeta = source === 'date' ? `Converted in ${zone}` : `Converted as ${this.detectedUnitLabel || this.timestampUnit}`;
            this.makeCodeSnippet(ms);
            this.buildZoneComparison(ms);
            this.structuredOutput = JSON.stringify({ unix_seconds:Number(this.unixSecondsOutput), unix_milliseconds:Number(this.unixMillisecondsOutput), iso_8601:this.isoOutput, utc:this.utcOutput, timezone:zone, timezone_offset:this.timezoneOffsetOutput, timezone_abbreviation:this.timezoneAbbreviation, human_readable:this.humanReadable }, null, 2);
            this.hasResult = true;
        },

        cleanNumber(value) {
            return Number.isInteger(value) ? String(value) : String(Number(value.toFixed(6)));
        },

        verifyRoundTrip(ms) {
            const s = this.timestampFromMilliseconds(ms, 's');
            const parsed = this.parseTimestamp(String(s), 's');
            if (!parsed.ok) return 'Failed';
            return Math.abs(parsed.ms - ms) < 1 ? 'Verified ✓' : `Difference ${parsed.ms - ms} ms`;
        },

        refreshOutput() {
            if (this.mode === 'timestamp' && this.timestampInput) this.convertTimestamp();
            else if (this.mode === 'date' && this.dateInput) this.convertDate();
            else if (this.mode === 'duration' && this.durationStart && this.durationEnd) this.calculateDuration();
            if (this.resultDate) this.makeCodeSnippet(this.resultDate.getTime());
        },

        calculateDuration() {
            this.error = '';
            if (!this.durationStart || !this.durationEnd) { this.durationResult = null; this.hasResult = false; return; }
            const a = this.parseTimestamp(this.durationStart, 'auto'), b = this.parseTimestamp(this.durationEnd, 'auto');
            if (!a.ok || !b.ok) { this.durationResult = null; this.hasResult = false; this.error = a.ok ? b.error : a.error; return; }
            const diff = b.ms - a.ms;
            this.durationResult = { start:this.durationStart, end:this.durationEnd, milliseconds:Math.abs(diff), seconds:this.cleanNumber(Math.abs(diff)/1000), direction:diff >= 0 ? 'End is after start' : 'End is before start', human:this.formatDuration(Math.abs(diff), false) };
            this.resultMeta = 'Timestamp difference'; this.primaryCopyValue = this.durationResult.human; this.hasResult = true;
            this.addHistory('Timestamp difference', `${this.durationStart} → ${this.durationEnd}`, 'duration');
        },

        formatDuration(ms, compact) {
            let total = Math.floor(Math.abs(ms) / 1000);
            const days = Math.floor(total / 86400); total %= 86400;
            const hours = Math.floor(total / 3600); total %= 3600;
            const minutes = Math.floor(total / 60); const seconds = total % 60;
            if (compact) return `${days}d ${hours}h ${minutes}m ${seconds}s`;
            const parts = [];
            if (days) parts.push(`${days} day${days===1?'':'s'}`); if (hours) parts.push(`${hours} hour${hours===1?'':'s'}`); if (minutes) parts.push(`${minutes} minute${minutes===1?'':'s'}`); if (seconds || !parts.length) parts.push(`${seconds} second${seconds===1?'':'s'}`);
            return parts.join(', ');
        },

        convertBulk() {
            this.error = ''; const lines = String(this.bulkInput || '').split(/\r?\n/).map(v => v.trim()).filter(Boolean).slice(0,1000);
            if (!lines.length) { this.bulkResults=[]; this.hasResult=false; return; }
            this.bulkResults = lines.map(input => {
                const candidate = input.includes(',') ? input.split(',')[0].trim() : input;
                const parsed = this.parseTimestamp(candidate, 'auto');
                return parsed.ok ? { input, unit:parsed.unit, date:this.formatDate(parsed.date, this.selectedTimezone), ok:true } : { input, unit:'—', date:'—', ok:false, error:parsed.error };
            });
            this.hasResult = true; this.resultMeta = `${this.bulkResults.length} item${this.bulkResults.length===1?'':'s'} processed`; this.primaryCopyValue = this.bulkResults.filter(r=>r.ok).map(r=>r.date).join('\n');
            this.addHistory('Bulk conversion', `${this.bulkResults.length} items`, 'bulk');
        },

        async importBulk(event) {
            const file = event.target.files?.[0]; if (!file) return;
            try { this.bulkInput = await file.text(); this.convertBulk(); } catch { this.error = 'Unable to read the selected file.'; }
            event.target.value = '';
        },

        convertJson() {
            this.error = ''; this.jsonOutput = ''; this.jsonMeta = '';
            if (!this.jsonInput.trim()) { this.hasResult=false; return; }
            try {
                const source = JSON.parse(this.jsonInput);
                let converted = 0, invalid = 0;
                const walk = value => {
                    if (Array.isArray(value)) return value.map(walk);
                    if (value && typeof value === 'object') { const out={}; Object.entries(value).forEach(([k,v]) => { out[k] = walk(v); }); return out; }
                    if (typeof value === 'number' && Number.isFinite(value) && Math.abs(value) >= 1e8) {
                        const p = this.parseTimestamp(String(value), 'auto');
                        if (p.ok) { converted++; return { value, detected_unit:p.unit, iso_8601:p.date.toISOString(), utc:p.date.toUTCString(), local:this.formatDate(p.date,'local') }; }
                        invalid++;
                    }
                    return value;
                };
                const output = walk(source);
                this.jsonOutput = JSON.stringify(output, null, 2); this.jsonMeta = `${converted} timestamp value${converted===1?'':'s'} converted${invalid ? `; ${invalid} numeric value${invalid===1?'':'s'} could not be converted` : ''}.`;
                this.hasResult=true; this.primaryCopyValue=this.jsonOutput; this.resultMeta='JSON conversion';
                this.addHistory('JSON conversion', `${converted} converted values`, 'json');
            } catch (e) { this.hasResult=false; this.error = `Invalid JSON: ${e.message}`; }
        },

        exploreRange() {
            this.error=''; this.rangeRows=[];
            const center = this.parseNumeric(this.rangeCenter);
            const count = Math.min(25, Math.max(1, Number(this.rangeCount) || 5));
            const step = Math.max(1, Number(this.rangeStep) || 1);
            if (center === null) { this.hasResult=false; this.error='Enter a valid range center timestamp.'; return; }
            const rows=[];
            for (let offset=-count; offset<=count; offset++) {
                const n = center + offset * step;
                const p = this.parseTimestamp(String(n), this.rangeUnit);
                if (!p.ok) continue;
                rows.push({ offset, timestamp:this.cleanNumber(n), utc:p.date.toISOString(), local:this.formatDate(p.date,'local') });
            }
            this.rangeRows=rows; this.hasResult=rows.length>0; this.resultMeta=`${rows.length} timestamps explored`; this.primaryCopyValue=rows.map(r=>`${r.timestamp}\t${r.utc}`).join('\n');
        },

        buildZoneComparison(ms) {
            this.zoneComparison = this.selectedComparisonZones.map(zone => ({ zone, value:this.formatDate(new Date(ms), zone) }));
        },

        toggleComparisonZone(zone) {
            if (this.selectedComparisonZones.includes(zone)) {
                if (this.selectedComparisonZones.length > 1) this.selectedComparisonZones = this.selectedComparisonZones.filter(z => z !== zone);
            } else if (this.selectedComparisonZones.length < 6) this.selectedComparisonZones.push(zone);
            if (this.resultDate) this.buildZoneComparison(this.resultDate.getTime());
            this.saveSettings();
        },

        makeCodeSnippet(ms) {
            const unix = Math.trunc(this.timestampFromMilliseconds(ms, 's')); const millis = Math.trunc(this.timestampFromMilliseconds(ms,'ms'));
            const snippets = {
                javascript:`const date = new Date(${Math.trunc(ms)});\nconsole.log(date.toISOString());\n// Unix seconds: ${unix}`,
                php:`$date = new DateTimeImmutable('@${unix}');\necho $date->setTimezone(new DateTimeZone('UTC'))->format('c');`,
                python:`from datetime import datetime, timezone\ndate = datetime.fromtimestamp(${unix}, tz=timezone.utc)\nprint(date.isoformat())`,
                java:`Instant instant = Instant.ofEpochMilli(${millis}L);\nSystem.out.println(instant);`,
                go:`t := time.Unix(${unix}, 0).UTC()\nfmt.Println(t.Format(time.RFC3339))`,
                csharp:`var date = DateTimeOffset.FromUnixTimeSeconds(${unix});\nConsole.WriteLine(date.ToUniversalTime().ToString("O"));`,
                sql:`SELECT FROM_UNIXTIME(${unix}); -- MySQL / MariaDB\n-- PostgreSQL: to_timestamp(${unix})`
            };
            this.codeSnippet = snippets[this.codeLanguage] || snippets.javascript;
        },

        useExample(type) {
            const now = Math.trunc(Date.now()/1000);
            this.error=''; this.shareMessage='';
            if (type === 'unix') { this.mode='timestamp'; this.timestampUnit='auto'; this.timestampInput='1750000000'; this.convertTimestamp(); }
            if (type === 'date') { this.mode='date'; this.dateInput='2025-06-15T12:30:00'; this.selectedTimezone='UTC'; this.convertDate(); }
            if (type === 'duration') { this.mode='duration'; this.durationStart=String(now); this.durationEnd=String(now+86400+3661); this.calculateDuration(); }
            if (type === 'bulk') { this.mode='bulk'; this.bulkInput=`1750000000\n1750000000000\n0\n-1\ninvalid`; this.convertBulk(); }
            if (type === 'json') { this.mode='json'; this.jsonInput='{"created_at":1750000000,"updated_at":1750000000000,"nested":{"published_at":1609459200}}'; this.convertJson(); }
            if (type === 'range') { this.mode='range'; this.rangeCenter='0'; this.rangeUnit='s'; this.rangeCount=5; this.rangeStep=86400; this.exploreRange(); }
            this.saveSettings();
        },

        clearConversion(clearInputs = true) {
            this.resultDate=null; this.unixSecondsOutput=''; this.unixMillisecondsOutput=''; this.isoOutput=''; this.utcOutput=''; this.timezoneOutput=''; this.rfcOutput=''; this.humanReadable=''; this.timezoneOffsetOutput=''; this.timezoneAbbreviation=''; this.calendarDetails=''; this.isoWeekOutput=''; this.relativeOutput=''; this.javascriptDateOutput=''; this.roundTripOutput=''; this.resultMeta=''; this.primaryCopyValue=''; this.structuredOutput=''; this.codeSnippet=''; this.zoneComparison=[]; this.durationResult=null; this.bulkResults=[]; this.jsonOutput=''; this.jsonMeta=''; this.rangeRows=[]; this.hasResult=false; this.error='';
            if (clearInputs) { this.timestampInput=''; this.dateInput=''; this.durationStart=''; this.durationEnd=''; this.bulkInput=''; this.jsonInput=''; }
        },

        copyStructuredOutput(event) { this.copyText(this.structuredOutput || this.primaryCopyValue, event); },

        async copyText(text, event) {
            if (!text) return;
            try { await navigator.clipboard.writeText(String(text)); } catch { this.copyFallback(String(text)); }
            const button = event?.currentTarget;
            if (button) { const original=button.textContent; button.textContent='✓ Copied'; setTimeout(()=>button.textContent=original,1200); }
        },

        copyFallback(text) {
            const area=document.createElement('textarea'); area.value=text; area.style.position='fixed'; area.style.opacity='0'; document.body.appendChild(area); area.select(); try{document.execCommand('copy');}catch{} area.remove();
        },

        downloadResult(type='txt') {
            let content='', filename='timestamp-conversion.txt', mime='text/plain;charset=utf-8';
            if (type === 'csv' && this.bulkResults.length) {
                content='Input,Unit,Date,Status\n' + this.bulkResults.map(r => [r.input,r.unit,r.date,r.ok?'Valid':r.error].map(v=>`"${String(v).replaceAll('"','""')}"`).join(',')).join('\n'); filename='timestamp-conversion.csv'; mime='text/csv;charset=utf-8';
            } else if (type === 'txt' && this.bulkResults.length) content=this.bulkResults.map(r=>`${r.input}\t${r.unit}\t${r.date}\t${r.ok?'Valid':r.error}`).join('\n');
            else content=this.structuredOutput || this.primaryCopyValue;
            if (!content) return;
            const blob=new Blob([content],{type:mime}), url=URL.createObjectURL(blob), a=document.createElement('a'); a.href=url; a.download=filename; a.click(); URL.revokeObjectURL(url);
        },

        shareState() {
            const state={ mode:this.mode, timestampInput:this.timestampInput, timestampUnit:this.timestampUnit, dateInput:this.dateInput, selectedTimezone:this.selectedTimezone, formatPreset:this.formatPreset, customFormat:this.customFormat, hour12:this.hour12, epochMode:this.epochMode, customEpoch:this.customEpoch };
            const encoded=btoa(unescape(encodeURIComponent(JSON.stringify(state)))); location.hash='timestamp='+encoded; this.shareMessage='✓ Link state saved'; setTimeout(()=>this.shareMessage='',1400);
        },

        loadFromHash() {
            if (!location.hash.startsWith('#timestamp=')) return;
            try { const state=JSON.parse(decodeURIComponent(escape(atob(location.hash.slice(10))))); Object.assign(this,state); this.error=''; this.setMode(this.mode || 'timestamp'); } catch { /* Ignore malformed share state. */ }
        },

        addHistory(label, value, mode) {
            if (!value) return;
            const fingerprint=`${mode}|${value}`;
            this.history=this.history.filter(item=>item.fingerprint!==fingerprint);
            this.history.unshift({ id:Date.now()+Math.random(), fingerprint, label, value:String(value), mode });
            this.history=this.history.slice(0,20); this.saveHistory();
        },

        restoreHistory(item) {
            this.mode=item.mode;
            if (item.mode === 'timestamp') { this.timestampInput=item.value; this.convertTimestamp(); }
            else if (item.mode === 'date') { this.dateInput=item.value; this.convertDate(); }
            else if (item.mode === 'duration') { const parts=item.value.split(' → '); this.durationStart=parts[0]||''; this.durationEnd=parts[1]||''; this.calculateDuration(); }
        },

        clearHistory() { this.history=[]; this.saveHistory(); },
        saveHistory() { try { localStorage.setItem('aabi_timestamp_history', JSON.stringify(this.history)); } catch {} },
        restoreHistoryFromStorage() { try { const value=JSON.parse(localStorage.getItem('aabi_timestamp_history')||'[]'); if(Array.isArray(value)) this.history=value.slice(0,20); } catch { this.history=[]; } },
        saveSettings() { try { localStorage.setItem('aabi_timestamp_settings', JSON.stringify({ selectedTimezone:this.selectedTimezone, formatPreset:this.formatPreset, customFormat:this.customFormat, hour12:this.hour12, epochMode:this.epochMode, customEpoch:this.customEpoch, relativeEnabled:this.relativeEnabled, zoneCompareEnabled:this.zoneCompareEnabled, epochCounterEnabled:this.epochCounterEnabled, selectedComparisonZones:this.selectedComparisonZones })); } catch {} },
        restoreSettings() { try { const value=JSON.parse(localStorage.getItem('aabi_timestamp_settings')||'null'); if(value) Object.assign(this,value); } catch {} },

        reset() { this.clearConversion(true); this.mode='timestamp'; this.timestampUnit='auto'; this.shareMessage=''; this.saveSettings(); },

        handleShortcut(event) {
            if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                event.preventDefault();
                if(this.mode==='timestamp') this.convertTimestamp(); else if(this.mode==='date') this.convertDate(); else if(this.mode==='duration') this.calculateDuration(); else if(this.mode==='bulk') this.convertBulk(); else if(this.mode==='json') this.convertJson(); else if(this.mode==='range') this.exploreRange();
            }
            if (event.key === 'Escape') this.error='';
        }
    };
};
</script>
@endscript
