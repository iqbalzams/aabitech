<?php

use Livewire\Component;

new class extends Component
{
    // Browser-only calculator.
    // Project inputs are calculated locally and are not sent to the server.
};
?>

<div
    x-data="constructionEstimateCalculator()"
    x-init="init()"
    x-cloak
    class="construction-workspace"
>
    <section
        class="construction-calculator"
        aria-labelledby="construction-estimate-heading"
    >
        <div class="construction-layout">

            {{-- ================================================================
                LEFT: INPUT WORKSPACE
            ================================================================= --}}
            <div class="construction-inputs">

                {{-- Project basics --}}
                <div class="construction-section">
                    <div class="construction-section-heading">
                        <div>
                            <h2
                                id="construction-estimate-heading"
                                class="construction-title"
                            >
                                Project estimate
                            </h2>

                            <p class="construction-help">
                                Enter your covered construction area and project details
                                to build a planning budget.
                            </p>
                        </div>

                        <span class="construction-local-badge">
                            Browser calculated
                        </span>
                    </div>

                    <div class="construction-grid construction-grid-4">

                        <div class="construction-field">
                            <label for="construction-country">
                                Country
                            </label>

                            <select
                                id="construction-country"
                                x-model="form.country"
                                @change="applyCountryProfile()"
                            >
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="GB">United Kingdom</option>
                                <option value="AU">Australia</option>
                                <option value="AE">United Arab Emirates</option>
                                <option value="PK">Pakistan</option>
                                <option value="IN">India</option>
                            </select>
                        </div>

                        <div class="construction-field">
                            <label for="construction-location">
                                Location profile
                            </label>

                            <select
                                id="construction-location"
                                x-model="form.location"
                                @change="applyLocationProfile()"
                            >
                                <template
                                    x-for="location in locationOptions"
                                    :key="location.value"
                                >
                                    <option
                                        :value="location.value"
                                        x-text="location.label"
                                    ></option>
                                </template>
                            </select>
                        </div>

                        <div class="construction-field">
                            <label for="construction-type">
                                Project type
                            </label>

                            <select
                                id="construction-type"
                                x-model="form.projectType"
                                @change="recalculate()"
                            >
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="renovation">Renovation</option>
                            </select>
                        </div>

                        <div class="construction-field">
                            <label for="construction-quality">
                                Construction quality
                            </label>

                            <select
                                id="construction-quality"
                                x-model="form.quality"
                                @change="recalculate()"
                            >
                                <option value="economy">Economy</option>
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                                <option value="luxury">Luxury</option>
                                <option value="custom">Custom rate</option>
                            </select>
                        </div>

                    </div>
                </div>


                {{-- ============================================================
                    ESTIMATE TYPE
                ================================================================= --}}
                <div class="construction-section">

                    <div class="construction-section-heading compact">
                        <div>
                            <h3>What are you estimating?</h3>
                            <p>
                                Choose the construction scope for your budget.
                            </p>
                        </div>
                    </div>

                    <div
                        class="construction-methods"
                        role="group"
                        aria-label="Construction estimate type"
                    >

                        <button
                            type="button"
                            class="construction-method"
                            :class="{ 'is-active': form.scope === 'complete' }"
                            :aria-pressed="form.scope === 'complete'"
                            @click="form.scope = 'complete'; recalculate()"
                        >
                            <span class="construction-method-title">
                                Complete house
                            </span>

                            <span class="construction-method-description">
                                Structure, systems and finishes
                            </span>
                        </button>

                        <button
                            type="button"
                            class="construction-method"
                            :class="{ 'is-active': form.scope === 'grey' }"
                            :aria-pressed="form.scope === 'grey'"
                            @click="form.scope = 'grey'; recalculate()"
                        >
                            <span class="construction-method-title">
                                Grey structure
                            </span>

                            <span class="construction-method-description">
                                Foundation, structure and shell
                            </span>
                        </button>

                        <button
                            type="button"
                            class="construction-method"
                            :class="{ 'is-active': form.scope === 'finishing' }"
                            :aria-pressed="form.scope === 'finishing'"
                            @click="form.scope = 'finishing'; recalculate()"
                        >
                            <span class="construction-method-title">
                                Finishing only
                            </span>

                            <span class="construction-method-description">
                                Interior and finishing allowance
                            </span>
                        </button>

                    </div>

                </div>


                {{-- ============================================================
                    AREA
                ================================================================= --}}
                <div class="construction-section">

                    <div class="construction-section-heading compact">
                        <div>
                            <h3>Building area</h3>
                            <p>
                                Use the total covered / built-up construction area.
                            </p>
                        </div>
                    </div>

                    <div class="construction-grid construction-grid-3">

                        <div class="construction-field construction-field-large">
                            <label for="construction-area">
                                Covered area
                            </label>

                            <div class="construction-input-with-unit">
                                <input
                                    id="construction-area"
                                    type="number"
                                    min="1"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="form.area"
                                    @input="recalculate()"
                                    aria-describedby="construction-area-help"
                                >

                                <select
                                    x-model="form.areaUnit"
                                    @change="recalculate()"
                                    aria-label="Area unit"
                                >
                                    <option value="sqft">sq ft</option>
                                    <option value="sqm">m²</option>
                                </select>
                            </div>

                            <p
                                id="construction-area-help"
                                class="construction-field-help"
                            >
                                <span
                                    x-text="formatNumber(result.areaSqft)"
                                ></span>
                                sq ft equivalent
                            </p>
                        </div>

                        <div class="construction-field">
                            <label for="construction-floors">
                                Number of floors
                            </label>

                            <input
                                id="construction-floors"
                                type="number"
                                min="1"
                                max="100"
                                step="1"
                                inputmode="numeric"
                                x-model.number="form.floors"
                                @input="recalculate()"
                            >

                            <p class="construction-field-help">
                                Including ground floor
                            </p>
                        </div>

                        <div class="construction-field">
                            <label for="construction-basement">
                                Basement
                            </label>

                            <select
                                id="construction-basement"
                                x-model="form.basement"
                                @change="recalculate()"
                            >
                                <option value="none">No basement</option>
                                <option value="partial">Partial basement</option>
                                <option value="full">Full basement</option>
                            </select>
                        </div>

                    </div>

                    <div
                        class="construction-presets"
                        aria-label="Common area presets"
                    >
                        <span class="construction-preset-label">
                            Quick area:
                        </span>

                        <template
                            x-for="preset in areaPresets"
                            :key="preset.label"
                        >
                            <button
                                type="button"
                                class="construction-preset"
                                @click="setAreaPreset(preset)"
                            >
                                <span x-text="preset.label"></span>
                            </button>
                        </template>
                    </div>

                </div>


                {{-- ============================================================
                    DESIGN FACTORS
                ================================================================= --}}
                <div class="construction-section">

                    <div class="construction-section-heading compact">
                        <div>
                            <h3>Project factors</h3>
                            <p>
                                These adjust the base construction rate.
                            </p>
                        </div>
                    </div>

                    <div class="construction-grid construction-grid-3">

                        <div class="construction-field">
                            <label for="construction-complexity">
                                Design complexity
                            </label>

                            <select
                                id="construction-complexity"
                                x-model="form.complexity"
                                @change="recalculate()"
                            >
                                <option value="simple">Simple</option>
                                <option value="standard">Standard</option>
                                <option value="complex">Complex</option>
                                <option value="custom">Highly customized</option>
                            </select>
                        </div>

                        <div class="construction-field">
                            <label for="construction-contract">
                                Construction mode
                            </label>

                            <select
                                id="construction-contract"
                                x-model="form.contractMode"
                                @change="recalculate()"
                            >
                                <option value="turnkey">Turnkey / complete</option>
                                <option value="materials">Materials + labour</option>
                                <option value="labour">Labour only</option>
                            </select>
                        </div>

                        <div class="construction-field">
                            <label for="construction-custom-rate">
                                Custom rate
                            </label>

                            <div class="construction-input-with-prefix">
                                <span
                                    x-text="currencySymbol()"
                                    aria-hidden="true"
                                ></span>

                                <input
                                    id="construction-custom-rate"
                                    type="number"
                                    min="0"
                                    step="1"
                                    inputmode="decimal"
                                    x-model.number="form.customRate"
                                    @input="recalculate()"
                                    :disabled="form.quality !== 'custom'"
                                    :aria-disabled="form.quality !== 'custom'"
                                >

                                <span> / sq ft</span>
                            </div>

                            <p class="construction-field-help">
                                Used only with Custom rate
                            </p>
                        </div>

                    </div>

                </div>


                {{-- ============================================================
                    ROOM / FACILITY INPUTS
                ================================================================= --}}
                <details class="construction-details">

                    <summary>
                        <span>
                            <strong>Rooms & facilities</strong>
                            <small>
                                Refine the estimate for residential projects
                            </small>
                        </span>

                        <span class="construction-summary-chevron">
                            +
                        </span>
                    </summary>

                    <div class="construction-details-content">

                        <div class="construction-grid construction-grid-4">

                            <div class="construction-field">
                                <label for="construction-bedrooms">
                                    Bedrooms
                                </label>

                                <input
                                    id="construction-bedrooms"
                                    type="number"
                                    min="0"
                                    max="30"
                                    step="1"
                                    x-model.number="form.bedrooms"
                                    @input="recalculate()"
                                >
                            </div>

                            <div class="construction-field">
                                <label for="construction-bathrooms">
                                    Bathrooms
                                </label>

                                <input
                                    id="construction-bathrooms"
                                    type="number"
                                    min="0"
                                    max="30"
                                    step="1"
                                    x-model.number="form.bathrooms"
                                    @input="recalculate()"
                                >
                            </div>

                            <div class="construction-field">
                                <label for="construction-kitchens">
                                    Kitchens
                                </label>

                                <input
                                    id="construction-kitchens"
                                    type="number"
                                    min="0"
                                    max="10"
                                    step="1"
                                    x-model.number="form.kitchens"
                                    @input="recalculate()"
                                >
                            </div>

                            <div class="construction-field">
                                <label for="construction-living">
                                    Living rooms
                                </label>

                                <input
                                    id="construction-living"
                                    type="number"
                                    min="0"
                                    max="20"
                                    step="1"
                                    x-model.number="form.livingRooms"
                                    @input="recalculate()"
                                >
                            </div>

                        </div>

                        <div class="construction-room-note">
                            Room counts are refinement factors only. The covered
                            construction area remains the primary cost basis.
                        </div>

                    </div>

                </details>


                {{-- ============================================================
                    COST ASSUMPTIONS
                ================================================================= --}}
                <details class="construction-details" open>

                    <summary>
                        <span>
                            <strong>Cost assumptions</strong>
                            <small>
                                Review or edit the rates used by the calculator
                            </small>
                        </span>

                        <span class="construction-summary-chevron">
                            +
                        </span>
                    </summary>

                    <div class="construction-details-content">

                        <div class="construction-rate-summary">

                            <div>
                                <span>Base rate</span>
                                <strong
                                    x-text="money(result.baseRate) + ' / sq ft'"
                                ></strong>
                            </div>

                            <div>
                                <span>Scope rate</span>
                                <strong
                                    x-text="money(result.scopeRate) + ' / sq ft'"
                                ></strong>
                            </div>

                            <div>
                                <span>Effective rate</span>
                                <strong
                                    x-text="money(result.effectiveRate) + ' / sq ft'"
                                ></strong>
                            </div>

                        </div>

                        <div class="construction-grid construction-grid-3">

                            <div class="construction-field">
                                <label for="construction-contingency">
                                    Contingency %
                                </label>

                                <input
                                    id="construction-contingency"
                                    type="number"
                                    min="0"
                                    max="50"
                                    step="0.5"
                                    x-model.number="form.contingency"
                                    @input="recalculate()"
                                >
                            </div>

                            <div class="construction-field">
                                <label for="construction-escalation">
                                    Price escalation %
                                </label>

                                <input
                                    id="construction-escalation"
                                    type="number"
                                    min="0"
                                    max="50"
                                    step="0.5"
                                    x-model.number="form.escalation"
                                    @input="recalculate()"
                                >
                            </div>

                            <div class="construction-field">
                                <label for="construction-tax">
                                    Tax / permit allowance %
                                </label>

                                <input
                                    id="construction-tax"
                                    type="number"
                                    min="0"
                                    max="30"
                                    step="0.5"
                                    x-model.number="form.tax"
                                    @input="recalculate()"
                                >
                            </div>

                        </div>

                        <div class="construction-adjustment-note">
                            Rates are editable planning assumptions. They are not
                            live contractor quotations.
                        </div>

                    </div>

                </details>


                {{-- ============================================================
                    MATERIAL PLANNING
                ================================================================= --}}
                <details class="construction-details">

                    <summary>
                        <span>
                            <strong>Material quantity planning</strong>
                            <small>
                                Optional thumb-rule quantities — not a structural BOQ
                            </small>
                        </span>

                        <span class="construction-summary-chevron">
                            +
                        </span>
                    </summary>

                    <div class="construction-details-content">

                        <div class="construction-material-grid">

                            <template
                                x-for="material in materials"
                                :key="material.key"
                            >
                                <div class="construction-material">

                                    <div>
                                        <strong
                                            x-text="material.name"
                                        ></strong>

                                        <span
                                            x-text="material.note"
                                        ></span>
                                    </div>

                                    <div class="construction-material-value">
                                        <strong
                                            x-text="formatQty(material.quantity())"
                                        ></strong>

                                        <span
                                            x-text="material.unit"
                                        ></span>
                                    </div>

                                    <label
                                        class="construction-material-rate"
                                    >
                                        <span>Planning rate</span>

                                        <div>
                                            <span
                                                x-text="currencySymbol()"
                                            ></span>

                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                x-model.number="material.rate"
                                                @input="recalculate()"
                                                :aria-label="material.name + ' planning rate'"
                                            >
                                        </div>
                                    </label>

                                </div>
                            </template>

                        </div>

                        <div class="construction-material-note">
                            Quantities use simplified planning factors and should
                            not replace architectural drawings, structural design,
                            specifications or a detailed BOQ.
                        </div>

                    </div>

                </details>


                {{-- ============================================================
                    ACTIONS
                ================================================================= --}}
                <div class="construction-actions">

                    <button
                        type="button"
                        class="construction-button construction-button-primary"
                        @click="copyEstimate()"
                        :aria-label="copied ? 'Estimate copied' : 'Copy construction estimate'"
                    >
                        <span
                            x-text="copied ? 'Copied' : 'Copy estimate'"
                        ></span>
                    </button>

                    <button
                        type="button"
                        class="construction-button construction-button-secondary"
                        @click="downloadEstimate()"
                    >
                        Download
                    </button>

                    <button
                        type="button"
                        class="construction-button construction-button-secondary"
                        @click="reset()"
                    >
                        Reset
                    </button>

                    <span
                        x-show="copied"
                        x-transition.opacity
                        x-cloak
                        class="construction-copy-status"
                        role="status"
                        aria-live="polite"
                    >
                        ✓ Estimate copied to clipboard
                    </span>

                </div>

            </div>


            {{-- ================================================================
                RIGHT: RESULTS
            ================================================================= --}}
            <aside
                class="construction-results"
                aria-label="Construction estimate results"
            >

                {{-- Main result --}}
                <div class="construction-result-card">

                    <div class="construction-result-top">
                        <div>
                            <span class="construction-result-eyebrow">
                                Estimated construction budget
                            </span>

                            <h3 class="construction-result-title">
                                Planning estimate
                            </h3>
                        </div>

                        <span class="construction-result-country">
                            <span x-text="form.country"></span>
                        </span>
                    </div>

                    <div
                        class="construction-result-total"
                        aria-live="polite"
                    >
                        <span x-text="money(result.total)"></span>
                    </div>

                    <div class="construction-result-range">
                        Planning range:
                        <strong
                            x-text="money(result.rangeLow) + ' – ' + money(result.rangeHigh)"
                        ></strong>
                    </div>

                    <div class="construction-result-stats">

                        <div>
                            <span>Covered area</span>

                            <strong>
                                <span
                                    x-text="formatNumber(result.areaSqft)"
                                ></span>
                                sq ft
                            </strong>
                        </div>

                        <div>
                            <span>Effective rate</span>

                            <strong
                                x-text="money(result.effectiveRate) + ' / sq ft'"
                            ></strong>
                        </div>

                        <div>
                            <span>Floors</span>

                            <strong
                                x-text="form.floors"
                            ></strong>
                        </div>

                        <div>
                            <span>Scope</span>

                            <strong
                                x-text="scopeLabel()"
                            ></strong>
                        </div>

                    </div>

                </div>


                {{-- Cost cards --}}
                <div class="construction-cost-cards">

                    <div class="construction-cost-card">
                        <span>Base construction</span>

                        <strong
                            x-text="money(result.base)"
                        ></strong>
                    </div>

                    <div class="construction-cost-card">
                        <span>Contingency</span>

                        <strong
                            x-text="money(result.contingency)"
                        ></strong>
                    </div>

                    <div class="construction-cost-card">
                        <span>Escalation</span>

                        <strong
                            x-text="money(result.escalation)"
                        ></strong>
                    </div>

                    <div class="construction-cost-card">
                        <span>Tax / permits</span>

                        <strong
                            x-text="money(result.tax)"
                        ></strong>
                    </div>

                </div>


                {{-- Breakdown --}}
                <div class="construction-breakdown">

                    <div class="construction-result-heading">
                        <div>
                            <h3>Cost breakdown</h3>
                            <p>Estimated share of the base construction budget.</p>
                        </div>
                    </div>

                    <div class="construction-breakdown-list">

                        <template
                            x-for="stage in result.stages"
                            :key="stage.key"
                        >
                            <div class="construction-breakdown-row">

                                <div class="construction-breakdown-name">
                                    <span
                                        class="construction-breakdown-dot"
                                        :style="'--stage-width:' + stage.share + '%'"
                                    ></span>

                                    <span x-text="stage.name"></span>
                                </div>

                                <div class="construction-breakdown-value">
                                    <span
                                        x-text="stage.share.toFixed(1) + '%'"
                                    ></span>

                                    <strong
                                        x-text="money(stage.amount)"
                                    ></strong>
                                </div>

                            </div>
                        </template>

                    </div>

                </div>


                {{-- Material summary --}}
                <div class="construction-material-result">

                    <div class="construction-result-heading">
                        <div>
                            <h3>Material planning</h3>
                            <p>Indicative quantities based on covered area.</p>
                        </div>
                    </div>

                    <div class="construction-material-result-list">

                        <template
                            x-for="material in materials.slice(0, 5)"
                            :key="material.key"
                        >
                            <div>
                                <span x-text="material.name"></span>

                                <strong>
                                    <span
                                        x-text="formatQty(material.quantity())"
                                    ></span>
                                    <small x-text="material.unit"></small>
                                </strong>
                            </div>
                        </template>

                    </div>

                </div>


                {{-- Disclaimer --}}
                <div class="construction-notice">

                    <strong>
                        Planning estimate only
                    </strong>

                    <p>
                        Actual construction cost depends on drawings, structural
                        requirements, site conditions, local labour and material
                        prices, specifications, permits and contractor quotations.
                    </p>

                </div>

                {{-- Warnings --}}
                <div
                    x-show="result.warnings.length"
                    x-cloak
                    class="construction-warnings"
                    role="alert"
                >
                    <strong>Review assumptions</strong>

                    <ul>
                        <template
                            x-for="warning in result.warnings"
                            :key="warning"
                        >
                            <li x-text="warning"></li>
                        </template>
                    </ul>
                </div>

            </aside>

        </div>
    </section>
