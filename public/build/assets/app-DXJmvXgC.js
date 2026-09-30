document.addEventListener(`click`,async t=>{let n=t.target.closest(`[data-copy], [data-copy-text], [data-copy-target]`);if(!n||n.dataset.globalCopyHandled===`true`)return;let r=n.dataset.copyText||``;if(!r&&n.dataset.copyTarget){let e=document.querySelector(n.dataset.copyTarget);e&&(r=`value`in e?e.value:e.textContent)}if(!r)return;n.dataset.globalCopyHandled=`true`;let i=n.innerHTML;try{await navigator.clipboard.writeText(r),n.innerHTML=`
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
        `,n.classList.add(`is-copied`),e(`Copied to clipboard`),setTimeout(()=>{n.innerHTML=i,n.classList.remove(`is-copied`),n.dataset.globalCopyHandled=`false`},1800)}catch{n.dataset.globalCopyHandled=`false`,e(`Unable to copy`)}}),document.addEventListener(`click`,e=>{let t=e.target.closest(`[data-active-group]`);if(!t||t.disabled)return;let n=t.dataset.activeGroup;if(!n)return;let r=CSS.escape(n);document.querySelectorAll(`[data-active-group="${r}"]`).forEach(e=>{e.classList.remove(`is-active`),e.removeAttribute(`data-active`),e.setAttribute(`aria-selected`,`false`)}),t.classList.add(`is-active`),t.setAttribute(`data-active`,`true`),t.hasAttribute(`aria-selected`)&&t.setAttribute(`aria-selected`,`true`)});function e(e){let t=document.getElementById(`aabi-global-toast`);t||(t=document.createElement(`div`),t.id=`aabi-global-toast`,t.className=[`fixed`,`bottom-5`,`right-5`,`z-[9999]`,`translate-y-2`,`rounded-xl`,`border`,`border-slate-700`,`bg-slate-900`,`px-4`,`py-3`,`text-sm`,`font-medium`,`text-white`,`opacity-0`,`shadow-2xl`,`transition-all`,`duration-200`].join(` `),document.body.appendChild(t)),t.textContent=e,requestAnimationFrame(()=>{t.classList.remove(`translate-y-2`,`opacity-0`)}),clearTimeout(t._aabiTimer),t._aabiTimer=setTimeout(()=>{t.classList.add(`translate-y-2`,`opacity-0`)},1800)}