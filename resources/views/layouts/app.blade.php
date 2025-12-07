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

<body class="font-sans antialiased">
    <style>
        /* Estilos Responsivos */
        .sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 50;
        }

        #sidebarToggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 100;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            #sidebarToggle {
                display: block;
            }

            .flex-container {
                flex-direction: column;
            }

            .sidebar-container {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 260px;
                transform: translateX(-100%);
                z-index: 50;
            }

            .sidebar-container.active {
                transform: translateX(0);
            }

            .main-content {
                padding-top: 60px !important;
                /* Espacio para el botón hamburguesa */
                width: 100%;
            }

            /* Overlay para cerrar sidebar al hacer clic fuera */
            #sidebarOverlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            #sidebarOverlay.active {
                display: block;
            }
        }
    </style>

    <button id="sidebarToggle" onclick="toggleSidebar()">
        ☰ Menú
    </button>
    <div id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="min-h-screen bg-gray-100 flex-container">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- Script Global para SweetAlert2 y Sidebar --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
            if (overlay) {
                overlay.classList.toggle('active');
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