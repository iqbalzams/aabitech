<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.app')]
class extends Component
{
    public function getSeoProperty(): array
    {
        return [
            'title' => 'Privacy Policy | AabiTech',
            'description' => 'Learn how AabiTech handles personal information, tool inputs, cookies, local storage, contact requests and third-party services.',
            'canonical' => url('/privacy-policy'),
        ];
    }
};
?>

<div class="min-h-screen bg-white text-zinc-900">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

        {{-- Header --}}
        <header class="mx-auto max-w-3xl text-center">
            <div
                class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-xl border border-zinc-200 bg-zinc-50 text-indigo-600"
                aria-hidden="true"
            >
                <svg
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3l7 3v5c0 4.7-2.9 8.5-7 10-4.1-1.5-7-5.3-7-10V6l7-3z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.5 12l1.7 1.7 3.5-3.5"
                    />
                </svg>
            </div>

            <p class="text-sm font-medium text-indigo-600">
                AabiTech
            </p>

            <h1 class="mt-1 text-3xl font-semibold tracking-tight text-zinc-950 sm:text-4xl">
                Privacy Policy
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-zinc-600 sm:text-base">
                This policy explains what information AabiTech may collect, how it is used,
                and how privacy is handled when you use our website and online tools.
            </p>

            <p class="mt-4 text-xs text-zinc-500">
                Last updated: September 30, 2026
            </p>
        </header>

        <div class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1fr)_240px] lg:items-start">

            {{-- Main policy --}}
            <main
                id="privacy-policy"
                class="min-w-0 rounded-2xl border border-zinc-200 bg-white"
                aria-label="Privacy Policy"
            >
                <article class="px-5 py-7 sm:px-8 sm:py-9">

                    {{-- Privacy at a glance --}}
                    <section
                        id="at-a-glance"
                        aria-labelledby="privacy-at-a-glance"
                        class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-5"
                    >
                        <h2
                            id="privacy-at-a-glance"
                            class="text-base font-semibold text-zinc-950"
                        >
                            Privacy at a glance
                        </h2>

                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            <li class="flex gap-3 text-sm leading-6 text-zinc-700">
                                <span
                                    class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"
                                    aria-hidden="true"
                                >
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <span>
                                    We do not sell or rent your personal information.
                                </span>
                            </li>

                            <li class="flex gap-3 text-sm leading-6 text-zinc-700">
                                <span
                                    class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"
                                    aria-hidden="true"
                                >
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <span>
                                    Browser-based tools may process your input directly in your browser.
                                </span>
                            </li>

                            <li class="flex gap-3 text-sm leading-6 text-zinc-700">
                                <span
                                    class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"
                                    aria-hidden="true"
                                >
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <span>
                                    Contact information is used to respond to your requests.
                                </span>
                            </li>

                            <li class="flex gap-3 text-sm leading-6 text-zinc-700">
                                <span
                                    class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"
                                    aria-hidden="true"
                                >
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <span>
                                    We use reasonable measures to protect information we handle.
                                </span>
                            </li>
                        </ul>
                    </section>

                    {{-- Introduction --}}
                    <section id="introduction" class="mt-10 scroll-mt-24" aria-labelledby="introduction-heading">
                        <h2 id="introduction-heading" class="text-xl font-semibold text-zinc-950">
                            1. Introduction
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech provides free online tools for developers, students,
                                writers, creators, businesses and everyday users. This Privacy
                                Policy describes how information may be collected, used, stored
                                and disclosed when you visit or use
                                <a
                                    href="{{ url('/') }}"
                                    wire:navigate
                                    class="font-medium text-indigo-600 underline decoration-indigo-200 underline-offset-2 hover:text-indigo-700"
                                >
                                    AabiTech
                                </a>
                                and its tools.
                            </p>

                            <p>
                                By using AabiTech, you acknowledge the practices described in
                                this policy. If you do not agree with this policy, please do not
                                use the website or submit personal information through it.
                            </p>
                        </div>
                    </section>

                    {{-- Information collected --}}
                    <section id="information-we-collect" class="mt-10 scroll-mt-24" aria-labelledby="information-heading">
                        <h2 id="information-heading" class="text-xl font-semibold text-zinc-950">
                            2. Information we may collect
                        </h2>

                        <div class="mt-4 space-y-6 text-sm leading-7 text-zinc-700">
                            <div>
                                <h3 class="font-semibold text-zinc-900">
                                    Information you provide
                                </h3>

                                <p class="mt-2">
                                    If you contact AabiTech, we may receive information that you
                                    voluntarily provide, such as your name, email address, subject,
                                    reason for contacting us and the contents of your message.
                                </p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-zinc-900">
                                    Technical information
                                </h3>

                                <p class="mt-2">
                                    Like most websites, AabiTech and its hosting infrastructure may
                                    process ordinary technical information required to operate,
                                    secure and troubleshoot the service. Depending on the
                                    configuration of the website and hosting environment, this may
                                    include IP address, browser type, device information, requested
                                    pages, timestamps, referring pages and basic diagnostic
                                    information.
                                </p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-zinc-900">
                                    Tool input and generated output
                                </h3>

                                <p class="mt-2">
                                    AabiTech contains different types of tools. Where a tool is
                                    specifically designed for browser-side or local processing,
                                    the data entered into that tool can be processed directly on
                                    your device and does not need to be transmitted to AabiTech's
                                    servers for the tool to perform its core function.
                                </p>

                                <p class="mt-3">
                                    However, not every present or future AabiTech feature necessarily
                                    operates this way. Tools that require server-side processing,
                                    external APIs or other online services may transmit the relevant
                                    information needed to provide that feature. The individual tool
                                    interface or documentation should be consulted where processing
                                    architecture is important to your use case.
                                </p>

                                <p class="mt-3 font-medium text-zinc-800">
                                    Do not enter passwords, private keys, authentication tokens,
                                    confidential business information, personal records or other
                                    highly sensitive information into a tool unless you have verified
                                    that the tool's processing method is appropriate for that data.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- How information is used --}}
                    <section id="how-we-use-information" class="mt-10 scroll-mt-24" aria-labelledby="use-heading">
                        <h2 id="use-heading" class="text-xl font-semibold text-zinc-950">
                            3. How information is used
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Information that AabiTech receives may be used for purposes such as:
                        </p>

                        <ul class="mt-4 list-disc space-y-2 ps-5 text-sm leading-7 text-zinc-700">
                            <li>providing, maintaining and improving the website and its tools;</li>
                            <li>responding to contact requests and support inquiries;</li>
                            <li>identifying and resolving technical problems;</li>
                            <li>protecting the website against abuse, fraud and security threats;</li>
                            <li>understanding general website usage and improving usability, where analytics are enabled;</li>
                            <li>maintaining the reliability and security of our infrastructure; and</li>
                            <li>complying with applicable legal obligations.</li>
                        </ul>
                    </section>

                    {{-- Local processing --}}
                    <section id="local-processing" class="mt-10 scroll-mt-24" aria-labelledby="local-processing-heading">
                        <h2 id="local-processing-heading" class="text-xl font-semibold text-zinc-950">
                            4. Browser-based and local processing
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Privacy is an important consideration in the design of AabiTech.
                                Whenever practical, utility functions are designed so that processing
                                can happen in the user's browser rather than requiring data to be
                                uploaded to a server.
                            </p>

                            <p>
                                Browser-side processing does not mean that the website itself receives
                                no technical information. Normal web requests may still involve
                                information such as an IP address, browser details and request
                                metadata as necessary for web delivery, security and infrastructure.
                            </p>

                            <p>
                                Some features may use server-side processing or third-party services.
                                Where this is necessary, the relevant feature should be considered
                                separately from tools that explicitly perform processing locally.
                            </p>
                        </div>
                    </section>

                    {{-- Cookies --}}
                    <section id="cookies-and-local-storage" class="mt-10 scroll-mt-24" aria-labelledby="cookies-heading">
                        <h2 id="cookies-heading" class="text-xl font-semibold text-zinc-950">
                            5. Cookies and local storage
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech may use essential cookies or similar browser mechanisms
                                required for website functionality, security, sessions and
                                navigation.
                            </p>

                            <p>
                                Some tools may also use browser storage such as
                                <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-xs text-zinc-800">
                                    localStorage
                                </code>
                                to remember settings, preferences or locally stored tool data.
                                Such information remains in your browser unless the relevant tool
                                or your browser removes it.
                            </p>

                            <p>
                                If optional analytics, advertising or other non-essential tracking
                                technologies are introduced in the future, AabiTech will update its
                                privacy and cookie information as appropriate.
                            </p>
                        </div>
                    </section>

                    {{-- Third parties --}}
                    <section id="third-party-services" class="mt-10 scroll-mt-24" aria-labelledby="third-party-heading">
                        <h2 id="third-party-heading" class="text-xl font-semibold text-zinc-950">
                            6. Third-party services and external websites
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech may rely on third-party providers for infrastructure,
                                hosting, email delivery, security, analytics, embedded content or
                                other operational services. Those providers may process information
                                according to their own privacy policies and contractual obligations.
                            </p>

                            <p>
                                The Contact page may contain an embedded map or links to external
                                services such as WhatsApp or email providers. When you interact with
                                an external service, that service may receive information according
                                to its own policies.
                            </p>

                            <p>
                                AabiTech is not responsible for the privacy practices, content or
                                security of websites and services that it does not operate.
                                Please review their respective privacy policies before providing
                                information to them.
                            </p>
                        </div>
                    </section>

                    {{-- Sharing --}}
                    <section id="information-sharing" class="mt-10 scroll-mt-24" aria-labelledby="sharing-heading">
                        <h2 id="sharing-heading" class="text-xl font-semibold text-zinc-950">
                            7. When information may be shared
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech does not sell or rent your personal information.
                            </p>

                            <p>
                                Information may be disclosed when reasonably necessary to operate
                                the service, for example to trusted service providers that perform
                                functions such as hosting, infrastructure, email delivery or
                                security.
                            </p>

                            <p>
                                Information may also be disclosed where required by applicable law,
                                legal process, a valid governmental request, or when reasonably
                                necessary to protect the rights, property, security or users of
                                AabiTech.
                            </p>
                        </div>
                    </section>

                    {{-- Retention --}}
                    <section id="data-retention" class="mt-10 scroll-mt-24" aria-labelledby="retention-heading">
                        <h2 id="retention-heading" class="text-xl font-semibold text-zinc-950">
                            8. Data retention
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Contact messages and related information are retained only for as long
                            as reasonably necessary to respond to requests, maintain appropriate
                            business records, resolve disputes, protect the service or satisfy
                            applicable legal obligations.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Information stored locally in your browser by an AabiTech tool is under
                            your browser's control. You can generally remove such data through the
                            browser's storage or site-data controls.
                        </p>
                    </section>

                    {{-- Security --}}
                    <section id="security" class="mt-10 scroll-mt-24" aria-labelledby="security-heading">
                        <h2 id="security-heading" class="text-xl font-semibold text-zinc-950">
                            9. Security
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech uses reasonable technical and organizational measures intended
                            to protect information handled by the service. However, no website,
                            transmission method or storage system can be guaranteed to be completely
                            secure. You should avoid submitting information through an online service
                            when you do not consider the service appropriate for the sensitivity of
                            that information.
                        </p>
                    </section>

                    {{-- Children's privacy --}}
                    <section id="children" class="mt-10 scroll-mt-24" aria-labelledby="children-heading">
                        <h2 id="children-heading" class="text-xl font-semibold text-zinc-950">
                            10. Children's privacy
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech is a general-purpose online tools website and is not specifically
                            directed at children. We do not knowingly request personal information
                            from children through our contact facilities. If you believe that a child
                            has provided personal information to AabiTech, please contact us so that
                            the matter can be reviewed and appropriate action can be taken.
                        </p>
                    </section>

                    {{-- International processing --}}
                    <section id="international-processing" class="mt-10 scroll-mt-24" aria-labelledby="international-heading">
                        <h2 id="international-heading" class="text-xl font-semibold text-zinc-950">
                            11. International processing
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech and its service providers may operate infrastructure or process
                            information in countries other than the country where you live. Where
                            information is transferred internationally, applicable service providers
                            may be subject to the laws of the jurisdictions in which they operate.
                        </p>
                    </section>

                    {{-- User rights --}}
                    <section id="your-rights" class="mt-10 scroll-mt-24" aria-labelledby="rights-heading">
                        <h2 id="rights-heading" class="text-xl font-semibold text-zinc-950">
                            12. Your privacy rights
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Depending on where you live and which privacy laws apply to your
                                information, you may have rights concerning your personal information.
                                These may include the right to request access, correction, deletion,
                                restriction or other forms of control over your information.
                            </p>

                            <p>
                                To make a privacy-related request, contact AabiTech with enough
                                information for us to understand and process your request. We may
                                need to verify the request before taking action.
                            </p>

                            <p>
                                Your applicable rights can vary according to jurisdiction and the
                                circumstances in which your information was collected.
                            </p>
                        </div>
                    </section>

                    {{-- Policy changes --}}
                    <section id="policy-changes" class="mt-10 scroll-mt-24" aria-labelledby="changes-heading">
                        <h2 id="changes-heading" class="text-xl font-semibold text-zinc-950">
                            13. Changes to this Privacy Policy
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech may update this Privacy Policy when its services, technology,
                            legal requirements or privacy practices change. The updated version will
                            be published on this page with a revised "Last updated" date.
                        </p>
                    </section>

                    {{-- Contact --}}
                    <section id="contact" class="mt-10 scroll-mt-24" aria-labelledby="contact-heading">
                        <h2 id="contact-heading" class="text-xl font-semibold text-zinc-950">
                            14. Contact us about privacy
                        </h2>

                        <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-5">
                            <p class="text-sm leading-7 text-zinc-700">
                                If you have a question about this Privacy Policy, believe your
                                information has been handled incorrectly, or want to make a privacy
                                request, please contact AabiTech through our contact page.
                            </p>

                            <div class="mt-5">
                                <a
                                    href="{{ route('contact') }}"
                                    wire:navigate
                                    class="inline-flex min-h-9 items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Contact AabiTech
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 10H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </section>

                    {{-- Disclaimer --}}
                    <div class="mt-10 border-t border-zinc-200 pt-6">
                        <p class="text-xs leading-6 text-zinc-500">
                            This Privacy Policy is provided to explain AabiTech's general privacy
                            practices. It is not legal advice and does not create rights or
                            obligations beyond those required by applicable law.
                        </p>
                    </div>
                </article>
            </main>

            {{-- Table of contents --}}
            <aside class="lg:sticky lg:top-6" aria-label="Privacy Policy navigation">
                <nav class="rounded-xl border border-zinc-200 bg-zinc-50/70 p-4">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        On this page
                    </h2>

                    <ol class="mt-3 space-y-1 text-sm">
                        <li>
                            <a
                                href="#at-a-glance"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Privacy at a glance
                            </a>
                        </li>

                        <li>
                            <a
                                href="#introduction"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Introduction
                            </a>
                        </li>

                        <li>
                            <a
                                href="#information-we-collect"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Information we collect
                            </a>
                        </li>

                        <li>
                            <a
                                href="#how-we-use-information"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                How information is used
                            </a>
                        </li>

                        <li>
                            <a
                                href="#local-processing"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Local processing
                            </a>
                        </li>

                        <li>
                            <a
                                href="#cookies-and-local-storage"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Cookies & storage
                            </a>
                        </li>

                        <li>
                            <a
                                href="#third-party-services"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Third-party services
                            </a>
                        </li>

                        <li>
                            <a
                                href="#information-sharing"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Information sharing
                            </a>
                        </li>

                        <li>
                            <a
                                href="#data-retention"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Data retention
                            </a>
                        </li>

                        <li>
                            <a
                                href="#security"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Security
                            </a>
                        </li>

                        <li>
                            <a
                                href="#children"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Children's privacy
                            </a>
                        </li>

                        <li>
                            <a
                                href="#international-processing"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                International processing
                            </a>
                        </li>

                        <li>
                            <a
                                href="#your-rights"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Your rights
                            </a>
                        </li>

                        <li>
                            <a
                                href="#policy-changes"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Policy changes
                            </a>
                        </li>

                        <li>
                            <a
                                href="#contact"
                                class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Contact us
                            </a>
                        </li>
                    </ol>
                </nav>
            </aside>
        </div>
    </div>
</div>