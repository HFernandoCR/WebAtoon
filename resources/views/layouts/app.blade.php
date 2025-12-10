<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-100">

    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-100">

        <!-- Sidebar (Global Inclusion) -->
        <!-- Sidebar (Global Inclusion) -->
        <!-- Fixed/Sticky Sidebar for Desktop. Mobile: Wrapper exists but sidebar handles positioning. -->
        <div class="z-50 md:w-64 md:flex-col md:fixed md:inset-y-0 md:bg-slate-900">
             @include('sidebar')
        </div>

        <!-- Main Content Column -->
        <div class="flex-1 flex flex-col min-h-screen md:pl-64 transition-all duration-300">

            <!-- Mobile Toggle (Visible only on mobile) -->
            <div class="md:hidden bg-[#2c3e50] text-white p-4 flex items-center justify-between shadow-md sticky top-0 z-40">
                <span class="font-bold text-lg">Menú</span>
                <button onclick="toggleSidebar()" class="p-2 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            @isset($header)
                <header class="bg-white shadow z-30 relative">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center gap-3">
                        <img src="{{ asset('favicon.ico') }}" alt="Logo" class="h-10 w-10">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Script Global para Sidebar y SweetAlert2 --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar-container');
            const overlay = document.getElementById('mobile-overlay');

            if (sidebar) {
                // Tailwind classes for toggle
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                }
            }

            if (overlay) {
                if (overlay.classList.contains('hidden')) {
                    overlay.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                }
            }
        }

        function confirmAction(event, title, text, confirmButtonText) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) form.submit();
                }
            });
            return false;
        }

        function showAlert(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        }
    </script>
</body>

</html>