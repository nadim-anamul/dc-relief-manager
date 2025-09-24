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
		<!-- Hero Statistics Section -->
		<div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 text-white shadow-2xl">
			<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-6 lg:space-y-0">
				<div class="flex-1">
					<h2 class="text-3xl font-bold mb-2">Welcome to Relief Management System</h2>
					<p class="text-blue-100 text-lg">Real-time insights into relief operations and distribution analytics</p>
				</div>
				<div class="flex flex-wrap gap-4">
					<div class="text-center">
						<div class="text-3xl font-bold">{{ $stats['totalApplications'] }}</div>
						<div class="text-blue-200 text-sm">Total Applications</div>
					</div>
					<div class="text-center">
						<div class="text-3xl font-bold">৳{{ number_format($stats['totalReliefDistributed'] / 1000000, 1) }}M</div>
						<div class="text-blue-200 text-sm">Relief Distributed</div>
					</div>
					<div class="text-center">
						<div class="text-3xl font-bold">{{ $stats['activeProjects'] }}</div>
						<div class="text-blue-200 text-sm">Active Projects</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Key Performance Indicators -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
			<!-- Total Relief Distributed -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 group">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-4">
						<div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Relief Distributed</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">৳{{ number_format($stats['totalReliefDistributed'], 2) }}</p>
							<p class="text-xs text-green-600 dark:text-green-400 mt-1">↑ 12% from last month</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Total Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 group">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-4">
						<div class="p-3 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalApplications'] }}</p>
							<p class="text-xs text-blue-600 dark:text-blue-400 mt-1">↑ 8% from last month</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Pending Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 group">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-4">
						<div class="p-3 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Review</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pendingApplications'] }}</p>
							<p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Needs attention</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Active Projects -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 group">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-4">
						<div class="p-3 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['activeProjects'] }}</p>
							<p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Current year</p>
						</div>
					</div>
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

		<!-- Inventory Overview -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-2 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory Management</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Current inventory status and distribution analytics</p>
						</div>
					</div>
					<a href="{{ route('admin.relief-types.index') }}" class="inline-flex items-center px-3 py-1.5 bg-teal-100 hover:bg-teal-200 dark:bg-teal-900 dark:hover:bg-teal-800 text-teal-700 dark:text-teal-300 text-sm font-medium rounded-lg transition-colors duration-200">
						View All
					</a>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
					<!-- Total Inventory Value -->
					<div class="text-center">
						<div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl w-fit mx-auto mb-3">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
							</svg>
						</div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Value</p>
						<p class="text-2xl font-bold text-gray-900 dark:text-white">৳{{ number_format($stats['totalInventoryValue'], 2) }}</p>
					</div>

					<!-- Total Inventory Items -->
					<div class="text-center">
						<div class="p-3 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl w-fit mx-auto mb-3">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
							</svg>
						</div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</p>
						<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalInventoryItems'] }}</p>
					</div>

					<!-- Low Stock Items -->
					<div class="text-center">
						<div class="p-3 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl w-fit mx-auto mb-3">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
							</svg>
						</div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock</p>
						<p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['lowStockItems'] }}</p>
						<p class="text-xs text-red-500 dark:text-red-400 mt-1">Needs attention</p>
					</div>

					<!-- Total Distributed Items -->
					<div class="text-center">
						<div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl w-fit mx-auto mb-3">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Distributed</p>
						<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['totalDistributedItems'], 0) }}</p>
					</div>
				</div>
			</div>
		</div>

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

			<!-- Recent Activity & Trends -->
			<div class="lg:col-span-2">
				<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
					<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
							<div class="flex items-center space-x-3">
								<div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
									</svg>
								</div>
								<div>
									<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Trends</h3>
									<p class="text-sm text-gray-500 dark:text-gray-400">Relief distribution over time</p>
								</div>
							</div>
							<div class="flex items-center space-x-2">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
									+15% vs last month
								</span>
							</div>
						</div>
					</div>
					<div class="p-6">
						<div class="h-80">
							<canvas id="monthlyChart"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Monthly Relief Distribution -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Monthly Relief Distribution (Last 12 Months)</h3>
			</div>
			<div class="p-6">
				<canvas id="monthlyChart" width="800" height="400"></canvas>
			</div>
		</div>

		<!-- Relief Items Distribution Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Relief Items Distribution</h3>
				<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Distribution of relief items by type and quantity (Requested vs Approved)</p>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requested</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Amount</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($stats['reliefItemStats'] as $item)
						<tr>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
								{{ $item->item_name }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
									@if($item->item_type === 'monetary') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
									@elseif($item->item_type === 'food') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
									@elseif($item->item_type === 'medical') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
									@elseif($item->item_type === 'shelter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
									@else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
									@endif">
									{{ ucfirst($item->item_type) }}
								</span>
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
								{{ $item->item_unit }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
								{{ number_format($item->total_quantity_requested, 3) }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
								{{ number_format($item->total_quantity_approved, 3) }}
							</td>
							<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
								@if($item->item_type === 'monetary')
									৳{{ number_format($item->total_amount, 2) }}
								@else
									<span class="text-gray-500 dark:text-gray-400">Physical Item</span>
								@endif
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
								No relief items distributed yet.
							</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Detailed Statistics Tables -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<!-- Area-wise Summary -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex justify-between items-center">
						<h3 class="text-lg font-medium text-gray-900 dark:text-white">Area-wise Allocation Summary</h3>
						<div class="flex space-x-2">
							<a href="{{ route('admin.exports.area-wise-relief.excel') }}" class="btn-success text-xs">
								<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
								Excel
							</a>
							<a href="{{ route('admin.exports.area-wise-relief.pdf') }}" class="btn-danger text-xs">
								<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
								</svg>
								PDF
							</a>
						</div>
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
							@forelse($stats['areaWiseStats'] as $area)
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $area->zilla_name }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($area->total_amount, 2) }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $area->application_count }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>

			<!-- Organization Type Summary -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Organization Type Summary</h3>
				</div>
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-800">
							<tr>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization Type</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applications</th>
							</tr>
						</thead>
						<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
							@forelse($stats['orgTypeStats'] as $orgType)
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $orgType->org_type_name ?? 'Not Specified' }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($orgType->total_amount, 2) }}</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $orgType->application_count }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No data available</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
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
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $project->economicYear->name ?? 'N/A' }}</td>
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