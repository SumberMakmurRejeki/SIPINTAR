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

    <!-- Employee Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-[#D8D8D8] transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto" :class="{ 'lg:w-16': sidebarCollapsed }">
        <div class="flex h-14 items-center border-b border-[#D8D8D8] px-4" :class="{ 'justify-center px-0': sidebarCollapsed }">
            <a href="{{ route('karyawan.dashboard') }}" class="flex items-center gap-2 text-sm font-semibold text-[#080808]" :class="{ hidden: sidebarCollapsed }">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.684-.457 50.734 50.734 0 0 1 0-6.273m15.482 6.734A50.583 50.583 0 0 1 12 4.095a50.583 50.583 0 0 1 5.482 2.305M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0v6m-3-3h6" /></svg>
                <span>SI-PINTAR</span>
            </a>
        </div>

        <nav class="flex flex-col gap-1 p-2 overflow-y-auto h-[calc(100%-3.5rem)]">
            <x-admin.sidebar-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3Zm11 0h7v7h-7V3ZM3 14h7v7H3v-7Zm11 0h7v7h-7v-7Z" /></svg>
                <span>Dashboard</span>
            </x-admin.sidebar-link>

            <x-admin.sidebar-link :href="route('karyawan.training-saya')" :active="request()->routeIs('karyawan.training-saya')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                <span>Training Saya</span>
            </x-admin.sidebar-link>

            <x-admin.sidebar-link :href="route('karyawan.riwayat-training')" :active="request()->routeIs('karyawan.riwayat-training')">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span>Riwayat Training</span>
            </x-admin.sidebar-link>

            <div class="mt-auto border-t border-[#D8D8D8] pt-2">
                <x-admin.sidebar-link :href="route('karyawan.profil-password')" :active="request()->routeIs('karyawan.profil-password')">
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
                    <span>Karyawan</span>
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
                    <a href="{{ route('karyawan.profil-password') }}" class="block px-4 py-2 text-sm text-[#080808] hover:bg-gray-50">Profil & Password</a>
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
