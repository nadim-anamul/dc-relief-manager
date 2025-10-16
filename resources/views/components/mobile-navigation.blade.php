<!-- Mobile menu -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- Mobile menu button -->
    <button 
        @click="open = !open"
        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
        aria-expanded="false"
    >
        <span class="sr-only">Open main menu</span>
        <!-- Hamburger icon -->
        <svg x-show="!open" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <!-- Close icon -->
        <svg x-show="open" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile menu overlay -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 md:hidden"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="open = false"></div>
        
        <!-- Mobile menu panel -->
        <div 
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex-1 flex flex-col max-w-xs w-full h-full min-h-0 bg-white dark:bg-gray-800 overflow-hidden"
        >
            <!-- Mobile menu header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center" @click="open = false">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="ml-2 text-lg font-semibold text-gray-900 dark:text-white">DC Relief</span>
                </a>
                <button @click="open = false" class="p-2 rounded-md text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Utility controls inside menu: search + theme -->
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="mb-3">
                    <div class="relative w-full text-gray-400 focus-within:text-gray-600 dark:focus-within:text-gray-300" x-data="searchComponent()">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input id="mobileMenuSearch"
                               x-model="searchTerm"
                               @input.debounce.300ms="search()"
                               @focus="showResultsOnFocus()"
                               @blur="setTimeout(() => showResults = false, 200)"
                               class="block w-full pl-10 pr-3 py-2 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="{{ __('Search applications, projects, areas...') }}"
                               type="search">
                        <div x-show="showResults"
                             x-transition
                             class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                             style="display: none;">
                            <div x-show="isLoading" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Searching...') }}
                            </div>
                            <div x-show="!isLoading && searchResults.length === 0 && searchTerm.length >= 2"
                                 class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('No results found') }}
                            </div>
                            <template x-for="result in searchResults" :key="result.id">
                                <a :href="result.url" @click="open = false"
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
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ __('Theme') }}</span>
                    <button @click="darkMode = !darkMode" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu navigation -->
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto min-h-0">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <a href="{{ route('relief-applications.create') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('relief-applications.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Create Application') }}
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyPermission(['relief-applications.create-own', 'relief-applications.view-own']) && !auth()->user()->hasAnyRole(['super-admin', 'district-admin']))
                    <a href="{{ route('relief-applications.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('relief-applications.index') || request()->routeIs('relief-applications.show') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('My Applications') }}
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasAnyRole(['super-admin', 'district-admin']))
                    <a href="{{ route('admin.relief-applications.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.relief-applications.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Application Reviews') }}
                    </a>
                    @endif
                </div>

                @if(auth()->user()->hasAnyRole(['super-admin', 'district-admin']))
                <!-- Projects top-level after Applications -->
                <a href="{{ route('admin.projects.index') }}" 
                   class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.projects.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ __('Projects Management') }}
                </a>

                <!-- System Management -->
                <div class="space-y-1">
                    <div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('System Management') }}
                    </div>
                    
                    <a href="{{ route('admin.relief-types.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.relief-types.*') || request()->routeIs('admin.economic-years.*') || request()->routeIs('admin.organization-types.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ __('Relief Type Management') }}
                    </a>
                    
                    <a href="{{ route('admin.zillas.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.zillas.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        প্রশাসনিক বিভাগ যোগ/পরিবর্তন
                    </a>

                    
                    
                </div>
                @endif

                <!-- User Management Group -->
                @if(auth()->user()->hasAnyPermission(['users.manage', 'roles.manage', 'permissions.manage']))
                <div class="space-y-1">
                    <div class="px-2 py-1 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('User Management') }}
                    </div>
                    
                    @if(auth()->user()->hasPermissionTo('users.manage'))
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Users
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermissionTo('roles.manage'))
                    <a href="{{ route('admin.roles.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.roles.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ __('Roles') }}
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermissionTo('permissions.manage'))
                    <a href="{{ route('admin.permissions.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.permissions.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    
                    <a href="{{ route('admin.audit-logs.index') }}" 
                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        {{ __('Audit Logs') }}
                    </a>
                </div>
                @endif
            </nav>

            <!-- Mobile menu footer -->
            <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-4">
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
</div>
