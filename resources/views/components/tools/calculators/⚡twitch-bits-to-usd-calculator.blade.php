<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only calculator. No server-side state is required.
};
?>

<div
    x-data="twitchBitsUsdCalculator()"
    x-init="init()"
    x-cloak
    class="min-w-0"
>
    {{-- Primary calculator --}}
    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(360px,460px)] lg:items-end">

            {{-- Bits input --}}
            <div class="min-w-0">

                <div class="mb-2 flex items-center justify-between gap-3">
                    <label
                        for="twitch-bits"
                        class="text-sm font-semibold text-slate-800"
                    >
                        Twitch Bits
                    </label>

                    <span
                        x-show="hasValidAmount"
                        class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold tabular-nums text-slate-700"
                        x-text="formatNumber(bits) + ' Bits'"
                    ></span>
                </div>

                {{-- Calculated payout directly beneath label --}}
                <div
                    x-show="hasValidAmount"
                    class="mb-2.5 flex min-h-[36px] items-center justify-between gap-3 rounded-md border border-indigo-200 bg-indigo-50 px-3 py-2"
                >
                    <span class="text-xs font-semibold text-indigo-700">
                        Estimated streamer payout
                    </span>

                    <strong
                        class="text-base font-bold tabular-nums text-indigo-800"
                        x-text="formatUsd(streamerPayout)"
                    ></strong>
                </div>

                <input
                    id="twitch-bits"
                    type="number"
                    min="0"
                    step="1"
                    inputmode="numeric"
                    x-model="amount"
                    @input="scheduleCalculate()"
                    @keydown.enter.prevent="calculate()"
                    class="h-14 w-full rounded-lg border border-slate-300 bg-white px-4 text-base font-semibold tabular-nums text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    placeholder="Enter Twitch Bits, e.g. 1000"
                    aria-label="Twitch Bits amount"
                    aria-describedby="bits-help"
                >

                <p
                    id="bits-help"
                    class="mt-2 text-xs leading-5 text-slate-500"
                >
                    1 Bit = $0.01 streamer payout.
                </p>

            </div>

            {{-- Results --}}
            <div class="grid grid-cols-2 gap-3">

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3.5">
                    <span class="block text-xs font-medium text-slate-500">
                        Streamer payout
                    </span>

                    <strong
                        class="mt-1 block text-lg font-bold tabular-nums text-slate-900 sm:text-xl"
                        x-text="formatUsd(streamerPayout)"
                    ></strong>
                </div>

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3.5">
                    <span class="block text-xs font-medium text-slate-500">
                        Estimated viewer cost
                    </span>

                    <strong
                        class="mt-1 block text-lg font-bold tabular-nums text-slate-900 sm:text-xl"
                        x-text="formatUsd(viewerCost)"
                    ></strong>
                </div>

                <div class="col-span-2 flex min-h-[48px] items-center justify-between gap-3 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <span class="text-xs font-semibold text-emerald-700">
                        Viewer cost above payout
                    </span>

                    <strong
                        class="text-base font-bold tabular-nums text-emerald-800"
                        x-text="formatUsd(viewerDifference)"
                    ></strong>
                </div>

            </div>

        </div>

        {{-- Quick amounts and actions --}}
        <div class="mt-5 flex flex-col gap-4 border-t border-slate-100 pt-5 lg:flex-row lg:items-center lg:justify-between">

            <div
                data-active-group
                class="flex flex-wrap items-center gap-2"
                aria-label="Quick Bit amounts"
            >
                <span class="mr-1 text-xs font-medium text-slate-500">
                    Quick:
                </span>

                <template
                    x-for="preset in presets"
                    :key="preset"
                >
                    <button
                        type="button"
                        class="preset-button"
                        :class="{ 'is-active': bits === preset }"
                        :aria-pressed="bits === preset"
                        :aria-label="'Calculate ' + formatNumber(preset) + ' Bits'"
                        @click="usePreset(preset)"
                        x-text="formatNumber(preset)"
                    ></button>
                </template>
            </div>

            <div class="flex flex-wrap gap-2">

                <button
                    type="button"
                    @click="copyResult()"
                    :disabled="!hasValidAmount"
                    :class="{ 'is-active': activeAction === 'copy' }"
                    class="action-button action-button-primary"
                    :aria-pressed="activeAction === 'copy'"
                >
                    <span
                        x-text="activeAction === 'copy'
                            ? 'Copied to clipboard'
                            : 'Copy result'"
                    ></span>
                </button>

                <button
                    type="button"
                    @click="downloadCsv()"
                    :disabled="!hasValidAmount"
                    :class="{ 'is-active': activeAction === 'download' }"
                    class="action-button"
                    :aria-pressed="activeAction === 'download'"
                >
                    <span
                        x-text="activeAction === 'download'
                            ? 'Downloaded'
                            : 'Download CSV'"
                    ></span>
                </button>

            </div>

        </div>

    </section>

    {{-- Secondary tools --}}
    <div class="mt-5 grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(360px,460px)]">

        {{-- Reference bundles --}}
        <section class="min-w-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Reference bundle prices
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Used to estimate what viewers may pay.
                    </p>
                </div>

                <button
                    type="button"
                    @click="resetBundles()"
                    class="text-button"
                >
                    Reset
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="w-full min-w-[430px] text-sm">
                    <caption class="sr-only">
                        Twitch Bits reference bundle prices
                    </caption>

                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr class="text-left text-xs font-semibold text-slate-500">
                            <th
                                scope="col"
                                class="px-4 py-3"
                            >
                                Bits
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 text-right"
                            >
                                Viewer price
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 text-right"
                            >
                                Price / Bit
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <template
                            x-for="bundle in bundles"
                            :key="bundle.bits"
                        >
                            <tr>
                                <td
                                    class="px-4 py-3 font-medium tabular-nums text-slate-700"
                                    x-text="formatNumber(bundle.bits)"
                                ></td>

                                <td
                                    class="px-4 py-3 text-right font-semibold tabular-nums text-slate-700"
                                    x-text="formatUsd(bundle.price)"
                                ></td>

                                <td
                                    class="px-4 py-3 text-right tabular-nums text-slate-500"
                                    x-text="formatUsd(bundle.price / bundle.bits, 4)"
                                ></td>
                            </tr>
                        </template>

                    </tbody>
                </table>
            </div>

            {{-- Custom bundle --}}
            <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_1fr_auto] sm:items-end">

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Custom Bits
                    </span>

                    <input
                        type="number"
                        min="1"
                        step="1"
                        inputmode="numeric"
                        x-model="customBits"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="10000"
                        aria-label="Custom reference bundle Bits"
                    >
                </label>

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Price
                    </span>

                    <input
                        type="number"
                        min="0.01"
                        step="0.01"
                        inputmode="decimal"
                        x-model="customPrice"
                        @keydown.enter.prevent="addCustomBundle()"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="126"
                        aria-label="Custom reference bundle price"
                    >
                </label>

                <button
                    type="button"
                    @click="addCustomBundle()"
                    class="add-button"
                >
                    Add bundle
                </button>

            </div>

            <p class="mt-3 text-xs leading-5 text-slate-400">
                Viewer cost is an estimate based on these reference prices.
                Actual Twitch pricing may vary by market, taxes, and purchase package.
            </p>

        </section>

        {{-- Earnings and budget --}}
        <section class="min-w-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-base font-semibold text-slate-900">
                Earnings &amp; budget
            </h2>

            <p class="mt-1 text-xs leading-5 text-slate-500">
                Estimate Bits needed for earnings or a viewer spending budget.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Target earnings
                    </span>

                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        inputmode="decimal"
                        x-model="targetEarnings"
                        @input="scheduleGoals()"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="$100"
                        aria-label="Target streamer earnings"
                    >

                    <span
                        x-show="goalBits > 0"
                        class="mt-1.5 block rounded-md border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-xs font-semibold tabular-nums text-indigo-700"
                        x-text="formatNumber(goalBits) + ' Bits needed'"
                    ></span>
                </label>

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Viewer budget
                    </span>

                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        inputmode="decimal"
                        x-model="viewerBudget"
                        @input="scheduleGoals()"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="$50"
                        aria-label="Viewer budget"
                    >

                    <span
                        x-show="budgetBits > 0"
                        class="mt-1.5 block rounded-md border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-semibold tabular-nums text-emerald-700"
                        x-text="formatNumber(budgetBits) + ' estimated Bits'"
                    ></span>
                </label>

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Monthly Bits
                    </span>

                    <input
                        type="number"
                        min="0"
                        step="1"
                        inputmode="numeric"
                        x-model="monthlyBits"
                        @input="scheduleGoals()"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="10000"
                        aria-label="Monthly Twitch Bits"
                    >

                    <span
                        x-show="monthlyPayout > 0"
                        class="mt-1.5 block rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold tabular-nums text-slate-700"
                        x-text="formatUsd(monthlyPayout) + ' monthly payout'"
                    ></span>
                </label>

                <label>
                    <span class="mb-1.5 block text-xs font-medium text-slate-600">
                        Tax / adjustment
                    </span>

                    <input
                        type="number"
                        min="0"
                        max="100"
                        step="0.1"
                        inputmode="decimal"
                        x-model="taxAdjustment"
                        @input="scheduleGoals()"
                        class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm tabular-nums text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        placeholder="0%"
                        aria-label="Tax or adjustment percentage"
                    >

                    <span
                        x-show="monthlyPayout > 0"
                        class="mt-1.5 block rounded-md border border-amber-100 bg-amber-50 px-3 py-1.5 text-xs font-semibold tabular-nums text-amber-700"
                        x-text="formatUsd(adjustedMonthlyPayout) + ' after adjustment'"
                    ></span>
                </label>

            </div>

        </section>

    </div>

    <p class="mt-3 text-xs leading-5 text-slate-400">
        Streamer payout uses $0.01 per Bit. Viewer cost is an estimate based on
        the editable reference bundles and is not an official Twitch pricing quote.
    </p>

