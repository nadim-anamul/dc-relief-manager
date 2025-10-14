<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Area-wise Allocation Summary</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Summary of relief allocations by administrative areas</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <a href="{{ route('admin.exports.area-wise-relief.excel', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Smart Filter Row -->
        <div>
            <form method="GET" action="{{ route('admin.distributions.area-summary') }}" class="w-full relative overflow-hidden rounded-2xl shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 via-red-500/10 to-pink-500/10 pointer-events-none"></div>
                <div class="relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-4 items-end">
                        <!-- Economic Year -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Economic Year</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/></svg>
                                <select name="economic_year_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
                                            {{ $y->name ?? ($y->start_date?->format('Y') .' - '. $y->end_date?->format('Y')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Zilla (hidden: default to first/only zilla) -->
                        <input type="hidden" name="zilla_id" value="{{ $selectedZillaId ?? ($zillas->count() === 1 ? ($zillas->first()->id ?? '') : '') }}">

                        <!-- Project -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Project</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <select name="project_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-7 4h6"/></svg>
                                Apply
                            </button>
                            <a href="{{ route('admin.distributions.area-summary') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-300">Total Upazilas</p>
                        <p class="text-3xl font-bold text-orange-800 dark:text-orange-200">{{ $data['pagination']['total_items'] }}</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">Upazilas with allocations</p>
                    </div>
                    <div class="p-3 bg-orange-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Amount</p>
                        <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">৳{{ number_format($data['summary']->sum('total_amount'), 2) }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Total relief amount</p>
                    </div>
                    <div class="p-3 bg-blue-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Total Applications</p>
                        <p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $data['summary']->sum('application_count') }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">Approved applications</p>
                    </div>
                    <div class="p-3 bg-green-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Budget Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Budget Breakdown</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Initial budget vs distributed vs available for each project</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($projectBudgetBreakdown as $project)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($project['relief_type']?->color_code)
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $project['relief_type']->color_code }}"></span>
                                @endif
                                <span class="font-medium text-gray-900 dark:text-white">{{ $project['name'] }}</span>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Initial Budget</span><span class="font-semibold text-gray-900 dark:text-white">{{ $project['formatted_allocated'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Distributed</span><span class="font-semibold text-gray-900 dark:text-white">{{ $project['formatted_distributed'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Available</span><span class="font-semibold text-gray-900 dark:text-white">{{ $project['formatted_available'] }}</span></div>
                            <div class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                <div class="h-2 bg-orange-500" style="width: {{ (int)round(($project['used_ratio'] ?? 0) * 100) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ (int)round(($project['used_ratio'] ?? 0) * 100) }}% used</div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-sm text-gray-500 dark:text-gray-400">No project budget data available for the selected filters.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Zilla Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Zilla Distribution</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount by zilla</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="zillaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Upazila Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upazila Distribution</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount by upazila</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="upazilaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Summary Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Area-wise Summary</h3>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                        {{ $data['pagination']['total_items'] }} upazilas
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zilla</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg. Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $totalAmount = $data['summary']->sum('total_amount');
                        @endphp
                        @forelse($data['summary'] as $area)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $area->upazila->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $area->zilla->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    ৳{{ number_format($area->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $area->application_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($area->total_amount / max($area->application_count, 1), 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $totalAmount > 0 ? number_format(($area->total_amount / $totalAmount) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($data['pagination']['total_pages'] > 1)
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                    <span>Showing {{ (($data['pagination']['current_page'] - 1) * $pageSize) + 1 }} to {{ min($data['pagination']['current_page'] * $pageSize, $data['pagination']['total_items']) }} of {{ $data['pagination']['total_items'] }} results</span>
                </div>
                <div class="flex items-center space-x-2">
                    @if($data['pagination']['has_previous'])
                        <a href="{{ request()->fullUrlWithQuery(['page' => $data['pagination']['previous_page']]) }}" 
                           class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                            Previous
                        </a>
                    @else
                        <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Previous</span>
                    @endif
                    
                    <span class="px-3 py-1 text-sm bg-orange-600 text-white rounded-lg">
                        {{ $data['pagination']['current_page'] }} / {{ $data['pagination']['total_pages'] }}
                    </span>
                    
                    @if($data['pagination']['has_next'])
                        <a href="{{ request()->fullUrlWithQuery(['page' => $data['pagination']['next_page']]) }}" 
                           class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                            Next
                        </a>
                    @else
                        <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Detailed Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detailed Distribution</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Individual relief applications with organization details</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                    {{ $data['detailed']->count() }} applications
                </span>
            </div>
            <div class="overflow-x-auto">
                <table id="detailedTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zilla</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($data['detailed'] as $application)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $application->organization_name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->project?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->zilla?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->upazila?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @php
                                        $pu = $projectUnits[$application->project_id] ?? null;
                                        $isMoney = $pu['is_money'] ?? false;
                                        $unit = $pu['unit'] ?? '';
                                    @endphp
                                    @if($isMoney)
                                        ৳{{ number_format($application->approved_amount, 2) }}
                                    @else
                                        {{ number_format($application->approved_amount, 2) }} {{ $unit }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $application->approved_at?->format('M d, Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No detailed data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- DataTables CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.tailwindcss.min.css">
    <style>
        /* Custom DataTables CSS overrides */
        .dataTables_filter input[type="search"] {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #ffffff;
            color: #374151;
            font-size: 14px;
            transition: all 0.2s ease;
            min-width: 200px;
        }
        
        .dataTables_filter input[type="search"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .dark .dataTables_filter input[type="search"] {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dark .dataTables_filter input[type="search"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .dataTables_length select {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #ffffff;
            color: #374151;
            font-size: 14px;
            margin: 0 8px;
        }
        
        .dark .dataTables_length select {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dataTables_info {
            color: #6b7280;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .dark .dataTables_info {
            color: #9ca3af;
        }
        
        /* Pagination Styling */
        .dataTables_paginate {
            margin-top: 15px;
        }
        
        .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #ffffff;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }
        
        .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .dark .dataTables_paginate .paginate_button {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dark .dataTables_paginate .paginate_button:hover {
            background-color: #4b5563;
            border-color: #6b7280;
        }
        
        .dark .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        /* DataTables Wrapper */
        .dataTables_wrapper {
            padding: 0 20px 20px 20px;
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        
        /* DataTables Buttons */
        .dt-buttons {
            margin-bottom: 20px;
            margin-top: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .dt-button {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .dt-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Print-specific styles */
        @media print {
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info,
            .dataTables_paginate,
            .dt-buttons {
                display: none !important;
            }
            
            .dataTables_wrapper {
                padding: 0 !important;
            }
            
            table {
                font-size: 12px !important;
            }
            
            th, td {
                padding: 4px 8px !important;
            }
        }
    </style>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    
    <script>
        const chartData = @json($chartData);
        
        // Zilla Distribution Chart
        const zillaCtx = document.getElementById('zillaChart').getContext('2d');
        new Chart(zillaCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.zillaData.labels,
                datasets: [{
                    data: chartData.zillaData.data,
                    backgroundColor: [
                        '#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ৳' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Upazila Distribution Chart
        const upazilaCtx = document.getElementById('upazilaChart').getContext('2d');
        new Chart(upazilaCtx, {
            type: 'bar',
            data: {
                labels: chartData.upazilaData.labels,
                datasets: [{
                    label: 'Amount (৳)',
                    data: chartData.upazilaData.data,
                    backgroundColor: 'rgba(249, 115, 22, 0.8)',
                    borderColor: 'rgba(249, 115, 22, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ৳' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // DataTables Initialization
        $(document).ready(function() {
            $('#detailedTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        title: 'Area-wise Allocation Summary Report',
                        messageTop: function() {
                            let filters = [];
                            @if($selectedYearId)
                                filters.push('Economic Year: {{ $years->firstWhere("id", $selectedYearId)?->name ?? "Unknown" }}');
                            @endif
                            @if($selectedZillaId)
                                filters.push('Zilla: {{ $zillas->firstWhere("id", $selectedZillaId)?->name ?? "Unknown" }}');
                            @endif
                            @if($selectedProjectId)
                                filters.push('Project: {{ $projects->firstWhere("id", $selectedProjectId)?->name ?? "Unknown" }}');
                            @endif
                            return '<h2 style="text-align: center; margin-bottom: 20px;">Area-wise Allocation Summary Report</h2>' +
                                   (filters.length > 0 ? '<p style="text-align: center; margin-bottom: 20px;"><strong>Filters:</strong> ' + filters.join(' | ') + '</p>' : '');
                        },
                        messageBottom: '<p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">This report shows detailed distribution of relief applications across administrative areas.</p>'
                    },
                    { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200' },
                    { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200' }
                ],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                order: [[1, 'asc']]
            });
        });
    </script>
</x-main-layout>
