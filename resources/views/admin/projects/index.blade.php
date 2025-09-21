<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Projects</h1>
			<div class="flex space-x-3">
				<!-- Export Buttons -->
				<div class="flex space-x-2">
					<a href="{{ route('admin.exports.project-summary.excel', request()->query()) }}" class="btn-success">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						Export Excel
					</a>
					<a href="{{ route('admin.exports.project-summary.pdf', request()->query()) }}" class="btn-danger">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
						</svg>
						Export PDF
					</a>
				</div>
				
				<a href="{{ route('admin.projects.create') }}" class="btn-primary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
					Add New Project
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Filter Section -->
		<div class="card p-6">
			<form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap gap-4">
				<div class="flex-1 min-w-64">
					<label for="economic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Economic Year
					</label>
					<select name="economic_year_id" id="economic_year_id" class="input-field">
						<option value="">All Economic Years</option>
						@foreach($economicYears as $economicYear)
							<option value="{{ $economicYear->id }}" {{ request('economic_year_id') == $economicYear->id ? 'selected' : '' }}>
								{{ $economicYear->name }}
							</option>
						@endforeach
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
					<label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Start Date From
					</label>
					<input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="input-field">
				</div>
				<div class="flex-1 min-w-64">
					<label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						End Date To
					</label>
					<input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="input-field">
				</div>
				<div class="flex items-end">
					<button type="submit" class="btn-primary">
						Filter
					</button>
					@if(request('economic_year_id') || request('relief_type_id') || request('start_date') || request('end_date'))
						<a href="{{ route('admin.projects.index') }}" class="btn-secondary ml-2">
							Clear
						</a>
					@endif
				</div>
			</form>
		</div>

		<!-- Stats Cards -->
		<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
						<svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Projects</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $projects->total() }}</p>
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
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Projects</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $projects->where('is_active', true)->count() }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Budget</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">৳{{ number_format($projects->sum('budget'), 0) }}</p>
					</div>
				</div>
			</div>
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
						<svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Upcoming Projects</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $projects->where('is_upcoming', true)->count() }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Projects Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Projects List</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Economic Year</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Relief Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Budget</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($projects as $project)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->name }}</div>
									@if($project->remarks)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($project->remarks, 50) }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $project->economicYear->name }}</div>
									@if($project->economicYear->is_current)
										<span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
											Current
										</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										@if($project->reliefType->color_code)
											<div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $project->reliefType->color_code }}"></div>
										@endif
										<div class="text-sm text-gray-900 dark:text-white">{{ $project->reliefType->name }}</div>
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->formatted_budget }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">
										{{ $project->start_date->format('M d, Y') }} - {{ $project->end_date->format('M d, Y') }}
									</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">
										{{ $project->duration_in_months }} months
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($project->is_active)
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
											Active
										</span>
									@elseif($project->is_completed)
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
											Completed
										</span>
									@elseif($project->is_upcoming)
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
											Upcoming
										</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
									<div class="flex space-x-2">
										<a href="{{ route('admin.projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.projects.edit', $project) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
										<form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
												<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
												</svg>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
									No projects found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		<div class="flex justify-center">
			{{ $projects->appends(request()->query())->links() }}
		</div>
	</div>
</x-main-layout>
