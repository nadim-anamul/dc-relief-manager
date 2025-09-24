<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
					<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
					</svg>
				</div>
				<div>
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relief Applications</h1>
					<p class="text-sm text-gray-500 dark:text-gray-400">Manage and review relief applications</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<!-- Export Buttons -->
				<div class="flex gap-2">
					<a href="{{ route('admin.exports.relief-applications.excel', request()->query()) }}" 
						class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						Export Excel
					</a>
					<a href="{{ route('admin.exports.relief-applications.pdf', request()->query()) }}" 
						class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
						</svg>
						Export PDF
					</a>
				</div>
				
				<a href="{{ route('relief-applications.index') }}" 
					class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
					</svg>
					View Public Applications
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Filter Section -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
			<div class="flex items-center justify-between mb-4">
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Applications</h3>
				@if(request('status') || request('relief_type_id') || request('organization_type_id') || request('zilla_id'))
					<a href="{{ route('admin.relief-applications.index') }}" 
						class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
						<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
						Clear Filters
					</a>
				@endif
			</div>
			<form method="GET" action="{{ route('admin.relief-applications.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
				<div>
					<label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Status
					</label>
					<select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
						<option value="">All Status</option>
						<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
						<option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
						<option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
					</select>
				</div>
				<div>
					<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Relief Type
					</label>
					<select name="relief_type_id" id="relief_type_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
						<option value="">All Relief Types</option>
						@foreach($reliefTypes as $reliefType)
							<option value="{{ $reliefType->id }}" {{ request('relief_type_id') == $reliefType->id ? 'selected' : '' }}>
								{{ $reliefType->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div>
					<label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Organization Type
					</label>
					<select name="organization_type_id" id="organization_type_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
						<option value="">All Organization Types</option>
						@foreach($organizationTypes as $organizationType)
							<option value="{{ $organizationType->id }}" {{ request('organization_type_id') == $organizationType->id ? 'selected' : '' }}>
								{{ $organizationType->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div>
					<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Zilla
					</label>
					<select name="zilla_id" id="zilla_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
						<option value="">All Zillas</option>
						@foreach($zillas as $zilla)
							<option value="{{ $zilla->id }}" {{ request('zilla_id') == $zilla->id ? 'selected' : '' }}>
								{{ $zilla->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="md:col-span-2 lg:col-span-4 flex justify-end">
					<button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
						</svg>
						Apply Filters
					</button>
				</div>
			</form>
		</div>

		<!-- Stats Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
			<!-- Total Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
							<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalApplications }}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Pending Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
							<svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingApplications }}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Approved Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
							<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $approvedApplications }}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Rejected Applications -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
							<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejected</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rejectedApplications }}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Total Approved Amount -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
							<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Approved</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">৳{{ number_format($totalApprovedAmount, 2) }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Applications Table -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Applications</h3>
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
							{{ $reliefApplications->total() }} total
						</span>
					</div>
					<div class="flex items-center space-x-2">
						<span class="text-sm text-gray-500 dark:text-gray-400">Showing {{ $reliefApplications->firstItem() ?? 0 }} to {{ $reliefApplications->lastItem() ?? 0 }} of {{ $reliefApplications->total() }}</span>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Organization</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subject</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Relief Type</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Approved</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
							<th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($reliefApplications as $application)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="flex items-center">
										<div class="flex-shrink-0">
											<div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
												<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
												</svg>
											</div>
										</div>
										<div class="ml-4">
											<div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->organization_name }}</div>
											@if($application->organizationType)
												<div class="text-xs text-gray-500 dark:text-gray-400">{{ $application->organizationType->name }}</div>
											@endif
										</div>
									</div>
								</td>
								<td class="px-6 py-5">
									<div class="text-sm text-gray-900 dark:text-white font-medium">{{ Str::limit($application->subject, 35) }}</div>
									@if(strlen($application->subject) > 35)
										<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($application->subject, 50) }}</div>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									@if($application->reliefType)
										<div class="flex items-center">
											@if($application->reliefType->color_code)
												<div class="w-3 h-3 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $application->reliefType->color_code }}"></div>
											@endif
											<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->reliefType->name }}</div>
										</div>
									@else
										<div class="text-sm text-gray-500 dark:text-gray-400">Not specified</div>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $application->formatted_amount }}</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									@if($application->approved_amount)
										<div class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $application->formatted_approved_amount }}</div>
										@if($application->project)
											<div class="text-xs text-gray-500 dark:text-gray-400">From: {{ Str::limit($application->project->name, 20) }}</div>
										@endif
									@else
										<div class="text-sm text-gray-400 dark:text-gray-500">Not approved</div>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $application->status_badge_class }}">
										{{ $application->status_display }}
									</span>
									@if($application->approvedBy)
										<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">By: {{ Str::limit($application->approvedBy->name, 15) }}</div>
									@elseif($application->rejectedBy)
										<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">By: {{ Str::limit($application->rejectedBy->name, 15) }}</div>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->date->format('M d, Y') }}</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">{{ $application->created_at->format('M d, Y') }}</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
									<div class="flex justify-end space-x-2">
										<a href="{{ route('admin.relief-applications.show', $application) }}" 
											class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors duration-200"
											title="View Details">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										@if(auth()->user()->hasPermissionTo('relief-applications.approve') || auth()->user()->hasPermissionTo('relief-applications.reject'))
											<a href="{{ route('admin.relief-applications.edit', $application) }}" 
												class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-lg transition-colors duration-200"
												title="Review & Approve">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
												</svg>
											</a>
										@endif
										@if($application->application_file)
											<a href="{{ $application->file_url }}" target="_blank" 
												class="inline-flex items-center justify-center w-8 h-8 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 hover:bg-green-100 dark:hover:bg-green-900 rounded-lg transition-colors duration-200"
												title="Download File">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
												</svg>
											</a>
										@endif
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" class="px-6 py-12 text-center">
									<div class="flex flex-col items-center">
										<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
										</svg>
										<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications found</h3>
										<p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or check back later.</p>
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		@if($reliefApplications->hasPages())
			<div class="flex justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
				{{ $reliefApplications->appends(request()->query())->links() }}
			</div>
		@endif
	</div>
</x-main-layout>
