@extends('layouts.auth', ['title' => 'Login'])

@section('content')
<div class="flex min-h-screen">
    {{-- Left: Branding Panel (desktop only) --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-center bg-[#080808] px-12 text-white">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight">SI-PINTAR</h1>
            <p class="mt-3 text-lg text-gray-300 leading-relaxed">Sistem Informasi Pelatihan dan Penilaian Karyawan</p>
            <div class="mt-8 space-y-3">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="text-sm text-gray-300">Training terstruktur untuk seluruh karyawan</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-gray-300">Pre-test, materi, post-test, dan penilaian</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span class="text-sm text-gray-300">Monitoring progress dan laporan training</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Login Form --}}
    <div class="flex w-full flex-col justify-center px-4 py-12 sm:px-6 lg:w-1/2 lg:px-12">
        <div class="w-full max-w-sm mx-auto lg:mx-0">
            {{-- Mobile branding --}}
            <div class="mb-8 lg:hidden text-center">
                <h1 class="text-2xl font-semibold text-[#080808]">SI-PINTAR</h1>
                <p class="mt-1 text-sm text-[#5A5A5A]">Sistem Informasi Pelatihan dan Penilaian Karyawan</p>
            </div>

            <h2 class="text-xl font-semibold text-[#080808]">Masuk ke akun Anda</h2>
            <p class="mt-1 text-sm text-[#5A5A5A]">Masukkan username dan password Anda untuk melanjutkan.</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                @csrf

                @if (session('status'))
                    <div class="rounded-md border border-green-300 bg-green-50 px-3 py-2 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-md border border-[#EE1D36]/30 bg-red-50 px-3 py-2 text-sm text-[#EE1D36]">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="username" class="block text-sm font-medium text-[#080808]">Username</label>
                    <input type="text" id="username" name="username" required autofocus autocomplete="username"
                           value="{{ old('username') }}"
                           class="mt-1 block w-full rounded-md border border-[#D8D8D8] px-3 py-2 text-sm focus:border-[#080808] focus:outline-none focus:ring-2 focus:ring-[#080808] focus:ring-offset-1"
                           placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#080808]">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="mt-1 block w-full rounded-md border border-[#D8D8D8] px-3 py-2 text-sm focus:border-[#080808] focus:outline-none focus:ring-2 focus:ring-[#080808] focus:ring-offset-1"
                           placeholder="Masukkan password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-[#D8D8D8] text-[#080808] focus:ring-[#080808]">
                        <span class="text-sm text-[#5A5A5A]">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#5A5A5A] hover:text-[#080808] transition-colors">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="flex w-full items-center justify-center rounded-md bg-[#080808] px-4 py-2 text-sm font-medium text-white hover:bg-[#080808]/90 focus:outline-none focus:ring-2 focus:ring-[#080808] focus:ring-offset-2 transition-colors">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
