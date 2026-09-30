<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public string $reason = 'general';

    /**
     * Contact-page SEO is intentionally page-local.
     *
     * The shared layouts.app continues to render the actual
     * <title>, meta description, canonical, etc. through the
     * existing centralized SEO layer.
     */
    public function getSeoProperty(): array
    {
        return [
            'title' => 'Contact AabiTech | Questions, Feedback & Tool Requests',
            'description' => 'Contact AabiTech for tool questions, feedback, bug reports, feature requests, and suggestions for new online tools.',
            'canonical' => url('/contact'),
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'email' => [
                'required',
                'email:rfc',
                'max:254',
            ],

            'reason' => [
                'required',
                'string',
                'in:general,tool-question,bug-report,feature-request,feedback',
            ],

            'subject' => [
                'required',
                'string',
                'min:3',
                'max:150',
            ],

            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Your name must contain at least 2 characters.',

            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',

            'reason.required' => 'Please select a reason for contacting us.',

            'subject.required' => 'Please enter a subject.',
            'subject.min' => 'The subject must contain at least 3 characters.',

            'message.required' => 'Please enter your message.',
            'message.min' => 'Please provide a little more detail.',
            'message.max' => 'Your message may not exceed 5,000 characters.',
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $recipient = config('services.aabitech.contact_email')
            ?: env('CONTACT_EMAIL');

        if (! $recipient) {
            report(new \RuntimeException(
                'AabiTech contact form recipient is not configured.'
            ));

            $this->addError(
                'form',
                'The contact form is temporarily unavailable. Please use the direct email option instead.'
            );

            return;
        }

        $reasonLabels = [
            'general' => 'General inquiry',
            'tool-question' => 'Question about a tool',
            'bug-report' => 'Report a problem',
            'feature-request' => 'Request a new tool or feature',
            'feedback' => 'Feedback or suggestion',
        ];

        $reasonLabel = $reasonLabels[$validated['reason']]
            ?? 'General inquiry';

        $senderName = Str::of($validated['name'])
            ->trim()
            ->limit(100, '')
            ->toString();

        $senderEmail = Str::of($validated['email'])
            ->trim()
            ->toString();

        $subject = Str::of($validated['subject'])
            ->trim()
            ->limit(150, '')
            ->toString();

        $message = Str::of($validated['message'])
            ->trim()
            ->limit(5000, '')
            ->toString();

        try {
            Mail::raw(
                implode("\n", [
                    'New AabiTech contact form message',
                    '',
                    'Name: ' . $senderName,
                    'Email: ' . $senderEmail,
                    'Reason: ' . $reasonLabel,
                    'Subject: ' . $subject,
                    '',
                    'Message:',
                    $message,
                ]),
                function ($mail) use (
                    $recipient,
                    $senderName,
                    $senderEmail,
                    $subject
                ) {
                    $mail->to($recipient)
                        ->replyTo($senderEmail, $senderName)
                        ->subject(
                            'AabiTech Contact: ' . $subject
                        );
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            $this->addError(
                'form',
                'We could not send your message right now. Please try again or contact us directly by email.'
            );

            return;
        }

        $this->reset([
            'name',
            'email',
            'subject',
            'message',
        ]);

        $this->reason = 'general';

        session()->flash(
            'contact-success',
            'Thanks for contacting AabiTech. Your message has been sent successfully.'
        );
    }
};
?>

<div class="min-h-screen bg-white dark:bg-zinc-950">
    <main
        id="main-content"
        class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12"
    >
        {{-- Page heading --}}
        <header class="mx-auto max-w-3xl text-center">
            <div
                class="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:border-indigo-900/60 dark:bg-indigo-950/40 dark:text-indigo-300"
            >
                <svg
                    aria-hidden="true"
                    class="h-3.5 w-3.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6A8.38 8.38 0 0 1 12.5 3h.5a8.5 8.5 0 0 1 8 8v.5Z"
                    />
                </svg>

                Contact AabiTech
            </div>

            <h1
                class="text-3xl font-bold tracking-tight text-zinc-950 dark:text-white sm:text-4xl"
            >
                How can we help?
            </h1>

            <p
                class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400 sm:text-base"
            >
                Have a question about a tool, found a problem, or have an idea
                for something AabiTech should build? Send us a message.
            </p>
        </header>

        {{-- Contact workspace --}}
        <div class="mx-auto mt-8 grid max-w-6xl gap-6 lg:grid-cols-[minmax(0,1.55fr)_minmax(300px,0.8fr)]">
            {{-- Form --}}
            <section
                aria-labelledby="contact-form-heading"
                class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
            >
                <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-800 sm:px-6">
                    <h2
                        id="contact-form-heading"
                        class="text-base font-semibold text-zinc-950 dark:text-white"
                    >
                        Send us a message
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        We welcome questions, feedback, bug reports, and ideas
                        for new tools.
                    </p>
                </div>

                <form
                    wire:submit="submit"
                    novalidate
                    class="space-y-5 p-5 sm:p-6"
                >
                    @if (session('contact-success'))
                        <div
                            role="status"
                            aria-live="polite"
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300"
                        >
                            <div class="flex items-start gap-3">
                                <svg
                                    aria-hidden="true"
                                    class="mt-0.5 h-5 w-5 shrink-0"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m5 12 4 4L19 6"
                                    />
                                </svg>

                                <p>{{ session('contact-success') }}</p>
                            </div>
                        </div>
                    @endif

                    @error('form')
                        <div
                            role="alert"
                            aria-live="assertive"
                            class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300"
                        >
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="grid gap-5 sm:grid-cols-2">
                        {{-- Name --}}
                        <div>
                            <label
                                for="contact-name"
                                class="mb-1.5 block text-sm font-medium text-zinc-800 dark:text-zinc-200"
                            >
                                Name
                                <span
                                    class="text-red-500"
                                    aria-hidden="true"
                                >*</span>
                            </label>

                            <input
                                id="contact-name"
                                name="name"
                                type="text"
                                wire:model="name"
                                autocomplete="name"
                                maxlength="100"
                                required
                                aria-required="true"
                                aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                @if ($errors->has('name')) aria-describedby="contact-name-error" @endif
                                class="block w-full rounded-xl border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:placeholder:text-zinc-500"
                                placeholder="Your name"
                            />

                            @error('name')
                                <p
                                    id="contact-name-error"
                                    class="mt-1.5 text-xs text-red-600 dark:text-red-400"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label
                                for="contact-email"
                                class="mb-1.5 block text-sm font-medium text-zinc-800 dark:text-zinc-200"
                            >
                                Email
                                <span
                                    class="text-red-500"
                                    aria-hidden="true"
                                >*</span>
                            </label>

                            <input
                                id="contact-email"
                                name="email"
                                type="email"
                                wire:model="email"
                                autocomplete="email"
                                inputmode="email"
                                maxlength="254"
                                required
                                aria-required="true"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                @if ($errors->has('email')) aria-describedby="contact-email-error" @endif
                                class="block w-full rounded-xl border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:placeholder:text-zinc-500"
                                placeholder="you@example.com"
                            />

                            @error('email')
                                <p
                                    id="contact-email-error"
                                    class="mt-1.5 text-xs text-red-600 dark:text-red-400"
                                >
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Reason --}}
                    <div>
                        <label
                            for="contact-reason"
                            class="mb-1.5 block text-sm font-medium text-zinc-800 dark:text-zinc-200"
                        >
                            What can we help with?
                            <span
                                class="text-red-500"
                                aria-hidden="true"
                            >*</span>
                        </label>

                        <select
                            id="contact-reason"
                            name="reason"
                            wire:model="reason"
                            required
                            aria-required="true"
                            aria-invalid="{{ $errors->has('reason') ? 'true' : 'false' }}"
                            @if ($errors->has('reason')) aria-describedby="contact-reason-error" @endif
                            class="block w-full rounded-xl border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white"
                        >
                            <option value="general">General inquiry</option>
                            <option value="tool-question">Question about a tool</option>
                            <option value="bug-report">Report a problem</option>
                            <option value="feature-request">Request a new tool or feature</option>
                            <option value="feedback">Feedback or suggestion</option>
                        </select>

                        @error('reason')
                            <p
                                id="contact-reason-error"
                                class="mt-1.5 text-xs text-red-600 dark:text-red-400"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label
                            for="contact-subject"
                            class="mb-1.5 block text-sm font-medium text-zinc-800 dark:text-zinc-200"
                        >
                            Subject
                            <span
                                class="text-red-500"
                                aria-hidden="true"
                            >*</span>
                        </label>

                        <input
                            id="contact-subject"
                            name="subject"
                            type="text"
                            wire:model="subject"
                            maxlength="150"
                            required
                            aria-required="true"
                            aria-invalid="{{ $errors->has('subject') ? 'true' : 'false' }}"
                            @if ($errors->has('subject')) aria-describedby="contact-subject-error" @endif
                            class="block w-full rounded-xl border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:placeholder:text-zinc-500"
                            placeholder="How can we help?"
                        />

                        @error('subject')
                            <p
                                id="contact-subject-error"
                                class="mt-1.5 text-xs text-red-600 dark:text-red-400"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <label
                                for="contact-message"
                                class="block text-sm font-medium text-zinc-800 dark:text-zinc-200"
                            >
                                Message
                                <span
                                    class="text-red-500"
                                    aria-hidden="true"
                                >*</span>
                            </label>

                            <span class="text-xs text-zinc-400 dark:text-zinc-500">
                                Max 5,000 characters
                            </span>
                        </div>

                        <textarea
                            id="contact-message"
                            name="message"
                            wire:model="message"
                            rows="7"
                            maxlength="5000"
                            required
                            aria-required="true"
                            aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}"
                            @if ($errors->has('message')) aria-describedby="contact-message-error" @endif
                            class="block w-full resize-y rounded-xl border border-zinc-300 bg-white px-3.5 py-3 text-sm leading-6 text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 dark:border-zinc-700 dark:bg-zinc-950 dark:text-white dark:placeholder:text-zinc-500"
                            placeholder="Tell us how we can help..."
                        ></textarea>

                        @error('message')
                            <p
                                id="contact-message-error"
                                class="mt-1.5 text-xs text-red-600 dark:text-red-400"
                            >
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-800 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs leading-5 text-zinc-500 dark:text-zinc-400">
                            Please do not include passwords, payment details,
                            or other sensitive information.
                        </p>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="inline-flex min-h-10 shrink-0 cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-zinc-900"
                        >
                            <span wire:loading.remove wire:target="submit">
                                Send message
                            </span>

                            <span
                                wire:loading
                                wire:target="submit"
                                class="inline-flex items-center gap-2"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="h-4 w-4 animate-spin"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="3"
                                    />
                                    <path
                                        class="opacity-90"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4Z"
                                    />
                                </svg>

                                Sending...
                            </span>

                            <svg
                                wire:loading.remove
                                wire:target="submit"
                                aria-hidden="true"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21.5 3.5 10 15"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21.5 3.5-6.2 17-5.3-5.3-6.5-2.2 18-9.5Z"
                                />
                            </svg>
                        </button>
                    </div>
                </form>
            </section>

            {{-- Direct contact --}}
            <aside class="space-y-4" aria-label="Direct contact options">
                <section class="rounded-2xl border border-zinc-200 bg-zinc-50/70 p-5 dark:border-zinc-800 dark:bg-zinc-900/60">
                    <h2 class="text-base font-semibold text-zinc-950 dark:text-white">
                        Contact directly
                    </h2>

                    <p class="mt-1 text-sm leading-5 text-zinc-500 dark:text-zinc-400">
                        Prefer email or WhatsApp? You can reach AabiTech directly.
                    </p>

                    <div class="mt-5 space-y-3">
                        {{-- Email --}}
                        <a
                            href="mailto:{{ config('services.aabitech.contact_email', env('CONTACT_EMAIL', '')) }}"
                            class="group flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-zinc-200 bg-white px-3.5 py-3 transition hover:border-indigo-200 hover:bg-indigo-50/50 dark:border-zinc-800 dark:bg-zinc-950 dark:hover:border-indigo-900 dark:hover:bg-indigo-950/30"
                        >
                            <span
                                aria-hidden="true"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400"
                            >
                                <svg
                                    class="h-4.5 w-4.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 5h16v14H4z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m4 7 8 6 8-6"
                                    />
                                </svg>
                            </span>

                            <span class="min-w-0">
                                <span class="block text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    Email
                                </span>

                                <span class="block truncate text-sm font-medium text-zinc-900 group-hover:text-indigo-700 dark:text-zinc-100 dark:group-hover:text-indigo-300">
                                    {{ config('services.aabitech.contact_email', env('CONTACT_EMAIL', '')) ?: 'Email AabiTech' }}
                                </span>
                            </span>
                        </a>

                        {{-- WhatsApp --}}
                        <a
                            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', env('AABITECH_WHATSAPP', '')) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-zinc-200 bg-white px-3.5 py-3 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-zinc-800 dark:bg-zinc-950 dark:hover:border-emerald-900 dark:hover:bg-emerald-950/30"
                        >
                            <span
                                aria-hidden="true"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400"
                            >
                                <svg
                                    class="h-4.5 w-4.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M20 11.5a8.5 8.5 0 0 1-12.9 7.3L4 20l1.2-3.2A8.5 8.5 0 1 1 20 11.5Z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.7 8.2c.2-.4.4-.4.7-.4h.4c.2 0 .3.1.4.3l.8 1.8c.1.2.1.4-.1.6l-.6.7c.6 1.1 1.5 2 2.7 2.5l.6-.7c.2-.2.4-.2.6-.1l1.8.8c.2.1.3.2.3.4v.4c0 .3 0 .5-.4.7-.4.2-1 .3-1.5.1-2.9-.9-5.2-3.1-6.4-5.8-.2-.5-.1-1.1.1-1.5Z"
                                    />
                                </svg>
                            </span>

                            <span>
                                <span class="block text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    WhatsApp
                                </span>

                                <span class="block text-sm font-medium text-zinc-900 group-hover:text-emerald-700 dark:text-zinc-100 dark:group-hover:text-emerald-300">
                                    Message us on WhatsApp
                                </span>
                            </span>
                        </a>
                    </div>
                </section>

                {{-- Location --}}
                <section
                    aria-labelledby="location-heading"
                    class="overflow-hidden rounded-2xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900"
                >
                    <div class="p-5">
                        <h2
                            id="location-heading"
                            class="text-base font-semibold text-zinc-950 dark:text-white"
                        >
                            Find us
                        </h2>

                        <p class="mt-1 text-sm leading-5 text-zinc-500 dark:text-zinc-400">
                            AabiTech is an online platform. For general questions
                            and support, email or WhatsApp is the quickest way
                            to reach us.
                        </p>
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-800">
                        <iframe
                            title="AabiTech location on Google Maps"
                            src="{{ env('AABITECH_GOOGLE_MAPS_EMBED_URL', 'https://www.google.com/maps?q=Lahore,Pakistan&output=embed') }}"
                            class="h-52 w-full border-0"
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                    </div>
                </section>
            </aside>
        </div>

        {{-- Helpful links --}}
        <section
            aria-labelledby="before-contact-heading"
            class="mx-auto mt-8 max-w-6xl rounded-2xl border border-zinc-200 bg-zinc-50/70 p-5 dark:border-zinc-800 dark:bg-zinc-900/50 sm:p-6"
        >
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2
                        id="before-contact-heading"
                        class="text-sm font-semibold text-zinc-950 dark:text-white"
                    >
                        Looking for a tool?
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        You may find what you need in the AabiTech tools directory.
                    </p>
                </div>

                <a
                    href="{{ route('tools') }}"
                    wire:navigate
                    class="inline-flex min-h-10 cursor-pointer items-center justify-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:border-indigo-300 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-indigo-800 dark:hover:text-indigo-300"
                >
                    Browse all tools

                    <svg
                        aria-hidden="true"
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 12h14"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m13 6 6 6-6 6"
                        />
                    </svg>
                </a>
            </div>
        </section>
    </main>
</div>