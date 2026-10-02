<?php

use Livewire\Component;

new class extends Component
{
    //
};

?>

<div
    x-data="aabiAgeCalculator()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    {{-- Workspace --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/70 p-4 sm:p-5">
            <div class="flex flex-wrap items-center gap-2">
                <template x-for="item in modes" :key="item.id">
                    <button type="button"
                        data-active-group="age-mode"
                        :data-active-value="item.id"
                        :class="{ 'is-active': mode === item.id }"
                        :aria-pressed="mode === item.id"
                        @click="setMode(item.id)"
                        class="compact-tab border border-slate-200 bg-white">
                        <span x-text="item.label"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            {{-- Exact Age / Age on Date --}}
            <template x-if="mode === 'exact' || mode === 'on_date'">
                <div class="space-y-5">
                    <div class="grid gap-5 lg:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 sm:p-5">
                            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900"
                                        x-text="mode === 'exact' ? 'Date of birth' : 'Calculate age on any date'"></h2>
                                    <p class="mt-1 text-xs text-slate-500">All calculations run locally in your browser.</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="setToday()"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-indigo-300 hover:text-indigo-700">
                                        Today
                                    </button>
                                    <button type="button" @click="loadExample()"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-indigo-300 hover:text-indigo-700">
                                        Example
                                    </button>
                                    <button type="button" @click="reset()"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-rose-300 hover:text-rose-700">
                                        Reset
                                    </button>
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-600">Date of birth</span>
                                    <input type="date" x-model="birthDate" @input="calculate()"
                                        class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                                    <span class="mt-1 block text-xs text-slate-500" x-text="birthDate ? formatDate(birthDate) : 'Enter your birth date'"></span>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-600">Age as of</span>
                                    <input type="date" x-model="asOfDate" :min="birthDate || null" @input="calculate()"
                                        class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                                    <span class="mt-1 block text-xs text-slate-500" x-text="asOfDate ? formatDate(asOfDate) : 'Today'"></span>
                                </label>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <button type="button" @click="setAsOfToday()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:border-indigo-300">Today</button>
                                <button type="button" @click="setAsOfNextBirthday()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:border-indigo-300">Next birthday</button>
                                <button type="button" @click="setAsOfYearEnd()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:border-indigo-300">Year end</button>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold text-slate-600">Birth time (optional)</span>
                                    <input type="time" step="1" x-model="birthTime" @input="calculate()"
                                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold">
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold text-slate-600">Language</span>
                                    <select x-model="language" @change="calculate()"
                                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold">
                                        <option value="en">English</option>
                                        <option value="ur">اردو</option>
                                    </select>
                                </label>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold text-slate-600">Calendar display</span>
                                    <select x-model="calendarDisplay" @change="calculate()"
                                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold">
                                        <option value="gregorian">Gregorian</option>
                                        <option value="islamic">Hijri (display)</option>
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold text-slate-600">Date format</span>
                                    <select x-model="dateFormat" @change="calculate()"
                                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold">
                                        <option value="long">Long date</option>
                                        <option value="short">Short date</option>
                                        <option value="iso">ISO (YYYY-MM-DD)</option>
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold text-slate-600">Leap-day birthday convention</span>
                                    <select x-model="leapConvention" @change="calculate()"
                                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold">
                                        <option value="feb28">February 28</option>
                                        <option value="mar1">March 1</option>
                                    </select>
                                </label>
                            </div>

                            <p x-show="error" x-text="error" class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700"></p>
                        </div>

                        <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-indigo-600"
                                       x-text="mode === 'exact' ? 'Exact age' : 'Age on selected date'"></p>
                                    <p class="mt-2 text-2xl font-black tracking-tight text-slate-900" x-text="result.ageLabel || '—'"></p>
                                </div>
                                <span class="rounded-lg bg-white px-2.5 py-1 text-[11px] font-bold text-indigo-700"
                                      x-text="result.direction || '—'"></span>
                            </div>

                            <div class="mt-5 grid grid-cols-3 gap-2">
                                <div class="rounded-xl bg-white p-3 text-center shadow-sm"><p class="text-xl font-black" x-text="result.years"></p><p class="text-[11px] font-semibold text-slate-500">Years</p></div>
                                <div class="rounded-xl bg-white p-3 text-center shadow-sm"><p class="text-xl font-black" x-text="result.months"></p><p class="text-[11px] font-semibold text-slate-500">Months</p></div>
                                <div class="rounded-xl bg-white p-3 text-center shadow-sm"><p class="text-xl font-black" x-text="result.days"></p><p class="text-[11px] font-semibold text-slate-500">Days</p></div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <div class="rounded-xl bg-white p-3"><p class="text-lg font-black" x-text="fmt(result.totalDays)"></p><p class="text-[11px] text-slate-500">Total days</p></div>
                                <div class="rounded-xl bg-white p-3"><p class="text-lg font-black" x-text="fmt(result.totalMonths)"></p><p class="text-[11px] text-slate-500">Calendar months</p></div>
                            </div>

                            <div class="mt-4 rounded-xl border border-white bg-white p-3">
                                <p class="text-xs font-bold text-slate-500">Calculation</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800" x-text="result.formula || '—'"></p>
                                <p class="mt-1 text-xs leading-5 text-slate-500" x-text="result.steps || 'Enter a valid date of birth.'"></p>
                            </div>

                            <button type="button" @click="copySummary()"
                                :class="{ 'is-active': copied }"
                                class="mt-4 w-full rounded-xl border border-indigo-200 bg-white px-4 py-3 text-sm font-bold text-indigo-700">
                                <span x-text="copied ? '✓ Copied to clipboard' : 'Copy age summary'"></span>
                            </button>
                            <button type="button" @click="copyShareLink()"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 hover:border-indigo-300 hover:text-indigo-700">
                                Copy shareable link
                            </button>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <button type="button" @click="printResult()" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-600 hover:border-indigo-300 hover:text-indigo-700">Print</button>
                                <button type="button" @click="downloadResult()" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-600 hover:border-indigo-300 hover:text-indigo-700">Download</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-[11px] font-bold uppercase text-slate-500">Born</p><p class="mt-2 font-black text-slate-900" x-text="result.birthDateLabel || '—'"></p><p class="mt-1 text-xs text-slate-500" x-text="result.birthWeekday || '—'"></p></div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-[11px] font-bold uppercase text-slate-500">Decimal age</p><p class="mt-2 font-black text-slate-900" x-text="result.decimalAge || '—'"></p><p class="mt-1 text-xs text-slate-500">Approximate year value</p></div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-[11px] font-bold uppercase text-slate-500">Birth weekday</p><p class="mt-2 font-black text-slate-900" x-text="result.birthWeekday || '—'"></p><p class="mt-1 text-xs text-slate-500" x-text="result.targetWeekday || '—'"></p></div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-[11px] font-bold uppercase text-slate-500">Elapsed time</p><p class="mt-2 font-black text-slate-900" x-text="result.preciseAge || '—'"></p><p class="mt-1 text-xs text-slate-500">Based on optional birth time</p></div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <div class="flex items-center justify-between gap-3">
                                <div><p class="text-xs font-bold uppercase text-indigo-600">Next birthday</p><p class="mt-1 text-xl font-black" x-text="result.nextBirthdayLabel || '—'"></p></div>
                                <div class="rounded-xl bg-indigo-50 px-3 py-2 text-center"><p class="text-xl font-black text-indigo-700" x-text="result.nextBirthdayAge ?? '—'"></p><p class="text-[10px] font-bold text-indigo-600">Turning</p></div>
                            </div>
                            <p class="mt-2 text-sm text-slate-500" x-text="result.nextBirthdayCountdown || '—'"></p>
                            <div class="mt-4 rounded-xl bg-slate-50 p-3 text-xs text-slate-600" x-text="result.halfBirthdayLabel ? 'Half birthday: ' + result.halfBirthdayLabel : ''"></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-5">
                            <p class="text-xs font-bold uppercase text-slate-500">Last birthday</p>
                            <p class="mt-2 text-xl font-black" x-text="result.lastBirthdayLabel || '—'"></p>
                            <p class="mt-1 text-sm text-slate-500" x-text="result.lastBirthdayAge !== null ? 'Age: ' + result.lastBirthdayAge : '—'"></p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <div><h2 class="text-base font-extrabold">Age in other units</h2><p class="mt-1 text-xs text-slate-500">Elapsed time from the selected dates.</p></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                            <template x-for="item in [
                                ['Months', result.totalMonths],
                                ['Weeks', result.totalWeeks],
                                ['Days', result.totalDays],
                                ['Hours', result.totalHours],
                                ['Minutes', result.totalMinutes],
                                ['Seconds', result.totalSeconds]
                            ]" :key="item[0]">
                                <div class="rounded-xl bg-slate-50 p-3"><p class="text-lg font-black" x-text="fmt(item[1])"></p><p class="text-xs text-slate-500" x-text="item[0]"></p></div>
                            </template>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div><h2 class="text-base font-extrabold">Milestone timeline</h2><p class="mt-1 text-xs text-slate-500">Upcoming age and day-count milestones.</p></div>
                            <button type="button" @click="addCustomMilestone()" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold hover:border-indigo-300">Add milestone</button>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                            <template x-for="item in milestones" :key="item.id">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="text-sm font-black" x-text="item.label"></p>
                                    <p class="mt-1 text-xs text-slate-500" x-text="item.date ? formatDateObject(item.date) : item.message"></p>
                                    <p class="mt-1 text-xs font-semibold text-indigo-600" x-text="item.remaining !== null ? fmt(item.remaining) + ' days' : ''"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Difference --}}
            <template x-if="mode === 'difference'">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                        <h2 class="text-base font-extrabold">Age difference between two people</h2>
                        <p class="mt-1 text-xs text-slate-500">Enter two birthdays to compare their exact calendar ages.</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Person A</span><input type="date" x-model="personA" @input="calculateDifference()" class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3 text-sm font-semibold"></label>
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Person B</span><input type="date" x-model="personB" @input="calculateDifference()" class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3 text-sm font-semibold"></label>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button" @click="loadDifferenceExample()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold">Example</button>
                            <button type="button" @click="reset()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold">Reset</button>
                        </div>
                        <p x-show="error" x-text="error" class="mt-4 rounded-xl bg-rose-50 p-3 text-xs font-semibold text-rose-700"></p>
                    </div>
                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5">
                        <p class="text-xs font-bold uppercase text-indigo-600">Exact age gap</p>
                        <p class="mt-2 text-3xl font-black" x-text="difference.label || '—'"></p>
                        <div class="mt-5 grid grid-cols-3 gap-2">
                            <div class="rounded-xl bg-white p-3"><p class="text-xl font-black" x-text="difference.years"></p><p class="text-[11px] text-slate-500">Years</p></div>
                            <div class="rounded-xl bg-white p-3"><p class="text-xl font-black" x-text="difference.months"></p><p class="text-[11px] text-slate-500">Months</p></div>
                            <div class="rounded-xl bg-white p-3"><p class="text-xl font-black" x-text="difference.days"></p><p class="text-[11px] text-slate-500">Days</p></div>
                        </div>
                        <div class="mt-4 rounded-xl bg-white p-3 text-sm font-semibold" x-text="difference.detail || '—'"></div>
                        <button type="button" @click="copyDifference()" class="mt-4 w-full rounded-xl border border-indigo-200 bg-white px-4 py-3 text-sm font-bold text-indigo-700">
                            <span x-text="copied ? '✓ Copied to clipboard' : 'Copy comparison'"></span>
                        </button>
                    </div>
                </div>
            </template>

            {{-- Date Difference --}}
            <template x-if="mode === 'date_difference'">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                        <h2 class="text-base font-extrabold">Date difference</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Start date</span><input type="date" x-model="dateStart" @input="calculateDateDifference()" class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3"></label>
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">End date</span><input type="date" x-model="dateEnd" @input="calculateDateDifference()" class="h-11 w-full rounded-xl border-2 border-slate-200 bg-white px-3"></label>
                        </div>
                        <div class="mt-4 flex gap-2"><button type="button" @click="loadDateExample()" class="rounded-lg border px-3 py-2 text-xs font-bold">Example</button><button type="button" @click="reset()" class="rounded-lg border px-3 py-2 text-xs font-bold">Reset</button></div>
                    </div>
                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5">
                        <p class="text-xs font-bold uppercase text-indigo-600">Calendar duration</p>
                        <p class="mt-2 text-3xl font-black" x-text="dateDifference.label || '—'"></p>
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <div class="rounded-xl bg-white p-3"><p class="font-black" x-text="fmt(dateDifference.totalDays)"></p><p class="text-[11px] text-slate-500">Days</p></div>
                            <div class="rounded-xl bg-white p-3"><p class="font-black" x-text="fmt(dateDifference.totalWeeks)"></p><p class="text-[11px] text-slate-500">Weeks</p></div>
                            <div class="rounded-xl bg-white p-3"><p class="font-black" x-text="fmt(dateDifference.totalMonths)"></p><p class="text-[11px] text-slate-500">Months</p></div>
                        </div>
                        <button type="button" @click="copyText(dateDifference.summary)" class="mt-4 w-full rounded-xl border bg-white px-4 py-3 text-sm font-bold text-indigo-700">Copy result</button>
                    </div>
                </div>
            </template>

            {{-- DOB Finder --}}
            <template x-if="mode === 'dob_finder'">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                        <h2 class="text-base font-extrabold">Date of birth finder</h2>
                        <p class="mt-1 text-xs text-slate-500">Find the DOB that corresponds to an exact age on a reference date.</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Target age — years</span><input type="number" min="0" step="1" x-model.number="finderYears" @input="calculateDobFinder()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Months</span><input type="number" min="0" max="11" step="1" x-model.number="finderMonths" @input="calculateDobFinder()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Days</span><input type="number" min="0" max="31" step="1" x-model.number="finderDays" @input="calculateDobFinder()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                            <label><span class="mb-2 block text-xs font-bold text-slate-600">Reference date</span><input type="date" x-model="finderReference" @input="calculateDobFinder()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5">
                        <p class="text-xs font-bold uppercase text-indigo-600">Calculated birth date</p>
                        <p class="mt-2 text-3xl font-black" x-text="dobFinder.label || '—'"></p>
                        <p class="mt-2 text-sm text-slate-600" x-text="dobFinder.weekday || ''"></p>
                        <div class="mt-4 rounded-xl bg-white p-4 text-sm text-slate-600" x-text="dobFinder.explanation || 'Enter an age and reference date.'"></div>
                        <button type="button" @click="copyText(dobFinder.summary)" class="mt-4 w-full rounded-xl border bg-white px-4 py-3 text-sm font-bold text-indigo-700">Copy DOB result</button>
                    </div>
                </div>
            </template>

            {{-- Eligibility --}}
            <template x-if="mode === 'eligibility'">
                <div class="space-y-5">
                    <div class="grid gap-5 lg:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                            <h2 class="text-base font-extrabold">Age eligibility checker</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <label><span class="mb-2 block text-xs font-bold text-slate-600">Date of birth</span><input type="date" x-model="eligibilityDob" @input="calculateEligibility()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                                <label><span class="mb-2 block text-xs font-bold text-slate-600">Cutoff date</span><input type="date" x-model="eligibilityDate" @input="calculateEligibility()" class="h-11 w-full rounded-xl border-2 border-slate-200 px-3"></label>
                                <label><span class="mb-2 block text-xs font-bold text-slate-600">Minimum age</span><input type="number" min="0" step="1" x-model.number="minAge" @input="calculateEligibility()" class="h-11 rounded-xl border-2 border-slate-200 px-3"></label>
                                <label><span class="mb-2 block text-xs font-bold text-slate-600">Maximum age</span><input type="number" min="0" step="1" x-model.number="maxAge" @input="calculateEligibility()" class="h-11 rounded-xl border-2 border-slate-200 px-3"></label>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <template x-for="preset in eligibilityPresets" :key="preset.id">
                                    <button type="button" @click="applyEligibilityPreset(preset)" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold hover:border-indigo-300" x-text="preset.label"></button>
                                </template>
                            </div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label><span class="mb-2 block text-xs font-bold">Minimum cutoff</span><select x-model="minInclusive" @change="calculateEligibility()" class="h-10 w-full rounded-xl border border-slate-200 bg-white"><option :value="true">Inclusive</option><option :value="false">Exclusive</option></select></label>
                                <label><span class="mb-2 block text-xs font-bold">Maximum cutoff</span><select x-model="maxInclusive" @change="calculateEligibility()" class="h-10 w-full rounded-xl border border-slate-200 bg-white"><option :value="true">Inclusive</option><option :value="false">Exclusive</option></select></label>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5">
                            <p class="text-xs font-bold uppercase text-indigo-600">Eligibility result</p>
                            <p class="mt-2 text-2xl font-black" x-text="eligibility.status || '—'"></p>
                            <p class="mt-2 text-sm text-slate-600" x-text="eligibility.explanation || ''"></p>
                            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                <div class="rounded-xl bg-white p-3"><p class="text-[11px] text-slate-500">Earliest qualifying DOB</p><p class="mt-1 text-sm font-black" x-text="eligibility.earliest || '—'"></p></div>
                                <div class="rounded-xl bg-white p-3"><p class="text-[11px] text-slate-500">Latest qualifying DOB</p><p class="mt-1 text-sm font-black" x-text="eligibility.latest || '—'"></p></div>
                            </div>
                            <button type="button" @click="copyText(eligibility.summary)" class="mt-4 w-full rounded-xl border bg-white px-4 py-3 text-sm font-bold text-indigo-700">Copy eligibility result</button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Multi-person --}}
            <template x-if="mode === 'multi'">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div><h2 class="text-base font-extrabold">Multi-person age comparison</h2><p class="mt-1 text-xs text-slate-500">Compare several birthdays against today.</p></div>
                        <button type="button" @click="addPerson()" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold hover:border-indigo-300">Add person</button>
                    </div>
                    <div class="space-y-2">
                        <template x-for="(person,index) in people" :key="person.id">
                            <div class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]">
                                <input type="text" x-model="person.name" @input="calculatePeople()" placeholder="Person name" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
                                <input type="date" x-model="person.dob" @input="calculatePeople()" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
                                <button type="button" @click="removePerson(index)" class="rounded-xl border border-slate-200 px-3 text-xs font-bold text-rose-600">Remove</button>
                            </div>
                        </template>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        <template x-for="item in peopleResults" :key="item.id">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4"><p class="font-black" x-text="item.name"></p><p class="mt-1 text-sm text-indigo-700" x-text="item.age"></p><p class="mt-1 text-xs text-slate-500" x-text="item.weekday"></p></div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-4">
        <div class="flex gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">🔒</div>
            <div>
                <p class="text-sm font-extrabold text-emerald-900">Privacy-first calculation</p>
                <p class="mt-1 text-xs leading-5 text-emerald-800">
                    Dates are calculated in your browser. AabiTech does not send birth dates to the server.
                    Birth dates are not saved in localStorage. Share links use only an optional URL fragment and are never submitted to the application.
                </p>
            </div>
        </div>
    </section>

    <div x-show="toast" x-transition class="fixed bottom-5 right-5 z-50 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-lg" x-text="toast"></div>
