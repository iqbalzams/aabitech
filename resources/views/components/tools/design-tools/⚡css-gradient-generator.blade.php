<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiCssGradientGenerator()"
    x-init="init()"
    x-cloak
    @keydown.window="handleKeyboard($event)"
    class="w-full"
>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- =========================================================
             TOOLBAR
        ========================================================== --}}
        <div class="border-b border-slate-200 bg-slate-50/80 p-4 sm:p-5">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        data-active-group="gradient-mode"
                        data-active-value="linear"
                        :class="{ 'is-active': mode === 'linear' }"
                        @click="setMode('linear')"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Linear
                    </button>

                    <button
                        type="button"
                        data-active-group="gradient-mode"
                        data-active-value="radial"
                        :class="{ 'is-active': mode === 'radial' }"
                        @click="setMode('radial')"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Radial
                    </button>

                    <button
                        type="button"
                        data-active-group="gradient-mode"
                        data-active-value="conic"
                        :class="{ 'is-active': mode === 'conic' }"
                        @click="setMode('conic')"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Conic
                    </button>

                    <button
                        type="button"
                        data-active-group="gradient-mode"
                        data-active-value="css"
                        :class="{ 'is-active': mode === 'css' }"
                        @click="setMode('css')"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        CSS Import
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        @click="undo()"
                        :disabled="historyIndex <= 0"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        Undo
                    </button>

                    <button
                        type="button"
                        @click="redo()"
                        :disabled="historyIndex >= history.length - 1"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        Redo
                    </button>

                    <button
                        type="button"
                        @click="randomize()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Randomize
                    </button>

                    <button
                        type="button"
                        @click="reset()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- =========================================================
             WORKSPACE
        ========================================================== --}}
        <div class="grid lg:grid-cols-[minmax(0,1fr)_420px]">

            {{-- =====================================================
                 EDITOR
            ====================================================== --}}
            <div class="border-b border-slate-200 p-4 sm:p-6 lg:border-b-0 lg:border-r">

                {{-- Layers --}}
                <div class="mb-6">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Gradient Layers
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Compose multiple CSS gradients in one background.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="addLayer()"
                            :disabled="layers.length >= 8"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-700 disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            + Layer
                        </button>
                    </div>

                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <template x-for="(layer, index) in layers" :key="layer.id">
                            <button
                                type="button"
                                @click="selectLayer(index)"
                                :class="selectedLayer === index ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600'"
                                class="flex min-w-[110px] shrink-0 items-center justify-between gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                            >
                                <span x-text="'Layer ' + (index + 1)"></span>

                                <span
                                    x-show="layers.length > 1"
                                    @click.stop="removeLayer(index)"
                                    class="text-slate-400 hover:text-red-600"
                                    aria-label="Remove layer"
                                >
                                    ×
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Gradient type --}}
                <div class="mb-6">
                    <div class="mb-3">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Gradient Type
                        </h2>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <button
                            type="button"
                            @click="updateLayer({ type: 'linear' })"
                            :class="currentLayer.type === 'linear' ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-700'"
                            class="rounded-lg border px-3 py-2.5 text-xs font-semibold"
                        >
                            Linear
                        </button>

                        <button
                            type="button"
                            @click="updateLayer({ type: 'radial' })"
                            :class="currentLayer.type === 'radial' ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-700'"
                            class="rounded-lg border px-3 py-2.5 text-xs font-semibold"
                        >
                            Radial
                        </button>

                        <button
                            type="button"
                            @click="updateLayer({ type: 'conic' })"
                            :class="currentLayer.type === 'conic' ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-700'"
                            class="rounded-lg border px-3 py-2.5 text-xs font-semibold"
                        >
                            Conic
                        </button>
                    </div>

                    <label class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                        <input
                            type="checkbox"
                            x-model="currentLayer.repeating"
                            @change="commit()"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        Repeating gradient
                    </label>
                </div>

                {{-- Color stops --}}
                <div class="mb-7">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Color Stops
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Drag stops or edit colors and positions.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="addStop()"
                            :disabled="currentLayer.stops.length >= 12"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-700 disabled:opacity-40"
                        >
                            + Color
                        </button>
                    </div>

                    {{-- Visual timeline --}}
                    <div
                        class="relative mb-4 h-10 rounded-lg border border-slate-200 shadow-inner"
                        :style="'background:' + currentGradientValue"
                        @pointerdown="timelinePointerDown($event)"
                    >
                        <template x-for="(stop, index) in sortedStops" :key="stop.id">
                            <button
                                type="button"
                                class="absolute top-1/2 h-7 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow-md"
                                :style="'left:' + stop.position + '%;background:' + stop.color"
                                :title="'Stop ' + (index + 1) + ' — ' + stop.position + '%'"
                                @pointerdown.stop="startDrag($event, stop.id)"
                                @keydown.left.prevent="moveStop(stop.id, -1)"
                                @keydown.right.prevent="moveStop(stop.id, 1)"
                                @keydown.home.prevent="setStopPosition(stop.id, 0)"
                                @keydown.end.prevent="setStopPosition(stop.id, 100)"
                            ></button>
                        </template>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(stop, index) in currentLayer.stops" :key="stop.id">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-3">
                                <div class="grid grid-cols-[48px_minmax(0,1fr)_90px_36px] items-end gap-2">

                                    <input
                                        type="color"
                                        x-model="stop.color"
                                        @input="commitDebounced()"
                                        class="h-10 w-12 cursor-pointer rounded-lg border border-slate-300 bg-white p-1"
                                        :aria-label="'Color stop ' + (index + 1)"
                                    >

                                    <div>
                                        <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                            Color
                                        </label>

                                        <input
                                            type="text"
                                            x-model="stop.color"
                                            @change="normalizeStop(stop)"
                                            class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 font-mono text-xs font-semibold uppercase outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                            Position
                                        </label>

                                        <div class="relative">
                                            <input
                                                type="number"
                                                min="0"
                                                max="100"
                                                step="0.1"
                                                x-model.number="stop.position"
                                                @change="normalizeStop(stop); commit()"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-2 py-2 pr-6 text-xs font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                            >

                                            <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400">
                                                %
                                            </span>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        @click="removeStop(index)"
                                        :disabled="currentLayer.stops.length <= 2"
                                        class="h-10 rounded-lg border border-slate-200 bg-white text-lg text-slate-400 hover:border-red-200 hover:bg-red-50 hover:text-red-600 disabled:opacity-30"
                                    >
                                        ×
                                    </button>
                                </div>

                                <div class="mt-2">
                                    <input
                                        type="range"
                                        min="0"
                                        max="100"
                                        step="0.1"
                                        x-model.number="stop.position"
                                        @input="commitDebounced()"
                                        class="h-2 w-full cursor-pointer accent-indigo-600"
                                    >
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                        Opacity
                                    </label>

                                    <input
                                        type="range"
                                        min="0"
                                        max="1"
                                        step="0.01"
                                        x-model.number="stop.alpha"
                                        @input="commitDebounced()"
                                        class="h-1.5 flex-1 cursor-pointer accent-indigo-600"
                                    >

                                    <span
                                        class="w-10 text-right text-[10px] font-semibold text-slate-500"
                                        x-text="Math.round(Number(stop.alpha) * 100) + '%'"
                                    ></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Linear settings --}}
                <div x-show="currentLayer.type === 'linear'" class="mb-6">
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">
                        Direction & Angle
                    </h2>

                    <div class="grid grid-cols-4 gap-2 sm:grid-cols-8">
                        <template x-for="direction in directions" :key="direction.value">
                            <button
                                type="button"
                                @click="setAngle(direction.angle)"
                                :class="Number(currentLayer.angle) === direction.angle ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600'"
                                class="rounded-lg border px-2 py-2 text-[10px] font-semibold"
                                x-text="direction.label"
                            ></button>
                        </template>
                    </div>

                    <div class="mt-3 flex items-center gap-3">
                        <input
                            type="range"
                            min="0"
                            max="360"
                            step="1"
                            x-model.number="currentLayer.angle"
                            @input="commitDebounced()"
                            class="h-2 flex-1 cursor-pointer accent-indigo-600"
                        >

                        <div class="relative w-20">
                            <input
                                type="number"
                                min="0"
                                max="360"
                                x-model.number="currentLayer.angle"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 px-2 py-2 pr-6 text-center text-xs font-semibold"
                            >
                            <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                                °
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Radial settings --}}
                <div x-show="currentLayer.type === 'radial'" class="mb-6">
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">
                        Radial Settings
                    </h2>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Shape
                            </label>

                            <select
                                x-model="currentLayer.shape"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="ellipse">Ellipse</option>
                                <option value="circle">Circle</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Position
                            </label>

                            <select
                                x-model="currentLayer.position"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="right">Right</option>
                                <option value="bottom">Bottom</option>
                                <option value="left">Left</option>
                                <option value="top right">Top Right</option>
                                <option value="top left">Top Left</option>
                                <option value="bottom right">Bottom Right</option>
                                <option value="bottom left">Bottom Left</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Conic settings --}}
                <div x-show="currentLayer.type === 'conic'" class="mb-6">
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">
                        Conic Settings
                    </h2>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                From angle
                            </label>

                            <input
                                type="number"
                                min="0"
                                max="360"
                                x-model.number="currentLayer.angle"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs"
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Position
                            </label>

                            <select
                                x-model="currentLayer.position"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="right">Right</option>
                                <option value="bottom">Bottom</option>
                                <option value="left">Left</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Background settings --}}
                <div class="mb-6">
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">
                        Background Settings
                    </h2>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Background Size
                            </label>

                            <select
                                x-model="currentLayer.backgroundSize"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="cover">cover</option>
                                <option value="contain">contain</option>
                                <option value="100% 100%">100% 100%</option>
                                <option value="auto">auto</option>
                                <option value="50% 50%">50% 50%</option>
                                <option value="200% 200%">200% 200%</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Background Position
                            </label>

                            <select
                                x-model="currentLayer.backgroundPosition"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="center">center</option>
                                <option value="top">top</option>
                                <option value="right">right</option>
                                <option value="bottom">bottom</option>
                                <option value="left">left</option>
                                <option value="top right">top right</option>
                                <option value="top left">top left</option>
                                <option value="bottom right">bottom right</option>
                                <option value="bottom left">bottom left</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Blend Mode
                            </label>

                            <select
                                x-model="currentLayer.blendMode"
                                @change="commit()"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs"
                            >
                                <option value="normal">normal</option>
                                <option value="multiply">multiply</option>
                                <option value="screen">screen</option>
                                <option value="overlay">overlay</option>
                                <option value="darken">darken</option>
                                <option value="lighten">lighten</option>
                                <option value="color-dodge">color-dodge</option>
                                <option value="color-burn">color-burn</option>
                                <option value="soft-light">soft-light</option>
                                <option value="hard-light">hard-light</option>
                                <option value="difference">difference</option>
                                <option value="exclusion">exclusion</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">
                                Layer Opacity
                            </label>

                            <input
                                type="range"
                                min="0"
                                max="1"
                                step="0.01"
                                x-model.number="currentLayer.opacity"
                                @input="commitDebounced()"
                                class="mt-2 h-2 w-full cursor-pointer accent-indigo-600"
                            >
                        </div>
                    </div>
                </div>

                {{-- Presets --}}
                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">
                                Presets
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Ready-made design combinations.
                            </p>
                        </div>

                        <select
                            x-model="presetCategory"
                            class="rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-xs"
                        >
                            <option value="all">All</option>
                            <option value="background">Backgrounds</option>
                            <option value="hero">Hero</option>
                            <option value="button">Buttons</option>
                            <option value="text">Text</option>
                            <option value="design">Design</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                        <template x-for="preset in filteredPresets" :key="preset.name">
                            <button
                                type="button"
                                @click="applyPreset(preset)"
                                class="overflow-hidden rounded-lg border border-slate-200 bg-white text-left hover:border-indigo-300"
                            >
                                <span
                                    class="block h-12"
                                    :style="'background:' + preset.value"
                                ></span>

                                <span
                                    class="block px-2.5 py-2 text-[11px] font-semibold text-slate-700"
                                    x-text="preset.name"
                                ></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- =====================================================
                 PREVIEW / OUTPUT
            ====================================================== --}}
            <div class="bg-slate-50/70 p-4 sm:p-6">

                {{-- Preview --}}
                <div
                    class="relative h-[330px] overflow-hidden rounded-2xl border border-slate-200 shadow-inner sm:h-[400px]"
                    :style="previewStyle"
                >
                    <div
                        x-show="overlayEnabled"
                        class="absolute inset-0 flex items-center justify-center p-6 text-center"
                    >
                        <div
                            :style="'color:' + overlayTextColor"
                            class="max-w-xs text-2xl font-bold drop-shadow-sm"
                            x-text="overlayText"
                        ></div>
                    </div>

                    <div class="absolute inset-x-0 bottom-0 bg-black/30 px-4 py-3 backdrop-blur-sm">
                        <div class="text-[10px] font-medium uppercase tracking-wide text-white/70">
                            Live Preview
                        </div>

                        <div
                            class="mt-1 text-xs font-semibold text-white"
                            x-text="layerSummary"
                        ></div>
                    </div>
                </div>

                {{-- Preview controls --}}
                <div class="mt-3 rounded-xl border border-slate-200 bg-white p-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="flex items-center gap-2 text-xs text-slate-600">
                            <input
                                type="checkbox"
                                x-model="overlayEnabled"
                                class="rounded border-slate-300 text-indigo-600"
                            >
                            Text overlay
                        </label>

                        <input
                            type="text"
                            x-model="overlayText"
                            class="min-w-[130px] flex-1 rounded-lg border border-slate-300 px-2.5 py-2 text-xs"
                            placeholder="Preview text"
                        >

                        <input
                            type="color"
                            x-model="overlayTextColor"
                            class="h-8 w-10 rounded border border-slate-300"
                            title="Text color"
                        >
                    </div>
                </div>

                {{-- Output tabs --}}
                <div class="mt-5">
                    <div class="flex gap-1 overflow-x-auto rounded-lg border border-slate-200 bg-white p-1">
                        <template x-for="tab in outputTabs" :key="tab.value">
                            <button
                                type="button"
                                @click="outputTab = tab.value"
                                :class="outputTab === tab.value ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-slate-800'"
                                class="shrink-0 rounded-md px-3 py-2 text-[11px] font-semibold"
                                x-text="tab.label"
                            ></button>
                        </template>
                    </div>

                    <div class="mt-2 rounded-xl border border-slate-200 bg-white">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-3 py-2.5">
                            <span
                                class="text-xs font-semibold text-slate-800"
                                x-text="outputTitle"
                            ></span>

                            <button
                                type="button"
                                @click="copyOutput()"
                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-[11px] font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-700"
                                x-text="copyLabel"
                            ></button>
                        </div>

                        <pre class="max-h-[250px] overflow-auto p-4 text-[11px] leading-6 text-slate-700"><code x-text="outputValue"></code></pre>
                    </div>
                </div>

                {{-- Contrast --}}
                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xs font-semibold text-slate-900">
                                WCAG Contrast Check
                            </h3>

                            <p class="mt-1 text-[11px] text-slate-500">
                                Checks the selected foreground against sampled gradient points.
                            </p>
                        </div>

                        <input
                            type="color"
                            x-model="contrastForeground"
                            @input="calculateContrast()"
                            class="h-8 w-10 rounded border border-slate-300"
                        >
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <div class="rounded-lg bg-slate-50 p-2.5">
                            <div class="text-[10px] text-slate-400">Minimum</div>
                            <div
                                class="mt-1 text-sm font-bold text-slate-900"
                                x-text="contrastResult.min.toFixed(2) + ':1'"
                            ></div>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-2.5">
                            <div class="text-[10px] text-slate-400">Average</div>
                            <div
                                class="mt-1 text-sm font-bold text-slate-900"
                                x-text="contrastResult.average.toFixed(2) + ':1'"
                            ></div>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-2.5">
                            <div class="text-[10px] text-slate-400">AA</div>
                            <div
                                class="mt-1 text-sm font-bold"
                                :class="contrastResult.aa ? 'text-emerald-600' : 'text-red-600'"
                                x-text="contrastResult.aa ? 'Pass' : 'Fail'"
                            ></div>
                        </div>
                    </div>
                </div>

                {{-- Tools --}}
                <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <button
                        type="button"
                        @click="downloadText()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 hover:border-indigo-300"
                    >
                        Export CSS
                    </button>

                    <button
                        type="button"
                        @click="exportSvg()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 hover:border-indigo-300"
                    >
                        Export SVG
                    </button>

                    <button
                        type="button"
                        @click="exportPng()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 hover:border-indigo-300"
                    >
                        Export PNG
                    </button>

                    <button
                        type="button"
                        @click="shareConfiguration()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 hover:border-indigo-300"
                    >
                        Share
                    </button>
                </div>

                {{-- Image palette --}}
                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-xs font-semibold text-slate-900">
                                Image Palette Extraction
                            </h3>

                            <p class="mt-1 text-[11px] text-slate-500">
                                Extract dominant colors locally from an image.
                            </p>
                        </div>

                        <label class="cursor-pointer rounded-lg border border-slate-200 px-3 py-2 text-[11px] font-semibold text-slate-600 hover:border-indigo-300">
                            Choose Image
                            <input
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="extractPalette($event)"
                            >
                        </label>
                    </div>

                    <div
                        x-show="palette.length"
                        class="mt-3 flex flex-wrap gap-2"
                    >
                        <template x-for="color in palette" :key="color">
                            <button
                                type="button"
                                @click="addPaletteColor(color)"
                                class="group flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-2 py-1.5"
                            >
                                <span
                                    class="h-6 w-6 rounded"
                                    :style="'background:' + color"
                                ></span>

                                <span
                                    class="font-mono text-[10px] text-slate-600"
                                    x-text="color"
                                ></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                        <div class="text-[10px] text-slate-400">Layers</div>
                        <div class="mt-1 text-sm font-bold text-slate-900" x-text="layers.length"></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                        <div class="text-[10px] text-slate-400">Stops</div>
                        <div class="mt-1 text-sm font-bold text-slate-900" x-text="totalStops"></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                        <div class="text-[10px] text-slate-400">CSS Size</div>
                        <div class="mt-1 text-sm font-bold text-slate-900" x-text="cssCode.length + ' chars'"></div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-3">
                        <div class="text-[10px] text-slate-400">Processing</div>
                        <div class="mt-1 text-sm font-bold text-emerald-600">
                            Local
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Import CSS --}}
    <div
        x-show="showImport"
        x-transition
        class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"
    >
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">
                    Import CSS Gradient
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Paste a linear, radial or conic gradient declaration.
                </p>
            </div>

            <button
                type="button"
                @click="showImport = false"
                class="text-lg text-slate-400 hover:text-slate-700"
            >
                ×
            </button>
        </div>

        <textarea
            x-model="importCss"
            rows="5"
            class="mt-4 w-full rounded-xl border border-slate-300 bg-slate-50 p-3 font-mono text-xs leading-6 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
            placeholder="background: linear-gradient(135deg, #667eea, #764ba2);"
        ></textarea>

        <div class="mt-3 flex flex-wrap gap-2">
            <button
                type="button"
                @click="parseImportedCss()"
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-indigo-700"
            >
                Import Gradient
            </button>

            <button
                type="button"
                @click="importCss = ''"
                class="rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600"
            >
                Clear
            </button>
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-show="toast"
        x-transition
        class="fixed bottom-5 right-5 z-50 rounded-xl border border-slate-200 bg-slate-900 px-4 py-3 text-xs font-semibold text-white shadow-lg"
        x-text="toast"
    ></div>
