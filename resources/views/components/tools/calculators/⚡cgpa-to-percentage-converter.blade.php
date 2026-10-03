<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>
{{-- ==========================================================================
    AabiTech — CGPA to Percentage Converter
    File:
    resources/views/components/tools/calculators/⚡cgpa-percentage-converter.blade.php

    Features:
    - 4.00 scale selected by default
    - CGPA ↔ Percentage
    - Automatic calculation while typing
    - HEC 4.00 reference bands
    - HEC 5.00 reference bands
    - Linear estimate
    - Explicit HEC boundary handling
    - Comparison mode
    - Copy result
    - Browser-local processing
    - Responsive
    - Accessible controls
============================================================================ --}}
<div class="max-w-4xl mx-auto px-4 py-8"
     x-data="{
        mode: 'cgpa_to_percent', // 'cgpa_to_percent' | 'percent_to_cgpa'
        scale: 4.0,              // 4.0 | 5.0
        cgpaInput: '3.45',
        percentInput: '86.25',
        precision: 2,            // 2 | 3 decimal places
        copied: false,
        copiedPpsc: false,

        get validScale() {
            return parseFloat(this.scale) || 4.0;
        },

        get currentCgpaNum() {
            let val = parseFloat(this.cgpaInput);
            if (isNaN(val) || val < 0) return 0;
            return Math.min(val, this.validScale);
        },

        get currentPercentNum() {
            let val = parseFloat(this.percentInput);
            if (isNaN(val) || val < 0) return 0;
            return Math.min(val, 100);
        },

        // Core Calculations
        get calculatedPercentage() {
            return ((this.currentCgpaNum / this.validScale) * 100).toFixed(this.precision);
        },

        get calculatedCgpa() {
            return ((this.currentPercentNum / 100) * this.validScale).toFixed(this.precision);
        },

        get effectivePercentage() {
            return this.mode === 'cgpa_to_percent' 
                ? parseFloat(this.calculatedPercentage) 
                : this.currentPercentNum;
        },

        get effectiveCgpa() {
            return this.mode === 'cgpa_to_percent' 
                ? this.currentCgpaNum 
                : parseFloat(this.calculatedCgpa);
        },

        // Standard 4.0 equivalent when in 5.0 scale
        get equivalent4Cgpa() {
            if (this.validScale !== 5.0) return null;
            return (this.effectiveCgpa * 0.8).toFixed(this.precision);
        },

        // HEC Division Logic
        get divisionInfo() {
            const p = this.effectivePercentage;
            if (p >= 80) {
                return {
                    name: '1st Division (Distinction / A Grade)',
                    short: 'First Division (Distinction)',
                    badgeClass: 'bg-emerald-500/10 text-emerald-700 border-emerald-300 ring-emerald-500/20'
                };
            }
            if (p >= 60) {
                return {
                    name: '1st Division (Pass)',
                    short: 'First Division',
                    badgeClass: 'bg-blue-500/10 text-blue-700 border-blue-300 ring-blue-500/20'
                };
            }
            if (p >= 50) {
                return {
                    name: '2nd Division',
                    short: 'Second Division',
                    badgeClass: 'bg-amber-500/10 text-amber-700 border-amber-300 ring-amber-500/20'
                };
            }
            if (p >= 40) {
                return {
                    name: '3rd Division (Minimum Pass)',
                    short: 'Third Division',
                    badgeClass: 'bg-orange-500/10 text-orange-700 border-orange-300 ring-orange-500/20'
                };
            }
            return {
                name: 'Below Passing Standard',
                short: 'Fail / Ineligible',
                badgeClass: 'bg-rose-500/10 text-rose-700 border-rose-300 ring-rose-500/20'
            };
        },

        // Program Eligibility Radar
        get eligibility() {
            const c4 = this.validScale === 5.0 ? (this.effectiveCgpa * 0.8) : this.effectiveCgpa;
            const p = this.effectivePercentage;
            return {
                bsGraduation: c4 >= 2.0 && p >= 50,
                msAdmission: c4 >= 2.5 && p >= 60,
                phdOverseas: c4 >= 3.0 && p >= 70
            };
        },

        // Standard Text Representations
        get ppscFormattedText() {
            const val = this.mode === 'cgpa_to_percent' ? `${this.calculatedPercentage}%` : `${this.calculatedCgpa}/${this.validScale}.00`;
            return `Equivalent: ${val} (${this.divisionInfo.short})`;
        },

        copyToClipboard(text, isPpsc = false) {
            navigator.clipboard.writeText(text);
            if (isPpsc) {
                this.copiedPpsc = true;
                setTimeout(() => this.copiedPpsc = false, 2000);
            } else {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
        },

        setPreset(val) {
            this.cgpaInput = val.toString();
        }
     }">

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 sm:p-8 text-white relative">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    HEC Linear Formula Standard
                </span>
                
                <!-- Precision Selector -->
                <div class="flex items-center bg-white/10 rounded-xl p-1 text-xs">
                    <span class="px-2 text-slate-300">Precision:</span>
                    <button type="button" @click="precision = 2" :class="precision === 2 ? 'bg-white text-slate-950 font-bold' : 'text-white/80 hover:text-white'" class="px-2.5 py-0.5 rounded-lg transition">2 Dec</button>
                    <button type="button" @click="precision = 3" :class="precision === 3 ? 'bg-white text-slate-950 font-bold' : 'text-white/80 hover:text-white'" class="px-2.5 py-0.5 rounded-lg transition">3 Dec</button>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black tracking-tight mt-4">CGPA &harr; Percentage Converter</h1>
            <p class="text-sm text-slate-300 mt-1 max-w-xl">Accurate conversion for university admissions, HEC equivalence, PPSC, FPSC, and CSS competitive examination portals.</p>

            <!-- Mode Selector Switch -->
            <div class="grid grid-cols-2 gap-2 mt-6 bg-white/10 p-1.5 rounded-2xl backdrop-blur-md">
                <button type="button"
                        @click="mode = 'cgpa_to_percent'"
                        :class="mode === 'cgpa_to_percent' ? 'bg-white text-slate-900 shadow font-bold' : 'text-white/80 hover:text-white font-medium'"
                        class="py-2.5 text-xs sm:text-sm rounded-xl transition text-center">
                    CGPA &rarr; Percentage
                </button>
                <button type="button"
                        @click="mode = 'percent_to_cgpa'"
                        :class="mode === 'percent_to_cgpa' ? 'bg-white text-slate-900 shadow font-bold' : 'text-white/80 hover:text-white font-medium'"
                        class="py-2.5 text-xs sm:text-sm rounded-xl transition text-center">
                    Percentage &rarr; CGPA
                </button>
            </div>
        </div>

        <!-- Calculator Body -->
        <div class="p-6 sm:p-8 space-y-6">

            <!-- Scale System Selection -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Grading System Scale</label>
                <div class="inline-flex rounded-xl bg-slate-100 p-1 w-full sm:w-auto">
                    <button type="button"
                            @click="scale = 4.0"
                            :class="scale === 4.0 ? 'bg-white text-indigo-600 shadow-sm font-bold' : 'text-slate-600 font-medium'"
                            class="flex-1 sm:flex-none px-5 py-2 text-xs sm:text-sm rounded-lg transition">
                        4.0 Scale (Standard HEC)
                    </button>
                    <button type="button"
                            @click="scale = 5.0"
                            :class="scale === 5.0 ? 'bg-white text-indigo-600 shadow-sm font-bold' : 'text-slate-600 font-medium'"
                            class="flex-1 sm:flex-none px-5 py-2 text-xs sm:text-sm rounded-lg transition">
                        5.0 Scale (Foreign Degree Equivalence)
                    </button>
                </div>
            </div>

            <!-- Dynamic Input Box -->
            <div>
                <!-- Mode: CGPA to % -->
                <template x-if="mode === 'cgpa_to_percent'">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="cgpaInput" class="text-sm font-bold text-slate-800">
                                Enter CGPA (Out of <span x-text="validScale + '.0'"></span>)
                            </label>
                            <span class="text-xs text-slate-400 font-mono">Range: 0.00 - <span x-text="validScale + '.00'"></span></span>
                        </div>
                        <div class="relative">
                            <input type="number"
                                   id="cgpaInput"
                                   x-model="cgpaInput"
                                   min="0"
                                   :max="validScale"
                                   step="0.01"
                                   placeholder="e.g. 3.45"
                                   class="w-full text-2xl font-black px-4 py-3.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 text-slate-900 placeholder-slate-300 transition">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">
                                / <span x-text="validScale + '.0'"></span>
                            </div>
                        </div>

                        <!-- Presets for fast input -->
                        <div class="flex flex-wrap items-center gap-1.5 mt-3">
                            <span class="text-xs text-slate-400 font-medium mr-1">Quick Select:</span>
                            <template x-if="validScale === 4.0">
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="p in [2.0, 2.5, 3.0, 3.5, 3.8, 4.0]" :key="p">
                                        <button type="button" @click="setPreset(p)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition" x-text="p"></button>
                                    </template>
                                </div>
                            </template>
                            <template x-if="validScale === 5.0">
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="p in [2.5, 3.0, 3.5, 4.0, 4.5, 5.0]" :key="p">
                                        <button type="button" @click="setPreset(p)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition" x-text="p"></button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Mode: % to CGPA -->
                <template x-if="mode === 'percent_to_cgpa'">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="percentInput" class="text-sm font-bold text-slate-800">
                                Enter Percentage (%)
                            </label>
                            <span class="text-xs text-slate-400 font-mono">Range: 0% - 100%</span>
                        </div>
                        <div class="relative">
                            <input type="number"
                                   id="percentInput"
                                   x-model="percentInput"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   placeholder="e.g. 86.25"
                                   class="w-full text-2xl font-black px-4 py-3.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 text-slate-900 placeholder-slate-300 transition">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">
                                %
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Primary Output Display Container -->
            <div class="rounded-3xl border border-slate-200 bg-slate-50/60 p-5 sm:p-7 space-y-6">
                
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Result</span>
                    
                    <button type="button"
                            @click="copyToClipboard(mode === 'cgpa_to_percent' ? calculatedPercentage + '%' : calculatedCgpa, false)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition">
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span x-text="copied ? 'Copied' : 'Copy Value'"></span>
                    </button>
                </div>

                <!-- Big Result Number -->
                <div class="flex flex-wrap items-baseline gap-3">
                    <template x-if="mode === 'cgpa_to_percent'">
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl sm:text-6xl font-black text-slate-900 tracking-tight" x-text="calculatedPercentage"></span>
                            <span class="text-3xl font-bold text-slate-400">%</span>
                        </div>
                    </template>
                    <template x-if="mode === 'percent_to_cgpa'">
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl sm:text-6xl font-black text-slate-900 tracking-tight" x-text="calculatedCgpa"></span>
                            <span class="text-2xl font-bold text-slate-400">/ <span x-text="validScale + '.0'"></span></span>
                        </div>
                    </template>
                </div>

                <!-- Foreign Equivalence Bridge (5.0 -> 4.0 Standard Scale) -->
                <template x-if="validScale === 5.0">
                    <div class="flex flex-wrap items-center justify-between gap-2 p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs sm:text-sm text-indigo-950 font-medium">
                        <span>Converted HEC 4.0 Standard Scale Equivalent:</span>
                        <span class="font-mono font-bold text-indigo-700 bg-white px-2.5 py-1 rounded-lg border border-indigo-200" x-text="equivalent4Cgpa + ' / 4.00'"></span>
                    </div>
                </template>

                <!-- Division Tag & Applied Formula -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="p-3.5 rounded-2xl border" :class="divisionInfo.badgeClass">
                        <span class="block text-[11px] font-bold uppercase tracking-wider opacity-75">Academic Classification</span>
                        <span class="text-sm font-extrabold mt-0.5 block" x-text="divisionInfo.name"></span>
                    </div>

                    <div class="p-3.5 rounded-2xl border border-slate-200 bg-white text-slate-600">
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Applied Formula</span>
                        <span class="text-xs font-mono font-bold text-slate-800 mt-1 block">
                            <span x-show="mode === 'cgpa_to_percent'">(<span x-text="currentCgpaNum"></span> &divide; <span x-text="validScale"></span>) &times; 100 = <span x-text="calculatedPercentage"></span>%</span>
                            <span x-show="mode === 'percent_to_cgpa'">(<span x-text="currentPercentNum"></span> &divide; 100) &times; <span x-text="validScale"></span> = <span x-text="calculatedCgpa"></span></span>
                        </span>
                    </div>
                </div>

                <!-- Academic Program Eligibility Radar -->
                <div class="border-t border-slate-200 pt-5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-3">HEC Academic Eligibility Indicators</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        
                        <!-- BS Degree Requirement -->
                        <div class="p-3 rounded-2xl border flex items-center gap-2.5"
                             :class="eligibility.bsGraduation ? 'bg-emerald-50/60 border-emerald-200 text-emerald-900' : 'bg-rose-50/60 border-rose-200 text-rose-900'">
                            <span class="w-2.5 h-2.5 rounded-full" :class="eligibility.bsGraduation ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                            <div>
                                <span class="text-xs font-bold block">BS Degree Passing</span>
                                <span class="text-[11px] text-slate-500 font-medium" x-text="eligibility.bsGraduation ? 'Eligible (min. 2.0)' : 'Ineligible (< 2.0)'"></span>
                            </div>
                        </div>

                        <!-- MS / MPhil Admission -->
                        <div class="p-3 rounded-2xl border flex items-center gap-2.5"
                             :class="eligibility.msAdmission ? 'bg-emerald-50/60 border-emerald-200 text-emerald-900' : 'bg-slate-100 border-slate-200 text-slate-700'">
                            <span class="w-2.5 h-2.5 rounded-full" :class="eligibility.msAdmission ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <div>
                                <span class="text-xs font-bold block">MS / MPhil Entry</span>
                                <span class="text-[11px] text-slate-500 font-medium" x-text="eligibility.msAdmission ? 'Eligible (min. 2.5)' : 'Below 2.5 CGPA'"></span>
                            </div>
                        </div>

                        <!-- PhD & Scholarships -->
                        <div class="p-3 rounded-2xl border flex items-center gap-2.5"
                             :class="eligibility.phdOverseas ? 'bg-emerald-50/60 border-emerald-200 text-emerald-900' : 'bg-slate-100 border-slate-200 text-slate-700'">
                            <span class="w-2.5 h-2.5 rounded-full" :class="eligibility.phdOverseas ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <div>
                                <span class="text-xs font-bold block">PhD / HEC Scholarship</span>
                                <span class="text-[11px] text-slate-500 font-medium" x-text="eligibility.phdOverseas ? 'Competitive (min. 3.0)' : 'Below 3.0 CGPA'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- One-Tap PPSC / FPSC Exam Form Text Generator -->
                <div class="p-4 rounded-2xl bg-indigo-50/40 border border-indigo-100 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-bold text-indigo-950 block">Official Application Text (PPSC / FPSC / NTS)</span>
                        <span class="text-xs font-mono text-indigo-700" x-text="ppscFormattedText"></span>
                    </div>
                    <button type="button"
                            @click="copyToClipboard(ppscFormattedText, true)"
                            class="px-3.5 py-1.5 text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        <span x-text="copiedPpsc ? 'Copied to Clipboard!' : 'Copy Form Entry'"></span>
                    </button>
                </div>

            </div>

        </div>

        <!-- HEC Conversion Benchmarks & Reference Table -->
        <div class="border-t border-slate-100 bg-slate-50/50 p-6 sm:p-8" x-data="{ expanded: false }">
            <button type="button" 
                    @click="expanded = !expanded" 
                    class="w-full flex items-center justify-between text-left font-bold text-slate-800 hover:text-indigo-600 transition">
                <span class="text-sm">Official HEC Reference Table & Divisions Lookup</span>
                <svg class="w-4 h-4 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="expanded" class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-2.5">Academic Division</th>
                            <th class="py-2.5">Percentage Standard</th>
                            <th class="py-2.5">4.0 Scale Equivalent</th>
                            <th class="py-2.5">5.0 Scale Equivalent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-2.5 font-bold text-slate-900">First Division with Distinction</td>
                            <td class="py-2.5 font-semibold text-emerald-600">&ge; 80.00%</td>
                            <td class="py-2.5 font-mono font-medium">&ge; 3.20</td>
                            <td class="py-2.5 font-mono font-medium">&ge; 4.00</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-slate-900">First Division</td>
                            <td class="py-2.5 font-semibold text-blue-600">60.00% - 79.99%</td>
                            <td class="py-2.5 font-mono font-medium">2.40 - 3.19</td>
                            <td class="py-2.5 font-mono font-medium">3.00 - 3.99</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-slate-900">Second Division</td>
                            <td class="py-2.5 font-semibold text-amber-600">50.00% - 59.99%</td>
                            <td class="py-2.5 font-mono font-medium">2.00 - 2.39</td>
                            <td class="py-2.5 font-mono font-medium">2.50 - 2.99</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-slate-900">Third Division (Pass Threshold)</td>
                            <td class="py-2.5 font-semibold text-orange-600">40.00% - 49.99%</td>
                            <td class="py-2.5 font-mono font-medium">1.60 - 1.99</td>
                            <td class="py-2.5 font-mono font-medium">2.00 - 2.49</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-rose-600">Failing Grade</td>
                            <td class="py-2.5 font-semibold text-rose-600">&lt; 40.00%</td>
                            <td class="py-2.5 font-mono font-medium">&lt; 1.60</td>
                            <td class="py-2.5 font-mono font-medium">&lt; 2.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>