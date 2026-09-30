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
            'title' => 'Terms of Service | AabiTech',
            'description' => 'Read the AabiTech Terms of Service covering acceptable use, online tools, user responsibilities, availability, intellectual property and limitations.',
            'canonical' => url('/terms'),
        ];
    }
};
?>

<div class="min-h-screen bg-white text-zinc-900">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

        {{-- Page header --}}
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
                        d="M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7h8M8 11h8M8 15h5"
                    />
                </svg>
            </div>

            <p class="text-sm font-medium text-indigo-600">
                AabiTech
            </p>

            <h1 class="mt-1 text-3xl font-semibold tracking-tight text-zinc-950 sm:text-4xl">
                Terms of Service
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-zinc-600 sm:text-base">
                These terms explain the rules for using AabiTech and its online tools,
                including your responsibilities, acceptable use and important limitations.
            </p>

            <p class="mt-4 text-xs text-zinc-500">
                Last updated: September 30, 2026
            </p>
        </header>

        <div class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1fr)_240px] lg:items-start">

            {{-- Main content --}}
            <main
                id="terms-of-service"
                class="min-w-0 rounded-2xl border border-zinc-200 bg-white"
                aria-label="Terms of Service"
            >
                <article class="px-5 py-7 sm:px-8 sm:py-9">

                    {{-- At a glance --}}
                    <section
                        id="at-a-glance"
                        class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-5"
                        aria-labelledby="terms-at-a-glance"
                    >
                        <h2
                            id="terms-at-a-glance"
                            class="text-base font-semibold text-zinc-950"
                        >
                            In brief
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
                                    Use AabiTech lawfully and responsibly.
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
                                    Check individual tools before entering sensitive information.
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
                                    Tool results should be reviewed before important decisions.
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
                                            d="M16.704 5.29a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <span>
                                    AabiTech tools are provided without a guarantee of uninterrupted availability.
                                </span>
                            </li>
                        </ul>
                    </section>

                    {{-- 1 --}}
                    <section id="acceptance" class="mt-10 scroll-mt-24" aria-labelledby="acceptance-heading">
                        <h2 id="acceptance-heading" class="text-xl font-semibold text-zinc-950">
                            1. Acceptance of these terms
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Welcome to AabiTech. These Terms of Service govern your access to
                                and use of the AabiTech website, online tools, content and related
                                services.
                            </p>

                            <p>
                                By accessing or using AabiTech, you agree to comply with these
                                Terms of Service and applicable laws. If you do not agree with
                                these terms, please do not use the website or its services.
                            </p>
                        </div>
                    </section>

                    {{-- 2 --}}
                    <section id="about-aabitech" class="mt-10 scroll-mt-24" aria-labelledby="about-heading">
                        <h2 id="about-heading" class="text-xl font-semibold text-zinc-950">
                            2. About AabiTech
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech provides free online utilities designed to help with common
                            tasks involving development, text, calculations, design, security,
                            education, productivity and other digital workflows.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech may add, modify, improve, replace or discontinue tools and
                            features from time to time.
                        </p>
                    </section>

                    {{-- 3 --}}
                    <section id="eligibility" class="mt-10 scroll-mt-24" aria-labelledby="eligibility-heading">
                        <h2 id="eligibility-heading" class="text-xl font-semibold text-zinc-950">
                            3. Eligibility
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            You may use AabiTech only if you are legally permitted to do so under
                            the laws applicable to you. If you use AabiTech on behalf of an
                            organization, you represent that you have appropriate authority to
                            accept these terms on its behalf.
                        </p>
                    </section>

                    {{-- 4 --}}
                    <section id="acceptable-use" class="mt-10 scroll-mt-24" aria-labelledby="acceptable-heading">
                        <h2 id="acceptable-heading" class="text-xl font-semibold text-zinc-950">
                            4. Acceptable use
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            You agree to use AabiTech responsibly and not to misuse the website,
                            its infrastructure or its tools.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            You must not use AabiTech to:
                        </p>

                        <ul class="mt-4 list-disc space-y-2 ps-5 text-sm leading-7 text-zinc-700">
                            <li>violate applicable laws or regulations;</li>
                            <li>infringe another person's intellectual property, privacy or other rights;</li>
                            <li>attempt to gain unauthorized access to AabiTech or another system;</li>
                            <li>interfere with, disrupt or overload the website or its infrastructure;</li>
                            <li>introduce malware, malicious code or other harmful material;</li>
                            <li>scrape, crawl or automate the service in a manner that places unreasonable load on the infrastructure;</li>
                            <li>bypass reasonable technical restrictions or security controls;</li>
                            <li>use generated results to facilitate unlawful or harmful activity; or</li>
                            <li>misrepresent your identity or affiliation when using AabiTech.</li>
                        </ul>
                    </section>

                    {{-- 5 --}}
                    <section id="tools-and-results" class="mt-10 scroll-mt-24" aria-labelledby="tools-heading">
                        <h2 id="tools-heading" class="text-xl font-semibold text-zinc-950">
                            5. Use of AabiTech tools and results
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech tools are provided to assist with everyday digital tasks.
                                Results are generated according to the inputs, algorithms,
                                configurations and services used by the relevant tool.
                            </p>

                            <p>
                                You are responsible for reviewing and validating results before
                                relying on them, particularly where an incorrect result could cause
                                financial, legal, security, academic, operational or other
                                significant consequences.
                            </p>

                            <p>
                                AabiTech does not represent that every calculation, conversion,
                                generated value, interpretation, transformation or other result
                                will be error-free or suitable for every purpose.
                            </p>
                        </div>
                    </section>

                    {{-- 6 --}}
                    <section id="sensitive-information" class="mt-10 scroll-mt-24" aria-labelledby="sensitive-heading">
                        <h2 id="sensitive-heading" class="text-xl font-semibold text-zinc-950">
                            6. Sensitive and confidential information
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                You are responsible for determining whether a particular AabiTech
                                tool is appropriate for sensitive information.
                            </p>

                            <p>
                                Unless a tool explicitly states otherwise, do not assume that
                                information entered into an online tool is confidential or that
                                it will necessarily be processed only on your device.
                            </p>

                            <p class="font-medium text-zinc-800">
                                Avoid entering passwords, private keys, authentication credentials,
                                confidential documents, personal records or other highly sensitive
                                information unless you have verified the tool's processing method
                                and consider the use appropriate.
                            </p>
                        </div>
                    </section>

                    {{-- 7 --}}
                    <section id="local-processing" class="mt-10 scroll-mt-24" aria-labelledby="local-heading">
                        <h2 id="local-heading" class="text-xl font-semibold text-zinc-950">
                            7. Browser-based processing
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Some AabiTech tools are designed to perform their core processing
                                directly in your browser. When a tool operates this way, the input
                                may be processed locally without being uploaded to AabiTech's
                                servers for the core operation.
                            </p>

                            <p>
                                AabiTech also contains or may introduce tools that require
                                server-side processing, external APIs or other online services.
                                The processing behavior of one tool should not automatically be
                                assumed to apply to every other tool.
                            </p>
                        </div>
                    </section>

                    {{-- 8 --}}
                    <section id="intellectual-property" class="mt-10 scroll-mt-24" aria-labelledby="ip-heading">
                        <h2 id="ip-heading" class="text-xl font-semibold text-zinc-950">
                            8. Intellectual property
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Unless otherwise stated, the AabiTech website, branding, logos,
                                interface design, original text, graphics, source code and other
                                site materials are owned by or licensed to AabiTech and are
                                protected by applicable intellectual property laws.
                            </p>

                            <p>
                                These terms do not grant you ownership of AabiTech's intellectual
                                property. You may use the website and its tools for their intended
                                purposes, subject to these terms.
                            </p>

                            <p>
                                You retain responsibility for content and data that you provide to
                                AabiTech. You should only submit material that you have the right
                                to use and process.
                            </p>
                        </div>
                    </section>

                    {{-- 9 --}}
                    <section id="third-party-services" class="mt-10 scroll-mt-24" aria-labelledby="third-party-heading">
                        <h2 id="third-party-heading" class="text-xl font-semibold text-zinc-950">
                            9. Third-party services and links
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                Some AabiTech features may rely on third-party services, APIs,
                                hosting providers, embedded content or external websites.
                            </p>

                            <p>
                                Third-party services operate under their own terms, policies and
                                technical limitations. Your use of those services may therefore be
                                subject to additional terms imposed by the relevant provider.
                            </p>

                            <p>
                                AabiTech is not responsible for the availability, content,
                                policies, security or performance of third-party websites and
                                services that it does not control.
                            </p>
                        </div>
                    </section>

                    {{-- 10 --}}
                    <section id="availability" class="mt-10 scroll-mt-24" aria-labelledby="availability-heading">
                        <h2 id="availability-heading" class="text-xl font-semibold text-zinc-950">
                            10. Availability and changes
                        </h2>

                        <div class="mt-4 space-y-4 text-sm leading-7 text-zinc-700">
                            <p>
                                AabiTech is provided on an evolving basis. We may update, improve,
                                temporarily disable, restrict or discontinue a tool, feature or
                                part of the website at any time.
                            </p>

                            <p>
                                We aim to keep AabiTech useful and reliable, but we do not guarantee
                                that the website or every tool will always be available, uninterrupted,
                                secure, current or free from errors.
                            </p>
                        </div>
                    </section>

                    {{-- 11 --}}
                    <section id="accuracy" class="mt-10 scroll-mt-24" aria-labelledby="accuracy-heading">
                        <h2 id="accuracy-heading" class="text-xl font-semibold text-zinc-950">
                            11. Accuracy and informational use
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech tools are general-purpose utilities and are not a substitute
                            for professional advice, authoritative documentation, official
                            calculations or expert review where those are required.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Before relying on an output for an important decision, verify the result
                            against an appropriate authoritative or professional source.
                        </p>
                    </section>

                    {{-- 12 --}}
                    <section id="disclaimer" class="mt-10 scroll-mt-24" aria-labelledby="disclaimer-heading">
                        <h2 id="disclaimer-heading" class="text-xl font-semibold text-zinc-950">
                            12. Disclaimer of warranties
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            To the maximum extent permitted by applicable law, AabiTech and its
                            services are provided on an "as is" and "as available" basis without
                            warranties of any kind, whether express, implied or statutory, except
                            where such warranties cannot lawfully be excluded.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            This includes, to the extent legally permitted, warranties concerning
                            availability, accuracy, reliability, fitness for a particular purpose,
                            non-infringement and suitability for a specific use.
                        </p>
                    </section>

                    {{-- 13 --}}
                    <section id="limitation-of-liability" class="mt-10 scroll-mt-24" aria-labelledby="liability-heading">
                        <h2 id="liability-heading" class="text-xl font-semibold text-zinc-950">
                            13. Limitation of liability
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            To the maximum extent permitted by applicable law, AabiTech will not
                            be liable for indirect, incidental, special, consequential or similar
                            losses arising from or related to your use of the website or tools,
                            including loss of data, business interruption or reliance on tool
                            results.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Nothing in these terms is intended to exclude or limit liability where
                            doing so would be prohibited by applicable law.
                        </p>
                    </section>

                    {{-- 14 --}}
                    <section id="indemnification" class="mt-10 scroll-mt-24" aria-labelledby="indemnification-heading">
                        <h2 id="indemnification-heading" class="text-xl font-semibold text-zinc-950">
                            14. Your responsibility
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            You are responsible for your use of AabiTech and for ensuring that your
                            use of the service complies with applicable laws and does not infringe
                            the rights of others.
                        </p>
                    </section>

                    {{-- 15 --}}
                    <section id="suspension" class="mt-10 scroll-mt-24" aria-labelledby="suspension-heading">
                        <h2 id="suspension-heading" class="text-xl font-semibold text-zinc-950">
                            15. Restriction or suspension of access
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech may restrict or suspend access where reasonably necessary to
                            protect the website, users, infrastructure or third parties, including
                            in response to abuse, security incidents, unlawful activity or attempts
                            to circumvent technical restrictions.
                        </p>
                    </section>

                    {{-- 16 --}}
                    <section id="privacy" class="mt-10 scroll-mt-24" aria-labelledby="privacy-heading">
                        <h2 id="privacy-heading" class="text-xl font-semibold text-zinc-950">
                            16. Privacy
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Your use of AabiTech is also subject to our
                            <a
                                href="{{ route('privacy-policy') }}"
                                wire:navigate
                                class="font-medium text-indigo-600 underline decoration-indigo-200 underline-offset-2 hover:text-indigo-700"
                            >
                                Privacy Policy
                            </a>,
                            which explains how information may be handled by the website and its
                            services.
                        </p>
                    </section>

                    {{-- 17 --}}
                    <section id="changes-to-terms" class="mt-10 scroll-mt-24" aria-labelledby="changes-heading">
                        <h2 id="changes-heading" class="text-xl font-semibold text-zinc-950">
                            17. Changes to these terms
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            AabiTech may update these Terms of Service as the website, tools,
                            business practices or applicable requirements change. Updated terms
                            will be published on this page with a revised "Last updated" date.
                        </p>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            Your continued use of AabiTech after an updated version is published
                            constitutes your continued use subject to the updated terms, to the
                            extent permitted by applicable law.
                        </p>
                    </section>

                    {{-- 18 --}}
                    <section id="governing-law" class="mt-10 scroll-mt-24" aria-labelledby="law-heading">
                        <h2 id="law-heading" class="text-xl font-semibold text-zinc-950">
                            18. Governing law
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-zinc-700">
                            These terms are intended to be interpreted in accordance with applicable
                            law. Any specific governing-law or jurisdiction provisions should be
                            reviewed and finalized according to the legal structure, location and
                            operating arrangements of AabiTech.
                        </p>
                    </section>

                    {{-- 19 --}}
                    <section id="contact" class="mt-10 scroll-mt-24" aria-labelledby="contact-heading">
                        <h2 id="contact-heading" class="text-xl font-semibold text-zinc-950">
                            19. Contact AabiTech
                        </h2>

                        <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-5">
                            <p class="text-sm leading-7 text-zinc-700">
                                If you have questions about these Terms of Service, a tool,
                                acceptable use or another aspect of AabiTech, please contact us.
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
                                            d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 1.414L13.586 10H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </section>

                    {{-- Legal note --}}
                    <div class="mt-10 border-t border-zinc-200 pt-6">
                        <p class="text-xs leading-6 text-zinc-500">
                            These Terms of Service are intended as a general website terms
                            framework. They should be reviewed and finalized by a qualified legal
                            professional for AabiTech's actual ownership structure, jurisdiction,
                            services and business model before launch.
                        </p>
                    </div>
                </article>
            </main>

            {{-- Table of contents --}}
            <aside class="lg:sticky lg:top-6" aria-label="Terms of Service navigation">
                <nav class="rounded-xl border border-zinc-200 bg-zinc-50/70 p-4">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-zinc-500">
                        On this page
                    </h2>

                    <ol class="mt-3 space-y-1 text-sm">
                        <li>
                            <a href="#at-a-glance"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                In brief
                            </a>
                        </li>

                        <li>
                            <a href="#acceptance"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Acceptance
                            </a>
                        </li>

                        <li>
                            <a href="#about-aabitech"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                About AabiTech
                            </a>
                        </li>

                        <li>
                            <a href="#eligibility"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Eligibility
                            </a>
                        </li>

                        <li>
                            <a href="#acceptable-use"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Acceptable use
                            </a>
                        </li>

                        <li>
                            <a href="#tools-and-results"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Tools & results
                            </a>
                        </li>

                        <li>
                            <a href="#sensitive-information"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Sensitive information
                            </a>
                        </li>

                        <li>
                            <a href="#local-processing"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Local processing
                            </a>
                        </li>

                        <li>
                            <a href="#intellectual-property"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Intellectual property
                            </a>
                        </li>

                        <li>
                            <a href="#third-party-services"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Third-party services
                            </a>
                        </li>

                        <li>
                            <a href="#availability"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Availability
                            </a>
                        </li>

                        <li>
                            <a href="#accuracy"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Accuracy
                            </a>
                        </li>

                        <li>
                            <a href="#disclaimer"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Disclaimer
                            </a>
                        </li>

                        <li>
                            <a href="#limitation-of-liability"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Liability
                            </a>
                        </li>

                        <li>
                            <a href="#privacy"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Privacy
                            </a>
                        </li>

                        <li>
                            <a href="#changes-to-terms"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Changes
                            </a>
                        </li>

                        <li>
                            <a href="#governing-law"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Governing law
                            </a>
                        </li>

                        <li>
                            <a href="#contact"
                               class="block rounded-md px-2.5 py-1.5 text-zinc-600 transition hover:bg-white hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Contact
                            </a>
                        </li>
                    </ol>
                </nav>
            </aside>
        </div>
    </div>
</div>