</div>

@script
<script>
    window.aabiCssGradientGenerator = function () {
        return {
            mode: 'linear',

            layers: [],

            selectedLayer: 0,

            history: [],

            historyIndex: -1,

            historyTimer: null,

            draggingStopId: null,

            outputTab: 'css',

            presetCategory: 'all',

            showImport: false,

            importCss: '',

            overlayEnabled: true,

            overlayText: 'Beautiful Gradient',

            overlayTextColor: '#FFFFFF',

            contrastForeground: '#FFFFFF',

            contrastResult: {
                min: 0,
                average: 0,
                aa: false
            },

            palette: [],

            toast: '',

            copyLabel: 'Copy',

            outputTabs: [
                { value: 'css', label: 'CSS' },
                { value: 'scss', label: 'SCSS' },
                { value: 'variables', label: 'Variables' },
                { value: 'tailwind', label: 'Tailwind' }
            ],

            directions: [
                { label: '↑', value: 'top', angle: 0 },
                { label: '↗', value: 'top-right', angle: 45 },
                { label: '→', value: 'right', angle: 90 },
                { label: '↘', value: 'bottom-right', angle: 135 },
                { label: '↓', value: 'bottom', angle: 180 },
                { label: '↙', value: 'bottom-left', angle: 225 },
                { label: '←', value: 'left', angle: 270 },
                { label: '↖', value: 'top-left', angle: 315 }
            ],

            presets: [
                {
                    name: 'Sunset',
                    category: 'hero',
                    value: 'linear-gradient(135deg, #ff9966 0%, #ff5e62 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#FF9966', '#FF5E62']
                },
                {
                    name: 'Ocean',
                    category: 'background',
                    value: 'linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#2193B0', '#6DD5ED']
                },
                {
                    name: 'Purple',
                    category: 'hero',
                    value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#667EEA', '#764BA2']
                },
                {
                    name: 'Emerald',
                    category: 'background',
                    value: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#11998E', '#38EF7D']
                },
                {
                    name: 'Peach',
                    category: 'button',
                    value: 'linear-gradient(135deg, #ed4264 0%, #ffedbc 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#ED4264', '#FFEDBC']
                },
                {
                    name: 'Midnight',
                    category: 'background',
                    value: 'linear-gradient(135deg, #232526 0%, #414345 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#232526', '#414345']
                },
                {
                    name: 'Aurora',
                    category: 'hero',
                    value: 'linear-gradient(135deg, #00c6ff 0%, #0072ff 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#00C6FF', '#0072FF']
                },
                {
                    name: 'Fire',
                    category: 'design',
                    value: 'radial-gradient(circle, #f12711 0%, #f5af19 100%)',
                    type: 'radial',
                    colors: ['#F12711', '#F5AF19']
                },
                {
                    name: 'Forest',
                    category: 'background',
                    value: 'radial-gradient(circle, #134e5e 0%, #71b280 100%)',
                    type: 'radial',
                    colors: ['#134E5E', '#71B280']
                },
                {
                    name: 'Candy',
                    category: 'button',
                    value: 'linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%)',
                    type: 'linear',
                    angle: 90,
                    colors: ['#FF9A9E', '#FAD0C4']
                },
                {
                    name: 'Deep Space',
                    category: 'background',
                    value: 'linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%)',
                    type: 'linear',
                    angle: 135,
                    colors: ['#0F2027', '#203A43', '#2C5364']
                },
                {
                    name: 'Instagram',
                    category: 'design',
                    value: 'linear-gradient(45deg, #feda75 0%, #fa7e1e 30%, #d62976 65%, #4f5bd5 100%)',
                    type: 'linear',
                    angle: 45,
                    colors: ['#FEDA75', '#FA7E1E', '#D62976', '#4F5BD5']
                }
            ],

            init() {
                this.createDefaultLayer();
                this.loadSettings();
                this.loadSharedState();
                this.pushHistory();
                this.calculateContrast();

                window.addEventListener('pointermove', (event) => {
                    this.dragStop(event);
                });

                window.addEventListener('pointerup', () => {
                    this.endDrag();
                });
            },

            createDefaultLayer() {
                this.layers = [
                    this.makeLayer('linear')
                ];
            },

            makeLayer(type) {
                return {
                    id: this.uid(),
                    type: type || 'linear',
                    repeating: false,
                    angle: 135,
                    shape: 'ellipse',
                    position: 'center',
                    opacity: 1,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    blendMode: 'normal',
                    stops: [
                        {
                            id: this.uid(),
                            color: '#667EEA',
                            alpha: 1,
                            position: 0
                        },
                        {
                            id: this.uid(),
                            color: '#764BA2',
                            alpha: 1,
                            position: 100
                        }
                    ]
                };
            },

            get currentLayer() {
                return this.layers[this.selectedLayer] || this.layers[0];
            },

            get sortedStops() {
                if (!this.currentLayer) {
                    return [];
                }

                return [...this.currentLayer.stops].sort(function (a, b) {
                    return Number(a.position) - Number(b.position);
                });
            },

            get filteredPresets() {
                if (this.presetCategory === 'all') {
                    return this.presets;
                }

                return this.presets.filter((preset) => {
                    return preset.category === this.presetCategory;
                });
            },

            get totalStops() {
                return this.layers.reduce(function (total, layer) {
                    return total + layer.stops.length;
                }, 0);
            },

            get currentGradientValue() {
                return this.buildGradient(this.currentLayer);
            },

            get cssCode() {
                var lines = [];

                lines.push('.gradient {');
                lines.push('    background: ' + this.backgroundValue + ';');

                if (this.layers.length > 1) {
                    lines.push('    background-size: ' + this.backgroundSizeValue + ';');
                    lines.push('    background-position: ' + this.backgroundPositionValue + ';');
                    lines.push('    background-blend-mode: ' + this.backgroundBlendValue + ';');
                }

                lines.push('}');

                return lines.join('\n');
            },

            get backgroundValue() {
                return this.layers
                    .map((layer) => this.buildGradient(layer))
                    .join(', ');
            },

            get backgroundSizeValue() {
                return this.layers
                    .map(function (layer) {
                        return layer.backgroundSize;
                    })
                    .join(', ');
            },

            get backgroundPositionValue() {
                return this.layers
                    .map(function (layer) {
                        return layer.backgroundPosition;
                    })
                    .join(', ');
            },

            get backgroundBlendValue() {
                return this.layers
                    .map(function (layer) {
                        return layer.blendMode;
                    })
                    .join(', ');
            },

            get previewStyle() {
                return [
                    'background:' + this.backgroundValue,
                    'background-size:' + this.backgroundSizeValue,
                    'background-position:' + this.backgroundPositionValue,
                    'background-blend-mode:' + this.backgroundBlendValue
                ].join(';');
            },

            get layerSummary() {
                return this.layers.length +
                    ' layer' +
                    (this.layers.length === 1 ? '' : 's') +
                    ' · ' +
                    this.totalStops +
                    ' color stops';
            },

            get outputTitle() {
                var titles = {
                    css: 'CSS',
                    scss: 'SCSS / Sass',
                    variables: 'CSS Custom Properties',
                    tailwind: 'Tailwind CSS'
                };

                return titles[this.outputTab] || 'CSS';
            },

            get outputValue() {
                if (this.outputTab === 'scss') {
                    return this.scssCode();
                }

                if (this.outputTab === 'variables') {
                    return this.variablesCode();
                }

                if (this.outputTab === 'tailwind') {
                    return this.tailwindCode();
                }

                return this.cssCode;
            },

            get shareState() {
                return {
                    version: 1,
                    layers: this.layers,
                    selectedLayer: this.selectedLayer,
                    overlayEnabled: this.overlayEnabled,
                    overlayText: this.overlayText,
                    overlayTextColor: this.overlayTextColor,
                    contrastForeground: this.contrastForeground
                };
            },

            setMode(mode) {
                this.mode = mode;

                if (mode === 'css') {
                    this.showImport = true;
                }

                this.commit();
            },

            selectLayer(index) {
                if (index < 0 || index >= this.layers.length) {
                    return;
                }

                this.selectedLayer = index;
            },

            updateLayer(values) {
                Object.assign(this.currentLayer, values);
                this.commit();
            },

            setAngle(angle) {
                this.currentLayer.angle = Number(angle);
                this.commit();
            },

            addLayer() {
                if (this.layers.length >= 8) {
                    return;
                }

                this.layers.push(this.makeLayer('linear'));
                this.selectedLayer = this.layers.length - 1;
                this.commit();
            },

            removeLayer(index) {
                if (this.layers.length <= 1) {
                    return;
                }

                this.layers.splice(index, 1);

                if (this.selectedLayer >= this.layers.length) {
                    this.selectedLayer = this.layers.length - 1;
                }

                this.commit();
            },

            addStop() {
                var layer = this.currentLayer;

                if (!layer || layer.stops.length >= 12) {
                    return;
                }

                var stops = this.sortedStops;

                var position = 50;

                if (stops.length >= 2) {
                    var first = Number(stops[0].position);
                    var last = Number(stops[stops.length - 1].position);

                    position = Math.round((first + last) / 2);

                    if (stops.some(function (stop) {
                        return Number(stop.position) === position;
                    })) {
                        position = Math.min(99, position + 1);
                    }
                }

                var color = this.interpolateColor(
                    stops[0].color,
                    stops[stops.length - 1].color,
                    0.5
                );

                layer.stops.push({
                    id: this.uid(),
                    color: color,
                    alpha: 1,
                    position: position
                });

                this.commit();
            },

            removeStop(index) {
                if (this.currentLayer.stops.length <= 2) {
                    return;
                }

                this.currentLayer.stops.splice(index, 1);
                this.commit();
            },

            normalizeStop(stop) {
                var parsed = this.parseColor(stop.color);

                if (!parsed) {
                    stop.color = '#000000';
                    stop.alpha = 1;
                    this.commit();
                    return;
                }

                stop.color = this.rgbToHex(parsed.r, parsed.g, parsed.b);

                if (parsed.a !== undefined) {
                    stop.alpha = parsed.a;
                }

                stop.position = this.clamp(
                    Number(stop.position) || 0,
                    0,
                    100
                );

                this.commit();
            },

            buildGradient(layer) {
                var stops = [...layer.stops].sort(function (a, b) {
                    return Number(a.position) - Number(b.position);
                });

                var stopText = stops.map((stop) => {
                    var color = this.colorWithAlpha(stop.color, stop.alpha);
                    return color + ' ' + this.cleanNumber(stop.position) + '%';
                }).join(', ');

                var prefix = '';

                if (layer.type === 'radial') {
                    prefix = layer.shape + ' at ' + layer.position;
                } else if (layer.type === 'conic') {
                    prefix = 'from ' +
                        this.cleanNumber(layer.angle) +
                        'deg at ' +
                        layer.position;
                } else {
                    prefix = this.cleanNumber(layer.angle) + 'deg';
                }

                var name = layer.type + '-gradient';

                if (layer.repeating) {
                    name = 'repeating-' + name;
                }

                return name + '(' + prefix + ', ' + stopText + ')';
            },

            colorWithAlpha(color, alpha) {
                var parsed = this.parseColor(color);

                if (!parsed) {
                    return color;
                }

                var a = alpha === undefined ? 1 : this.clamp(Number(alpha), 0, 1);

                if (a >= 0.999) {
                    return this.rgbToHex(parsed.r, parsed.g, parsed.b);
                }

                return 'rgba(' +
                    parsed.r + ', ' +
                    parsed.g + ', ' +
                    parsed.b + ', ' +
                    this.cleanNumber(a) +
                    ')';
            },

            startDrag(event, id) {
                this.draggingStopId = id;

                if (event.currentTarget && event.currentTarget.setPointerCapture) {
                    try {
                        event.currentTarget.setPointerCapture(event.pointerId);
                    } catch (error) {
                    }
                }
            },

            dragStop(event) {
                if (!this.draggingStopId || !this.currentLayer) {
                    return;
                }

                var track = event.target.closest
                    ? event.target.closest('[x-data]')
                    : null;

                var timeline = document.querySelector(
                    '[x-data="aabiCssGradientGenerator()"] .relative.h-10'
                );

                if (!timeline) {
                    return;
                }

                var rect = timeline.getBoundingClientRect();

                if (
                    event.clientX < rect.left - 20 ||
                    event.clientX > rect.right + 20
                ) {
                    return;
                }

                var position =
                    ((event.clientX - rect.left) / rect.width) * 100;

                this.setStopPosition(this.draggingStopId, position, false);
            },

            endDrag() {
                if (!this.draggingStopId) {
                    return;
                }

                this.draggingStopId = null;
                this.commit();
            },

            timelinePointerDown(event) {
                if (!this.currentLayer) {
                    return;
                }

                var rect = event.currentTarget.getBoundingClientRect();

                var position =
                    ((event.clientX - rect.left) / rect.width) * 100;

                var nearest = this.sortedStops.reduce(function (best, stop) {
                    if (!best) {
                        return stop;
                    }

                    return Math.abs(Number(stop.position) - position) <
                        Math.abs(Number(best.position) - position)
                        ? stop
                        : best;
                }, null);

                if (nearest) {
                    this.setStopPosition(nearest.id, position);
                    this.draggingStopId = nearest.id;
                }
            },

            setStopPosition(id, position, shouldCommit) {
                var stop = this.currentLayer.stops.find(function (item) {
                    return item.id === id;
                });

                if (!stop) {
                    return;
                }

                stop.position = this.clamp(Number(position), 0, 100);

                if (shouldCommit !== false) {
                    this.commitDebounced();
                }
            },

            moveStop(id, amount) {
                var stop = this.currentLayer.stops.find(function (item) {
                    return item.id === id;
                });

                if (!stop) {
                    return;
                }

                stop.position = this.clamp(
                    Number(stop.position) + amount,
                    0,
                    100
                );

                this.commit();
            },

            reverseGradient() {
                this.layers.forEach(function (layer) {
                    layer.stops.forEach(function (stop) {
                        stop.position = 100 - Number(stop.position);
                    });
                });

                this.commit();
            },

            randomize() {
                var count = 2 + Math.floor(Math.random() * 4);

                var stops = [];

                for (var i = 0; i < count; i++) {
                    stops.push({
                        id: this.uid(),
                        color: this.randomHex(),
                        alpha: 1,
                        position: Math.round(
                            (100 / (count - 1)) * i
                        )
                    });
                }

                var typeList = ['linear', 'radial', 'conic'];

                var type =
                    typeList[Math.floor(Math.random() * typeList.length)];

                this.currentLayer.type = type;
                this.currentLayer.repeating = Math.random() > 0.72;
                this.currentLayer.angle =
                    Math.floor(Math.random() * 361);
                this.currentLayer.shape =
                    Math.random() > 0.5 ? 'circle' : 'ellipse';
                this.currentLayer.position = 'center';
                this.currentLayer.stops = stops;

                this.commit();
            },

            applyPreset(preset) {
                this.layers = [
                    {
                        id: this.uid(),
                        type: preset.type,
                        repeating: false,
                        angle: preset.angle || 135,
                        shape: 'ellipse',
                        position: 'center',
                        opacity: 1,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                        blendMode: 'normal',
                        stops: preset.colors.map((color, index) => {
                            return {
                                id: this.uid(),
                                color: color,
                                alpha: 1,
                                position: Math.round(
                                    (100 / (preset.colors.length - 1)) * index
                                )
                            };
                        })
                    }
                ];

                this.selectedLayer = 0;
                this.commit();
            },

            reset() {
                this.createDefaultLayer();
                this.selectedLayer = 0;
                this.outputTab = 'css';
                this.overlayEnabled = true;
                this.overlayText = 'Beautiful Gradient';
                this.overlayTextColor = '#FFFFFF';
                this.contrastForeground = '#FFFFFF';

                try {
                    localStorage.removeItem('aabi.css-gradient.settings');
                } catch (error) {
                }

                this.pushHistory();
                this.calculateContrast();
            },

            getScssLayer(layer) {
                return this.buildGradient(layer);
            },

            scssCode() {
                var lines = [];

                lines.push('$gradient: ' + this.backgroundValue + ';');
                lines.push('');
                lines.push('.gradient {');
                lines.push('  background: $gradient;');
                lines.push('}');

                return lines.join('\n');
            },

            variablesCode() {
                var lines = [];

                lines.push(':root {');
                lines.push('  --gradient: ' + this.backgroundValue + ';');

                this.layers.forEach(function (layer, index) {
                    lines.push(
                        '  --gradient-' +
                        (index + 1) +
                        ': ' +
                        this.buildGradient(layer) +
                        ';'
                    );
                }, this);

                lines.push('}');
                lines.push('');
                lines.push('.gradient {');
                lines.push('  background: var(--gradient);');
                lines.push('}');

                return lines.join('\n');
            },

            tailwindCode() {
                var value = this.backgroundValue
                    .replace(/"/g, '&quot;');

                return 'class="bg-[' + value + ']"';
            },

            copyOutput() {
                var value = this.outputValue;

                this.copyText(value).then(() => {
                    this.copyLabel = '✓ Copied';

                    this.showToast('Copied to clipboard');

                    setTimeout(() => {
                        this.copyLabel = 'Copy';
                    }, 1600);
                });
            },

            copyText(value) {
                if (
                    navigator.clipboard &&
                    typeof navigator.clipboard.writeText === 'function'
                ) {
                    return navigator.clipboard.writeText(value);
                }

                return new Promise(function (resolve, reject) {
                    var textarea = document.createElement('textarea');

                    textarea.value = value;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';

                    document.body.appendChild(textarea);
                    textarea.select();

                    try {
                        document.execCommand('copy');
                        document.body.removeChild(textarea);
                        resolve();
                    } catch (error) {
                        document.body.removeChild(textarea);
                        reject(error);
                    }
                });
            },

            downloadText() {
                var blob = new Blob(
                    [this.cssCode],
                    { type: 'text/css;charset=utf-8' }
                );

                var url = URL.createObjectURL(blob);

                var anchor = document.createElement('a');

                anchor.href = url;
                anchor.download = 'aabitech-gradient.css';

                document.body.appendChild(anchor);
                anchor.click();
                anchor.remove();

                URL.revokeObjectURL(url);

                this.showToast('CSS file exported');
            },

            exportSvg() {
                var width = 1200;
                var height = 700;

                var svg = this.buildSvg(width, height);

                var blob = new Blob(
                    [svg],
                    { type: 'image/svg+xml;charset=utf-8' }
                );

                var url = URL.createObjectURL(blob);

                var anchor = document.createElement('a');

                anchor.href = url;
                anchor.download = 'aabitech-gradient.svg';

                document.body.appendChild(anchor);
                anchor.click();
                anchor.remove();

                URL.revokeObjectURL(url);

                this.showToast('SVG exported');
            },

            buildSvg(width, height) {
                var defs = [];
                var layerMarkup = [];

                this.layers.forEach((layer, layerIndex) => {
                    var id = 'gradient-' + layerIndex;

                    var stops = layer.stops
                        .slice()
                        .sort(function (a, b) {
                            return Number(a.position) - Number(b.position);
                        })
                        .map((stop) => {
                            var parsed = this.parseColor(stop.color);

                            if (!parsed) {
                                parsed = {
                                    r: 0,
                                    g: 0,
                                    b: 0
                                };
                            }

                            return '<stop offset="' +
                                this.cleanNumber(stop.position) +
                                '%" stop-color="' +
                                this.rgbToHex(parsed.r, parsed.g, parsed.b) +
                                '" stop-opacity="' +
                                this.cleanNumber(stop.alpha) +
                                '"/>';
                        })
                        .join('');

                    if (layer.type === 'radial') {
                        defs.push(
                            '<radialGradient id="' +
                            id +
                            '" cx="50%" cy="50%" r="75%">' +
                            stops +
                            '</radialGradient>'
                        );

                        layerMarkup.push(
                            '<rect width="100%" height="100%" fill="url(#' +
                            id +
                            ')" opacity="' +
                            this.cleanNumber(layer.opacity) +
                            '"/>'
                        );

                        return;
                    }

                    if (layer.type === 'conic') {
                        var segments = 120;

                        for (var i = 0; i < segments; i++) {
                            var start = (i / segments) * 360;
                            var end = ((i + 1) / segments) * 360;

                            var color = this.interpolateStops(
                                layer.stops,
                                (i / segments) * 100
                            );

                            var points = this.wedgePoints(
                                width / 2,
                                height / 2,
                                Math.max(width, height),
                                start,
                                end
                            );

                            layerMarkup.push(
                                '<polygon points="' +
                                points +
                                '" fill="' +
                                color +
                                '" opacity="' +
                                this.cleanNumber(layer.opacity) +
                                '"/>'
                            );
                        }

                        return;
                    }

                    var angle = Number(layer.angle) || 0;

                    var angleRad = (angle - 90) * Math.PI / 180;

                    var dx = Math.cos(angleRad);
                    var dy = Math.sin(angleRad);

                    var x1 = 50 - dx * 50;
                    var y1 = 50 - dy * 50;
                    var x2 = 50 + dx * 50;
                    var y2 = 50 + dy * 50;

                    defs.push(
                        '<linearGradient id="' +
                        id +
                        '" x1="' +
                        x1 +
                        '%" y1="' +
                        y1 +
                        '%" x2="' +
                        x2 +
                        '%" y2="' +
                        y2 +
                        '%">' +
                        stops +
                        '</linearGradient>'
                    );

                    layerMarkup.push(
                        '<rect width="100%" height="100%" fill="url(#' +
                        id +
                        ')" opacity="' +
                        this.cleanNumber(layer.opacity) +
                        '"/>'
                    );
                });

                return [
                    '<svg xmlns="http://www.w3.org/2000/svg" width="' +
                    width +
                    '" height="' +
                    height +
                    '" viewBox="0 0 ' +
                    width +
                    ' ' +
                    height +
                    '">',
                    '<defs>',
                    defs.join(''),
                    '</defs>',
                    layerMarkup.join(''),
                    '</svg>'
                ].join('');
            },

            wedgePoints(cx, cy, radius, start, end) {
                var a1 = (start - 90) * Math.PI / 180;
                var a2 = (end - 90) * Math.PI / 180;

                var x1 = cx + Math.cos(a1) * radius;
                var y1 = cy + Math.sin(a1) * radius;

                var x2 = cx + Math.cos(a2) * radius;
                var y2 = cy + Math.sin(a2) * radius;

                return [
                    cx + ',' + cy,
                    x1 + ',' + y1,
                    x2 + ',' + y2
                ].join(' ');
            },

            exportPng() {
                var width = 1200;
                var height = 700;

                var canvas = document.createElement('canvas');

                canvas.width = width;
                canvas.height = height;

                var context = canvas.getContext('2d');

                if (!context) {
                    this.showToast('PNG export is unavailable');
                    return;
                }

                var preview = document.createElement('div');

                preview.style.position = 'fixed';
                preview.style.left = '-10000px';
                preview.style.top = '0';
                preview.style.width = width + 'px';
                preview.style.height = height + 'px';
                preview.style.background = this.backgroundValue;

                document.body.appendChild(preview);

                var computed = getComputedStyle(preview).backgroundColor;

                context.fillStyle = computed || '#ffffff';
                context.fillRect(0, 0, width, height);

                var imageData = context.createImageData(width, height);

                for (var y = 0; y < height; y++) {
                    for (var x = 0; x < width; x++) {
                        var color = this.sampleGradient(
                            x / width * 100,
                            y / height * 100
                        );

                        var index = (y * width + x) * 4;

                        imageData.data[index] = color.r;
                        imageData.data[index + 1] = color.g;
                        imageData.data[index + 2] = color.b;
                        imageData.data[index + 3] = 255;
                    }
                }

                context.putImageData(imageData, 0, 0);

                document.body.removeChild(preview);

                canvas.toBlob((blob) => {
                    if (!blob) {
                        return;
                    }

                    var url = URL.createObjectURL(blob);
                    var anchor = document.createElement('a');

                    anchor.href = url;
                    anchor.download = 'aabitech-gradient.png';

                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();

                    URL.revokeObjectURL(url);

                    this.showToast('PNG exported');
                }, 'image/png');
            },

            sampleGradient(xPercent, yPercent) {
                if (!this.layers.length) {
                    return {
                        r: 255,
                        g: 255,
                        b: 255
                    };
                }

                var base = {
                    r: 255,
                    g: 255,
                    b: 255
                };

                this.layers.forEach((layer) => {
                    var position = 50;

                    if (layer.type === 'linear') {
                        var angle = Number(layer.angle) * Math.PI / 180;

                        var x = xPercent - 50;
                        var y = yPercent - 50;

                        position =
                            ((x * Math.sin(angle)) +
                            (y * -Math.cos(angle))) + 50;
                    } else if (layer.type === 'radial') {
                        var dx = xPercent - 50;
                        var dy = yPercent - 50;

                        position =
                            Math.sqrt(dx * dx + dy * dy) / 0.7071;
                    } else {
                        var cx = 50;
                        var cy = 50;

                        var radians =
                            Math.atan2(
                                yPercent - cy,
                                xPercent - cx
                            );

                        position =
                            ((radians * 180 / Math.PI) + 450) % 360 / 3.6;
                    }

                    position = this.clamp(position, 0, 100);

                    var color = this.interpolateStops(
                        layer.stops,
                        position
                    );

                    var opacity = this.clamp(
                        Number(layer.opacity),
                        0,
                        1
                    );

                    base.r =
                        color.r * opacity +
                        base.r * (1 - opacity);

                    base.g =
                        color.g * opacity +
                        base.g * (1 - opacity);

                    base.b =
                        color.b * opacity +
                        base.b * (1 - opacity);
                });

                return {
                    r: Math.round(base.r),
                    g: Math.round(base.g),
                    b: Math.round(base.b)
                };
            },

            interpolateStops(stops, position) {
                var sorted = [...stops].sort(function (a, b) {
                    return Number(a.position) - Number(b.position);
                });

                if (!sorted.length) {
                    return {
                        r: 0,
                        g: 0,
                        b: 0
                    };
                }

                if (position <= Number(sorted[0].position)) {
                    var first = this.parseColor(sorted[0].color);

                    return first || { r: 0, g: 0, b: 0 };
                }

                var last = sorted[sorted.length - 1];

                if (position >= Number(last.position)) {
                    var finalColor = this.parseColor(last.color);

                    return finalColor || { r: 0, g: 0, b: 0 };
                }

                for (var i = 0; i < sorted.length - 1; i++) {
                    var left = sorted[i];
                    var right = sorted[i + 1];

                    if (
                        position >= Number(left.position) &&
                        position <= Number(right.position)
                    ) {
                        var span =
                            Number(right.position) -
                            Number(left.position);

                        var t =
                            span === 0
                                ? 0
                                : (position - Number(left.position)) / span;

                        return this.interpolateColor(
                            left.color,
                            right.color,
                            t
                        );
                    }
                }

                return {
                    r: 0,
                    g: 0,
                    b: 0
                };
            },

            interpolateColor(first, second, amount) {
                var a = this.parseColor(first);
                var b = this.parseColor(second);

                if (!a || !b) {
                    return '#000000';
                }

                return this.rgbToHex(
                    Math.round(a.r + (b.r - a.r) * amount),
                    Math.round(a.g + (b.g - a.g) * amount),
                    Math.round(a.b + (b.b - a.b) * amount)
                );
            },

            parseColor(value) {
                var input = String(value || '').trim().toLowerCase();

                if (!input) {
                    return null;
                }

                if (input.charAt(0) === '#') {
                    var hex = input.substring(1);

                    if (hex.length === 3) {
                        hex =
                            hex[0] + hex[0] +
                            hex[1] + hex[1] +
                            hex[2] + hex[2];
                    }

                    if (hex.length === 4) {
                        var alpha =
                            parseInt(hex[3] + hex[3], 16) / 255;

                        hex =
                            hex[0] + hex[0] +
                            hex[1] + hex[1] +
                            hex[2] + hex[2];

                        return {
                            r: parseInt(hex.substring(0, 2), 16),
                            g: parseInt(hex.substring(2, 4), 16),
                            b: parseInt(hex.substring(4, 6), 16),
                            a: alpha
                        };
                    }

                    if (hex.length === 6 || hex.length === 8) {
                        return {
                            r: parseInt(hex.substring(0, 2), 16),
                            g: parseInt(hex.substring(2, 4), 16),
                            b: parseInt(hex.substring(4, 6), 16),
                            a:
                                hex.length === 8
                                    ? parseInt(hex.substring(6, 8), 16) / 255
                                    : 1
                        };
                    }

                    return null;
                }

                var rgbMatch = input.match(
                    /^rgba?\(\s*([0-9.]+)\s*[, ]\s*([0-9.]+)\s*[, ]\s*([0-9.]+)(?:\s*[,/]\s*([0-9.]+%?))?\s*\)$/
                );

                if (rgbMatch) {
                    var alphaValue = 1;

                    if (rgbMatch[4] !== undefined) {
                        alphaValue =
                            rgbMatch[4].indexOf('%') >= 0
                                ? parseFloat(rgbMatch[4]) / 100
                                : parseFloat(rgbMatch[4]);
                    }

                    return {
                        r: this.clamp(Math.round(parseFloat(rgbMatch[1])), 0, 255),
                        g: this.clamp(Math.round(parseFloat(rgbMatch[2])), 0, 255),
                        b: this.clamp(Math.round(parseFloat(rgbMatch[3])), 0, 255),
                        a: this.clamp(alphaValue, 0, 1)
                    };
                }

                var hslMatch = input.match(
                    /^hsla?\(\s*([0-9.]+)\s*[, ]\s*([0-9.]+)%\s*[, ]\s*([0-9.]+)%(?:\s*[,/]\s*([0-9.]+%?))?\s*\)$/
                );

                if (hslMatch) {
                    var h =
                        ((parseFloat(hslMatch[1]) % 360) + 360) % 360;

                    var s = parseFloat(hslMatch[2]) / 100;
                    var l = parseFloat(hslMatch[3]) / 100;

                    var rgb = this.hslToRgb(h, s, l);

                    var hslAlpha = 1;

                    if (hslMatch[4] !== undefined) {
                        hslAlpha =
                            hslMatch[4].indexOf('%') >= 0
                                ? parseFloat(hslMatch[4]) / 100
                                : parseFloat(hslMatch[4]);
                    }

                    rgb.a = this.clamp(hslAlpha, 0, 1);

                    return rgb;
                }

                return null;
            },

            hslToRgb(h, s, l) {
                var c = (1 - Math.abs(2 * l - 1)) * s;
                var x = c * (1 - Math.abs((h / 60) % 2 - 1));
                var m = l - c / 2;

                var r = 0;
                var g = 0;
                var b = 0;

                if (h < 60) {
                    r = c;
                    g = x;
                } else if (h < 120) {
                    r = x;
                    g = c;
                } else if (h < 180) {
                    g = c;
                    b = x;
                } else if (h < 240) {
                    g = x;
                    b = c;
                } else if (h < 300) {
                    r = x;
                    b = c;
                } else {
                    r = c;
                    b = x;
                }

                return {
                    r: Math.round((r + m) * 255),
                    g: Math.round((g + m) * 255),
                    b: Math.round((b + m) * 255)
                };
            },

            rgbToHex(r, g, b) {
                return '#' +
                    [r, g, b]
                        .map(function (value) {
                            return Math.round(value)
                                .toString(16)
                                .padStart(2, '0');
                        })
                        .join('')
                        .toUpperCase();
            },

            calculateContrast() {
                var ratios = [];

                for (var i = 0; i <= 20; i++) {
                    var sample = this.sampleGradient(
                        (i / 20) * 100,
                        50
                    );

                    ratios.push(
                        this.contrastRatio(
                            this.contrastForeground,
                            this.rgbToHex(
                                sample.r,
                                sample.g,
                                sample.b
                            )
                        )
                    );
                }

                var minimum = Math.min.apply(null, ratios);

                var average =
                    ratios.reduce(function (sum, value) {
                        return sum + value;
                    }, 0) / ratios.length;

                this.contrastResult = {
                    min: minimum,
                    average: average,
                    aa: minimum >= 4.5
                };
            },

            contrastRatio(first, second) {
                var a = this.parseColor(first);
                var b = this.parseColor(second);

                if (!a || !b) {
                    return 1;
                }

                var la = this.relativeLuminance(a);
                var lb = this.relativeLuminance(b);

                var lighter = Math.max(la, lb);
                var darker = Math.min(la, lb);

                return (lighter + 0.05) / (darker + 0.05);
            },

            relativeLuminance(rgb) {
                var values = [rgb.r, rgb.g, rgb.b].map(function (value) {
                    var channel = value / 255;

                    return channel <= 0.03928
                        ? channel / 12.92
                        : Math.pow(
                            (channel + 0.055) / 1.055,
                            2.4
                        );
                });

                return (
                    values[0] * 0.2126 +
                    values[1] * 0.7152 +
                    values[2] * 0.0722
                );
            },

            extractPalette(event) {
                var file =
                    event.target &&
                    event.target.files
                        ? event.target.files[0]
                        : null;

                if (!file || !file.type.startsWith('image/')) {
                    return;
                }

                var reader = new FileReader();

                reader.onload = (loadEvent) => {
                    var image = new Image();

                    image.onload = () => {
                        var canvas = document.createElement('canvas');

                        var size = 100;

                        canvas.width = size;
                        canvas.height = size;

                        var context = canvas.getContext('2d', {
                            willReadFrequently: true
                        });

                        if (!context) {
                            return;
                        }

                        context.drawImage(
                            image,
                            0,
                            0,
                            size,
                            size
                        );

                        var data = context.getImageData(
                            0,
                            0,
                            size,
                            size
                        ).data;

                        var buckets = {};

                        for (var i = 0; i < data.length; i += 16) {
                            var r = data[i];
                            var g = data[i + 1];
                            var b = data[i + 2];
                            var a = data[i + 3];

                            if (a < 100) {
                                continue;
                            }

                            var qr = Math.round(r / 24) * 24;
                            var qg = Math.round(g / 24) * 24;
                            var qb = Math.round(b / 24) * 24;

                            var key =
                                qr + ',' +
                                qg + ',' +
                                qb;

                            buckets[key] =
                                (buckets[key] || 0) + 1;
                        }

                        this.palette = Object.keys(buckets)
                            .sort(function (a, b) {
                                return buckets[b] - buckets[a];
                            })
                            .slice(0, 8)
                            .map((key) => {
                                var parts = key.split(',');

                                return this.rgbToHex(
                                    Number(parts[0]),
                                    Number(parts[1]),
                                    Number(parts[2])
                                );
                            });

                        this.showToast(
                            'Palette extracted locally'
                        );
                    };

                    image.src = loadEvent.target.result;
                };

                reader.readAsDataURL(file);

                event.target.value = '';
            },

            addPaletteColor(color) {
                if (this.currentLayer.stops.length >= 12) {
                    return;
                }

                this.currentLayer.stops.push({
                    id: this.uid(),
                    color: color,
                    alpha: 1,
                    position: 50
                });

                this.commit();
            },

            parseImportedCss() {
                var input = String(this.importCss || '').trim();

                if (!input) {
                    this.showToast('Paste a CSS gradient first');
                    return;
                }

                var gradients = this.extractGradientFunctions(input);

                if (!gradients.length) {
                    this.showToast('No supported CSS gradient found');
                    return;
                }

                var parsedLayers = [];

                gradients.forEach((gradient) => {
                    var parsed = this.parseGradientFunction(gradient);

                    if (parsed) {
                        parsedLayers.push(parsed);
                    }
                });

                if (!parsedLayers.length) {
                    this.showToast('Gradient could not be parsed');
                    return;
                }

                this.layers = parsedLayers;
                this.selectedLayer = 0;
                this.mode = 'linear';

                this.commit();

                this.showImport = false;

                this.showToast('Gradient imported');
            },

            extractGradientFunctions(input) {
                var results = [];

                var names = [
                    'linear-gradient',
                    'radial-gradient',
                    'conic-gradient',
                    'repeating-linear-gradient',
                    'repeating-radial-gradient',
                    'repeating-conic-gradient'
                ];

                names.forEach(function (name) {
                    var start = 0;

                    while (true) {
                        var index = input.indexOf(name + '(', start);

                        if (index < 0) {
                            break;
                        }

                        var depth = 0;
                        var end = -1;

                        for (var i = index; i < input.length; i++) {
                            if (input[i] === '(') {
                                depth++;
                            } else if (input[i] === ')') {
                                depth--;

                                if (depth === 0) {
                                    end = i + 1;
                                    break;
                                }
                            }
                        }

                        if (end > index) {
                            results.push(
                                input.substring(index, end)
                            );

                            start = end;
                        } else {
                            break;
                        }
                    }
                });

                return results;
            },

            parseGradientFunction(input) {
                var lower = input.toLowerCase();

                var repeating =
                    lower.indexOf('repeating-') === 0;

                var type = 'linear';

                if (lower.indexOf('radial-gradient') >= 0) {
                    type = 'radial';
                } else if (lower.indexOf('conic-gradient') >= 0) {
                    type = 'conic';
                }

                var firstOpen = input.indexOf('(');
                var lastClose = input.lastIndexOf(')');

                if (firstOpen < 0 || lastClose < 0) {
                    return null;
                }

                var body = input.substring(
                    firstOpen + 1,
                    lastClose
                );

                var parts = this.splitTopLevel(body, ',');

                if (parts.length < 2) {
                    return null;
                }

                var descriptor = parts.shift().trim();

                if (type === 'linear' && descriptor.indexOf('deg') >= 0) {
                    var angleMatch =
                        descriptor.match(/(-?[0-9.]+)deg/i);

                    if (angleMatch) {
                        var parsedAngle =
                            Number(angleMatch[1]);

                        var stops = this.parseStops(parts);

                        if (stops.length >= 2) {
                            return {
                                id: this.uid(),
                                type: type,
                                repeating: repeating,
                                angle: parsedAngle,
                                shape: 'ellipse',
                                position: 'center',
                                opacity: 1,
                                backgroundSize: 'cover',
                                backgroundPosition: 'center',
                                blendMode: 'normal',
                                stops: stops
                            };
                        }
                    }
                }

                if (type === 'linear') {
                    var stopsLinear = this.parseStops(
                        [descriptor].concat(parts)
                    );

                    if (stopsLinear.length >= 2) {
                        return {
                            id: this.uid(),
                            type: 'linear',
                            repeating: repeating,
                            angle: 180,
                            shape: 'ellipse',
                            position: 'center',
                            opacity: 1,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center',
                            blendMode: 'normal',
                            stops: stopsLinear
                        };
                    }
                }

                if (type === 'radial') {
                    var radialParts =
                        [descriptor].concat(parts);

                    var radialStops =
                        this.parseStops(radialParts);

                    if (radialStops.length >= 2) {
                        return {
                            id: this.uid(),
                            type: 'radial',
                            repeating: repeating,
                            angle: 0,
                            shape:
                                descriptor.indexOf('circle') >= 0
                                    ? 'circle'
                                    : 'ellipse',
                            position:
                                descriptor.indexOf('at ') >= 0
                                    ? descriptor
                                        .split('at ')[1]
                                        .trim()
                                    : 'center',
                            opacity: 1,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center',
                            blendMode: 'normal',
                            stops: radialStops
                        };
                    }
                }

                if (type === 'conic') {
                    var conicStops =
                        this.parseStops(parts);

                    if (conicStops.length >= 2) {
                        var conicAngle = 0;

                        var fromMatch =
                            descriptor.match(
                                /from\s+(-?[0-9.]+)deg/i
                            );

                        if (fromMatch) {
                            conicAngle =
                                Number(fromMatch[1]);
                        }

                        return {
                            id: this.uid(),
                            type: 'conic',
                            repeating: repeating,
                            angle: conicAngle,
                            shape: 'ellipse',
                            position:
                                descriptor.indexOf('at ') >= 0
                                    ? descriptor
                                        .split('at ')[1]
                                        .trim()
                                    : 'center',
                            opacity: 1,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center',
                            blendMode: 'normal',
                            stops: conicStops
                        };
                    }
                }

                return null;
            },

            parseStops(parts) {
                var stops = [];

                parts.forEach((part, index) => {
                    var value = part.trim();

                    var positionMatch =
                        value.match(
                            /\s+([0-9.]+)%\s*$/
                        );

                    var position =
                        positionMatch
                            ? Number(positionMatch[1])
                            : (
                                index /
                                Math.max(1, parts.length - 1)
                            ) * 100;

                    if (positionMatch) {
                        value = value
                            .substring(
                                0,
                                positionMatch.index
                            )
                            .trim();
                    }

                    var color = this.parseColor(value);

                    if (color) {
                        stops.push({
                            id: this.uid(),
                            color: this.rgbToHex(
                                color.r,
                                color.g,
                                color.b
                            ),
                            alpha:
                                color.a === undefined
                                    ? 1
                                    : color.a,
                            position: position
                        });
                    }
                });

                return stops;
            },

            splitTopLevel(value, delimiter) {
                var result = [];
                var current = '';
                var depth = 0;

                for (var i = 0; i < value.length; i++) {
                    var char = value[i];

                    if (char === '(') {
                        depth++;
                    }

                    if (char === ')') {
                        depth--;
                    }

                    if (
                        char === delimiter &&
                        depth === 0
                    ) {
                        result.push(current);
                        current = '';
                    } else {
                        current += char;
                    }
                }

                if (current) {
                    result.push(current);
                }

                return result;
            },

            shareConfiguration() {
                try {
                    var json = JSON.stringify(
                        this.shareState
                    );

                    var encoded =
                        btoa(
                            encodeURIComponent(json)
                                .replace(
                                    /%([0-9A-F]{2})/g,
                                    function (match, p1) {
                                        return String.fromCharCode(
                                            parseInt(p1, 16)
                                        );
                                    }
                                )
                        )
                        .replace(/\+/g, '-')
                        .replace(/\//g, '_')
                        .replace(/=+$/, '');

                    var url =
                        window.location.origin +
                        window.location.pathname +
                        '#gradient=' +
                        encoded;

                    this.copyText(url).then(() => {
                        this.showToast(
                            'Shareable configuration copied'
                        );
                    });
                } catch (error) {
                    this.showToast(
                        'Could not create share link'
                    );
                }
            },

            loadSharedState() {
                var hash = window.location.hash || '';

                if (hash.indexOf('#gradient=') !== 0) {
                    return;
                }

                try {
                    var encoded =
                        hash.substring(10)
                            .replace(/-/g, '+')
                            .replace(/_/g, '/');

                    while (encoded.length % 4) {
                        encoded += '=';
                    }

                    var binary = atob(encoded);

                    var decoded = decodeURIComponent(
                        binary
                            .split('')
                            .map(function (char) {
                                return '%' +
                                    char.charCodeAt(0)
                                        .toString(16)
                                        .padStart(2, '0');
                            })
                            .join('')
                    );

                    var state = JSON.parse(decoded);

                    if (
                        state &&
                        Array.isArray(state.layers) &&
                        state.layers.length
                    ) {
                        this.layers = state.layers;
                        this.selectedLayer =
                            Number(state.selectedLayer) || 0;

                        this.overlayEnabled =
                            state.overlayEnabled !== false;

                        this.overlayText =
                            state.overlayText ||
                            this.overlayText;

                        this.overlayTextColor =
                            state.overlayTextColor ||
                            this.overlayTextColor;

                        this.contrastForeground =
                            state.contrastForeground ||
                            this.contrastForeground;
                    }
                } catch (error) {
                }
            },

            pushHistory() {
                var snapshot = this.serializeState();

                if (
                    this.historyIndex >= 0 &&
                    this.history[this.historyIndex] === snapshot
                ) {
                    return;
                }

                this.history =
                    this.history.slice(
                        0,
                        this.historyIndex + 1
                    );

                this.history.push(snapshot);

                if (this.history.length > 50) {
                    this.history.shift();
                }

                this.historyIndex =
                    this.history.length - 1;

                this.saveSettings();
            },

            commit() {
                this.normalizeAll();
                this.pushHistory();
                this.calculateContrast();
            },

            commitDebounced() {
                this.normalizeAll();

                clearTimeout(this.historyTimer);

                this.historyTimer = setTimeout(() => {
                    this.pushHistory();
                    this.calculateContrast();
                }, 180);
            },

            undo() {
                if (this.historyIndex <= 0) {
                    return;
                }

                this.historyIndex--;

                this.restoreState(
                    this.history[this.historyIndex]
                );

                this.calculateContrast();
            },

            redo() {
                if (
                    this.historyIndex >=
                    this.history.length - 1
                ) {
                    return;
                }

                this.historyIndex++;

                this.restoreState(
                    this.history[this.historyIndex]
                );

                this.calculateContrast();
            },

            serializeState() {
                return JSON.stringify({
                    layers: this.layers,
                    selectedLayer: this.selectedLayer,
                    overlayEnabled: this.overlayEnabled,
                    overlayText: this.overlayText,
                    overlayTextColor: this.overlayTextColor,
                    contrastForeground: this.contrastForeground
                });
            },

            restoreState(snapshot) {
                try {
                    var state = JSON.parse(snapshot);

                    this.layers =
                        Array.isArray(state.layers)
                            ? state.layers
                            : [this.makeLayer('linear')];

                    this.selectedLayer =
                        Number(state.selectedLayer) || 0;

                    this.overlayEnabled =
                        state.overlayEnabled !== false;

                    this.overlayText =
                        state.overlayText ||
                        'Beautiful Gradient';

                    this.overlayTextColor =
                        state.overlayTextColor ||
                        '#FFFFFF';

                    this.contrastForeground =
                        state.contrastForeground ||
                        '#FFFFFF';
                } catch (error) {
                }
            },

            normalizeAll() {
                this.layers.forEach((layer) => {
                    layer.angle =
                        this.clamp(
                            Number(layer.angle) || 0,
                            0,
                            360
                        );

                    layer.opacity =
                        this.clamp(
                            Number(layer.opacity) || 0,
                            0,
                            1
                        );

                    layer.stops.forEach((stop) => {
                        var parsed =
                            this.parseColor(stop.color);

                        if (parsed) {
                            stop.color =
                                this.rgbToHex(
                                    parsed.r,
                                    parsed.g,
                                    parsed.b
                                );

                            if (
                                stop.alpha === undefined ||
                                stop.alpha === null
                            ) {
                                stop.alpha =
                                    parsed.a === undefined
                                        ? 1
                                        : parsed.a;
                            }
                        } else {
                            stop.color = '#000000';
                        }

                        stop.position =
                            this.clamp(
                                Number(stop.position) || 0,
                                0,
                                100
                            );

                        stop.alpha =
                            this.clamp(
                                Number(stop.alpha),
                                0,
                                1
                            );
                    });
                });
            },

            saveSettings() {
                try {
                    localStorage.setItem(
                        'aabi.css-gradient.settings',
                        this.serializeState()
                    );
                } catch (error) {
                }
            },

            loadSettings() {
                try {
                    var saved =
                        localStorage.getItem(
                            'aabi.css-gradient.settings'
                        );

                    if (!saved) {
                        return;
                    }

                    this.restoreState(saved);
                } catch (error) {
                }
            },

            handleKeyboard(event) {
                if (
                    (event.ctrlKey || event.metaKey) &&
                    event.key.toLowerCase() === 'z'
                ) {
                    event.preventDefault();

                    if (event.shiftKey) {
                        this.redo();
                    } else {
                        this.undo();
                    }

                    return;
                }

                if (
                    (event.ctrlKey || event.metaKey) &&
                    event.key.toLowerCase() === 'y'
                ) {
                    event.preventDefault();
                    this.redo();
                    return;
                }

                if (
                    (event.ctrlKey || event.metaKey) &&
                    event.key.toLowerCase() === 's'
                ) {
                    event.preventDefault();
                    this.copyOutput();
                }

                if (
                    event.key === 'Delete' &&
                    this.draggingStopId
                ) {
                    this.deleteStopById(
                        this.draggingStopId
                    );
                }
            },

            deleteStopById(id) {
                if (
                    !this.currentLayer ||
                    this.currentLayer.stops.length <= 2
                ) {
                    return;
                }

                var index =
                    this.currentLayer.stops.findIndex(
                        function (stop) {
                            return stop.id === id;
                        }
                    );

                if (index >= 0) {
                    this.currentLayer.stops.splice(
                        index,
                        1
                    );

                    this.draggingStopId = null;
                    this.commit();
                }
            },

            showToast(message) {
                this.toast = message;

                clearTimeout(this.toastTimer);

                this.toastTimer = setTimeout(() => {
                    this.toast = '';
                }, 1800);
            },

            randomHex() {
                return this.rgbToHex(
                    Math.floor(Math.random() * 256),
                    Math.floor(Math.random() * 256),
                    Math.floor(Math.random() * 256)
                );
            },

            uid() {
                return Date.now().toString(36) +
                    Math.random()
                        .toString(36)
                        .substring(2, 9);
            },

            cleanNumber(value) {
                var number = Number(value);

                if (!Number.isFinite(number)) {
                    return '0';
                }

                return String(
                    Math.round(number * 1000) / 1000
                );
            },

            clamp(value, min, max) {
                var number = Number(value);

                if (!Number.isFinite(number)) {
                    number = min;
                }

                return Math.min(
                    Math.max(number, min),
                    max
                );
            }
        };
    };
</script>
@endscript