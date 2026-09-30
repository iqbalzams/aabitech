/*
|--------------------------------------------------------------------------
| AabiTech Global UI Interaction Layer
|--------------------------------------------------------------------------
|
| Lightweight global interactions shared by AabiTech tools:
|
| 1. Copy buttons
| 2. Active-state controls
| 3. Global toast notifications
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Global Copy Handling
|--------------------------------------------------------------------------
|
| Supported attributes:
|
| data-copy-text="..."
| data-copy-target="#selector"
| data-copy
|
| Components that already handle their own copy logic can opt out by
| setting:
|
| data-global-copy-handled="true"
|
*/

document.addEventListener('click', async (event) => {
    const copyButton = event.target.closest(
        '[data-copy], [data-copy-text], [data-copy-target]'
    );

    if (!copyButton) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Do not interfere with a component that already owns copy handling.
    |--------------------------------------------------------------------------
    */

    if (copyButton.dataset.globalCopyHandled === 'true') {
        return;
    }

    let text = copyButton.dataset.copyText || '';

    /*
    |--------------------------------------------------------------------------
    | Resolve text from a target element when requested.
    |--------------------------------------------------------------------------
    */

    if (!text && copyButton.dataset.copyTarget) {
        const target = document.querySelector(
            copyButton.dataset.copyTarget
        );

        if (target) {
            text = 'value' in target
                ? target.value
                : target.textContent;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | No explicit copy value.
    |
    | Leave the button alone so an existing component-level copy()
    | implementation can handle it.
    |--------------------------------------------------------------------------
    */

    if (!text) {
        return;
    }

    copyButton.dataset.globalCopyHandled = 'true';

    const originalHTML = copyButton.innerHTML;

    try {
        await navigator.clipboard.writeText(text);

        copyButton.innerHTML = `
            <span class="inline-flex items-center gap-1.5">
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Copied
            </span>
        `;

        copyButton.classList.add('is-copied');

        showAabiToast('Copied to clipboard');

        setTimeout(() => {
            copyButton.innerHTML = originalHTML;
            copyButton.classList.remove('is-copied');
            copyButton.dataset.globalCopyHandled = 'false';
        }, 1800);

    } catch (error) {
        copyButton.dataset.globalCopyHandled = 'false';

        showAabiToast('Unable to copy');
    }
});


/*
|--------------------------------------------------------------------------
| Global Active-State Handling
|--------------------------------------------------------------------------
|
| Any clickable element with:
|
| data-active-group="some-group"
|
| becomes active when clicked.
|
| The active element receives:
|
| .is-active
| data-active="true"
| aria-selected="true"
|
| Other controls in the same group are cleared.
|--------------------------------------------------------------------------
*/

document.addEventListener('click', (event) => {
    const control = event.target.closest('[data-active-group]');

    if (!control || control.disabled) {
        return;
    }

    const group = control.dataset.activeGroup;

    if (!group) {
        return;
    }

    const escapedGroup = CSS.escape(group);

    document
        .querySelectorAll(`[data-active-group="${escapedGroup}"]`)
        .forEach((item) => {
            item.classList.remove('is-active');
            item.removeAttribute('data-active');
            item.setAttribute('aria-selected', 'false');
        });

    control.classList.add('is-active');
    control.setAttribute('data-active', 'true');

    /*
    |--------------------------------------------------------------------------
    | Only add aria-selected when the control already participates in
    | an aria-selected pattern. This avoids changing unrelated controls.
    |--------------------------------------------------------------------------
    */

    if (control.hasAttribute('aria-selected')) {
        control.setAttribute('aria-selected', 'true');
    }
});


/*
|--------------------------------------------------------------------------
| Global Toast
|--------------------------------------------------------------------------
*/

function showAabiToast(message) {
    let toast = document.getElementById('aabi-global-toast');

    if (!toast) {
        toast = document.createElement('div');

        toast.id = 'aabi-global-toast';

        toast.className = [
            'fixed',
            'bottom-5',
            'right-5',
            'z-[9999]',
            'translate-y-2',
            'rounded-xl',
            'border',
            'border-slate-700',
            'bg-slate-900',
            'px-4',
            'py-3',
            'text-sm',
            'font-medium',
            'text-white',
            'opacity-0',
            'shadow-2xl',
            'transition-all',
            'duration-200',
        ].join(' ');

        document.body.appendChild(toast);
    }

    toast.textContent = message;

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    });

    clearTimeout(toast._aabiTimer);

    toast._aabiTimer = setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
    }, 1800);
}