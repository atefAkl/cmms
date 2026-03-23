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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *[title] {
            position: relative;
        }

        *[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
            background-color: #363636ff;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            z-index: 50;
            opacity: 1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            pointer-events: none;
        }

        *[title]:hover::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%) translateY(0px);
            border: 10px solid transparent;
            border-top-color: #363636ff;
            z-index: 50;
            opacity: 1;
            pointer-events: none;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Flex Sibling -->
        <x-sidebar />

        <!-- Main Container - Flex Column Sibling -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Navbar -->
            <x-navbar />

            <!-- Page Content - Only area that scrolls -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @if (session('success'))
                    <div
                        class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl text-sm font-bold text-green-700 flex items-center gap-3">
                        <i class="fa fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl text-sm font-bold text-red-700 flex items-center gap-3">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>