<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin - Relief Applications</h1>
			<div class="flex space-x-3">
				<!-- Export Buttons -->
				<div class="flex space-x-2">
					<a href="{{ route('admin.exports.relief-applications.excel', request()->query()) }}" class="btn-success">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						Export Excel
					</a>
					<a href="{{ route('admin.exports.relief-applications.pdf', request()->query()) }}" class="btn-danger">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
						</svg>
						Export PDF
					</a>
				</div>
				
				<a href="{{ route('relief-applications.index') }}" class="btn-secondary">
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
		<div class="card p-6">
			<form method="GET" action="{{ route('admin.relief-applications.index') }}" class="flex flex-wrap gap-4">
				<div class="flex-1 min-w-64">
					<label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Status
					</label>
					<select name="status" id="status" class="input-field">
						<option value="">All Status</option>
						<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
						<option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
						<option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
					</select>
				</div>
				<div class="flex-1 min-w-64">
					<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Relief Type
					</label>
					<select name="relief_type_id" id="relief_type_id" class="input-field">
						<option value="">All Relief Types</option>
						@foreach($reliefTypes as $reliefType)
							<option value="{{ $reliefType->id }}" {{ request('relief_type_id') == $reliefType->id ? 'selected' : '' }}>
								{{ $reliefType->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex-1 min-w-64">
					<label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Organization Type
					</label>
					<select name="organization_type_id" id="organization_type_id" class="input-field">
						<option value="">All Organization Types</option>
						@foreach($organizationTypes as $organizationType)
							<option value="{{ $organizationType->id }}" {{ request('organization_type_id') == $organizationType->id ? 'selected' : '' }}>
								{{ $organizationType->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex-1 min-w-64">
					<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Zilla
					</label>
					<select name="zilla_id" id="zilla_id" class="input-field">
						<option value="">All Zillas</option>
						@foreach($zillas as $zilla)
							<option value="{{ $zilla->id }}" {{ request('zilla_id') == $zilla->id ? 'selected' : '' }}>
								{{ $zilla->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex items-end">
					<button type="submit" class="btn-primary">
						Filter
					</button>
					@if(request('status') || request('relief_type_id') || request('organization_type_id') || request('zilla_id'))
						<a href="{{ route('admin.relief-applications.index') }}" class="btn-secondary ml-2">
							Clear
						</a>
					@endif
				</div>
			</form>
		</div>

		<!-- Stats Cards -->
		<div class="grid grid-cols-1 md:grid-cols-5 gap-4">
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalApplications }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
						<svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingApplications }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
						<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $approvedApplications }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
						<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejected</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $rejectedApplications }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
						<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Approved</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">৳{{ number_format($totalApprovedAmount, 2) }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Applications Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Applications Management</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Relief Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($reliefApplications as $application)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->organization_name }}</div>
									@if($application->organizationType)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ $application->organizationType->name }}</div>
									@endif
								</td>
								<td class="px-6 py-4">
									<div class="text-sm text-gray-900 dark:text-white">{{ Str::limit($application->subject, 30) }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($application->reliefType->color_code)
											<div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $application->reliefType->color_code }}"></div>
										@endif
										<div class="text-sm text-gray-900 dark:text-white">{{ $application->reliefType->name }}</div>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->formatted_amount }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->formatted_approved_amount }}</div>
									@if($application->project)
										<div class="text-xs text-gray-500 dark:text-gray-400">From: {{ $application->project->name }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $application->status_badge_class }}">
										{{ $application->status_display }}
									</span>
									@if($application->approvedBy)
										<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">By: {{ $application->approvedBy->name }}</div>
									@elseif($application->rejectedBy)
										<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">By: {{ $application->rejectedBy->name }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $application->date->format('M d, Y') }}</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">{{ $application->created_at->format('M d, Y') }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
									<div class="flex space-x-2">
										<a href="{{ route('admin.relief-applications.show', $application) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										@if(auth()->user()->hasPermission('relief-applications.approve') || auth()->user()->hasPermission('relief-applications.reject'))
											<a href="{{ route('admin.relief-applications.edit', $application) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
												</svg>
											</a>
										@endif
										@if($application->application_file)
											<a href="{{ $application->file_url }}" target="_blank" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
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
								<td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
									No relief applications found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		<div class="flex justify-center">
			{{ $reliefApplications->appends(request()->query())->links() }}
		</div>
	</div>
</x-main-layout>
