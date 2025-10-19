<x-main-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                    {{ $title }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
                    {{ $description }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Advanced Filter Panel -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filters & Search') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Filter and search the distribution data') }}</p>
            </div>
            
            <form method="GET" action="{{ route('admin.distributions.detailed', ['type' => $type]) }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Economic Year Filter -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Economic Year') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <select name="economic_year_id" class="smart-input appearance-none pl-9 pr-8 py-2 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Years') }}</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ ($selectedYear?->id ?? null) == $year->id ? 'selected' : '' }}>
                                        {{ $year->name_display }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Zilla (hidden: default to first/only zilla) -->
                    <input type="hidden" name="zilla_id" value="{{ $selectedZillaId ?? ($zillas->count() === 1 ? ($zillas->first()->id ?? '') : '') }}">

                    <!-- Upazila Filter (for both upazila and union types) -->
                    @if($type === 'upazila' || $type === 'union')
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Upazila') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <select name="upazila_id" id="upazilaSelect" class="smart-input appearance-none pl-9 pr-8 py-2 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Upazilas') }}</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" {{ ($selectedUpazilaId ?? null) == $upazila->id ? 'selected' : '' }}>
                                        {{ app()->isLocale('bn') ? ($upazila->name_bn ?: $upazila->name) : ($upazila->name ?: $upazila->name_bn) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <!-- Union Filter (only for union type) -->
                    @if($type === 'union')
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Union') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <select name="union_id" id="unionSelect" class="smart-input appearance-none pl-9 pr-8 py-2 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('All Unions') }}</option>
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" {{ ($selectedUnionId ?? null) == $union->id ? 'selected' : '' }}>
                                        {{ app()->isLocale('bn') ? ($union->name_bn ?: $union->name) : ($union->name ?: $union->name_bn) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <!-- Page Size -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Per Page') }}</label>
                        <select name="per_page" class="smart-input appearance-none px-3 py-2 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="25" {{ ($pageSize ?? 25) == 25 ? 'selected' : '' }}>{{ bn_number(25) }}</option>
                            <option value="50" {{ ($pageSize ?? 25) == 50 ? 'selected' : '' }}>{{ bn_number(50) }}</option>
                            <option value="100" {{ ($pageSize ?? 25) == 100 ? 'selected' : '' }}>{{ bn_number(100) }}</option>
                        </select>
                    </div>
                </div>

                <!-- Search -->
                <div class="mt-6">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by project name, upazila, union, or zilla...') }}" class="smart-input pl-9 pr-4 py-2 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($search || $selectedYear || $selectedZillaId || $selectedUpazilaId || $selectedUnionId)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ __('Filters Active') }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.distributions.detailed', ['type' => $type]) }}" class="inline-flex items-center px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            {{ __('Reset') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('Apply Filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards (hidden for duplicates page) -->
        @if($type !== 'duplicates')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Records') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($data['pagination']['total_items'])</p>
                    </div>
                    <div class="p-3 bg-blue-500/10 rounded-xl">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            @moneybn($data['results']->sum('total_amount'))
                        </p>
                    </div>
                    <div class="p-3 bg-green-500/10 rounded-xl">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Applications') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($data['results']->sum('application_count'))</p>
                    </div>
                    <div class="p-3 bg-purple-500/10 rounded-xl">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Detailed Distribution Data') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Showing') }} @bn((($data['pagination']['current_page'] - 1) * $data['pagination']['per_page']) + 1) - @bn(min($data['pagination']['current_page'] * $data['pagination']['per_page'], $data['pagination']['total_items'])) {{ __('of') }} @bn($data['pagination']['total_items']) {{ __('results') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($type === 'upazila')
                        <a href="{{ route('admin.exports.distributions.detailed.upazila.excel', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Excel') }}
                        </a>
                        <a href="{{ route('admin.exports.distributions.detailed.upazila.pdf', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export PDF') }}
                        </a>
                    @elseif($type === 'union')
                        <a href="{{ route('admin.exports.distributions.detailed.union.excel', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Excel') }}
                        </a>
                        <a href="{{ route('admin.exports.distributions.detailed.union.pdf', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export PDF') }}
                        </a>
                    @elseif($type === 'duplicates')
                        <a href="{{ route('admin.exports.distributions.detailed.duplicates.excel', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Excel') }}
                        </a>
                        <a href="{{ route('admin.exports.distributions.detailed.duplicates.pdf', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export PDF') }}
                        </a>
                    @elseif($type === 'projects')
                        <a href="{{ route('admin.exports.distributions.detailed.projects.excel', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('Export Excel') }}
                        </a>
                        <a href="{{ route('admin.exports.distributions.detailed.projects.pdf', request()->query()) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Export PDF') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($type === 'duplicates')
                    <!-- Duplicate Allocations Table -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Organization') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Allocations') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Total Amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($data['results'] as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $row->organization_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                        @bn($row->allocations) {{ __('allocations') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    @moneybn($row->total_approved)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <a href="{{ route('admin.relief-applications.index', ['search' => $row->organization_name, 'economic_year_id' => $selectedYear?->id]) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-700 dark:text-orange-300 text-sm font-medium rounded-lg transition-colors duration-200 hover:shadow-sm">
                                        {{ __('View Applications') }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">{{ __('No duplicates found') }}</p>
                                        <p class="text-sm">{{ __('All organizations have single allocations') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                @elseif($type === 'projects')
                    <!-- Project Allocations Table -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Project') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Relief Type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Allocated Amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <span class="flex items-center gap-1">
                                        {{ __('Applications') }}
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="{{ __('Click to view applications') }}">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($data['results'] as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('admin.projects.show', $project->id) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors duration-200">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        {{ localized_attr($project->reliefType, 'name') ?? __('Unknown Type') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    @moneybn($project->allocated_amount)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($project->application_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            @bn($project->application_count) {{ __('applications') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <a href="{{ route('admin.relief-applications.index', array_filter([
                                        'economic_year_id' => $selectedYear?->id,
                                        'project_id' => $project->id,
                                        'status' => 'approved'
                                    ])) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-900 dark:hover:bg-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-medium rounded-lg transition-colors duration-200 hover:shadow-sm">
                                        {{ __('View Applications') }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <p class="text-lg font-medium">{{ __('No projects found') }}</p>
                                        <p class="text-sm">{{ __('No active projects with allocations') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <!-- Default Distribution Table (upazila/union) -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Project') }}</th>
                                @if($type === 'union')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Union') }}</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Upazila') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Zilla') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Total Amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    <span class="flex items-center gap-1">
                                        {{ __('Applications') }}
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="{{ __('Click to view filtered applications') }}">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($data['results'] as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.projects.show', $row->project_id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline transition-colors duration-200">
                                            {{ $data['projectNames'][$row->project_id] ?? ('Project #'.$row->project_id) }}
                                        </a>
                                    </td>
                                    @if($type === 'union')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $data['unionNames'][$row->union_id] ?? ('Union #'.$row->union_id) }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $data['upazilaNames'][$row->upazila_id] ?? ('Upazila #'.$row->upazila_id) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $data['zillaNames'][$row->zilla_id] ?? ('Zilla #'.$row->zilla_id) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                        @moneybn($row->total_amount)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        @if($row->application_count > 0)
                                            <a href="{{ route('admin.relief-applications.index', array_filter([
                                                'economic_year_id' => $selectedYear?->id,
                                                'zilla_id' => $row->zilla_id,
                                                'upazila_id' => $row->upazila_id,
                                                'union_id' => $type === 'union' ? $row->union_id : null,
                                                'project_id' => $row->project_id,
                                                'status' => 'approved'
                                            ])) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg transition-colors duration-200 hover:shadow-sm">
                                                @bn($row->application_count)
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        @else
                                            @bn($row->application_count)
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $type === 'union' ? '6' : '5' }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No data found') }}</p>
                                            <p class="text-sm">{{ __('Try adjusting your filters or search criteria') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Pagination -->
            @if($data['pagination']['total_pages'] > 1)
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                        <span>
                            {{ __('Showing') }} @bn((($data['pagination']['current_page'] - 1) * $data['pagination']['per_page']) + 1) 
                            {{ __('to') }} @bn(min($data['pagination']['current_page'] * $data['pagination']['per_page'], $data['pagination']['total_items'])) 
                            {{ __('of') }} @bn($data['pagination']['total_items']) {{ __('results') }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($data['pagination']['current_page'] > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $data['pagination']['current_page'] - 1]) }}" 
                               class="px-3 py-1 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                {{ __('Previous') }}
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">{{ __('Previous') }}</span>
                        @endif

                        @for($i = max(1, $data['pagination']['current_page'] - 2); $i <= min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 2); $i++)
                            @if($i == $data['pagination']['current_page'])
                                <span class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-lg">{{ $i }}</span>
                            @else
                                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                   class="px-3 py-1 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        @if($data['pagination']['current_page'] < $data['pagination']['total_pages'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $data['pagination']['current_page'] + 1]) }}" 
                               class="px-3 py-1 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                {{ __('Next') }}
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">{{ __('Next') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-main-layout>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const upazilaSelect = document.getElementById('upazilaSelect');
    const unionSelect = document.getElementById('unionSelect');
    const selectedUnionId = {{ $selectedUnionId ?? 'null' }};
    const selectedUpazilaId = {{ $selectedUpazilaId ?? 'null' }};
    const isBn = {{ app()->isLocale('bn') ? 'true' : 'false' }};

    function loadUnionsForUpazila(upazilaId, setSelected) {
        if (!unionSelect) return; // guard clause
        unionSelect.innerHTML = '<option value="">{{ __('All Unions') }}</option>'; // reset
        if (!upazilaId) return;
        fetch(`/admin/unions-by-upazila/${upazilaId}`)
            .then(r => r.json())
            .then(list => {
                list.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.id;
                    opt.textContent = isBn ? (u.name_bn || u.name || '') : (u.name || u.name_bn || '');
                    if (setSelected && selectedUnionId && Number(selectedUnionId) === Number(u.id)) opt.selected = true; // preserve selection
                    unionSelect.appendChild(opt);
                });
            })
            .catch(() => {});
    }

    if (upazilaSelect) {
        upazilaSelect.addEventListener('change', function() { loadUnionsForUpazila(this.value, false); });
        if (selectedUpazilaId) loadUnionsForUpazila(selectedUpazilaId, true); // initial load
    }
});
</script>
