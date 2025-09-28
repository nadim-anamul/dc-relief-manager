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
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relief Management Dashboard</h1>
					<p class="text-sm text-gray-500 dark:text-gray-400">Comprehensive overview of relief operations and analytics</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<button onclick="refreshDashboard()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
					</svg>
					Refresh Data
				</button>
				<a href="{{ route('admin.relief-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
					Quick Actions
				</a>
			</div>
		</div>
    </x-slot>

	<div class="space-y-8">
		@if(isset($years) && isset($selectedYearId))
		<!-- Smart Filter Row (separate row) -->
		<div>
			<form id="smartFilter" method="GET" action="{{ route('dashboard') }}" class="w-full relative overflow-hidden rounded-2xl shadow-xl">
				<div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-cyan-500/10 pointer-events-none"></div>
				<div class="relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 px-5 py-3 flex items-center gap-6">
				<div class="flex items-center gap-3">
					<span class="text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Economic Year</span>
					<div class="relative">
						<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/></svg>
						<select id="economic_year_id" name="economic_year_id" class="smart-input appearance-none pl-9 pr-8 py-2 rounded-full border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
							@foreach($years as $y)
								<option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
									{{ $y->name ?? ($y->start_date?->format('Y') .' - '. $y->end_date?->format('Y')) }}
								</option>
							@endforeach
						</select>
						
					</div>
				</div>
				@if(isset($zillas))
				<div class="flex items-center gap-3">
					<span class="text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Zilla (for detail tables)</span>
					<div class="relative">
						<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"/></svg>
						<select id="zilla_id" name="zilla_id" class="smart-input appearance-none pl-9 pr-8 py-2 rounded-full border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
							<option value="">All Zillas</option>
							@foreach($zillas as $z)
								<option value="{{ $z->id }}" {{ ($selectedZillaId ?? null) == $z->id ? 'selected' : '' }}>
									{{ $z->name }}
								</option>
							@endforeach
						</select>
						
					</div>
				</div>
				@endif
				<div class="ml-auto flex items-center gap-2">
					<a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm rounded-full border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Reset</a>
					<button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-full shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-7 4h6"/></svg>
						Apply
					</button>
				</div>
				</div>
			</form>
		</div>
		@endif
		<!-- Hero Statistics Section -->
		<div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 text-white shadow-2xl">
			<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-6 lg:space-y-0">
				<div class="flex-1">
					<h2 class="text-3xl font-bold mb-2">Welcome to Relief Management System</h2>
					<p class="text-blue-100 text-lg">Real-time insights into relief operations and distribution analytics</p>
				</div>
			</div>
		</div>

		<!-- Allocation by Relief Type (Allocated vs Available vs Used) -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center gap-3">
					<div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
						<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/></svg>
					</div>
					<div>
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Allocation by Relief Type</h3>
						<p class="text-sm text-gray-500 dark:text-gray-400">Allocated vs Used vs Available for the selected year</p>
					</div>
				</div>
				<div class="flex items-center gap-2">
					<a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-sm font-medium rounded-lg transition-colors duration-200">View Projects</a>
					<!-- Sort pills -->
					<form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
						<input type="hidden" name="economic_year_id" value="{{ $selectedYearId }}" />
						@if(isset($selectedZillaId) && $selectedZillaId)
							<input type="hidden" name="zilla_id" value="{{ $selectedZillaId }}" />
						@endif
						<button name="sort" value="allocated" class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'allocated' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">Allocated</button>
						<button name="sort" value="used" class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'used' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">Used %</button>
						<button name="sort" value="available" class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'available' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">Available</button>
					</form>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
					@forelse($stats['reliefTypeAllocationStats'] as $allocation)
					<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-4">
						<div class="flex items-center justify-between">
							<div class="flex items-center gap-2">
								@if($allocation->reliefType?->color_code)
									<span class="w-3 h-3 rounded-full" style="background-color: {{ $allocation->reliefType->color_code }}"></span>
								@endif
								<span class="font-medium text-gray-900 dark:text-white">{{ $allocation->reliefType->name ?? 'Unknown Type' }}</span>
							</div>
							<span class="text-xs text-gray-500 dark:text-gray-300">{{ $allocation->project_count }} {{ $allocation->project_count == 1 ? 'project' : 'projects' }}</span>
						</div>
						<div class="mt-3 space-y-1 text-sm">
							<div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Allocated</span><span class="font-semibold text-gray-900 dark:text-white">{{ $allocation->formatted_allocated }}</span></div>
							<div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Used</span><span class="font-semibold text-gray-900 dark:text-white">{{ $allocation->formatted_used }}</span></div>
							<div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Available</span><span class="font-semibold text-gray-900 dark:text-white">{{ $allocation->formatted_available }}</span></div>
							<div class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
								<div class="h-2 bg-indigo-500" style="width: {{ (int)round(($allocation->used_ratio ?? 0) * 100) }}%"></div>
							</div>
							<div class="text-xs text-gray-500 dark:text-gray-400">{{ (int)round(($allocation->used_ratio ?? 0) * 100) }}% used</div>
						</div>
					</div>
					@empty
					<div class="col-span-full text-center text-sm text-gray-500 dark:text-gray-400">No allocations found for the selected year.</div>
					@endforelse
				</div>
			</div>
		</div>

		<!-- Relief Type Allocation Overview -->
		@if($stats['reliefTypeAllocationStats'] && $stats['reliefTypeAllocationStats']->count() > 0)
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Relief Type Allocation</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Current year active project allocations by relief type</p>
						</div>
					</div>
					<div class="flex items-center space-x-2">
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
							{{ $stats['reliefTypeAllocationStats']->count() }} types
						</span>
					</div>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
					@foreach($stats['reliefTypeAllocationStats'] as $allocation)
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
						<div class="flex items-center space-x-3">
							<div class="w-4 h-4 rounded-full" style="background-color: {{ $allocation->reliefType->color_code ?? '#6366f1' }}"></div>
							<div class="flex-1 min-w-0">
								<p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $allocation->reliefType->name ?? 'Unknown Type' }}</p>
								<p class="text-lg font-bold text-gray-900 dark:text-white">{{ $allocation->formatted_total }}</p>
								<p class="text-xs text-gray-500 dark:text-gray-400">{{ $allocation->project_count }} {{ $allocation->project_count == 1 ? 'project' : 'projects' }}</p>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endif

			<!-- Inventory Overview removed -->

		<!-- Application Status Overview -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application Status Overview</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Current status distribution of relief applications</p>
						</div>
					</div>
					<a href="{{ route('admin.relief-applications.index') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg transition-colors duration-200">
						View All
					</a>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
					<!-- Pending Applications -->
					<div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-800">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Pending Review</p>
								<p class="text-3xl font-bold text-yellow-800 dark:text-yellow-200">{{ $stats['pendingApplications'] }}</p>
								<p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Awaiting approval</p>
							</div>
							<div class="p-3 bg-yellow-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</div>

					<!-- Approved Applications -->
					<div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-sm font-medium text-green-700 dark:text-green-300">Approved</p>
								<p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $stats['approvedApplications'] }}</p>
								<p class="text-xs text-green-600 dark:text-green-400 mt-1">Successfully processed</p>
							</div>
							<div class="p-3 bg-green-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</div>

					<!-- Rejected Applications -->
					<div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-sm font-medium text-red-700 dark:text-red-300">Rejected</p>
								<p class="text-3xl font-bold text-red-800 dark:text-red-200">{{ $stats['rejectedApplications'] }}</p>
								<p class="text-xs text-red-600 dark:text-red-400 mt-1">Not eligible</p>
							</div>
							<div class="p-3 bg-red-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Analytics Dashboard -->
		<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
			<!-- Application Status Distribution -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex items-center justify-between">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
								<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
								</svg>
							</div>
							<div>
								<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application Status</h3>
								<p class="text-sm text-gray-500 dark:text-gray-400">Distribution by status</p>
							</div>
						</div>
						<div class="flex items-center space-x-2">
							<div class="w-3 h-3 bg-green-500 rounded-full"></div>
							<span class="text-xs text-gray-500 dark:text-gray-400">Approved</span>
							<div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
							<span class="text-xs text-gray-500 dark:text-gray-400">Pending</span>
							<div class="w-3 h-3 bg-red-500 rounded-full"></div>
							<span class="text-xs text-gray-500 dark:text-gray-400">Rejected</span>
						</div>
					</div>
				</div>
				<div class="p-6">
					<div class="h-80">
						<canvas id="statusChart"></canvas>
					</div>
				</div>
			</div>

			<!-- Area-wise Relief Distribution -->
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
								<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Geographic Distribution</h3>
								<p class="text-sm text-gray-500 dark:text-gray-400">Relief by administrative areas</p>
							</div>
						</div>
						<div class="flex items-center space-x-2">
							<a href="{{ route('admin.exports.area-wise-relief.excel') }}" class="inline-flex items-center px-2 py-1 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-xs font-medium rounded transition-colors duration-200">
								<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
								Excel
							</a>
							<a href="{{ route('admin.exports.area-wise-relief.pdf') }}" class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 text-xs font-medium rounded transition-colors duration-200">
								<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
								</svg>
								PDF
							</a>
						</div>
					</div>
				</div>
				<div class="p-6">
					<div class="h-80">
						<canvas id="areaChart"></canvas>
					</div>
				</div>
			</div>
		</div>

		<!-- Project × Zilla Distribution -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project × Zilla Distribution</h3>
				<a href="{{ route('admin.distributions.project-upazila', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg transition-colors duration-200">
					See All
				</a>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zilla</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved Amount</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse(($stats['projectAreaDistribution'] ?? []) as $row)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['projectNames'][$row->project_id] ?? '—') }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['zillaNames'][$row->zilla_id] ?? '—') }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($row->total_amount, 2) }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->application_count }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Project × Upazila Distribution (filtered by Zilla if selected) -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center gap-4">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project × Upazila Distribution</h3>
					@if(!empty($selectedZillaId))
						<span class="text-xs text-gray-500 dark:text-gray-400">Zilla: {{ $stats['zillaNames'][$selectedZillaId] ?? ('#'.$selectedZillaId) }}</span>
					@endif
				</div>
				<div class="flex items-center gap-2">
					<a href="{{ route('admin.distributions.project-upazila-union', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg transition-colors duration-200">
						See All
					</a>
					<a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
					   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						PDF
					</a>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
                            </tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse(($stats['projectUpazilaDistribution'] ?? []) as $row)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['projectNames'][$row->project_id] ?? '—') }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['upazilaNames'][$row->upazila_id] ?? '—') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @php
                                        $pu = $stats['projectUnits'][$row->project_id] ?? null;
                                        $isMoney = $pu['is_money'] ?? false;
                                        $unit = $pu['unit'] ?? '';
                                    @endphp
                                    @if($isMoney)
                                        ৳{{ number_format($row->total_amount, 2) }}
                                    @else
                                        {{ number_format($row->total_amount, 2) }} {{ $unit }}
                                    @endif
                                </td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->application_count }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			
			@if(isset($stats['upazilaPagination']) && $stats['upazilaPagination']['total_pages'] > 1)
			<div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
					<span>Showing {{ (($stats['upazilaPagination']['current_page'] - 1) * $pageSize) + 1 }} to {{ min($stats['upazilaPagination']['current_page'] * $pageSize, $stats['upazilaPagination']['total_items']) }} of {{ $stats['upazilaPagination']['total_items'] }} results</span>
				</div>
				<div class="flex items-center space-x-2">
					@if($stats['upazilaPagination']['has_previous'])
						<a href="{{ request()->fullUrlWithQuery(['upazila_page' => $stats['upazilaPagination']['previous_page']]) }}" 
						   class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
							Previous
						</a>
					@else
						<span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Previous</span>
					@endif
					
					<span class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
						{{ $stats['upazilaPagination']['current_page'] }} / {{ $stats['upazilaPagination']['total_pages'] }}
					</span>
					
					@if($stats['upazilaPagination']['has_next'])
						<a href="{{ request()->fullUrlWithQuery(['upazila_page' => $stats['upazilaPagination']['next_page']]) }}" 
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

		<!-- Project × Upazila × Union Distribution (filtered by Zilla if selected) -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center gap-4">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project × Upazila × Union Distribution</h3>
					@if(!empty($selectedZillaId))
						<span class="text-xs text-gray-500 dark:text-gray-400">Zilla: {{ $stats['zillaNames'][$selectedZillaId] ?? ('#'.$selectedZillaId) }}</span>
					@endif
				</div>
				<div class="flex items-center gap-2">
					<a href="{{ route('admin.distributions.project-upazila-union', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 text-sm font-medium rounded-lg transition-colors duration-200">
						See All
					</a>
					<a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
					   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						PDF
					</a>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Union</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
                            </tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse(($stats['projectUpazilaUnionDistribution'] ?? []) as $row)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['projectNames'][$row->project_id] ?? '—') }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['upazilaNames'][$row->upazila_id] ?? '—') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ($stats['unionNames'][$row->union_id] ?? '—') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @php
                                        $pu = $stats['projectUnits'][$row->project_id] ?? null;
                                        $isMoney = $pu['is_money'] ?? false;
                                        $unit = $pu['unit'] ?? '';
                                    @endphp
                                    @if($isMoney)
                                        ৳{{ number_format($row->total_amount, 2) }}
                                    @else
                                        {{ number_format($row->total_amount, 2) }} {{ $unit }}
                                    @endif
                                </td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->application_count }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			
			@if(isset($stats['upazilaUnionPagination']) && $stats['upazilaUnionPagination']['total_pages'] > 1)
			<div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
					<span>Showing {{ (($stats['upazilaUnionPagination']['current_page'] - 1) * $pageSize) + 1 }} to {{ min($stats['upazilaUnionPagination']['current_page'] * $pageSize, $stats['upazilaUnionPagination']['total_items']) }} of {{ $stats['upazilaUnionPagination']['total_items'] }} results</span>
				</div>
				<div class="flex items-center space-x-2">
					@if($stats['upazilaUnionPagination']['has_previous'])
						<a href="{{ request()->fullUrlWithQuery(['upazila_union_page' => $stats['upazilaUnionPagination']['previous_page']]) }}" 
						   class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
							Previous
						</a>
					@else
						<span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Previous</span>
					@endif
					
					<span class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
						{{ $stats['upazilaUnionPagination']['current_page'] }} / {{ $stats['upazilaUnionPagination']['total_pages'] }}
					</span>
					
					@if($stats['upazilaUnionPagination']['has_next'])
						<a href="{{ request()->fullUrlWithQuery(['upazila_union_page' => $stats['upazilaUnionPagination']['next_page']]) }}" 
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

		<!-- Coverage Gaps (conditional) -->
		@if(!empty($selectedZillaId))
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Unserved Upazilas (Selected Zilla & Year)</h3>
				</div>
				<ul class="p-6 space-y-2">
					@forelse(($stats['coverage']['unserved_upazila_ids'] ?? []) as $id)
						<li class="text-sm text-gray-900 dark:text-gray-100">{{ $stats['upazilaNames'][$id] ?? ('#'.$id) }}</li>
					@empty
						<li class="text-sm text-gray-500 dark:text-gray-400">All upazilas have distributions.</li>
					@endforelse
				</ul>
			</div>
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Unserved Unions (Selected Zilla & Year)</h3>
				</div>
				<ul class="p-6 space-y-2">
					@forelse(($stats['coverage']['unserved_union_ids'] ?? []) as $id)
						<li class="text-sm text-gray-900 dark:text-gray-100">{{ $stats['unionNames'][$id] ?? ('#'.$id) }}</li>
					@empty
						<li class="text-sm text-gray-500 dark:text-gray-400">All unions have distributions.</li>
					@endforelse
				</ul>
			</div>
		</div>
		@endif

		<!-- Duplicate Allocations -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Duplicate Allocations (Same Organization in Year)</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Allocations</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Approved</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse(($stats['duplicateAllocations'] ?? []) as $row)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->organization_name ?? '—' }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->allocations }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($row->total_approved, 2) }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No duplicates detected</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Quick Actions & Recent Activity -->
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Quick Actions Panel -->
			<div class="lg:col-span-1">
				<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
					<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
						<div class="flex items-center space-x-3">
							<div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
								<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
								</svg>
							</div>
							<div>
								<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
								<p class="text-sm text-gray-500 dark:text-gray-400">Common administrative tasks</p>
							</div>
						</div>
					</div>
					<div class="p-6 space-y-3">
						<a href="{{ route('admin.relief-applications.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-lg transition-colors duration-200 group">
							<div class="p-2 bg-blue-500 rounded-lg group-hover:scale-110 transition-transform duration-200">
								<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
								</svg>
							</div>
							<div class="ml-3">
								<p class="text-sm font-medium text-blue-900 dark:text-blue-100">New Application</p>
								<p class="text-xs text-blue-600 dark:text-blue-300">Create relief application</p>
							</div>
						</a>
						
						<a href="{{ route('admin.projects.create') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 rounded-lg transition-colors duration-200 group">
							<div class="p-2 bg-green-500 rounded-lg group-hover:scale-110 transition-transform duration-200">
								<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
								</svg>
							</div>
							<div class="ml-3">
								<p class="text-sm font-medium text-green-900 dark:text-green-100">New Project</p>
								<p class="text-xs text-green-600 dark:text-green-300">Create relief project</p>
							</div>
						</a>
						
						<a href="{{ route('admin.relief-applications.index', ['status' => 'pending']) }}" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 rounded-lg transition-colors duration-200 group">
							<div class="p-2 bg-yellow-500 rounded-lg group-hover:scale-110 transition-transform duration-200">
								<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
							<div class="ml-3">
								<p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Review Applications</p>
								<p class="text-xs text-yellow-600 dark:text-yellow-300">{{ $stats['pendingApplications'] }} pending</p>
							</div>
						</a>
						
						<a href="{{ route('admin.exports.relief-applications.excel') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/30 rounded-lg transition-colors duration-200 group">
							<div class="p-2 bg-purple-500 rounded-lg group-hover:scale-110 transition-transform duration-200">
								<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
							</div>
							<div class="ml-3">
								<p class="text-sm font-medium text-purple-900 dark:text-purple-100">Export Data</p>
								<p class="text-xs text-purple-600 dark:text-purple-300">Download reports</p>
							</div>
						</a>
					</div>
				</div>
			</div>

			<!-- Right column reserved (Monthly Trends removed) -->
			<div class="lg:col-span-2"></div>
		</div>

		<!-- Monthly Relief Distribution removed -->

		<!-- Relief Items Distribution Table removed -->

		<!-- Detailed Statistics Tables -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Area-wise Summary -->
            <div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Area-wise Allocation Summary</h3>
					<div class="flex space-x-2">
						<a href="{{ route('admin.distributions.area-summary', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" class="inline-flex items-center px-3 py-1.5 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-700 dark:text-orange-300 text-sm font-medium rounded-lg transition-colors duration-200">
							See All
						</a>
						<a href="{{ route('admin.exports.area-wise-relief.excel', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
						   class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							Excel
						</a>
						<a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
						   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							PDF
						</a>
					</div>
				</div>
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Area</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
							</tr>
						</thead>
						<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($stats['upazilaSummary'] ?? []) as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Upazila: {{ $stats['upazilaNames'][$row->upazila_id] ?? ('#'.$row->upazila_id) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($row->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->application_count }}</td>
                                </tr>
                            @empty
								<tr>
									<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				
				@if(isset($stats['upazilaSummaryPagination']) && $stats['upazilaSummaryPagination']['total_pages'] > 1)
				<div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
						<span>Showing {{ (($stats['upazilaSummaryPagination']['current_page'] - 1) * $pageSize) + 1 }} to {{ min($stats['upazilaSummaryPagination']['current_page'] * $pageSize, $stats['upazilaSummaryPagination']['total_items']) }} of {{ $stats['upazilaSummaryPagination']['total_items'] }} results</span>
					</div>
					<div class="flex items-center space-x-2">
						@if($stats['upazilaSummaryPagination']['has_previous'])
							<a href="{{ request()->fullUrlWithQuery(['upazila_page' => $stats['upazilaSummaryPagination']['previous_page']]) }}" 
							   class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
								Previous
							</a>
						@else
							<span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Previous</span>
						@endif
						
						<span class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
							{{ $stats['upazilaSummaryPagination']['current_page'] }} / {{ $stats['upazilaSummaryPagination']['total_pages'] }}
						</span>
						
						@if($stats['upazilaSummaryPagination']['has_next'])
							<a href="{{ request()->fullUrlWithQuery(['upazila_page' => $stats['upazilaSummaryPagination']['next_page']]) }}" 
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

            <!-- Union Summary -->
            <div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Union-wise Allocation Summary</h3>
					<div class="flex space-x-2">
						<a href="{{ route('admin.distributions.union-summary', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 text-sm font-medium rounded-lg transition-colors duration-200">
							See All
						</a>
						<a href="{{ route('admin.exports.area-wise-relief.pdf', ['economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}" 
						   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							PDF
						</a>
					</div>
				</div>
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-800">
							<tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Union</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
							</tr>
						</thead>
						<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($stats['unionSummary'] ?? []) as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $stats['unionNames'][$row->union_id] ?? ('#'.$row->union_id) }}
                                        @if($row->upazila_id)
                                            <span class="text-gray-500 dark:text-gray-300"> ({{ $stats['upazilaNames'][$row->upazila_id] ?? ('#'.$row->upazila_id) }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($row->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $row->application_count }}</td>
                                </tr>
                            @empty
								<tr>
									<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				
				@if(isset($stats['unionSummaryPagination']) && $stats['unionSummaryPagination']['total_pages'] > 1)
				<div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
						<span>Showing {{ (($stats['unionSummaryPagination']['current_page'] - 1) * $pageSize) + 1 }} to {{ min($stats['unionSummaryPagination']['current_page'] * $pageSize, $stats['unionSummaryPagination']['total_items']) }} of {{ $stats['unionSummaryPagination']['total_items'] }} results</span>
					</div>
					<div class="flex items-center space-x-2">
						@if($stats['unionSummaryPagination']['has_previous'])
							<a href="{{ request()->fullUrlWithQuery(['union_summary_page' => $stats['unionSummaryPagination']['previous_page']]) }}" 
							   class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
								Previous
							</a>
						@else
							<span class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">Previous</span>
						@endif
						
						<span class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
							{{ $stats['unionSummaryPagination']['current_page'] }} / {{ $stats['unionSummaryPagination']['total_pages'] }}
						</span>
						
						@if($stats['unionSummaryPagination']['has_next'])
							<a href="{{ request()->fullUrlWithQuery(['union_summary_page' => $stats['unionSummaryPagination']['next_page']]) }}" 
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

		<!-- Project Budget Remaining -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Active Project Allocations (Current Year)</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Relief Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Allocated Amount</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Economic Year</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($stats['projectAllocationStats'] as $project)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $project->name }}</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
									<div class="flex items-center">
										@if($project->reliefType->color_code)
											<div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $project->reliefType->color_code }}"></div>
										@endif
										{{ $project->reliefType->name }}
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $project->formatted_allocated_amount }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($project->economicYear)
                                        {{ $project->economicYear->name ?? ($project->economicYear->start_date?->format('Y') . ' - ' . $project->economicYear->end_date?->format('Y')) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No active projects found</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Chart.js CDN -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	
	<script>
		// Chart data from PHP
		const chartData = @json($chartData);
		
		// Application Status Pie Chart
		const statusCtx = document.getElementById('statusChart').getContext('2d');
		new Chart(statusCtx, {
			type: 'pie',
			data: {
				labels: chartData.statusData.labels,
				datasets: [{
					data: chartData.statusData.data,
					backgroundColor: chartData.statusData.colors,
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
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
						}
					}
				}
			}
		});

		// Area-wise Relief Distribution Bar Chart
		const areaCtx = document.getElementById('areaChart').getContext('2d');
		new Chart(areaCtx, {
			type: 'bar',
			data: {
				labels: chartData.areaData.map(item => item.zilla_name),
				datasets: [{
					label: 'Relief Amount (৳)',
					data: chartData.areaData.map(item => item.total_amount),
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
					}
				}
			}
		});

		// Organization Type Distribution Doughnut Chart
		const orgTypeCtx = document.getElementById('orgTypeChart').getContext('2d');
		new Chart(orgTypeCtx, {
			type: 'doughnut',
			data: {
				labels: chartData.orgTypeData.map(item => item.org_type_name || 'Not Specified'),
				datasets: [{
					data: chartData.orgTypeData.map(item => item.total_amount),
					backgroundColor: [
						'#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#8b5cf6', '#ec4899'
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
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
						}
					}
				}
			}
		});

		// Relief Type Distribution Bar Chart
		const reliefTypeCtx = document.getElementById('reliefTypeChart').getContext('2d');
		new Chart(reliefTypeCtx, {
			type: 'bar',
			data: {
				labels: chartData.reliefTypeData.map(item => item.relief_type_name),
				datasets: [{
					label: 'Relief Amount (৳)',
					data: chartData.reliefTypeData.map(item => item.total_amount),
					backgroundColor: chartData.reliefTypeData.map(item => item.color_code || '#6b7280'),
					borderColor: chartData.reliefTypeData.map(item => item.color_code || '#6b7280'),
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
					}
				}
			}
		});

		// Trends removed

		// Monthly Relief Distribution Line Chart
		const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
		new Chart(monthlyCtx, {
			type: 'line',
			data: {
				labels: chartData.monthlyData.map(item => {
					const date = new Date(item.month + '-01');
					return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short' });
				}),
				datasets: [{
					label: 'Relief Amount (৳)',
					data: chartData.monthlyData.map(item => item.total_amount),
					borderColor: 'rgba(16, 185, 129, 1)',
					backgroundColor: 'rgba(16, 185, 129, 0.1)',
					borderWidth: 2,
					fill: true,
					tension: 0.4
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
					}
				}
			}
		});

		// Refresh dashboard function
		function refreshDashboard() {
			window.location.reload();
		}
	</script>
</x-main-layout>