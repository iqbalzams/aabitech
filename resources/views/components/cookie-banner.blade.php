<div
    x-data="{
        visible: false,

        init() {
            try {
                this.visible = localStorage.getItem('aabitech_cookie_notice') !== 'dismissed';
            } catch (error) {
                this.visible = true;
            }
        },

        dismiss() {
            this.visible = false;

            try {
                localStorage.setItem('aabitech_cookie_notice', 'dismissed');
            } catch (error) {
                // Continue normally if localStorage is unavailable.
            }
        }
    }"
    x-cloak
    x-show="visible"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-3"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-3"
    class="fixed inset-x-0 bottom-0 z-[60] px-3 pb-3 sm:px-5 sm:pb-5"
    role="dialog"
    aria-labelledby="cookie-notice-title"
    aria-describedby="cookie-notice-description"
>
    <div
        class="mx-auto flex max-w-4xl flex-col gap-4 rounded-2xl border border-white/10 bg-slate-950 p-4 shadow-2xl shadow-slate-950/30 sm:flex-row sm:items-center sm:justify-between sm:p-5"
    >
        <div class="flex min-w-0 items-start gap-3">
            {{-- Privacy icon --}}
            <div
                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-400"
                aria-hidden="true"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3.5 19 6v5.5c0 4.6-2.9 7.9-7 9-4.1-1.1-7-4.4-7-9V6l7-2.5Z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.5 12.2 11.2 14l3.5-3.7"
                    />
                </svg>
            </div>

            <div class="min-w-0">
                <p id="cookie-notice-title" class="text-sm font-semibold text-white">
                    Privacy & cookies
                </p>

                <p
                    id="cookie-notice-description"
                    class="mt-1 max-w-2xl text-xs leading-5 text-slate-400 sm:text-sm"
                >
                    AabiTech uses essential cookies and browser storage to keep the
                    site working and remember certain preferences.

                    <a
                        href="{{ route('privacy-policy') }}"
                        wire:navigate
                        class="font-medium text-indigo-400 underline decoration-indigo-400/40 underline-offset-2 transition hover:text-indigo-300 hover:decoration-indigo-300"
                    >
                        Learn more
                    </a>
                </p>
            </div>
        </div>

        <div class="flex shrink-0 items-center justify-end gap-2 sm:pl-4">
            <a
                href="{{ route('privacy-policy') }}"
                wire:navigate
                class="inline-flex h-9 cursor-pointer items-center rounded-lg border border-white/10 px-3 text-xs font-medium text-slate-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white"
            >
                Privacy Policy
            </a>

            <button
                type="button"
                x-on:click="dismiss()"
                class="inline-flex h-9 cursor-pointer items-center rounded-lg bg-indigo-500 px-4 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-950"
                aria-label="Dismiss privacy and cookie notice"
            >
                Got it
            </button>
        </div>
    </div>
</div>