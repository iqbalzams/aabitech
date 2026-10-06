<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only calculator.
    // No pricing inputs or calculation results are stored or processed server-side.
};
?>

<div
    x-data="tattooPriceCalculator()"
    x-init="init()"
    class="tattoo-workspace"
    x-cloak
>
    <section
        class="tattoo-calculator"
        aria-label="Tattoo price calculator workspace"
    >
        <div class="tattoo-layout">

            {{-- ================================================================
                 LEFT: ESTIMATE CONTROLS
                 ================================================================ --}}
            <div class="tattoo-controls">

                <section class="tattoo-panel tattoo-details-panel">
                    <div class="tattoo-section-heading">
                        <div>
                            <h2>Estimate details</h2>
                            <p>Adjust the details below to build a realistic tattoo price estimate.</p>
                        </div>
                    </div>

                    {{-- Pricing method --}}
                    <div class="tattoo-field tattoo-method-field">
                        <label class="tattoo-label">
                            Pricing method
                        </label>

                        <div
                            class="tattoo-tabs"
                            role="group"
                            aria-label="Pricing method"
                        >
                            <button
                                type="button"
                                class="tattoo-tab"
                                :class="{ 'is-active': pricingMode === 'hourly' }"
                                :aria-pressed="pricingMode === 'hourly'"
                                @click="pricingMode = 'hourly'; calculate()"
                            >
                                <span>Hourly</span>
                            </button>

                            <button
                                type="button"
                                class="tattoo-tab"
                                :class="{ 'is-active': pricingMode === 'flat' }"
                                :aria-pressed="pricingMode === 'flat'"
                                @click="pricingMode = 'flat'; calculate()"
                            >
                                <span>Flat quote</span>
                            </button>

                            <button
                                type="button"
                                class="tattoo-tab"
                                :class="{ 'is-active': pricingMode === 'day' }"
                                :aria-pressed="pricingMode === 'day'"
                                @click="pricingMode = 'day'; calculate()"
                            >
                                <span>Day rate</span>
                            </button>
                        </div>
                    </div>

                    {{-- Size --}}
                    <div class="tattoo-grid tattoo-grid-2">

                        <div class="tattoo-field">
                            <label
                                for="tattoo-size"
                                class="tattoo-label"
                            >
                                Tattoo size
                            </label>

                            <div class="tattoo-input-with-select">
                                <input
                                    id="tattoo-size"
                                    type="number"
                                    min="0.25"
                                    step="0.25"
                                    inputmode="decimal"
                                    x-model.number="size"
                                    @input="hoursOverridden = false; calculate()"
                                    class="tattoo-input tattoo-input-main"
                                    aria-describedby="tattoo-size-help"
                                >

                                <select
                                    id="tattoo-unit"
                                    x-model="unit"
                                    @change="hoursOverridden = false; calculate()"
                                    class="tattoo-select tattoo-unit-select"
                                    aria-label="Tattoo size unit"
                                >
                                    <option value="in">in</option>
                                    <option value="cm">cm</option>
                                </select>
                            </div>

                            <p
                                id="tattoo-size-help"
                                class="tattoo-help"
                            >
                                Longest dimension.
                            </p>
                        </div>

                        {{-- Preset --}}
                        <div class="tattoo-field">
                            <label
                                for="tattoo-preset"
                                class="tattoo-label"
                            >
                                Common size
                            </label>

                            <select
                                id="tattoo-preset"
                                class="tattoo-select"
                                @change="applyPreset($event.target.value); $event.target.value = ''"
                            >
                                <option value="">Choose a preset</option>
                                <option value="1">1 in — Tiny</option>
                                <option value="2">2 in — Small</option>
                                <option value="4">4 in — Medium</option>
                                <option value="6">6 in — Large</option>
                                <option value="10">10 in — Very large</option>
                            </select>
                        </div>

                    </div>

                    {{-- Hours / rate --}}
                    <div class="tattoo-grid tattoo-grid-2">

                        <div class="tattoo-field">
                            <div class="tattoo-label-row">
                                <label
                                    for="tattoo-hours"
                                    class="tattoo-label"
                                >
                                    Estimated chair time
                                </label>

                                <button
                                    type="button"
                                    class="tattoo-auto"
                                    :class="{ 'is-active': !hoursOverridden }"
                                    :aria-pressed="!hoursOverridden"
                                    @click="hoursOverridden = false; calculate()"
                                >
                                    Auto
                                </button>
                            </div>

                            <div class="tattoo-input-suffix">
                                <input
                                    id="tattoo-hours"
                                    type="number"
                                    min="0.25"
                                    step="0.25"
                                    inputmode="decimal"
                                    x-model.number="hours"
                                    @input="hoursOverridden = true; calculate()"
                                    class="tattoo-input"
                                >

                                <span>hours</span>
                            </div>

                            <p class="tattoo-help">
                                Auto is based on size and design factors.
                            </p>
                        </div>

                        <div
                            class="tattoo-field"
                            x-show="pricingMode === 'hourly'"
                            x-cloak
                        >
                            <label
                                for="tattoo-rate"
                                class="tattoo-label"
                            >
                                Artist hourly rate
                            </label>

                            <div class="tattoo-input-suffix">
                                <input
                                    id="tattoo-rate"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="rate"
                                    @input="calculate()"
                                    class="tattoo-input"
                                >

                                <span>/ hr</span>
                            </div>
                        </div>

                        <div
                            class="tattoo-field"
                            x-show="pricingMode === 'flat'"
                            x-cloak
                        >
                            <label
                                for="tattoo-flat-quote"
                                class="tattoo-label"
                            >
                                Flat quote
                            </label>

                            <div class="tattoo-input-suffix">
                                <input
                                    id="tattoo-flat-quote"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="flatQuote"
                                    @input="calculate()"
                                    class="tattoo-input"
                                >

                                <span>quote</span>
                            </div>
                        </div>

                        <div
                            class="tattoo-field"
                            x-show="pricingMode === 'day'"
                            x-cloak
                        >
                            <label
                                for="tattoo-day-rate"
                                class="tattoo-label"
                            >
                                Day rate
                            </label>

                            <div class="tattoo-input-suffix">
                                <input
                                    id="tattoo-day-rate"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="dayRate"
                                    @input="calculate()"
                                    class="tattoo-input"
                                >

                                <span>/ day</span>
                            </div>
                        </div>

                    </div>

                    {{-- Minimum --}}
                    <div class="tattoo-grid tattoo-grid-2">

                        <div class="tattoo-field">
                            <label
                                for="tattoo-minimum"
                                class="tattoo-label"
                            >
                                Shop minimum
                            </label>

                            <div class="tattoo-input-suffix">
                                <input
                                    id="tattoo-minimum"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="minimum"
                                    @input="calculate()"
                                    class="tattoo-input"
                                >

                                <span>minimum</span>
                            </div>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-market"
                                class="tattoo-label"
                            >
                                Market pricing
                            </label>

                            <select
                                id="tattoo-market"
                                x-model.number="market"
                                @change="calculate()"
                                class="tattoo-select"
                            >
                                <option :value="0.85">Lower-cost market</option>
                                <option :value="1">Typical market</option>
                                <option :value="1.15">Higher-cost market</option>
                            </select>
                        </div>

                    </div>

                    {{-- Design factors --}}
                    <div class="tattoo-subheading">
                        <span>Design details</span>
                    </div>

                    <div class="tattoo-grid tattoo-grid-2">

                        <div class="tattoo-field">
                            <label
                                for="tattoo-complexity"
                                class="tattoo-label"
                            >
                                Design complexity
                            </label>

                            <select
                                id="tattoo-complexity"
                                x-model="complexity"
                                @change="hoursOverridden = false; calculate()"
                                class="tattoo-select"
                            >
                                <option value="minimal">Minimal</option>
                                <option value="simple">Simple</option>
                                <option value="moderate">Moderate</option>
                                <option value="detailed">Detailed</option>
                                <option value="high">Highly detailed</option>
                            </select>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-color"
                                class="tattoo-label"
                            >
                                Ink coverage
                            </label>

                            <select
                                id="tattoo-color"
                                x-model="color"
                                @change="hoursOverridden = false; calculate()"
                                class="tattoo-select"
                            >
                                <option value="black">Black / grey</option>
                                <option value="color">Color</option>
                                <option value="full">Full color</option>
                            </select>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-placement"
                                class="tattoo-label"
                            >
                                Placement
                            </label>

                            <select
                                id="tattoo-placement"
                                x-model="placement"
                                @change="hoursOverridden = false; calculate()"
                                class="tattoo-select"
                            >
                                <option value="easy">Easy area</option>
                                <option value="normal">Typical area</option>
                                <option value="difficult">Difficult area</option>
                                <option value="sensitive">Sensitive area</option>
                            </select>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-shading"
                                class="tattoo-label"
                            >
                                Shading / fill
                            </label>

                            <select
                                id="tattoo-shading"
                                x-model="shading"
                                @change="hoursOverridden = false; calculate()"
                                class="tattoo-select"
                            >
                                <option value="line">Line work</option>
                                <option value="shade">Shading</option>
                                <option value="fill">Heavy fill</option>
                            </select>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-experience"
                                class="tattoo-label"
                            >
                                Artist experience
                            </label>

                            <select
                                id="tattoo-experience"
                                x-model="experience"
                                @change="calculate()"
                                class="tattoo-select"
                            >
                                <option value="emerging">Emerging artist</option>
                                <option value="established">Established artist</option>
                                <option value="specialist">Specialist artist</option>
                            </select>
                        </div>

                        <div class="tattoo-field">
                            <label
                                for="tattoo-coverup"
                                class="tattoo-label"
                            >
                                Cover-up
                            </label>

                            <select
                                id="tattoo-coverup"
                                x-model="coverup"
                                @change="hoursOverridden = false; calculate()"
                                class="tattoo-select"
                            >
                                <option value="none">No cover-up</option>
                                <option value="light">Light cover-up</option>
                                <option value="heavy">Heavy cover-up</option>
                            </select>
                        </div>

                    </div>

                    {{-- Currency --}}
                    <div class="tattoo-subheading">
                        <span>Display settings</span>
                    </div>

                    <div class="tattoo-grid tattoo-grid-2">

                        <div class="tattoo-field">
                            <label
                                for="tattoo-currency"
                                class="tattoo-label"
                            >
                                Currency
                            </label>

                            <select
                                id="tattoo-currency"
                                x-model="currency"
                                @change="calculate()"
                                class="tattoo-select"
                            >
                                <option value="USD">USD — US Dollar</option>
                                <option value="EUR">EUR — Euro</option>
                                <option value="GBP">GBP — British Pound</option>
                                <option value="CAD">CAD — Canadian Dollar</option>
                                <option value="AUD">AUD — Australian Dollar</option>
                                <option value="PKR">PKR — Pakistani Rupee</option>
                                <option value="custom">Custom symbol</option>
                            </select>

                            <p class="tattoo-help">
                                Display symbol only; no live FX conversion is performed.
                            </p>
                        </div>

                        <div
                            class="tattoo-field"
                            x-show="currency === 'custom'"
                            x-cloak
                        >
                            <label
                                for="tattoo-custom-symbol"
                                class="tattoo-label"
                            >
                                Custom symbol
                            </label>

                            <input
                                id="tattoo-custom-symbol"
                                type="text"
                                maxlength="5"
                                x-model="customSymbol"
                                @input="calculate()"
                                class="tattoo-input"
                                placeholder="¤"
                                aria-label="Custom currency symbol"
                            >
                        </div>

                    </div>

                </section>

                {{-- ============================================================
                     ADVANCED / APPOINTMENT COSTS
                     ============================================================ --}}
                <details class="tattoo-advanced">
                    <summary>
                        <span>
                            <strong>Appointment costs</strong>
                            <small>Optional fees, deposit and tip</small>
                        </span>

                        <span class="tattoo-summary-chevron" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="none">
                                <path
                                    d="m5 7.5 5 5 5-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    </summary>

                    <div class="tattoo-advanced-content">

                        <div class="tattoo-grid tattoo-grid-2">

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-deposit"
                                    class="tattoo-label"
                                >
                                    Deposit
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-deposit"
                                        type="number"
                                        min="0"
                                        step="1"
                                        inputmode="decimal"
                                        x-model.number="deposit"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>paid</span>
                                </div>
                            </div>

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-design-fee"
                                    class="tattoo-label"
                                >
                                    Design fee
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-design-fee"
                                        type="number"
                                        min="0"
                                        step="1"
                                        inputmode="decimal"
                                        x-model.number="designFee"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>fee</span>
                                </div>
                            </div>

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-aftercare"
                                    class="tattoo-label"
                                >
                                    Aftercare
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-aftercare"
                                        type="number"
                                        min="0"
                                        step="1"
                                        inputmode="decimal"
                                        x-model.number="aftercare"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>cost</span>
                                </div>
                            </div>

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-buffer"
                                    class="tattoo-label"
                                >
                                    Contingency
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-buffer"
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="1"
                                        inputmode="decimal"
                                        x-model.number="buffer"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>%</span>
                                </div>
                            </div>

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-tip"
                                    class="tattoo-label"
                                >
                                    Tip
                                </label>

                                <select
                                    id="tattoo-tip"
                                    x-model="tip"
                                    @change="calculate()"
                                    class="tattoo-select"
                                >
                                    <option value="0">No tip</option>
                                    <option value="15">15%</option>
                                    <option value="20">20%</option>
                                    <option value="25">25%</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <div
                                class="tattoo-field"
                                x-show="tip === 'custom'"
                                x-cloak
                            >
                                <label
                                    for="tattoo-custom-tip"
                                    class="tattoo-label"
                                >
                                    Custom tip
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-custom-tip"
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="1"
                                        inputmode="decimal"
                                        x-model.number="customTip"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>%</span>
                                </div>
                            </div>

                            <div class="tattoo-field">
                                <label
                                    for="tattoo-session-hours"
                                    class="tattoo-label"
                                >
                                    Hours per session
                                </label>

                                <div class="tattoo-input-suffix">
                                    <input
                                        id="tattoo-session-hours"
                                        type="number"
                                        min="0.25"
                                        step="0.25"
                                        inputmode="decimal"
                                        x-model.number="hoursPerSession"
                                        @input="calculate()"
                                        class="tattoo-input"
                                    >

                                    <span>hours</span>
                                </div>
                            </div>

                        </div>

                        <div class="tattoo-info-note">
                            <svg
                                viewBox="0 0 20 20"
                                fill="none"
                                aria-hidden="true"
                            >
                                <circle
                                    cx="10"
                                    cy="10"
                                    r="7.5"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                />
                                <path
                                    d="M10 9v4"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <circle
                                    cx="10"
                                    cy="6.5"
                                    r=".8"
                                    fill="currentColor"
                                />
                            </svg>

                            <span>
                                Tip, deposit, design and aftercare amounts are optional planning inputs.
                            </span>
                        </div>

                    </div>
                </details>

                {{-- ============================================================
                     ACTIONS
                     ============================================================ --}}
                <div class="tattoo-actions">

                    <button
                        type="button"
                        class="tattoo-button tattoo-button-primary"
                        :class="{ 'is-success': copied }"
                        @click="copyEstimate()"
                        :aria-label="copied ? 'Estimate copied to clipboard' : 'Copy estimate to clipboard'"
                    >
                        <svg
                            x-show="!copied"
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <rect
                                x="7"
                                y="7"
                                width="9"
                                height="9"
                                rx="1.5"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                            <path
                                d="M13 7V5.5A1.5 1.5 0 0 0 11.5 4h-6A1.5 1.5 0 0 0 4 5.5v6A1.5 1.5 0 0 0 5.5 13H7"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                        </svg>

                        <svg
                            x-show="copied"
                            x-cloak
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="m5 10.5 3.2 3.2L15.5 6.5"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span x-text="copied ? 'Copied' : 'Copy estimate'"></span>
                    </button>

                    <button
                        type="button"
                        class="tattoo-button tattoo-button-secondary"
                        @click="reset()"
                    >
                        Reset
                    </button>

                    <span
                        class="tattoo-copy-status"
                        role="status"
                        aria-live="polite"
                        x-text="copied ? 'Estimate copied to clipboard.' : ''"
                    ></span>

                </div>

            </div>

            {{-- ================================================================
                 RIGHT: RESULTS
                 ================================================================ --}}
            <aside
                class="tattoo-results"
                aria-labelledby="tattoo-result-heading"
            >

                <div class="tattoo-result-top">
                    <div>
                        <p class="tattoo-result-eyebrow">
                            Your estimate
                        </p>

                        <h2 id="tattoo-result-heading">
                            Estimated tattoo price
                        </h2>
                    </div>

                    <span
                        class="tattoo-method-badge"
                        x-text="result.method"
                    ></span>
                </div>

                {{-- Main estimate --}}
                <div
                    class="tattoo-price-box"
                    aria-live="polite"
                >
                    <span class="tattoo-price-label">
                        Estimated range
                    </span>

                    <div class="tattoo-price">
                        <span x-text="money(result.low)"></span>
                        <span class="tattoo-price-dash">–</span>
                        <span x-text="money(result.high)"></span>
                    </div>

                    <p>
                        Before tip and optional appointment extras.
                    </p>
                </div>

                {{-- Quick stats --}}
                <div class="tattoo-quick-stats">

                    <div class="tattoo-stat">
                        <span class="tattoo-stat-label">
                            Chair time
                        </span>

                        <strong>
                            <span x-text="result.hours.toFixed(2)"></span>
                            <small>hrs</small>
                        </strong>
                    </div>

                    <div class="tattoo-stat">
                        <span class="tattoo-stat-label">
                            Sessions
                        </span>

                        <strong x-text="result.sessions"></strong>
                    </div>

                    <div class="tattoo-stat tattoo-stat-highlight">
                        <span class="tattoo-stat-label">
                            Typical total
                        </span>

                        <strong x-text="money(result.typicalTotal)"></strong>
                    </div>

                </div>

                {{-- Remaining --}}
                <div class="tattoo-remaining">
                    <div>
                        <span>Estimated remaining</span>
                        <small>After your deposit</small>
                    </div>

                    <strong x-text="money(result.remaining)"></strong>
                </div>

                {{-- Breakdown --}}
                <div class="tattoo-breakdown">

                    <div class="tattoo-breakdown-heading">
                        <span>Price breakdown</span>
                    </div>

                    <div class="tattoo-breakdown-row">
                        <span>Base tattoo price</span>
                        <strong x-text="money(result.rawBase)"></strong>
                    </div>

                    <div class="tattoo-breakdown-row">
                        <span>Pricing adjustments</span>

                        <strong
                            :class="{
                                'tattoo-positive': result.adjustments > 0.005,
                                'tattoo-negative': result.adjustments < -0.005
                            }"
                            x-text="(result.adjustments >= 0 ? '+' : '') + money(result.adjustments)"
                        ></strong>
                    </div>

                    <div
                        class="tattoo-breakdown-row"
                        x-show="result.contingency > 0"
                    >
                        <span>
                            Contingency
                            <small x-text="'(' + Number(buffer || 0) + '%)'"></small>
                        </span>

                        <strong x-text="money(result.contingency)"></strong>
                    </div>

                    <div
                        class="tattoo-breakdown-row"
                        x-show="result.tip > 0"
                    >
                        <span>
                            Tip
                            <small x-text="'(' + result.tipPercent + '%)'"></small>
                        </span>

                        <strong x-text="money(result.tip)"></strong>
                    </div>

                    <div
                        class="tattoo-breakdown-row"
                        x-show="result.extras > 0"
                    >
                        <span>Design + aftercare</span>
                        <strong x-text="money(result.extras)"></strong>
                    </div>

                    <div
                        class="tattoo-breakdown-row"
                        x-show="result.deposit > 0"
                    >
                        <span>Deposit</span>
                        <strong x-text="'−' + money(result.deposit)"></strong>
                    </div>

                    <div class="tattoo-breakdown-total">
                        <span>Typical appointment total</span>
                        <strong x-text="money(result.typicalTotal)"></strong>
                    </div>

                </div>

                {{-- Assumptions --}}
                <div class="tattoo-assumptions">

                    <div class="tattoo-assumption-title">
                        <svg
                            viewBox="0 0 20 20"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M10 3.5 16.5 6v4.5c0 3.3-2.1 5.4-6.5 6.5-4.4-1.1-6.5-3.2-6.5-6.5V6L10 3.5Z"
                                stroke="currentColor"
                                stroke-width="1.4"
                            />
                            <path
                                d="M10 8v4"
                                stroke="currentColor"
                                stroke-width="1.4"
                                stroke-linecap="round"
                            />
                            <circle
                                cx="10"
                                cy="6.2"
                                r=".7"
                                fill="currentColor"
                            />
                        </svg>

                        Planning estimate
                    </div>

                    <p>
                        Tattoo pricing varies by artist, studio, location,
                        design and appointment conditions. Use this calculator
                        for planning; the final quote comes from the artist or studio.
                    </p>

                    <p x-show="currency !== 'USD'" x-cloak>
                        Currency selection changes the displayed symbol only.
                        It does not perform live exchange-rate conversion.
                    </p>

                </div>

            </aside>

        </div>
    </section>
