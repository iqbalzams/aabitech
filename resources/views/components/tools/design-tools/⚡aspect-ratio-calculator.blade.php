<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiAspectRatioCalculator()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full"
>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .aabi-aspect-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgb(203 213 225) transparent;
        }

        .aabi-aspect-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .aabi-aspect-scroll::-webkit-scrollbar-thumb {
            background: rgb(203 213 225);
            border-radius: 999px;
        }

        .aabi-number-input::-webkit-inner-spin-button,
        .aabi-number-input::-webkit-outer-spin-button {
            opacity: 1;
        }

        .aabi-drop-active {
            border-color: rgb(99 102 241) !important;
            background: rgb(238 242 255) !important;
        }

        .aabi-preview-grid {
            background-image:
                linear-gradient(to right, rgba(148,163,184,.12) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148,163,184,.12) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>

    {{-- ============================================================
        PRIVACY / LOCAL PROCESSING
    ============================================================= --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
        <div class="flex items-center gap-2 text-xs text-slate-600">
            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-50 text-emerald-600">
                🔒
            </span>
            <span>
                <strong class="font-semibold text-slate-800">Browser-local processing</strong>
                · Your calculations and uploaded image stay on your device.
            </span>
        </div>

        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span
                class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                aria-hidden="true"
            ></span>
            No server upload
        </div>
    </div>

    {{-- ============================================================
        MODE NAVIGATION
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/70 p-3 sm:p-4">
            <div
                class="aabi-aspect-scroll flex gap-1 overflow-x-auto"
                role="tablist"
                aria-label="Aspect ratio calculator modes"
            >
                <button
                    type="button"
                    role="tab"
                    data-active-group="aspect-main-mode"
                    data-active-value="ratio"
                    :aria-selected="mainMode === 'ratio'"
                    :class="{ 'is-active': mainMode === 'ratio' }"
                    @click="setMainMode('ratio')"
                    class="compact-tab"
                >
                    Ratio
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="aspect-main-mode"
                    data-active-value="resize"
                    :aria-selected="mainMode === 'resize'"
                    :class="{ 'is-active': mainMode === 'resize' }"
                    @click="setMainMode('resize')"
                    class="compact-tab"
                >
                    Resize
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="aspect-main-mode"
                    data-active-value="crop"
                    :aria-selected="mainMode === 'crop'"
                    :class="{ 'is-active': mainMode === 'crop' }"
                    @click="setMainMode('crop')"
                    class="compact-tab"
                >
                    Crop
                </button>

                <button
                    type="button"
                    role="tab"
                    data-active-group="aspect-main-mode"
                    data-active-value="compare"
                    :aria-selected="mainMode === 'compare'"
                    :class="{ 'is-active': mainMode === 'compare' }"
                    @click="setMainMode('compare')"
                    class="compact-tab"
                >
                    Compare
                </button>
            </div>
        </div>

        {{-- ========================================================
            RATIO MODE
        ========================================================= --}}
        <template x-if="mainMode === 'ratio'">
            <div class="grid lg:grid-cols-[1.08fr_.92fr]">
                <div class="border-b border-slate-200 p-5 lg:border-b-0 lg:border-r sm:p-6">

                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">
                                Ratio calculator
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Find a ratio, calculate a missing dimension, or convert a ratio to dimensions.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="reset()"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                        >
                            Reset
                        </button>
                    </div>

                    {{-- Calculation direction --}}
                    <div class="mb-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Calculate
                        </label>

                        <div class="grid grid-cols-3 gap-2">
                            <button
                                type="button"
                                data-active-group="ratio-calculation"
                                data-active-value="height"
                                :class="{ 'is-active': ratioMode === 'height' }"
                                @click="ratioMode = 'height'; calculateRatio()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Width → Height
                            </button>

                            <button
                                type="button"
                                data-active-group="ratio-calculation"
                                data-active-value="width"
                                :class="{ 'is-active': ratioMode === 'width' }"
                                @click="ratioMode = 'width'; calculateRatio()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Height → Width
                            </button>

                            <button
                                type="button"
                                data-active-group="ratio-calculation"
                                data-active-value="ratio"
                                :class="{ 'is-active': ratioMode === 'ratio' }"
                                @click="ratioMode = 'ratio'; calculateRatio()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Find Ratio
                            </button>
                        </div>
                    </div>

                    {{-- Dimensions --}}
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label
                                for="aspect-width"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Width
                            </label>

                            <div class="relative">
                                <input
                                    id="aspect-width"
                                    type="number"
                                    min="0"
                                    step="any"
                                    x-model.number="width"
                                    @input="calculateRatio()"
                                    class="aabi-number-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-lg font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                    placeholder="1920"
                                >

                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">
                                    <span x-text="unit"></span>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                for="aspect-height"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Height
                            </label>

                            <div class="relative">
                                <input
                                    id="aspect-height"
                                    type="number"
                                    min="0"
                                    step="any"
                                    x-model.number="height"
                                    @input="calculateRatio()"
                                    class="aabi-number-input w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-lg font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                    placeholder="1080"
                                >

                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">
                                    <span x-text="unit"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Ratio --}}
                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-slate-700">
                                Aspect ratio
                            </label>

                            <button
                                type="button"
                                @click="simplifyCurrentRatio()"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800"
                            >
                                Simplify
                            </button>
                        </div>

                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                            <input
                                type="number"
                                min="0"
                                step="any"
                                x-model.number="ratioWidth"
                                @input="calculateRatio()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-center font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="16"
                                aria-label="Ratio width"
                            >

                            <span class="font-bold text-slate-500">:</span>

                            <input
                                type="number"
                                min="0"
                                step="any"
                                x-model.number="ratioHeight"
                                @input="calculateRatio()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-center font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="9"
                                aria-label="Ratio height"
                            >
                        </div>

                        <p
                            x-show="error"
                            x-text="error"
                            class="mt-2 text-xs font-medium text-red-600"
                        ></p>
                    </div>

                    {{-- Unit --}}
                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Unit
                        </label>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="item in units" :key="item">
                                <button
                                    type="button"
                                    data-active-group="aspect-unit"
                                    :data-active-value="item"
                                    :class="{ 'is-active': unit === item }"
                                    @click="setUnit(item)"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600"
                                    x-text="item"
                                ></button>
                            </template>
                        </div>
                    </div>

                    {{-- Common ratios --}}
                    <div class="mt-6">
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Common ratios
                            </h3>

                            <button
                                type="button"
                                @click="showAllRatios = !showAllRatios"
                                class="text-xs font-medium text-indigo-600"
                                x-text="showAllRatios ? 'Show less' : 'Show all'"
                            ></button>
                        </div>

                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                            <template
                                x-for="preset in visibleRatios"
                                :key="preset.id"
                            >
                                <button
                                    type="button"
                                    data-active-group="aspect-ratio-preset"
                                    :data-active-value="preset.id"
                                    :class="{ 'is-active': isRatioActive(preset) }"
                                    @click="applyRatio(preset.width, preset.height)"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-2.5 text-xs font-medium text-slate-700"
                                >
                                    <span x-text="preset.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Preset library --}}
                    <div class="mt-6">
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Preset library
                            </h3>

                            <input
                                type="search"
                                x-model="presetSearch"
                                placeholder="Search presets..."
                                class="w-40 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs outline-none focus:border-indigo-500"
                                aria-label="Search aspect ratio presets"
                            >
                        </div>

                        <div class="mb-3 flex gap-1 overflow-x-auto pb-1">
                            <template x-for="category in presetCategories" :key="category">
                                <button
                                    type="button"
                                    data-active-group="preset-category"
                                    :data-active-value="category"
                                    :class="{ 'is-active': presetCategory === category }"
                                    @click="presetCategory = category"
                                    class="compact-tab"
                                    x-text="category"
                                ></button>
                            </template>
                        </div>

                        <div class="max-h-44 overflow-y-auto rounded-xl border border-slate-200">
                            <template x-if="filteredPresets.length === 0">
                                <div class="p-4 text-center text-xs text-slate-500">
                                    No presets found.
                                </div>
                            </template>

                            <template x-for="preset in filteredPresets" :key="preset.id">
                                <button
                                    type="button"
                                    @click="applyDimensionPreset(preset)"
                                    class="flex w-full items-center justify-between border-b border-slate-100 px-3 py-2.5 text-left last:border-b-0 hover:bg-slate-50"
                                >
                                    <span>
                                        <span
                                            class="block text-xs font-semibold text-slate-800"
                                            x-text="preset.name"
                                        ></span>
                                        <span
                                            class="block text-[11px] text-slate-500"
                                            x-text="preset.width + ' × ' + preset.height + ' · ' + preset.category"
                                        ></span>
                                    </span>

                                    <span
                                        class="text-xs font-semibold text-indigo-600"
                                        x-text="preset.ratio"
                                    ></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Custom preset --}}
                    <div class="mt-5 rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-4">
                        <div class="mb-3">
                            <h3 class="text-xs font-semibold text-slate-800">
                                Save custom preset
                            </h3>
                            <p class="mt-1 text-[11px] text-slate-500">
                                Save a ratio and dimensions locally for future use.
                            </p>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-3">
                            <input
                                type="text"
                                x-model="customPresetName"
                                placeholder="Preset name"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-indigo-500"
                            >

                            <input
                                type="number"
                                min="1"
                                step="any"
                                x-model.number="customPresetWidth"
                                placeholder="Width"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-indigo-500"
                            >

                            <input
                                type="number"
                                min="1"
                                step="any"
                                x-model.number="customPresetHeight"
                                placeholder="Height"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-indigo-500"
                            >
                        </div>

                        <button
                            type="button"
                            @click="saveCustomPreset()"
                            class="mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                        >
                            Save preset
                        </button>
                    </div>
                </div>

                {{-- Result --}}
                <div class="bg-slate-50/60 p-5 sm:p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">
                                Result
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Live aspect-ratio calculation.
                            </p>
                        </div>

                        <span
                            class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600"
                            x-text="simplifiedRatio"
                        ></span>
                    </div>

                    {{-- Visual preview --}}
                    <div class="aabi-preview-grid mb-4 flex min-h-[250px] items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white p-5">
                        <div
                            class="flex items-center justify-center rounded-lg border-2 border-indigo-500 bg-indigo-50/70 shadow-sm transition-all duration-200"
                            :style="previewStyle"
                        >
                            <div class="px-3 text-center">
                                <div
                                    class="text-xs font-semibold text-indigo-700"
                                    x-text="formattedWidth + ' × ' + formattedHeight"
                                ></div>

                                <div
                                    class="mt-1 text-[10px] text-indigo-500"
                                    x-text="orientation"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Dimensions
                        </div>

                        <div class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                            <span x-text="formattedWidth"></span>
                            <span class="mx-1 text-slate-300">×</span>
                            <span x-text="formattedHeight"></span>
                            <span
                                class="ml-1 text-sm font-medium text-slate-500"
                                x-text="unit"
                            ></span>
                        </div>

                        <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-slate-500">Ratio</div>
                                <div
                                    class="mt-1 font-semibold text-slate-800"
                                    x-text="simplifiedRatio"
                                ></div>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-slate-500">Decimal</div>
                                <div
                                    class="mt-1 font-semibold text-slate-800"
                                    x-text="decimalRatio"
                                ></div>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-slate-500">Orientation</div>
                                <div
                                    class="mt-1 font-semibold text-slate-800"
                                    x-text="orientation"
                                ></div>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-slate-500">Megapixels</div>
                                <div
                                    class="mt-1 font-semibold text-slate-800"
                                    x-text="megapixels"
                                ></div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                            <button
                                type="button"
                                @click="copyValue(simplifiedRatio, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy Ratio
                            </button>

                            <button
                                type="button"
                                @click="copyValue(formattedWidth + ' × ' + formattedHeight, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy Dimensions
                            </button>

                            <button
                                type="button"
                                @click="copyValue(cssAspectRatio, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy CSS
                            </button>

                            <button
                                type="button"
                                @click="copyValue(compactReport, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy Report
                            </button>
                        </div>
                    </div>

                    {{-- Derived values --}}
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                CSS aspect-ratio
                            </div>
                            <code
                                class="mt-1 block break-all text-xs font-semibold text-slate-800"
                                x-text="cssAspectRatio"
                            ></code>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                Padding-bottom
                            </div>
                            <div
                                class="mt-1 text-xs font-semibold text-slate-800"
                                x-text="paddingPercentage + '%'"
                            ></div>
                        </div>
                    </div>

                    {{-- Scale --}}
                    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-semibold text-slate-800">
                                    Retina / export scaling
                                </h3>
                                <p class="mt-1 text-[11px] text-slate-500">
                                    Generate dimensions from the current result.
                                </p>
                            </div>

                            <select
                                x-model.number="scaleFactor"
                                @change="calculateRatio()"
                                class="rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs font-medium outline-none"
                            >
                                <option value="0.5">0.5×</option>
                                <option value="1">1×</option>
                                <option value="2">2×</option>
                                <option value="3">3×</option>
                                <option value="4">4×</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-[10px] text-slate-500">Scaled width</div>
                                <div
                                    class="mt-1 text-sm font-semibold text-slate-800"
                                    x-text="scaledWidth"
                                ></div>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-[10px] text-slate-500">Scaled height</div>
                                <div
                                    class="mt-1 text-sm font-semibold text-slate-800"
                                    x-text="scaledHeight"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- ========================================================
            RESIZE MODE
        ========================================================= --}}
        <template x-if="mainMode === 'resize'">
            <div class="grid lg:grid-cols-[1fr_1fr]">
                <div class="border-b border-slate-200 p-5 lg:border-b-0 lg:border-r sm:p-6">

                    <div class="mb-5">
                        <h2 class="text-base font-semibold text-slate-900">
                            Resize dimensions
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Enter a source size and resize it without distorting the ratio.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Source width
                            </label>

                            <input
                                type="number"
                                min="1"
                                step="any"
                                x-model.number="sourceWidth"
                                @input="calculateResize()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Source height
                            </label>

                            <input
                                type="number"
                                min="1"
                                step="any"
                                x-model.number="sourceHeight"
                                @input="calculateResize()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </div>

                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <input
                            type="checkbox"
                            x-model="lockResizeRatio"
                            @change="calculateResize()"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <span>
                            <span class="block text-xs font-semibold text-slate-800">
                                Lock aspect ratio
                            </span>
                            <span class="block text-[11px] text-slate-500">
                                Keep the source proportions while changing dimensions.
                            </span>
                        </span>
                    </label>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Resize target
                        </label>

                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                data-active-group="resize-target"
                                data-active-value="width"
                                :class="{ 'is-active': resizeTarget === 'width' }"
                                @click="resizeTarget = 'width'; calculateResize()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Target width
                            </button>

                            <button
                                type="button"
                                data-active-group="resize-target"
                                data-active-value="height"
                                :class="{ 'is-active': resizeTarget === 'height' }"
                                @click="resizeTarget = 'height'; calculateResize()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Target height
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Target <span x-text="resizeTarget"></span>
                        </label>

                        <input
                            type="number"
                            min="1"
                            step="any"
                            x-model.number="resizeTargetValue"
                            @input="calculateResize()"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-lg font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                        >
                    </div>

                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Scale percentage
                            </span>
                            <span
                                class="text-xs font-semibold text-indigo-600"
                                x-text="resizePercentage + '%'"
                            ></span>
                        </div>

                        <input
                            type="range"
                            min="10"
                            max="400"
                            step="1"
                            x-model.number="resizePercentage"
                            @input="applyResizePercentage()"
                            class="w-full accent-indigo-600"
                        >

                        <div class="mt-2 flex flex-wrap gap-2">
                            <template x-for="value in [25,50,75,100,125,150,200]" :key="value">
                                <button
                                    type="button"
                                    @click="resizePercentage = value; applyResizePercentage()"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                                    x-text="value + '%'"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Standard resolution
                        </label>

                        <select
                            @change="applyResolutionPreset($event.target.value)"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-indigo-500"
                        >
                            <option value="">Select a resolution...</option>
                            <template x-for="preset in resolutionPresets" :key="preset.id">
                                <option
                                    :value="preset.id"
                                    x-text="preset.name + ' — ' + preset.width + ' × ' + preset.height"
                                ></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50/60 p-5 sm:p-6">
                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-slate-900">
                            Resize result
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Dimensions calculated from the locked source ratio.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                            Output dimensions
                        </div>

                        <div class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                            <span x-text="resizeResultWidth"></span>
                            <span class="mx-1 text-slate-300">×</span>
                            <span x-text="resizeResultHeight"></span>
                        </div>

                        <div class="mt-2 text-xs text-slate-500">
                            Ratio:
                            <strong
                                class="text-slate-700"
                                x-text="resizeRatio"
                            ></strong>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                @click="copyValue(resizeResultWidth + ' × ' + resizeResultHeight, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy Dimensions
                            </button>

                            <button
                                type="button"
                                @click="copyValue(resizeRatio, $event.currentTarget)"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            >
                                Copy Ratio
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <template x-for="item in resizeScaleTable" :key="item.scale">
                            <button
                                type="button"
                                @click="resizePercentage = item.percent; calculateResize()"
                                class="rounded-xl border border-slate-200 bg-white p-3 text-left hover:border-indigo-300"
                            >
                                <div
                                    class="text-[10px] text-slate-500"
                                    x-text="item.label"
                                ></div>
                                <div
                                    class="mt-1 text-xs font-semibold text-slate-800"
                                    x-text="item.width + ' × ' + item.height"
                                ></div>
                            </button>
                        </template>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                        <div class="text-xs font-semibold text-slate-800">
                            Responsive container
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-[11px] text-slate-500">
                                    Container width
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    x-model.number="containerWidth"
                                    @input="calculateResize()"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs outline-none focus:border-indigo-500"
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-[11px] text-slate-500">
                                    Required height
                                </label>
                                <div
                                    class="rounded-lg bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800"
                                    x-text="responsiveHeight"
                                ></div>
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg bg-slate-50 p-3">
                            <div class="text-[10px] text-slate-500">
                                CSS padding-bottom
                            </div>
                            <code
                                class="mt-1 block text-xs font-semibold text-slate-800"
                                x-text="responsivePadding + '%'"
                            ></code>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- ========================================================
            CROP MODE
        ========================================================= --}}
        <template x-if="mainMode === 'crop'">
            <div class="grid lg:grid-cols-[1fr_1fr]">
                <div class="border-b border-slate-200 p-5 lg:border-b-0 lg:border-r sm:p-6">

                    <div class="mb-5">
                        <h2 class="text-base font-semibold text-slate-900">
                            Target-ratio crop planner
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Calculate the exact crop rectangle and centered offsets.
                        </p>
                    </div>

                    {{-- Image upload --}}
                    <div
                        class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 text-center transition"
                        :class="{ 'aabi-drop-active': cropDragActive }"
                        @dragover.prevent="cropDragActive = true"
                        @dragleave.prevent="cropDragActive = false"
                        @drop.prevent="handleImageDrop($event)"
                    >
                        <input
                            x-ref="imageInput"
                            type="file"
                            accept="image/*"
                            class="hidden"
                            @change="handleImageFile($event.target.files[0])"
                        >

                        <div class="text-2xl">🖼️</div>

                        <div class="mt-2 text-xs font-semibold text-slate-800">
                            Image dimensions
                        </div>

                        <div class="mt-1 text-[11px] text-slate-500">
                            Upload an image to detect its dimensions locally.
                        </div>

                        <button
                            type="button"
                            @click="$refs.imageInput.click()"
                            class="mt-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                        >
                            Choose image
                        </button>

                        <div
                            x-show="imageInfo.loaded"
                            class="mt-3 text-xs font-medium text-indigo-600"
                            x-text="imageInfo.width + ' × ' + imageInfo.height"
                        ></div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Original width
                            </label>
                            <input
                                type="number"
                                min="1"
                                x-model.number="cropSourceWidth"
                                @input="calculateCrop()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Original height
                            </label>
                            <input
                                type="number"
                                min="1"
                                x-model.number="cropSourceHeight"
                                @input="calculateCrop()"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Target ratio
                        </label>

                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="cropRatioWidth"
                                @input="calculateCrop()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >

                            <span class="font-bold text-slate-500">:</span>

                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="cropRatioHeight"
                                @input="calculateCrop()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Crop strategy
                        </label>

                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                data-active-group="crop-strategy"
                                data-active-value="center"
                                :class="{ 'is-active': cropStrategy === 'center' }"
                                @click="cropStrategy = 'center'; calculateCrop()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Center crop
                            </button>

                            <button
                                type="button"
                                data-active-group="crop-strategy"
                                data-active-value="fit"
                                :class="{ 'is-active': cropStrategy === 'fit' }"
                                @click="cropStrategy = 'fit'; calculateCrop()"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700"
                            >
                                Fit / letterbox
                            </button>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Common target ratios
                        </label>

                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="preset in cropRatios" :key="preset.label">
                                <button
                                    type="button"
                                    @click="cropRatioWidth = preset.width; cropRatioHeight = preset.height; calculateCrop()"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-2.5 text-xs font-medium text-slate-700 hover:border-indigo-300"
                                >
                                    <span x-text="preset.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50/60 p-5 sm:p-6">

                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-slate-900">
                            Crop result
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Exact crop rectangle and centered offset.
                        </p>
                    </div>

                    {{-- Crop preview --}}
                    <div class="aabi-preview-grid flex min-h-[260px] items-center justify-center rounded-2xl border border-slate-200 bg-white p-5">
                        <div
                            class="relative flex items-center justify-center overflow-hidden rounded-lg border border-slate-300 bg-slate-100"
                            :style="cropPreviewOuterStyle"
                        >
                            <div
                                class="absolute rounded border-2 border-indigo-500 bg-indigo-50/30"
                                :style="cropPreviewInnerStyle"
                            ></div>

                            <span class="relative z-10 rounded bg-white/90 px-2 py-1 text-[10px] font-semibold text-slate-700 shadow-sm">
                                Target
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                Crop width
                            </div>
                            <div
                                class="mt-1 text-lg font-bold text-slate-900"
                                x-text="cropWidth"
                            ></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                Crop height
                            </div>
                            <div
                                class="mt-1 text-lg font-bold text-slate-900"
                                x-text="cropHeight"
                            ></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                Offset X
                            </div>
                            <div
                                class="mt-1 text-lg font-bold text-slate-900"
                                x-text="cropOffsetX"
                            ></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                                Offset Y
                            </div>
                            <div
                                class="mt-1 text-lg font-bold text-slate-900"
                                x-text="cropOffsetY"
                            ></div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-800">
                                Fit vs crop
                            </span>

                            <span
                                class="text-xs font-semibold text-indigo-600"
                                x-text="cropStrategy === 'center' ? 'Crop' : 'Fit'"
                            ></span>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-[10px] text-slate-500">Crop output</div>
                                <div
                                    class="mt-1 text-xs font-semibold text-slate-800"
                                    x-text="cropWidth + ' × ' + cropHeight"
                                ></div>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <div class="text-[10px] text-slate-500">Letterbox size</div>
                                <div
                                    class="mt-1 text-xs font-semibold text-slate-800"
                                    x-text="fitWidth + ' × ' + fitHeight"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="copyValue(cropReport, $event.currentTarget)"
                        class="mt-4 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Copy crop calculation
                    </button>
                </div>
            </div>
        </template>

        {{-- ========================================================
            COMPARE MODE
        ========================================================= --}}
        <template x-if="mainMode === 'compare'">
            <div class="p-5 sm:p-6">

                <div class="mb-5">
                    <h2 class="text-base font-semibold text-slate-900">
                        Compare aspect ratios
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Compare two ratios, their decimal values, orientation and proportional difference.
                    </p>
                </div>

                <div class="grid gap-5 lg:grid-cols-[1fr_auto_1fr]">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Ratio A
                        </div>

                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="compareAWidth"
                                @input="calculateComparison()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500"
                            >

                            <span class="font-bold text-slate-500">:</span>

                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="compareAHeight"
                                @input="calculateComparison()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500"
                            >
                        </div>

                        <div class="mt-4 text-center">
                            <div
                                class="text-2xl font-bold text-slate-900"
                                x-text="compareARatio"
                            ></div>
                            <div
                                class="mt-1 text-xs text-slate-500"
                                x-text="compareADecimal"
                            ></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center text-xl font-bold text-slate-300">
                        VS
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Ratio B
                        </div>

                        <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="compareBWidth"
                                @input="calculateComparison()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500"
                            >

                            <span class="font-bold text-slate-500">:</span>

                            <input
                                type="number"
                                min="0.001"
                                step="any"
                                x-model.number="compareBHeight"
                                @input="calculateComparison()"
                                class="rounded-xl border border-slate-300 px-3 py-3 text-center font-semibold outline-none focus:border-indigo-500"
                            >
                        </div>

                        <div class="mt-4 text-center">
                            <div
                                class="text-2xl font-bold text-slate-900"
                                x-text="compareBRatio"
                            ></div>
                            <div
                                class="mt-1 text-xs text-slate-500"
                                x-text="compareBDecimal"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">
                            Ratio A orientation
                        </div>
                        <div
                            class="mt-1 text-sm font-semibold text-slate-800"
                            x-text="compareAOrientation"
                        ></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">
                            Decimal difference
                        </div>
                        <div
                            class="mt-1 text-sm font-semibold text-slate-800"
                            x-text="compareDifference"
                        ></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                        <div class="text-[10px] uppercase tracking-wide text-slate-500">
                            Closest standard
                        </div>
                        <div
                            class="mt-1 text-sm font-semibold text-slate-800"
                            x-text="closestStandardRatio"
                        ></div>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <template x-for="preset in ratioPresets" :key="'compare-' + preset.id">
                        <button
                            type="button"
                            @click="compareAWidth = preset.width; compareAHeight = preset.height; calculateComparison()"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                            x-text="preset.label"
                        ></button>
                    </template>
                </div>

                <button
                    type="button"
                    @click="copyValue(compareReport, $event.currentTarget)"
                    class="mt-5 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                >
                    Copy comparison
                </button>
            </div>
        </template>
    </section>

    {{-- ============================================================
        MULTI-SIZE GENERATOR
    ============================================================= --}}
    <section class="mt-4 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Multi-size generator
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Generate multiple dimensions from one source ratio.
                    </p>
                </div>

                <button
                    type="button"
                    @click="multiSizeOpen = !multiSizeOpen"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:border-indigo-300"
                    x-text="multiSizeOpen ? 'Hide' : 'Show'"
                ></button>
            </div>
        </div>

        <div
            x-show="multiSizeOpen"
            x-transition
            class="p-5"
        >
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-2 block text-xs font-medium text-slate-600">
                        Base width
                    </label>
                    <input
                        type="number"
                        min="1"
                        x-model.number="multiBaseWidth"
                        @input="generateMultiSizes()"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-indigo-500"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-xs font-medium text-slate-600">
                        Ratio width
                    </label>
                    <input
                        type="number"
                        min="0.001"
                        step="any"
                        x-model.number="multiRatioWidth"
                        @input="generateMultiSizes()"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-indigo-500"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-xs font-medium text-slate-600">
                        Ratio height
                    </label>
                    <input
                        type="number"
                        min="0.001"
                        step="any"
                        x-model.number="multiRatioHeight"
                        @input="generateMultiSizes()"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-indigo-500"
                    >
                </div>
            </div>

            <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                <div class="grid grid-cols-3 bg-slate-50 px-4 py-2 text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                    <div>Scale</div>
                    <div>Width</div>
                    <div>Height</div>
                </div>

                <template x-for="item in multiSizes" :key="item.scale">
                    <div class="grid grid-cols-3 border-t border-slate-100 px-4 py-2.5 text-xs">
                        <div
                            class="font-semibold text-slate-700"
                            x-text="item.scale + '×'"
                        ></div>
                        <div
                            class="text-slate-600"
                            x-text="item.width"
                        ></div>
                        <div
                            class="text-slate-600"
                            x-text="item.height"
                        ></div>
                    </div>
                </template>
            </div>

            <button
                type="button"
                @click="copyValue(multiSizeReport, $event.currentTarget)"
                class="mt-4 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
            >
                Copy all sizes
            </button>
        </div>
    </section>

    {{-- ============================================================
        TOAST
    ============================================================= --}}
    <div
        x-show="toast"
        x-transition
        role="status"
        aria-live="polite"
        class="fixed bottom-5 right-5 z-50 rounded-xl border border-slate-200 bg-slate-900 px-4 py-3 text-xs font-medium text-white shadow-lg"
        x-text="toast"
    ></div>
