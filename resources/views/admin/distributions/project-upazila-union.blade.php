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
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Project × Upazila × Union Distribution</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Detailed distribution analysis across projects, upazilas, and unions</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Smart Filter Row -->
        <div>
            <form method="GET" action="{{ route('admin.distributions.project-upazila-union') }}" class="w-full relative overflow-hidden rounded-2xl shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-cyan-500/10 pointer-events-none"></div>
                <div class="relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                        <!-- Economic Year -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Economic Year</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/></svg>
                                <select name="economic_year_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    @foreach($years as $y)
                                        <option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
                                            {{ $y->name ?? ($y->start_date?->format('Y') .' - '. $y->end_date?->format('Y')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Zilla -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Zilla</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"/></svg>
                                <select name="zilla_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="">All Zillas</option>
                                    @foreach($zillas as $z)
                                        <option value="{{ $z->id }}" {{ $selectedZillaId == $z->id ? 'selected' : '' }}>
                                            {{ $z->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Upazila -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Upazila</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <select name="upazila_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="">All Upazilas</option>
                                    @foreach($upazilas as $u)
                                        <option value="{{ $u->id }}" {{ ($selectedUpazilaId ?? null) == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Project -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Project</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <select name="project_id" class="w-full appearance-none pl-9 pr-8 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ ($selectedProjectId ?? null) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-7 4h6"/></svg>
                                Apply
                            </button>
                            <a href="{{ route('admin.distributions.project-upazila-union') }}" class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Applications</p>
                        <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">{{ $data['pagination']['total_items'] }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Approved applications</p>
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
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Total Amount</p>
                        <p class="text-3xl font-bold text-green-800 dark:text-green-200">
                            @php
                                $totalAmount = $data['distribution']->sum('approved_amount');
                                // For summary cards, we'll show money format since it's aggregated data
                            @endphp
                            ৳{{ number_format($totalAmount, 2) }}
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">Approved relief amount</p>
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
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Unique Projects</p>
                        <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">{{ $data['distribution']->pluck('project.name')->unique()->count() }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Active projects</p>
                    </div>
                    <div class="p-3 bg-purple-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Budget Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
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
                                <div class="h-2 bg-indigo-500" style="width: {{ (int)round(($project['used_ratio'] ?? 0) * 100) }}%"></div>
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
            <!-- Union Relief Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Unions by Relief Amount</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Relief distribution by union</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="unionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Project Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Distribution</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amount by project</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-80">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Zilla Distribution Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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
        </div>

        <!-- Distribution Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detailed Distribution</h3>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                        {{ $data['pagination']['total_items'] }} applications
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="detailedTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zilla</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Union</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($data['distribution'] as $application)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $application->organization_name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->project->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->zilla->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->upazila->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $application->union->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
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
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
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
                    
                    <span class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
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
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- jQuery CDN (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.tailwindcss.min.css">
    
    <!-- Custom DataTables Theme Override -->
    <style>
        /* DataTables Search Box Styling */
        .dataTables_filter {
            float: right;
            margin-bottom: 20px;
        }
        
        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #374151;
        }
        
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
        
        /* Dark mode support for search */
        .dark .dataTables_filter label {
            color: #d1d5db;
        }
        
        .dark .dataTables_filter input[type="search"] {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dark .dataTables_filter input[type="search"]:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }
        
        /* DataTables Length Selector */
        .dataTables_length {
            margin-bottom: 20px;
        }
        
        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #374151;
        }
        
        .dataTables_length select {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #ffffff;
            color: #374151;
            font-size: 14px;
        }
        
        .dark .dataTables_length label {
            color: #d1d5db;
        }
        
        .dark .dataTables_length select {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        /* DataTables Info */
        .dataTables_info {
            color: #6b7280;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .dark .dataTables_info {
            color: #9ca3af;
        }
        
        /* DataTables Pagination */
        .dataTables_paginate {
            margin-top: 20px;
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
        }
        
        .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }
        
        .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }
        
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .dark .dataTables_paginate .paginate_button {
            background-color: #374151;
            border-color: #4b5563;
            color: #d1d5db;
        }
        
        .dark .dataTables_paginate .paginate_button:hover {
            background-color: #4b5563;
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
        
        /* Print-specific styling */
        @media print {
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info,
            .dataTables_paginate,
            .dt-buttons {
                display: none !important;
            }
            
            .dataTables_wrapper {
                overflow: visible !important;
            }
            
            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            
            th, td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                font-size: 12px !important;
            }
            
            th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dataTables_filter {
                float: none;
                margin-bottom: 15px;
            }
            
            .dataTables_filter input[type="search"] {
                min-width: 150px;
            }
            
            .dt-buttons {
                justify-content: center;
            }
            
            .dt-button {
                font-size: 12px;
                padding: 6px 12px;
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
        
        // Dynamic filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const zillaSelect = document.querySelector('select[name="zilla_id"]');
            const upazilaSelect = document.querySelector('select[name="upazila_id"]');
            
            if (zillaSelect && upazilaSelect) {
                // Load upazilas for initially selected zilla
                const initialZillaId = zillaSelect.value;
                if (initialZillaId) {
                    loadUpazilasForZilla(initialZillaId);
                }
                
                zillaSelect.addEventListener('change', function() {
                    const zillaId = this.value;
                    if (zillaId) {
                        loadUpazilasForZilla(zillaId);
                    } else {
                        upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
                    }
                });
                
                function loadUpazilasForZilla(zillaId) {
                    fetch(`/admin/upazilas-by-zilla/${zillaId}`)
                        .then(response => response.json())
                        .then(data => {
                            upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
                            data.forEach(upazila => {
                                const option = document.createElement('option');
                                option.value = upazila.id;
                                option.textContent = upazila.name;
                                upazilaSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching upazilas:', error));
                }
            }
        });
        
        // Union Relief Distribution Chart
        const unionCtx = document.getElementById('unionChart').getContext('2d');
        new Chart(unionCtx, {
            type: 'bar',
            data: {
                labels: @json($unionChartData['labels']),
                datasets: [{
                    label: 'Relief Amount (৳)',
                    data: @json($unionChartData['data']),
                    backgroundColor: 'rgba(147, 51, 234, 0.8)',
                    borderColor: 'rgba(147, 51, 234, 1)',
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
                            color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
                            maxRotation: 45,
                            minRotation: 0
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
                                return context.label + ': ৳' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Project Distribution Chart
        const projectCtx = document.getElementById('projectChart').getContext('2d');
        new Chart(projectCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.projectData.labels,
                datasets: [{
                    data: chartData.projectData.data,
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

        // Zilla Distribution Chart
        const zillaCtx = document.getElementById('zillaChart').getContext('2d');
        new Chart(zillaCtx, {
            type: 'bar',
            data: {
                labels: chartData.zillaData.labels,
                datasets: [{
                    label: 'Amount (৳)',
                    data: chartData.zillaData.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
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
    </script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $('#detailedTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        title: 'Project × Upazila × Union Distribution Report',
                        messageTop: function() {
                            const economicYear = document.querySelector('select[name="economic_year_id"] option:checked')?.textContent || 'All Years';
                            const zilla = document.querySelector('select[name="zilla_id"] option:checked')?.textContent || 'All Zillas';
                            const upazila = document.querySelector('select[name="upazila_id"] option:checked')?.textContent || 'All Upazilas';
                            const project = document.querySelector('select[name="project_id"] option:checked')?.textContent || 'All Projects';
                            const dateTime = new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString();
                            
                            return '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h1 style="color: #333; margin: 0;">Relief Management System</h1>' +
                                '<h2 style="color: #666; margin: 5px 0;">Project × Upazila × Union Distribution Report</h2>' +
                                '<div style="margin: 20px 0; padding: 15px; background-color: #f5f5f5; border-radius: 5px;">' +
                                    '<h3 style="margin: 0 0 10px 0; color: #333;">Report Filters</h3>' +
                                    '<div style="display: flex; flex-wrap: wrap; gap: 20px;">' +
                                        '<div><strong>Economic Year:</strong> ' + economicYear + '</div>' +
                                        '<div><strong>Zilla:</strong> ' + zilla + '</div>' +
                                        '<div><strong>Upazila:</strong> ' + upazila + '</div>' +
                                        '<div><strong>Project:</strong> ' + project + '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<p style="color: #666; font-size: 12px;">Generated on: ' + dateTime + '</p>' +
                            '</div>';
                        },
                        messageBottom: '<p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">This report shows detailed distribution of relief applications across projects, upazilas, and unions.</p>'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        title: 'Project × Upazila × Union Distribution Report'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        title: 'Project × Upazila × Union Distribution Report'
                    }
                ],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
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