</div>

@script
<script>
    Alpine.data('twitchBitsUsdCalculator', () => ({
        amount: '1000',

        payoutRate: 0.01,

        defaultBundles: [
            { bits: 100, price: 1.40 },
            { bits: 500, price: 7.00 },
            { bits: 1500, price: 19.95 },
            { bits: 5000, price: 64.40 },
            { bits: 10000, price: 126.00 },
            { bits: 25000, price: 308.00 },
        ],

        bundles: [],

        presets: [
            100,
            500,
            1000,
            5000,
            10000,
            25000,
        ],

        bits: 0,
        streamerPayout: 0,
        viewerCost: 0,
        viewerDifference: 0,

        targetEarnings: '',
        viewerBudget: '',
        monthlyBits: '',
        taxAdjustment: 0,

        goalBits: 0,
        budgetBits: 0,
        monthlyPayout: 0,
        adjustedMonthlyPayout: 0,

        customBits: '',
        customPrice: '',

        activeAction: '',

        calculateTimer: null,
        goalsTimer: null,

        init() {
            this.resetBundles(false);
            this.calculate();
            this.calculateGoals();
        },

        scheduleCalculate() {
            clearTimeout(this.calculateTimer);

            this.calculateTimer = setTimeout(() => {
                this.calculate();
            }, 100);
        },

        scheduleGoals() {
            clearTimeout(this.goalsTimer);

            this.goalsTimer = setTimeout(() => {
                this.calculateGoals();
            }, 100);
        },

        calculate() {
            const value = this.parseNumber(this.amount);

            if (!Number.isFinite(value) || value <= 0) {
                this.bits = 0;
                this.streamerPayout = 0;
                this.viewerCost = 0;
                this.viewerDifference = 0;

                return;
            }

            this.bits = Math.floor(value);

            this.streamerPayout =
                this.bits * this.payoutRate;

            this.viewerCost =
                this.estimateViewerCost(this.bits);

            this.viewerDifference = Math.max(
                0,
                this.viewerCost - this.streamerPayout
            );
        },

        /*
         * Fast piecewise-linear pricing.
         *
         * The calculator only has a small reference table, so there is
         * no reason to use dynamic programming or repeated searches.
         */
        estimateViewerCost(bits) {
            const target = Math.max(
                0,
                Number(bits)
            );

            const bundles = this.bundles;

            if (!target || !bundles.length) {
                return 0;
            }

            if (bundles.length === 1) {
                return target * (
                    bundles[0].price /
                    bundles[0].bits
                );
            }

            if (target <= bundles[0].bits) {
                return target * (
                    bundles[0].price /
                    bundles[0].bits
                );
            }

            const lastIndex =
                bundles.length - 1;

            if (target >= bundles[lastIndex].bits) {
                return target * (
                    bundles[lastIndex].price /
                    bundles[lastIndex].bits
                );
            }

            for (
                let index = 0;
                index < lastIndex;
                index++
            ) {
                const lower = bundles[index];
                const upper = bundles[index + 1];

                if (
                    target >= lower.bits &&
                    target <= upper.bits
                ) {
                    const range =
                        upper.bits -
                        lower.bits;

                    if (range <= 0) {
                        return lower.price;
                    }

                    const ratio =
                        (target - lower.bits) /
                        range;

                    return Math.max(
                        0,
                        lower.price +
                        (
                            (upper.price -
                                lower.price) *
                            ratio
                        )
                    );
                }
            }

            return 0;
        },

        /*
         * Direct inverse of the same pricing curve.
         * Avoids binary search and repeated calculations.
         */
        bitsForBudget(budget) {
            const value = Math.max(
                0,
                Number(budget)
            );

            const bundles = this.bundles;

            if (!value || !bundles.length) {
                return 0;
            }

            if (bundles.length === 1) {
                return value * (
                    bundles[0].bits /
                    bundles[0].price
                );
            }

            if (value <= bundles[0].price) {
                return value * (
                    bundles[0].bits /
                    bundles[0].price
                );
            }

            const lastIndex =
                bundles.length - 1;

            for (
                let index = 0;
                index < lastIndex;
                index++
            ) {
                const lower = bundles[index];
                const upper = bundles[index + 1];

                if (
                    value >= lower.price &&
                    value <= upper.price
                ) {
                    const range =
                        upper.price -
                        lower.price;

                    if (range <= 0) {
                        return lower.bits;
                    }

                    const ratio =
                        (value - lower.price) /
                        range;

                    return lower.bits +
                        (
                            (upper.bits -
                                lower.bits) *
                            ratio
                        );
                }
            }

            const last =
                bundles[lastIndex];

            return last.bits +
                (
                    (value - last.price) *
                    (last.bits / last.price)
                );
        },

        calculateGoals() {
            const target =
                this.parseNumber(
                    this.targetEarnings
                );

            this.goalBits =
                target > 0
                    ? target / this.payoutRate
                    : 0;

            const budget =
                this.parseNumber(
                    this.viewerBudget
                );

            this.budgetBits =
                budget > 0
                    ? this.bitsForBudget(budget)
                    : 0;

            const monthly =
                this.parseNumber(
                    this.monthlyBits
                );

            this.monthlyPayout =
                monthly > 0
                    ? monthly * this.payoutRate
                    : 0;

            const adjustment =
                Math.min(
                    100,
                    Math.max(
                        0,
                        this.parseNumber(
                            this.taxAdjustment
                        )
                    )
                );

            this.adjustedMonthlyPayout =
                this.monthlyPayout *
                (1 - adjustment / 100);
        },

        usePreset(value) {
            this.amount = String(value);
            this.calculate();
        },

        addCustomBundle() {
            const bits = Math.floor(
                this.parseNumber(
                    this.customBits
                )
            );

            const price =
                this.parseNumber(
                    this.customPrice
                );

            if (
                bits <= 0 ||
                price <= 0
            ) {
                return;
            }

            const index =
                this.bundles.findIndex(
                    bundle => bundle.bits === bits
                );

            const bundle = {
                bits,
                price: this.round(price, 4),
            };

            if (index >= 0) {
                this.bundles[index] = bundle;
            } else {
                this.bundles.push(bundle);
            }

            this.bundles =
                this.normalizedBundles();

            this.customBits = '';
            this.customPrice = '';

            this.calculate();
            this.calculateGoals();
        },

        resetBundles(
            recalculate = true
        ) {
            this.bundles =
                this.defaultBundles.map(
                    bundle => ({
                        bits: bundle.bits,
                        price: bundle.price,
                    })
                );

            if (recalculate) {
                this.calculate();
                this.calculateGoals();
            }
        },

        normalizedBundles() {
            const map = new Map();

            for (
                const bundle of this.bundles
            ) {
                const bits =
                    Number(bundle.bits);

                const price =
                    Number(bundle.price);

                if (
                    Number.isFinite(bits) &&
                    bits > 0 &&
                    Number.isFinite(price) &&
                    price > 0
                ) {
                    map.set(bits, {
                        bits,
                        price,
                    });
                }
            }

            return Array.from(
                map.values()
            ).sort(
                (a, b) =>
                    a.bits - b.bits
            );
        },

        parseNumber(value) {
            if (typeof value === 'number') {
                return Number.isFinite(value)
                    ? value
                    : 0;
            }

            if (typeof value !== 'string') {
                return 0;
            }

            const number = Number(
                value
                    .replace(/,/g, '')
                    .trim()
            );

            return Number.isFinite(number)
                ? number
                : 0;
        },

        round(
            value,
            decimals = 2
        ) {
            const factor =
                10 ** decimals;

            return Math.round(
                (
                    Number(value) +
                    Number.EPSILON
                ) * factor
            ) / factor;
        },

        formatNumber(value) {
            const number =
                Number(value);

            if (!Number.isFinite(number)) {
                return '0';
            }

            return new Intl.NumberFormat(
                'en-US',
                {
                    maximumFractionDigits: 2,
                }
            ).format(number);
        },

        formatUsd(
            value,
            decimals = 2
        ) {
            const number =
                Number(value);

            if (!Number.isFinite(number)) {
                return '$0.00';
            }

            return new Intl.NumberFormat(
                'en-US',
                {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits:
                        decimals,
                    maximumFractionDigits:
                        decimals,
                }
            ).format(number);
        },

        get hasValidAmount() {
            return this.bits > 0;
        },

        resultText() {
            return [
                'Twitch Bits to USD Calculator',
                `Bits: ${this.formatNumber(this.bits)}`,
                `Streamer payout: ${this.formatUsd(this.streamerPayout)}`,
                `Estimated viewer cost: ${this.formatUsd(this.viewerCost)}`,
                `Viewer cost above payout: ${this.formatUsd(this.viewerDifference)}`,
            ].join('\n');
        },

        async copyResult() {
            if (!this.hasValidAmount) {
                return;
            }

            const text =
                this.resultText();

            try {
                if (
                    navigator.clipboard &&
                    typeof navigator.clipboard.writeText ===
                        'function'
                ) {
                    await navigator.clipboard.writeText(
                        text
                    );
                } else {
                    this.copyFallback(text);
                }

                this.showAction('copy');
            } catch {
                try {
                    this.copyFallback(text);
                    this.showAction('copy');
                } catch {
                    this.activeAction = '';
                }
            }
        },

        copyFallback(text) {
            const textarea =
                document.createElement(
                    'textarea'
                );

            textarea.value = text;
            textarea.setAttribute(
                'readonly',
                ''
            );

            textarea.style.position =
                'fixed';

            textarea.style.left =
                '-9999px';

            textarea.style.top =
                '0';

            document.body.appendChild(
                textarea
            );

            textarea.focus();
            textarea.select();

            const success =
                document.execCommand(
                    'copy'
                );

            textarea.remove();

            if (!success) {
                throw new Error(
                    'Copy failed'
                );
            }
        },

        showAction(action) {
            this.activeAction = action;

            setTimeout(() => {
                if (
                    this.activeAction ===
                    action
                ) {
                    this.activeAction = '';
                }
            }, 1500);
        },

        downloadCsv() {
            if (!this.hasValidAmount) {
                return;
            }

            const rows = [
                [
                    'Metric',
                    'Value',
                ],
                [
                    'Twitch Bits',
                    this.round(
                        this.bits,
                        2
                    ),
                ],
                [
                    'Streamer payout (USD)',
                    this.round(
                        this.streamerPayout,
                        2
                    ),
                ],
                [
                    'Estimated viewer cost (USD)',
                    this.round(
                        this.viewerCost,
                        2
                    ),
                ],
                [
                    'Viewer cost above payout (USD)',
                    this.round(
                        this.viewerDifference,
                        2
                    ),
                ],
            ];

            const csv =
                rows
                    .map(row =>
                        row
                            .map(value =>
                                this.csvEscape(
                                    value
                                )
                            )
                            .join(',')
                    )
                    .join('\r\n');

            const blob =
                new Blob(
                    [csv],
                    {
                        type:
                            'text/csv;charset=utf-8;',
                    }
                );

            const url =
                URL.createObjectURL(
                    blob
                );

            const link =
                document.createElement(
                    'a'
                );

            link.href = url;
            link.download =
                'twitch-bits-to-usd.csv';
            link.hidden = true;

            document.body.appendChild(
                link
            );

            link.click();
            link.remove();

            setTimeout(
                () => URL.revokeObjectURL(url),
                1000
            );

            this.showAction(
                'download'
            );
        },

        csvEscape(value) {
            const text =
                String(value ?? '');

            return /[",\r\n]/.test(text)
                ? `"${text.replace(
                      /"/g,
                      '""'
                  )}"`
                : text;
        },
    }));
</script>
@endscript

@assets
<style>
    [x-cloak] {
        display: none !important;
    }

    .preset-button,
    .action-button,
    .add-button,
    .text-button {
        cursor: pointer;
    }

    .preset-button {
        min-height: 42px;
        padding: 0 14px;
        border: 1px solid rgb(203 213 225);
        border-radius: 7px;
        background: #ffffff;
        color: rgb(51 65 85);
        font-size: 13px;
        font-weight: 650;
        line-height: 1;
        font-variant-numeric: tabular-nums;
        transition:
            background-color 120ms ease,
            border-color 120ms ease,
            color 120ms ease,
            box-shadow 120ms ease,
            transform 120ms ease;
    }

    .preset-button:hover {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    .preset-button:active {
        transform: translateY(1px);
    }

    .preset-button.is-active {
        border-color: rgb(79 70 229);
        background: rgb(79 70 229);
        color: #ffffff;
        box-shadow:
            0 1px 2px rgb(15 23 42 / 0.08),
            0 0 0 1px rgb(79 70 229 / 0.12);
    }

    .preset-button.is-active:hover {
        border-color: rgb(67 56 202);
        background: rgb(67 56 202);
        color: #ffffff;
    }

    .action-button {
        min-height: 44px;
        padding: 0 15px;
        border: 1px solid rgb(226 232 240);
        border-radius: 8px;
        background: #ffffff;
        color: rgb(51 65 85);
        font-size: 13px;
        font-weight: 650;
        line-height: 1;
        transition:
            background-color 120ms ease,
            border-color 120ms ease,
            color 120ms ease,
            transform 120ms ease;
    }

    .action-button:hover:not(:disabled) {
        border-color: rgb(165 180 252);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    .action-button:active:not(:disabled) {
        transform: translateY(1px);
    }

    .action-button-primary {
        border-color: rgb(199 210 254);
        background: rgb(238 242 255);
        color: rgb(67 56 202);
    }

    .action-button.is-active {
        border-color: rgb(79 70 229);
        background: rgb(79 70 229);
        color: #ffffff;
    }

    .action-button:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .add-button {
        min-height: 44px;
        border: 1px solid rgb(199 210 254);
        border-radius: 7px;
        background: rgb(238 242 255);
        padding: 0 16px;
        color: rgb(67 56 202);
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
        transition:
            background-color 120ms ease,
            border-color 120ms ease,
            color 120ms ease;
    }

    .add-button:hover {
        border-color: rgb(79 70 229);
        background: rgb(79 70 229);
        color: #ffffff;
    }

    .text-button {
        min-height: 34px;
        border: 1px solid transparent;
        border-radius: 6px;
        padding: 0 9px;
        background: transparent;
        color: rgb(100 116 139);
        font-size: 12px;
        font-weight: 600;
        transition:
            background-color 120ms ease,
            color 120ms ease,
            border-color 120ms ease;
    }

    .text-button:hover {
        border-color: rgb(226 232 240);
        background: rgb(248 250 252);
        color: rgb(51 65 85);
    }

    .preset-button:focus-visible,
    .action-button:focus-visible,
    .add-button:focus-visible,
    .text-button:focus-visible {
        outline: 2px solid rgb(129 140 248 / 0.55);
        outline-offset: 2px;
    }

    @media (max-width: 640px) {
        .preset-button {
            min-height: 44px;
            padding-inline: 15px;
        }

        .action-button {
            min-height: 46px;
            flex: 1 1 auto;
        }

        .add-button {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .preset-button,
        .action-button,
        .add-button,
        .text-button {
            transition: none;
        }
    }
</style>
@endassets