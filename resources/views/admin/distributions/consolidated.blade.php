<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Consolidated Distribution Analysis') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Comprehensive distribution analysis with advanced filtering') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Advanced Filter Panel -->
        <div>
            <form method="GET" action="{{ route('admin.distributions.consolidated') }}" class="w-full relative overflow-hidden rounded-2xl shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-cyan-500/10 pointer-events-none"></div>
                <div class="relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 p-6">
                    <!-- Filter Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 items-end mb-4">
                        <!-- Economic Year -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">{{ __('Economic Year') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <select name="economic_year_id" class="w-full appearance-none pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
                                            {{ $y->name_display ?? ($y->name ?? ($y->start_date?->format('Y') .' - '. $y->end_date?->format('Y'))) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Zilla -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">{{ __('Zilla') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <select name="zilla_id" id="zillaSelect" class="w-full appearance-none pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                                    <option value="">{{ __('All Zillas') }}</option>
                                    @foreach($zillas as $z)
                                        <option value="{{ $z->id }}" {{ $selectedZillaId == $z->id ? 'selected' : '' }}>
                                            {{ $z->name_display ?? $z->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Upazila -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">{{ __('Upazila') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <select name="upazila_id" id="upazilaSelect" class="w-full appearance-none pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                                    <option value="">{{ __('All Upazilas') }}</option>
                                    @foreach($upazilas as $u)
                                        <option value="{{ $u->id }}" {{ ($selectedUpazilaId ?? null) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name_display ?? $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Union -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">{{ __('Union') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <select name="union_id" id="unionSelect" class="w-full appearance-none pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                                    <option value="">{{ __('All Unions') }}</option>
                                    @foreach($unions as $un)
                                        <option value="{{ $un->id }}" {{ ($selectedUnionId ?? null) == $un->id ? 'selected' : '' }}>
                                            {{ $un->name_display ?? $un->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Project -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">{{ __('Project') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <select name="project_id" class="w-full appearance-none pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                                    <option value="">{{ __('All Projects') }}</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ ($selectedProjectId ?? null) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 justify-center">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-full shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-7 4h6"/></svg>
                            {{ __('Apply') }}
                        </button>
                        <a href="{{ route('admin.distributions.consolidated') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-full shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            {{ __('Reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ __('Total Applications') }}</p>
                        <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">@bn($data['pagination']['total_items'])</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ __('Approved') }}</p>
                    </div>
                    <div class="p-3 bg-blue-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Total Amount') }}</p>
                        <p class="text-3xl font-bold text-green-800 dark:text-green-200">
                            @moneybn($data['distribution']->sum('approved_amount'))
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ __('Distributed') }}</p>
                    </div>
                    <div class="p-3 bg-green-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">{{ __('Unique Projects') }}</p>
                        <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">@bn($data['distribution']->pluck('project.name')->unique()->count())</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">{{ __('Active') }}</p>
                    </div>
                    <div class="p-3 bg-purple-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-300">{{ __('Unique Organizations') }}</p>
                        <p class="text-3xl font-bold text-orange-800 dark:text-orange-200">@bn($data['distribution']->pluck('organization_name')->unique()->count())</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">{{ __('Beneficiaries') }}</p>
                    </div>
                    <div class="p-3 bg-orange-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Budget Breakdown -->
        @if(count($projectBudgetBreakdown) > 0 && $data['distribution']->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Project Budget Overview') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Budget allocation and utilization') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($projectBudgetBreakdown as $project)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                @if($project['relief_type']?->color_code)
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $project['relief_type']->color_code }}"></span>
                                @endif
                                <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $project['name'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">{{ __('Allocated') }}</span><span class="font-semibold text-gray-900 dark:text-white">{{ bn_number($project['formatted_allocated']) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">{{ __('Distributed') }}</span><span class="font-semibold text-gray-900 dark:text-white">{{ bn_number($project['formatted_distributed']) }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">{{ __('Available') }}</span><span class="font-semibold text-gray-900 dark:text-white">{{ bn_number($project['formatted_available']) }}</span></div>
                            <div class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden mt-2">
                                <div class="h-2 bg-indigo-500" style="width: {{ (int)round(($project['used_ratio'] ?? 0) * 100) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 text-center">@bn((int)round(($project['used_ratio'] ?? 0) * 100))% {{ __('utilized') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Dynamic Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Project Distribution Chart -->
            @if(isset($chartData['projectData']) && count($chartData['projectData']['labels']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Project Distribution') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Relief by project') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Zilla Distribution Chart -->
            @if(isset($chartData['zillaData']) && count($chartData['zillaData']['labels']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Zilla Distribution') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Relief by district') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="zillaChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upazila Distribution Chart -->
            @if(isset($chartData['upazilaData']) && count($chartData['upazilaData']['labels']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Upazila Distribution') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Relief by upazila') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="upazilaChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Union Distribution Chart -->
            @if(isset($chartData['unionData']) && count($chartData['unionData']['labels']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Union Distribution') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Relief by union') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="unionChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Organization Type Distribution Chart -->
            @if(isset($chartData['orgTypeData']) && count($chartData['orgTypeData']['labels']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Organization Type') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Relief by organization type') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="orgTypeChart"></canvas>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Detailed Distribution Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Detailed Distribution') }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                    @bn($data['pagination']['total_items']) {{ __('applications') }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table id="detailedTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Organization') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Zilla') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Upazila') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Union') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @if($data['distribution']->count() > 0)
                            @foreach($data['distribution'] as $application)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $application->organization_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $application->project->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $application->zilla->name_display ?? $application->zilla->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $application->upazila->name_display ?? $application->upazila->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $application->union->name_display ?? $application->union->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $application->organizationType->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                        @php
                                            $pu = $projectUnits[$application->project_id] ?? null;
                                            $isMoney = $pu['is_money'] ?? false;
                                            $unit = $pu['unit'] ?? '';
                                        @endphp
                                        @if($isMoney)
                                            @moneybn($application->approved_amount)
                                        @else
                                            @bn(number_format($application->approved_amount, 2)) {{ $unit }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ locale_is_bn() ? $application->approved_at?->translatedFormat('j F, Y') : ($application->approved_at?->format('M d, Y')) ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ __('No data available') }}</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">{{ __('Try adjusting your filters to see more results') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">—</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Organization') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Project') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Zilla') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Upazila') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Union') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.tailwindcss.min.css">
    
    <style>
        .dataTables_filter { float: right; margin-bottom: 20px; }
        .dataTables_filter label { display: flex; align-items: center; gap: 8px; font-weight: 500; color: #374151; }
        .dataTables_filter input[type="search"] { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #ffffff; color: #374151; font-size: 14px; transition: all 0.2s ease; min-width: 200px; }
        .dataTables_filter input[type="search"]:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .dark .dataTables_filter label { color: #d1d5db; }
        .dark .dataTables_filter input[type="search"] { background-color: #374151; border-color: #4b5563; color: #d1d5db; }
        .dark .dataTables_filter input[type="search"]:focus { border-color: #60a5fa; box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1); }
        .dataTables_length { margin-bottom: 20px; }
        .dataTables_length label { display: flex; align-items: center; gap: 8px; font-weight: 500; color: #374151; }
        .dataTables_length select { padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; color: #374151; font-size: 14px; }
        .dark .dataTables_length label { color: #d1d5db; }
        .dark .dataTables_length select { background-color: #374151; border-color: #4b5563; color: #d1d5db; }
        .dataTables_info { color: #6b7280; font-size: 14px; margin-top: 10px; }
        .dark .dataTables_info { color: #9ca3af; }
        .dataTables_paginate { margin-top: 20px; }
        .dataTables_paginate .paginate_button { padding: 8px 12px; margin: 0 2px; border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; color: #374151; cursor: pointer; transition: all 0.2s ease; }
        .dataTables_paginate .paginate_button:hover { background-color: #f3f4f6; border-color: #9ca3af; }
        .dataTables_paginate .paginate_button.current { background-color: #3b82f6; border-color: #3b82f6; color: #ffffff; }
        .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; }
        .dark .dataTables_paginate .paginate_button { background-color: #374151; border-color: #4b5563; color: #d1d5db; }
        .dark .dataTables_paginate .paginate_button:hover { background-color: #4b5563; }
        .dark .dataTables_paginate .paginate_button.current { background-color: #3b82f6; border-color: #3b82f6; }
        .dataTables_wrapper { padding: 0 20px 20px 20px; }
        .dt-buttons { margin-bottom: 20px; margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; }
        .dt-button { padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .dt-button:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
        @media print { .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate, .dt-buttons { display: none !important; } .dataTables_wrapper { overflow: visible !important; } table { border-collapse: collapse !important; width: 100% !important; } th, td { border: 1px solid #000 !important; padding: 8px !important; font-size: 12px !important; } th { background-color: #f3f4f6 !important; font-weight: bold !important; } }
        @media (max-width: 768px) { .dataTables_filter { float: none; margin-bottom: 15px; } .dataTables_filter input[type="search"] { min-width: 150px; } .dt-buttons { justify-content: center; } .dt-button { font-size: 12px; padding: 6px 12px; } }
    </style>
    
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const zillaSelect = document.getElementById('zillaSelect');
            const upazilaSelect = document.getElementById('upazilaSelect');
            const unionSelect = document.getElementById('unionSelect');
            const selectedUpazilaId = {{ $selectedUpazilaId ?? 0 }};
            const selectedUnionId = {{ $selectedUnionId ?? 0 }};
            
            if (zillaSelect) {
                const initialZillaId = zillaSelect.value;
                if (initialZillaId) {
                    loadUpazilasForZilla(initialZillaId, true);
                }
                
                zillaSelect.addEventListener('change', function() {
                    const zillaId = this.value;
                    upazilaSelect.innerHTML = '<option value="">{{ __('All Upazilas') }}</option>';
                    unionSelect.innerHTML = '<option value="">{{ __('All Unions') }}</option>';
                    
                    if (zillaId) {
                        loadUpazilasForZilla(zillaId, false);
                    }
                });
            }
            
            if (upazilaSelect) {
                upazilaSelect.addEventListener('change', function() {
                    const upazilaId = this.value;
                    unionSelect.innerHTML = '<option value="">{{ __('All Unions') }}</option>';
                    
                    if (upazilaId) {
                        loadUnionsForUpazila(upazilaId);
                    }
                });
            }
            
            function loadUpazilasForZilla(zillaId, isInitialLoad) {
                fetch(`/admin/upazilas-by-zilla/${zillaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(upazila => {
                            const option = document.createElement('option');
                            option.value = upazila.id;
                            option.textContent = upazila.name_display || upazila.name;
                            if (isInitialLoad && selectedUpazilaId == upazila.id) {
                                option.selected = true;
                            }
                            upazilaSelect.appendChild(option);
                        });
                        
                        if (isInitialLoad && selectedUpazilaId) {
                            loadUnionsForUpazila(selectedUpazilaId);
                        }
                    })
                    .catch(error => console.error('Error loading upazilas:', error));
            }
            
            function loadUnionsForUpazila(upazilaId) {
                fetch(`/admin/unions-by-upazila/${upazilaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(union => {
                            const option = document.createElement('option');
                            option.value = union.id;
                            option.textContent = union.name_display || union.name;
                            if (selectedUnionId == union.id) {
                                option.selected = true;
                            }
                            unionSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading unions:', error));
            }
        });
        
        const chartData = @json($chartData);
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#d1d5db' : '#374151';
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: textColor }
                }
            }
        };
        
        @if(isset($chartData['projectData']) && count($chartData['projectData']['labels']) > 0)
        new Chart(document.getElementById('projectChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($chartData['projectData']['labels']),
                datasets: [{
                    data: @json($chartData['projectData']['data']),
                    backgroundColor: ['#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { ...commonOptions, plugins: { ...commonOptions.plugins, legend: { position: 'bottom', labels: { color: textColor, padding: 20, usePointStyle: true } }, tooltip: { callbacks: { label: (ctx) => ctx.label + ': ৳' + ctx.parsed.toLocaleString() } } } }
        });
        @endif
        
        @if(isset($chartData['zillaData']) && count($chartData['zillaData']['labels']) > 0)
        new Chart(document.getElementById('zillaChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartData['zillaData']['labels']),
                datasets: [{
                    label: '{{ __('Amount (৳)') }}',
                    data: @json($chartData['zillaData']['data']),
                    backgroundColor: 'rgba(147, 51, 234, 0.8)',
                    borderColor: 'rgba(147, 51, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { color: textColor, callback: (val) => '৳' + val.toLocaleString() }, grid: { color: gridColor } }, x: { ticks: { color: textColor }, grid: { color: gridColor } } }, plugins: { ...commonOptions.plugins, tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': ৳' + ctx.parsed.y.toLocaleString() } } } }
        });
        @endif
        
        @if(isset($chartData['upazilaData']) && count($chartData['upazilaData']['labels']) > 0)
        new Chart(document.getElementById('upazilaChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartData['upazilaData']['labels']),
                datasets: [{
                    label: '{{ __('Amount (৳)') }}',
                    data: @json($chartData['upazilaData']['data']),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }]
            },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { color: textColor, callback: (val) => '৳' + val.toLocaleString() }, grid: { color: gridColor } }, x: { ticks: { color: textColor }, grid: { color: gridColor } } }, plugins: { ...commonOptions.plugins, tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': ৳' + ctx.parsed.y.toLocaleString() } } } }
        });
        @endif
        
        @if(isset($chartData['unionData']) && count($chartData['unionData']['labels']) > 0)
        new Chart(document.getElementById('unionChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartData['unionData']['labels']),
                datasets: [{
                    label: '{{ __('Amount (৳)') }}',
                    data: @json($chartData['unionData']['data']),
                    backgroundColor: 'rgba(6, 182, 212, 0.8)',
                    borderColor: 'rgba(6, 182, 212, 1)',
                    borderWidth: 1
                }]
            },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { color: textColor, callback: (val) => '৳' + val.toLocaleString() }, grid: { color: gridColor } }, x: { ticks: { color: textColor }, grid: { color: gridColor } } }, plugins: { ...commonOptions.plugins, tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': ৳' + ctx.parsed.y.toLocaleString() } } } }
        });
        @endif
        
        @if(isset($chartData['orgTypeData']) && count($chartData['orgTypeData']['labels']) > 0)
        new Chart(document.getElementById('orgTypeChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: @json($chartData['orgTypeData']['labels']),
                datasets: [{
                    data: @json($chartData['orgTypeData']['data']),
                    backgroundColor: ['#f59e0b', '#10b981', '#6366f1', '#ec4899', '#8b5cf6', '#06b6d4', '#ef4444', '#22c55e'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { ...commonOptions, plugins: { ...commonOptions.plugins, legend: { position: 'bottom', labels: { color: textColor, padding: 15, usePointStyle: true } }, tooltip: { callbacks: { label: (ctx) => ctx.label + ': ৳' + ctx.parsed.toLocaleString() } } } }
        });
        @endif
        
        $(document).ready(function() {
            $('#detailedTable').DataTable({
                dom: 'Bfrtip',
                paging: true,
                lengthChange: true,
                info: true,
                buttons: [
                    { extend: 'print', text: '<i class="fas fa-print"></i> {{ __('Print') }}', className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200', title: '{{ __('Consolidated Distribution Report') }}' },
                    { extend: 'excel', text: '<i class="fas fa-file-excel"></i> {{ __('Excel') }}', className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200', title: '{{ __('Consolidated Distribution Report') }}' },
                    { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> {{ __('PDF') }}', className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200', title: '{{ __('Consolidated Distribution Report') }}' }
                ],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ __('All') }}"]],
                responsive: true,
                language: {
                    search: "{{ __('Search:') }}",
                    lengthMenu: "{{ __('Show _MENU_ entries') }}",
                    info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                    emptyTable: "{{ __('No data available in table') }}",
                    zeroRecords: "{{ __('No matching records found') }}",
                    paginate: { first: "{{ __('First') }}", last: "{{ __('Last') }}", next: "{{ __('Next') }}", previous: "{{ __('Previous') }}" }
                },
                order: [[6, 'desc']],
                drawCallback: function(settings) {
                    var api = this.api();
                    var recordsTotal = api.page.info().recordsTotal;
                    
                    if (recordsTotal === 0) {
                        // Hide pagination and info when no data
                        $(api.table().container()).find('.dataTables_paginate').hide();
                        $(api.table().container()).find('.dataTables_info').hide();
                        
                        // Customize empty message
                        $(api.table().container()).find('.dataTables_empty').html(
                            '<div class="flex flex-col items-center justify-center py-8">' +
                            '<svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>' +
                            '</svg>' +
                            '<p class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ __('No data available') }}</p>' +
                            '<p class="text-sm text-gray-400 dark:text-gray-500 mt-1">{{ __('Try adjusting your filters to see more results') }}</p>' +
                            '</div>'
                        );
                    } else {
                        // Show pagination and info when data exists
                        $(api.table().container()).find('.dataTables_paginate').show();
                        $(api.table().container()).find('.dataTables_info').show();
                    }
                }
            });
        });
    </script>
</x-main-layout>