</div>


@script
<script>
    Alpine.data('constructionEstimateCalculator', () => ({
        /* ================================================================
           COUNTRY / LOCATION PROFILES
        ================================================================ */

        profiles: {
            US: {
                currency: 'USD',
                defaultLocation: 'national',
                areaUnit: 'sqft',
                locations: {
                    national: {
                        label: 'United States — national planning',
                        multiplier: 1
                    },
                    highCost: {
                        label: 'High-cost metro area',
                        multiplier: 1.18
                    },
                    moderate: {
                        label: 'Moderate-cost market',
                        multiplier: 0.92
                    }
                },
                rates: {
                    economy: 165,
                    standard: 225,
                    premium: 310,
                    luxury: 425
                },
                materialFactors: {
                    cement: 0.04,
                    steel: 3.3,
                    concrete: 0.30,
                    lumber: 0.08,
                    drywall: 0.95,
                    flooring: 0.90
                }
            },

            CA: {
                currency: 'CAD',
                defaultLocation: 'national',
                areaUnit: 'sqft',
                locations: {
                    national: {
                        label: 'Canada — national planning',
                        multiplier: 1
                    },
                    highCost: {
                        label: 'High-cost metro area',
                        multiplier: 1.18
                    },
                    moderate: {
                        label: 'Moderate-cost market',
                        multiplier: 0.92
                    }
                },
                rates: {
                    economy: 190,
                    standard: 275,
                    premium: 370,
                    luxury: 500
                },
                materialFactors: {
                    cement: 0.04,
                    steel: 3.3,
                    concrete: 0.30,
                    lumber: 0.08,
                    drywall: 0.95,
                    flooring: 0.90
                }
            },

            GB: {
                currency: 'GBP',
                defaultLocation: 'national',
                areaUnit: 'sqm',
                locations: {
                    national: {
                        label: 'United Kingdom — national planning',
                        multiplier: 1
                    },
                    london: {
                        label: 'London / high-cost area',
                        multiplier: 1.22
                    },
                    regional: {
                        label: 'Regional market',
                        multiplier: 0.90
                    }
                },
                rates: {
                    economy: 1500,
                    standard: 2100,
                    premium: 2850,
                    luxury: 3800
                },
                materialFactors: {
                    cement: 0.04,
                    steel: 3.3,
                    concrete: 0.30,
                    lumber: 0.08,
                    drywall: 0.95,
                    flooring: 0.90
                }
            },

            AU: {
                currency: 'AUD',
                defaultLocation: 'national',
                areaUnit: 'sqm',
                locations: {
                    national: {
                        label: 'Australia — national planning',
                        multiplier: 1
                    },
                    sydney: {
                        label: 'Sydney / high-cost area',
                        multiplier: 1.18
                    },
                    regional: {
                        label: 'Regional market',
                        multiplier: 0.90
                    }
                },
                rates: {
                    economy: 2100,
                    standard: 2900,
                    premium: 3900,
                    luxury: 5200
                },
                materialFactors: {
                    cement: 0.04,
                    steel: 3.3,
                    concrete: 0.30,
                    lumber: 0.08,
                    drywall: 0.95,
                    flooring: 0.90
                }
            },

            AE: {
                currency: 'AED',
                defaultLocation: 'national',
                areaUnit: 'sqm',
                locations: {
                    national: {
                        label: 'UAE — national planning',
                        multiplier: 1
                    },
                    dubai: {
                        label: 'Dubai / premium market',
                        multiplier: 1.18
                    },
                    regional: {
                        label: 'Other emirates',
                        multiplier: 0.90
                    }
                },
                rates: {
                    economy: 1100,
                    standard: 1600,
                    premium: 2300,
                    luxury: 3300
                },
                materialFactors: {
                    cement: 0.04,
                    steel: 3.3,
                    concrete: 0.30,
                    lumber: 0.08,
                    drywall: 0.95,
                    flooring: 0.90
                }
            },

            PK: {
                currency: 'PKR',
                defaultLocation: 'lahore',
                areaUnit: 'sqft',
                locations: {
                    lahore: {
                        label: 'Lahore',
                        multiplier: 1
                    },
                    islamabad: {
                        label: 'Islamabad / Rawalpindi',
                        multiplier: 1.05
                    },
                    karachi: {
                        label: 'Karachi',
                        multiplier: 0.98
                    }
                },
                rates: {
                    economy: 2500,
                    standard: 3300,
                    premium: 4800,
                    luxury: 7000
                },
                materialFactors: {
                    cement: 0.40,
                    steel: 3.3,
                    concrete: 1.20,
                    lumber: 0.08,
                    drywall: 0.20,
                    flooring: 0.90
                }
            },

            IN: {
                currency: 'INR',
                defaultLocation: 'national',
                areaUnit: 'sqft',
                locations: {
                    national: {
                        label: 'India — national planning',
                        multiplier: 1
                    },
                    metro: {
                        label: 'Major metro',
                        multiplier: 1.18
                    },
                    regional: {
                        label: 'Regional market',
                        multiplier: 0.90
                    }
                },
                rates: {
                    economy: 1600,
                    standard: 2300,
                    premium: 3300,
                    luxury: 4500
                },
                materialFactors: {
                    cement: 0.40,
                    steel: 3.3,
                    concrete: 1.20,
                    lumber: 0.08,
                    drywall: 0.20,
                    flooring: 0.90
                }
            }
        },


        /* ================================================================
           STATE
        ================================================================ */

        form: {
            country: 'US',
            location: 'national',
            projectType: 'residential',
            scope: 'complete',
            quality: 'standard',

            areaUnit: 'sqft',
            area: 2000,
            floors: 2,
            basement: 'none',

            complexity: 'standard',
            contractMode: 'turnkey',

            bedrooms: 3,
            bathrooms: 2,
            kitchens: 1,
            livingRooms: 1,

            customRate: 225,

            contingency: 10,
            escalation: 0,
            tax: 0
        },

        copied: false,
        copyTimer: null,

        result: {
            total: 0,
            rangeLow: 0,
            rangeHigh: 0,
            base: 0,
            baseRate: 0,
            scopeRate: 0,
            effectiveRate: 0,
            contingency: 0,
            escalation: 0,
            tax: 0,
            areaSqft: 0,
            stages: [],
            warnings: []
        },


        /* ================================================================
           QUICK AREA PRESETS
        ================================================================ */

        areaPresets: [
            { label: '1,000 sq ft', unit: 'sqft', value: 1000 },
            { label: '1,500 sq ft', unit: 'sqft', value: 1500 },
            { label: '2,000 sq ft', unit: 'sqft', value: 2000 },
            { label: '2,500 sq ft', unit: 'sqft', value: 2500 },
            { label: '3,000 sq ft', unit: 'sqft', value: 3000 }
        ],


        /* ================================================================
           MATERIAL PLANNING FACTORS
        ================================================================ */

        materials: [],


        /* ================================================================
           STAGE ALLOCATION
        ================================================================ */

        stageDefinitions: [
            {
                key: 'site',
                name: 'Site & foundation',
                complete: 0.14,
                grey: 0.22,
                finishing: 0.00
            },
            {
                key: 'structure',
                name: 'Structure & framing',
                complete: 0.23,
                grey: 0.36,
                finishing: 0.00
            },
            {
                key: 'envelope',
                name: 'Walls & envelope',
                complete: 0.13,
                grey: 0.20,
                finishing: 0.00
            },
            {
                key: 'roof',
                name: 'Roof / slabs',
                complete: 0.09,
                grey: 0.14,
                finishing: 0.00
            },
            {
                key: 'mechanical',
                name: 'Mechanical systems',
                complete: 0.10,
                grey: 0.04,
                finishing: 0.12
            },
            {
                key: 'electrical',
                name: 'Electrical',
                complete: 0.07,
                grey: 0.02,
                finishing: 0.09
            },
            {
                key: 'plumbing',
                name: 'Plumbing',
                complete: 0.06,
                grey: 0.02,
                finishing: 0.08
            },
            {
                key: 'interior',
                name: 'Interior finishes',
                complete: 0.10,
                grey: 0.00,
                finishing: 0.43
            },
            {
                key: 'fixtures',
                name: 'Fixtures & final finishes',
                complete: 0.08,
                grey: 0.00,
                finishing: 0.28
            }
        ],


        /* ================================================================
           INITIALIZATION
        ================================================================ */

        init() {
            this.applyCountryProfile(false);
            this.rebuildMaterials();
            this.recalculate();
        },


        /* ================================================================
           COUNTRY
        ================================================================ */

        applyCountryProfile(recalculate = true) {
            const profile =
                this.profiles[this.form.country] ||
                this.profiles.US;

            this.form.location = profile.defaultLocation;
            this.form.currency = profile.currency;
            this.form.areaUnit = profile.areaUnit;

            this.rebuildMaterials();

            if (recalculate) {
                this.recalculate();
            }
        },


        applyLocationProfile() {
            this.recalculate();
        },


        /* ================================================================
           LOCATION OPTIONS
        ================================================================ */

        get locationOptions() {
            const profile =
                this.profiles[this.form.country] ||
                this.profiles.US;

            return Object.entries(profile.locations).map(
                ([value, data]) => ({
                    value,
                    label: data.label
                })
            );
        },


        /* ================================================================
           MATERIALS
        ================================================================ */

        rebuildMaterials() {
            const profile =
                this.profiles[this.form.country] ||
                this.profiles.US;

            const factor = profile.materialFactors;

            const materialDefinitions = [
                {
                    key: 'cement',
                    name: 'Cement',
                    unit: 'bags',
                    note: 'Approx. planning factor'
                },
                {
                    key: 'steel',
                    name: 'Steel / rebar',
                    unit: 'kg',
                    note: 'Structural planning allowance'
                },
                {
                    key: 'concrete',
                    name: 'Concrete',
                    unit: 'cu yd',
                    note: 'Approx. concrete quantity'
                },
                {
                    key: 'lumber',
                    name: 'Lumber',
                    unit: '100 board-ft',
                    note: 'Framing planning allowance'
                },
                {
                    key: 'drywall',
                    name: 'Drywall',
                    unit: 'sq ft',
                    note: 'Approx. surface quantity'
                },
                {
                    key: 'flooring',
                    name: 'Flooring',
                    unit: 'sq ft',
                    note: 'Approx. finish area'
                }
            ];

            const defaultRates = {
                US: {
                    cement: 8,
                    steel: 1.25,
                    concrete: 175,
                    lumber: 550,
                    drywall: 1.25,
                    flooring: 8
                },
                CA: {
                    cement: 11,
                    steel: 1.60,
                    concrete: 210,
                    lumber: 650,
                    drywall: 1.50,
                    flooring: 9
                },
                GB: {
                    cement: 8,
                    steel: 1.25,
                    concrete: 150,
                    lumber: 650,
                    drywall: 1.50,
                    flooring: 35
                },
                AU: {
                    cement: 11,
                    steel: 1.80,
                    concrete: 180,
                    lumber: 700,
                    drywall: 2,
                    flooring: 45
                },
                AE: {
                    cement: 16,
                    steel: 2.60,
                    concrete: 210,
                    lumber: 650,
                    drywall: 8,
                    flooring: 55
                },
                PK: {
                    cement: 1500,
                    steel: 280,
                    concrete: 130,
                    lumber: 450,
                    drywall: 180,
                    flooring: 260
                },
                IN: {
                    cement: 450,
                    steel: 75,
                    concrete: 110,
                    lumber: 500,
                    drywall: 120,
                    flooring: 90
                }
            };

            const rates =
                defaultRates[this.form.country] ||
                defaultRates.US;

            this.materials = materialDefinitions.map(material => ({
                ...material,
                factor: factor[material.key] || 0,
                rate: rates[material.key] || 0,

                quantity: () => {
                    return this.result.areaSqft *
                        (material.key === 'flooring'
                            ? material.factor
                            : material.factor);
                }
            }));
        },


        /* ================================================================
           AREA
        ================================================================ */

        areaSqft() {
            const value =
                Math.max(0, Number(this.form.area) || 0);

            if (this.form.areaUnit === 'sqm') {
                return value * 10.7639104167;
            }

            return value;
        },


        setAreaPreset(preset) {
            this.form.areaUnit = preset.unit;
            this.form.area = preset.value;
            this.recalculate();
        },


        /* ================================================================
           RATE CALCULATION
        ================================================================ */

        baseRate() {
            const profile =
                this.profiles[this.form.country] ||
                this.profiles.US;

            if (this.form.quality === 'custom') {
                return Math.max(
                    0,
                    Number(this.form.customRate) || 0
                );
            }

            return profile.rates[this.form.quality] || 0;
        },


        scopeMultiplier() {
            if (this.form.scope === 'grey') {
                return 0.62;
            }

            if (this.form.scope === 'finishing') {
                return 0.38;
            }

            return 1;
        },


        complexityMultiplier() {
            return {
                simple: 0.94,
                standard: 1,
                complex: 1.10,
                custom: 1.20
            }[this.form.complexity] || 1;
        },


        contractMultiplier() {
            return {
                turnkey: 1,
                materials: 0.94,
                labour: 0.72
            }[this.form.contractMode] || 1;
        },


        floorMultiplier() {
            const floors =
                Math.max(1, Number(this.form.floors) || 1);

            if (floors <= 1) {
                return 1;
            }

            return 1 + Math.min(0.12, (floors - 1) * 0.035);
        },


        basementMultiplier() {
            return {
                none: 1,
                partial: 1.12,
                full: 1.20
            }[this.form.basement] || 1;
        },


        roomAdjustment() {
            if (this.form.projectType !== 'residential') {
                return 1;
            }

            const bedrooms =
                Math.max(0, Number(this.form.bedrooms) || 0);

            const bathrooms =
                Math.max(0, Number(this.form.bathrooms) || 0);

            const kitchens =
                Math.max(0, Number(this.form.kitchens) || 0);

            /*
             * Keep room adjustment deliberately small.
             * Covered area remains the primary driver.
             */
            const adjustment =
                1 +
                Math.min(0.05, Math.max(0, bedrooms - 3) * 0.01) +
                Math.min(0.04, Math.max(0, bathrooms - 2) * 0.01) +
                Math.min(0.02, Math.max(0, kitchens - 1) * 0.01);

            return adjustment;
        },


        /* ================================================================
           STAGES
        ================================================================ */

        stageResults(base) {
            let definitions = this.stageDefinitions;

            return definitions
                .map(stage => {
                    const share =
                        Number(stage[this.form.scope]) || 0;

                    return {
                        key: stage.key,
                        name: stage.name,
                        share: share * 100,
                        amount: base * share
                    };
                })
                .filter(stage => stage.share > 0);
        },


        /* ================================================================
           MAIN CALCULATION
        ================================================================ */

        recalculate() {
            const area = this.areaSqft();

            const profile =
                this.profiles[this.form.country] ||
                this.profiles.US;

            const location =
                profile.locations[this.form.location] ||
                profile.locations[profile.defaultLocation];

            const rawBaseRate = this.baseRate();

            const scopeRate =
                rawBaseRate * this.scopeMultiplier();

            const effectiveRate =
                scopeRate *
                (location?.multiplier || 1) *
                this.complexityMultiplier() *
                this.contractMultiplier() *
                this.floorMultiplier() *
                this.basementMultiplier() *
                this.roomAdjustment();

            const base =
                area * effectiveRate;

            const contingency =
                base *
                this.clamp(this.form.contingency, 0, 50) /
                100;

            const escalation =
                (base + contingency) *
                this.clamp(this.form.escalation, 0, 50) /
                100;

            const beforeTax =
                base +
                contingency +
                escalation;

            const tax =
                beforeTax *
                this.clamp(this.form.tax, 0, 30) /
                100;

            const total =
                beforeTax + tax;

            /*
             * Planning range.
             *
             * This is intentionally a range rather than a claim
             * of exact construction cost.
             */
            const rangeLow =
                total * 0.90;

            const rangeHigh =
                total * 1.10;

            const warnings = [];

            if (!area) {
                warnings.push(
                    'Enter a covered construction area to calculate the budget.'
                );
            }

            if (this.form.basement !== 'none') {
                warnings.push(
                    'Basement costs can vary substantially with excavation, soil, waterproofing and site conditions.'
                );
            }

            if (this.form.projectType === 'renovation') {
                warnings.push(
                    'Renovation projects may require demolition and existing-condition allowances that this calculator does not model in detail.'
                );
            }

            if (this.form.contractMode === 'labour') {
                warnings.push(
                    'Labour-only mode excludes most material procurement costs.'
                );
            }

            warnings.push(
                'Use current local contractor and supplier quotations before committing to a construction budget.'
            );

            this.result = {
                total,
                rangeLow,
                rangeHigh,
                base,
                baseRate: rawBaseRate,
                scopeRate,
                effectiveRate,
                contingency,
                escalation,
                tax,
                areaSqft: area,
                stages: this.stageResults(base),
                warnings
            };
        },


        /* ================================================================
           FORMATTING
        ================================================================ */

        currencySymbol() {
            return {
                USD: '$',
                CAD: 'CA$',
                GBP: '£',
                AUD: 'A$',
                AED: 'AED',
                PKR: 'Rs',
                INR: '₹'
            }[this.form.currency] || this.form.currency;
        },


        money(value) {
            const number =
                Number(value) || 0;

            return this.currencySymbol() +
                new Intl.NumberFormat('en-US', {
                    maximumFractionDigits: 0
                }).format(number);
        },


        formatNumber(value) {
            return new Intl.NumberFormat('en-US', {
                maximumFractionDigits: 0
            }).format(Number(value) || 0);
        },


        formatQty(value) {
            return new Intl.NumberFormat('en-US', {
                maximumFractionDigits: 2
            }).format(Number(value) || 0);
        },


        scopeLabel() {
            return {
                complete: 'Complete',
                grey: 'Grey',
                finishing: 'Finishing'
            }[this.form.scope] || 'Complete';
        },


        clamp(value, min, max) {
            const number = Number(value);

            if (!Number.isFinite(number)) {
                return min;
            }

            return Math.min(
                max,
                Math.max(min, number)
            );
        },


        /* ================================================================
           COPY / DOWNLOAD
        ================================================================ */

        report() {
            return [
                'AabiTech Construction Estimate',
                '',
                'Country: ' + this.form.country,
                'Location profile: ' + this.form.location,
                'Project type: ' + this.form.projectType,
                'Construction scope: ' + this.scopeLabel(),
                'Quality: ' + this.form.quality,
                'Construction mode: ' + this.form.contractMode,
                'Covered area: ' + this.formatNumber(this.result.areaSqft) + ' sq ft',
                'Floors: ' + this.form.floors,
                'Basement: ' + this.form.basement,
                '',
                'Estimated budget: ' + this.money(this.result.total),
                'Planning range: ' +
                    this.money(this.result.rangeLow) +
                    ' - ' +
                    this.money(this.result.rangeHigh),
                'Effective rate: ' +
                    this.money(this.result.effectiveRate) +
                    ' / sq ft',
                '',
                'Base construction: ' + this.money(this.result.base),
                'Contingency: ' + this.money(this.result.contingency),
                'Price escalation: ' + this.money(this.result.escalation),
                'Tax / permits: ' + this.money(this.result.tax),
                '',
                'Stage breakdown:',
                ...this.result.stages.map(stage =>
                    '- ' +
                    stage.name +
                    ': ' +
                    this.money(stage.amount) +
                    ' (' +
                    stage.share.toFixed(1) +
                    '%)'
                ),
                '',
                'Planning assumption:',
                'This is an indicative construction planning estimate, not a contractor quotation or detailed BOQ.',
                'Confirm quantities, specifications and current local rates with qualified professionals.'
            ].join('\n');
        },


        async copyEstimate() {
            const text = this.report();

            if (this.copyTimer) {
                clearTimeout(this.copyTimer);
            }

            try {
                if (
                    navigator.clipboard &&
                    typeof navigator.clipboard.writeText === 'function'
                ) {
                    await navigator.clipboard.writeText(text);
                } else {
                    this.fallbackCopy(text);
                }

                this.copied = true;

                this.copyTimer = setTimeout(() => {
                    this.copied = false;
                    this.copyTimer = null;
                }, 1800);

            } catch (error) {
                try {
                    this.fallbackCopy(text);

                    this.copied = true;

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
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            textarea.style.pointerEvents = 'none';

            document.body.appendChild(textarea);

            textarea.select();
            textarea.setSelectionRange(
                0,
                textarea.value.length
            );

            const successful =
                document.execCommand('copy');

            textarea.remove();

            if (!successful) {
                throw new Error('Clipboard copy failed.');
            }
        },


        downloadEstimate() {
            const blob = new Blob(
                [this.report()],
                {
                    type: 'text/plain;charset=utf-8'
                }
            );

            const url =
                URL.createObjectURL(blob);

            const anchor =
                document.createElement('a');

            anchor.href = url;
            anchor.download =
                'aabitech-construction-estimate.txt';

            document.body.appendChild(anchor);
            anchor.click();
            anchor.remove();

            setTimeout(() => {
                URL.revokeObjectURL(url);
            }, 1000);
        },


        /* ================================================================
           RESET
        ================================================================ */

        reset() {
            if (this.copyTimer) {
                clearTimeout(this.copyTimer);
                this.copyTimer = null;
            }

            this.copied = false;

            this.form = {
                country: 'US',
                location: 'national',
                projectType: 'residential',
                scope: 'complete',
                quality: 'standard',

                areaUnit: 'sqft',
                area: 2000,
                floors: 2,
                basement: 'none',

                complexity: 'standard',
                contractMode: 'turnkey',

                bedrooms: 3,
                bathrooms: 2,
                kitchens: 1,
                livingRooms: 1,

                customRate: 225,

                contingency: 10,
                escalation: 0,
                tax: 0
            };

            this.applyCountryProfile();
            this.recalculate();
        }
    }));
</script>
@endscript


<style>
    [x-cloak] {
        display: none !important;
    }

    .construction-workspace {
        --construction-primary: #4f46e5;
        --construction-primary-dark: #4338ca;
        --construction-primary-soft: #eef2ff;
        --construction-border: #dbe2ea;
        --construction-border-light: #e8edf3;
        --construction-text: #172033;
        --construction-muted: #64748b;
        --construction-background: #f8fafc;
        --construction-success: #047857;
        --construction-warning: #a16207;

        color: var(--construction-text);
        font-size: 14px;
    }

    .construction-calculator {
        overflow: hidden;
        border: 1px solid var(--construction-border);
        border-radius: 16px;
        background: #fff;
        box-shadow:
            0 1px 2px rgba(15, 23, 42, .04),
            0 8px 24px rgba(15, 23, 42, .04);
    }

    .construction-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(340px, 420px);
        gap: 0;
    }

    .construction-inputs {
        min-width: 0;
        padding: 20px;
    }

    .construction-results {
        min-width: 0;
        padding: 20px;
        border-left: 1px solid var(--construction-border);
        background: #f8fafc;
    }

    .construction-section {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--construction-border-light);
    }

    .construction-section:last-child {
        border-bottom: 0;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .construction-section-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .construction-section-heading.compact {
        margin-bottom: 12px;
    }

    .construction-title,
    .construction-section-heading h3 {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -.01em;
    }

    .construction-section-heading p {
        margin: 4px 0 0;
        color: var(--construction-muted);
        font-size: 13px;
        line-height: 1.55;
    }

    .construction-local-badge {
        flex: 0 0 auto;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        background: #f0fdf4;
        padding: 6px 10px;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .construction-grid {
        display: grid;
        gap: 13px;
    }

    .construction-grid-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .construction-grid-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .construction-field {
        min-width: 0;
    }

    .construction-field label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 13px;
        font-weight: 650;
    }

    .construction-field input,
    .construction-field select,
    .construction-input-with-unit input,
    .construction-input-with-unit select,
    .construction-input-with-prefix input {
        width: 100%;
        height: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        color: #172033;
        font-size: 14px;
        outline: none;
        transition:
            border-color .15s ease,
            box-shadow .15s ease,
            background-color .15s ease;
    }

    .construction-field input,
    .construction-field select {
        padding: 0 12px;
    }

    .construction-field input:focus,
    .construction-field select:focus,
    .construction-input-with-unit:focus-within,
    .construction-input-with-prefix:focus-within {
        border-color: var(--construction-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }

    .construction-field input:disabled {
        cursor: not-allowed;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .construction-field-help {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 11px;
        line-height: 1.45;
    }

    .construction-input-with-unit {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 88px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
    }

    .construction-input-with-unit input {
        border: 0;
        border-radius: 0;
        box-shadow: none !important;
    }

    .construction-input-with-unit select {
        border: 0;
        border-left: 1px solid #cbd5e1;
        border-radius: 0;
        padding: 0 8px;
        font-size: 13px;
    }

    .construction-input-with-prefix {
        display: flex;
        align-items: center;
        overflow: hidden;
        height: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
    }

    .construction-input-with-prefix span {
        flex: 0 0 auto;
        color: #64748b;
        font-size: 12px;
    }

    .construction-input-with-prefix span:first-child {
        padding-left: 12px;
    }

    .construction-input-with-prefix span:last-child {
        padding-right: 10px;
        padding-left: 4px;
    }

    .construction-input-with-prefix input {
        height: 42px;
        border: 0;
        border-radius: 0;
        padding: 0 5px;
        box-shadow: none !important;
    }

    .construction-methods {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .construction-method {
        min-height: 78px;
        cursor: pointer;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #fff;
        padding: 12px;
        text-align: left;
        transition:
            border-color .15s ease,
            background-color .15s ease,
            box-shadow .15s ease,
            color .15s ease;
    }

    .construction-method:hover {
        border-color: #a5b4fc;
        background: #fafaff;
    }

    .construction-method.is-active {
    background: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: #ffffff !important;
    box-shadow: 0 2px 5px rgba(15, 23, 42, .14);
}

    .construction-method-title {
        display: block;
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
    }

    .construction-method.is-active .construction-method-title,
    .construction-method.is-active .construction-method-description {
        color: #ffffff !important;
    }
    .construction-method.is-active:hover,
.construction-method.is-active:focus-visible {
    background: #4338ca !important;
    border-color: #4338ca !important;
    color: #ffffff !important;
}

    .construction-method-description {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.4;
    }

    .construction-presets {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
    }

    .construction-preset-label {
        margin-right: 2px;
        color: #64748b;
        font-size: 12px;
    }

    .construction-preset {
        min-height: 34px;
        cursor: pointer;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        padding: 6px 10px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        transition: .15s ease;
    }

    .construction-preset:hover {
        border-color: #818cf8;
        color: #4338ca;
        background: #f8faff;
    }

    .construction-details {
        margin-bottom: 12px;
        border: 1px solid var(--construction-border);
        border-radius: 11px;
        background: #fff;
    }

    .construction-details summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        cursor: pointer;
        list-style: none;
        padding: 13px 14px;
    }

    .construction-details summary::-webkit-details-marker {
        display: none;
    }

    .construction-details summary strong {
        display: block;
        color: #1e293b;
        font-size: 13px;
    }

    .construction-details summary small {
        display: block;
        margin-top: 3px;
        color: #64748b;
        font-size: 11px;
        font-weight: 400;
    }

    .construction-summary-chevron {
        display: grid;
        width: 26px;
        height: 26px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 18px;
        line-height: 1;
    }

    .construction-details[open] .construction-summary-chevron {
        transform: rotate(45deg);
    }

    .construction-details-content {
        border-top: 1px solid var(--construction-border-light);
        padding: 15px;
    }

    .construction-room-note,
    .construction-adjustment-note,
    .construction-material-note {
        margin-top: 12px;
        border-radius: 8px;
        background: #f8fafc;
        padding: 9px 11px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }

    .construction-rate-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 14px;
    }

    .construction-rate-summary > div {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
        padding: 10px;
    }

    .construction-rate-summary span {
        display: block;
        color: #64748b;
        font-size: 11px;
    }

    .construction-rate-summary strong {
        display: block;
        margin-top: 3px;
        color: #172033;
        font-size: 13px;
    }

    .construction-material-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .construction-material {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 11px;
    }

    .construction-material > div:first-child strong {
        display: block;
        color: #334155;
        font-size: 13px;
    }

    .construction-material > div:first-child span {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 10px;
    }

    .construction-material-value {
        margin-top: 10px;
    }

    .construction-material-value strong {
        color: #172033;
        font-size: 17px;
    }

    .construction-material-value span {
        margin-left: 3px;
        color: #64748b;
        font-size: 11px;
    }

    .construction-material-rate {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 9px;
        color: #64748b;
        font-size: 10px;
    }

    .construction-material-rate > div {
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .construction-material-rate input {
        width: 90px;
        height: 32px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        padding: 0 7px;
        color: #334155;
        font-size: 12px;
        outline: none;
    }

    .construction-material-rate input:focus {
        border-color: var(--construction-primary);
        box-shadow: 0 0 0 2px rgba(79, 70, 229, .1);
    }

    .construction-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
    }

    .construction-button {
        min-height: 44px;
        cursor: pointer;
        border-radius: 9px;
        padding: 0 15px;
        font-size: 13px;
        font-weight: 650;
        transition: .15s ease;
    }

    .construction-button:focus-visible,
    .construction-method:focus-visible,
    .construction-preset:focus-visible {
        outline: 3px solid rgba(79, 70, 229, .22);
        outline-offset: 2px;
    }

    .construction-button-primary {
        border: 1px solid var(--construction-primary);
        background: var(--construction-primary);
        color: #fff;
    }

    .construction-button-primary:hover {
        border-color: var(--construction-primary-dark);
        background: var(--construction-primary-dark);
    }

    .construction-button-secondary {
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #334155;
    }

    .construction-button-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    .construction-copy-status {
        color: var(--construction-success);
        font-size: 12px;
        font-weight: 600;
    }

    /* Results */

    .construction-result-card {
        overflow: hidden;
        border: 1px solid #c7d2fe;
        border-radius: 13px;
        background: #eef2ff;
        padding: 17px;
    }

    .construction-result-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .construction-result-eyebrow {
        display: block;
        color: #4f46e5;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .construction-result-title {
        margin: 3px 0 0;
        color: #1e1b4b;
        font-size: 16px;
        font-weight: 750;
    }

    .construction-result-country {
        border: 1px solid #c7d2fe;
        border-radius: 999px;
        background: #fff;
        padding: 5px 8px;
        color: #4338ca;
        font-size: 10px;
        font-weight: 700;
    }

    .construction-result-total {
        margin-top: 15px;
        color: #111827;
        font-size: clamp(30px, 4vw, 40px);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -.035em;
        overflow-wrap: anywhere;
    }

    .construction-result-range {
        margin-top: 8px;
        color: #6366f1;
        font-size: 12px;
    }

    .construction-result-range strong {
        color: #3730a3;
    }

    .construction-result-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 7px;
        margin-top: 16px;
    }

    .construction-result-stats > div {
        border: 1px solid rgba(165, 180, 252, .55);
        border-radius: 8px;
        background: rgba(255,255,255,.65);
        padding: 9px;
    }

    .construction-result-stats span {
        display: block;
        color: #6366f1;
        font-size: 10px;
    }

    .construction-result-stats strong {
        display: block;
        margin-top: 3px;
        color: #1e1b4b;
        font-size: 12px;
    }

    .construction-cost-cards {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 10px;
    }

    .construction-cost-card {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #fff;
        padding: 11px;
    }

    .construction-cost-card span {
        display: block;
        color: #64748b;
        font-size: 10px;
    }

    .construction-cost-card strong {
        display: block;
        margin-top: 4px;
        color: #172033;
        font-size: 14px;
    }

    .construction-breakdown,
    .construction-material-result {
        margin-top: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
    }

    .construction-result-heading {
        border-bottom: 1px solid #e8edf3;
        padding: 12px 13px;
    }

    .construction-result-heading h3 {
        margin: 0;
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .construction-result-heading p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 10px;
        line-height: 1.45;
    }

    .construction-breakdown-list {
        padding: 2px 0;
    }

    .construction-breakdown-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 9px 13px;
        border-bottom: 1px solid #f1f5f9;
    }

    .construction-breakdown-row:last-child {
        border-bottom: 0;
    }

    .construction-breakdown-name {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 0;
        color: #475569;
        font-size: 11px;
    }

    .construction-breakdown-dot {
        display: block;
        width: 7px;
        height: 7px;
        flex: 0 0 auto;
        border-radius: 50%;
        background: #6366f1;
    }

    .construction-breakdown-value {
        display: flex;
        align-items: center;
        gap: 9px;
        flex: 0 0 auto;
    }

    .construction-breakdown-value > span {
        color: #94a3b8;
        font-size: 10px;
    }

    .construction-breakdown-value strong {
        color: #172033;
        font-size: 11px;
    }

    .construction-material-result-list {
        padding: 4px 13px;
    }

    .construction-material-result-list > div {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 11px;
    }

    .construction-material-result-list > div:last-child {
        border-bottom: 0;
    }

    .construction-material-result-list strong {
        color: #172033;
    }

    .construction-material-result-list small {
        margin-left: 3px;
        color: #64748b;
        font-size: 9px;
        font-weight: 400;
    }

    .construction-notice {
        margin-top: 10px;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        background: #eff6ff;
        padding: 11px 12px;
        color: #1e40af;
    }

    .construction-notice strong {
        display: block;
        font-size: 11px;
    }

    .construction-notice p {
        margin: 4px 0 0;
        font-size: 10px;
        line-height: 1.55;
    }

    .construction-warnings {
        margin-top: 10px;
        border: 1px solid #fde68a;
        border-radius: 9px;
        background: #fffbeb;
        padding: 11px 12px;
        color: #854d0e;
    }

    .construction-warnings strong {
        font-size: 11px;
    }

    .construction-warnings ul {
        margin: 5px 0 0;
        padding-left: 16px;
        font-size: 10px;
        line-height: 1.55;
    }

    @media (max-width: 1100px) {
        .construction-layout {
            grid-template-columns: 1fr;
        }

        .construction-results {
            border-top: 1px solid var(--construction-border);
            border-left: 0;
        }
    }

    @media (max-width: 850px) {
        .construction-grid-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .construction-grid-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .construction-inputs,
        .construction-results {
            padding: 14px;
        }

        .construction-section-heading {
            flex-direction: column;
        }

        .construction-local-badge {
            align-self: flex-start;
        }

        .construction-grid-4,
        .construction-grid-3,
        .construction-methods,
        .construction-rate-summary,
        .construction-material-grid {
            grid-template-columns: 1fr;
        }

        .construction-method {
            min-height: 70px;
        }

        .construction-result-total {
            font-size: 31px;
        }

        .construction-actions {
            align-items: stretch;
        }

        .construction-actions .construction-button {
            flex: 1 1 auto;
        }

        .construction-copy-status {
            width: 100%;
        }
    }
    .construction-field input,
.construction-field select {
    min-height: 46px;
    font-size: 15px;
    color: #0f172a;
}

.construction-field label {
    font-size: 14px;
    font-weight: 700;
}

.construction-section-heading h3,
.construction-title {
    font-size: 19px;
}

.construction-section-heading p {
    font-size: 13px;
}
</style>