</div>

@script
<script>
    window.aabiAspectRatioCalculator = function () {
        return {
            mainMode: 'ratio',

            ratioMode: 'height',

            width: 1920,
            height: 1080,

            resultWidth: 1920,
            resultHeight: 1080,

            ratioWidth: 16,
            ratioHeight: 9,

            unit: 'px',
            units: ['px', '%', 'in', 'cm', 'mm'],

            error: '',
            toast: '',

            scaleFactor: 1,

            showAllRatios: false,

            ratioPresets: [
                { id: '1-1', label: '1:1', width: 1, height: 1 },
                { id: '4-3', label: '4:3', width: 4, height: 3 },
                { id: '3-2', label: '3:2', width: 3, height: 2 },
                { id: '16-9', label: '16:9', width: 16, height: 9 },
                { id: '16-10', label: '16:10', width: 16, height: 10 },
                { id: '21-9', label: '21:9', width: 21, height: 9 },
                { id: '9-16', label: '9:16', width: 9, height: 16 },
                { id: '4-5', label: '4:5', width: 4, height: 5 },
                { id: '2-3', label: '2:3', width: 2, height: 3 },
                { id: '3-4', label: '3:4', width: 3, height: 4 },
                { id: '5-4', label: '5:4', width: 5, height: 4 },
                { id: '2.35-1', label: '2.35:1', width: 2.35, height: 1 },
            ],

            showAllRatioPresets: false,

            presetSearch: '',
            presetCategory: 'All',

            presetCategories: [
                'All',
                'YouTube',
                'Instagram',
                'TikTok',
                'Facebook',
                'LinkedIn',
                'X',
                'Pinterest',
                'Video',
                'Photography',
                'Display',
                'Print'
            ],

            presetLibrary: [
                { id: 'youtube-video', name: 'YouTube Video', width: 1920, height: 1080, ratio: '16:9', category: 'YouTube' },
                { id: 'youtube-short', name: 'YouTube Short', width: 1080, height: 1920, ratio: '9:16', category: 'YouTube' },
                { id: 'instagram-square', name: 'Instagram Square', width: 1080, height: 1080, ratio: '1:1', category: 'Instagram' },
                { id: 'instagram-portrait', name: 'Instagram Portrait', width: 1080, height: 1350, ratio: '4:5', category: 'Instagram' },
                { id: 'instagram-story', name: 'Instagram Story', width: 1080, height: 1920, ratio: '9:16', category: 'Instagram' },
                { id: 'tiktok-video', name: 'TikTok Video', width: 1080, height: 1920, ratio: '9:16', category: 'TikTok' },
                { id: 'facebook-post', name: 'Facebook Post', width: 1200, height: 630, ratio: '40:21', category: 'Facebook' },
                { id: 'facebook-story', name: 'Facebook Story', width: 1080, height: 1920, ratio: '9:16', category: 'Facebook' },
                { id: 'linkedin-post', name: 'LinkedIn Post', width: 1200, height: 627, ratio: '1.91:1', category: 'LinkedIn' },
                { id: 'x-post', name: 'X Post', width: 1600, height: 900, ratio: '16:9', category: 'X' },
                { id: 'pinterest-pin', name: 'Pinterest Pin', width: 1000, height: 1500, ratio: '2:3', category: 'Pinterest' },
                { id: 'hd', name: 'HD', width: 1280, height: 720, ratio: '16:9', category: 'Video' },
                { id: 'full-hd', name: 'Full HD', width: 1920, height: 1080, ratio: '16:9', category: 'Video' },
                { id: 'qhd', name: 'QHD', width: 2560, height: 1440, ratio: '16:9', category: 'Display' },
                { id: '4k', name: '4K UHD', width: 3840, height: 2160, ratio: '16:9', category: 'Video' },
                { id: '8k', name: '8K UHD', width: 7680, height: 4320, ratio: '16:9', category: 'Video' },
                { id: 'photo-3-2', name: 'Photography 3:2', width: 6000, height: 4000, ratio: '3:2', category: 'Photography' },
                { id: 'photo-4-3', name: 'Photography 4:3', width: 4000, height: 3000, ratio: '4:3', category: 'Photography' },
                { id: 'monitor-16-10', name: 'Monitor 16:10', width: 1920, height: 1200, ratio: '16:10', category: 'Display' },
                { id: 'a4', name: 'A4 Landscape', width: 297, height: 210, ratio: '99:70', category: 'Print' },
                { id: 'a4-portrait', name: 'A4 Portrait', width: 210, height: 297, ratio: '70:99', category: 'Print' },
            ],

            customPresets: [],

            customPresetName: '',
            customPresetWidth: '',
            customPresetHeight: '',

            /* Resize */
            sourceWidth: 1920,
            sourceHeight: 1080,
            lockResizeRatio: true,
            resizeTarget: 'width',
            resizeTargetValue: 1280,
            resizePercentage: 100,

            resizeResultWidth: 1280,
            resizeResultHeight: 720,

            containerWidth: 100,
            responsiveHeight: '56.25',
            responsivePadding: '56.25',

            /* Crop */
            cropDragActive: false,
            imageInfo: {
                loaded: false,
                width: 0,
                height: 0,
                name: ''
            },

            cropSourceWidth: 1920,
            cropSourceHeight: 1080,

            cropRatioWidth: 1,
            cropRatioHeight: 1,

            cropStrategy: 'center',

            cropWidth: 1080,
            cropHeight: 1080,
            cropOffsetX: 420,
            cropOffsetY: 0,

            fitWidth: 1080,
            fitHeight: 1080,

            cropRatios: [
                { label: '1:1', width: 1, height: 1 },
                { label: '4:5', width: 4, height: 5 },
                { label: '3:4', width: 3, height: 4 },
                { label: '16:9', width: 16, height: 9 },
                { label: '9:16', width: 9, height: 16 },
                { label: '2:3', width: 2, height: 3 },
            ],

            /* Compare */
            compareAWidth: 16,
            compareAHeight: 9,

            compareBWidth: 4,
            compareBHeight: 3,

            compareARatio: '16:9',
            compareBRatio: '4:3',
            compareADecimal: '1.7778',
            compareBDecimal: '1.3333',
            compareAOrientation: 'Landscape',
            compareDifference: '0.4444',
            closestStandardRatio: '16:9',

            /* Multi-size */
            multiSizeOpen: false,
            multiBaseWidth: 1920,
            multiRatioWidth: 16,
            multiRatioHeight: 9,

            multiSizes: [],

            /* UI */
            toastTimer: null,

            init() {
                this.loadState();

                this.calculateRatio();
                this.calculateResize();
                this.calculateCrop();
                this.calculateComparison();
                this.generateMultiSizes();

                this.$watch('mainMode', () => this.persistState());

                this.$watch('ratioWidth', () => {
                    if (this.mainMode === 'ratio') {
                        this.calculateRatio();
                    }
                });

                this.$watch('ratioHeight', () => {
                    if (this.mainMode === 'ratio') {
                        this.calculateRatio();
                    }
                });

                this.$watch('width', () => {
                    if (this.mainMode === 'ratio') {
                        this.calculateRatio();
                    }
                });

                this.$watch('height', () => {
                    if (this.mainMode === 'ratio') {
                        this.calculateRatio();
                    }
                });
            },

            /* ========================================================
               GETTERS
            ======================================================== */

            get visibleRatios() {
                return this.showAllRatios
                    ? this.ratioPresets
                    : this.ratioPresets.slice(0, 8);
            },

            get filteredPresets() {
                const query = String(this.presetSearch || '').trim().toLowerCase();

                return this.presetLibrary
                    .concat(this.customPresets)
                    .filter((preset) => {
                        const categoryMatch =
                            this.presetCategory === 'All' ||
                            preset.category === this.presetCategory;

                        const searchMatch =
                            !query ||
                            preset.name.toLowerCase().includes(query) ||
                            preset.ratio.toLowerCase().includes(query);

                        return categoryMatch && searchMatch;
                    });
            },

            get formattedWidth() {
                return this.formatNumber(this.resultWidth);
            },

            get formattedHeight() {
                return this.formatNumber(this.resultHeight);
            },

            get simplifiedRatio() {
                return this.simplifyRatio(
                    this.ratioWidth,
                    this.ratioHeight
                );
            },

            get decimalRatio() {
                const w = Number(this.ratioWidth);
                const h = Number(this.ratioHeight);

                if (!w || !h || w <= 0 || h <= 0) {
                    return '—';
                }

                return (w / h).toFixed(4);
            },

            get orientation() {
                const w = Number(this.resultWidth);
                const h = Number(this.resultHeight);

                if (!w || !h) {
                    return '—';
                }

                if (Math.abs(w - h) < 0.000001) {
                    return 'Square';
                }

                return w > h ? 'Landscape' : 'Portrait';
            },

            get megapixels() {
                const value =
                    Number(this.resultWidth) *
                    Number(this.resultHeight) /
                    1000000;

                return Number.isFinite(value)
                    ? value.toFixed(2) + ' MP'
                    : '—';
            },

            get cssAspectRatio() {
                return `aspect-ratio: ${this.cleanNumber(this.ratioWidth)} / ${this.cleanNumber(this.ratioHeight)};`;
            },

            get paddingPercentage() {
                const w = Number(this.ratioWidth);
                const h = Number(this.ratioHeight);

                if (!w || !h) {
                    return '—';
                }

                return ((h / w) * 100).toFixed(4).replace(/0+$/, '').replace(/\.$/, '');
            },

            get scaledWidth() {
                return this.formatNumber(
                    Number(this.resultWidth) * Number(this.scaleFactor || 1)
                );
            },

            get scaledHeight() {
                return this.formatNumber(
                    Number(this.resultHeight) * Number(this.scaleFactor || 1)
                );
            },

            get resizeRatio() {
                return this.simplifyRatio(
                    this.sourceWidth,
                    this.sourceHeight
                );
            },

            get resizeScaleTable() {
                return [50, 75, 100, 125, 150, 200].map((percent) => {
                    return {
                        scale: percent / 100,
                        percent,
                        label: `${percent}%`,
                        width: Math.round(this.sourceWidth * percent / 100),
                        height: Math.round(this.sourceHeight * percent / 100)
                    };
                });
            },

            get cropReport() {
                return [
                    `Source: ${this.cropSourceWidth} × ${this.cropSourceHeight}`,
                    `Target ratio: ${this.simplifyRatio(this.cropRatioWidth, this.cropRatioHeight)}`,
                    `Crop: ${this.cropWidth} × ${this.cropHeight}`,
                    `Offset X: ${this.cropOffsetX}`,
                    `Offset Y: ${this.cropOffsetY}`,
                    `Fit: ${this.fitWidth} × ${this.fitHeight}`,
                ].join('\n');
            },

            get cropPreviewOuterStyle() {
                const w = Number(this.cropSourceWidth) || 1;
                const h = Number(this.cropSourceHeight) || 1;

                const maxW = 330;
                const maxH = 210;

                let pw = maxW;
                let ph = pw * h / w;

                if (ph > maxH) {
                    ph = maxH;
                    pw = ph * w / h;
                }

                return `width:${Math.max(100, pw)}px;height:${Math.max(70, ph)}px;`;
            },

            get cropPreviewInnerStyle() {
                const sourceW = Number(this.cropSourceWidth) || 1;
                const sourceH = Number(this.cropSourceHeight) || 1;

                const cropW = Number(this.cropWidth) || sourceW;
                const cropH = Number(this.cropHeight) || sourceH;

                const outerW = 280;
                const outerH = outerW * sourceH / sourceW;

                let renderedW = outerW;
                let renderedH = outerH;

                if (renderedH > 180) {
                    renderedH = 180;
                    renderedW = renderedH * sourceW / sourceH;
                }

                const left =
                    (Number(this.cropOffsetX) / sourceW) *
                    renderedW;

                const top =
                    (Number(this.cropOffsetY) / sourceH) *
                    renderedH;

                const width =
                    (cropW / sourceW) *
                    renderedW;

                const height =
                    (cropH / sourceH) *
                    renderedH;

                return [
                    `width:${width}px`,
                    `height:${height}px`,
                    `left:${left}px`,
                    `top:${top}px`,
                ].join(';') + ';';
            },

            get responsivePadding() {
                const w = Number(this.ratioWidth);
                const h = Number(this.ratioHeight);

                if (!w || !h) {
                    return '0';
                }

                return ((h / w) * 100).toFixed(4);
            },

            get compareReport() {
                return [
                    `Ratio A: ${this.compareARatio}`,
                    `Ratio A decimal: ${this.compareADecimal}`,
                    `Ratio B: ${this.compareBRatio}`,
                    `Ratio B decimal: ${this.compareBDecimal}`,
                    `Decimal difference: ${this.compareDifference}`,
                    `Closest standard ratio: ${this.closestStandardRatio}`,
                ].join('\n');
            },

            get compactReport() {
                return [
                    `Dimensions: ${this.formattedWidth} × ${this.formattedHeight} ${this.unit}`,
                    `Aspect ratio: ${this.simplifiedRatio}`,
                    `Decimal ratio: ${this.decimalRatio}`,
                    `Orientation: ${this.orientation}`,
                    `Megapixels: ${this.megapixels}`,
                    `CSS: ${this.cssAspectRatio}`,
                ].join('\n');
            },

            get multiSizeReport() {
                return this.multiSizes
                    .map(item => `${item.scale}× — ${item.width} × ${item.height}`)
                    .join('\n');
            },

            /* ========================================================
               MAIN MODES
            ======================================================== */

            setMainMode(mode) {
                this.mainMode = mode;

                if (mode === 'resize') {
                    this.calculateResize();
                }

                if (mode === 'crop') {
                    this.calculateCrop();
                }

                if (mode === 'compare') {
                    this.calculateComparison();
                }

                this.persistState();
            },

            /* ========================================================
               RATIO
            ======================================================== */

            calculateRatio() {
                this.error = '';

                const rw = Number(this.ratioWidth);
                const rh = Number(this.ratioHeight);

                if (
                    !Number.isFinite(rw) ||
                    !Number.isFinite(rh) ||
                    rw <= 0 ||
                    rh <= 0
                ) {
                    this.error = 'Enter a valid positive aspect ratio.';
                    return;
                }

                const w = Number(this.width);
                const h = Number(this.height);

                if (this.ratioMode === 'height') {
                    if (!Number.isFinite(w) || w <= 0) {
                        this.error = 'Enter a valid width.';
                        return;
                    }

                    this.resultWidth = w;
                    this.resultHeight = w * rh / rw;
                }

                if (this.ratioMode === 'width') {
                    if (!Number.isFinite(h) || h <= 0) {
                        this.error = 'Enter a valid height.';
                        return;
                    }

                    this.resultHeight = h;
                    this.resultWidth = h * rw / rh;
                }

                if (this.ratioMode === 'ratio') {
                    if (
                        !Number.isFinite(w) ||
                        !Number.isFinite(h) ||
                        w <= 0 ||
                        h <= 0
                    ) {
                        this.error = 'Enter valid width and height values.';
                        return;
                    }

                    const parts = this.simplifyRatio(w, h).split(':');

                    this.ratioWidth = Number(parts[0]);
                    this.ratioHeight = Number(parts[1]);

                    this.resultWidth = w;
                    this.resultHeight = h;
                }

                this.persistState();
            },

            applyRatio(width, height) {
                this.ratioWidth = width;
                this.ratioHeight = height;
                this.ratioMode = 'height';

                this.calculateRatio();
            },

            applyDimensionPreset(preset) {
                this.width = preset.width;
                this.height = preset.height;

                const ratio = this.simplifyRatio(
                    preset.width,
                    preset.height
                );

                const parts = ratio.split(':');

                this.ratioWidth = Number(parts[0]);
                this.ratioHeight = Number(parts[1]);

                this.ratioMode = 'ratio';

                this.calculateRatio();
            },

            isRatioActive(preset) {
                return (
                    Math.abs(Number(this.ratioWidth) - Number(preset.width)) < 0.000001 &&
                    Math.abs(Number(this.ratioHeight) - Number(preset.height)) < 0.000001
                );
            },

            simplifyCurrentRatio() {
                if (
                    Number(this.width) > 0 &&
                    Number(this.height) > 0
                ) {
                    const ratio = this.simplifyRatio(
                        this.width,
                        this.height
                    );

                    const parts = ratio.split(':');

                    this.ratioWidth = Number(parts[0]);
                    this.ratioHeight = Number(parts[1]);

                    this.ratioMode = 'ratio';

                    this.calculateRatio();
                }
            },

            simplifyRatio(a, b) {
                a = Number(a);
                b = Number(b);

                if (
                    !Number.isFinite(a) ||
                    !Number.isFinite(b) ||
                    a <= 0 ||
                    b <= 0
                ) {
                    return '—';
                }

                const decimals = Math.max(
                    this.getDecimalPlaces(a),
                    this.getDecimalPlaces(b)
                );

                const factor = Math.pow(
                    10,
                    Math.min(decimals, 6)
                );

                let x = Math.round(a * factor);
                let y = Math.round(b * factor);

                const divisor = this.gcd(x, y);

                x /= divisor;
                y /= divisor;

                return `${this.cleanNumber(x)}:${this.cleanNumber(y)}`;
            },

            gcd(a, b) {
                a = Math.abs(Math.round(a));
                b = Math.abs(Math.round(b));

                while (b !== 0) {
                    [a, b] = [b, a % b];
                }

                return a || 1;
            },

            getDecimalPlaces(value) {
                const text = String(value);

                if (!text.includes('.')) {
                    return 0;
                }

                return text.split('.')[1].length;
            },

            /* ========================================================
               RESIZE
            ======================================================== */

            calculateResize() {
                const sw = Number(this.sourceWidth);
                const sh = Number(this.sourceHeight);

                if (
                    !Number.isFinite(sw) ||
                    !Number.isFinite(sh) ||
                    sw <= 0 ||
                    sh <= 0
                ) {
                    return;
                }

                if (this.resizeTarget === 'width') {
                    const target = Number(this.resizeTargetValue);

                    if (target > 0) {
                        this.resizeResultWidth = target;
                        this.resizeResultHeight =
                            target * sh / sw;
                    }
                } else {
                    const target = Number(this.resizeTargetValue);

                    if (target > 0) {
                        this.resizeResultHeight = target;
                        this.resizeResultWidth =
                            target * sw / sh;
                    }
                }

                this.responsiveHeight =
                    this.containerWidth * sh / sw;

                this.responsivePadding =
                    ((sh / sw) * 100).toFixed(4);

                this.generateMultiSizes();
                this.persistState();
            },

            applyResizePercentage() {
                const percent = Number(this.resizePercentage);

                if (!percent || percent <= 0) {
                    return;
                }

                this.resizeResultWidth =
                    this.sourceWidth * percent / 100;

                this.resizeResultHeight =
                    this.sourceHeight * percent / 100;

                this.resizeTarget =
                    'width';

                this.resizeTargetValue =
                    this.resizeResultWidth;

                this.calculateResize();
            },

            applyResolutionPreset(id) {
                const preset = this.resolutionPresets.find(
                    item => item.id === id
                );

                if (!preset) {
                    return;
                }

                this.sourceWidth = preset.width;
                this.sourceHeight = preset.height;
                this.resizeTarget = 'width';
                this.resizeTargetValue = preset.width;

                this.calculateResize();
            },

            get resolutionPresets() {
                return [
                    { id: 'hd', name: 'HD', width: 1280, height: 720 },
                    { id: 'fhd', name: 'Full HD', width: 1920, height: 1080 },
                    { id: 'qhd', name: 'QHD', width: 2560, height: 1440 },
                    { id: '4k', name: '4K UHD', width: 3840, height: 2160 },
                    { id: '5k', name: '5K', width: 5120, height: 2880 },
                    { id: '8k', name: '8K UHD', width: 7680, height: 4320 },
                ];
            },

            /* ========================================================
               CROP
            ======================================================== */

            calculateCrop() {
                const sw = Number(this.cropSourceWidth);
                const sh = Number(this.cropSourceHeight);
                const rw = Number(this.cropRatioWidth);
                const rh = Number(this.cropRatioHeight);

                if (
                    !sw || !sh || !rw || !rh ||
                    sw <= 0 || sh <= 0 || rw <= 0 || rh <= 0
                ) {
                    return;
                }

                const sourceRatio = sw / sh;
                const targetRatio = rw / rh;

                if (sourceRatio > targetRatio) {
                    this.cropHeight = sh;
                    this.cropWidth = sh * targetRatio;

                    this.cropOffsetX =
                        (sw - this.cropWidth) / 2;

                    this.cropOffsetY = 0;
                } else {
                    this.cropWidth = sw;
                    this.cropHeight = sw / targetRatio;

                    this.cropOffsetX = 0;

                    this.cropOffsetY =
                        (sh - this.cropHeight) / 2;
                }

                this.cropWidth = Math.round(this.cropWidth);
                this.cropHeight = Math.round(this.cropHeight);

                this.cropOffsetX = Math.round(this.cropOffsetX);
                this.cropOffsetY = Math.round(this.cropOffsetY);

                if (sourceRatio > targetRatio) {
                    this.fitWidth = sw;
                    this.fitHeight =
                        Math.round(sw / targetRatio);
                } else {
                    this.fitHeight = sh;
                    this.fitWidth =
                        Math.round(sh * targetRatio);
                }

                this.persistState();
            },

            handleImageFile(file) {
                if (!file || !file.type.startsWith('image/')) {
                    this.showToast('Please select a valid image file.');
                    return;
                }

                const url = URL.createObjectURL(file);
                const image = new Image();

                image.onload = () => {
                    this.imageInfo = {
                        loaded: true,
                        width: image.naturalWidth,
                        height: image.naturalHeight,
                        name: file.name
                    };

                    this.cropSourceWidth = image.naturalWidth;
                    this.cropSourceHeight = image.naturalHeight;

                    this.calculateCrop();

                    URL.revokeObjectURL(url);
                };

                image.onerror = () => {
                    URL.revokeObjectURL(url);
                    this.showToast('Unable to read this image.');
                };

                image.src = url;
            },

            handleImageDrop(event) {
                this.cropDragActive = false;

                const file =
                    event.dataTransfer &&
                    event.dataTransfer.files
                        ? event.dataTransfer.files[0]
                        : null;

                this.handleImageFile(file);
            },

            /* ========================================================
               COMPARISON
            ======================================================== */

            calculateComparison() {
                const aw = Number(this.compareAWidth);
                const ah = Number(this.compareAHeight);
                const bw = Number(this.compareBWidth);
                const bh = Number(this.compareBHeight);

                if (
                    aw <= 0 ||
                    ah <= 0 ||
                    bw <= 0 ||
                    bh <= 0
                ) {
                    return;
                }

                this.compareARatio =
                    this.simplifyRatio(aw, ah);

                this.compareBRatio =
                    this.simplifyRatio(bw, bh);

                this.compareADecimal =
                    (aw / ah).toFixed(4);

                this.compareBDecimal =
                    (bw / bh).toFixed(4);

                this.compareAOrientation =
                    aw === ah
                        ? 'Square'
                        : aw > ah
                            ? 'Landscape'
                            : 'Portrait';

                this.compareDifference =
                    Math.abs((aw / ah) - (bw / bh))
                        .toFixed(4);

                this.closestStandardRatio =
                    this.findClosestStandardRatio(aw / ah);

                this.persistState();
            },

            findClosestStandardRatio(decimal) {
                let closest = null;
                let difference = Infinity;

                this.ratioPresets.forEach(preset => {
                    const value =
                        preset.width / preset.height;

                    const current =
                        Math.abs(decimal - value);

                    if (current < difference) {
                        difference = current;
                        closest = preset.label;
                    }
                });

                return closest || '—';
            },

            /* ========================================================
               MULTI-SIZE
            ======================================================== */

            generateMultiSizes() {
                const base = Number(this.multiBaseWidth);
                const rw = Number(this.multiRatioWidth);
                const rh = Number(this.multiRatioHeight);

                if (
                    !base ||
                    !rw ||
                    !rh ||
                    base <= 0 ||
                    rw <= 0 ||
                    rh <= 0
                ) {
                    return;
                }

                this.multiSizes = [0.5, 1, 1.5, 2, 3, 4].map(scale => {
                    const width =
                        Math.round(base * scale);

                    const height =
                        Math.round(width * rh / rw);

                    return {
                        scale,
                        width,
                        height
                    };
                });
            },

            /* ========================================================
               CUSTOM PRESETS
            ======================================================== */

            saveCustomPreset() {
                const name =
                    String(this.customPresetName || '').trim();

                const width =
                    Number(this.customPresetWidth);

                const height =
                    Number(this.customPresetHeight);

                if (!name || width <= 0 || height <= 0) {
                    this.showToast(
                        'Enter a name, width and height.'
                    );
                    return;
                }

                const preset = {
                    id: 'custom-' + Date.now(),
                    name,
                    width,
                    height,
                    ratio: this.simplifyRatio(width, height),
                    category: 'Custom'
                };

                this.customPresets.push(preset);

                this.persistState();

                this.customPresetName = '';
                this.customPresetWidth = '';
                this.customPresetHeight = '';

                this.showToast('Custom preset saved.');
            },

            /* ========================================================
               UNIT
            ======================================================== */

            setUnit(unit) {
                this.unit = unit;
                this.persistState();
            },

            /* ========================================================
               ACTIONS
            ======================================================== */

            swapDimensions() {
                [this.width, this.height] =
                    [this.height, this.width];

                this.ratioMode = 'ratio';
                this.calculateRatio();
            },

            reset() {
                this.mainMode = 'ratio';
                this.ratioMode = 'height';

                this.width = 1920;
                this.height = 1080;

                this.resultWidth = 1920;
                this.resultHeight = 1080;

                this.ratioWidth = 16;
                this.ratioHeight = 9;

                this.error = '';

                this.sourceWidth = 1920;
                this.sourceHeight = 1080;
                this.resizeTarget = 'width';
                this.resizeTargetValue = 1280;
                this.resizePercentage = 100;

                this.cropSourceWidth = 1920;
                this.cropSourceHeight = 1080;
                this.cropRatioWidth = 1;
                this.cropRatioHeight = 1;

                this.compareAWidth = 16;
                this.compareAHeight = 9;
                this.compareBWidth = 4;
                this.compareBHeight = 3;

                this.multiBaseWidth = 1920;
                this.multiRatioWidth = 16;
                this.multiRatioHeight = 9;

                try {
                    localStorage.removeItem(
                        'aabi.aspect-ratio.state'
                    );
                } catch (error) {
                    // Storage may be unavailable.
                }

                this.calculateRatio();
                this.calculateResize();
                this.calculateCrop();
                this.calculateComparison();
                this.generateMultiSizes();

                this.showToast('Calculator reset.');
            },

            /* ========================================================
               COPY
            ======================================================== */

            async copyValue(value, button = null) {
                const text = String(value ?? '');

                if (!text || text === '—') {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(text);

                    if (button) {
                        const original =
                            button.innerText;

                        button.innerText = '✓ Copied';

                        setTimeout(() => {
                            button.innerText = original;
                        }, 1400);
                    }

                    this.showToast('Copied to clipboard.');
                } catch (error) {
                    this.fallbackCopy(text);

                    if (button) {
                        const original =
                            button.innerText;

                        button.innerText = '✓ Copied';

                        setTimeout(() => {
                            button.innerText = original;
                        }, 1400);
                    }
                }
            },

            fallbackCopy(text) {
                const textarea =
                    document.createElement('textarea');

                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';

                document.body.appendChild(textarea);
                textarea.select();

                try {
                    document.execCommand('copy');
                } catch (error) {
                    // Ignore unavailable fallback.
                }

                textarea.remove();

                this.showToast('Copied to clipboard.');
            },

            showToast(message) {
                this.toast = message;

                clearTimeout(this.toastTimer);

                this.toastTimer = setTimeout(() => {
                    this.toast = '';
                }, 1800);
            },

            /* ========================================================
               KEYBOARD SHORTCUTS
            ======================================================== */

            handleShortcut(event) {
                const key =
                    String(event.key || '').toLowerCase();

                const modifier =
                    event.ctrlKey || event.metaKey;

                if (!modifier) {
                    return;
                }

                if (key === 'r' && !event.shiftKey) {
                    event.preventDefault();
                    this.reset();
                }

                if (key === 'c' && event.shiftKey) {
                    event.preventDefault();
                    this.copyValue(this.compactReport);
                }
            },

            /* ========================================================
               PERSISTENCE
            ======================================================== */

            persistState() {
                try {
                    localStorage.setItem(
                        'aabi.aspect-ratio.state',
                        JSON.stringify({
                            mainMode: this.mainMode,
                            ratioMode: this.ratioMode,
                            width: this.width,
                            height: this.height,
                            ratioWidth: this.ratioWidth,
                            ratioHeight: this.ratioHeight,
                            unit: this.unit,
                            scaleFactor: this.scaleFactor,

                            sourceWidth: this.sourceWidth,
                            sourceHeight: this.sourceHeight,
                            lockResizeRatio: this.lockResizeRatio,
                            resizeTarget: this.resizeTarget,
                            resizeTargetValue: this.resizeTargetValue,
                            resizePercentage: this.resizePercentage,

                            cropSourceWidth: this.cropSourceWidth,
                            cropSourceHeight: this.cropSourceHeight,
                            cropRatioWidth: this.cropRatioWidth,
                            cropRatioHeight: this.cropRatioHeight,
                            cropStrategy: this.cropStrategy,

                            compareAWidth: this.compareAWidth,
                            compareAHeight: this.compareAHeight,
                            compareBWidth: this.compareBWidth,
                            compareBHeight: this.compareBHeight,

                            multiBaseWidth: this.multiBaseWidth,
                            multiRatioWidth: this.multiRatioWidth,
                            multiRatioHeight: this.multiRatioHeight,

                            customPresets: this.customPresets
                        })
                    );
                } catch (error) {
                    // Local storage may be unavailable.
                }
            },

            loadState() {
                try {
                    const saved =
                        localStorage.getItem(
                            'aabi.aspect-ratio.state'
                        );

                    if (!saved) {
                        return;
                    }

                    const state =
                        JSON.parse(saved);

                    const allowed = [
                        'mainMode',
                        'ratioMode',
                        'width',
                        'height',
                        'ratioWidth',
                        'ratioHeight',
                        'unit',
                        'scaleFactor',

                        'sourceWidth',
                        'sourceHeight',
                        'lockResizeRatio',
                        'resizeTarget',
                        'resizeTargetValue',
                        'resizePercentage',

                        'cropSourceWidth',
                        'cropSourceHeight',
                        'cropRatioWidth',
                        'cropRatioHeight',
                        'cropStrategy',

                        'compareAWidth',
                        'compareAHeight',
                        'compareBWidth',
                        'compareBHeight',

                        'multiBaseWidth',
                        'multiRatioWidth',
                        'multiRatioHeight',
                    ];

                    allowed.forEach(key => {
                        if (
                            Object.prototype.hasOwnProperty.call(
                                state,
                                key
                            )
                        ) {
                            this[key] = state[key];
                        }
                    });

                    if (Array.isArray(state.customPresets)) {
                        this.customPresets =
                            state.customPresets;
                    }
                } catch (error) {
                    // Ignore invalid local state.
                }
            },

            /* ========================================================
               SHARING
            ======================================================== */

            shareState() {
                try {
                    const payload = {
                        w: this.width,
                        h: this.height,
                        rw: this.ratioWidth,
                        rh: this.ratioHeight,
                        mode: this.mainMode
                    };

                    const encoded =
                        btoa(
                            encodeURIComponent(
                                JSON.stringify(payload)
                            )
                        );

                    const url =
                        window.location.origin +
                        window.location.pathname +
                        '#ar=' +
                        encoded;

                    history.replaceState(
                        null,
                        '',
                        url
                    );

                    this.copyValue(url);

                } catch (error) {
                    this.showToast(
                        'Unable to create share link.'
                    );
                }
            },

            /* ========================================================
               FORMAT
            ======================================================== */

            formatNumber(value) {
                const number = Number(value);

                if (!Number.isFinite(number)) {
                    return '—';
                }

                if (Number.isInteger(number)) {
                    return number.toLocaleString();
                }

                return Number(
                    number.toFixed(4)
                ).toLocaleString(
                    undefined,
                    {
                        maximumFractionDigits: 4
                    }
                );
            },

            cleanNumber(value) {
                const number = Number(value);

                if (!Number.isFinite(number)) {
                    return '—';
                }

                if (Number.isInteger(number)) {
                    return String(number);
                }

                return String(
                    Number(number.toFixed(6))
                );
            },

            get previewStyle() {
                const w =
                    Number(this.resultWidth) || 16;

                const h =
                    Number(this.resultHeight) || 9;

                const maxWidth = 330;
                const maxHeight = 180;

                let previewWidth = maxWidth;
                let previewHeight =
                    previewWidth * h / w;

                if (previewHeight > maxHeight) {
                    previewHeight = maxHeight;
                    previewWidth =
                        previewHeight * w / h;
                }

                return [
                    `width:${Math.max(previewWidth, 70)}px`,
                    `height:${Math.max(previewHeight, 70)}px`
                ].join(';') + ';';
            }
        };
    };
</script>
@endscript