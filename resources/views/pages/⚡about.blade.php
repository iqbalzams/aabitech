<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
};
?>

<div class="min-h-screen bg-slate-50">

    {{-- ============================================================
        HERO
    ============================================================= --}}
    <section class="border-b border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

            {{-- Breadcrumb --}}
            <nav
                aria-label="Breadcrumb"
                class="mb-10 flex items-center gap-2 text-sm text-slate-500"
            >
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="transition-colors hover:text-indigo-600"
                >
                    Home
                </a>

                <svg
                    class="h-4 w-4 text-slate-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06l-4.25-4.25a.75.75 0 10-1.06 1.06L10.94 10l-3.72 3.72a.75.75 0 000 1.06z"
                        clip-rule="evenodd"
                    />
                </svg>

                <span class="font-medium text-slate-700">
                    About
                </span>
            </nav>


            <div class="max-w-4xl">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700">
                    <span aria-hidden="true">⚡</span>
                    About AabiTech
                </div>


                <h1 class="text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Building practical technology for everyday digital work
                </h1>


                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl">
                    AabiTech is a technology platform focused on creating
                    useful, accessible and easy-to-use digital tools for
                    developers, students, creators, professionals and
                    everyday internet users.
                </p>

            </div>

        </div>

    </section>


    {{-- ============================================================
        INTRODUCTION
    ============================================================= --}}
    <main>

        <section class="bg-white">

            <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="prose prose-slate max-w-none">

                    <p class="text-lg leading-8 text-slate-700 sm:text-xl">
                        Technology should make everyday work simpler, not
                        more complicated. Yet many common digital tasks still
                        require unnecessary software, complicated interfaces,
                        registrations, downloads or technical knowledge.
                    </p>

                    <p class="mt-6 text-base leading-7 text-slate-600 sm:text-lg">
                        AabiTech was created around a simple idea: build
                        focused digital products that solve real problems
                        clearly and efficiently. Instead of trying to make
                        technology complicated, we aim to make useful
                        technology easier to understand and easier to use.
                    </p>

                    <p class="mt-6 text-base leading-7 text-slate-600 sm:text-lg">
                        Our work begins with practical online utilities, but
                        the broader goal is much bigger. AabiTech is being
                        developed as a long-term technology platform that can
                        bring together online tools, developer utilities,
                        educational technology, AI-powered products and
                        other useful digital services.
                    </p>

                </div>

            </div>

        </section>


        {{-- ============================================================
            MISSION / VISION
        ============================================================= --}}
        <section class="border-y border-slate-200 bg-slate-50">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="grid gap-6 lg:grid-cols-2">

                    {{-- Mission --}}
                    <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm sm:p-9">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-600">
                            ◎
                        </div>


                        <p class="mt-7 text-xs font-bold uppercase tracking-widest text-indigo-600">
                            Our Mission
                        </p>


                        <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                            Make useful technology simple and accessible
                        </h2>


                        <p class="mt-5 text-base leading-7 text-slate-600">
                            Our mission is to create practical digital tools
                            and technology products that help people complete
                            everyday tasks with less friction.
                        </p>


                        <p class="mt-4 text-base leading-7 text-slate-600">
                            We focus on clear interfaces, useful functionality,
                            fast experiences and technology that people can
                            understand and use without unnecessary complexity.
                        </p>

                    </article>


                    {{-- Vision --}}
                    <article class="rounded-2xl border border-slate-200 bg-slate-950 p-7 text-white shadow-sm sm:p-9">

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-xl text-indigo-300">
                            ◇
                        </div>


                        <p class="mt-7 text-xs font-bold uppercase tracking-widest text-indigo-300">
                            Our Vision
                        </p>


                        <h2 class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl">
                            Build a trusted home for practical digital tools
                        </h2>


                        <p class="mt-5 text-base leading-7 text-slate-300">
                            Our vision is to grow AabiTech into a trusted
                            technology platform where people can discover
                            useful tools, learn about technology and access
                            practical digital solutions in one place.
                        </p>


                        <p class="mt-4 text-base leading-7 text-slate-300">
                            Over time, this vision includes a broader ecosystem
                            of tools, AI-powered services, educational
                            technology and software products.
                        </p>

                    </article>

                </div>

            </div>

        </section>


        {{-- ============================================================
            WHAT WE BUILD
        ============================================================= --}}
        <section class="bg-white">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="max-w-3xl">

                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">
                        What we build
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Practical digital products with a clear purpose
                    </h2>

                    <p class="mt-5 text-base leading-7 text-slate-600 sm:text-lg">
                        AabiTech is not limited to one type of technology.
                        We build products around real digital needs and
                        prioritize usefulness over unnecessary complexity.
                    </p>

                </div>


                <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Online Tools --}}
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                        <div class="text-2xl" aria-hidden="true">
                            ⚡
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-slate-950">
                            Online Tools
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Focused utilities for development, text,
                            calculations, design, security and everyday
                            digital tasks.
                        </p>

                    </article>


                    {{-- Developer Tools --}}
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                        <div class="text-2xl" aria-hidden="true">
                            &lt;/&gt;
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-slate-950">
                            Developer Tools
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Practical utilities that help developers work
                            with code, data, URLs, JSON, regular expressions
                            and other common tasks.
                        </p>

                    </article>


                    {{-- AI --}}
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                        <div class="text-2xl" aria-hidden="true">
                            ✦
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-slate-950">
                            AI & Automation
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Exploring practical ways to use artificial
                            intelligence and automation to improve everyday
                            digital workflows.
                        </p>

                    </article>


                    {{-- Education --}}
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                        <div class="text-2xl" aria-hidden="true">
                            ◫
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-slate-950">
                            Education Technology
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Technology and digital resources designed to make
                            learning, teaching and access to useful knowledge
                            more practical.
                        </p>

                    </article>

                </div>

            </div>

        </section>


        {{-- ============================================================
            PRINCIPLES
        ============================================================= --}}
        <section class="border-y border-slate-200 bg-slate-50">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="max-w-3xl">

                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">
                        How we work
                    </p>

                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        Principles behind AabiTech
                    </h2>

                    <p class="mt-5 text-base leading-7 text-slate-600 sm:text-lg">
                        Every product does not need to do everything. We
                        believe focused products can often provide a better
                        experience when they solve one problem well.
                    </p>

                </div>


                <div class="mt-10 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">

                    {{-- Principle 01 --}}
                    <div class="grid gap-4 p-6 sm:grid-cols-[80px_1fr] sm:p-8">

                        <span class="text-sm font-bold text-indigo-600">
                            01
                        </span>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-950">
                                Useful before complicated
                            </h3>

                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                We prioritize solving the actual problem over
                                adding features that make a product harder to
                                understand.
                            </p>

                        </div>

                    </div>


                    {{-- Principle 02 --}}
                    <div class="grid gap-4 p-6 sm:grid-cols-[80px_1fr] sm:p-8">

                        <span class="text-sm font-bold text-indigo-600">
                            02
                        </span>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-950">
                                Simple by design
                            </h3>

                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                Interfaces should help users accomplish their
                                task quickly rather than make them learn the
                                product first.
                            </p>

                        </div>

                    </div>


                    {{-- Principle 03 --}}
                    <div class="grid gap-4 p-6 sm:grid-cols-[80px_1fr] sm:p-8">

                        <span class="text-sm font-bold text-indigo-600">
                            03
                        </span>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-950">
                                Fast and accessible
                            </h3>

                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                We aim to create lightweight experiences that
                                work well across devices and are accessible to
                                people with different levels of technical
                                knowledge.
                            </p>

                        </div>

                    </div>


                    {{-- Principle 04 --}}
                    <div class="grid gap-4 p-6 sm:grid-cols-[80px_1fr] sm:p-8">

                        <span class="text-sm font-bold text-indigo-600">
                            04
                        </span>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-950">
                                Privacy-conscious technology
                            </h3>

                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                When a task can be performed locally in the
                                browser, unnecessary transfer of user input
                                should be avoided.
                            </p>

                        </div>

                    </div>


                    {{-- Principle 05 --}}
                    <div class="grid gap-4 p-6 sm:grid-cols-[80px_1fr] sm:p-8">

                        <span class="text-sm font-bold text-indigo-600">
                            05
                        </span>

                        <div>

                            <h3 class="text-lg font-semibold text-slate-950">
                                Build for the long term
                            </h3>

                            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                                AabiTech is being developed as a growing
                                technology platform, so we focus on strong
                                foundations rather than short-lived features.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ============================================================
            PRIVACY
        ============================================================= --}}
        <section class="bg-slate-950 text-white">

            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <div class="grid gap-10 lg:grid-cols-[1fr_380px] lg:items-center">

                    <div class="max-w-3xl">

                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-400">
                            Privacy-conscious by design
                        </p>

                        <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                            Your data should not travel when it doesn't need to.
                        </h2>

                        <p class="mt-5 text-base leading-7 text-slate-300 sm:text-lg">
                            Many of AabiTech's utilities are designed to
                            perform their work directly in the browser. For
                            supported tools, this means your input can be
                            processed on your device instead of being sent to
                            a server simply to complete a basic task.
                        </p>

                        <p class="mt-4 text-sm leading-6 text-slate-400">
                            We aim to be clear about how individual products
                            process information rather than making broad
                            privacy claims that do not apply to every service.
                        </p>

                    </div>


                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">

                        <div class="space-y-5">

                            <div class="flex gap-4">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-400/10 text-emerald-400">
                                    ✓
                                </div>

                                <div>

                                    <h3 class="text-sm font-semibold">
                                        Browser-first processing
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-slate-400">
                                        Supported utilities can process input
                                        directly on your device.
                                    </p>

                                </div>

                            </div>


                            <div class="flex gap-4">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-400/10 text-indigo-400">
                                    ◇
                                </div>

                                <div>

                                    <h3 class="text-sm font-semibold">
                                        Clear product behavior
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-slate-400">
                                        Individual tools should clearly
                                        communicate relevant processing
                                        behavior.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ============================================================
            WHERE WE ARE GOING
        ============================================================= --}}
        <section class="bg-white">

            <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">
                    The road ahead
                </p>

                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    A growing technology platform
                </h2>


                <div class="mt-7 space-y-5 text-base leading-7 text-slate-600 sm:text-lg">

                    <p>
                        AabiTech starts with a collection of focused online
                        tools, but our long-term direction goes beyond simple
                        utilities.
                    </p>

                    <p>
                        We want to develop a broader ecosystem where useful
                        digital tools, software development, artificial
                        intelligence, automation and education technology can
                        work together to solve practical problems.
                    </p>

                    <p>
                        That means continuously improving existing tools,
                        adding new utilities based on real user needs,
                        experimenting with AI-powered products and building
                        technology that provides genuine value rather than
                        simply adding another layer of complexity to the web.
                    </p>

                </div>


                {{-- Future areas --}}
                <div class="mt-10 grid gap-3 sm:grid-cols-2">

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <span class="text-sm font-medium text-slate-700">
                            More practical online utilities
                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <span class="text-sm font-medium text-slate-700">
                            Developer and productivity tools
                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <span class="text-sm font-medium text-slate-700">
                            AI-powered digital products
                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">
                        <span class="text-sm font-medium text-slate-700">
                            Education and learning technology
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- ============================================================
            EXPLORE
        ============================================================= --}}
        <section class="border-t border-slate-200 bg-slate-50">

            <div class="mx-auto max-w-4xl px-4 py-14 text-center sm:px-6 lg:px-8 lg:py-16">

                <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                    Explore AabiTech
                </h2>

                <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                    Start with our collection of free online tools and
                    discover practical utilities built for everyday digital
                    work.
                </p>


                <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">

                    <a
                        href="{{ route('tools') }}"
                        wire:navigate
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-950 px-6 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Explore all tools

                        <svg
                            class="ml-2 h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.22 14.78a.75.75 0 001.06 0l4.25-4.25a.75.75 0 000-1.06l-4.25-4.25a.75.75 0 10-1.06 1.06L10.94 10l-3.72 3.72a.75.75 0 000 1.06z"
                                clip-rule="evenodd"
                            />
                        </svg>

                    </a>


                    <a
                        href="{{ route('home') }}"
                        wire:navigate
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                    >
                        Visit homepage
                    </a>

                </div>

            </div>

        </section>

    </main>

</div>