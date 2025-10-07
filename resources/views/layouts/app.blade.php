<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <x-success-alert 
                        title="{{ __('Success!') }}" 
                        :message="session('success')" 
                        class="mb-4 sm:mb-6"
                    />
                @endif

                @if (session('error'))
                    <x-error-alert 
                        type="error" 
                        title="{{ __('Error!') }}" 
                        :message="session('error')" 
                        class="mb-4 sm:mb-6"
                    />
                @endif

                @if ($errors->any())
                    <x-error-alert 
                        type="error" 
                        title="{{ __('Please correct the following errors:') }}" 
                        class="mb-4 sm:mb-6"
                    >
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-error-alert>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