</div>

@script
<script>
    Alpine.data('tattooPriceCalculator', () => ({
        pricingMode: 'hourly',

        size: 4,
        unit: 'in',

        hours: 2.5,
        hoursOverridden: false,

        rate: 150,
        minimum: 100,
        flatQuote: 500,
        dayRate: 900,

        complexity: 'moderate',
        color: 'black',
        placement: 'normal',
        shading: 'shade',
        experience: 'established',
        market: 1,
        coverup: 'none',

        deposit: 0,
        designFee: 0,
        aftercare: 0,

        buffer: 10,

        tip: '20',
        customTip: 20,

        hoursPerSession: 4,

        currency: 'USD',
        customSymbol: '¤',

        copied: false,
        copyTimer: null,

        result: {
            low: 0,
            high: 0,
            base: 0,
            rawBase: 0,
            adjustments: 0,
            contingency: 0,
            tip: 0,
            extras: 0,
            deposit: 0,
            typicalTotal: 0,
            remaining: 0,
            hours: 0,
            sessions: 1,
            method: 'Hourly',
            tipPercent: 20
        },

        init() {
            this.calculate();
        },

        applyPreset(value) {
            const preset = Number(value);

            if (!Number.isFinite(preset) || preset <= 0) {
                return;
            }

            this.unit = 'in';
            this.size = preset;
            this.hoursOverridden = false;

            this.calculate();
        },

        factor(group) {
            const maps = {
                complexity: {
                    minimal: 0.82,
                    simple: 0.92,
                    moderate: 1,
                    detailed: 1.18,
                    high: 1.38
                },

                color: {
                    black: 1,
                    color: 1.12,
                    full: 1.25
                },

                placement: {
                    easy: 0.92,
                    normal: 1,
                    difficult: 1.14,
                    sensitive: 1.25
                },

                shading: {
                    line: 0.90,
                    shade: 1,
                    fill: 1.15
                },

                experience: {
                    emerging: 0.92,
                    established: 1,
                    specialist: 1.10
                },

                coverup: {
                    none: 1,
                    light: 1.12,
                    heavy: 1.28
                }
            };

            return maps[group]?.[this[group]] ?? 1;
        },

        autoHours() {
            const rawSize = Math.max(
                0.25,
                Number(this.size) || 0
            );

            const inches = this.unit === 'cm'
                ? rawSize / 2.54
                : rawSize;

            const base = Math.max(
                0.5,
                Math.pow(Math.max(inches, 0.25), 0.72) * 0.62
            );

            return base
                * this.factor('complexity')
                * this.factor('color')
                * this.factor('placement')
                * this.factor('shading')
                * this.factor('coverup');
        },

        calculate() {
            const size = Math.max(
                0.25,
                Number(this.size) || 0
            );

            this.size = Number(size.toFixed(2));

            const rawAutoHours = this.autoHours();

            let hours = this.hoursOverridden
                ? Math.max(
                    0.25,
                    Number(this.hours) || 0.25
                )
                : rawAutoHours;

            if (!this.hoursOverridden) {
                this.hours = Number(
                    (Math.round(hours * 4) / 4).toFixed(2)
                );

                hours = this.hours;
            } else {
                this.hours = Number(
                    hours.toFixed(2)
                );
            }

            const market = Math.max(
                0.5,
                Math.min(
                    2,
                    Number(this.market) || 1
                )
            );

            const rate = Math.max(
                0,
                Number(this.rate) || 0
            );

            const minimum = Math.max(
                0,
                Number(this.minimum) || 0
            );

            const flatQuote = Math.max(
                0,
                Number(this.flatQuote) || 0
            );

            const dayRate = Math.max(
                0,
                Number(this.dayRate) || 0
            );

            const hoursPerSession = Math.max(
                0.25,
                Number(this.hoursPerSession) || 4
            );

            const lowHours = hours * 0.82;
            const highHours = hours * 1.22;

            let lowBase;
            let highBase;
            let rawBase;
            let method;

            if (this.pricingMode === 'flat') {
                lowBase = flatQuote;
                highBase = flatQuote;
                rawBase = flatQuote;
                method = 'Flat quote';

            } else if (this.pricingMode === 'day') {
                const sessions = Math.max(
                    1,
                    Math.ceil(hours / hoursPerSession)
                );

                lowBase = sessions * dayRate;
                highBase = sessions * dayRate;
                rawBase = sessions * dayRate;
                method = 'Day rate';

            } else {
                lowBase = lowHours * rate;
                highBase = highHours * rate;
                rawBase = hours * rate;
                method = 'Hourly';
            }

            const pricingFactor =
                market * this.factor('experience');

            lowBase *= pricingFactor;
            highBase *= pricingFactor;
            rawBase *= pricingFactor;

            lowBase = Math.max(
                lowBase,
                minimum
            );

            highBase = Math.max(
                highBase,
                minimum
            );

            rawBase = Math.max(
                rawBase,
                minimum
            );

            const bufferPercent = Math.max(
                0,
                Math.min(
                    100,
                    Number(this.buffer) || 0
                )
            );

            const bufferFactor =
                1 + (bufferPercent / 100);

            const low =
                lowBase * bufferFactor;

            const high =
                highBase * bufferFactor;

            const base =
                ((lowBase + highBase) / 2)
                * bufferFactor;

            const contingency =
                base - ((lowBase + highBase) / 2);

            const adjustments =
                ((lowBase + highBase) / 2)
                - rawBase;

            const tipPercent =
                this.tip === 'custom'
                    ? Math.max(
                        0,
                        Math.min(
                            100,
                            Number(this.customTip) || 0
                        )
                    )
                    : Math.max(
                        0,
                        Math.min(
                            100,
                            Number(this.tip) || 0
                        )
                    );

            const tip =
                base * (tipPercent / 100);

            const extras =
                Math.max(
                    0,
                    Number(this.designFee) || 0
                )
                +
                Math.max(
                    0,
                    Number(this.aftercare) || 0
                );

            const deposit =
                Math.max(
                    0,
                    Number(this.deposit) || 0
                );

            const typicalTotal =
                base + extras + tip;

            const remaining =
                Math.max(
                    0,
                    typicalTotal - deposit
                );

            const sessions =
                Math.max(
                    1,
                    Math.ceil(
                        hours / hoursPerSession
                    )
                );

            this.result = {
                low,
                high,
                base,
                rawBase,
                adjustments,
                contingency,
                tip,
                extras,
                deposit,
                typicalTotal,
                remaining,
                hours,
                sessions,
                method,
                tipPercent
            };
        },

        symbol() {
            if (this.currency === 'custom') {
                return this.customSymbol || '¤';
            }

            return {
                USD: '$',
                EUR: '€',
                GBP: '£',
                CAD: 'C$',
                AUD: 'A$',
                PKR: '₨'
            }[this.currency] || '';
        },

        money(value) {
            const amount = Number(value) || 0;

            return `${this.symbol()}${amount.toLocaleString(
                undefined,
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            )}`;
        },

        async copyEstimate() {
            const r = this.result;

            const text = [
                'Tattoo price estimate',
                `Estimated tattoo price: ${this.money(r.low)} – ${this.money(r.high)}`,
                `Typical total: ${this.money(r.typicalTotal)}`,
                `Estimated chair time: ${r.hours.toFixed(2)} hours`,
                `Sessions: ${r.sessions}`,
                `Method: ${r.method}`,
                `Base tattoo price: ${this.money(r.base)}`,
                `Tip: ${this.money(r.tip)}`,
                `Design + aftercare: ${this.money(r.extras)}`,
                `Deposit: ${this.money(r.deposit)}`,
                `Estimated remaining: ${this.money(r.remaining)}`,
                '',
                'Planning estimate only; final pricing is determined by the artist or studio.'
            ].join('\n');

            try {
                if (
                    navigator.clipboard &&
                    window.isSecureContext
                ) {
                    await navigator.clipboard.writeText(text);
                } else {
                    this.fallbackCopy(text);
                }

                this.copied = true;

                if (this.copyTimer) {
                    clearTimeout(this.copyTimer);
                }

                this.copyTimer = setTimeout(() => {
                    this.copied = false;
                    this.copyTimer = null;
                }, 1800);

            } catch (error) {
                try {
                    this.fallbackCopy(text);

                    this.copied = true;

                    if (this.copyTimer) {
                        clearTimeout(this.copyTimer);
                    }

                    this.copyTimer = setTimeout(() => {
                        this.copied = false;
                        this.copyTimer = null;
                    }, 1800);

                } catch (fallbackError) {
                    this.copied = false;
                }
            }
        },

        fallbackCopy(text) {
            const textarea =
                document.createElement('textarea');

            textarea.value = text;

            textarea.setAttribute(
                'readonly',
                ''
            );

            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            textarea.style.pointerEvents = 'none';

            document.body.appendChild(textarea);

            textarea.focus();
            textarea.select();
            textarea.setSelectionRange(
                0,
                textarea.value.length
            );

            const copied =
                document.execCommand('copy');

            textarea.remove();

            if (!copied) {
                throw new Error(
                    'Clipboard copy failed.'
                );
            }
        },

        reset() {
            if (this.copyTimer) {
                clearTimeout(this.copyTimer);
            }

            Object.assign(this, {
                pricingMode: 'hourly',

                size: 4,
                unit: 'in',

                hours: 2.5,
                hoursOverridden: false,

                rate: 150,
                minimum: 100,
                flatQuote: 500,
                dayRate: 900,

                complexity: 'moderate',
                color: 'black',
                placement: 'normal',
                shading: 'shade',
                experience: 'established',
                market: 1,
                coverup: 'none',

                deposit: 0,
                designFee: 0,
                aftercare: 0,

                buffer: 10,

                tip: '20',
                customTip: 20,

                hoursPerSession: 4,

                currency: 'USD',
                customSymbol: '¤',

                copied: false,
                copyTimer: null
            });

            this.calculate();
        }
    }));
