<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'DC Relief Manager') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	
	<!-- Accessibility Helpers -->
	<x-accessibility-helpers />
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
	<div class="min-h-screen flex">
		<!-- Sidebar -->
		<div class="hidden md:flex md:w-64 md:flex-col">
			<div class="flex flex-col flex-grow pt-5 bg-white dark:bg-gray-800 overflow-y-auto border-r border-gray-200 dark:border-gray-700">
				<!-- Logo -->
				<div class="flex items-center flex-shrink-0 px-4">
					<a href="{{ route('dashboard') }}" class="flex items-center">
						<div class="flex-shrink-0">
							<svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
							</svg>
						</div>
						<div class="ml-3">
							<h1 class="text-xl font-bold text-gray-900 dark:text-white">DC Relief</h1>
						</div>
					</a>
				</div>

				<!-- Navigation -->
				<div class="mt-8 flex-grow flex flex-col">
					<nav class="flex-1 px-2 space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
							<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
							</svg>
                            {{ __('Dashboard') }}
						</a>
						
						<!-- Applications Group -->
						<div class="space-y-1">
							<div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ __('Applications') }}
							</div>
							
							@if(auth()->user()->hasAnyPermission(['relief-applications.create', 'relief-applications.create-own']))
							<a href="{{ route('relief-applications.create') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('relief-applications.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
								</svg>
                                {{ __('Create Application') }}
							</a>
							@endif
							
							@if(auth()->user()->hasAnyPermission(['relief-applications.create-own', 'relief-applications.view-own']) && !auth()->user()->hasAnyRole(['super-admin', 'district-admin']))
							<a href="{{ route('relief-applications.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('relief-applications.index') || request()->routeIs('relief-applications.show') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
                                {{ __('My Applications') }}
							</a>
							@endif
							
							@if(auth()->user()->hasAnyRole(['super-admin', 'district-admin']))
							<a href="{{ route('admin.relief-applications.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.relief-applications.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
                                {{ __('Application Reviews') }}
							</a>
							@endif
						</div>
						
					@if(auth()->user()->hasAnyRole(['super-admin', 'district-admin', 'data-entry']))
					<!-- Projects top-level after Applications -->
					@if(auth()->user()->hasAnyPermission(['projects.view', 'projects.create', 'projects.update']))
					<a href="{{ route('admin.projects.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.projects.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
						<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
						{{ __('Projects Management') }}
					</a>
					@endif
					
					<!-- System Management -->
					@if(auth()->user()->hasAnyPermission(['relief-types.manage', 'economic-years.manage', 'organization-types.manage']))
						<div class="space-y-1">
							<div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ __('System Management') }}
							</div>
							
							<a href="{{ route('admin.relief-types.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.relief-types.*') || request()->routeIs('admin.economic-years.*') || request()->routeIs('admin.organization-types.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
								</svg>
								{{ __('Relief Type Management') }}
							</a>

						<!-- Administrative Divisions moved under System Management -->
						<a href="{{ route('admin.zillas.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.zillas.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
							<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
							</svg>
							প্রশাসনিক বিভাগ যোগ/পরিবর্তন
						</a>
							
						</div>
					@endif
						
						<!-- Analytics & Reports Group -->
						@if(auth()->user()->hasAnyPermission(['exports.access']))
						<div class="space-y-1">
							<div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ __('Analytics & Reports') }}
							</div>
							
							<a href="{{ route('admin.distributions.consolidated') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.distributions.consolidated') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
								</svg>
                                <span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Consolidated Analysis') }}</span>
							</a>
							<a href="{{ route('admin.distributions.detailed', ['type' => 'duplicates']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.distributions.detailed') && request('type') === 'duplicates' ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
								</svg>
                                <span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Duplicate Allocations') }}</span>
							</a>
							
							<a href="{{ route('admin.distributions.detailed', ['type' => 'upazila']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.distributions.detailed') && request('type') === 'upazila' ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
								</svg>
                                <span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Upazila Distribution') }}</span>
							</a>
							
							<a href="{{ route('admin.distributions.detailed', ['type' => 'union']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.distributions.detailed') && request('type') === 'union' ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
								</svg>
                                <span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Union Distribution') }}</span>
							</a>
							
							<a href="{{ route('admin.distributions.detailed', ['type' => 'projects']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.distributions.detailed') && request('type') === 'projects' ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
								</svg>
                                <span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Project Allocations') }}</span>
							</a>
						</div>
						@endif
					@endif
						
						<!-- User Management Group -->
						@if(auth()->user()->hasAnyPermission(['users.manage', 'roles.manage', 'permissions.manage']))
						<div class="space-y-1">
							<div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ __('User Management') }}
							</div>
							
							@if(auth()->user()->hasPermissionTo('users.manage'))
							<a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
								</svg>
                                {{ __('Users') }}
							</a>
							@endif
							
							@if(auth()->user()->hasPermissionTo('roles.manage'))
							<a href="{{ route('admin.roles.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.roles.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
								</svg>
                                {{ __('Roles') }}
							</a>
							@endif
							
							@if(auth()->user()->hasPermissionTo('permissions.manage'))
							<a href="{{ route('admin.permissions.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.permissions.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
								</svg>
                                {{ __('Permissions') }}
							</a>
							@endif
						</div>
						@endif
						
						<!-- System Administration Group -->
						@if(auth()->user()->hasPermissionTo('audit-logs.view'))
						<div class="space-y-1">
							<div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                {{ __('System Administration') }}
							</div>
						<!-- Language Switcher inside System Administration -->
						<div class="px-2 py-2">
							<div class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
								<a href="{{ route('locale.switch', ['lang' => 'bn']) }}" class="px-2 py-1 rounded {{ app()->isLocale('bn') ? 'bg-gray-200 dark:bg-gray-700 font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">বাংলা</a>
								<span class="text-gray-400">|</span>
								<a href="{{ route('locale.switch', ['lang' => 'en']) }}" class="px-2 py-1 rounded {{ app()->isLocale('en') ? 'bg-gray-200 dark:bg-gray-700 font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">English</a>
							</div>
						</div>
							<a href="{{ route('admin.audit-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
								<svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
								</svg>
                                {{ __('Audit Logs') }}
							</a>
						</div>
						@endif
					</nav>
				</div>

				<!-- User Menu -->
				<div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
					<div class="flex items-center">
						<div class="flex-shrink-0">
							<div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
								<span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
							</div>
						</div>
						<div class="ml-3">
							<p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
							<p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
							@if(Auth::user()->roles->count() > 0)
							<p class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ Auth::user()->roles->first()->name }}</p>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Main Content -->
		<div class="flex flex-col w-0 flex-1 overflow-hidden">
			<!-- Top Navigation -->
			<div class="relative z-10 flex-shrink-0 flex h-16 bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700">
				<!-- Mobile top row: logo left, hamburger right -->
				<div class="flex items-center justify-between w-full px-4 md:hidden">
					<a href="{{ route('dashboard') }}" class="flex items-center">
						<svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
						</svg>
						<span class="ml-2 text-lg font-semibold text-gray-900 dark:text-white">DC Relief</span>
					</a>
					<!-- Hamburger on the right -->
					<x-mobile-navigation />
				</div>

				<!-- Desktop/top row content hidden on mobile -->
				<div class="hidden md:flex flex-1 px-4 justify-between">
					<div class="flex-1 flex">
						<div class="w-full flex md:ml-0">
							<div class="relative w-full text-gray-400 focus-within:text-gray-600 dark:focus-within:text-gray-300" 
								 x-data="searchComponent()">
								<div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
									<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
									</svg>
								</div>
								<input 
									id="globalSearch"
									x-model="searchTerm" 
									@input.debounce.300ms="search()" 
									@focus="showResultsOnFocus()"
									@blur="setTimeout(() => showResults = false, 200)"
									class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-0 focus:border-transparent bg-transparent" 
									placeholder="{{ __('Search applications, projects, areas...') }}" 
									type="search">
								
								<!-- Search Results Dropdown -->
								<div x-show="showResults" 
									 x-transition:enter="transition ease-out duration-100"
									 x-transition:enter-start="transform opacity-0 scale-95"
									 x-transition:enter-end="transform opacity-100 scale-100"
									 x-transition:leave="transition ease-in duration-75"
									 x-transition:leave-start="transform opacity-100 scale-100"
									 x-transition:leave-end="transform opacity-0 scale-95"
									 class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-96 overflow-y-auto"
									 style="display: none;">
									<div x-show="isLoading" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
										{{ __('Searching...') }}
									</div>
									<div x-show="!isLoading && searchResults.length === 0 && searchTerm.length >= 2" 
										 class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
										{{ __('No results found') }}
									</div>
									<template x-for="result in searchResults" :key="result.id">
										<a :href="result.url" 
										   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
											<div class="flex items-start space-x-3">
												<div class="flex-shrink-0">
													<div class="w-8 h-8 rounded-full flex items-center justify-center" :class="result.icon_bg">
														<svg class="w-4 h-4" :class="result.icon_color" fill="none" stroke="currentColor" viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="result.icon_path"></path>
														</svg>
													</div>
												</div>
												<div class="flex-1 min-w-0">
													<p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="result.title"></p>
													<p class="text-sm text-gray-500 dark:text-gray-400 truncate" x-text="result.subtitle"></p>
												</div>
											</div>
										</a>
									</template>
								</div>
							</div>
						</div>
					</div>
					
					<div class="ml-4 flex items-center md:ml-6">
						<!-- Theme Toggle -->
						<button @click="darkMode = !darkMode" class="p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
							<svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
							</svg>
							<svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
							</svg>
						</button>

						<!-- Profile Dropdown -->
						<div class="ml-3 relative" x-data="{ open: false }">
							<div>
								<button @click="open = !open" class="max-w-xs bg-white dark:bg-gray-800 flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
									<span class="sr-only">Open user menu</span>
									<div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
										<span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
									</div>
								</button>
							</div>
							
							<div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none">
								<div class="py-1">
									<a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Your Profile</a>
									<form method="POST" action="{{ route('logout') }}">
										@csrf
										<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Sign out</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Page Content -->
			<main id="main-content" class="flex-1 relative overflow-y-auto focus:outline-none">
				<div class="py-6">
					<div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
						@isset($header)
							<div class="mb-6">
								<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $header }}</h1>
							</div>
						@endisset
						
						<!-- Success Messages -->
						@if (session('success'))
							<x-success-alert 
                                title="Success!" 
                                :message="session('success')" 
                                class="mb-4"
                            />
						@endif

						<!-- Error Messages -->
						@if (session('error'))
							<x-error-alert 
                                type="error" 
                                title="Error!" 
                                :message="session('error')" 
                                class="mb-4"
                            />
						@endif

						<!-- Validation Errors -->
						@if ($errors->any())
							<x-error-alert 
                                type="error" 
                                title="Please correct the following errors:" 
                                class="mb-4"
                            >
								<ul class="mt-2 list-disc list-inside text-sm">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</x-error-alert>
						@endif
						
						{{ $slot }}
					</div>
				</div>
			</main>
		</div>
	</div>

	<!-- Mobile Sidebar -->
	<div x-ref="sidebar" class="fixed inset-0 flex z-40 md:hidden -translate-x-full transition-transform duration-300 ease-in-out">
		<div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800">
			<div class="absolute top-0 right-0 -mr-12 pt-2">
				<button @click="$refs.sidebar.classList.add('-translate-x-full')" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
					<svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</button>
			</div>
			
			<div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
				<div class="flex-shrink-0 flex items-center px-4">
					<div class="flex items-center">
						<svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
						</svg>
						<div class="ml-3">
							<h1 class="text-xl font-bold text-gray-900 dark:text-white">DC Relief</h1>
						</div>
					</div>
				</div>
				
				<nav class="mt-5 px-2 space-y-1">
					<a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
						<svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
						</svg>
						Dashboard
					</a>
				</nav>
			</div>
		</div>
		
		<div class="flex-shrink-0 w-14"></div>
	</div>

	<!-- Global Search Component and Keyboard Shortcuts -->
	<script>
		function searchComponent() {
			return {
				searchTerm: '',
				showResults: false,
				searchResults: [],
				isLoading: false,
				init() {
					// Ensure searchResults is always an array
					this.searchResults = this.searchResults || [];
				},
				async search() {
					if (this.searchTerm.length < 2) {
						this.showResults = false;
						return;
					}
					this.isLoading = true;
					try {
						const response = await fetch('/search?q=' + encodeURIComponent(this.searchTerm), {
							headers: {
								'X-Requested-With': 'XMLHttpRequest',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
							},
							credentials: 'same-origin'
						});
						if (!response.ok) {
							throw new Error('HTTP error! status: ' + response.status);
						}
						this.searchResults = await response.json();
						this.showResults = true;
					} catch (error) {
						console.error('Search error:', error);
						this.searchResults = [];
					} finally {
						this.isLoading = false;
					}
				},
				showResultsOnFocus() {
					// Safe check for searchResults length
					this.showResults = this.searchResults && this.searchResults.length > 0;
				}
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			// Add keyboard shortcuts
			document.addEventListener('keydown', function(e) {
				// Ctrl/Cmd + K for search focus
				if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
					e.preventDefault();
					const searchInput = document.getElementById('globalSearch');
					if (searchInput) {
						searchInput.focus();
					}
				}
			});
		});
	</script>
</body>
</html>
