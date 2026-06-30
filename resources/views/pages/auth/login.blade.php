@extends('layouts.auth', ['title' => 'Login'])

@section('content')
@php
    $passwordResetAvailable = Route::has('password.request');
@endphp

<div class="min-h-[100dvh] bg-surface lg:px-8 xl:px-12">
    <div class="mx-auto grid min-h-[100dvh] w-full max-w-[1440px] lg:grid-cols-[1.22fr_1fr] lg:items-center lg:gap-10 xl:gap-14">
        <section class="relative hidden overflow-hidden border-b border-border-soft bg-white px-5 py-8 sm:px-6 sm:py-10 lg:col-span-1 lg:flex lg:border-b-0 lg:border-r lg:px-8 lg:py-8 xl:px-10 xl:py-10">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -right-16 top-10 h-56 w-56 rounded-full bg-support-purple/20 blur-3xl"></div>
                <div class="absolute -left-16 bottom-8 h-64 w-64 rounded-full bg-support-blue/20 blur-3xl"></div>
                <div class="absolute right-8 top-8 h-20 w-20 rounded-full bg-support-orange/20 blur-2xl"></div>
            </div>

            <div class="relative mx-auto flex h-full max-w-2xl flex-col justify-center">
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-border-soft bg-surface shadow-soft">
                        <svg viewBox="0 0 64 64" class="h-8 w-8 text-support-blue" fill="none" aria-hidden="true">
                                <path d="M32 8 8 20l24 12 24-12L32 8Z" fill="currentColor" fill-opacity="0.28"></path>
                            <path d="M12 24v12c0 2.4 9 8 20 8s20-5.6 20-8V24" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M44 36v10" stroke="#31B36B" stroke-width="5" stroke-linecap="round"></path>
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold tracking-tight text-ink sm:text-[40px] sm:leading-tight">SI-PINTAR</h1>
                        <p class="max-w-xl text-sm leading-6 text-muted sm:text-base lg:text-lg">
                            Sistem Informasi Pelatihan dan Penilaian Karyawan
                        </p>
                    </div>
                </div>

                <p class="mt-4 max-w-2xl text-sm leading-6 text-muted sm:text-base lg:mt-5 lg:text-lg">
                    Pelatihan, penilaian, dan monitoring progress dirancang rapi agar setiap karyawan bisa belajar dengan alur yang jelas dan hasil yang mudah dilacak.
                </p>

                <div class="mt-5 hidden max-h-[420px] xl:block">
                    <div class="relative overflow-hidden rounded-2xl border border-border-soft bg-surface p-3 shadow-soft sm:p-4">
                        <div class="mb-3 flex items-center justify-between rounded-2xl border border-border-soft bg-surface px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-support-orange"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-support-green"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-support-blue"></span>
                            </div>
                            <span class="text-xs font-medium tracking-wide text-muted">Training dashboard</span>
                        </div>

                        <svg viewBox="0 0 720 420" class="block h-auto w-full max-h-[360px] xl:max-h-[390px]" aria-hidden="true">
                            <defs>
                                <linearGradient id="siPintarBlue" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#146EF5"></stop>
                                    <stop offset="100%" stop-color="#7C5CFF"></stop>
                                </linearGradient>
                                <linearGradient id="siPintarGreen" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#31B36B"></stop>
                                    <stop offset="100%" stop-color="#00A65A"></stop>
                                </linearGradient>
                                <linearGradient id="siPintarOrange" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#F59E0B"></stop>
                                    <stop offset="100%" stop-color="#FFAE13"></stop>
                                </linearGradient>
                            </defs>

                            <rect x="6" y="6" width="708" height="408" rx="28" fill="#F8FAFC"></rect>
                            <rect x="115" y="56" width="440" height="276" rx="26" fill="#FFFFFF" stroke="#EEF2F7"></rect>
                            <rect x="115" y="56" width="440" height="22" rx="11" fill="#146EF5"></rect>
                            <circle cx="136" cy="67" r="4.2" fill="#FFAE13"></circle>
                            <circle cx="154" cy="67" r="4.2" fill="#31B36B"></circle>
                            <circle cx="172" cy="67" r="4.2" fill="#2F6BFF"></circle>

                            <rect x="152" y="106" width="168" height="128" rx="20" fill="#F8FAFC"></rect>
                            <circle cx="190" cy="146" r="30" fill="none" stroke="url(#siPintarBlue)" stroke-width="16" stroke-linecap="round" stroke-dasharray="124 44"></circle>
                            <path d="M190 116v30h30" stroke="url(#siPintarBlue)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M190 146 214 132" stroke="#146EF5" stroke-width="8" stroke-linecap="round"></path>
                            <rect x="236" y="124" width="58" height="10" rx="5" fill="#EEF2F7"></rect>
                            <rect x="236" y="144" width="96" height="10" rx="5" fill="#EEF2F7"></rect>
                            <rect x="236" y="164" width="82" height="10" rx="5" fill="#EEF2F7"></rect>
                            <rect x="236" y="194" width="24" height="24" rx="6" fill="#DBEAFE"></rect>
                            <path d="M242 206l5 5 9-11" stroke="#146EF5" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                            <rect x="236" y="226" width="24" height="24" rx="6" fill="#DCFCE7"></rect>
                            <path d="M242 238l5 5 9-11" stroke="#31B36B" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>

                            <rect x="356" y="108" width="168" height="120" rx="20" fill="#FFFFFF" stroke="#EEF2F7"></rect>
                            <path d="M370 180 392 164 412 170 432 154 450 158 470 136 492 126" fill="none" stroke="url(#siPintarBlue)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="370" cy="180" r="4" fill="#2F6BFF"></circle>
                            <circle cx="392" cy="164" r="4" fill="#7C5CFF"></circle>
                            <circle cx="412" cy="170" r="4" fill="#31B36B"></circle>
                            <circle cx="432" cy="154" r="4" fill="#F59E0B"></circle>
                            <circle cx="450" cy="158" r="4" fill="#146EF5"></circle>
                            <circle cx="470" cy="136" r="4" fill="#7C5CFF"></circle>
                            <circle cx="492" cy="126" r="4" fill="#31B36B"></circle>

                            <rect x="356" y="242" width="168" height="90" rx="20" fill="#F8FAFC"></rect>
                            <rect x="378" y="266" width="118" height="9" rx="4.5" fill="#EEF2F7"></rect>
                            <rect x="378" y="284" width="96" height="9" rx="4.5" fill="#EEF2F7"></rect>
                            <rect x="378" y="302" width="74" height="9" rx="4.5" fill="#EEF2F7"></rect>
                            <rect x="372" y="262" width="12" height="12" rx="3" fill="#DBEAFE"></rect>
                            <path d="M375 268l3 3 6-7" stroke="#146EF5" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            <rect x="372" y="280" width="12" height="12" rx="3" fill="#DCFCE7"></rect>
                            <path d="M375 286l3 3 6-7" stroke="#31B36B" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            <rect x="372" y="298" width="12" height="12" rx="3" fill="#FFEDD5"></rect>
                            <path d="M375 304l3 3 6-7" stroke="#F59E0B" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"></path>

                            <rect x="66" y="270" width="112" height="84" rx="20" fill="#FFFFFF" stroke="#EEF2F7"></rect>
                            <circle cx="124" cy="307" r="20" fill="url(#siPintarBlue)" fill-opacity="0.32"></circle>
                            <circle cx="124" cy="296" r="8" fill="#080808"></circle>
                            <rect x="111" y="305" width="26" height="24" rx="8" fill="#2F6BFF"></rect>
                            <rect x="91" y="330" width="66" height="8" rx="4" fill="#E2E8F0"></rect>
                            <rect x="87" y="348" width="74" height="10" rx="5" fill="#7C5CFF"></rect>

                            <rect x="560" y="96" width="124" height="96" rx="22" fill="#FFFFFF" stroke="#EEF2F7"></rect>
                            <path d="M586 136h42" stroke="#EEF2F7" stroke-width="8" stroke-linecap="round"></path>
                            <path d="M586 156h30" stroke="#EEF2F7" stroke-width="8" stroke-linecap="round"></path>
                            <path d="M586 136c0-13 10-24 24-24s24 11 24 24-10 24-24 24-24-11-24-24Z" fill="url(#siPintarOrange)" fill-opacity="0.3"></path>
                            <path d="M610 117v38" stroke="#F59E0B" stroke-width="8" stroke-linecap="round"></path>
                            <path d="M592 135h36" stroke="#F59E0B" stroke-width="8" stroke-linecap="round"></path>

                            <rect x="556" y="238" width="104" height="86" rx="22" fill="#FFFFFF" stroke="#EEF2F7"></rect>
                            <path d="M578 278 598 258 618 278 638 250" fill="none" stroke="url(#siPintarGreen)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="578" cy="278" r="4" fill="#31B36B"></circle>
                            <circle cx="598" cy="258" r="4" fill="#2F6BFF"></circle>
                            <circle cx="618" cy="278" r="4" fill="#7C5CFF"></circle>
                            <circle cx="638" cy="250" r="4" fill="#F59E0B"></circle>

                            <rect x="254" y="334" width="168" height="14" rx="7" fill="#EEF2F7"></rect>
                            <rect x="254" y="356" width="122" height="14" rx="7" fill="#EEF2F7"></rect>

                            <circle cx="488" cy="336" r="22" fill="url(#siPintarBlue)" fill-opacity="0.28"></circle>
                            <path d="M476 336h24" stroke="#146EF5" stroke-width="8" stroke-linecap="round"></path>
                            <path d="M488 324v24" stroke="#146EF5" stroke-width="8" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 hidden xl:grid xl:grid-cols-3 xl:gap-4">
                    <div class="flex items-start gap-3 rounded-2xl border border-border-soft bg-surface px-4 py-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-support-blue/20 text-support-blue">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19.5V6.75A2.75 2.75 0 0 1 6.75 4H19.5v13.25A2.75 2.75 0 0 1 16.75 20H4z"></path>
                                <path d="M8 8h8M8 12h5"></path>
                            </svg>
                        </div>
                        <p class="text-sm leading-6 text-ink">Training terstruktur untuk seluruh karyawan</p>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-border-soft bg-surface px-4 py-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-support-purple/20 text-support-purple">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 4h8l2 3h2v13H4V7h2l2-3z"></path>
                                <path d="M9 12l2 2 4-5"></path>
                            </svg>
                        </div>
                        <p class="text-sm leading-6 text-ink">Pre-test, materi, post-test, dan penilaian</p>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-border-soft bg-surface px-4 py-4 shadow-sm">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-support-green/20 text-support-green">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 20V10"></path>
                                <path d="M10 20V4"></path>
                                <path d="M16 20v-7"></path>
                                <path d="M22 20H2"></path>
                            </svg>
                        </div>
                        <p class="text-sm leading-6 text-ink">Monitoring progress dan laporan training</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center bg-white px-5 py-8 sm:px-6 sm:py-10 lg:col-span-1 lg:px-6 lg:py-8 xl:px-8 xl:py-10">
            <div class="w-full max-w-md">
                <div class="mx-auto w-full max-w-[460px] rounded-2xl border border-border bg-surface px-5 py-6 shadow-soft sm:px-8 sm:py-8 lg:px-8 lg:py-8">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border border-border-soft bg-surface-soft text-accent">
                        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V7a5 5 0 0 0-5-5Z"></path>
                            <path d="M9 10V7a3 3 0 0 1 6 0v3"></path>
                        </svg>
                    </div>

                    <div class="mt-5 space-y-2 text-center">
                        <h2 class="text-[28px] font-semibold tracking-tight text-ink sm:text-[30px]">Masuk ke akun Anda</h2>
                        <p class="text-sm leading-6 text-muted sm:text-base">Masukkan username dan password Anda untuk melanjutkan.</p>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-xl border border-success/20 bg-success/10 px-4 py-3 text-sm leading-6 text-ink" role="status" aria-live="polite">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-danger/20 bg-danger/10 px-4 py-3 text-sm leading-6 text-ink" role="alert">
                            <div class="flex items-start gap-3">
                                <svg viewBox="0 0 24 24" class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 9v4"></path>
                                    <path d="M12 17h.01"></path>
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3l-8.47-14.14a2 2 0 0 0-3.42 0Z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-ink">{{ $errors->first() }}</p>
                                    <p class="mt-1 text-xs text-muted">Periksa kembali username dan password yang Anda masukkan.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5" data-login-form>
                        @csrf

                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium text-ink">Username</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 21a8 8 0 1 0-16 0"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    autocomplete="username"
                                    autofocus
                                    required
                                    placeholder="Masukkan username Anda"
                                    class="block w-full rounded-xl border border-border bg-surface px-11 py-3 text-sm text-ink placeholder:text-text-muted focus:border-accent focus:outline-none focus:ring-4 focus:ring-accent/10"
                                >
                            </div>
                            @error('username')
                                <p class="text-xs leading-5 text-ink">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-ink">Password</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    placeholder="Masukkan password Anda"
                                    class="block w-full rounded-xl border border-border bg-surface px-11 py-3 pr-12 text-sm text-ink placeholder:text-text-muted focus:border-accent focus:outline-none focus:ring-4 focus:ring-accent/10"
                                    data-password-input
                                >
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted transition hover:text-ink focus:outline-none focus-visible:text-accent"
                                    aria-label="Tampilkan password"
                                    aria-pressed="false"
                                    data-password-toggle
                                >
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-password-toggle-icon="show">
                                        <path d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg viewBox="0 0 24 24" class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-password-toggle-icon="hide">
                                        <path d="M3 3l18 18"></path>
                                        <path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 3-3 3 3 0 0 0-.42-1.58"></path>
                                        <path d="M6.2 6.2C4 7.85 2.5 10 2.5 12c0 0 3.5 7 9.5 7 1.75 0 3.3-.37 4.64-1.02"></path>
                                        <path d="M14.9 5.1C14.03 4.76 13.06 4.5 12 4.5 6 4.5 2.5 12 2.5 12a16.7 16.7 0 0 0 3.16 4.86"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs leading-5 text-ink">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <label for="remember" class="inline-flex items-center gap-3 text-sm text-ink">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    @checked(old('remember'))
                                    class="h-4 w-4 rounded border-border text-accent focus:ring-4 focus:ring-accent/10"
                                >
                                <span>Ingat saya</span>
                            </label>

                            @if ($passwordResetAvailable)
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-muted transition hover:text-ink">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-accent px-4 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-accent-hover hover:-translate-y-px focus:outline-none focus-visible:ring-4 focus-visible:ring-accent/20 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
                            data-login-submit
                        >
                            <span data-login-submit-label>Masuk</span>
                        </button>

                        <div class="flex items-center gap-4 pt-2 text-center text-xs text-muted">
                            <span class="h-px flex-1 bg-border-soft"></span>
                            <span class="inline-flex items-center gap-2">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 3 4 6v6c0 5 3.5 7.9 8 9 4.5-1.1 8-4 8-9V6l-8-3Z"></path>
                                    <path d="M9.5 12.5 11.4 14l3.1-3.7"></path>
                                </svg>
                                Akses aman
                            </span>
                            <span class="h-px flex-1 bg-border-soft"></span>
                        </div>

                        <p class="text-center text-xs leading-5 text-muted">
                            Data Anda terenkripsi dan aman.
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-login-form]');
        const passwordInput = document.querySelector('[data-password-input]');
        const toggle = document.querySelector('[data-password-toggle]');
        const submitButton = document.querySelector('[data-login-submit]');
        const submitLabel = document.querySelector('[data-login-submit-label]');

        if (toggle && passwordInput) {
            const showIcon = toggle.querySelector('[data-password-toggle-icon="show"]');
            const hideIcon = toggle.querySelector('[data-password-toggle-icon="hide"]');

            toggle.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', String(isHidden));
                toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');

                showIcon?.classList.toggle('hidden', isHidden);
                hideIcon?.classList.toggle('hidden', !isHidden);
            });
        }

        if (form && submitButton && submitLabel) {
            form.addEventListener('submit', () => {
                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');
                submitLabel.textContent = 'Memproses...';
            }, { once: true });
        }
    });
</script>
@endsection
