<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Expense Tracker') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700;800&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ page: '{{ request()->route()->getName() }}' }" class="min-h-screen pb-20">
        @yield('content')

        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around items-center h-16 max-w-lg mx-auto">
                <a href="{{ route('dashboard') }}"
                   class="flex flex-col items-center gap-1 px-4 py-2 transition-colors"
                   :class="page === 'dashboard' ? 'text-blue-600' : 'text-gray-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z" />
                    </svg>
                    <span class="text-xs font-medium">Home</span>
                </a>

                <a href="{{ route('charts.index') }}"
                   class="flex flex-col items-center gap-1 px-4 py-2 transition-colors"
                   :class="page?.startsWith('charts') ? 'text-blue-600' : 'text-gray-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <span class="text-xs font-medium">Charts</span>
                </a>

                <a href="{{ route('categories.index') }}"
                   class="flex flex-col items-center gap-1 px-4 py-2 transition-colors"
                   :class="page?.startsWith('categories') ? 'text-blue-600' : 'text-gray-400'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-xs font-medium">Categories</span>
                </a>
            </div>
        </nav>

        <div x-data="{ notification: null }" class="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"
                data-notification="{{ session('success') }}"
        x-init="notification = $el.dataset.notification?.trim() || null; if(notification) setTimeout(() => { notification = null }, 3000)">
            <template x-if="notification">
                <div @click.outside="notification = null" class="pointer-events-auto">
                    <div class="border-l-4 rounded-xl p-4 shadow-lg max-w-xs bg-green-50 border-green-500 text-green-800 transform transition-all duration-300 translate-x-0 opacity-100">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium" x-text="notification"></p>
                            </div>
                            <div class="ml-auto pl-3">
                                <button @click="notification = null" type="button" class="inline-flex rounded-md p-1.5 hover:bg-gray-100 focus:outline-none">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</body>
</html>