</script>
@endscript

@assets
<style>
    [x-cloak] {
        display: none !important;
    }

    .tattoo-workspace {
        width: 100%;
        color: #0f172a;
    }

    .tattoo-calculator {
        width: 100%;
    }

    .tattoo-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(360px, 0.85fr);
        gap: 20px;
        align-items: start;
    }

    /* ================================================================
       LEFT PANEL
       ================================================================ */

    .tattoo-controls {
        min-width: 0;
    }

    .tattoo-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow:
            0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .tattoo-details-panel {
        padding: 22px;
    }

    .tattoo-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 22px;
    }

    .tattoo-section-heading h2 {
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        line-height: 1.35;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .tattoo-section-heading p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.55;
    }

    .tattoo-field {
        min-width: 0;
        margin-bottom: 17px;
    }

    .tattoo-grid {
        display: grid;
        gap: 16px;
    }

    .tattoo-grid-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .tattoo-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 7px;
    }

    .tattoo-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 13px;
        line-height: 1.35;
        font-weight: 600;
    }

    .tattoo-label-row .tattoo-label {
        margin-bottom: 0;
    }

    .tattoo-help {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
    }

    .tattoo-input,
    .tattoo-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        color: #0f172a;
        font-size: 14px;
        line-height: 1.3;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            background-color 0.15s ease;
    }

    .tattoo-input {
        padding: 10px 12px;
    }

    .tattoo-select {
        padding: 9px 36px 9px 12px;
        cursor: pointer;
    }

    .tattoo-input:hover,
    .tattoo-select:hover {
        border-color: #94a3b8;
    }

    .tattoo-input:focus,
    .tattoo-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow:
            0 0 0 3px rgba(99, 102, 241, 0.14);
    }

    .tattoo-input::placeholder {
        color: #94a3b8;
    }

    .tattoo-input-with-select {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 82px;
    }

    .tattoo-input-with-select .tattoo-input {
        border-radius: 9px 0 0 9px;
    }

    .tattoo-unit-select {
        border-left: 0;
        border-radius: 0 9px 9px 0;
    }

    .tattoo-input-suffix {
        display: flex;
        align-items: stretch;
        min-height: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        overflow: hidden;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .tattoo-input-suffix:focus-within {
        border-color: #6366f1;
        box-shadow:
            0 0 0 3px rgba(99, 102, 241, 0.14);
    }

    .tattoo-input-suffix .tattoo-input {
        min-height: 42px;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        flex: 1;
        min-width: 0;
    }

    .tattoo-input-suffix > span {
        display: flex;
        align-items: center;
        padding: 0 11px;
        border-left: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ================================================================
       METHOD TABS
       ================================================================ */

    .tattoo-method-field {
        margin-bottom: 20px;
    }

    .tattoo-tabs {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 5px;
        padding: 5px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
    }

    .tattoo-tab {
        min-height: 44px;
        padding: 9px 12px;
        border: 1px solid transparent;
        border-radius: 8px;
        background: transparent;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
        cursor: pointer;
        transition:
            background-color 0.15s ease,
            color 0.15s ease,
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .tattoo-tab:hover {
        background: #ffffff;
        color: #1e293b;
    }

    .tattoo-tab.is-active {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff;
        box-shadow:
            0 1px 3px rgba(15, 23, 42, 0.16);
    }

    .tattoo-tab.is-active:hover {
        background: #4338ca;
        border-color: #4338ca;
        color: #ffffff;
    }

    .tattoo-tab:focus-visible,
    .tattoo-auto:focus-visible,
    .tattoo-button:focus-visible {
        outline: 3px solid rgba(79, 70, 229, 0.22);
        outline-offset: 2px;
    }

    /* ================================================================
       AUTO BUTTON
       ================================================================ */

    .tattoo-auto {
        min-height: 30px;
        padding: 5px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        cursor: pointer;
        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .tattoo-auto:hover {
        border-color: #a5b4fc;
        color: #3730a3;
    }

    .tattoo-auto.is-active {
        border-color: #c7d2fe;
        background: #eef2ff;
        color: #3730a3;
    }

    /* ================================================================
       SUBHEADINGS
       ================================================================ */

    .tattoo-subheading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 7px 0 16px;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.055em;
    }

    .tattoo-subheading::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    /* ================================================================
       ADVANCED
       ================================================================ */

    .tattoo-advanced {
        margin-top: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        overflow: hidden;
    }

    .tattoo-advanced summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        min-height: 64px;
        padding: 12px 17px;
        list-style: none;
        cursor: pointer;
        user-select: none;
    }

    .tattoo-advanced summary::-webkit-details-marker {
        display: none;
    }

    .tattoo-advanced summary > span:first-child {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .tattoo-advanced summary strong {
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
    }

    .tattoo-advanced summary small {
        color: #64748b;
        font-size: 12px;
        font-weight: 400;
    }

    .tattoo-summary-chevron {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 7px;
        color: #64748b;
        background: #f8fafc;
        transition: transform 0.15s ease;
    }

    .tattoo-summary-chevron svg {
        width: 17px;
        height: 17px;
    }

    .tattoo-advanced[open] .tattoo-summary-chevron {
        transform: rotate(180deg);
    }

    .tattoo-advanced-content {
        padding: 2px 17px 18px;
        border-top: 1px solid #f1f5f9;
    }

    .tattoo-info-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 3px;
        padding: 11px 12px;
        border: 1px solid #e0e7ff;
        border-radius: 9px;
        background: #f8faff;
        color: #475569;
        font-size: 12px;
        line-height: 1.5;
    }

    .tattoo-info-note svg {
        flex: 0 0 auto;
        width: 17px;
        height: 17px;
        margin-top: 1px;
        color: #4f46e5;
    }

    /* ================================================================
       ACTIONS
       ================================================================ */

    .tattoo-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
        margin-top: 14px;
    }

    .tattoo-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .tattoo-button svg {
        width: 17px;
        height: 17px;
    }

    .tattoo-button-primary {
        border: 1px solid #4f46e5;
        background: #4f46e5;
        color: #ffffff;
        box-shadow:
            0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .tattoo-button-primary:hover {
        border-color: #4338ca;
        background: #4338ca;
    }

    .tattoo-button-primary.is-success {
        border-color: #059669;
        background: #059669;
        color: #ffffff;
    }

    .tattoo-button-primary.is-success:hover {
        border-color: #047857;
        background: #047857;
    }

    .tattoo-button-secondary {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
    }

    .tattoo-button-secondary:hover {
        border-color: #94a3b8;
        background: #f8fafc;
        color: #0f172a;
    }

    .tattoo-copy-status {
        min-height: 18px;
        color: #059669;
        font-size: 12px;
        font-weight: 600;
    }

    /* ================================================================
       RESULTS
       ================================================================ */

    .tattoo-results {
        position: sticky;
        top: 20px;
        min-width: 0;
        padding: 22px;
        border: 1px solid #dbe4f0;
        border-radius: 14px;
        background: #f8faff;
        box-shadow:
            0 3px 12px rgba(15, 23, 42, 0.05);
    }

    .tattoo-result-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .tattoo-result-eyebrow {
        margin: 0 0 3px;
        color: #6366f1;
        font-size: 11px;
        line-height: 1.3;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .tattoo-result-top h2 {
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        line-height: 1.35;
        font-weight: 700;
    }

    .tattoo-method-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 5px 9px;
        border: 1px solid #c7d2fe;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .tattoo-price-box {
        padding: 20px;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        background: #ffffff;
    }

    .tattoo-price-label {
        display: block;
        margin-bottom: 7px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    .tattoo-price {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 6px;
        color: #1e1b4b;
        font-size: clamp(27px, 3vw, 38px);
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -0.035em;
    }

    .tattoo-price-dash {
        color: #94a3b8;
        font-weight: 500;
    }

    .tattoo-price-box p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.45;
    }

    /* ================================================================
       QUICK STATS
       ================================================================ */

    .tattoo-quick-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-top: 10px;
    }

    .tattoo-stat {
        min-width: 0;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
    }

    .tattoo-stat-highlight {
        border-color: #c7d2fe;
        background: #eef2ff;
    }

    .tattoo-stat-label {
        display: block;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.3;
        font-weight: 600;
    }

    .tattoo-stat strong {
        display: block;
        overflow: hidden;
        color: #0f172a;
        font-size: 16px;
        line-height: 1.25;
        font-weight: 750;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tattoo-stat-highlight strong {
        color: #3730a3;
    }

    .tattoo-stat strong small {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    /* ================================================================
       REMAINING
       ================================================================ */

    .tattoo-remaining {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 10px;
        padding: 14px 15px;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        background: #ecfdf5;
    }

    .tattoo-remaining span,
    .tattoo-remaining small {
        display: block;
    }

    .tattoo-remaining span {
        color: #065f46;
        font-size: 13px;
        font-weight: 700;
    }

    .tattoo-remaining small {
        margin-top: 2px;
        color: #047857;
        font-size: 11px;
    }

    .tattoo-remaining strong {
        color: #047857;
        font-size: 18px;
        line-height: 1.2;
        font-weight: 800;
        white-space: nowrap;
    }

    /* ================================================================
       BREAKDOWN
       ================================================================ */

    .tattoo-breakdown {
        margin-top: 16px;
        border-top: 1px solid #dbe4f0;
    }

    .tattoo-breakdown-heading {
        padding: 14px 0 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .tattoo-breakdown-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        min-height: 34px;
        color: #64748b;
        font-size: 12px;
    }

    .tattoo-breakdown-row span {
        min-width: 0;
    }

    .tattoo-breakdown-row span small {
        color: #94a3b8;
        font-size: 11px;
    }

    .tattoo-breakdown-row strong {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .tattoo-positive {
        color: #b45309 !important;
    }

    .tattoo-negative {
        color: #059669 !important;
    }

    .tattoo-breakdown-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 7px;
        padding-top: 12px;
        border-top: 1px solid #dbe4f0;
    }

    .tattoo-breakdown-total span {
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
    }

    .tattoo-breakdown-total strong {
        color: #1e1b4b;
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* ================================================================
       ASSUMPTIONS
       ================================================================ */

    .tattoo-assumptions {
        margin-top: 16px;
        padding: 13px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
    }

    .tattoo-assumption-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .tattoo-assumption-title svg {
        width: 16px;
        height: 16px;
        color: #6366f1;
    }

    .tattoo-assumptions p {
        margin: 0;
        color: #64748b;
        font-size: 11px;
        line-height: 1.55;
    }

    .tattoo-assumptions p + p {
        margin-top: 7px;
        padding-top: 7px;
        border-top: 1px solid #f1f5f9;
    }

    /* ================================================================
       RESPONSIVE
       ================================================================ */

    @media (max-width: 1024px) {
        .tattoo-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .tattoo-results {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .tattoo-details-panel {
            padding: 17px;
        }

        .tattoo-grid-2 {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .tattoo-tabs {
            gap: 4px;
            padding: 4px;
        }

        .tattoo-tab {
            min-height: 44px;
            padding: 9px 7px;
            font-size: 12px;
        }

        .tattoo-actions {
            align-items: stretch;
        }

        .tattoo-button {
            flex: 1 1 auto;
        }

        .tattoo-copy-status {
            width: 100%;
        }

        .tattoo-results {
            padding: 17px;
        }

        .tattoo-price-box {
            padding: 17px;
        }

        .tattoo-price {
            font-size: 29px;
        }

        .tattoo-quick-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .tattoo-stat-highlight {
            grid-column: 1 / -1;
        }

        .tattoo-remaining {
            align-items: flex-start;
        }
    }

    @media (max-width: 390px) {
        .tattoo-tabs {
            grid-template-columns: 1fr;
        }

        .tattoo-tab {
            min-height: 42px;
        }

        .tattoo-quick-stats {
            grid-template-columns: 1fr;
        }

        .tattoo-stat-highlight {
            grid-column: auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .tattoo-tab,
        .tattoo-auto,
        .tattoo-button,
        .tattoo-input,
        .tattoo-select,
        .tattoo-input-suffix,
        .tattoo-summary-chevron {
            transition: none;
        }
    }
</style>
@endassets