</div>

@script
<script>
window.aabiAgeCalculator = function () {
    return {
        mode: 'exact',
        modes: [
            { id: 'exact', label: 'Exact Age' },
            { id: 'on_date', label: 'Age on Date' },
            { id: 'difference', label: 'Age Difference' },
            { id: 'date_difference', label: 'Date Difference' },
            { id: 'dob_finder', label: 'DOB Finder' },
            { id: 'eligibility', label: 'Eligibility' },
            { id: 'multi', label: 'Multi-Person' }
        ],
        today: '',
        birthDate: '',
        asOfDate: '',
        birthTime: '',
        leapConvention: 'feb28',
        language: 'en',
        calendarDisplay: 'gregorian',
        dateFormat: 'long',
        error: '',
        countdownTimer: null,
        copied: false,
        toast: '',
        result: {},
        milestones: [],
        personA: '',
        personB: '',
        difference: {},
        dateStart: '',
        dateEnd: '',
        dateDifference: {},
        finderYears: 0,
        finderMonths: 0,
        finderDays: 0,
        finderReference: '',
        dobFinder: {},
        eligibilityDob: '',
        eligibilityDate: '',
        minAge: 18,
        maxAge: 60,
        minInclusive: true,
        maxInclusive: true,
        eligibility: {},
        people: [
            { id: 1, name: 'Person 1', dob: '' },
            { id: 2, name: 'Person 2', dob: '' }
        ],
        peopleResults: [],
        eligibilityPresets: [
            { id: 'admission18', label: 'Admission — 18+', min: 18, max: null },
            { id: 'job18_30', label: 'Job — 18 to 30', min: 18, max: 30 },
            { id: 'licence18', label: 'Driving — 18+', min: 18, max: null }
        ],

        init() {
            this.today = this.todayISO();
            this.asOfDate = this.today;
            this.finderReference = this.today;
            this.eligibilityDate = this.today;
            this.dateEnd = this.today;
            this.applyShareState();
            this.calculate();
            this.startCountdownTimer();
        },

        startCountdownTimer() {
            if (this.countdownTimer) clearInterval(this.countdownTimer);
            this.countdownTimer = setInterval(() => {
                if (this.birthDate && (this.mode === 'exact' || this.mode === 'on_date')) this.calculateAge();
            }, 1000);
        },

        destroy() {
            if (this.countdownTimer) clearInterval(this.countdownTimer);
        },

        setMode(mode) {
            this.mode = mode;
            this.error = '';
            this.calculate();
        },

        todayISO() {
            const d = new Date();
            return this.toISO(d);
        },

        parseISO(value) {
            if (!value || !/^(\d{4})-(\d{2})-(\d{2})$/.test(value)) return null;
            const [y,m,d] = value.split('-').map(Number);
            const date = new Date(y, m - 1, d);
            if (date.getFullYear() !== y || date.getMonth() !== m - 1 || date.getDate() !== d) return null;
            return date;
        },

        toISO(date) {
            return [
                date.getFullYear(),
                String(date.getMonth() + 1).padStart(2,'0'),
                String(date.getDate()).padStart(2,'0')
            ].join('-');
        },

        utcDayNumber(date) {
            return Math.floor(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) / 86400000);
        },

        daysBetween(a,b) {
            return this.utcDayNumber(b) - this.utcDayNumber(a);
        },

        isLeapYear(year) {
            return year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0);
        },

        daysInMonth(year, monthIndex) {
            return new Date(year, monthIndex + 1, 0).getDate();
        },

        clampDay(year, monthIndex, day) {
            return Math.min(day, this.daysInMonth(year, monthIndex));
        },

        addMonthsClamped(date, months) {
            const index = date.getFullYear() * 12 + date.getMonth() + months;
            const year = Math.floor(index / 12);
            const month = ((index % 12) + 12) % 12;
            return new Date(year, month, this.clampDay(year, month, date.getDate()));
        },

        calendarDifference(start, end) {
            if (!start || !end || end < start) return null;
            let totalMonths = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
            let anchor = this.addMonthsClamped(start, totalMonths);
            if (anchor > end) {
                totalMonths--;
                anchor = this.addMonthsClamped(start, totalMonths);
            }
            const years = Math.floor(totalMonths / 12);
            const months = totalMonths % 12;
            const days = this.daysBetween(anchor, end);
            return { years, months, days, totalMonths, totalDays: this.daysBetween(start,end) };
        },

        birthdayForYear(birth, year) {
            if (birth.getMonth() === 1 && birth.getDate() === 29 && !this.isLeapYear(year)) {
                return this.leapConvention === 'mar1' ? new Date(year, 2, 1) : new Date(year, 1, 28);
            }
            return new Date(year, birth.getMonth(), birth.getDate());
        },

        findNextBirthday(birth, reference) {
            let year = reference.getFullYear();
            let date = this.birthdayForYear(birth, year);
            if (date < reference) {
                year++;
                date = this.birthdayForYear(birth, year);
            }
            return { date, days: this.daysBetween(reference,date), age: year - birth.getFullYear() };
        },

        findLastBirthday(birth, reference) {
            let year = reference.getFullYear();
            let date = this.birthdayForYear(birth, year);
            if (date > reference) {
                year--;
                date = this.birthdayForYear(birth, year);
            }
            return { date, age: year - birth.getFullYear() };
        },

        calculate() {
            if (this.mode === 'difference') return this.calculateDifference();
            if (this.mode === 'date_difference') return this.calculateDateDifference();
            if (this.mode === 'dob_finder') return this.calculateDobFinder();
            if (this.mode === 'eligibility') return this.calculateEligibility();
            if (this.mode === 'multi') return this.calculatePeople();
            this.calculateAge();
        },

        calculateAge() {
            this.error = '';
            this.result = {};
            if (!this.birthDate) return;
            const birth = this.parseISO(this.birthDate);
            const asOf = this.parseISO(this.asOfDate || this.today);
            if (!birth || !asOf) { this.error = 'Please enter valid dates.'; return; }
            if (birth > asOf) { this.error = 'Date of birth cannot be after the reference date.'; return; }

            const diff = this.calendarDifference(birth, asOf);
            const next = this.findNextBirthday(birth, asOf);
            const last = this.findLastBirthday(birth, asOf);
            const totalDays = diff.totalDays;
            const hasTime = Boolean(this.birthTime);
            let totalSeconds = totalDays * 86400;
            if (hasTime) {
                const parts = this.birthTime.split(':').map(Number);
                const birthSeconds = (parts[0] || 0) * 3600 + (parts[1] || 0) * 60 + (parts[2] || 0);
                const target = this.asOfDate === this.today ? new Date() : new Date(asOf.getFullYear(), asOf.getMonth(), asOf.getDate());
                const targetSeconds = target.getHours() * 3600 + target.getMinutes() * 60 + target.getSeconds();
                totalSeconds = Math.max(0, totalSeconds + targetSeconds - birthSeconds);
            }
            const totalHours = Math.floor(totalSeconds / 3600);
            const totalMinutes = Math.floor(totalSeconds / 60);
            const preciseSeconds = totalSeconds % 60;
            const decimalAge = totalDays / 365.2425;
            const half = this.addMonthsClamped(birth, diff.years * 12 + 6);
            const halfDate = half > asOf ? half : this.addMonthsClamped(birth, (diff.years + 1) * 12 + 6);

            this.result = {
                years: diff.years, months: diff.months, days: diff.days,
                ageLabel: this.ageLabel(diff.years,diff.months,diff.days),
                direction: diff.totalDays === 0 ? 'Today' : 'Elapsed',
                birthDateLabel: this.formatDate(this.birthDate),
                birthWeekday: this.weekday(birth),
                targetWeekday: this.weekday(asOf),
                totalMonths: diff.totalMonths,
                totalWeeks: Math.floor(totalDays / 7),
                totalDays,
                totalHours,
                totalMinutes,
                totalSeconds: Math.floor(totalSeconds),
                decimalAge: decimalAge.toFixed(6),
                preciseAge: hasTime ? this.preciseDuration(totalSeconds) : this.fmt(totalDays * 24) + ' hours',
                nextBirthdayLabel: this.formatDateObject(next.date),
                nextBirthdayCountdown: this.liveBirthdayCountdown(next.date),
                nextBirthdayAge: next.age,
                lastBirthdayLabel: this.formatDateObject(last.date),
                lastBirthdayAge: last.age,
                halfBirthdayLabel: this.formatDateObject(halfDate),
                formula: this.formatDate(this.birthDate) + ' → ' + this.formatDate(this.asOfDate),
                steps: 'Subtract complete calendar years, then complete months, then remaining days. Month lengths and leap years are respected.'
            };
            this.buildMilestones(birth, asOf);
        },

        calculateDifference() {
            this.error = '';
            if (!this.personA || !this.personB) { this.difference = {}; return; }
            const a = this.parseISO(this.personA), b = this.parseISO(this.personB);
            if (!a || !b) { this.error = 'Please enter two valid birthdays.'; return; }
            const older = a <= b ? a : b, younger = a <= b ? b : a;
            const d = this.calendarDifference(older,younger);
            this.difference = {
                years:d.years, months:d.months, days:d.days,
                label:this.ageLabel(d.years,d.months,d.days),
                detail:(a <= b ? 'Person A is older by ' : 'Person B is older by ') + this.ageLabel(d.years,d.months,d.days),
                totalDays:d.totalDays
            };
        },

        calculateDateDifference() {
            this.error = '';
            if (!this.dateStart || !this.dateEnd) { this.dateDifference = {}; return; }
            const a=this.parseISO(this.dateStart), b=this.parseISO(this.dateEnd);
            if (!a || !b) { this.error='Please enter two valid dates.'; return; }
            const start=a<=b?a:b, end=a<=b?b:a, d=this.calendarDifference(start,end);
            this.dateDifference = {
                years:d.years, months:d.months, days:d.days, totalDays:d.totalDays,
                totalWeeks:Math.floor(d.totalDays/7), totalMonths:d.totalMonths,
                label:this.ageLabel(d.years,d.months,d.days),
                summary:'Date difference: '+this.ageLabel(d.years,d.months,d.days)+'\\nTotal days: '+this.fmt(d.totalDays)+'\\nTotal weeks: '+this.fmt(Math.floor(d.totalDays/7))
            };
        },

        calculateDobFinder() {
            this.error='';
            const ref=this.parseISO(this.finderReference);
            if (!ref) { this.dobFinder={}; return; }
            const y=Math.max(0,Number(this.finderYears)||0), m=Math.max(0,Number(this.finderMonths)||0), d=Math.max(0,Number(this.finderDays)||0);
            let date=this.addMonthsClamped(ref,-(y*12+m));
            date.setDate(date.getDate()-d);
            this.dobFinder={
                label:this.formatDateObject(date), weekday:this.weekday(date),
                explanation:'A target age of '+y+' years, '+m+' months and '+d+' days is subtracted from the reference date using calendar-aware month lengths.',
                summary:'Date of birth: '+this.formatDateObject(date)+'\\nTarget age: '+y+' years, '+m+' months, '+d+' days\\nReference date: '+this.formatDate(this.finderReference)
            };
        },

        calculateEligibility() {
            this.error='';
            const dob=this.parseISO(this.eligibilityDob), cutoff=this.parseISO(this.eligibilityDate);
            if (!cutoff) { this.eligibility={}; return; }
            const min=Math.max(0,Number(this.minAge)||0), max=this.maxAge === '' || this.maxAge === null ? null : Math.max(0,Number(this.maxAge)||0);
            let status='No age entered';
            let explanation='';
            if (dob) {
                const age=this.calendarDifference(dob,cutoff);
                if (!age) { this.error='Invalid eligibility dates.'; return; }
                const minOk=this.minInclusive ? age.years >= min : age.years > min;
                const maxOk=max===null ? true : (this.maxInclusive ? age.years <= max : age.years < max);
                status=(minOk && maxOk) ? 'Eligible' : 'Not eligible';
                explanation='Age on cutoff: '+this.ageLabel(age.years,age.months,age.days)+'.';
            }
            const latest=this.addYearsForEligibility(cutoff, min, this.minInclusive ? 0 : -1);
            const earliest=max===null ? null : this.addYearsForEligibility(cutoff, max, this.maxInclusive ? 0 : 1);
            this.eligibility={
                status, explanation,
                earliest: earliest ? this.formatDateObject(earliest) : 'No maximum-age limit',
                latest: this.formatDateObject(latest),
                summary: status+'\\n'+explanation+'\\nEarliest qualifying DOB: '+(earliest?this.formatDateObject(earliest):'No maximum-age limit')+'\\nLatest qualifying DOB: '+this.formatDateObject(latest)
            };
        },

        addYearsForEligibility(cutoff, age, dayOffset) {
            const y=cutoff.getFullYear()-age;
            let d=new Date(y,cutoff.getMonth(),this.clampDay(y,cutoff.getMonth(),cutoff.getDate()));
            if (dayOffset) d.setDate(d.getDate()+dayOffset);
            return d;
        },

        calculatePeople() {
            this.peopleResults=this.people.map(p=>{
                if (!p.dob) return {...p,age:'—',weekday:'—'};
                const d=this.parseISO(p.dob);
                if (!d || d>new Date()) return {...p,age:'Invalid date',weekday:'—'};
                const diff=this.calendarDifference(d,this.parseISO(this.today));
                return {...p,age:this.ageLabel(diff.years,diff.months,diff.days),weekday:this.weekday(d)};
            });
        },

        buildMilestones(birth, reference) {
            const items=[];
            [1000,5000,10000].forEach(n=>{
                const date=new Date(Date.UTC(1970,0,1));
                date.setUTCDate(date.getUTCDate()+this.utcDayNumber(birth)+n);
                const local=new Date(date.getUTCFullYear(),date.getUTCMonth(),date.getUTCDate());
                items.push({id:'d'+n,label:n.toLocaleString()+' days',date:local,remaining:Math.max(0,this.daysBetween(reference,local)),message:local<reference?'Completed':''});
            });
            [1,5,10,18,21,25,30,40,50,60,65,70,80,90,100].forEach(age=>{
                const date=new Date(birth.getFullYear()+age,birth.getMonth(),this.clampDay(birth.getFullYear()+age,birth.getMonth(),birth.getDate()));
                if (date>=reference) items.push({id:'a'+age,label:'Age '+age,date,remaining:this.daysBetween(reference,date),message:''});
            });
            this.milestones=items.sort((a,b)=>a.date-b.date).slice(0,8);
        },

        addCustomMilestone() {
            const raw=window.prompt('Enter milestone days (for example 15000):');
            const n=Number(raw);
            const birth=this.parseISO(this.birthDate);
            const ref=this.parseISO(this.asOfDate||this.today);
            if (!birth || !Number.isInteger(n) || n<1) return;
            const day=new Date(Date.UTC(1970,0,1));
            day.setUTCDate(this.utcDayNumber(birth)+n);
            const date=new Date(day.getUTCFullYear(),day.getUTCMonth(),day.getUTCDate());
            this.milestones=[...this.milestones,{id:'custom-'+Date.now(),label:n.toLocaleString()+' days',date,remaining:Math.max(0,this.daysBetween(ref,date)),message:''}].sort((a,b)=>a.date-b.date);
        },

        setToday() { this.asOfDate=this.today; this.calculate(); },
        setAsOfToday() { this.asOfDate=this.today; this.calculate(); },
        setAsOfYearEnd() { const d=this.parseISO(this.today); this.asOfDate=d.getFullYear()+'-12-31'; this.calculate(); },
        setAsOfNextBirthday() {
            const b=this.parseISO(this.birthDate), r=this.parseISO(this.today);
            if (!b) return;
            this.asOfDate=this.toISO(this.findNextBirthday(b,r).date); this.calculate();
        },

        loadExample() {
            this.mode='exact'; this.birthDate='2000-06-15'; this.asOfDate=this.today; this.birthTime='09:30:00'; this.calculate();
        },
        loadDifferenceExample() {
            this.mode='difference'; this.personA='1990-04-12'; this.personB='1995-09-28'; this.calculateDifference();
        },
        loadDateExample() {
            this.mode='date_difference'; this.dateStart='2020-01-15'; this.dateEnd=this.today; this.calculateDateDifference();
        },
        applyEligibilityPreset(p) {
            this.minAge=p.min; this.maxAge=p.max; this.eligibilityDob=this.birthDate; this.eligibilityDate=this.today; this.calculateEligibility();
        },
        addPerson() { this.people.push({id:Date.now(),name:'Person '+(this.people.length+1),dob:''}); },
        removePerson(i) { if(this.people.length>2)this.people.splice(i,1); this.calculatePeople(); },

        reset() {
            this.birthDate=''; this.birthTime=''; this.asOfDate=this.today; this.personA=''; this.personB='';
            this.dateStart=''; this.dateEnd=this.today; this.finderYears=0; this.finderMonths=0; this.finderDays=0;
            this.finderReference=this.today; this.eligibilityDob=''; this.eligibilityDate=this.today; this.result={};
            this.difference={}; this.dateDifference={}; this.dobFinder={}; this.eligibility={}; this.milestones=[]; this.error='';
        },

        ageLabel(y,m,d) {
            const parts=[];
            if(y) parts.push(y+' '+(y===1?'year':'years'));
            if(m) parts.push(m+' '+(m===1?'month':'months'));
            if(d || !parts.length) parts.push(d+' '+(d===1?'day':'days'));
            return parts.join(', ');
        },

        weekday(date) { return date.toLocaleDateString(this.language==='ur'?'ur-PK':'en-US',{weekday:'long'}); },

        formatDate(value) {
            const d=this.parseISO(value); return d ? this.formatDateObject(d) : '—';
        },

        formatDateObject(date) {
            if(!date)return '—';
            if(this.calendarDisplay==='gregorian' && this.dateFormat === 'iso') return this.toISO(date);
            if(this.calendarDisplay==='gregorian' && this.dateFormat === 'short') {
                return date.toLocaleDateString(this.language==='ur'?'ur-PK':'en-US',{year:'numeric',month:'2-digit',day:'2-digit'});
            }
            if(this.calendarDisplay==='islamic') {
                try { return new Intl.DateTimeFormat(this.language==='ur'?'ur-PK':'en-US-u-ca-islamic',{day:'numeric',month:'long',year:'numeric'}).format(date); } catch(e) {}
            }
            return date.toLocaleDateString(this.language==='ur'?'ur-PK':'en-US',{day:'numeric',month:'long',year:'numeric'});
        },

        fmt(value) {
            if(value===null || value===undefined || !Number.isFinite(Number(value))) return '0';
            return new Intl.NumberFormat(this.language==='ur'?'ur-PK':'en-US',{maximumFractionDigits:0}).format(Number(value));
        },

        preciseDuration(seconds) {
            seconds=Math.max(0,Math.floor(seconds));
            const days=Math.floor(seconds/86400); seconds%=86400;
            const hours=Math.floor(seconds/3600); seconds%=3600;
            const minutes=Math.floor(seconds/60); seconds%=60;
            return this.fmt(days)+' days, '+hours+' hours, '+minutes+' minutes, '+seconds+' seconds';
        },

        liveBirthdayCountdown(date) {
            const target = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0);
            const now = new Date();
            if (target < now) return 'Today is the birthday.';
            const seconds = Math.max(0, Math.floor((target - now) / 1000));
            const days = Math.floor(seconds / 86400);
            const hours = Math.floor((seconds % 86400) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            if (days > 0) return this.fmt(days) + ' days, ' + hours + 'h ' + minutes + 'm ' + secs + 's remaining';
            return hours + 'h ' + minutes + 'm ' + secs + 's remaining';
        },

        summaryText() {
            if(!this.result.ageLabel)return '';
            return [
                'Age: '+this.result.ageLabel,
                'Born: '+this.result.birthDateLabel+' ('+this.result.birthWeekday+')',
                'Age as of: '+this.formatDate(this.asOfDate),
                'Total days: '+this.fmt(this.result.totalDays),
                'Total weeks: '+this.fmt(this.result.totalWeeks),
                'Next birthday: '+this.result.nextBirthdayLabel+' — '+this.result.nextBirthdayCountdown,
                'Half birthday: '+this.result.halfBirthdayLabel
            ].join('\\n');
        },

        copySummary() { this.copyText(this.summaryText()); },
        copyDifference() { this.copyText('Age difference: '+this.difference.label+'\\n'+this.difference.detail); },

        async copyText(text) {
            if(!text)return;
            try {
                await navigator.clipboard.writeText(text);
            } catch(e) {
                const area=document.createElement('textarea'); area.value=text; document.body.appendChild(area); area.select(); document.execCommand('copy'); area.remove();
            }
            this.copied=true; this.toast='Copied to clipboard'; setTimeout(()=>{this.copied=false;this.toast='';},1600);
        },

        printResult() {
            const text = this.mode === 'exact' || this.mode === 'on_date'
                ? this.summaryText()
                : this.mode === 'difference'
                    ? ('Age difference: ' + this.difference.label + '\\n' + this.difference.detail)
                    : this.mode === 'date_difference'
                        ? this.dateDifference.summary
                        : this.mode === 'dob_finder'
                            ? this.dobFinder.summary
                            : this.mode === 'eligibility'
                                ? this.eligibility.summary
                                : this.peopleResults.map(p => p.name + ': ' + p.age).join('\\n');
            const w = window.open('', '_blank', 'noopener,noreferrer');
            if (!w) { this.toast = 'Please allow pop-ups to print.'; return; }
            w.document.write('<!doctype html><html><head><title>AabiTech Age Calculator</title><style>body{font-family:system-ui,sans-serif;max-width:760px;margin:40px auto;padding:20px;color:#0f172a}pre{white-space:pre-wrap;font:16px/1.7 system-ui}</style></head><body><h1>AabiTech Age Calculator</h1><pre>' + this.escapeHtml(text) + '</pre></body></html>');
            w.document.close();
            w.focus();
            w.print();
        },

        downloadResult() {
            const blob = new Blob([this.summaryText()], {type:'text/plain;charset=utf-8'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href=url; a.download='aabitech-age-calculation.txt'; a.click();
            URL.revokeObjectURL(url);
        },

        escapeHtml(value) {
            return String(value || '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[ch]));
        },

        copyShareLink() {
            const state={mode:this.mode,b:this.birthDate,a:this.asOfDate,t:this.birthTime,lc:this.leapConvention,lang:this.language,cal:this.calendarDisplay,df:this.dateFormat};
            const encoded=this.base64Url(JSON.stringify(state));
            this.copyText(location.origin+location.pathname+'#age='+encoded);
        },

        base64Url(value) {
            const bytes=new TextEncoder().encode(value);
            let binary=''; bytes.forEach(b=>binary+=String.fromCharCode(b));
            return btoa(binary).replace(/\+/g,'-').replace(/\//g,'_').replace(/=+$/,'');
        },

        decodeBase64Url(value) {
            try {
                const binary=atob(value.replace(/-/g,'+').replace(/_/g,'/')+'==='.slice((value.length+3)%4));
                return new TextDecoder().decode(Uint8Array.from(binary,c=>c.charCodeAt(0)));
            } catch(e) { return null; }
        },

        applyShareState() {
            const match=location.hash.match(/^#age=([A-Za-z0-9_-]+)$/);
            if(!match)return;
            const raw=this.decodeBase64Url(match[1]); if(!raw)return;
            try {
                const s=JSON.parse(raw);
                if(s.b && this.parseISO(s.b)) this.birthDate=s.b;
                if(s.a && this.parseISO(s.a)) this.asOfDate=s.a;
                if(typeof s.t==='string')this.birthTime=s.t;
                if(s.lc==='mar1'||s.lc==='feb28')this.leapConvention=s.lc;
                if(s.lang==='ur'||s.lang==='en')this.language=s.lang;
                if(s.cal==='islamic'||s.cal==='gregorian')this.calendarDisplay=s.cal;
                if(s.df==='long'||s.df==='short'||s.df==='iso')this.dateFormat=s.df;
                if(this.modes.some(m=>m.id===s.mode))this.mode=s.mode;
            } catch(e) {}
        },

        handleShortcut(event) {
            if(event.key==='Escape') this.error='';
            if((event.ctrlKey||event.metaKey) && event.key.toLowerCase()==='k') { event.preventDefault(); this.reset(); }
        }
    };
};
</script>
@endscript
