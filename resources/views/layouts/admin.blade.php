<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI-PINTAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-[#f8fafc] font-sans antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" @keydown.escape.window="sidebarOpen = false">
    <div class="flex min-h-screen bg-[#f8fafc]">
    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    {{-- Admin Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-[#e2e8f0] transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
        :class="sidebarCollapsed ? 'w-[260px] lg:w-[72px]' : 'w-[260px] lg:w-[260px]'"
    >
        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center border-b border-[#e2e8f0] px-5" :class="{ 'justify-center px-0': sidebarCollapsed }">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5" :class="{ '!hidden': sidebarCollapsed }">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" />
                    </svg>
                </div>
                <span class="text-[15px] font-bold text-[#0f172a]">SI-PINTAR</span>
            </a>
            {{-- Collapsed logo icon --}}
            <a href="{{ route('admin.dashboard') }}" :class="{ hidden: !sidebarCollapsed }" class="hidden lg:flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-semibold">
                SP
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-1 p-3 overflow-y-auto h-[calc(100%-4rem)]">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Dashboard</span>
            </a>

            {{-- Master Data --}}
            <div x-data="{ open: {{ request()->routeIs('admin.master.*') ? 'true' : 'false' }} }">
                <button type="button" @click="open = !open" class="flex w-full items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.master.*') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                    <span :class="{ hidden: sidebarCollapsed }">Master Data</span>
                    <svg :class="{ hidden: sidebarCollapsed }" class="ml-auto h-4 w-4 transition-transform" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </button>
                <div x-show="open" x-cloak x-collapse class="mt-1 ml-4 flex flex-col gap-0.5 border-l border-[#e2e8f0] pl-3" :class="{ hidden: sidebarCollapsed }">
                    <a href="{{ route('admin.master.karyawan.index') }}" class="block rounded-[10px] px-3 py-1.5 text-[13px] transition-all {{ request()->routeIs('admin.master.karyawan.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-[#64748b] hover:text-[#0f172a]' }}">Karyawan</a>
                    <a href="{{ route('admin.master.departemen.index') }}" class="block rounded-[10px] px-3 py-1.5 text-[13px] transition-all {{ request()->routeIs('admin.master.departemen.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-[#64748b] hover:text-[#0f172a]' }}">Departemen</a>
                    <a href="{{ route('admin.master.jabatan.index') }}" class="block rounded-[10px] px-3 py-1.5 text-[13px] transition-all {{ request()->routeIs('admin.master.jabatan.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-[#64748b] hover:text-[#0f172a]' }}">Jabatan</a>
                    <a href="{{ route('admin.master.admin-user.index') }}" class="block rounded-[10px] px-3 py-1.5 text-[13px] transition-all {{ request()->routeIs('admin.master.admin-user.*') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-[#64748b] hover:text-[#0f172a]' }}">Admin User</a>
                </div>
            </div>

            {{-- Training --}}
            <a href="{{ route('admin.training.index') }}" class="flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.training*') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Data Training</span>
            </a>

            {{-- Penilaian --}}
            <a href="{{ route('admin.penilaian') }}" class="flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.penilaian') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.846-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Penilaian</span>
            </a>

            {{-- Monitoring Progress --}}
            <a href="{{ route('admin.monitoring-progress') }}" class="flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.monitoring-progress') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Monitoring</span>
            </a>

            {{-- Laporan Training --}}
            <a href="{{ route('admin.laporan-training') }}" class="flex items-center gap-3 rounded-[10px] px-3 py-2.5 text-[14px] font-medium transition-all {{ request()->routeIs('admin.laporan-training') ? 'bg-blue-50 text-blue-600 border-l-[3px] border-blue-600' : 'text-[#64748b] hover:bg-gray-50 hover:text-[#0f172a]' }}" :class="{ '!justify-center !px-0': sidebarCollapsed }">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <span :class="{ hidden: sidebarCollapsed }">Laporan</span>
            </a>

            {{-- User Profile Footer --}}
            <div class="mt-auto border-t border-[#e2e8f0] pt-3">
                <div class="flex items-center gap-3 rounded-[10px] px-3 py-2" :class="{ 'justify-center !px-0': sidebarCollapsed }">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div :class="{ hidden: sidebarCollapsed }" class="min-w-0">
                        <div class="text-[13px] font-medium text-[#0f172a] truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-[#64748b]">Administrator</div>
                    </div>
                </div>
            </div>
        </nav>
    </aside>

    {{-- Main content wrapper --}}
    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-[72px] shrink-0 items-center justify-between border-b border-[#e2e8f0] bg-white px-6">
            <div class="flex items-center gap-4">
                {{-- Mobile menu toggle --}}
                <button type="button" @click="sidebarOpen = !sidebarOpen" class="p-1.5 text-[#64748b] rounded-lg transition-colors hover:bg-gray-100 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                {{-- Desktop sidebar toggle --}}
                <button type="button" @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="hidden p-1.5 text-[#64748b] rounded-lg transition-colors hover:bg-gray-100 lg:block">
                    <svg class="h-5 w-5" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
                {{-- Breadcrumb --}}
                <nav class="hidden text-sm text-[#64748b] md:flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span>Admin</span>
                    <svg class="h-3.5 w-3.5 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-[#0f172a] font-medium">{{ $title ?? 'Dashboard' }}</span>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                {{-- User dropdown --}}
                <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                    <button type="button" @click="open = !open" class="flex items-center gap-2.5 rounded-lg p-1.5 hover:bg-gray-100 transition-colors">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-[13px] font-semibold text-blue-600">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="hidden text-[13px] font-medium text-[#0f172a] md:block">{{ auth()->user()->name }}</span>
                        <svg class="hidden h-4 w-4 text-[#64748b] md:block" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-48 rounded-lg border border-[#e2e8f0] bg-white py-1 shadow-lg"
                    >
                        <a href="{{ route('admin.profil-password') }}" class="block px-4 py-2 text-[13px] text-[#0f172a] hover:bg-gray-50 transition-colors">Profil & Password</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-[13px] text-[#EE1D36] hover:bg-red-50 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content area --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    {{-- Toast container --}}
    <x-ui.toast-container />
    </div>
</body>
</html>
