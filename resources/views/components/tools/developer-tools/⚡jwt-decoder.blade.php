<?php

use Livewire\Component;

new class extends Component
{
    // JWT decoding, inspection, analysis and optional verification are performed client-side.
};
?>

<div
    x-data="aabiJwtDecoder()"
    x-init="init()"
    x-cloak
    @keydown.window="handleShortcut($event)"
    class="w-full space-y-4"
>
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/70 px-4 py-3">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" @click="decode()" class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700">Decode JWT</button>
                    <button type="button" @click="loadSample()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Example</button>
                    <button type="button" @click="clearAll()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Clear</button>
                    <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700">
                        <input type="file" accept=".jwt,.txt,application/jwt,text/plain" class="hidden" @change="handleFile($event)">
                        <span>Import JWT</span>
                    </label>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2 py-1 text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Browser-local
                    </span>
                    <span>Ctrl/Cmd + Enter decode</span>
                    <span>Esc clear</span>
                </div>
            </div>
        </div>

        <div class="grid min-w-0 lg:grid-cols-[minmax(0,0.92fr)_minmax(0,1.08fr)]">
            <section class="min-w-0 border-b border-slate-200 lg:border-b-0 lg:border-r">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-2.5">
                    <div>
                        <label for="jwt-token" class="text-xs font-semibold text-slate-800">JWT Token</label>
                        <p class="mt-0.5 text-[11px] text-slate-500">Paste a compact JWT or a <code>Bearer</code> value.</p>
                    </div>
                    <span class="text-[11px] text-slate-400" x-text="tokenStats"></span>
                </div>
                <div class="p-4">
                    <textarea
                        id="jwt-token"
                        x-model="token"
                        @input="onTokenInput()"
                        @paste="setTimeout(() => onTokenInput(), 0)"
                        rows="13"
                        spellcheck="false"
                        autocomplete="off"
                        autocapitalize="off"
                        class="block w-full resize-y rounded-lg border border-slate-300 bg-white px-3 py-3 font-mono text-[12px] leading-5 text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                        placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
                    ></textarea>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <template x-for="part in tokenParts" :key="part.key">
                            <button
                                type="button"
                                @click="copyText(part.value, part.label + ' copied')"
                                class="group min-w-0 flex-1 rounded-lg border px-2.5 py-2 text-left text-[10px] transition hover:shadow-sm"
                                :class="part.className"
                                :title="'Copy ' + part.label"
                            >
                                <span class="block font-semibold" x-text="part.label"></span>
                                <span class="mt-1 block truncate font-mono opacity-80" x-text="part.value || '—'"></span>
                            </button>
                        </template>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <button type="button" @click="decode()" class="rounded-md border border-indigo-200 bg-indigo-50 px-2.5 py-1.5 text-[11px] font-medium text-indigo-700 hover:bg-indigo-100">Decode</button>
                        <button type="button" @click="validate()" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-700 hover:bg-slate-50">Validate structure</button>
                        <button type="button" @click="inspect()" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-700 hover:bg-slate-50">Inspect claims</button>
                        <button type="button" @click="copyText(token.replace(/^\s*Bearer\s+/i, '').trim(), 'JWT copied')" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-700 hover:bg-slate-50">Copy token</button>
                    </div>

                    <template x-if="statusMessage">
                        <div class="mt-3 rounded-lg border px-3 py-2 text-xs" :class="statusClass" x-text="statusMessage"></div>
                    </template>
                </div>
            </section>

            <section class="min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-2.5">
                    <div class="flex min-w-0 items-center gap-1 overflow-x-auto" role="tablist" aria-label="JWT output">
                        <template x-for="tab in tabs" :key="tab">
                            <button
                                type="button"
                                role="tab"
                                :aria-selected="activeTab === tab"
                                @click="activeTab = tab"
                                class="compact-tab"
                                :data-active-group="'jwt-output'"
                                :class="{ 'is-active': activeTab === tab }"
                                x-text="tab"
                            ></button>
                        </template>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="copyHeader()" class="rounded-md border border-slate-200 bg-white px-2 py-1.5 text-[10px] font-medium text-slate-700 hover:bg-slate-50">Copy Header</button>
                        <button type="button" @click="copyPayload()" class="rounded-md border border-slate-200 bg-white px-2 py-1.5 text-[10px] font-medium text-slate-700 hover:bg-slate-50">Copy Payload</button>
                        <button type="button" @click="downloadDecodedJson()" class="rounded-md border border-slate-200 bg-white px-2 py-1.5 text-[10px] font-medium text-slate-700 hover:bg-slate-50">Download JSON</button>
                    </div>
                </div>

                <div class="p-4">
                    <template x-if="activeTab === 'Decoded'">
                        <div class="space-y-3">
                            <div class="grid gap-2 sm:grid-cols-3">
                                <div class="rounded-lg border border-rose-200 bg-rose-50 p-3">
                                    <div class="text-[10px] font-semibold uppercase tracking-wide text-rose-600">Header</div>
                                    <div class="mt-1 font-mono text-[11px] text-slate-800" x-text="headerSummary"></div>
                                </div>
                                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                                    <div class="text-[10px] font-semibold uppercase tracking-wide text-amber-700">Payload</div>
                                    <div class="mt-1 text-[11px] text-slate-800" x-text="payloadSummary"></div>
                                </div>
                                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3">
                                    <div class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700">Signature</div>
                                    <div class="mt-1 font-mono text-[11px] text-slate-800" x-text="signatureSummary"></div>
                                </div>
                            </div>

                            <div class="grid gap-3 xl:grid-cols-2">
                                <div class="overflow-hidden rounded-lg border border-rose-200">
                                    <div class="flex items-center justify-between border-b border-rose-200 bg-rose-50 px-3 py-2">
                                        <span class="text-xs font-semibold text-rose-800">Header JSON</span>
                                        <button type="button" @click="copyHeader()" class="text-[10px] font-medium text-rose-700 hover:underline">Copy</button>
                                    </div>
                                    <pre class="max-h-[330px] overflow-auto bg-slate-950 p-3 font-mono text-[11px] leading-5 text-slate-100" x-text="headerJson || '—'"></pre>
                                </div>
                                <div class="overflow-hidden rounded-lg border border-amber-200">
                                    <div class="flex items-center justify-between border-b border-amber-200 bg-amber-50 px-3 py-2">
                                        <span class="text-xs font-semibold text-amber-900">Payload JSON</span>
                                        <button type="button" @click="copyPayload()" class="text-[10px] font-medium text-amber-800 hover:underline">Copy</button>
                                    </div>
                                    <pre class="max-h-[330px] overflow-auto bg-slate-950 p-3 font-mono text-[11px] leading-5 text-slate-100" x-text="payloadJson || '—'"></pre>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-emerald-200">
                                <div class="flex items-center justify-between border-b border-emerald-200 bg-emerald-50 px-3 py-2">
                                    <span class="text-xs font-semibold text-emerald-800">Signature Segment</span>
                                    <button type="button" @click="copyText(signature, 'Signature copied')" class="text-[10px] font-medium text-emerald-700 hover:underline">Copy</button>
                                </div>
                                <pre class="max-h-[180px] overflow-auto whitespace-pre-wrap break-all bg-slate-950 p-3 font-mono text-[11px] leading-5 text-emerald-200" x-text="signature || 'No signature segment' "></pre>
                            </div>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Claims'">
                        <div class="space-y-3">
                            <div class="grid gap-2 sm:grid-cols-3">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-500">Issuer</div><div class="mt-1 truncate text-xs font-medium text-slate-800" x-text="claimValue('iss') || '—'"></div></div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-500">Subject</div><div class="mt-1 truncate text-xs font-medium text-slate-800" x-text="claimValue('sub') || '—'"></div></div>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-500">Audience</div><div class="mt-1 truncate text-xs font-medium text-slate-800" x-text="claimValue('aud') || '—'"></div></div>
                            </div>
                            <div class="overflow-x-auto rounded-lg border border-slate-200">
                                <table class="min-w-full text-left text-[11px]">
                                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500">
                                        <tr><th class="px-3 py-2">Claim</th><th class="px-3 py-2">Type</th><th class="px-3 py-2">Value</th><th class="px-3 py-2">Interpretation</th><th class="px-3 py-2"></th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <template x-if="!claims.length"><tr><td colspan="5" class="px-3 py-8 text-center text-slate-400">Decode a JWT to inspect claims.</td></tr></template>
                                        <template x-for="claim in claims" :key="claim.key">
                                            <tr class="align-top hover:bg-slate-50/70">
                                                <td class="px-3 py-2 font-mono font-semibold text-slate-800" x-text="claim.key"></td>
                                                <td class="px-3 py-2 text-slate-500" x-text="claim.type"></td>
                                                <td class="max-w-[240px] px-3 py-2 font-mono text-slate-700 break-words" x-text="claim.displayValue"></td>
                                                <td class="max-w-[300px] px-3 py-2 text-slate-500" x-text="claim.interpretation || '—'"></td>
                                                <td class="px-3 py-2"><button type="button" @click="copyText(claim.rawValue, claim.key + ' copied')" class="text-indigo-600 hover:underline">Copy</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Timeline'">
                        <div class="space-y-4">
                            <div class="grid gap-2 md:grid-cols-3">
                                <template x-for="item in timelineCards" :key="item.key">
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500" x-text="item.label"></span><span class="text-[10px] font-mono text-slate-400" x-text="item.claim"></span></div>
                                        <div class="mt-2 text-xs font-medium text-slate-800" x-text="item.date || 'Not provided'"></div>
                                        <div class="mt-1 text-[11px] text-slate-500" x-text="item.relative || '—'"></div>
                                    </div>
                                </template>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between"><span class="text-xs font-semibold text-slate-800">Token timeline</span><span class="text-[11px] text-slate-500" x-text="expiryStatus"></span></div>
                                <div class="relative h-16">
                                    <div class="absolute left-0 right-0 top-7 h-1 rounded-full bg-slate-200"></div>
                                    <template x-for="point in timelinePoints" :key="point.key">
                                        <div class="absolute top-0 -translate-x-1/2" :style="'left:' + point.position + '%'">
                                            <div class="mx-auto h-4 w-4 rounded-full border-2 border-white shadow" :class="point.dotClass"></div>
                                            <div class="mt-1 whitespace-nowrap text-center text-[9px] font-medium text-slate-600" x-text="point.label"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Security Audit'">
                        <div class="space-y-3">
                            <template x-if="!audit.length"><div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-8 text-center text-xs text-slate-400">Decode a token to run the security audit.</div></template>
                            <template x-for="item in audit" :key="item.id">
                                <div class="flex gap-3 rounded-lg border p-3" :class="auditClass(item.level)">
                                    <div class="mt-0.5 h-2 w-2 flex-none rounded-full" :class="auditDot(item.level)"></div>
                                    <div class="min-w-0"><div class="text-xs font-semibold" x-text="item.title"></div><div class="mt-1 text-[11px] leading-5 opacity-80" x-text="item.message"></div></div>
                                </div>
                            </template>
                            <div class="flex justify-end"><button type="button" @click="downloadAudit()" class="rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-slate-700 hover:bg-slate-50">Download Audit Report</button></div>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Verify'">
                        <div class="space-y-4">
                            <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2.5 text-[11px] leading-5 text-amber-900">
                                Decoding only reads the token. Verification cryptographically checks its signature. Verification is optional and remains in your browser. If a JWKS URL is used, only the public-key request leaves the browser; the JWT itself is not sent.
                            </div>
                            <div class="grid gap-3 md:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-[11px] font-semibold text-slate-700">Verification key type</label>
                                    <select x-model="verifyKeyType" @change="resetVerificationResult()" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                                        <option value="secret">HMAC secret</option>
                                        <option value="publicKey">Public key (PEM/JWK)</option>
                                        <option value="jwks">JWKS JSON / URL</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-[11px] font-semibold text-slate-700">Algorithm from token</label>
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-xs text-slate-800" x-text="algorithm || '—'"></div>
                                </div>
                            </div>
                            <template x-if="verifyKeyType === 'secret'">
                                <div><label class="mb-1.5 block text-[11px] font-semibold text-slate-700">HMAC secret</label><textarea x-model="verificationKey" rows="4" spellcheck="false" class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-xs outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" placeholder="Enter the shared secret"></textarea></div>
                            </template>
                            <template x-if="verifyKeyType === 'publicKey'">
                                <div><label class="mb-1.5 block text-[11px] font-semibold text-slate-700">Public key</label><textarea x-model="verificationKey" rows="8" spellcheck="false" class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" placeholder="-----BEGIN PUBLIC KEY-----"></textarea></div>
                            </template>
                            <template x-if="verifyKeyType === 'jwks'">
                                <div class="space-y-2">
                                    <textarea x-model="jwksInput" rows="7" spellcheck="false" class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" placeholder='Paste JWKS JSON here, or enter a JWKS URL'></textarea>
                                    <p class="text-[10px] text-slate-500">Paste JWKS for fully local verification, or use an HTTPS URL. A remote request can reveal network metadata to that key host.</p>
                                </div>
                            </template>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" @click="verifySignature()" class="rounded-md bg-slate-900 px-3 py-2 text-[11px] font-semibold text-white hover:bg-slate-800">Verify Signature</button>
                                <button type="button" @click="resetVerificationResult()" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-700 hover:bg-slate-50">Reset</button>
                            </div>
                            <template x-if="verificationMessage"><div class="rounded-lg border px-3 py-2 text-xs" :class="verificationResultClass" x-text="verificationMessage"></div></template>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Editor'">
                        <div class="space-y-3">
                            <div class="grid gap-3 lg:grid-cols-2">
                                <div>
                                    <div class="mb-1.5 flex items-center justify-between"><label class="text-[11px] font-semibold text-slate-700">Editable payload JSON</label><button type="button" @click="loadPayloadIntoEditor()" class="text-[10px] text-indigo-600 hover:underline">Reload</button></div>
                                    <textarea x-model="payloadEditor" rows="16" spellcheck="false" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 font-mono text-[11px] leading-5 outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"></textarea>
                                </div>
                                <div>
                                    <div class="mb-1.5 flex items-center justify-between"><span class="text-[11px] font-semibold text-slate-700">Unsigned token preview</span><button type="button" @click="copyText(unsignedPreview, 'Unsigned token copied')" class="text-[10px] text-indigo-600 hover:underline">Copy</button></div>
                                    <pre class="min-h-[350px] overflow-auto whitespace-pre-wrap break-all rounded-lg border border-slate-200 bg-slate-950 p-3 font-mono text-[11px] leading-5 text-slate-100" x-text="unsignedPreview || 'Edit payload and click Generate Preview.'"></pre>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="generateUnsignedPreview()" class="rounded-md bg-indigo-600 px-3 py-2 text-[11px] font-semibold text-white hover:bg-indigo-700">Generate Unsigned Preview</button>
                                <button type="button" @click="copyText(unsignedPreview, 'Unsigned token copied')" class="rounded-md border border-slate-200 bg-white px-3 py-2 text-[11px] font-medium text-slate-700 hover:bg-slate-50">Copy Preview</button>
                            </div>
                            <p class="text-[10px] text-slate-500">This preview deliberately has an empty signature. It is not a valid authenticated replacement token.</p>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Compare'">
                        <div class="space-y-3">
                            <div class="grid gap-3 lg:grid-cols-2">
                                <div><label class="mb-1.5 block text-[11px] font-semibold text-slate-700">Token A</label><textarea x-model="compareTokenA" rows="9" spellcheck="false" class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"></textarea></div>
                                <div><label class="mb-1.5 block text-[11px] font-semibold text-slate-700">Token B</label><textarea x-model="compareTokenB" rows="9" spellcheck="false" class="w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-[11px] outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"></textarea></div>
                            </div>
                            <button type="button" @click="compareTokens()" class="rounded-md bg-slate-900 px-3 py-2 text-[11px] font-semibold text-white hover:bg-slate-800">Compare Tokens</button>
                            <template x-if="comparison">
                                <div class="grid gap-3 md:grid-cols-2">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3"><div class="text-[10px] font-semibold uppercase text-slate-500">Structural differences</div><ul class="mt-2 space-y-1 text-[11px] text-slate-700"><template x-for="item in comparison.structural" :key="item"><li x-text="item"></li></template></ul></div>
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3"><div class="text-[10px] font-semibold uppercase text-slate-500">Claim differences</div><ul class="mt-2 space-y-1 text-[11px] text-slate-700"><template x-for="item in comparison.claims" :key="item"><li x-text="item"></li></template></ul></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="activeTab === 'Raw'">
                        <div class="space-y-3">
                            <template x-for="part in tokenParts" :key="part.key">
                                <div class="overflow-hidden rounded-lg border" :class="part.borderClass">
                                    <div class="flex items-center justify-between px-3 py-2" :class="part.headerClass"><span class="text-xs font-semibold" x-text="part.label"></span><button type="button" @click="copyText(part.value, part.label + ' copied')" class="text-[10px] hover:underline">Copy</button></div>
                                    <pre class="max-h-[190px] overflow-auto whitespace-pre-wrap break-all bg-slate-950 p-3 font-mono text-[11px] leading-5 text-slate-100" x-text="part.value || '—'"></pre>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </section>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-3">
            <div><div class="text-xs font-semibold text-slate-800">JWT status</div><div class="mt-0.5 text-[11px] text-slate-500">Decode, inspect and verify independently.</div></div>
            <div class="flex flex-wrap gap-2">
                <span class="rounded-full border px-2.5 py-1 text-[10px] font-semibold" :class="statusPillClass" x-text="overallStatus"></span>
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] text-slate-600" x-text="algorithm ? 'alg: ' + algorithm : 'alg: —'"></span>
                <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] text-slate-600" x-text="tokenType ? 'typ: ' + tokenType : 'typ: —'"></span>
            </div>
        </div>
        <div class="grid gap-3 p-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-slate-200 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-400">Segments</div><div class="mt-1 text-lg font-semibold text-slate-800" x-text="segmentCount"></div></div>
            <div class="rounded-lg border border-slate-200 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-400">Claims</div><div class="mt-1 text-lg font-semibold text-slate-800" x-text="claims.length"></div></div>
            <div class="rounded-lg border border-slate-200 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-400">Expires</div><div class="mt-1 text-xs font-semibold text-slate-800" x-text="formatClaimDate('exp') || '—'"></div></div>
            <div class="rounded-lg border border-slate-200 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-400">Remaining</div><div class="mt-1 text-xs font-semibold text-slate-800" x-text="relativeExpiration"></div></div>
            <div class="rounded-lg border border-slate-200 p-3"><div class="text-[10px] uppercase tracking-wide text-slate-400">Verification</div><div class="mt-1 text-xs font-semibold text-slate-800" x-text="verificationState"></div></div>
        </div>
    </div>

    <style>
        button, [role="button"], a, label, summary, [data-clickable], [data-active-group] { cursor: pointer; }
        button:disabled, [role="button"][aria-disabled="true"] { cursor: not-allowed; }
        [data-active-group].is-active { border-color: rgb(129 140 248) !important; background: rgb(238 242 254) !important; color: rgb(67 56 202) !important; box-shadow: none !important; }
        .compact-tab { display:inline-flex; height:30px; width:auto; flex-shrink:0; align-items:center; justify-content:center; border-radius:6px; border:1px solid transparent; background:transparent; padding:0 9px; font-size:11px; font-weight:500; color:rgb(71 85 105); white-space:nowrap; transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease; cursor:pointer; }
        [x-cloak] { display:none !important; }
    </style>
