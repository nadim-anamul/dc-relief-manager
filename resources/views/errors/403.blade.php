<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Access Denied') }} - {{ config('app.name', 'Relief Management System') }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Fonts for Bengali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-sans {
            font-family: 'Noto Sans Bengali', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <!-- Error Icon -->
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 dark:bg-red-900 mb-6">
                    <svg class="h-12 w-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                
                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">403</h1>
                
                <!-- Error Title -->
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                    {{ __('Access Denied') }}
                </h2>
                
                <!-- Error Message -->
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                    {{ __('Sorry, you do not have permission to access this resource.') }}
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <!-- Detailed Message -->
                <div class="mb-6">
                    <div class="rounded-md bg-yellow-50 dark:bg-yellow-900 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                                    {{ __('Insufficient Permissions') }}
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                                    <p>{{ __('You may need to contact your administrator to request access to this resource.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <!-- Go Home Button -->
                    <a href="{{ route('dashboard') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('Go to Dashboard') }}
                    </a>

                    <!-- Go Back Button -->
                    <button onclick="history.back()" 
                            class="w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Go Back') }}
                    </button>
                </div>

                <!-- Help Section -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                            {{ __('Need Help?') }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                            {{ __('If you believe this is an error, please contact your system administrator.') }}
                        </p>
                        
                        <!-- Contact Information -->
                        <div class="text-xs text-gray-500 dark:text-gray-500">
                            <p class="mb-1">{{ __('Error Code') }}: 403</p>
                            <p class="mb-1">{{ __('Timestamp') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
                            @auth
                                <p class="mb-1">{{ __('User') }}: {{ auth()->user()->name }}</p>
                                <p>{{ __('Roles') }}: {{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle (Optional) -->
    <div class="fixed top-4 right-4">
        <button onclick="toggleDarkMode()" 
                class="p-2 rounded-md bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>
    </div>

    <script>
        // Dark mode toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }

        // Initialize dark mode from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
