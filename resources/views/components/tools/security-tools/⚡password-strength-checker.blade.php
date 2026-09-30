<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiPasswordStrengthChecker()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full"
>
    {{-- ============================================================
        PRIVACY STATUS
    ============================================================= --}}
    <section class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0 text-base">🔒</div>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-semibold text-emerald-900">
                        Local password analysis
                    </span>

                    <span
                        class="rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700"
                        x-text="privacyStatus"
                    ></span>
                </div>

                <p class="mt-1 text-xs leading-5 text-emerald-800">
                    Your password is analyzed in this browser. It is not stored
                    in localStorage, sessionStorage, URLs, Livewire state, or
                    sent to AabiTech during local analysis.
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================
        MAIN WORKSPACE
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="grid lg:grid-cols-2">

            {{-- ========================================================
                INPUT / CONFIGURATION
            ========================================================= --}}
            <div class="border-b border-slate-200 p-5 lg:border-b-0 lg:border-r sm:p-7">

                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-slate-900">
                        Test your password
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Analyze length, composition, patterns and practical
                        guessing resistance.
                    </p>
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="aabi-password-input"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Password
                    </label>

                    <div class="relative">
                        <input
                            id="aabi-password-input"
                            x-ref="passwordInput"
                            :type="showPassword ? 'text' : 'password'"
                            x-model="password"
                            @input="analyze()"
                            autocomplete="new-password"
                            autocapitalize="off"
                            autocorrect="off"
                            spellcheck="false"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 pr-12 font-mono text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            placeholder="Enter a password to check..."
                        >

                        <button
                            type="button"
                            @click="togglePasswordVisibility()"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >
                            <span
                                class="text-sm"
                                x-text="showPassword ? '◉' : '○'"
                            ></span>
                        </button>
                    </div>

                    <div class="mt-2 flex items-center justify-between gap-3">
                        <span
                            class="text-xs text-slate-400"
                            x-text="passwordLengthLabel"
                        ></span>

                        <button
                            type="button"
                            @click="clearPassword()"
                            class="text-xs font-medium text-slate-500 hover:text-red-600"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="mt-4 flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="loadExample()"
                        data-active-group="password-action"
                        data-active-value="example"
                        :class="{ 'is-active': lastAction === 'example' }"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Example
                    </button>

                    <button
                        type="button"
                        @click="generateStrongPassword()"
                        data-active-group="password-action"
                        data-active-value="generate"
                        :class="{ 'is-active': lastAction === 'generate' }"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Generate Strong
                    </button>

                    <button
                        type="button"
                        @click="saveComparisonBaseline()"
                        data-active-group="password-action"
                        data-active-value="compare"
                        :class="{ 'is-active': lastAction === 'compare' }"
                        :disabled="!password"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Save for Comparison
                    </button>
                </div>

                {{-- Strength --}}
                <div class="mt-7">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Practical strength
                            </div>

                            <div
                                class="mt-1 text-sm font-medium"
                                :class="strengthTextClass"
                                x-text="strengthLabel"
                            ></div>
                        </div>

                        <div
                            class="text-2xl font-bold"
                            :class="strengthTextClass"
                            x-text="score + '/100'"
                        ></div>
                    </div>

                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full transition-all duration-300"
                            :class="strengthBarClass"
                            :style="'width:' + score + '%'"
                        ></div>
                    </div>

                    <p
                        class="mt-2 text-xs leading-5 text-slate-500"
                        x-text="summary"
                    ></p>
                </div>

                {{-- Basic checklist --}}
                <div class="mt-7">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-900">
                            Password checklist
                        </h3>

                        <span
                            class="text-xs font-medium text-slate-400"
                            x-text="passedChecklist + '/' + checklist.length"
                        ></span>
                    </div>

                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        <template x-for="item in checklist" :key="item.key">
                            <div
                                class="flex items-center gap-2 rounded-lg border px-3 py-2.5 text-sm"
                                :class="item.pass
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
                                    : 'border-slate-200 bg-slate-50 text-slate-500'"
                            >
                                <span
                                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                                    :class="item.pass
                                        ? 'bg-emerald-500 text-white'
                                        : 'bg-slate-200 text-slate-500'"
                                    x-text="item.pass ? '✓' : '–'"
                                ></span>

                                <span x-text="item.label"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Password policy --}}
                <div class="mt-7 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Password policy
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Simulate an organization's password requirements.
                            </p>
                        </div>

                        <span
                            class="rounded-full px-2.5 py-1 text-[11px] font-semibold"
                            :class="policyPassed
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-amber-100 text-amber-700'"
                            x-text="policyPassed ? 'Pass' : 'Review'"
                        ></span>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-1 block text-xs font-medium text-slate-600">
                                Minimum length
                            </span>

                            <input
                                type="number"
                                min="1"
                                max="128"
                                x-model.number="policy.minLength"
                                @input="analyze()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >
                        </label>

                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input
                                type="checkbox"
                                x-model="policy.requireUpper"
                                @change="analyze()"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-600">
                                Uppercase
                            </span>
                        </label>

                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input
                                type="checkbox"
                                x-model="policy.requireLower"
                                @change="analyze()"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-600">
                                Lowercase
                            </span>
                        </label>

                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input
                                type="checkbox"
                                x-model="policy.requireNumber"
                                @change="analyze()"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-600">
                                Number
                            </span>
                        </label>

                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input
                                type="checkbox"
                                x-model="policy.requireSymbol"
                                @change="analyze()"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-600">
                                Symbol
                            </span>
                        </label>

                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2">
                            <input
                                type="checkbox"
                                x-model="policy.blockCommon"
                                @change="analyze()"
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-600">
                                Block common passwords
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ========================================================
                ANALYSIS
            ========================================================= --}}
            <div class="bg-slate-50/70 p-5 sm:p-7">

                <div class="mb-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                Security analysis
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Explainable analysis of the password.
                            </p>
                        </div>

                        <span
                            class="rounded-full border px-2.5 py-1 text-[11px] font-semibold"
                            :class="breachStatusClass"
                            x-text="breachStatus"
                        ></span>
                    </div>
                </div>

                {{-- Summary --}}
                <div
                    class="rounded-2xl border p-5"
                    :class="resultPanelClass"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold uppercase tracking-wider opacity-70">
                                Assessment
                            </div>

                            <div
                                class="mt-1 text-xl font-bold"
                                x-text="strengthLabel"
                            ></div>

                            <p
                                class="mt-2 text-sm leading-6 opacity-80"
                                x-text="summary"
                            ></p>
                        </div>

                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full border-4 bg-white text-xl font-bold shadow-sm"
                            :class="strengthBorderClass"
                            x-text="score"
                        ></div>
                    </div>
                </div>

                {{-- Core metrics --}}
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="text-xs text-slate-400">Length</div>
                        <div
                            class="mt-1 text-xl font-bold text-slate-900"
                            x-text="passwordLength"
                        ></div>
                        <div class="text-xs text-slate-400">characters</div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="text-xs text-slate-400">Raw entropy</div>
                        <div
                            class="mt-1 text-xl font-bold text-slate-900"
                            x-text="entropy + ' bits'"
                        ></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="text-xs text-slate-400">Practical entropy</div>
                        <div
                            class="mt-1 text-xl font-bold text-slate-900"
                            x-text="practicalEntropy + ' bits'"
                        ></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="text-xs text-slate-400">Guesses required</div>
                        <div
                            class="mt-1 text-xl font-bold text-slate-900"
                            x-text="formatGuessCount(estimatedGuesses)"
                        ></div>
                    </div>
                </div>

                {{-- Character composition --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-900">
                            Character composition
                        </h3>

                        <span
                            class="text-xs text-slate-400"
                            x-text="uniqueCharacters + ' unique'"
                        ></span>
                    </div>

                    <div class="mt-4 space-y-3">
                        <template x-for="item in composition" :key="item.key">
                            <div>
                                <div class="mb-1 flex justify-between text-xs">
                                    <span class="text-slate-500" x-text="item.label"></span>

                                    <span
                                        class="font-semibold text-slate-700"
                                        x-text="item.count"
                                    ></span>
                                </div>

                                <div class="h-2 rounded-full bg-slate-100">
                                    <div
                                        class="h-2 rounded-full bg-indigo-500 transition-all"
                                        :style="'width:' + percentage(item.count) + '%'"
                                    ></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Detected weaknesses --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-sm font-semibold text-slate-900">
                            Weakness breakdown
                        </h3>

                        <span
                            class="text-xs text-slate-400"
                            x-text="weaknesses.length + ' detected'"
                        ></span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <template x-if="weaknesses.length === 0">
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-3 text-sm text-emerald-800">
                                No major predictable pattern was detected by the local heuristic checks.
                            </div>
                        </template>

                        <template x-for="weakness in weaknesses" :key="weakness.key">
                            <div class="flex gap-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-3">
                                <span class="shrink-0 text-amber-600">!</span>

                                <div class="min-w-0">
                                    <div
                                        class="text-sm font-semibold text-amber-900"
                                        x-text="weakness.title"
                                    ></div>

                                    <p
                                        class="mt-0.5 text-xs leading-5 text-amber-800"
                                        x-text="weakness.detail"
                                    ></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Pattern visualization --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Pattern visualization
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Detected character patterns are highlighted without sending the password anywhere.
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-4 min-h-12 overflow-x-auto rounded-lg border border-slate-200 bg-slate-50 p-3 font-mono text-sm"
                    >
                        <template x-if="!password">
                            <span class="text-slate-400">
                                Enter a password to visualize detected patterns.
                            </span>
                        </template>

                        <template x-if="password">
                            <div class="whitespace-nowrap" aria-label="Password pattern visualization">
                                <template x-for="part in patternVisualization" :key="part.id">
                                    <span
                                        class="rounded px-0.5"
                                        :class="part.className"
                                        x-text="part.text"
                                        :title="part.label"
                                    ></span>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2 text-[11px]">
                        <span class="rounded bg-slate-100 px-2 py-1 text-slate-500">
                            Normal
                        </span>

                        <span class="rounded bg-amber-100 px-2 py-1 text-amber-700">
                            Predictable
                        </span>

                        <span class="rounded bg-red-100 px-2 py-1 text-red-700">
                            Weak pattern
                        </span>
                    </div>
                </div>

                {{-- Attack assumptions --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Attack scenarios
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                These are mathematical estimates based on configurable guesses-per-second assumptions.
                                They are not predictions of a real attack.
                            </p>
                        </div>

                        <label class="flex items-center gap-2 text-xs text-slate-600">
                            <span>Custom</span>

                            <input
                                type="number"
                                min="1"
                                step="1"
                                x-model.number="attack.customRate"
                                @input="analyze()"
                                class="w-28 rounded-lg border border-slate-300 px-2 py-1.5 text-xs outline-none focus:border-indigo-500"
                            >

                            <span>/sec</span>
                        </label>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">
                                Online throttled
                            </div>

                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="formatDuration(estimateTime(attack.onlineRate))"
                            ></div>

                            <div
                                class="mt-1 text-[11px] text-slate-400"
                                x-text="formatRate(attack.onlineRate)"
                            ></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">
                                Offline fast hash
                            </div>

                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="formatDuration(estimateTime(attack.offlineRate))"
                            ></div>

                            <div
                                class="mt-1 text-[11px] text-slate-400"
                                x-text="formatRate(attack.offlineRate)"
                            ></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">
                                Custom scenario
                            </div>

                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="formatDuration(estimateTime(attack.customRate))"
                            ></div>

                            <div
                                class="mt-1 text-[11px] text-slate-400"
                                x-text="formatRate(attack.customRate)"
                            ></div>
                        </div>
                    </div>
                </div>

                {{-- Passphrase analysis --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            Passphrase analysis
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            A rough analysis based on whitespace-separated words.
                        </p>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">Words</div>
                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="passphrase.wordCount"
                            ></div>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">Unique words</div>
                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="passphrase.uniqueWords"
                            ></div>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">Separator</div>
                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="passphrase.separatorQuality"
                            ></div>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-3">
                            <div class="text-xs text-slate-400">Assessment</div>
                            <div
                                class="mt-1 font-semibold text-slate-900"
                                x-text="passphrase.assessment"
                            ></div>
                        </div>
                    </div>
                </div>

                {{-- Breach check --}}
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Optional breach check
                            </h3>

                            <p class="mt-1 max-w-xl text-xs leading-5 text-slate-500">
                                Uses the Have I Been Pwned Pwned Passwords range
                                API. The password itself is never sent. Only the
                                first five characters of a locally calculated SHA-1
                                hash are requested.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="checkBreach()"
                            :disabled="!password || breachChecking"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span x-text="breachChecking ? 'Checking…' : 'Check breach exposure'"></span>
                        </button>
                    </div>

                    <div
                        x-show="breachResult"
                        x-transition
                        class="mt-4 rounded-lg border px-3 py-3 text-sm"
                        :class="breachResultClass"
                        x-text="breachResult"
                    ></div>
                </div>

                {{-- Copy report --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="copyText(analysisText, 'analysis')"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-700"
                    >
                        <span x-text="copiedType === 'analysis' ? '✓ Copied' : 'Copy Analysis'"></span>
                    </button>

                    <button
                        type="button"
                        @click="copyText(recommendationsText, 'recommendations')"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-700"
                    >
                        <span x-text="copiedType === 'recommendations' ? '✓ Copied' : 'Copy Recommendations'"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
        RECOMMENDATIONS
    ============================================================= --}}
    <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 sm:p-7">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    How to improve this password
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Suggestions update automatically as the password changes.
                </p>
            </div>

            <span
                class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700"
                x-text="recommendations.length + ' suggestions'"
            ></span>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <template x-for="recommendation in recommendations" :key="recommendation">
                <div class="flex gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                        →
                    </span>

                    <p
                        class="text-sm leading-6 text-slate-600"
                        x-text="recommendation"
                    ></p>
                </div>
            </template>
        </div>
    </section>

    {{-- ============================================================
        BEFORE / AFTER COMPARISON
    ============================================================= --}}
    <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 sm:p-7">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    Before / after comparison
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Save a baseline and compare a later password without storing
                    either password persistently.
                </p>
            </div>

            <button
                type="button"
                @click="saveComparisonBaseline()"
                :disabled="!password"
                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Save current
            </button>
        </div>

        <div class="mt-5 grid gap-3 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-400">Baseline</div>

                <div
                    class="mt-1 font-semibold text-slate-900"
                    x-text="comparison.hasBaseline ? comparison.baselineScore + '/100' : 'Not saved'"
                ></div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-400">Current</div>

                <div
                    class="mt-1 font-semibold text-slate-900"
                    x-text="password ? score + '/100' : '—'"
                ></div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-400">Change</div>

                <div
                    class="mt-1 font-semibold text-slate-900"
                    x-text="comparison.hasBaseline && password
                        ? signedNumber(score - comparison.baselineScore)
                        : '—'"
                ></div>
            </div>
        </div>

        <div
            x-show="comparison.hasBaseline && password"
            x-transition
            class="mt-4 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600"
            x-text="comparisonMessage"
        ></div>
    </section>

    {{-- ============================================================
        SHORTCUTS / TOOL STATUS
    ============================================================= --}}
    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-[11px] text-slate-400">
        <div>
            <span class="font-medium text-slate-500">Shortcuts:</span>
            Ctrl/Cmd + Enter = focus password · Esc = clear
        </div>

        <div>
            Client-side analysis · No password persistence
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-show="toast"
        x-transition
        class="fixed bottom-5 right-5 z-50 rounded-xl border border-slate-200 bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-lg"
        x-text="toast"
    ></div>
</div>

@script
<script>
    window.aabiPasswordStrengthChecker = function () {
        return {
            password: '',
            showPassword: false,

            score: 0,
            entropy: 0,
            practicalEntropy: 0,
            estimatedGuesses: 0,

            uppercaseCount: 0,
            lowercaseCount: 0,
            numberCount: 0,
            symbolCount: 0,
            uniqueCharacters: 0,
            poolSize: 0,

            strengthLabel: 'Enter a password',
            summary: 'Start typing to analyze the password.',
            crackTime: '—',

            lastAction: null,
            copiedType: null,
            toast: '',
            toastTimer: null,

            privacyStatus: 'Local only',

            weaknesses: [],
            patternVisualization: [],

            breachChecking: false,
            breachStatus: 'Not checked',
            breachResult: '',
            breachCount: 0,

            policy: {
                minLength: 12,
                requireUpper: true,
                requireLower: true,
                requireNumber: true,
                requireSymbol: true,
                blockCommon: true,
            },

            policyPassed: false,

            attack: {
                onlineRate: 10,
                offlineRate: 10000000000,
                customRate: 1000000,
            },

            comparison: {
                hasBaseline: false,
                baselineScore: 0,
                baselineEntropy: 0,
                baselineLength: 0,
            },

            passphrase: {
                wordCount: 0,
                uniqueWords: 0,
                separatorQuality: '—',
                assessment: 'Not enough data',
            },

            commonPasswords: [
                'password',
                'password1',
                'password123',
                '123456',
                '1234567',
                '12345678',
                '123456789',
                '1234567890',
                'qwerty',
                'qwerty123',
                'abc123',
                'admin',
                'admin123',
                'administrator',
                'welcome',
                'welcome1',
                'letmein',
                'monkey',
                'dragon',
                'football',
                'baseball',
                'iloveyou',
                'princess',
                'sunshine',
                'master',
                'login',
                'secret',
                'passw0rd',
                'p@ssword',
                'password!',
                'test',
                'test123',
                'guest',
                'root',
                'changeme',
                'default',
            ],

            dictionaryWords: [
                'password',
                'admin',
                'administrator',
                'welcome',
                'login',
                'secret',
                'summer',
                'winter',
                'spring',
                'autumn',
                'football',
                'cricket',
                'computer',
                'internet',
                'school',
                'college',
                'university',
                'teacher',
                'student',
                'family',
                'friend',
                'love',
                'hello',
                'world',
                'home',
                'office',
                'security',
                'manager',
                'qwerty',
                'dragon',
                'monkey',
                'master',
                'princess',
                'sunshine',
                'flower',
                'pakistan',
                'lahore',
                'islamabad',
                'karachi',
            ],

            keyboardRows: [
                '1234567890',
                'qwertyuiop',
                'asdfghjkl',
                'zxcvbnm',
            ],

            init() {
                this.analyze();
            },

            get passwordLength() {
                return Array.from(this.password || '').length;
            },

            get passwordLengthLabel() {
                if (!this.password) {
                    return '0 characters';
                }

                return this.passwordLength + (
                    this.passwordLength === 1 ? ' character' : ' characters'
                );
            },

            get checklist() {
                const length = this.passwordLength;

                return [
                    {
                        key: 'length12',
                        label: '12+ characters',
                        pass: length >= 12,
                    },
                    {
                        key: 'length16',
                        label: '16+ characters',
                        pass: length >= 16,
                    },
                    {
                        key: 'upper',
                        label: 'Uppercase letter',
                        pass: this.uppercaseCount > 0,
                    },
                    {
                        key: 'lower',
                        label: 'Lowercase letter',
                        pass: this.lowercaseCount > 0,
                    },
                    {
                        key: 'number',
                        label: 'Number',
                        pass: this.numberCount > 0,
                    },
                    {
                        key: 'symbol',
                        label: 'Symbol',
                        pass: this.symbolCount > 0,
                    },
                    {
                        key: 'unique',
                        label: 'Good character diversity',
                        pass: this.uniqueCharacters >= Math.max(
                            5,
                            Math.ceil(length * 0.55)
                        ),
                    },
                ];
            },

            get passedChecklist() {
                return this.checklist.filter(function (item) {
                    return item.pass;
                }).length;
            },

            get composition() {
                return [
                    {
                        key: 'uppercase',
                        label: 'Uppercase',
                        count: this.uppercaseCount,
                    },
                    {
                        key: 'lowercase',
                        label: 'Lowercase',
                        count: this.lowercaseCount,
                    },
                    {
                        key: 'numbers',
                        label: 'Numbers',
                        count: this.numberCount,
                    },
                    {
                        key: 'symbols',
                        label: 'Symbols',
                        count: this.symbolCount,
                    },
                ];
            },

            get strengthTextClass() {
                if (this.score >= 80) {
                    return 'text-emerald-600';
                }

                if (this.score >= 60) {
                    return 'text-blue-600';
                }

                if (this.score >= 40) {
                    return 'text-amber-600';
                }

                if (this.score > 0) {
                    return 'text-red-600';
                }

                return 'text-slate-500';
            },

            get strengthBarClass() {
                if (this.score >= 80) {
                    return 'bg-emerald-500';
                }

                if (this.score >= 60) {
                    return 'bg-blue-500';
                }

                if (this.score >= 40) {
                    return 'bg-amber-500';
                }

                if (this.score > 0) {
                    return 'bg-red-500';
                }

                return 'bg-slate-300';
            },

            get resultPanelClass() {
                if (this.score >= 80) {
                    return 'border-emerald-200 bg-emerald-50 text-emerald-900';
                }

                if (this.score >= 60) {
                    return 'border-blue-200 bg-blue-50 text-blue-900';
                }

                if (this.score >= 40) {
                    return 'border-amber-200 bg-amber-50 text-amber-900';
                }

                if (this.score > 0) {
                    return 'border-red-200 bg-red-50 text-red-900';
                }

                return 'border-slate-200 bg-slate-50 text-slate-900';
            },

            get strengthBorderClass() {
                if (this.score >= 80) {
                    return 'border-emerald-300 text-emerald-600';
                }

                if (this.score >= 60) {
                    return 'border-blue-300 text-blue-600';
                }

                if (this.score >= 40) {
                    return 'border-amber-300 text-amber-600';
                }

                if (this.score > 0) {
                    return 'border-red-300 text-red-600';
                }

                return 'border-slate-300 text-slate-500';
            },

            get breachStatusClass() {
                if (this.breachStatus === 'Potentially exposed') {
                    return 'border-red-200 bg-red-50 text-red-700';
                }

                if (this.breachStatus === 'Not found') {
                    return 'border-emerald-200 bg-emerald-50 text-emerald-700';
                }

                if (this.breachStatus === 'Checking…') {
                    return 'border-blue-200 bg-blue-50 text-blue-700';
                }

                return 'border-slate-200 bg-slate-100 text-slate-500';
            },

            get breachResultClass() {
                if (this.breachCount > 0) {
                    return 'border-red-200 bg-red-50 text-red-800';
                }

                return 'border-emerald-200 bg-emerald-50 text-emerald-800';
            },

            get recommendations() {
                const suggestions = [];

                if (!this.password) {
                    return [
                        'Use a unique password or passphrase for every important account.',
                        'Prefer a long password that is difficult to predict.',
                        'Use a reputable password manager to generate and store unique credentials.',
                    ];
                }

                if (this.passwordLength < 12) {
                    suggestions.push(
                        'Increase the password length to at least 12 characters.'
                    );
                }

                if (this.passwordLength < 16) {
                    suggestions.push(
                        'Consider using 16 or more characters for additional protection.'
                    );
                }

                if (this.detectedCommonPassword) {
                    suggestions.push(
                        'Avoid passwords that appear in common-password lists or contain common password phrases.'
                    );
                }

                if (this.detectedDictionaryWord) {
                    suggestions.push(
                        'Avoid relying on ordinary dictionary words unless they are part of a long, unpredictable passphrase.'
                    );
                }

                if (this.detectedKeyboardPattern) {
                    suggestions.push(
                        'Avoid keyboard sequences such as qwerty, asdf or other adjacent-key patterns.'
                    );
                }

                if (this.detectedSequentialPattern) {
                    suggestions.push(
                        'Avoid alphabetical or numerical sequences such as abc, 1234 or their reversed forms.'
                    );
                }

                if (this.detectedRepeatedPattern) {
                    suggestions.push(
                        'Avoid repeated characters or repeated blocks such as aaa, 1111 or abcabc.'
                    );
                }

                if (this.detectedDatePattern) {
                    suggestions.push(
                        'Avoid birthdays, dates, months and four-digit years that could be associated with you.'
                    );
                }

                if (this.detectedSubstitutionPattern) {
                    suggestions.push(
                        'Do not rely on predictable substitutions such as @ for a or 0 for o.'
                    );
                }

                if (this.uppercaseCount === 0) {
                    suggestions.push(
                        'Add uppercase characters if the password policy supports them.'
                    );
                }

                if (this.lowercaseCount === 0) {
                    suggestions.push(
                        'Include lowercase characters where appropriate.'
                    );
                }

                if (this.numberCount === 0) {
                    suggestions.push(
                        'Add numbers when they do not create an obvious predictable pattern.'
                    );
                }

                if (this.symbolCount === 0) {
                    suggestions.push(
                        'Add symbols when supported, but prioritize length and unpredictability.'
                    );
                }

                if (
                    this.uniqueCharacters <
                    Math.max(5, Math.ceil(this.passwordLength * 0.55))
                ) {
                    suggestions.push(
                        'Increase character diversity and avoid excessive repetition.'
                    );
                }

                if (!this.policyPassed) {
                    suggestions.push(
                        'The password does not currently satisfy the configured organization policy.'
                    );
                }

                if (suggestions.length === 0) {
                    suggestions.push(
                        'Keep this password unique and do not reuse it across different services.'
                    );

                    suggestions.push(
                        'Store it in a reputable password manager instead of reusing a memorable password.'
                    );
                }

                return suggestions.slice(0, 8);
            },

            get recommendationsText() {
                return [
                    'Password Improvement Recommendations',
                    '',
                    ...this.recommendations.map(function (item, index) {
                        return (index + 1) + '. ' + item;
                    }),
                ].join('\n');
            },

            get analysisText() {
                const lines = [
                    'Password Strength Analysis',
                    '',
                    'Strength: ' + this.strengthLabel,
                    'Score: ' + this.score + '/100',
                    'Length: ' + this.passwordLength,
                    'Raw entropy estimate: ' + this.entropy + ' bits',
                    'Adjusted practical entropy: ' + this.practicalEntropy + ' bits',
                    'Estimated guesses required: ' + this.formatGuessCount(this.estimatedGuesses),
                    'Unique characters: ' + this.uniqueCharacters,
                    'Character pool: ' + this.poolSize,
                    'Uppercase: ' + this.uppercaseCount,
                    'Lowercase: ' + this.lowercaseCount,
                    'Numbers: ' + this.numberCount,
                    'Symbols: ' + this.symbolCount,
                    'Online estimate: ' + this.formatDuration(this.estimateTime(this.attack.onlineRate)),
                    'Offline fast-hash estimate: ' + this.formatDuration(this.estimateTime(this.attack.offlineRate)),
                    'Policy: ' + (this.policyPassed ? 'Passed' : 'Needs review'),
                    'Breach check: ' + this.breachStatus,
                    '',
                    'Detected weaknesses:',
                ];

                if (this.weaknesses.length === 0) {
                    lines.push('None detected by the local heuristic checks.');
                } else {
                    this.weaknesses.forEach(function (item) {
                        lines.push('- ' + item.title + ': ' + item.detail);
                    });
                }

                lines.push('');
                lines.push(
                    'Note: These measurements are estimates. Practical password security depends on authentication controls, hashing algorithms, rate limiting, breach exposure and attacker resources.'
                );

                return lines.join('\n');
            },

            get comparisonMessage() {
                if (!this.comparison.hasBaseline || !this.password) {
                    return '';
                }

                const difference =
                    this.score - this.comparison.baselineScore;

                if (difference > 0) {
                    return 'The current password has a higher local strength score than the saved baseline by ' + difference + ' points.';
                }

                if (difference < 0) {
                    return 'The current password has a lower local strength score than the saved baseline by ' + Math.abs(difference) + ' points.';
                }

                return 'The current password has the same local strength score as the saved baseline.';
            },

            get detectedCommonPassword() {
                const normalized = this.normalizePassword(this.password);

                if (!normalized) {
                    return false;
                }

                return this.commonPasswords.some(function (item) {
                    return normalized === item ||
                        normalized.includes(item);
                });
            },

            get detectedDictionaryWord() {
                const normalized = this.normalizePassword(this.password);

                if (!normalized || normalized.length < 4) {
                    return false;
                }

                return this.dictionaryWords.some(function (word) {
                    return normalized.includes(word);
                });
            },

            get detectedKeyboardPattern() {
                return this.findKeyboardPatterns(this.password).length > 0;
            },

            get detectedSequentialPattern() {
                return this.findSequentialPatterns(this.password).length > 0;
            },

            get detectedRepeatedPattern() {
                return this.findRepeatedPatterns(this.password).length > 0;
            },

            get detectedDatePattern() {
                return this.findDatePatterns(this.password).length > 0;
            },

            get detectedSubstitutionPattern() {
                return this.findSubstitutionPatterns(this.password).length > 0;
            },

            analyze() {
                const value = this.password || '';

                this.resetDerivedState();

                if (!value) {
                    this.strengthLabel = 'Enter a password';
                    this.summary = 'Start typing to analyze the password.';
                    this.crackTime = '—';
                    this.policyPassed = false;
                    this.passphrase = {
                        wordCount: 0,
                        uniqueWords: 0,
                        separatorQuality: '—',
                        assessment: 'Not enough data',
                    };
                    return;
                }

                const chars = Array.from(value);

                this.uppercaseCount = chars.filter(function (char) {
                    return /[A-Z]/.test(char) || /\p{Lu}/u.test(char);
                }).length;

                this.lowercaseCount = chars.filter(function (char) {
                    return /[a-z]/.test(char) || /\p{Ll}/u.test(char);
                }).length;

                this.numberCount = chars.filter(function (char) {
                    return /[0-9]/.test(char) || /\p{N}/u.test(char);
                }).length;

                this.symbolCount = chars.filter(function (char) {
                    return !(/[A-Za-z0-9]/.test(char)) &&
                        !(/\p{L}/u.test(char)) &&
                        !(/\p{N}/u.test(char));
                }).length;

                this.uniqueCharacters = new Set(chars).size;
                this.poolSize = this.detectPoolSize(value);

                this.entropy = this.calculateEntropy(
                    this.passwordLength,
                    this.poolSize
                );

                this.weaknesses = this.detectWeaknesses(value);

                this.practicalEntropy =
                    this.calculatePracticalEntropy(
                        this.entropy,
                        this.weaknesses
                    );

                this.estimatedGuesses =
                    this.calculateEstimatedGuesses(
                        this.practicalEntropy
                    );

                this.score = this.calculateScore(
                    value,
                    this.practicalEntropy
                );

                this.strengthLabel =
                    this.getStrengthLabel(this.score);

                this.summary =
                    this.getSummary(this.score);

                this.crackTime =
                    this.formatDuration(
                        this.estimateTime(this.attack.offlineRate)
                    );

                this.policyPassed =
                    this.checkPolicy(value);

                this.passphrase =
                    this.analyzePassphrase(value);

                this.patternVisualization =
                    this.buildPatternVisualization(value);
            },

            resetDerivedState() {
                this.uppercaseCount = 0;
                this.lowercaseCount = 0;
                this.numberCount = 0;
                this.symbolCount = 0;
                this.uniqueCharacters = 0;
                this.poolSize = 0;
                this.entropy = 0;
                this.practicalEntropy = 0;
                this.estimatedGuesses = 0;
                this.weaknesses = [];
                this.patternVisualization = [];
            },

            detectPoolSize(value) {
                let pool = 0;

                if (/[a-z]/.test(value) || /\p{Ll}/u.test(value)) {
                    pool += 26;
                }

                if (/[A-Z]/.test(value) || /\p{Lu}/u.test(value)) {
                    pool += 26;
                }

                if (/[0-9]/.test(value) || /\p{N}/u.test(value)) {
                    pool += 10;
                }

                if (
                    /[^A-Za-z0-9]/.test(value) ||
                    /[^\p{L}\p{N}]/u.test(value)
                ) {
                    pool += 32;
                }

                return pool;
            },

            calculateEntropy(length, pool) {
                if (!length || !pool) {
                    return 0;
                }

                const entropy =
                    length * Math.log2(pool);

                return Math.round(entropy * 10) / 10;
            },

            calculatePracticalEntropy(rawEntropy, weaknesses) {
                let penalty = 0;

                weaknesses.forEach(function (weakness) {
                    penalty += weakness.penalty || 0;
                });

                return Math.max(
                    0,
                    Math.round(
                        Math.min(200, rawEntropy - penalty) * 10
                    ) / 10
                );
            },

            calculateEstimatedGuesses(practicalEntropy) {
                if (!practicalEntropy) {
                    return 0;
                }

                const exponent =
                    Math.min(practicalEntropy, 200) - 1;

                return Math.pow(2, Math.max(0, exponent));
            },

            calculateScore(value, practicalEntropy) {
                const length = this.passwordLength;

                let score = 0;

                score += Math.min(length * 3.5, 55);

                if (length >= 12) {
                    score += 8;
                }

                if (length >= 16) {
                    score += 8;
                }

                if (length >= 20) {
                    score += 5;
                }

                if (this.uppercaseCount > 0) {
                    score += 5;
                }

                if (this.lowercaseCount > 0) {
                    score += 5;
                }

                if (this.numberCount > 0) {
                    score += 5;
                }

                if (this.symbolCount > 0) {
                    score += 7;
                }

                const diversityRatio =
                    length > 0
                        ? this.uniqueCharacters / length
                        : 0;

                score += Math.min(
                    7,
                    Math.round(diversityRatio * 7)
                );

                score += Math.min(
                    12,
                    practicalEntropy / 10
                );

                if (this.detectedCommonPassword) {
                    score -= 30;
                }

                if (this.detectedDictionaryWord) {
                    score -= 12;
                }

                if (this.detectedKeyboardPattern) {
                    score -= 12;
                }

                if (this.detectedSequentialPattern) {
                    score -= 10;
                }

                if (this.detectedRepeatedPattern) {
                    score -= 12;
                }

                if (this.detectedDatePattern) {
                    score -= 12;
                }

                if (this.detectedSubstitutionPattern) {
                    score -= 8;
                }

                if (length < 8) {
                    score = Math.min(score, 30);
                }

                if (length < 6) {
                    score = Math.min(score, 15);
                }

                return Math.max(
                    0,
                    Math.min(100, Math.round(score))
                );
            },

            detectWeaknesses(value) {
                const results = [];

                if (this.passwordLength < 8) {
                    results.push({
                        key: 'very-short',
                        title: 'Very short password',
                        detail: 'Passwords shorter than 8 characters have a relatively small search space.',
                        penalty: 20,
                    });
                } else if (this.passwordLength < 12) {
                    results.push({
                        key: 'short',
                        title: 'Short password',
                        detail: 'Increasing the length can substantially increase the theoretical search space.',
                        penalty: 10,
                    });
                }

                if (this.detectedCommonPassword) {
                    results.push({
                        key: 'common',
                        title: 'Common password or fragment',
                        detail: 'The password matches or contains a locally known common-password pattern.',
                        penalty: 25,
                    });
                }

                if (this.detectedDictionaryWord) {
                    results.push({
                        key: 'dictionary',
                        title: 'Dictionary word detected',
                        detail: 'Ordinary dictionary words can be efficient targets for password-guessing attacks.',
                        penalty: 10,
                    });
                }

                const keyboardPatterns =
                    this.findKeyboardPatterns(value);

                if (keyboardPatterns.length) {
                    results.push({
                        key: 'keyboard',
                        title: 'Keyboard pattern',
                        detail: 'Detected keyboard sequence: ' + keyboardPatterns.slice(0, 2).join(', ') + '.',
                        penalty: 12,
                    });
                }

                const sequentialPatterns =
                    this.findSequentialPatterns(value);

                if (sequentialPatterns.length) {
                    results.push({
                        key: 'sequence',
                        title: 'Sequential characters',
                        detail: 'Detected predictable sequence: ' + sequentialPatterns.slice(0, 2).join(', ') + '.',
                        penalty: 10,
                    });
                }

                const repeatedPatterns =
                    this.findRepeatedPatterns(value);

                if (repeatedPatterns.length) {
                    results.push({
                        key: 'repetition',
                        title: 'Repeated characters or blocks',
                        detail: 'Detected repetition: ' + repeatedPatterns.slice(0, 2).join(', ') + '.',
                        penalty: 12,
                    });
                }

                const datePatterns =
                    this.findDatePatterns(value);

                if (datePatterns.length) {
                    results.push({
                        key: 'date',
                        title: 'Date or year pattern',
                        detail: 'Detected a date/year-like sequence: ' + datePatterns.slice(0, 2).join(', ') + '.',
                        penalty: 10,
                    });
                }

                const substitutionPatterns =
                    this.findSubstitutionPatterns(value);

                if (substitutionPatterns.length) {
                    results.push({
                        key: 'substitution',
                        title: 'Predictable substitution',
                        detail: 'Detected common leetspeak-style substitutions such as @, 0, 1 or 3 replacing letters.',
                        penalty: 7,
                    });
                }

                if (
                    this.passwordLength > 0 &&
                    this.uniqueCharacters <
                    Math.max(4, Math.ceil(this.passwordLength * 0.45))
                ) {
                    results.push({
                        key: 'low-diversity',
                        title: 'Low character diversity',
                        detail: 'A large proportion of the password uses repeated characters.',
                        penalty: 8,
                    });
                }

                if (
                    this.passwordLength >= 6 &&
                    this.passwordLength <= 12 &&
                    this.passphraseLooksLikePersonalPattern(value)
                ) {
                    results.push({
                        key: 'personal-pattern',
                        title: 'Potential personal pattern',
                        detail: 'The value resembles a name, common term or short personal-style password pattern.',
                        penalty: 8,
                    });
                }

                return results;
            },

            normalizePassword(value) {
                return (value || '')
                    .normalize('NFKD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase()
                    .replace(/\s+/g, '');
            },

            findKeyboardPatterns(value) {
                const normalized =
                    this.normalizePassword(value);

                const found = [];

                this.keyboardRows.forEach(function (row) {
                    for (let i = 0; i <= row.length - 4; i++) {
                        const chunk = row.slice(i, i + 4);

                        if (normalized.includes(chunk)) {
                            found.push(chunk);
                        }

                        const reverse =
                            chunk.split('').reverse().join('');

                        if (normalized.includes(reverse)) {
                            found.push(reverse);
                        }
                    }
                });

                return [...new Set(found)];
            },

            findSequentialPatterns(value) {
                const normalized =
                    this.normalizePassword(value);

                const sequences = [
                    'abcdefghijklmnopqrstuvwxyz',
                    '0123456789',
                ];

                const found = [];

                sequences.forEach(function (sequence) {
                    for (let i = 0; i <= sequence.length - 4; i++) {
                        const chunk = sequence.slice(i, i + 4);

                        if (normalized.includes(chunk)) {
                            found.push(chunk);
                        }

                        const reverse =
                            chunk.split('').reverse().join('');

                        if (normalized.includes(reverse)) {
                            found.push(reverse);
                        }
                    }
                });

                return [...new Set(found)];
            },

            findRepeatedPatterns(value) {
                const found = [];

                const chars = Array.from(value);

                for (let i = 0; i < chars.length - 2; i++) {
                    if (
                        chars[i] === chars[i + 1] &&
                        chars[i] === chars[i + 2]
                    ) {
                        found.push(
                            chars[i] + chars[i + 1] + chars[i + 2]
                        );
                    }
                }

                for (let size = 2; size <= 4; size++) {
                    for (
                        let i = 0;
                        i + size * 2 <= chars.length;
                        i++
                    ) {
                        const first =
                            chars.slice(i, i + size).join('');

                        const second =
                            chars.slice(i + size, i + size * 2).join('');

                        if (
                            first.length === size &&
                            first === second
                        ) {
                            found.push(first + first);
                        }
                    }
                }

                return [...new Set(found)];
            },

            findDatePatterns(value) {
                const found = [];

                const fourDigitYears =
                    value.match(
                        /(19\d{2}|20\d{2}|21\d{2})/g
                    ) || [];

                const dates =
                    value.match(
                        /(?:0?[1-9]|[12]\d|3[01])(?:0?[1-9]|1[0-2])(?:19\d{2}|20\d{2})/g
                    ) || [];

                const compactDates =
                    value.match(
                        /(?:0[1-9]|1[0-2])[0-3]\d(?:19\d{2}|20\d{2})/g
                    ) || [];

                found.push(...fourDigitYears);
                found.push(...dates);
                found.push(...compactDates);

                return [...new Set(found)];
            },

            findSubstitutionPatterns(value) {
                const found = [];

                if (/[4@]/.test(value) && /[aA]/.test(value)) {
                    found.push('@ → a');
                }

                if (/[0]/.test(value) && /[oO]/.test(value)) {
                    found.push('0 → o');
                }

                if (/[1!]/.test(value) && /[iIlL]/.test(value)) {
                    found.push('1/! → i/l');
                }

                if (/[3]/.test(value) && /[eE]/.test(value)) {
                    found.push('3 → e');
                }

                if (/[5$]/.test(value) && /[sS]/.test(value)) {
                    found.push('5/$ → s');
                }

                return [...new Set(found)];
            },

            passphraseLooksLikePersonalPattern(value) {
                const normalized =
                    this.normalizePassword(value);

                return this.dictionaryWords.some(function (word) {
                    return normalized === word ||
                        normalized.startsWith(word) ||
                        normalized.endsWith(word);
                });
            },

            buildPatternVisualization(value) {
                const chars = Array.from(value);

                if (!chars.length) {
                    return [];
                }

                const highlighted = new Array(chars.length).fill(null);

                const markPattern = function (
                    pattern,
                    className,
                    label
                ) {
                    if (!pattern) {
                        return;
                    }

                    const normalizedValue =
                        value.toLowerCase();

                    const normalizedPattern =
                        pattern.toLowerCase();

                    let start =
                        normalizedValue.indexOf(normalizedPattern);

                    while (start !== -1) {
                        for (
                            let i = start;
                            i < start + pattern.length;
                            i++
                        ) {
                            if (i < highlighted.length) {
                                highlighted[i] = {
                                    className: className,
                                    label: label,
                                };
                            }
                        }

                        start =
                            normalizedValue.indexOf(
                                normalizedPattern,
                                start + 1
                            );
                    }
                };

                this.findKeyboardPatterns(value).forEach(function (item) {
                    markPattern(
                        item,
                        'bg-red-100 text-red-700',
                        'Keyboard pattern'
                    );
                });

                this.findSequentialPatterns(value).forEach(function (item) {
                    markPattern(
                        item,
                        'bg-red-100 text-red-700',
                        'Sequential pattern'
                    );
                });

                this.findRepeatedPatterns(value).forEach(function (item) {
                    markPattern(
                        item,
                        'bg-amber-100 text-amber-700',
                        'Repeated pattern'
                    );
                });

                this.findDatePatterns(value).forEach(function (item) {
                    markPattern(
                        item,
                        'bg-amber-100 text-amber-700',
                        'Date/year pattern'
                    );
                });

                const output = [];
                let current = null;

                for (let i = 0; i < chars.length; i++) {
                    const marker = highlighted[i] || {
                        className: 'text-slate-700',
                        label: 'Character',
                    };

                    if (
                        current &&
                        current.className === marker.className &&
                        current.label === marker.label
                    ) {
                        current.text += chars[i];
                    } else {
                        current = {
                            id: i,
                            text: chars[i],
                            className: marker.className,
                            label: marker.label,
                        };

                        output.push(current);
                    }
                }

                return output;
            },

            analyzePassphrase(value) {
                const words =
                    value.trim()
                        .split(/\s+/)
                        .filter(Boolean);

                if (!words.length) {
                    return {
                        wordCount: 0,
                        uniqueWords: 0,
                        separatorQuality: '—',
                        assessment: 'Not enough data',
                    };
                }

                const normalizedWords =
                    words.map(function (word) {
                        return word
                            .normalize('NFKD')
                            .toLowerCase();
                    });

                const uniqueWords =
                    new Set(normalizedWords).size;

                let separatorQuality = 'None';

                if (/\s{2,}/.test(value)) {
                    separatorQuality = 'Multiple spaces';
                } else if (/\s/.test(value)) {
                    separatorQuality = 'Spaces';
                } else if (/[^\p{L}\p{N}]/u.test(value)) {
                    separatorQuality = 'Mixed separators';
                }

                let assessment = 'Short phrase';

                if (words.length >= 4 && uniqueWords === words.length) {
                    assessment = 'Good passphrase structure';
                } else if (words.length >= 3) {
                    assessment = 'Moderate passphrase structure';
                }

                if (uniqueWords < words.length) {
                    assessment = 'Repeated words detected';
                }

                return {
                    wordCount: words.length,
                    uniqueWords: uniqueWords,
                    separatorQuality: separatorQuality,
                    assessment: assessment,
                };
            },

            checkPolicy(value) {
                if (!value) {
                    return false;
                }

                if (this.passwordLength < Number(this.policy.minLength || 1)) {
                    return false;
                }

                if (
                    this.policy.requireUpper &&
                    this.uppercaseCount === 0
                ) {
                    return false;
                }

                if (
                    this.policy.requireLower &&
                    this.lowercaseCount === 0
                ) {
                    return false;
                }

                if (
                    this.policy.requireNumber &&
                    this.numberCount === 0
                ) {
                    return false;
                }

                if (
                    this.policy.requireSymbol &&
                    this.symbolCount === 0
                ) {
                    return false;
                }

                if (
                    this.policy.blockCommon &&
                    this.detectedCommonPassword
                ) {
                    return false;
                }

                return true;
            },

            getStrengthLabel(score) {
                if (!this.password) {
                    return 'Enter a password';
                }

                if (score >= 80) {
                    return 'Very Strong';
                }

                if (score >= 60) {
                    return 'Strong';
                }

                if (score >= 40) {
                    return 'Moderate';
                }

                if (score >= 20) {
                    return 'Weak';
                }

                return 'Very Weak';
            },

            getSummary(score) {
                if (!this.password) {
                    return 'Start typing to analyze the password.';
                }

                if (score >= 80) {
                    return 'The password has strong measurable characteristics and no major predictable pattern was detected by the local checks.';
                }

                if (score >= 60) {
                    return 'The password has several useful characteristics, but additional length or unpredictability may improve resistance to guessing.';
                }

                if (score >= 40) {
                    return 'The password has some useful characteristics but contains weaknesses that can make guessing more efficient.';
                }

                if (score >= 20) {
                    return 'The password has noticeable weaknesses such as short length, common terms, repetition or predictable patterns.';
                }

                return 'The password contains characteristics commonly associated with easily guessed credentials.';
            },

            estimateTime(rate) {
                if (!this.estimatedGuesses || !rate || rate <= 0) {
                    return 0;
                }

                return this.estimatedGuesses / rate;
            },

            formatDuration(seconds) {
                if (!seconds || !Number.isFinite(seconds)) {
                    return '—';
                }

                if (seconds < 1) {
                    return 'Less than a second';
                }

                if (seconds < 60) {
                    return this.formatLargeNumber(seconds) + ' seconds';
                }

                if (seconds < 3600) {
                    return this.formatLargeNumber(seconds / 60) + ' minutes';
                }

                if (seconds < 86400) {
                    return this.formatLargeNumber(seconds / 3600) + ' hours';
                }

                if (seconds < 31557600) {
                    return this.formatLargeNumber(seconds / 86400) + ' days';
                }

                if (seconds < 31557600 * 1000) {
                    return this.formatLargeNumber(
                        seconds / 31557600
                    ) + ' years';
                }

                if (seconds < 31557600 * 1e6) {
                    return this.formatLargeNumber(
                        seconds / (31557600 * 1000)
                    ) + ' thousand years';
                }

                if (seconds < 31557600 * 1e9) {
                    return this.formatLargeNumber(
                        seconds / (31557600 * 1e6)
                    ) + ' million years';
                }

                return 'Extremely long';
            },

            formatLargeNumber(value) {
                if (!Number.isFinite(value)) {
                    return '—';
                }

                if (value < 10) {
                    return value.toFixed(1);
                }

                if (value < 1000) {
                    return Math.round(value).toLocaleString();
                }

                if (value < 1000000) {
                    return Math.round(value / 1000) + 'k';
                }

                if (value < 1000000000) {
                    return Math.round(value / 1000000) + 'M';
                }

                if (value < 1000000000000) {
                    return Math.round(value / 1000000000) + 'B';
                }

                return Math.round(value / 1000000000000) + 'T';
            },

            formatGuessCount(value) {
                if (!value) {
                    return '—';
                }

                if (value < 1000) {
                    return Math.round(value).toLocaleString();
                }

                if (value < 1e6) {
                    return this.formatLargeNumber(value);
                }

                if (value < 1e9) {
                    return this.formatLargeNumber(value / 1e6) + 'M';
                }

                if (value < 1e12) {
                    return this.formatLargeNumber(value / 1e9) + 'B';
                }

                if (value < 1e15) {
                    return this.formatLargeNumber(value / 1e12) + 'T';
                }

                return value.toExponential(2);
            },

            formatRate(value) {
                if (!value || value <= 0) {
                    return 'Invalid rate';
                }

                if (value >= 1e9) {
                    return (value / 1e9).toFixed(0) + 'B guesses/sec';
                }

                if (value >= 1e6) {
                    return (value / 1e6).toFixed(0) + 'M guesses/sec';
                }

                if (value >= 1e3) {
                    return (value / 1e3).toFixed(0) + 'k guesses/sec';
                }

                return value.toLocaleString() + ' guesses/sec';
            },

            percentage(value) {
                if (!this.passwordLength) {
                    return 0;
                }

                return Math.min(
                    100,
                    Math.round(
                        (value / this.passwordLength) * 100
                    )
                );
            },

            signedNumber(value) {
                if (value > 0) {
                    return '+' + value;
                }

                return String(value);
            },

            loadExample() {
                this.password =
                    'BlueRiver!92-Cedar';

                this.showPassword = true;
                this.lastAction = 'example';
                this.breachStatus = 'Not checked';
                this.breachResult = '';
                this.breachCount = 0;

                this.analyze();

                this.focusPassword();
            },

            clearPassword() {
                this.password = '';
                this.showPassword = false;
                this.lastAction = 'clear';

                this.breachStatus = 'Not checked';
                this.breachResult = '';
                this.breachCount = 0;

                this.analyze();

                this.focusPassword();
            },

            togglePasswordVisibility() {
                this.showPassword = !this.showPassword;
            },

            focusPassword() {
                this.$nextTick(function () {
                    if (this.$refs.passwordInput) {
                        this.$refs.passwordInput.focus();
                    }
                }.bind(this));
            },

            generateStrongPassword() {
                const uppercase =
                    'ABCDEFGHJKLMNPQRSTUVWXYZ';

                const lowercase =
                    'abcdefghijkmnopqrstuvwxyz';

                const numbers =
                    '23456789';

                const symbols =
                    '!@#$%^&*()-_=+[]{}';

                const all =
                    uppercase +
                    lowercase +
                    numbers +
                    symbols;

                const length = 20;
                const result = [];

                result.push(this.randomCharacter(uppercase));
                result.push(this.randomCharacter(lowercase));
                result.push(this.randomCharacter(numbers));
                result.push(this.randomCharacter(symbols));

                while (result.length < length) {
                    result.push(
                        this.randomCharacter(all)
                    );
                }

                this.secureShuffle(result);

                this.password =
                    result.join('');

                this.showPassword = true;
                this.lastAction = 'generate';

                this.breachStatus = 'Not checked';
                this.breachResult = '';
                this.breachCount = 0;

                this.analyze();

                this.showToast(
                    'A new password was generated locally.'
                );

                this.focusPassword();
            },

            randomCharacter(characters) {
                if (
                    window.crypto &&
                    window.crypto.getRandomValues
                ) {
                    const array =
                        new Uint32Array(1);

                    window.crypto.getRandomValues(array);

                    return characters[
                        array[0] % characters.length
                    ];
                }

                return characters[
                    Math.floor(
                        Math.random() * characters.length
                    )
                ];
            },

            secureShuffle(array) {
                if (
                    window.crypto &&
                    window.crypto.getRandomValues
                ) {
                    for (
                        let i = array.length - 1;
                        i > 0;
                        i--
                    ) {
                        const random =
                            new Uint32Array(1);

                        window.crypto.getRandomValues(random);

                        const j =
                            random[0] % (i + 1);

                        const temp = array[i];

                        array[i] = array[j];
                        array[j] = temp;
                    }

                    return;
                }

                for (
                    let i = array.length - 1;
                    i > 0;
                    i--
                ) {
                    const j =
                        Math.floor(
                            Math.random() * (i + 1)
                        );

                    const temp = array[i];

                    array[i] = array[j];
                    array[j] = temp;
                }
            },

            async checkBreach() {
                if (!this.password || this.breachChecking) {
                    return;
                }

                if (
                    !window.crypto ||
                    !window.crypto.subtle
                ) {
                    this.breachStatus = 'Unavailable';
                    this.breachResult =
                        'This browser does not provide the required Web Crypto API.';
                    return;
                }

                this.breachChecking = true;
                this.breachStatus = 'Checking…';
                this.breachResult = '';
                this.breachCount = 0;
                this.privacyStatus = 'Breach lookup active';

                try {
                    const encoder =
                        new TextEncoder();

                    const data =
                        encoder.encode(this.password);

                    const hashBuffer =
                        await crypto.subtle.digest(
                            'SHA-1',
                            data
                        );

                    const hashArray =
                        Array.from(
                            new Uint8Array(hashBuffer)
                        );

                    const hash =
                        hashArray
                            .map(function (byte) {
                                return byte
                                    .toString(16)
                                    .padStart(2, '0');
                            })
                            .join('')
                            .toUpperCase();

                    const prefix =
                        hash.slice(0, 5);

                    const suffix =
                        hash.slice(5);

                    const response =
                        await fetch(
                            'https://api.pwnedpasswords.com/range/' +
                            prefix,
                            {
                                method: 'GET',
                                headers: {
                                    'Add-Padding': 'true',
                                },
                                cache: 'no-store',
                            }
                        );

                    if (!response.ok) {
                        throw new Error(
                            'Breach service returned HTTP ' +
                            response.status
                        );
                    }

                    const text =
                        await response.text();

                    const lines =
                        text.split(/\r?\n/);

                    let count = 0;

                    for (
                        let i = 0;
                        i < lines.length;
                        i++
                    ) {
                        const parts =
                            lines[i].trim().split(':');

                        if (
                            parts.length === 2 &&
                            parts[0].toUpperCase() === suffix
                        ) {
                            count =
                                parseInt(
                                    parts[1],
                                    10
                                ) || 0;

                            break;
                        }
                    }

                    this.breachCount = count;

                    if (count > 0) {
                        this.breachStatus =
                            'Potentially exposed';

                        this.breachResult =
                            'This password hash appears in the breach corpus ' +
                            this.formatLargeNumber(count) +
                            ' time(s). Do not use this password for an important account.';
                    } else {
                        this.breachStatus =
                            'Not found';

                        this.breachResult =
                            'No matching hash was returned by the breach service. This does not prove that the password has never been exposed.';
                    }
                } catch (error) {
                    this.breachStatus = 'Unavailable';

                    this.breachResult =
                        'The optional breach check could not be completed. Local password analysis remains available.';

                    console.error(
                        'AabiTech password breach check:',
                        error
                    );
                } finally {
                    this.breachChecking = false;
                    this.privacyStatus = 'Local analysis';

                    this.analyze();
                }
            },

            async copyText(text, type) {
                if (!text) {
                    return;
                }

                try {
                    if (
                        navigator.clipboard &&
                        window.isSecureContext
                    ) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        const textarea =
                            document.createElement('textarea');

                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';

                        document.body.appendChild(textarea);

                        textarea.focus();
                        textarea.select();

                        document.execCommand('copy');

                        textarea.remove();
                    }

                    this.copiedType = type;

                    this.showToast(
                        'Copied to clipboard'
                    );

                    window.setTimeout(
                        function () {
                            if (this.copiedType === type) {
                                this.copiedType = null;
                            }
                        }.bind(this),
                        1600
                    );
                } catch (error) {
                    this.showToast(
                        'Copy failed. Please copy manually.'
                    );
                }
            },

            saveComparisonBaseline() {
                if (!this.password) {
                    return;
                }

                this.comparison = {
                    hasBaseline: true,
                    baselineScore: this.score,
                    baselineEntropy: this.practicalEntropy,
                    baselineLength: this.passwordLength,
                };

                this.lastAction = 'compare';

                this.showToast(
                    'Current strength saved for comparison.'
                );
            },

            showToast(message) {
                this.toast = message;

                if (this.toastTimer) {
                    window.clearTimeout(
                        this.toastTimer
                    );
                }

                this.toastTimer =
                    window.setTimeout(
                        function () {
                            this.toast = '';
                        }.bind(this),
                        2200
                    );
            },

            handleShortcut(event) {
                const target =
                    event.target;

                const tag =
                    target && target.tagName
                        ? target.tagName.toLowerCase()
                        : '';

                if (
                    tag === 'input' &&
                    event.key === 'Escape'
                ) {
                    this.clearPassword();
                    return;
                }

                if (
                    (event.ctrlKey || event.metaKey) &&
                    event.key === 'Enter'
                ) {
                    event.preventDefault();
                    this.focusPassword();
                    return;
                }

                if (
                    event.key === 'Escape' &&
                    this.password
                ) {
                    this.clearPassword();
                }
            },
        };
    };
</script>
@endscript
