{{-- filepath: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'CCS App') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <div id="app" class="flex-1 flex flex-col">
        <!-- Navigation -->
        <nav class="bg-green-700 text-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold flex items-center hover:text-green-200 transition">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    CCS App
                </a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:text-green-200 transition">Dashboard</a>
                        <a href="{{ route('perencanaan.create') }}" class="hover:text-green-200 transition">Perencanaan</a>
                        <a href="{{ route('implementasi.create') }}" class="hover:text-green-200 transition">Implementasi</a>
                        <a href="{{ route('monitoring.create') }}" class="hover:text-green-200 transition">Monitoring</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:text-green-200 transition">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-green-200 transition">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-green-200 transition">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="container mx-auto px-4 py-6 flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-green-800 text-white py-6 mt-8 shadow-inner">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} Carbon Credit System. All rights reserved.</p>
                <p class="mt-2 text-sm text-green-200">Version 1.0.0</p>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>