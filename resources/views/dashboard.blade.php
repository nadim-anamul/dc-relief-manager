<x-main-layout>
    <x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
			<div class="flex space-x-3">
				<button onclick="refreshDashboard()" class="btn-secondary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
					</svg>
					Refresh
				</button>
			</div>
		</div>
    </x-slot>

	<div class="space-y-6">
		<!-- Key Statistics Cards -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
			<!-- Total Relief Distributed -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
						<svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Relief Distributed</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">৳{{ number_format($stats['totalReliefDistributed'], 2) }}</p>
					</div>
				</div>
			</div>

			<!-- Total Applications -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['totalApplications'] }}</p>
					</div>
				</div>
			</div>

			<!-- Remaining Project Budget -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
						<svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Remaining Budget</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">৳{{ number_format($stats['remainingProjectBudget'], 2) }}</p>
					</div>
				</div>
			</div>

			<!-- Active Projects -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
						<svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['activeProjects'] }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Inventory Statistics Cards -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
			<!-- Total Inventory Value -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
						<svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Inventory Value</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">৳{{ number_format($stats['totalInventoryValue'], 2) }}</p>
					</div>
				</div>
			</div>

			<!-- Total Inventory Items -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-teal-100 dark:bg-teal-900 rounded-lg">
						<svg class="w-8 h-8 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inventory Items</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['totalInventoryItems'] }}</p>
					</div>
				</div>
			</div>

			<!-- Low Stock Items -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
						<svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock Items</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['lowStockItems'] }}</p>
					</div>
				</div>
			</div>

			<!-- Total Distributed Items -->
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
						<svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Items Distributed</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['totalDistributedItems'], 0) }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Application Status Overview -->
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<div class="card p-6">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Applications</p>
						<p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $stats['pendingApplications'] }}</p>
					</div>
					<div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
						<svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
				</div>
			</div>

			<div class="card p-6">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved Applications</p>
						<p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $stats['approvedApplications'] }}</p>
					</div>
					<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
						<svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
				</div>
			</div>

			<div class="card p-6">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejected Applications</p>
						<p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $stats['rejectedApplications'] }}</p>
					</div>
					<div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
						<svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
                </div>
            </div>
        </div>
    </div>

		<!-- Charts Section -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<!-- Application Status Pie Chart -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Status Distribution</h3>
				</div>
				<div class="p-6">
					<canvas id="statusChart" width="400" height="300"></canvas>
				</div>
			</div>

			<!-- Area-wise Relief Distribution -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Area-wise Relief Distribution</h3>
				</div>
				<div class="p-6">
					<canvas id="areaChart" width="400" height="300"></canvas>
				</div>
			</div>
		</div>

		<!-- Organization Type and Relief Type Charts -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<!-- Organization Type Distribution -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Organization Type Distribution</h3>
				</div>
				<div class="p-6">
					<canvas id="orgTypeChart" width="400" height="300"></canvas>
				</div>
			</div>

			<!-- Relief Type Distribution -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Relief Type Distribution</h3>
				</div>
				<div class="p-6">
					<canvas id="reliefTypeChart" width="400" height="300"></canvas>
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
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Remaining Fund per Project</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Relief Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remaining Budget</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($stats['projectBudgetStats'] as $project)
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
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">৳{{ number_format($project->budget, 2) }}</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $project->budget > 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
										{{ $project->budget > 0 ? 'Active' : 'Exhausted' }}
									</span>
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