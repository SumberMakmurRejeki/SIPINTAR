<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI-PINTAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white font-sans antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" @keydown.escape.window="sidebarOpen = false">
    <div class="flex min-h-screen bg-white">
    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    {{-- Employee Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-[#D8D8D8] transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
        :class="sidebarCollapsed ? 'w-64 lg:w-16' : 'w-64 lg:w-64'"
    >
        {{-- Logo --}}
        <div class="flex h-14 shrink-0 items-center border-b border-[#D8D8D8] px-4" :class="{ 'justify-center px-0': sidebarCollapsed }">
            <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-2 text-sm font-semibold text-[#080808]" :class="{ '!hidden': sidebarCollapsed }">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" />
                </svg>
                <span>SI-PINTAR</span>
            </a>
            <a href="{{ route('karyawan.dashboard') }}" :class="{ hidden: !sidebarCollapsed }" class="hidden lg:flex h-8 w-8 items-center justify-center rounded-md bg-[#080808] text-white text-xs font-semibold">
                SP
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-0.5 p-2 overflow-y-auto h-[calc(100%-3.5rem)]">
            {{-- Dashboard --}}
            <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium transition-colors {{ request()->routeIs('karyawan.dashboard') ? 'bg-gray-100 text-[#080808]' : 'text-[#5A5A5A] hover:bg-gray-50 hover:text-[#080808]' }}" :class="{ '!justify-center': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Dashboard</span>
            </a>

            {{-- Training Saya --}}
            <a href="{{ route('karyawan.training-saya') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium transition-colors {{ request()->routeIs('karyawan.training-saya') ? 'bg-gray-100 text-[#080808]' : 'text-[#5A5A5A] hover:bg-gray-50 hover:text-[#080808]' }}" :class="{ '!justify-center': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Training Saya</span>
            </a>

            {{-- Riwayat Training --}}
            <a href="{{ route('karyawan.riwayat-training') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium transition-colors {{ request()->routeIs('karyawan.riwayat-training') ? 'bg-gray-100 text-[#080808]' : 'text-[#5A5A5A] hover:bg-gray-50 hover:text-[#080808]' }}" :class="{ '!justify-center': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Riwayat</span>
            </a>

            {{-- Profil --}}
            <div class="mt-auto border-t border-[#D8D8D8] pt-2">
                <a href="{{ route('karyawan.profil-password') }}" class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium transition-colors {{ request()->routeIs('karyawan.profil-password') ? 'bg-gray-100 text-[#080808]' : 'text-[#5A5A5A] hover:bg-gray-50 hover:text-[#080808]' }}" :class="{ '!justify-center': sidebarCollapsed }">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    <span :class="{ hidden: sidebarCollapsed }">Profil</span>
                </a>
            </div>
        </nav>
    </aside>

    {{-- Main content wrapper --}}
    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Navbar --}}
        <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between border-b border-[#D8D8D8] bg-white px-4">
            <div class="flex items-center gap-3">
                {{-- Mobile menu toggle --}}
                <button type="button" @click="sidebarOpen = !sidebarOpen" class="p-1.5 text-[#080808] rounded-md transition-colors hover:bg-gray-100 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                {{-- Desktop sidebar toggle --}}
                <button type="button" @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="hidden p-1.5 text-[#080808] rounded-md transition-colors hover:bg-gray-100 lg:block">
                    <svg class="h-5 w-5" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
                {{-- Breadcrumb --}}
                <nav class="hidden text-sm text-[#5A5A5A] md:flex items-center gap-1">
                    <span>Karyawan</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-[#080808] font-medium">{{ $title ?? 'Dashboard' }}</span>
                </nav>
            </div>

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-md p-1.5 hover:bg-gray-100 transition-colors">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#080808] text-sm font-medium text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <span class="hidden text-sm font-medium text-[#080808] md:block">{{ auth()->user()->name }}</span>
                    <svg class="hidden h-4 w-4 text-[#5A5A5A] md:block" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-48 rounded-md border border-[#D8D8D8] bg-white py-1 shadow-lg"
                >
                    <a href="{{ route('karyawan.profil-password') }}" class="block px-4 py-2 text-sm text-[#080808] hover:bg-gray-50 transition-colors">Profil & Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-[#EE1D36] hover:bg-red-50 transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content area --}}
        <main class="flex-1 p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    {{-- Toast container --}}
    <x-ui.toast-container />
    </div>
</body>
</html>
