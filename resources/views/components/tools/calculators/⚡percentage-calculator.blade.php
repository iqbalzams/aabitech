<?php
use Livewire\Component;

new class extends Component
{
    // The percentage calculator is intentionally browser-only. No calculation
    // data is sent to Livewire or stored server-side.
};
?>

<div
    x-data="aabiPercentageCalculator()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    {{-- Mode / smart detection --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0 flex-1">
                <label for="percentage-smart-input" class="mb-1.5 block text-xs font-bold text-slate-600">
                    Describe the calculation <span class="font-normal text-slate-500">(optional)</span>
                </label>

                <div class="flex gap-2">
                    <input
                        id="percentage-smart-input"
                        type="text"
                        x-model="smartInput"
                        @keydown.enter.prevent="detectMode()"
                        placeholder="e.g. What is 15% of 240?"
                        class="h-10 min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                    >

                    <button
                        type="button"
                        @click="detectMode()"
                        class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                    >
                        Detect
                    </button>
                </div>

                <p
                    x-show="detectionMessage"
                    x-text="detectionMessage"
                    class="mt-1.5 text-[11px] text-indigo-600"
                ></p>
            </div>

            <div class="flex shrink-0 flex-wrap gap-2">
                <button
                    type="button"
                    @click="loadExample()"
                    class="inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700"
                >
                    Example
                </button>

                <button
                    type="button"
                    @click="reset()"
                    class="inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50"
                >
                    Reset
                </button>
            </div>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-8">
            <template x-for="item in modes" :key="item.id">
                <button
                    type="button"
                    data-active-group="percentage-mode"
                    :data-active-value="item.id"
                    :class="{ 'is-active': mode === item.id }"
                    @click="setMode(item.id)"
                    class="min-h-[48px] rounded-xl border border-slate-200 bg-white px-2 py-2 text-left transition hover:border-indigo-300 hover:bg-indigo-50"
                >
                    <span
                        class="block text-[10px] font-extrabold uppercase tracking-wide text-slate-500"
                        x-text="item.short"
                    ></span>

                    <span
                        class="mt-0.5 block text-[11px] font-bold leading-tight text-slate-700"
                        x-text="item.label"
                    ></span>
                </button>
            </template>
        </div>
    </div>

    {{-- Main workspace --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="grid lg:grid-cols-[1.05fr_0.95fr]">

            {{-- Input workspace --}}
            <section class="border-b border-slate-200 p-4 sm:p-5 lg:border-b-0 lg:border-r">
                <div class="mb-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2
                                class="text-base font-extrabold text-slate-900"
                                x-text="currentMode.title"
                            ></h2>

                            <p
                                class="mt-1 text-sm text-slate-500"
                                x-text="currentMode.description"
                            ></p>
                        </div>

                        <span
                            class="shrink-0 rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-500"
                            x-text="currentMode.category"
                        ></span>
                    </div>
                </div>

                {{-- Standard mode fields --}}
                <div x-show="isStandardMode" class="space-y-3">
                    <template x-for="field in activeFields" :key="field.key">
                        <div>
                            <label
                                :for="'percentage-' + field.key"
                                class="mb-1.5 block text-xs font-bold text-slate-600"
                                x-text="field.label"
                            ></label>

                            <div class="relative">
                                <input
                                    :id="'percentage-' + field.key"
                                    :type="field.type || 'number'"
                                    :step="field.step || 'any'"
                                    :min="field.min"
                                    :max="field.max"
                                    :placeholder="field.placeholder || ''"
                                    :inputmode="field.type === 'number' ? 'decimal' : 'text'"
                                    x-model="form[field.key]"
                                    @input="calculateLive()"
                                    class="h-12 w-full rounded-xl border-2 border-slate-300 bg-white px-4 text-base font-bold text-slate-900 outline-none transition placeholder:text-slate-300 hover:border-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                >

                                <span
                                    x-show="field.suffix"
                                    x-text="field.suffix"
                                    class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-500"
                                ></span>
                            </div>

                            <p
                                x-show="field.help"
                                x-text="field.help"
                                class="mt-1 text-[11px] text-slate-500"
                            ></p>
                        </div>
                    </template>
                </div>

                {{-- Increase/decrease direction --}}
                <div
                    x-show="mode === 'increase_decrease'"
                    class="mt-3 grid grid-cols-2 gap-2"
                >
                    <button
                        type="button"
                        data-active-group="increase-direction"
                        data-active-value="increase"
                        :class="{ 'is-active': direction === 'increase' }"
                        @click="direction='increase'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        Increase
                    </button>

                    <button
                        type="button"
                        data-active-group="increase-direction"
                        data-active-value="decrease"
                        :class="{ 'is-active': direction === 'decrease' }"
                        @click="direction='decrease'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        Decrease
                    </button>
                </div>

                {{-- Reverse direction --}}
                <div
                    x-show="mode === 'reverse' || mode === 'tax_inclusive'"
                    class="mt-3 grid grid-cols-2 gap-2"
                >
                    <button
                        type="button"
                        data-active-group="reverse-direction"
                        data-active-value="decrease"
                        :class="{ 'is-active': reverseDirection === 'decrease' }"
                        @click="reverseDirection='decrease'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        After decrease
                    </button>

                    <button
                        type="button"
                        data-active-group="reverse-direction"
                        data-active-value="increase"
                        :class="{ 'is-active': reverseDirection === 'increase' }"
                        @click="reverseDirection='increase'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        After increase
                    </button>
                </div>

                {{-- Tax-inclusive direction --}}
                <div
                    x-show="mode === 'tax_inclusive'"
                    class="mt-3 grid grid-cols-2 gap-2"
                >
                    <button
                        type="button"
                        data-active-group="tax-inclusive-direction"
                        data-active-value="extract"
                        :class="{ 'is-active': taxInclusiveDirection === 'extract' }"
                        @click="taxInclusiveDirection='extract'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600"
                    >
                        Inclusive → exclusive
                    </button>

                    <button
                        type="button"
                        data-active-group="tax-inclusive-direction"
                        data-active-value="add"
                        :class="{ 'is-active': taxInclusiveDirection === 'add' }"
                        @click="taxInclusiveDirection='add'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600"
                    >
                        Exclusive → inclusive
                    </button>
                </div>

                {{-- Tax direction --}}
                <div
                    x-show="mode === 'tax'"
                    class="mt-3 grid grid-cols-2 gap-2"
                >
                    <button
                        type="button"
                        data-active-group="tax-direction"
                        data-active-value="add"
                        :class="{ 'is-active': taxDirection === 'add' }"
                        @click="taxDirection='add'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        Add tax
                    </button>

                    <button
                        type="button"
                        data-active-group="tax-direction"
                        data-active-value="extract"
                        :class="{ 'is-active': taxDirection === 'extract' }"
                        @click="taxDirection='extract'; calculateLive()"
                        class="h-10 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 transition hover:border-indigo-300"
                    >
                        Extract tax
                    </button>
                </div>

                {{-- Discount presets --}}
                <div
                    x-show="mode === 'discount'"
                    class="mt-3 space-y-3"
                >
                    <div class="flex flex-wrap gap-2">
                        <template x-for="rate in discountPresets" :key="rate">
                            <button
                                type="button"
                                @click="form.discountPercent=rate; calculateLive()"
                                class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-[11px] font-bold text-slate-600 hover:border-indigo-300 hover:bg-indigo-50"
                                x-text="rate + '%'"
                            ></button>
                        </template>
                    </div>
                </div>

                {{-- Tax presets --}}
                <div
                    x-show="mode === 'tax' || mode === 'tax_inclusive'"
                    class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3"
                >
                    <div class="flex flex-wrap items-center gap-2">
                        <label
                            for="tax-preset"
                            class="text-[11px] font-bold text-slate-500"
                        >
                            Rate preset
                        </label>

                        <select
                            id="tax-preset"
                            x-model="taxPreset"
                            @change="applyTaxPreset()"
                            class="h-9 rounded-lg border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-700 outline-none focus:border-indigo-500"
                        >
                            <option value="">Custom rate</option>

                            <template x-for="preset in taxPresets" :key="preset.id">
                                <option
                                    :value="preset.id"
                                    x-text="preset.label + ' · ' + preset.rate + '%'"
                                ></option>
                            </template>
                        </select>

                        <span class="text-[10px] text-slate-500">
                            Rates are editable examples; verify the applicable local rate.
                        </span>
                    </div>
                </div>

                {{-- Sequential changes --}}
                <div
                    x-show="mode === 'sequential'"
                    class="space-y-3"
                >
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">
                            Starting value
                        </label>

                        <input
                            type="number"
                            step="any"
                            x-model="form.sequentialStart"
                            @input="calculateLive()"
                            class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >
                    </div>

                    <div class="grid gap-2 sm:grid-cols-3">
                        <template x-for="(change, index) in sequentialChanges" :key="index">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-2">
                                <label
                                    class="mb-1 block text-[10px] font-bold uppercase text-slate-500"
                                    x-text="'Change ' + (index + 1)"
                                ></label>

                                <div class="flex gap-1">
                                    <select
                                        x-model="change.direction"
                                        @change="calculateLive()"
                                        class="h-9 w-20 rounded-lg border border-slate-200 bg-white px-1 text-xs font-bold outline-none"
                                    >
                                        <option value="increase">+</option>
                                        <option value="decrease">−</option>
                                    </select>

                                    <input
                                        type="number"
                                        step="any"
                                        min="0"
                                        x-model="change.percent"
                                        @input="calculateLive()"
                                        class="h-9 min-w-0 flex-1 rounded-lg border border-slate-200 bg-white px-2 text-sm font-bold outline-none focus:border-indigo-500"
                                        placeholder="10"
                                    >
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Scenario comparison --}}
                <div
                    x-show="mode === 'compare'"
                    class="space-y-3"
                >
                    <div class="grid gap-3 sm:grid-cols-2">
                        <template x-for="scenario in scenarios" :key="scenario.id">
                            <div class="rounded-xl border border-slate-200 p-3">
                                <div
                                    class="mb-2 text-xs font-extrabold text-slate-700"
                                    x-text="scenario.label"
                                ></div>

                                <input
                                    type="number"
                                    step="any"
                                    x-model="scenario.start"
                                    @input="calculateLive()"
                                    placeholder="Starting value"
                                    class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm font-bold outline-none focus:border-indigo-500"
                                >

                                <div class="mt-2 flex gap-2">
                                    <input
                                        type="number"
                                        step="any"
                                        x-model="scenario.percent"
                                        @input="calculateLive()"
                                        placeholder="%"
                                        class="h-10 min-w-0 flex-1 rounded-lg border border-slate-200 px-3 text-sm font-bold outline-none focus:border-indigo-500"
                                    >

                                    <select
                                        x-model="scenario.direction"
                                        @change="calculateLive()"
                                        class="h-10 rounded-lg border border-slate-200 bg-white px-2 text-xs font-bold outline-none"
                                    >
                                        <option value="increase">Increase</option>
                                        <option value="decrease">Decrease</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Exam / marks --}}
                <div
                    x-show="mode === 'exam'"
                    class="space-y-3"
                >
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Marks obtained
                            </label>

                            <input
                                type="number"
                                step="any"
                                x-model="form.obtained"
                                @input="calculateLive()"
                                class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Total marks
                            </label>

                            <input
                                type="number"
                                step="any"
                                x-model="form.totalMarks"
                                @input="calculateLive()"
                                class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500"
                            >
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Target percentage
                                <span class="font-normal text-slate-500">optional</span>
                            </label>

                            <input
                                type="number"
                                step="any"
                                x-model="form.targetPercent"
                                @input="calculateLive()"
                                class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm font-bold outline-none focus:border-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Grade scale
                            </label>

                            <select
                                x-model="gradeScale"
                                @change="calculateLive()"
                                class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold outline-none focus:border-indigo-500"
                            >
                                <option value="standard">Standard</option>
                                <option value="pakistan">Pakistan-style</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Salary --}}
                <div
                    x-show="mode === 'salary'"
                    class="space-y-3"
                >
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Current salary
                            </label>

                            <input
                                type="number"
                                step="any"
                                x-model="form.salary"
                                @input="calculateLive()"
                                class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-slate-600">
                                Change %
                            </label>

                            <input
                                type="number"
                                step="any"
                                x-model="form.salaryPercent"
                                @input="calculateLive()"
                                class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            data-active-group="salary-direction"
                            data-active-value="increase"
                            :class="{ 'is-active': salaryDirection === 'increase' }"
                            @click="salaryDirection='increase'; calculateLive()"
                            class="h-10 rounded-xl border border-slate-200 text-xs font-bold"
                        >
                            Increase
                        </button>

                        <button
                            type="button"
                            data-active-group="salary-direction"
                            data-active-value="decrease"
                            :class="{ 'is-active': salaryDirection === 'decrease' }"
                            @click="salaryDirection='decrease'; calculateLive()"
                            class="h-10 rounded-xl border border-slate-200 text-xs font-bold"
                        >
                            Decrease
                        </button>
                    </div>
                </div>

                {{-- Commission / markup / margin --}}
                <div
                    x-show="mode === 'commission' || mode === 'markup' || mode === 'margin'"
                    class="space-y-3"
                >
                    <template x-for="field in activeFields" :key="field.key">
                        <div>
                            <label
                                :for="'special-' + field.key"
                                class="mb-1.5 block text-xs font-bold text-slate-600"
                                x-text="field.label"
                            ></label>

                            <input
                                :id="'special-' + field.key"
                                type="number"
                                step="any"
                                x-model="form[field.key]"
                                @input="calculateLive()"
                                class="h-12 w-full rounded-xl border-2 border-slate-300 px-4 text-base font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </template>
                </div>

                {{-- Formatting --}}
                <div class="mt-5 grid gap-3 sm:grid-cols-[1fr_150px]">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">
                            Currency display
                        </label>

                        <select
                            x-model="currency"
                            @change="calculateLive()"
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 outline-none focus:border-indigo-500"
                        >
                            <template x-for="item in currencies" :key="item.code">
                                <option
                                    :value="item.code"
                                    x-text="item.code + ' · ' + item.name"
                                ></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">
                            Decimals
                        </label>

                        <select
                            x-model.number="precision"
                            @change="calculateLive()"
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 outline-none focus:border-indigo-500"
                        >
                            <template x-for="n in 13" :key="n">
                                <option :value="n - 1" x-text="n - 1"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Error --}}
                <div
                    x-show="error"
                    x-text="error"
                    role="alert"
                    class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
                ></div>

                {{-- Actions --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="calculate(true)"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-xl bg-slate-900 px-5 text-sm font-extrabold text-white transition hover:bg-indigo-700 sm:flex-none"
                    >
                        Calculate
                    </button>

                    <button
                        type="button"
                        @click="shareState()"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Share
                    </button>

                    <button
                        type="button"
                        @click="reset()"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 hover:bg-slate-50"
                    >
                        Reset
                    </button>
                </div>

                <p class="mt-3 text-[11px] text-slate-500">
                    Live results update as you type. Enter calculates; Ctrl/Cmd+Enter calculates and saves to history.
                </p>
            </section>

            {{-- Result --}}
            <section class="flex min-h-[440px] flex-col bg-gradient-to-br from-indigo-50 via-white to-slate-50 p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600">
                            Result
                        </span>

                        <h3
                            class="mt-1 text-sm font-bold text-slate-800"
                            x-text="resultLabel || 'Your answer will appear here'"
                        ></h3>
                    </div>

                    <button
                        type="button"
                        data-copy
                        :data-copy-text="copyResultText"
                        x-show="hasValidResult"
                        class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 shadow-sm hover:border-indigo-300 hover:text-indigo-600"
                    >
                        <span data-copy-label>Copy</span>
                    </button>
                </div>

                <div class="mt-4 rounded-2xl border border-indigo-100 bg-white p-5 shadow-sm">
                    <div x-show="hasValidResult">
                        <div class="text-xs font-semibold text-slate-500">
                            Answer
                        </div>

                        <div
                            class="mt-1 break-words text-4xl font-black tracking-tight text-slate-900 sm:text-5xl"
                            x-text="displayResult"
                        ></div>

                        <div
                            class="mt-2 text-sm font-medium text-indigo-600"
                            x-text="resultSummary"
                        ></div>

                        <div
                            x-show="directionIndicator"
                            class="mt-3 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold"
                            :class="directionIndicator === 'increase'
                                ? 'bg-emerald-50 text-emerald-700'
                                : directionIndicator === 'decrease'
                                    ? 'bg-amber-50 text-amber-700'
                                    : 'bg-slate-100 text-slate-600'"
                            x-text="directionIndicator === 'increase'
                                ? '↑ Increase'
                                : directionIndicator === 'decrease'
                                    ? '↓ Decrease'
                                    : '→ No change'"
                        ></div>
                    </div>

                    <div
                        x-show="!hasValidResult && !error"
                        class="py-10 text-center"
                    >
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl font-black text-slate-500">
                            %
                        </div>

                        <p class="mt-3 text-sm font-bold text-slate-600">
                            Enter your values
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            The answer, formula and steps will appear here.
                        </p>
                    </div>
                </div>

                {{-- Before / after --}}
                <div
                    x-show="beforeAfter"
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4"
                >
                    <div class="mb-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Before / after
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                        <span x-text="format(beforeAfter?.before ?? 0)"></span>
                        <span x-text="format(beforeAfter?.after ?? 0)"></span>
                    </div>

                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full bg-indigo-500 transition-all"
                            :style="'width:' + gaugeValue + '%'"
                        ></div>
                    </div>

                    <div
                        class="mt-1 text-[10px] text-slate-500"
                        x-text="'Absolute percentage change: ' + pct(Math.abs(result ?? 0))"
                    ></div>
                </div>

                {{-- Formula --}}
                <div
                    x-show="hasValidResult"
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4"
                >
                    <div class="mb-1 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Formula
                    </div>

                    <div
                        class="break-words font-mono text-sm font-semibold text-slate-700"
                        x-text="formula"
                    ></div>
                </div>

                {{-- Steps --}}
                <div
                    x-show="steps.length"
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4"
                >
                    <div class="mb-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Step-by-step
                    </div>

                    <div class="space-y-2">
                        <template x-for="(step,index) in steps" :key="index">
                            <div class="flex gap-3 text-sm">
                                <span
                                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-[10px] font-black text-indigo-600"
                                    x-text="index+1"
                                ></span>

                                <span
                                    class="font-medium leading-5 text-slate-600"
                                    x-text="step"
                                ></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Quick facts --}}
                <div
                    x-show="quickFacts.length"
                    class="mt-3 grid grid-cols-2 gap-2"
                >
                    <template x-for="fact in quickFacts" :key="fact.label">
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <div
                                class="text-[10px] font-bold uppercase tracking-wide text-slate-500"
                                x-text="fact.label"
                            ></div>

                            <div
                                class="mt-1 truncate text-sm font-extrabold text-slate-800"
                                x-text="fact.value"
                            ></div>
                        </div>
                    </template>
                </div>

                {{-- Comparison --}}
                <div
                    x-show="mode === 'compare' && comparisonResults.length"
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4"
                >
                    <div class="mb-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Scenario comparison
                    </div>

                    <div class="space-y-2">
                        <template x-for="item in comparisonResults" :key="item.label">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span
                                    class="font-semibold text-slate-600"
                                    x-text="item.label"
                                ></span>

                                <span
                                    class="font-black text-slate-900"
                                    x-text="item.result"
                                ></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Sequential simulation --}}
                <div
                    x-show="mode === 'sequential' && sequentialResults.length"
                    class="mt-3 rounded-xl border border-slate-200 bg-white p-4"
                >
                    <div class="mb-2 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Sequential simulation
                    </div>

                    <div class="space-y-1.5">
                        <template x-for="item in sequentialResults" :key="item.label">
                            <div class="flex justify-between gap-3 text-xs">
                                <span
                                    class="text-slate-500"
                                    x-text="item.label"
                                ></span>

                                <span
                                    class="font-bold text-slate-800"
                                    x-text="item.result"
                                ></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-auto pt-4 text-[11px] text-slate-500">
                    All calculations run locally in your browser.
                </div>
            </section>
        </div>
    </div>

    {{-- Settings / utility panels --}}
    <div class="grid gap-4 lg:grid-cols-2">
        <details class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-sm font-bold text-slate-700">
                Calculation settings
                <span class="text-xs font-semibold text-slate-500">
                    Formatting & presets
                </span>
            </summary>

            <div class="border-t border-slate-100 p-4">
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="rounded-xl border border-slate-200 p-3">
                        <span class="block text-xs font-bold text-slate-600">
                            Thousands grouping
                        </span>

                        <select
                            x-model="useGrouping"
                            @change="calculateLive()"
                            class="mt-2 h-9 w-full rounded-lg border border-slate-200 bg-white px-2 text-xs font-semibold"
                        >
                            <option :value="true">Enabled</option>
                            <option :value="false">Disabled</option>
                        </select>
                    </label>

                    <label class="rounded-xl border border-slate-200 p-3">
                        <span class="block text-xs font-bold text-slate-600">
                            Percentage points
                        </span>

                        <span class="mt-1 block text-[11px] leading-5 text-slate-500">
                            Use percentage change for relative change; percentage points for rate-to-rate differences.
                        </span>
                    </label>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="mode='percentage_points'; calculateLive()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-indigo-300"
                    >
                        Percentage points
                    </button>

                    <button
                        type="button"
                        @click="mode='margin'; calculateLive()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-indigo-300"
                    >
                        Margin vs markup
                    </button>

                    <button
                        type="button"
                        @click="mode='tax_inclusive'; calculateLive()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-indigo-300"
                    >
                        Tax inclusive ↔ exclusive
                    </button>
                </div>
            </div>
        </details>

        <details
            class="rounded-2xl border border-slate-200 bg-white shadow-sm"
            x-show="history.length"
        >
            <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-sm font-bold text-slate-700">
                Recent calculations
                <span
                    class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500"
                    x-text="history.length"
                ></span>
            </summary>

            <div class="border-t border-slate-100">
                <template x-for="item in history" :key="item.id">
                    <button
                        type="button"
                        @click="restoreHistory(item)"
                        class="flex w-full items-center justify-between gap-3 border-b border-slate-100 px-4 py-3 text-left last:border-b-0 hover:bg-slate-50"
                    >
                        <div class="min-w-0">
                            <div
                                class="truncate text-xs font-bold text-slate-700"
                                x-text="item.mode"
                            ></div>

                            <div
                                class="mt-0.5 truncate text-[11px] text-slate-500"
                                x-text="item.summary"
                            ></div>
                        </div>

                        <div
                            class="shrink-0 text-sm font-black text-indigo-600"
                            x-text="item.result"
                        ></div>
                    </button>
                </template>
            </div>
        </details>
    </div>

    {{-- Privacy --}}
    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
        <div class="flex gap-3">
            <div class="mt-0.5 text-emerald-600">
                ✓
            </div>

            <div>
                <p class="text-xs font-bold text-emerald-800">
                    Private by design
                </p>

                <p class="mt-0.5 text-xs leading-5 text-emerald-700">
                    Calculations are performed in your browser. AabiTech does not receive your entered values for calculation.
                </p>
            </div>
        </div>
    </div>

    @script
    <script>
        window.aabiPercentageCalculator = function () {
            return {
                mode: 'percent_of',

                smartInput: '',
                detectionMessage: '',
                error: '',

                result: null,
                resultLabel: '',
                resultSummary: '',
                displayResult: '',
                formula: '',
                steps: [],
                quickFacts: [],
                directionIndicator: '',

                gaugeValue: 0,
                beforeAfter: null,

                lastAction: null,
                history: [],

                precision: 2,
                currency: 'NONE',
                useGrouping: true,

                taxPreset: '',
                taxDirection: 'add',
                taxInclusiveDirection: 'extract',

                reverseDirection: 'decrease',
                direction: 'increase',
                salaryDirection: 'increase',

                gradeScale: 'standard',

                comparisonResults: [],
                sequentialResults: [],

                sequentialChanges: [
                    {
                        direction: 'increase',
                        percent: 10
                    },
                    {
                        direction: 'decrease',
                        percent: 5
                    },
                    {
                        direction: 'increase',
                        percent: 10
                    }
                ],

                scenarios: [
                    {
                        id: 'a',
                        label: 'Scenario A',
                        start: 1000,
                        percent: 10,
                        direction: 'increase'
                    },
                    {
                        id: 'b',
                        label: 'Scenario B',
                        start: 1000,
                        percent: 15,
                        direction: 'increase'
                    }
                ],

                form: {
                    percent: '',
                    value: '',

                    part: '',
                    whole: '',

                    oldValue: '',
                    newValue: '',

                    differenceA: '',
                    differenceB: '',

                    startValue: '',
                    changePercent: '',

                    finalValue: '',
                    reversePercent: '',

                    price: '',
                    discountPercent: '',

                    taxAmount: '',
                    taxPercent: 18,

                    tipBill: '',
                    tipPercent: 15,
                    tipPeople: 1,

                    commissionSales: '',
                    commissionPercent: '',

                    cost: '',
                    markupPercent: '',

                    sellingPrice: '',
                    profit: '',

                    rateBefore: '',
                    rateAfter: '',

                    inclusiveAmount: '',
                    inclusiveTaxPercent: 18,

                    sequentialStart: 1000,

                    obtained: '',
                    totalMarks: '',
                    targetPercent: '',

                    salary: '',
                    salaryPercent: '',

                    marginRevenue: '',
                    marginProfit: '',

                    pointsBefore: '',
                    pointsAfter: ''
                },

                currencies: [
                    {
                        code: 'NONE',
                        name: 'No currency'
                    },
                    {
                        code: 'PKR',
                        name: 'Pakistani Rupee'
                    },
                    {
                        code: 'USD',
                        name: 'US Dollar'
                    },
                    {
                        code: 'EUR',
                        name: 'Euro'
                    },
                    {
                        code: 'GBP',
                        name: 'British Pound'
                    },
                    {
                        code: 'AED',
                        name: 'UAE Dirham'
                    },
                    {
                        code: 'INR',
                        name: 'Indian Rupee'
                    },
                    {
                        code: 'SAR',
                        name: 'Saudi Riyal'
                    },
                    {
                        code: 'CAD',
                        name: 'Canadian Dollar'
                    },
                    {
                        code: 'AUD',
                        name: 'Australian Dollar'
                    }
                ],

                taxPresets: [
                    {
                        id: 'pk',
                        label: 'Pakistan example',
                        rate: 18
                    },
                    {
                        id: 'in',
                        label: 'India GST example',
                        rate: 18
                    },
                    {
                        id: 'uk',
                        label: 'UK VAT example',
                        rate: 20
                    },
                    {
                        id: 'ae',
                        label: 'UAE VAT example',
                        rate: 5
                    },
                    {
                        id: 'eu',
                        label: 'EU VAT example',
                        rate: 20
                    },
                    {
                        id: 'us',
                        label: 'US sales-tax example',
                        rate: 8.875
                    }
                ],

                discountPresets: [
                    5,
                    10,
                    15,
                    20,
                    25,
                    30,
                    50
                ],

                modes: [
                    {
                        id: 'percent_of',
                        short: '% OF',
                        label: 'Percent of',
                        category: 'Core',
                        title: 'What is X% of Y?',
                        description: 'Find a percentage of any number.'
                    },
                    {
                        id: 'what_percent',
                        short: 'WHAT %',
                        label: 'What percent?',
                        category: 'Core',
                        title: 'X is what % of Y?',
                        description: 'Find what percentage one value represents.'
                    },
                    {
                        id: 'change',
                        short: 'CHANGE',
                        label: '% change',
                        category: 'Core',
                        title: 'What is the percentage change?',
                        description: 'Measure relative increase or decrease from an original value.'
                    },
                    {
                        id: 'difference',
                        short: 'DIFF',
                        label: '% difference',
                        category: 'Core',
                        title: 'What is the percentage difference?',
                        description: 'Compare two values without choosing a baseline.'
                    },
                    {
                        id: 'increase_decrease',
                        short: '+ / −',
                        label: 'Add / subtract',
                        category: 'Core',
                        title: 'Increase or decrease a value',
                        description: 'Apply a percentage increase or decrease.'
                    },
                    {
                        id: 'reverse',
                        short: 'REVERSE',
                        label: 'Reverse %',
                        category: 'Core',
                        title: 'Find the original value',
                        description: 'Recover the value before a known percentage change.'
                    },
                    {
                        id: 'discount',
                        short: 'SALE',
                        label: 'Discount',
                        category: 'Money',
                        title: 'Calculate a discount',
                        description: 'Find savings and the final discounted price.'
                    },
                    {
                        id: 'tax',
                        short: 'TAX',
                        label: 'Tax / VAT / GST',
                        category: 'Money',
                        title: 'Add or extract tax',
                        description: 'Calculate tax added to a price or extract tax from a tax-inclusive total.'
                    },
                    {
                        id: 'tip',
                        short: 'TIP',
                        label: 'Tip / split',
                        category: 'Money',
                        title: 'Calculate a tip',
                        description: 'Calculate tip, total and amount per person.'
                    },
                    {
                        id: 'commission',
                        short: 'COMM',
                        label: 'Commission',
                        category: 'Business',
                        title: 'Calculate commission',
                        description: 'Find commission earned from sales.'
                    },
                    {
                        id: 'markup',
                        short: 'MARKUP',
                        label: 'Markup',
                        category: 'Business',
                        title: 'Calculate markup',
                        description: 'Calculate selling price and markup amount.'
                    },
                    {
                        id: 'margin',
                        short: 'MARGIN',
                        label: 'Margin',
                        category: 'Business',
                        title: 'Profit margin vs markup',
                        description: 'Compare profit margin and markup from cost and selling price.'
                    },
                    {
                        id: 'exam',
                        short: 'EXAM',
                        label: 'Exam marks',
                        category: 'Study',
                        title: 'Calculate exam percentage',
                        description: 'Convert marks into a percentage and optional target marks.'
                    },
                    {
                        id: 'salary',
                        short: 'SALARY',
                        label: 'Salary change',
                        category: 'Money',
                        title: 'Calculate a salary increase or decrease',
                        description: 'See the change amount and new salary.'
                    },
                    {
                        id: 'sequential',
                        short: 'CHAIN',
                        label: 'Sequential changes',
                        category: 'Advanced',
                        title: 'Simulate sequential percentage changes',
                        description: 'Apply multiple percentage changes in sequence.'
                    },
                    {
                        id: 'compare',
                        short: 'COMPARE',
                        label: 'Compare scenarios',
                        category: 'Advanced',
                        title: 'Compare percentage scenarios',
                        description: 'Calculate two scenarios side by side.'
                    },
                    {
                        id: 'percentage_points',
                        short: 'POINTS',
                        label: 'Percentage points',
                        category: 'Advanced',
                        title: 'Percentage points vs percentage change',
                        description: 'Compare two percentage rates in percentage points and relative change.'
                    },
                    {
                        id: 'tax_inclusive',
                        short: 'IN/EX',
                        label: 'Tax inclusive',
                        category: 'Money',
                        title: 'Tax-inclusive ↔ tax-exclusive',
                        description: 'Move between a tax-inclusive amount and its pre-tax amount.'
                    }
                ],

                get currentMode() {
                    return this.modes.find(item => item.id === this.mode) || this.modes[0];
                },

                get isStandardMode() {
                    return ![
                        'sequential',
                        'compare',
                        'exam',
                        'salary',
                        'commission',
                        'markup',
                        'margin'
                    ].includes(this.mode);
                },

                get hasValidResult() {
                    return Number.isFinite(this.result) && !this.error;
                },

                get copyResultText() {
                    if (!this.hasValidResult) {
                        return '';
                    }

                    return [
                        'AabiTech Percentage Calculator',
                        this.resultLabel,
                        'Result: ' + this.displayResult,
                        'Formula: ' + this.formula,
                        ...this.steps.map((s, i) => (i + 1) + '. ' + s)
                    ].join('\n');
                },

                get activeFields() {
                    const f = (
                        key,
                        label,
                        placeholder = '',
                        suffix = ''
                    ) => ({
                        key,
                        label,
                        placeholder,
                        suffix
                    });

                    const map = {
                        percent_of: [
                            f('percent', 'Percentage', '15', '%'),
                            f('value', 'Number', '240')
                        ],

                        what_percent: [
                            f('part', 'Part', '30'),
                            f('whole', 'Whole', '120')
                        ],

                        change: [
                            f('oldValue', 'Original value', '80'),
                            f('newValue', 'New value', '100')
                        ],

                        difference: [
                            f('differenceA', 'First value', '80'),
                            f('differenceB', 'Second value', '100')
                        ],

                        increase_decrease: [
                            f('startValue', 'Starting value', '500'),
                            f('changePercent', 'Percentage', '20', '%')
                        ],

                        reverse: [
                            f('finalValue', 'Final value', '80'),
                            f('reversePercent', 'Percentage', '20', '%')
                        ],

                        discount: [
                            f('price', 'Original price', '240'),
                            f('discountPercent', 'Discount', '15', '%')
                        ],

                        tax: [
                            f('taxAmount', 'Amount', '1000'),
                            f('taxPercent', 'Tax rate', '18', '%')
                        ],

                        tip: [
                            f('tipBill', 'Bill', '100'),
                            f('tipPercent', 'Tip percentage', '15', '%'),
                            f('tipPeople', 'People', '2')
                        ],

                        commission: [
                            f('commissionSales', 'Sales amount', '10000'),
                            f('commissionPercent', 'Commission rate', '5', '%')
                        ],

                        markup: [
                            f('cost', 'Cost', '100'),
                            f('markupPercent', 'Markup', '25', '%')
                        ],

                        margin: [
                            f('cost', 'Cost', '100'),
                            f('sellingPrice', 'Selling price', '125')
                        ],

                        tax_inclusive: [
                            f('inclusiveAmount', 'Tax-inclusive amount', '1180'),
                            f('inclusiveTaxPercent', 'Tax rate', '18', '%')
                        ],

                        percentage_points: [
                            f('pointsBefore', 'First rate', '40', '%'),
                            f('pointsAfter', 'Second rate', '55', '%')
                        ]
                    };

                    return map[this.mode] || [];
                },

                init() {
                    this.loadState();
                    this.loadSharedState();

                    this.$nextTick(() => {
                        this.calculateLive();
                    });
                },

                setMode(mode) {
                    if (!this.modes.some(item => item.id === mode)) {
                        return;
                    }

                    this.mode = mode;
                    this.clearResult();
                    this.detectionMessage = '';

                    this.saveState();
                    this.calculateLive();
                },

                clearResult() {
                    this.error = '';
                    this.result = null;
                    this.beforeAfter = null;
                    this.gaugeValue = 0;
                    this.resultLabel = '';
                    this.resultSummary = '';
                    this.displayResult = '';
                    this.formula = '';
                    this.steps = [];
                    this.quickFacts = [];
                    this.directionIndicator = '';
                    this.comparisonResults = [];
                    this.sequentialResults = [];
                },

                calculateLive() {
                    this.calculate(false);
                },

                calculate(recordHistory = false) {
                    this.clearResult();

                    try {
                        const fn = this['calculate_' + this.mode];

                        if (typeof fn !== 'function') {
                            throw new Error('Select a valid calculation mode.');
                        }

                        fn.call(this);

                        if (recordHistory && this.hasValidResult) {
                            this.addHistory();
                        }

                        this.saveState();
                    } catch (e) {
                        this.error = e instanceof Error
                            ? e.message
                            : 'Please check your values.';
                    }
                },

                n(value, label, opts = {}) {
                    if (
                        value === '' ||
                        value === null ||
                        value === undefined
                    ) {
                        throw new Error('Enter a value for ' + label + '.');
                    }

                    const v = Number(value);

                    if (!Number.isFinite(v)) {
                        throw new Error(
                            label + ' must be a valid finite number.'
                        );
                    }

                    if (
                        opts.min !== undefined &&
                        v < opts.min
                    ) {
                        throw new Error(
                            label + ' must be at least ' + opts.min + '.'
                        );
                    }

                    if (
                        opts.max !== undefined &&
                        v > opts.max
                    ) {
                        throw new Error(
                            label + ' must be at most ' + opts.max + '.'
                        );
                    }

                    return v;
                },

                rate(value, label, opts = {}) {
                    return this.n(value, label, {
                        min: opts.min ?? -Infinity,
                        max: opts.max ?? Infinity
                    });
                },

                setResult(
                    value,
                    label,
                    formula,
                    steps = [],
                    facts = [],
                    direction = ''
                ) {
                    if (!Number.isFinite(value)) {
                        throw new Error(
                            'The calculation produced an invalid result.'
                        );
                    }

                    this.result = value;
                    this.resultLabel = label;
                    this.resultSummary = label;
                    this.displayResult = this.format(
                        value,
                        this.isMoneyMode()
                    );
                    this.formula = formula;
                    this.steps = steps;
                    this.quickFacts = facts;
                    this.directionIndicator = direction;
                },

                isMoneyMode() {
                    return [
                        'discount',
                        'tax',
                        'tip',
                        'commission',
                        'markup',
                        'margin',
                        'salary',
                        'tax_inclusive'
                    ].includes(this.mode);
                },

                format(value, money = false) {
                    if (!Number.isFinite(Number(value))) {
                        return '—';
                    }

                    let v = Number(value);

                    if (Object.is(v, -0)) {
                        v = 0;
                    }

                    const options = {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: Number(this.precision),
                        useGrouping: Boolean(this.useGrouping)
                    };

                    if (
                        money &&
                        this.currency !== 'NONE'
                    ) {
                        try {
                            return new Intl.NumberFormat(
                                undefined,
                                {
                                    ...options,
                                    style: 'currency',
                                    currency: this.currency
                                }
                            ).format(v);
                        } catch (e) {
                            // Fall back to normal number formatting.
                        }
                    }

                    return new Intl.NumberFormat(
                        undefined,
                        options
                    ).format(v);
                },

                raw(value) {
                    return this.format(value, false);
                },

                pct(value) {
                    return this.format(value, false) + '%';
                },

                calculate_percent_of() {
                    const p = this.n(
                        this.form.percent,
                        'Percentage'
                    );

                    const y = this.n(
                        this.form.value,
                        'Number'
                    );

                    const r = (p / 100) * y;

                    this.setResult(
                        r,
                        `${this.raw(p)}% of ${this.raw(y)}`,
                        `(${this.raw(p)} ÷ 100) × ${this.raw(y)} = ${this.raw(r)}`,
                        [
                            `Convert ${this.raw(p)}% to decimal: ${this.raw(p / 100)}`,
                            `Multiply ${this.raw(p / 100)} × ${this.raw(y)} = ${this.raw(r)}`
                        ],
                        [
                            {
                                label: 'Absolute result',
                                value: this.raw(r)
                            }
                        ],
                        r > 0
                            ? 'increase'
                            : r < 0
                                ? 'decrease'
                                : ''
                    );
                },

                calculate_what_percent() {
                    const a = this.n(
                        this.form.part,
                        'Part'
                    );

                    const b = this.n(
                        this.form.whole,
                        'Whole'
                    );

                    if (b === 0) {
                        throw new Error(
                            'The whole value cannot be zero.'
                        );
                    }

                    const r = (a / b) * 100;

                    this.setResult(
                        r,
                        `${this.raw(a)} is ${this.raw(r)}% of ${this.raw(b)}`,
                        `(${this.raw(a)} ÷ ${this.raw(b)}) × 100 = ${this.pct(r)}`,
                        [
                            `Divide ${this.raw(a)} by ${this.raw(b)} = ${this.raw(a / b)}`,
                            `Multiply by 100 = ${this.pct(r)}`
                        ]
                    );
                },

                calculate_change() {
                    const a = this.n(
                        this.form.oldValue,
                        'Original value'
                    );

                    const b = this.n(
                        this.form.newValue,
                        'New value'
                    );

                    if (a === 0) {
                        throw new Error(
                            'The original value cannot be zero.'
                        );
                    }

                    const d = b - a;

                    const r =
                        (d / Math.abs(a)) * 100;

                    const dir =
                        d > 0
                            ? 'increase'
                            : d < 0
                                ? 'decrease'
                                : '';

                    this.setResult(
                        r,
                        `Percentage ${dir || 'change'}`,
                        `((${this.raw(b)} − ${this.raw(a)}) ÷ |${this.raw(a)}|) × 100 = ${this.pct(r)}`,
                        [
                            `Difference: ${this.raw(b)} − ${this.raw(a)} = ${this.raw(d)}`,
                            `Divide by |${this.raw(a)}| = ${this.raw(d / Math.abs(a))}`,
                            `Multiply by 100 = ${this.pct(r)}`
                        ],
                        [
                            {
                                label: 'Absolute difference',
                                value: this.raw(Math.abs(d))
                            },
                            {
                                label: 'Direction',
                                value: dir || 'No change'
                            }
                        ],
                        dir
                    );

                    this.beforeAfter = {
                        before: a,
                        after: b
                    };

                    this.gaugeValue = Math.min(
                        100,
                        Math.abs(r)
                    );
                },

                calculate_difference() {
                    const a = this.n(
                        this.form.differenceA,
                        'First value'
                    );

                    const b = this.n(
                        this.form.differenceB,
                        'Second value'
                    );

                    const avg =
                        (Math.abs(a) + Math.abs(b)) / 2;

                    if (avg === 0) {
                        throw new Error(
                            'Both values cannot be zero.'
                        );
                    }

                    const d = Math.abs(a - b);

                    const r =
                        d / avg * 100;

                    this.setResult(
                        r,
                        'Percentage difference',
                        `|${this.raw(a)} − ${this.raw(b)}| ÷ ((${this.raw(a)}| + |${this.raw(b)}|) ÷ 2) × 100 = ${this.pct(r)}`,
                        [
                            `Absolute difference = ${this.raw(d)}`,
                            `Average magnitude = ${this.raw(avg)}`,
                            `Difference ÷ average × 100 = ${this.pct(r)}`
                        ],
                        [
                            {
                                label: 'Absolute difference',
                                value: this.raw(d)
                            }
                        ]
                    );
                },

                calculate_increase_decrease() {
                    const a = this.n(
                        this.form.startValue,
                        'Starting value'
                    );

                    const p = this.n(
                        this.form.changePercent,
                        'Percentage'
                    );

                    const amount =
                        a * (p / 100);

                    const r =
                        this.direction === 'increase'
                            ? a + amount
                            : a - amount;

                    const dir =
                        amount > 0
                            ? this.direction === 'increase'
                                ? 'increase'
                                : 'decrease'
                            : '';

                    this.setResult(
                        r,
                        `${this.direction === 'increase' ? 'Increased' : 'Decreased'} value`,
                        `${this.raw(a)} × (1 ${this.direction === 'increase' ? '+' : '−'} ${this.raw(p)} ÷ 100) = ${this.raw(r)}`,
                        [
                            `Percentage amount: ${this.raw(a)} × ${this.raw(p)}% = ${this.raw(amount)}`,
                            `${this.direction === 'increase' ? 'Add' : 'Subtract'} ${this.raw(amount)} ${this.direction === 'increase' ? 'to' : 'from'} ${this.raw(a)}`,
                            `Final value = ${this.raw(r)}`
                        ],
                        [
                            {
                                label: 'Change',
                                value:
                                    (amount >= 0 ? '+' : '') +
                                    this.raw(
                                        this.direction === 'increase'
                                            ? amount
                                            : -amount
                                    )
                            },
                            {
                                label: 'Rate',
                                value: this.pct(p)
                            }
                        ],
                        dir
                    );
                },

                calculate_reverse() {
                    const final = this.n(
                        this.form.finalValue,
                        'Final value'
                    );

                    const p = this.n(
                        this.form.reversePercent,
                        'Percentage'
                    );

                    const m =
                        this.reverseDirection === 'decrease'
                            ? 1 - p / 100
                            : 1 + p / 100;

                    if (m === 0) {
                        throw new Error(
                            'A 100% decrease cannot be reversed.'
                        );
                    }

                    const original =
                        final / m;

                    this.setResult(
                        original,
                        'Original value',
                        `${this.raw(final)} ÷ ${this.raw(m)} = ${this.raw(original)}`,
                        [
                            `Multiplier after ${this.reverseDirection}: ${this.raw(m)}`,
                            `Original value = ${this.raw(final)} ÷ ${this.raw(m)} = ${this.raw(original)}`
                        ],
                        [
                            {
                                label: 'Applied change',
                                value:
                                    this.pct(p) +
                                    ' ' +
                                    this.reverseDirection
                            }
                        ]
                    );
                },

                calculate_discount() {
                    const price = this.n(
                        this.form.price,
                        'Original price'
                    );

                    const p = this.n(
                        this.form.discountPercent,
                        'Discount',
                        {
                            min: 0,
                            max: 100
                        }
                    );

                    const save =
                        price * p / 100;

                    const final =
                        price - save;

                    this.setResult(
                        final,
                        'Final price after discount',
                        `${this.raw(price)} − (${this.raw(price)} × ${this.raw(p)} ÷ 100) = ${this.raw(final)}`,
                        [
                            `Discount amount = ${this.raw(save)}`,
                            `Final price = ${this.raw(price)} − ${this.raw(save)} = ${this.raw(final)}`
                        ],
                        [
                            {
                                label: 'You save',
                                value: this.format(save, true)
                            },
                            {
                                label: 'Discount',
                                value: this.pct(p)
                            }
                        ]
                    );
                },

                calculate_tax() {
                    const amount = this.n(
                        this.form.taxAmount,
                        'Amount'
                    );

                    const p = this.n(
                        this.form.taxPercent,
                        'Tax rate',
                        {
                            min: 0
                        }
                    );

                    if (this.taxDirection === 'add') {
                        const tax =
                            amount * p / 100;

                        const total =
                            amount + tax;

                        this.setResult(
                            total,
                            'Total including tax',
                            `${this.raw(amount)} + ${this.raw(tax)} = ${this.raw(total)}`,
                            [
                                `Tax = ${this.raw(amount)} × ${this.raw(p)}% = ${this.raw(tax)}`,
                                `Total = ${this.raw(total)}`
                            ],
                            [
                                {
                                    label: 'Tax',
                                    value: this.format(tax, true)
                                },
                                {
                                    label: 'Before tax',
                                    value: this.format(amount, true)
                                }
                            ]
                        );

                        return;
                    }

                    const before =
                        amount / (1 + p / 100);

                    const tax =
                        amount - before;

                    this.setResult(
                        before,
                        'Amount before tax',
                        `${this.raw(amount)} ÷ (1 + ${this.raw(p)} ÷ 100) = ${this.raw(before)}`,
                        [
                            `Pre-tax amount = ${this.raw(before)}`,
                            `Tax portion = ${this.raw(tax)}`
                        ],
                        [
                            {
                                label: 'Tax portion',
                                value: this.format(tax, true)
                            },
                            {
                                label: 'Rate',
                                value: this.pct(p)
                            }
                        ]
                    );
                },

                calculate_tip() {
                    const bill = this.n(
                        this.form.tipBill,
                        'Bill'
                    );

                    const p = this.n(
                        this.form.tipPercent,
                        'Tip percentage',
                        {
                            min: 0
                        }
                    );

                    const people = this.n(
                        this.form.tipPeople,
                        'People',
                        {
                            min: 1
                        }
                    );

                    if (!Number.isInteger(people)) {
                        throw new Error(
                            'People must be a whole number.'
                        );
                    }

                    const tip =
                        bill * p / 100;

                    const total =
                        bill + tip;

                    const each =
                        total / people;

                    this.setResult(
                        total,
                        'Total including tip',
                        `${this.raw(bill)} + ${this.raw(tip)} = ${this.raw(total)}`,
                        [
                            `Tip = ${this.raw(tip)}`,
                            `Total = ${this.raw(total)}`,
                            `Per person = ${this.raw(each)}`
                        ],
                        [
                            {
                                label: 'Tip',
                                value: this.format(tip, true)
                            },
                            {
                                label: 'Per person',
                                value: this.format(each, true)
                            }
                        ]
                    );
                },

                calculate_commission() {
                    const sales = this.n(
                        this.form.commissionSales,
                        'Sales amount'
                    );

                    const p = this.n(
                        this.form.commissionPercent,
                        'Commission rate',
                        {
                            min: 0
                        }
                    );

                    const c =
                        sales * p / 100;

                    this.setResult(
                        c,
                        'Commission earned',
                        `${this.raw(sales)} × ${this.raw(p)}% = ${this.raw(c)}`,
                        [
                            'Commission = sales × rate',
                            `${this.raw(sales)} × ${this.raw(p)}% = ${this.raw(c)}`
                        ],
                        [
                            {
                                label: 'Sales',
                                value: this.format(sales, true)
                            },
                            {
                                label: 'Rate',
                                value: this.pct(p)
                            }
                        ]
                    );
                },

                calculate_markup() {
                    const cost = this.n(
                        this.form.cost,
                        'Cost'
                    );

                    const p = this.n(
                        this.form.markupPercent,
                        'Markup rate',
                        {
                            min: -100
                        }
                    );

                    const amount =
                        cost * p / 100;

                    const sell =
                        cost + amount;

                    this.setResult(
                        sell,
                        'Selling price',
                        `${this.raw(cost)} × (1 + ${this.raw(p)} ÷ 100) = ${this.raw(sell)}`,
                        [
                            `Markup amount = ${this.raw(amount)}`,
                            `Selling price = ${this.raw(sell)}`
                        ],
                        [
                            {
                                label: 'Markup',
                                value: this.format(amount, true)
                            },
                            {
                                label: 'Markup rate',
                                value: this.pct(p)
                            }
                        ]
                    );
                },

                calculate_margin() {
                    const cost = this.n(
                        this.form.cost,
                        'Cost'
                    );

                    const sell = this.n(
                        this.form.sellingPrice,
                        'Selling price'
                    );

                    if (sell === 0) {
                        throw new Error(
                            'Selling price cannot be zero.'
                        );
                    }

                    const profit =
                        sell - cost;

                    const margin =
                        profit / sell * 100;

                    const markup =
                        cost === 0
                            ? null
                            : profit / cost * 100;

                    this.setResult(
                        margin,
                        'Profit margin',
                        `(${this.raw(sell)} − ${this.raw(cost)}) ÷ ${this.raw(sell)} × 100 = ${this.pct(margin)}`,
                        [
                            `Profit = ${this.raw(profit)}`,
                            `Margin = profit ÷ selling price × 100 = ${this.pct(margin)}`,
                            markup === null
                                ? 'Markup is undefined when cost is zero.'
                                : `Markup = profit ÷ cost × 100 = ${this.pct(markup)}`
                        ],
                        [
                            {
                                label: 'Profit',
                                value: this.format(profit, true)
                            },
                            {
                                label: 'Markup',
                                value:
                                    markup === null
                                        ? '—'
                                        : this.pct(markup)
                            }
                        ]
                    );
                },

                calculate_exam() {
                    const obtained = this.n(
                        this.form.obtained,
                        'Marks obtained'
                    );

                    const total = this.n(
                        this.form.totalMarks,
                        'Total marks',
                        {
                            min: 0
                        }
                    );

                    if (total <= 0) {
                        throw new Error(
                            'Total marks must be greater than zero.'
                        );
                    }

                    if (obtained < 0) {
                        throw new Error(
                            'Marks obtained cannot be negative.'
                        );
                    }

                    const p =
                        obtained / total * 100;

                    let grade = '';

                    if (this.gradeScale === 'pakistan') {
                        grade =
                            p >= 80
                                ? 'A+'
                                : p >= 70
                                    ? 'A'
                                    : p >= 60
                                        ? 'B'
                                        : p >= 50
                                            ? 'C'
                                            : p >= 40
                                                ? 'D'
                                                : 'F';
                    } else {
                        grade =
                            p >= 90
                                ? 'A+'
                                : p >= 80
                                    ? 'A'
                                    : p >= 70
                                        ? 'B'
                                        : p >= 60
                                            ? 'C'
                                            : p >= 50
                                                ? 'D'
                                                : 'F';
                    }

                    const target =
                        this.form.targetPercent === ''
                            ? null
                            : this.n(
                                this.form.targetPercent,
                                'Target percentage',
                                {
                                    min: 0
                                }
                            );

                    const needed =
                        target === null
                            ? null
                            : total * target / 100 - obtained;

                    this.setResult(
                        p,
                        'Exam percentage',
                        `(${this.raw(obtained)} ÷ ${this.raw(total)}) × 100 = ${this.pct(p)}`,
                        [
                            `Divide marks obtained by total marks = ${this.raw(obtained / total)}`,
                            `Multiply by 100 = ${this.pct(p)}`,
                            `Grade estimate = ${grade}`,
                            ...(target === null
                                ? []
                                : [
                                    needed > 0
                                        ? 'Marks needed to reach ' +
                                          this.pct(target) +
                                          ' = ' +
                                          this.raw(needed)
                                        : 'Target of ' +
                                          this.pct(target) +
                                          ' is already reached.'
                                ])
                        ],
                        [
                            {
                                label: 'Grade',
                                value: grade
                            },
                            {
                                label: 'Marks',
                                value:
                                    `${this.raw(obtained)} / ${this.raw(total)}`
                            },
                            {
                                label: 'Target',
                                value:
                                    target === null
                                        ? '—'
                                        : this.pct(target)
                            }
                        ]
                    );
                },

                calculate_salary() {
                    const salary = this.n(
                        this.form.salary,
                        'Current salary'
                    );

                    const p = this.n(
                        this.form.salaryPercent,
                        'Salary change percentage',
                        {
                            min: 0
                        }
                    );

                    const change =
                        salary * p / 100;

                    const final =
                        this.salaryDirection === 'increase'
                            ? salary + change
                            : salary - change;

                    this.setResult(
                        final,
                        'New salary',
                        `${this.raw(salary)} ${this.salaryDirection === 'increase' ? '+' : '−'} ${this.raw(change)} = ${this.raw(final)}`,
                        [
                            `Change amount = ${this.raw(salary)} × ${this.raw(p)}% = ${this.raw(change)}`,
                            `New salary = ${this.raw(final)}`
                        ],
                        [
                            {
                                label: 'Change',
                                value: this.format(change, true)
                            },
                            {
                                label: 'Rate',
                                value: this.pct(p)
                            }
                        ],
                        this.salaryDirection === 'increase'
                            ? 'increase'
                            : 'decrease'
                    );
                },

                calculate_sequential() {
                    let current = this.n(
                        this.form.sequentialStart,
                        'Starting value'
                    );

                    const start = current;

                    this.sequentialResults = [
                        {
                            label: 'Start',
                            result: this.format(current)
                        }
                    ];

                    this.sequentialChanges.forEach(
                        (item, index) => {
                            const p = this.n(
                                item.percent,
                                'Change ' + (index + 1),
                                {
                                    min: 0
                                }
                            );

                            const amount =
                                current * p / 100;

                            current =
                                item.direction === 'increase'
                                    ? current + amount
                                    : current - amount;

                            this.sequentialResults.push({
                                label:
                                    (item.direction === 'increase'
                                        ? '+'
                                        : '−') +
                                    this.raw(p) +
                                    '%',

                                result:
                                    this.format(current)
                            });
                        }
                    );

                    const net =
                        (current - start) /
                        Math.abs(start) *
                        100;

                    this.setResult(
                        current,
                        'Final sequential value',
                        `Start ${this.raw(start)} → final ${this.raw(current)}`,
                        [
                            'Each percentage is applied to the previous result, not the original value.',
                            `Net change = ${this.pct(net)}`
                        ],
                        [
                            {
                                label: 'Net change',
                                value: this.pct(net)
                            },
                            {
                                label: 'Start',
                                value: this.raw(start)
                            }
                        ],
                        net > 0
                            ? 'increase'
                            : net < 0
                                ? 'decrease'
                                : ''
                    );
                },

                calculate_compare() {
                    this.comparisonResults = [];

                    this.scenarios.forEach(s => {
                        const start = this.n(
                            s.start,
                            s.label + ' starting value'
                        );

                        const p = this.n(
                            s.percent,
                            s.label + ' percentage',
                            {
                                min: 0
                            }
                        );

                        const amount =
                            start * p / 100;

                        const final =
                            s.direction === 'increase'
                                ? start + amount
                                : start - amount;

                        this.comparisonResults.push({
                            label: s.label,
                            result: this.format(final, true),
                            value: final
                        });
                    });

                    const fa =
                        this.comparisonResults[0].value;

                    const fb =
                        this.comparisonResults[1].value;

                    const difference =
                        Math.abs(fa - fb);

                    this.setResult(
                        difference,
                        'Scenario difference',
                        `|${this.format(fa)} − ${this.format(fb)}| = ${this.format(difference)}`,
                        [
                            `Scenario A final = ${this.format(fa)}`,
                            `Scenario B final = ${this.format(fb)}`,
                            `Absolute difference = ${this.format(difference)}`
                        ],
                        [
                            {
                                label: 'Scenario A',
                                value: this.format(fa, true)
                            },
                            {
                                label: 'Scenario B',
                                value: this.format(fb, true)
                            }
                        ]
                    );
                },

                calculate_percentage_points() {
                    const a = this.n(
                        this.form.pointsBefore,
                        'First rate'
                    );

                    const b = this.n(
                        this.form.pointsAfter,
                        'Second rate'
                    );

                    const points =
                        b - a;

                    if (a === 0) {
                        throw new Error(
                            'The first rate cannot be zero when calculating relative change.'
                        );
                    }

                    const relative =
                        points / Math.abs(a) * 100;

                    this.setResult(
                        points,
                        'Percentage-point difference',
                        `${this.raw(b)}% − ${this.raw(a)}% = ${this.raw(points)} percentage points`,
                        [
                            `Percentage-point difference = ${this.raw(points)} points`,
                            `Relative percentage change = ${this.pct(relative)}`
                        ],
                        [
                            {
                                label: 'Relative change',
                                value: this.pct(relative)
                            },
                            {
                                label: 'Direction',
                                value:
                                    points > 0
                                        ? 'increase'
                                        : points < 0
                                            ? 'decrease'
                                            : 'no change'
                            }
                        ],
                        points > 0
                            ? 'increase'
                            : points < 0
                                ? 'decrease'
                                : ''
                    );
                },

                calculate_tax_inclusive() {
                    const amount = this.n(
                        this.form.inclusiveAmount,
                        'Amount'
                    );

                    const p = this.n(
                        this.form.inclusiveTaxPercent,
                        'Tax rate',
                        {
                            min: 0
                        }
                    );

                    if (
                        this.taxInclusiveDirection === 'extract'
                    ) {
                        const before =
                            amount / (1 + p / 100);

                        const tax =
                            amount - before;

                        this.setResult(
                            before,
                            'Tax-exclusive amount',
                            `${this.raw(amount)} ÷ (1 + ${this.raw(p)} ÷ 100) = ${this.raw(before)}`,
                            [
                                `Pre-tax amount = ${this.raw(before)}`,
                                `Tax portion = ${this.raw(tax)}`,
                                `Inclusive total = ${this.raw(amount)}`
                            ],
                            [
                                {
                                    label: 'Tax',
                                    value: this.format(tax, true)
                                },
                                {
                                    label: 'Rate',
                                    value: this.pct(p)
                                }
                            ]
                        );
                    } else {
                        const tax =
                            amount * p / 100;

                        const total =
                            amount + tax;

                        this.setResult(
                            total,
                            'Tax-inclusive amount',
                            `${this.raw(amount)} + (${this.raw(amount)} × ${this.raw(p)} ÷ 100) = ${this.raw(total)}`,
                            [
                                `Tax = ${this.raw(amount)} × ${this.raw(p)}% = ${this.raw(tax)}`,
                                `Inclusive total = ${this.raw(total)}`
                            ],
                            [
                                {
                                    label: 'Tax',
                                    value: this.format(tax, true)
                                },
                                {
                                    label: 'Exclusive amount',
                                    value: this.format(amount, true)
                                }
                            ]
                        );
                    }
                },

                applyTaxPreset() {
                    const p =
                        this.taxPresets.find(
                            x => x.id === this.taxPreset
                        );

                    if (p) {
                        this.form.taxPercent = p.rate;
                        this.form.inclusiveTaxPercent = p.rate;
                        this.calculateLive();
                    }
                },

                detectMode() {
                    const q =
                        this.smartInput
                            .trim()
                            .toLowerCase();

                    if (!q) {
                        this.detectionMessage =
                            'Enter a short percentage question to detect a mode.';

                        return;
                    }

                    let id = 'percent_of';

                    if (
                        /what\s+percent|what percentage|is .*%? of/.test(q)
                    ) {
                        id = 'what_percent';
                    } else if (/discount|sale|off/.test(q)) {
                        id = 'discount';
                    } else if (/tax|vat|gst/.test(q)) {
                        id = 'tax';
                    } else if (/tip|gratuity/.test(q)) {
                        id = 'tip';
                    } else if (
                        /increase|decrease|raise|lower/.test(q)
                    ) {
                        id = 'increase_decrease';
                    } else if (/difference/.test(q)) {
                        id = 'difference';
                    } else if (/change/.test(q)) {
                        id = 'change';
                    } else if (/commission/.test(q)) {
                        id = 'commission';
                    } else if (/markup/.test(q)) {
                        id = 'markup';
                    } else if (/margin/.test(q)) {
                        id = 'margin';
                    } else if (/salary|pay raise/.test(q)) {
                        id = 'salary';
                    } else if (/marks|exam|grade/.test(q)) {
                        id = 'exam';
                    } else if (/original|reverse|before/.test(q)) {
                        id = 'reverse';
                    }

                    this.setMode(id);

                    this.detectionMessage =
                        'Detected: ' +
                        this.modes.find(
                            x => x.id === id
                        ).label +
                        '. Enter the values below.';
                },

                loadExample() {
                    const examples = {
                        percent_of: {
                            percent: 15,
                            value: 240
                        },

                        what_percent: {
                            part: 30,
                            whole: 120
                        },

                        change: {
                            oldValue: 80,
                            newValue: 100
                        },

                        difference: {
                            differenceA: 80,
                            differenceB: 100
                        },

                        increase_decrease: {
                            startValue: 500,
                            changePercent: 20
                        },

                        reverse: {
                            finalValue: 80,
                            reversePercent: 20
                        },

                        discount: {
                            price: 240,
                            discountPercent: 15
                        },

                        tax: {
                            taxAmount: 1000,
                            taxPercent: 18
                        },

                        tip: {
                            tipBill: 100,
                            tipPercent: 15,
                            tipPeople: 2
                        },

                        commission: {
                            commissionSales: 10000,
                            commissionPercent: 5
                        },

                        markup: {
                            cost: 100,
                            markupPercent: 25
                        },

                        margin: {
                            cost: 100,
                            sellingPrice: 125
                        },

                        tax_inclusive: {
                            inclusiveAmount: 1180,
                            inclusiveTaxPercent: 18
                        },

                        exam: {
                            obtained: 720,
                            totalMarks: 1000,
                            targetPercent: 80
                        },

                        salary: {
                            salary: 100000,
                            salaryPercent: 10
                        },

                        percentage_points: {
                            pointsBefore: 40,
                            pointsAfter: 55
                        },

                        sequential: {
                            sequentialStart: 1000
                        },

                        compare: {}
                    };

                    if (examples[this.mode]) {
                        Object.assign(
                            this.form,
                            examples[this.mode]
                        );
                    }

                    this.lastAction = 'example';

                    this.calculate(true);
                },

                reset() {
                    Object.keys(this.form).forEach(
                        key => this.form[key] = ''
                    );

                    this.form.taxPercent = 18;
                    this.form.tipPercent = 15;
                    this.form.tipPeople = 1;
                    this.form.inclusiveTaxPercent = 18;

                    this.taxInclusiveDirection = 'extract';

                    this.form.sequentialStart = 1000;

                    this.sequentialChanges = [
                        {
                            direction: 'increase',
                            percent: 10
                        },
                        {
                            direction: 'decrease',
                            percent: 5
                        },
                        {
                            direction: 'increase',
                            percent: 10
                        }
                    ];

                    this.scenarios = [
                        {
                            id: 'a',
                            label: 'Scenario A',
                            start: 1000,
                            percent: 10,
                            direction: 'increase'
                        },
                        {
                            id: 'b',
                            label: 'Scenario B',
                            start: 1000,
                            percent: 15,
                            direction: 'increase'
                        }
                    ];

                    this.smartInput = '';
                    this.taxPreset = '';
                    this.lastAction = 'reset';

                    this.clearResult();
                    this.saveState();
                },

                addHistory() {
                    const item = {
                        id: Date.now() + Math.random(),
                        mode: this.currentMode.label,
                        summary: this.resultLabel,
                        result: this.displayResult,
                        state: this.serializableState()
                    };

                    this.history = [
                        item,
                        ...this.history.filter(
                            x =>
                                x.mode !== item.mode ||
                                x.result !== item.result
                        )
                    ].slice(0, 10);

                    this.persistHistory();
                },

                restoreHistory(item) {
                    if (item.state) {
                        this.applyState(item.state);
                        this.calculate(false);
                    }
                },

                serializableState() {
                    return {
                        mode: this.mode,
                        form: this.form,
                        precision: this.precision,
                        currency: this.currency,
                        useGrouping: this.useGrouping,
                        direction: this.direction,
                        reverseDirection: this.reverseDirection,
                        taxDirection: this.taxDirection,
                        taxInclusiveDirection: this.taxInclusiveDirection,
                        salaryDirection: this.salaryDirection,
                        gradeScale: this.gradeScale,
                        sequentialChanges: this.sequentialChanges,
                        scenarios: this.scenarios
                    };
                },

                applyState(s) {
                    if (!s) {
                        return;
                    }

                    this.mode =
                        s.mode || this.mode;

                    Object.assign(
                        this.form,
                        s.form || {}
                    );

                    [
                        'precision',
                        'currency',
                        'useGrouping',
                        'direction',
                        'reverseDirection',
                        'taxDirection',
                        'taxInclusiveDirection',
                        'salaryDirection',
                        'gradeScale'
                    ].forEach(key => {
                        if (s[key] !== undefined) {
                            this[key] = s[key];
                        }
                    });

                    if (
                        Array.isArray(
                            s.sequentialChanges
                        )
                    ) {
                        this.sequentialChanges =
                            s.sequentialChanges;
                    }

                    if (
                        Array.isArray(s.scenarios)
                    ) {
                        this.scenarios =
                            s.scenarios;
                    }
                },

                saveState() {
                    try {
                        localStorage.setItem(
                            'aabitech_percentage_state',
                            JSON.stringify(
                                this.serializableState()
                            )
                        );

                        this.persistHistory();
                    } catch (e) {
                        // Storage can be unavailable in some privacy modes.
                    }
                },

                persistHistory() {
                    try {
                        localStorage.setItem(
                            'aabitech_percentage_history',
                            JSON.stringify(this.history)
                        );
                    } catch (e) {
                        // Ignore storage restrictions.
                    }
                },

                loadState() {
                    try {
                        const s =
                            JSON.parse(
                                localStorage.getItem(
                                    'aabitech_percentage_state'
                                ) || 'null'
                            );

                        if (s) {
                            this.applyState(s);
                        }

                        const h =
                            JSON.parse(
                                localStorage.getItem(
                                    'aabitech_percentage_history'
                                ) || '[]'
                            );

                        if (Array.isArray(h)) {
                            this.history =
                                h.slice(0, 10);
                        }
                    } catch (e) {
                        this.history = [];
                    }
                },

                shareState() {
                    try {
                        const json =
                            JSON.stringify(
                                this.serializableState()
                            );

                        const bytes =
                            new TextEncoder().encode(json);

                        let binary = '';

                        bytes.forEach(
                            b =>
                                binary +=
                                String.fromCharCode(b)
                        );

                        const token =
                            btoa(binary)
                                .replace(/\+/g, '-')
                                .replace(/\//g, '_')
                                .replace(/=+$/, '');

                        const url =
                            location.origin +
                            location.pathname +
                            '#p=' +
                            token;

                        if (
                            navigator.clipboard &&
                            typeof navigator.clipboard.writeText === 'function'
                        ) {
                            navigator.clipboard.writeText(url);
                        }

                        history.replaceState(
                            null,
                            '',
                            location.pathname +
                            '#p=' +
                            token
                        );

                        this.detectionMessage =
                            'Shareable calculation link copied.';
                    } catch (e) {
                        this.error =
                            'Unable to create a shareable link in this browser.';
                    }
                },

                loadSharedState() {
                    try {
                        const hash =
                            location.hash;

                        if (!hash.startsWith('#p=')) {
                            return;
                        }

                        let token =
                            hash
                                .slice(3)
                                .replace(/-/g, '+')
                                .replace(/_/g, '/');

                        token +=
                            '='.repeat(
                                (4 - token.length % 4) % 4
                            );

                        const binary =
                            atob(token);

                        const bytes =
                            Uint8Array.from(
                                binary,
                                c =>
                                    c.charCodeAt(0)
                            );

                        const state =
                            JSON.parse(
                                new TextDecoder().decode(bytes)
                            );

                        this.applyState(state);

                        this.detectionMessage =
                            'Shared calculation loaded.';
                    } catch (e) {
                        this.error =
                            'The shared calculation link is invalid or incomplete.';
                    }
                },

                handleShortcut(event) {
                    if (
                        event.target?.matches(
                            'input,select,textarea'
                        )
                    ) {
                        if (
                            event.key === 'Enter' &&
                            (event.ctrlKey || event.metaKey)
                        ) {
                            event.preventDefault();
                            this.calculate(true);
                        } else if (
                            event.key === 'Enter' &&
                            !event.shiftKey &&
                            event.target.tagName === 'INPUT'
                        ) {
                            event.preventDefault();
                            this.calculate(false);
                        }

                        return;
                    }

                    if (event.key === 'Escape') {
                        this.clearResult();
                    }
                }
            };
        };
    </script>
    @endscript
</div>