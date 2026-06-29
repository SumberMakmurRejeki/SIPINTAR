<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — SI-PINTAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    <style>
        @theme {
            --color-primary: #080808;
            --color-info: #146EF5;
            --color-success: #00A65A;
            --color-warning: #FFAE13;
            --color-danger: #EE1D36;
            --color-border: #D8D8D8;
            --color-text-secondary: #5A5A5A;
            --font-sans: 'Inter', system-ui, sans-serif;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-white font-sans antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-[#D8D8D8] transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto" :class="{ 'lg:w-16': sidebarCollapsed }">
        <div class="flex h-14 items-center border-b border-[#D8D8D8] px-4" :class="{ 'justify-center px-0': sidebarCollapsed }">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-sm font-semibold text-[#080808]" :class="{ hidden: sidebarCollapsed }">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" /></svg>
                <span>SI-PINTAR</span>
            </a>
        </div>

        <nav class="flex flex-col gap-1 p-2 overflow-y-auto h-[calc(100%-3.5rem)]">
            <x-admin.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z" /></svg>
                <span>Dashboard</span>
            </x-admin.sidebar-link>

            <!-- Master Data group -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-xs font-semibold uppercase tracking-wider text-[#5A5A5A] hover:text-[#080808]" :class="{ 'hidden': sidebarCollapsed }">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                    <span>Master Data</span>
                    <svg class="ml-auto h-4 w-4 transition-transform" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </button>
                <div x-show="open" x-collapse class="mt-1 ml-4 flex flex-col gap-0.5 border-l border-[#D8D8D8] pl-3" :class="{ hidden: sidebarCollapsed }">
                    <x-admin.sidebar-link :href="route('admin.master.karyawan')" :active="request()->routeIs('admin.master.karyawan')">Karyawan</x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.master.departemen')" :active="request()->routeIs('admin.master.departemen')">Departemen</x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.master.jabatan')" :active="request()->routeIs('admin.master.jabatan')">Jabatan</x-admin.sidebar-link>
                    <x-admin.sidebar-link :href="route('admin.master.admin-user')" :active="request()->routeIs('admin.master.admin-user')">Admin User</x-admin.sidebar-link>
                </div>
            </div>

            <!-- Training -->
            <x-admin.sidebar-link :href="route('admin.training')" :active="request()->routeIs('admin.training')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" /></svg>
                <span>Data Training</span>
            </x-admin.sidebar-link>

            <!-- Penilaian -->
            <x-admin.sidebar-link :href="route('admin.penilaian')" :active="request()->routeIs('admin.penilaian')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.846-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                <span>Penilaian</span>
            </x-admin.sidebar-link>

            <!-- Monitoring Progress -->
            <x-admin.sidebar-link :href="route('admin.monitoring-progress')" :active="request()->routeIs('admin.monitoring-progress')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                <span>Monitoring Progress</span>
            </x-admin.sidebar-link>

            <!-- Laporan Training -->
            <x-admin.sidebar-link :href="route('admin.laporan-training')" :active="request()->routeIs('admin.laporan-training')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <span>Laporan Training</span>
            </x-admin.sidebar-link>

            <div class="mt-auto border-t border-[#D8D8D8] pt-2">
                <x-admin.sidebar-link :href="route('admin.profil-password')" :active="request()->routeIs('admin.profil-password')">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    <span>Profil & Password</span>
                </x-admin.sidebar-link>
            </div>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex flex-1 flex-col lg:ml-0" :class="{ 'lg:ml-16': sidebarCollapsed }">
        <!-- Navbar -->
        <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-[#D8D8D8] bg-white px-4">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-md hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block p-1.5 rounded-md hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M22 18.75 17.5 14.25 22 9.75" /></svg>
                </button>
                <nav class="hidden text-sm text-[#5A5A5A] md:flex items-center gap-1">
                    <span>Admin</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-[#080808] font-medium">{{ $title ?? 'Dashboard' }}</span>
                </nav>
            </div>

            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-md p-1.5 hover:bg-gray-100">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#080808] text-sm font-medium text-white">
                        {{ auth()->user()->initials() }}
                    </div>
                    <span class="hidden text-sm font-medium text-[#080808] md:block">{{ auth()->user()->name }}</span>
                    <svg class="hidden h-4 w-4 text-[#5A5A5A] md:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 rounded-md border border-[#D8D8D8] bg-white py-1 shadow-lg">
                    <a href="{{ route('admin.profil-password') }}" class="block px-4 py-2 text-sm text-[#080808] hover:bg-gray-50">Profil & Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-[#EE1D36] hover:bg-gray-50">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content area -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