</div>

@script
<script>
window.aabiJwtDecoder = function () {
    return {
        token: '',
        header: null,
        payload: null,
        headerJson: '',
        payloadJson: '',
        signature: '',
        segments: [],
        errors: [],
        claims: [],
        audit: [],
        activeTab: 'Decoded',
        tabs: ['Decoded', 'Claims', 'Timeline', 'Security Audit', 'Verify', 'Editor', 'Compare', 'Raw'],
        statusMessage: '',
        statusKind: 'info',
        processing: false,
        verificationKey: '',
        verifyKeyType: 'secret',
        jwksInput: '',
        verificationMessage: '',
        verificationKind: 'info',
        verificationState: 'Not verified',
        payloadEditor: '',
        unsignedPreview: '',
        compareTokenA: '',
        compareTokenB: '',
        comparison: null,
        now: Date.now(),
        clockTimer: null,

        init() {
            this.clockTimer = setInterval(() => { this.now = Date.now(); }, 1000);
            this.loadShareableState();
            this.updateDerived();
        },

        get cleanToken() { return this.token.replace(/^\s*Bearer\s+/i, '').trim(); },
        get segmentCount() { return this.cleanToken ? this.cleanToken.split('.').length : 0; },
        get algorithm() { return this.header && typeof this.header.alg === 'string' ? this.header.alg : ''; },
        get tokenType() { return this.header && typeof this.header.typ === 'string' ? this.header.typ : ''; },
        get headerSummary() { return this.header ? ((this.algorithm || 'unknown') + (this.tokenType ? ' · ' + this.tokenType : '')) : 'Not decoded'; },
        get payloadSummary() { return this.payload ? Object.keys(this.payload).length + ' claim' + (Object.keys(this.payload).length === 1 ? '' : 's') : 'Not decoded'; },
        get signatureSummary() { return this.signature ? this.signature.length + ' Base64URL chars' : 'Missing'; },
        get tokenStats() { return this.cleanToken ? this.cleanToken.length.toLocaleString() + ' chars' : '0 chars'; },
        get overallStatus() {
            if (this.errors.some(e => e.level === 'error')) return 'Invalid';
            if (!this.cleanToken) return 'Ready';
            if (!this.segments.length) return 'Not decoded';
            return 'Structure valid';
        },
        get statusPillClass() {
            if (this.overallStatus === 'Invalid') return 'border-rose-200 bg-rose-50 text-rose-700';
            if (this.overallStatus === 'Structure valid') return 'border-emerald-200 bg-emerald-50 text-emerald-700';
            return 'border-slate-200 bg-slate-50 text-slate-600';
        },
        get statusClass() {
            return this.statusKind === 'error' ? 'border-rose-200 bg-rose-50 text-rose-700' : this.statusKind === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-600';
        },
        get verificationResultClass() {
            return this.verificationKind === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : this.verificationKind === 'error' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-slate-200 bg-slate-50 text-slate-600';
        },
        get relativeExpiration() {
            const value = this.numericClaim('exp');
            if (value === null) return 'No exp claim';
            return this.relativeFromSeconds(value);
        },
        get expiryStatus() {
            const exp = this.numericClaim('exp');
            const nbf = this.numericClaim('nbf');
            const now = this.now / 1000;
            if (exp !== null && now >= exp) return 'Expired';
            if (nbf !== null && now < nbf) return 'Not yet valid';
            if (exp !== null) return 'Active';
            return 'No expiration claim';
        },
        get verificationStateClass() { return ''; },
        get timelineCards() {
            return [
                { key:'iat', label:'Issued at', claim:'iat', date:this.formatClaimDate('iat'), relative:this.relativeClaim('iat') },
                { key:'nbf', label:'Valid from', claim:'nbf', date:this.formatClaimDate('nbf'), relative:this.relativeClaim('nbf') },
                { key:'exp', label:'Expires', claim:'exp', date:this.formatClaimDate('exp'), relative:this.relativeClaim('exp') }
            ];
        },
        get timelinePoints() {
            const values = [this.numericClaim('iat'), this.numericClaim('nbf'), this.numericClaim('exp')].filter(v => v !== null);
            if (!values.length) return [];
            let min = Math.min(...values), max = Math.max(...values);
            const now = this.now / 1000;
            min = Math.min(min, now); max = Math.max(max, now);
            if (min === max) { min -= 1; max += 1; }
            const pos = v => Math.max(2, Math.min(98, ((v - min) / (max - min)) * 100));
            const result = [];
            if (this.numericClaim('iat') !== null) result.push({ key:'iat', label:'Issued', position:pos(this.numericClaim('iat')), dotClass:'bg-indigo-500' });
            if (this.numericClaim('nbf') !== null) result.push({ key:'nbf', label:'Valid from', position:pos(this.numericClaim('nbf')), dotClass:'bg-amber-500' });
            result.push({ key:'now', label:'Now', position:pos(now), dotClass:'bg-slate-700' });
            if (this.numericClaim('exp') !== null) result.push({ key:'exp', label:'Expires', position:pos(this.numericClaim('exp')), dotClass:'bg-rose-500' });
            return result;
        },
        get tokenParts() {
            const s = this.cleanToken.split('.');
            return [
                { key:'header', label:'Header segment', value:s[0] || '', className:'border-rose-200 bg-rose-50 text-rose-800', borderClass:'border-rose-200', headerClass:'bg-rose-50 text-rose-800' },
                { key:'payload', label:'Payload segment', value:s[1] || '', className:'border-amber-200 bg-amber-50 text-amber-900', borderClass:'border-amber-200', headerClass:'bg-amber-50 text-amber-900' },
                { key:'signature', label:'Signature segment', value:s[2] || '', className:'border-emerald-200 bg-emerald-50 text-emerald-800', borderClass:'border-emerald-200', headerClass:'bg-emerald-50 text-emerald-800' }
            ];
        },

        onTokenInput() {
            this.statusMessage = '';
            this.verificationState = 'Not verified';
            this.verificationMessage = '';
            if (!this.cleanToken) { this.resetDecoded(); return; }
            if (this.cleanToken.length <= 8192) this.pushShareableState();
        },

        decode() {
            if (!this.cleanToken) { this.showStatus('Paste a JWT token first.', 'error'); return; }
            this.processing = true;
            try {
                this.resetDecoded();
                const parts = this.cleanToken.split('.');
                this.segments = parts;
                if (parts.length !== 3) throw new Error('JWT must contain exactly three dot-separated segments: header.payload.signature.');
                if (!parts[0] || !parts[1] || !parts[2]) throw new Error('JWT contains an empty segment.');
                const headerText = this.decodeBase64Url(parts[0]);
                const payloadText = this.decodeBase64Url(parts[1]);
                this.header = this.parseJson(headerText, 'header');
                this.payload = this.parseJson(payloadText, 'payload');
                if (!this.header || typeof this.header !== 'object' || Array.isArray(this.header)) throw new Error('JWT header must decode to a JSON object.');
                if (!this.payload || typeof this.payload !== 'object' || Array.isArray(this.payload)) throw new Error('JWT payload must decode to a JSON object.');
                this.signature = parts[2];
                this.headerJson = JSON.stringify(this.header, null, 2);
                this.payloadJson = JSON.stringify(this.payload, null, 2);
                this.payloadEditor = this.payloadJson;
                this.runValidation();
                this.runAudit();
                this.buildClaims();
                this.activeTab = 'Decoded';
                this.showStatus('JWT decoded locally. No token data was sent to AabiTech.', 'success');
                this.pushShareableState();
            } catch (error) {
                this.errors.push({ level:'error', message:error.message || 'Unable to decode JWT.' });
                this.showStatus(error.message || 'Unable to decode JWT.', 'error');
            } finally {
                this.processing = false;
                this.updateDerived();
            }
        },

        validate() {
            if (!this.cleanToken) { this.showStatus('Paste a JWT token first.', 'error'); return; }
            if (!this.header || !this.payload) this.decode();
            else { this.runValidation(); this.runAudit(); this.showStatus(this.errors.length ? 'Validation completed with findings.' : 'JWT structure is valid.', this.errors.length ? 'error' : 'success'); }
            this.activeTab = 'Security Audit';
        },

        inspect() {
            if (!this.header || !this.payload) this.decode();
            this.buildClaims();
            this.runAudit();
            this.activeTab = 'Claims';
        },

        resetDecoded() {
            this.header = null; this.payload = null; this.headerJson = ''; this.payloadJson = ''; this.signature = ''; this.segments = []; this.errors = []; this.claims = []; this.audit = []; this.payloadEditor = ''; this.unsignedPreview = ''; this.comparison = null;
        },

        parseJson(text, name) {
            try { return JSON.parse(text); } catch (e) { throw new Error('Invalid JSON in ' + name + ' segment: ' + (e.message || 'parse error') + '.'); }
        },

        decodeBase64Url(input) {
            if (!/^[A-Za-z0-9_-]+={0,2}$/.test(input)) throw new Error('Invalid Base64URL characters or padding in JWT segment.');
            if (input.includes('=')) {
                const firstPad = input.indexOf('=');
                if (firstPad < input.length - 2 && /[^=]/.test(input.slice(firstPad))) throw new Error('Invalid Base64URL padding.');
            }
            const unpadded = input.replace(/=/g, '');
            if (unpadded.length % 4 === 1) throw new Error('Invalid Base64URL length.');
            const padded = unpadded + '='.repeat((4 - (unpadded.length % 4)) % 4);
            const binary = atob(padded.replace(/-/g, '+').replace(/_/g, '/'));
            const bytes = new Uint8Array(binary.length);
            for (let i = 0; i < binary.length; i++) bytes[i] = binary.charCodeAt(i);
            try { return new TextDecoder('utf-8', { fatal:true }).decode(bytes); }
            catch { throw new Error('Invalid UTF-8 data in Base64URL segment.'); }
        },

        base64UrlEncodeUtf8(text) {
            const bytes = new TextEncoder().encode(text);
            let binary = '';
            const chunk = 0x8000;
            for (let i=0; i<bytes.length; i+=chunk) binary += String.fromCharCode(...bytes.subarray(i, i+chunk));
            return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/g, '');
        },

        runValidation() {
            const issues = [];
            const parts = this.cleanToken.split('.');
            if (parts.length !== 3) issues.push({ level:'error', message:'JWT must contain exactly three segments.' });
            if (parts.length === 3) {
                ['header','payload','signature'].forEach((name, i) => { if (!parts[i]) issues.push({ level:'error', message:'The ' + name + ' segment is empty.' }); });
                parts.slice(0,2).forEach((part, i) => { try { this.decodeBase64Url(part); } catch(e) { issues.push({ level:'error', message:'Invalid Base64URL in ' + (i === 0 ? 'header' : 'payload') + ': ' + e.message }); } });
                if (parts[0] && parts[1]) {
                    try { this.parseJson(this.decodeBase64Url(parts[0]), 'header'); } catch(e) { issues.push({ level:'error', message:e.message }); }
                    try { this.parseJson(this.decodeBase64Url(parts[1]), 'payload'); } catch(e) { issues.push({ level:'error', message:e.message }); }
                }
            }
            if (this.header && !this.header.alg) issues.push({ level:'warning', message:'Header does not contain an alg field.' });
            this.errors = issues;
        },

        buildClaims() {
            if (!this.payload) { this.claims = []; return; }
            this.claims = Object.keys(this.payload).map(key => {
                const value = this.payload[key];
                const isTime = ['exp','iat','nbf'].includes(key) && typeof value === 'number';
                return {
                    key,
                    type: this.valueType(value),
                    rawValue: this.stringifyClaim(value),
                    displayValue: this.stringifyClaim(value),
                    interpretation: isTime ? this.formatClaimDate(key) + ' · ' + this.relativeClaim(key) : this.claimDescription(key, value)
                };
            });
        },

        valueType(value) {
            if (value === null) return 'null';
            if (Array.isArray(value)) return 'array';
            return typeof value;
        },
        stringifyClaim(value) { return typeof value === 'string' ? value : JSON.stringify(value); },
        claimValue(key) { return this.payload && Object.prototype.hasOwnProperty.call(this.payload, key) ? this.stringifyClaim(this.payload[key]) : ''; },
        numericClaim(key) {
            const value = this.payload && this.payload[key];
            if (typeof value === 'number' && Number.isFinite(value)) return value;
            if (typeof value === 'string' && value.trim() !== '' && Number.isFinite(Number(value))) return Number(value);
            return null;
        },
        formatClaimDate(key) {
            const seconds = this.numericClaim(key);
            if (seconds === null) return '';
            const date = new Date(seconds * 1000);
            if (Number.isNaN(date.getTime())) return 'Invalid timestamp';
            const local = date.toLocaleString(undefined, { dateStyle:'medium', timeStyle:'medium', timeZoneName:'short' });
            const utc = date.toLocaleString(undefined, { dateStyle:'medium', timeStyle:'medium', timeZone:'UTC', timeZoneName:'short' });
            return local + ' · UTC ' + utc;
        },
        relativeClaim(key) {
            const value = this.numericClaim(key);
            if (value === null) return '';
            return this.relativeFromSeconds(value);
        },
        relativeFromSeconds(seconds) {
            const diff = seconds - this.now / 1000;
            const abs = Math.abs(diff);
            const units = abs >= 86400 ? ['day',86400] : abs >= 3600 ? ['hour',3600] : abs >= 60 ? ['minute',60] : ['second',1];
            const n = Math.round(abs / units[1]);
            if (diff > 0) return 'in ' + n + ' ' + units[0] + (n === 1 ? '' : 's');
            if (diff < 0) return n + ' ' + units[0] + (n === 1 ? '' : 's') + ' ago';
            return 'now';
        },
        claimDescription(key, value) {
            const map = {
                iss:'Issuer identifying the principal that issued the JWT.',
                sub:'Subject identifier for the principal.',
                aud:'Intended audience for the JWT.',
                exp:'Expiration time after which the JWT must not be accepted.',
                iat:'Time at which the JWT was issued.',
                nbf:'Time before which the JWT must not be accepted.',
                jti:'Unique identifier for the JWT.'
            };
            if (map[key]) return map[key];
            if (key === 'scope' || key === 'scp') return 'Authorization scope/permissions claim.';
            if (key === 'azp') return 'Authorized party claim commonly used with OpenID Connect.';
            if (key === 'nonce') return 'Value commonly used to associate a client session with an ID token.';
            return 'Custom claim.';
        },

        runAudit() {
            const a = [];
            if (!this.header || !this.payload) { this.audit = []; return; }
            const alg = String(this.header.alg || '').toUpperCase();
            if (alg === 'NONE') a.push({id:'alg-none', level:'critical', title:'alg: none detected', message:'This token declares no cryptographic signature algorithm. Treat it as unsigned and do not accept it as authenticated.'});
            if (!this.header.alg) a.push({id:'missing-alg', level:'critical', title:'Missing alg', message:'The JWT header has no alg value, so the intended signature algorithm is undefined.'});
            if (alg && ['HS256','HS384','HS512'].includes(alg)) a.push({id:'hmac', level:'info', title:'Symmetric HMAC algorithm', message:'HMAC verification requires the correct shared secret. Never substitute a public RSA/ECDSA key as an HMAC secret.'});
            if (alg) a.push({id:'allowlist', level:'warning', title:'Algorithm allowlisting required', message:'A verifier should allowlist the expected algorithm and key type instead of blindly trusting alg from the untrusted token header. This helps prevent algorithm-confusion and key-type substitution attacks.'});
            if (alg && ['RS256','RS384','RS512','ES256','ES384','ES512'].includes(alg)) a.push({id:'asym', level:'info', title:'Asymmetric algorithm', message:'Verification requires a compatible public key. The token itself does not prove that the supplied key is trusted.'});
            if (alg && !['HS256','HS384','HS512','RS256','RS384','RS512','ES256','ES384','ES512','NONE'].includes(alg)) a.push({id:'unusual-alg', level:'warning', title:'Unusual or unsupported algorithm', message:'The token uses ' + alg + '. Verify that your application explicitly supports and allowlists this algorithm.'});
            if (!Object.prototype.hasOwnProperty.call(this.payload,'exp')) a.push({id:'no-exp', level:'warning', title:'Missing expiration (exp)', message:'No expiration claim is present. Whether this is acceptable depends on the application and token type.'});
            if (!Object.prototype.hasOwnProperty.call(this.payload,'nbf')) a.push({id:'no-nbf', level:'info', title:'No not-before (nbf) claim', message:'No nbf claim is present. This is not automatically unsafe; it simply provides no not-before constraint.'});
            const exp = this.numericClaim('exp'), nbf = this.numericClaim('nbf'), now = this.now/1000;
            if (exp !== null && now >= exp) a.push({id:'expired', level:'critical', title:'Token is expired', message:'The current browser time is at or after exp.'});
            else if (exp !== null) a.push({id:'active', level:'info', title:'Expiration is present', message:'The token currently has an expiration in the future.'});
            if (nbf !== null && now < nbf) a.push({id:'future-nbf', level:'warning', title:'Token is not yet valid', message:'The current browser time is before nbf.'});
            if (this.header.kid) a.push({id:'kid', level:'info', title:'Key ID present', message:'The header contains kid=' + String(this.header.kid) + '. A verifier should resolve it only against a trusted key set.'});
            if (this.header.jku || this.header.x5u) a.push({id:'remote-key', level:'warning', title:'Remote key reference in header', message:'The header contains jku/x5u. Applications should not blindly fetch arbitrary keys from token-controlled URLs.'});
            if (this.header.crit) a.push({id:'crit', level:'warning', title:'Critical header parameters present', message:'crit is present. Every listed critical extension must be explicitly understood by the verifier.'});
            if (!this.signature) a.push({id:'empty-signature', level:'critical', title:'Missing signature', message:'The signature segment is empty.'});
            this.audit = a;
        },

        auditClass(level) { return level === 'critical' ? 'border-rose-200 bg-rose-50 text-rose-800' : level === 'warning' ? 'border-amber-200 bg-amber-50 text-amber-900' : 'border-slate-200 bg-white text-slate-700'; },
        auditDot(level) { return level === 'critical' ? 'bg-rose-500' : level === 'warning' ? 'bg-amber-500' : 'bg-slate-400'; },

        async verifySignature() {
            if (!this.header || !this.payload) this.decode();
            if (!this.header || !this.signature) return;
            const alg = String(this.algorithm || '').toUpperCase();
            this.verificationMessage = '';
            this.verificationState = 'Verifying…';
            try {
                if (alg === 'NONE') throw new Error('Verification is not applicable to alg: none.');
                const input = this.cleanToken.split('.').slice(0,2).join('.');
                const signatureBytes = this.base64UrlToBytes(this.signature);
                let key;
                if (alg.startsWith('HS')) {
                    if (this.verifyKeyType !== 'secret') throw new Error('An HMAC algorithm requires the HMAC secret option.');
                    key = await crypto.subtle.importKey('raw', new TextEncoder().encode(this.verificationKey), {name:'HMAC', hash:{name:'SHA-' + alg.slice(2)}}, false, ['verify']);
                    const ok = await crypto.subtle.verify('HMAC', key, signatureBytes, new TextEncoder().encode(input));
                    this.setVerification(ok, ok ? 'Signature verified successfully with the supplied HMAC secret.' : 'Signature verification failed.');
                    return;
                }
                if (alg.startsWith('RS') || alg.startsWith('ES')) {
                    if (this.verifyKeyType === 'jwks') key = await this.importKeyFromJwks(alg);
                    else key = await this.importPublicKey(alg, this.verificationKey);
                    const params = alg.startsWith('RS') ? {name:'RSASSA-PKCS1-v1_5'} : {name:'ECDSA', hash:{name:'SHA-' + alg.slice(2)}};
                    const ok = await crypto.subtle.verify(params, key, alg.startsWith('ES') ? this.joseToDerSignature(signatureBytes) : signatureBytes, new TextEncoder().encode(input));
                    this.setVerification(ok, ok ? 'Signature verified successfully with the supplied public key.' : 'Signature verification failed.');
                    return;
                }
                throw new Error('This decoder supports HS256/384/512, RS256/384/512 and ES256/384/512 for Web Crypto verification.');
            } catch (error) {
                this.verificationKind = 'error'; this.verificationState = 'Failed'; this.verificationMessage = error.message || 'Verification could not be completed.';
            }
        },

        setVerification(ok, message) { this.verificationKind = ok ? 'success' : 'error'; this.verificationState = ok ? 'Verified' : 'Failed'; this.verificationMessage = message; },
        resetVerificationResult() { this.verificationMessage = ''; this.verificationState = 'Not verified'; this.verificationKind = 'info'; },

        async importPublicKey(alg, input) {
            if (!input.trim()) throw new Error('Provide a public key.');
            if (input.includes('BEGIN')) {
                const der = this.pemToArrayBuffer(input);
                if (alg.startsWith('RS')) return crypto.subtle.importKey('spki', der, {name:'RSASSA-PKCS1-v1_5', hash:{name:'SHA-' + alg.slice(2)}}, false, ['verify']);
                return crypto.subtle.importKey('spki', der, {name:'ECDSA', namedCurve:this.curveForAlg(alg)}, false, ['verify']);
            }
            const jwk = JSON.parse(input);
            return crypto.subtle.importKey('jwk', jwk, this.jwkAlgorithm(alg), false, ['verify']);
        },

        async importKeyFromJwks(alg) {
            let text = this.jwksInput.trim();
            if (/^https:\/\//i.test(text)) {
                const response = await fetch(text, {headers:{Accept:'application/json'}});
                if (!response.ok) throw new Error('JWKS request failed with HTTP ' + response.status + '.');
                text = await response.text();
            }
            const jwks = JSON.parse(text);
            if (!jwks || !Array.isArray(jwks.keys)) throw new Error('Invalid JWKS: expected a keys array.');
            const kid = this.header.kid;
            const candidates = jwks.keys.filter(k => (!kid || k.kid === kid) && this.keyMatchesAlgorithm(k, alg));
            if (!candidates.length) throw new Error('No compatible JWKS key matched the token algorithm' + (kid ? ' and kid.' : '.'));
            return crypto.subtle.importKey('jwk', candidates[0], this.jwkAlgorithm(alg), false, ['verify']);
        },
        keyMatchesAlgorithm(k, alg) {
            if (alg.startsWith('RS')) return k.kty === 'RSA' && (!k.alg || k.alg === alg) && (!k.use || k.use === 'sig');
            if (alg.startsWith('ES')) return k.kty === 'EC' && (!k.alg || k.alg === alg) && (!k.use || k.use === 'sig');
            return false;
        },
        jwkAlgorithm(alg) { return alg.startsWith('RS') ? {name:'RSASSA-PKCS1-v1_5', hash:{name:'SHA-' + alg.slice(2)}} : {name:'ECDSA', namedCurve:this.curveForAlg(alg)}; },
        curveForAlg(alg) { return alg === 'ES256' ? 'P-256' : alg === 'ES384' ? 'P-384' : 'P-521'; },
        pemToArrayBuffer(pem) {
            const base64 = pem.replace(/-----BEGIN [^-]+-----/g,'').replace(/-----END [^-]+-----/g,'').replace(/\s+/g,'');
            const binary = atob(base64); const bytes = new Uint8Array(binary.length);
            for(let i=0;i<binary.length;i++) bytes[i]=binary.charCodeAt(i);
            return bytes.buffer;
        },
        base64UrlToBytes(input) {
            const normalized = input.replace(/-/g,'+').replace(/_/g,'/') + '='.repeat((4 - input.length % 4) % 4);
            const binary = atob(normalized); const bytes = new Uint8Array(binary.length);
            for(let i=0;i<binary.length;i++) bytes[i]=binary.charCodeAt(i);
            return bytes;
        },
        joseToDerSignature(signature) {
            const size = signature.length / 2;
            if (![32,48,66].includes(size)) throw new Error('Invalid ECDSA JOSE signature length.');
            let r = signature.slice(0,size), s = signature.slice(size);
            const hexOf = bytes => Array.from(bytes).map(x=>x.toString(16).padStart(2,'0')).join('');
            const trim = hex => { let i=0; while(i < hex.length-2 && hex.slice(i,i+2)==='00' && parseInt(hex.slice(i+2,i+4),16) < 0x80) i+=2; if(parseInt(hex.slice(i,i+2),16)>=0x80) hex='00'+hex; return hex; };
            r=trim(hexOf(r)); s=trim(hexOf(s));
            const encodeLength = length => length < 128 ? [length] : [0x80 | (length.toString(16).length/2), ...length.toString(16).padStart(length.toString(16).length % 2 ? length.toString(16).length+1 : length.toString(16).length, '0').match(/../g).map(x=>parseInt(x,16))];
            const rBytes=r.match(/../g).map(x=>parseInt(x,16)), sBytes=s.match(/../g).map(x=>parseInt(x,16));
            const body=[0x02,...encodeLength(rBytes.length),...rBytes,0x02,...encodeLength(sBytes.length),...sBytes];
            return new Uint8Array([0x30,...encodeLength(body.length),...body]);
        },

        loadPayloadIntoEditor() { this.payloadEditor = this.payloadJson || ''; },
        generateUnsignedPreview() {
            try {
                if (!this.header) throw new Error('Decode a JWT first.');
                const payload = JSON.parse(this.payloadEditor);
                const headerSegment = this.cleanToken.split('.')[0] || this.base64UrlEncodeUtf8(JSON.stringify(this.header));
                this.unsignedPreview = headerSegment + '.' + this.base64UrlEncodeUtf8(JSON.stringify(payload)) + '.';
                this.showStatus('Unsigned token preview generated. It has no signature.', 'success');
            } catch(e) { this.showStatus(e.message || 'Invalid payload JSON.', 'error'); }
        },

        compareTokens() {
            try {
                const a=this.parseTokenForCompare(this.compareTokenA), b=this.parseTokenForCompare(this.compareTokenB), structural=[], claims=[];
                if(a.parts.length!==b.parts.length) structural.push('Segment count differs: '+a.parts.length+' vs '+b.parts.length+'.');
                if(JSON.stringify(a.header)!==JSON.stringify(b.header)) structural.push('Header differs.');
                if(a.signature!==b.signature) structural.push('Signature segment differs.');
                const keys=[...new Set([...Object.keys(a.payload||{}),...Object.keys(b.payload||{})])].sort();
                keys.forEach(k=>{const av=JSON.stringify(a.payload?.[k]), bv=JSON.stringify(b.payload?.[k]); if(av!==bv) claims.push(k+': '+(av===undefined?'missing':av)+' → '+(bv===undefined?'missing':bv));});
                if(!structural.length) structural.push('No structural differences detected.');
                if(!claims.length) claims.push('No payload claim differences detected.');
                this.comparison={structural,claims};
            } catch(e) { this.comparison={structural:[e.message||'Unable to compare tokens.'],claims:[]}; }
        },
        parseTokenForCompare(raw) {
            const clean=String(raw||'').replace(/^\s*Bearer\s+/i,'').trim(), parts=clean.split('.');
            if(parts.length!==3) throw new Error('Each comparison token must contain three segments.');
            return {parts, header:JSON.parse(this.decodeBase64Url(parts[0])), payload:JSON.parse(this.decodeBase64Url(parts[1])), signature:parts[2]};
        },

        copyHeader() { this.copyText(this.headerJson, 'Header copied'); },
        copyPayload() { this.copyText(this.payloadJson, 'Payload copied'); },
        async copyText(value, message='Copied to clipboard') {
            if (value === null || value === undefined || value === '') { this.showStatus('Nothing to copy.', 'error'); return; }
            try { await navigator.clipboard.writeText(String(value)); this.showStatus(message, 'success'); }
            catch { this.showStatus('Clipboard access was not available.', 'error'); }
        },

        loadSample() {
            this.token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJhYWJpdGVjaC11c2VyIiwiaXNzIjoiYWFiaXRlY2guY29tIiwiYXVkIjoiYWFiaXRlY2gtYXBpIiwiaWF0IjoxNzU5MDAwMDAwLCJuYmYiOjE3NTkwMDAwMDAsImV4cCI6MTc1OTAwMzYwMCwianRpIjoiZGVtby0xMjMiLCJyb2xlIjoidXNlciJ9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
            this.decode();
        },
        clearAll() { this.token=''; this.resetDecoded(); this.statusMessage=''; this.verificationMessage=''; this.verificationState='Not verified'; this.activeTab='Decoded'; this.clearShareableState(); },

        async handleFile(event) {
            const file=event.target.files?.[0]; if(!file) return;
            if(file.size>5*1024*1024){this.showStatus('JWT file exceeds the 5 MB limit.','error');event.target.value='';return;}
            try { this.token=await file.text(); this.decode(); } catch { this.showStatus('Unable to read the JWT file.','error'); }
            event.target.value='';
        },

        downloadFile(name, content, type='text/plain') {
            const blob=new Blob([content],{type}), url=URL.createObjectURL(blob), a=document.createElement('a'); a.href=url; a.download=name; document.body.appendChild(a); a.click(); a.remove(); setTimeout(()=>URL.revokeObjectURL(url),1000);
        },
        downloadDecodedJson() {
            if (!this.header || !this.payload) { this.showStatus('Decode a JWT before downloading decoded JSON.', 'error'); return; }
            const decoded = { header: this.header, payload: this.payload, signature: this.signature, algorithm: this.algorithm, type: this.tokenType };
            this.downloadFile('jwt-decoded.json', JSON.stringify(decoded, null, 2), 'application/json');
        },
        downloadAudit() {
            const report={generatedAt:new Date().toISOString(), algorithm:this.algorithm, tokenType:this.tokenType, structure:{segments:this.segmentCount,valid:!this.errors.some(e=>e.level==='error')}, claims:this.payload||{}, audit:this.audit, verification:{state:this.verificationState}};
            this.downloadFile('jwt-security-audit.json',JSON.stringify(report,null,2),'application/json');
        },

        showStatus(message, kind='info') { this.statusMessage=message; this.statusKind=kind; },
        handleShortcut(event) {
            if ((event.ctrlKey||event.metaKey) && event.key==='Enter') { event.preventDefault(); this.decode(); }
            if (event.key==='Escape' && document.activeElement?.id==='jwt-token') { this.clearAll(); }
        },
        pushShareableState() {
            try { const url=new URL(location.href); url.hash='jwt-settings=' + encodeURIComponent(JSON.stringify({tab:this.activeTab})); history.replaceState(null,'',url); } catch {}
        },
        loadShareableState() {
            try { const match=location.hash.match(/^#jwt-settings=(.+)$/); if(!match)return; const state=JSON.parse(decodeURIComponent(match[1])); if(this.tabs.includes(state.tab))this.activeTab=state.tab; } catch {}
        },
        clearShareableState() { try { history.replaceState(null,'',location.pathname+location.search); } catch {} },
        updateDerived() { if(this.header&&this.payload){this.buildClaims();this.runAudit();} },
    };
};
</script>
@endscript
