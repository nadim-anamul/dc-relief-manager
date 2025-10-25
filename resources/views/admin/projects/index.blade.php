<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
					<svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
					</svg>
				</div>
				<div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Projects') }} {{ __('Management') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage and track relief projects and budgets') }}</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<!-- Export Buttons -->
				<div class="flex gap-2">
					<a href="{{ route('admin.exports.project-summary.excel', request()->query()) }}" 
						class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
                        {{ __('Export Excel') }}
					</a>
					<a href="{{ route('admin.exports.project-summary.pdf', request()->query()) }}" 
						class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
						</svg>
                        {{ __('Export PDF') }}
					</a>
				</div>
				
				<a href="{{ route('admin.projects.create') }}" 
					class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
                    {{ __('Add New Project') }}
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Filter Section -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
			<div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filter Projects') }}</h3>
				@if(request('economic_year_id') || request('relief_type_id') || request('status') || request('project_id'))
					<a href="{{ route('admin.projects.index') }}" 
						class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
						<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
                        {{ __('Clear Filters') }}
					</a>
				@endif
			</div>
			<form method="GET" action="{{ route('admin.projects.index') }}" class="space-y-6">
				<!-- Filters Row - 4 items per row -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
					<div>
						<label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Status') }}
						</label>
						<select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active Distribution') }} </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed Projects') }}</option>
						</select>
					</div>
					<div>
						<label for="economic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Economic Year') }}
						</label>
						<select name="economic_year_id" id="economic_year_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            <option value="">{{ __('All Economic Years') }}</option>
							@foreach($economicYears as $economicYear)
								<option value="{{ $economicYear->id }}" {{ (request('economic_year_id') == $economicYear->id || (!request()->has('economic_year_id') && $economicYear->is_current)) ? 'selected' : '' }}>
                                    {{ localized_attr($economicYear,'name') }}
								</option>
							@endforeach
						</select>
					</div>
					<div>
						<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Relief Type') }}
						</label>
						<select name="relief_type_id" id="relief_type_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            <option value="">{{ __('All Relief Types') }}</option>
							@foreach($reliefTypes as $reliefType)
								<option value="{{ $reliefType->id }}" {{ request('relief_type_id') == $reliefType->id ? 'selected' : '' }}>
                                    {{ $reliefType->display_name ?? localized_attr($reliefType,'name') }}
								</option>
							@endforeach
						</select>
					</div>
					<div>
						<label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Select Project') }}
						</label>
						<select name="project_id" id="project_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                            <option value="">{{ __('All Projects') }}</option>
							@foreach($projectsForDropdown as $project)
								@if(!request()->filled('relief_type_id') || $project['relief_type_id'] == request('relief_type_id'))
									<option value="{{ $project['id'] }}" {{ request('project_id') == $project['id'] ? 'selected' : '' }}>
										{{ $project['name'] }}
									</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>

				<!-- Submit Button -->
				<div class="flex justify-end">
					<button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
						</svg>
                        {{ __('Apply Filters') }}
					</button>
				</div>
			</form>
		</div>

		<script>
			// Auto-submit form when project is selected
			document.addEventListener('DOMContentLoaded', function() {
				const projectSelect = document.getElementById('project_id');
				const reliefTypeSelect = document.getElementById('relief_type_id');
				
				if (projectSelect) {
					projectSelect.addEventListener('change', function() {
						// Submit the form when a project is selected
						this.closest('form').submit();
					});
				}
				
				if (reliefTypeSelect) {
					reliefTypeSelect.addEventListener('change', function() {
						// Update project dropdown dynamically without page refresh
						updateProjectDropdown(this.value);
					});
				}
			});
			
			function updateProjectDropdown(reliefTypeId) {
				const projectSelect = document.getElementById('project_id');
				const currentValue = projectSelect.value;
				
				// Clear existing options except the first one
				projectSelect.innerHTML = '<option value="">{{ __("All Projects") }}</option>';
				
				// Get all projects from the server data
				const allProjects = @json($projectsForDropdown);
				
				// Filter projects based on relief type
				const filteredProjects = reliefTypeId ? 
					allProjects.filter(project => project.relief_type_id == reliefTypeId) : 
					allProjects;
				
				// Add filtered projects to dropdown
				filteredProjects.forEach(project => {
					const option = document.createElement('option');
					option.value = project.id;
					option.textContent = project.name;
					
					// Restore previous selection if it still exists
					if (project.id == currentValue) {
						option.selected = true;
					}
					
					projectSelect.appendChild(option);
				});
			}
		</script>

		<!-- Stats Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<!-- Total Projects -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
						<svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Projects') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($stats['total'])</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Active Projects -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
						<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active Projects') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($stats['active'])</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Completed Projects -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
						<svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
						</svg>
					</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed Projects') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($stats['completed'])</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Relief Type Allocation Cards -->
		@if($stats['reliefTypeStats'] && $stats['reliefTypeStats']->count() > 0)
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Allocation by Relief Type') }}</h3>
					@if(request()->hasAny(['status', 'economic_year_id', 'relief_type_id']))
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ __('Filtered Results') }}
							</span>
					@endif
					</div>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
					@foreach($stats['reliefTypeStats'] as $allocation)
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
						<div class="flex items-center">
							<div class="p-2 rounded-lg" style="background-color: {{ $allocation->reliefType->color_code ?? '#6366f1' }}20;">
								<div class="w-4 h-4 rounded-full" style="background-color: {{ $allocation->reliefType->color_code ?? '#6366f1' }}"></div>
							</div>
							<div class="ml-3">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $allocation->reliefType?->display_name ?? __('Unknown Type') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">@bn($allocation->formatted_total)</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">@bn($allocation->project_count) {{ $allocation->project_count == 1 ? __('project') : __('projects') }}</p>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endif

		<!-- Projects Table -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Projects') }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            @bn($projects->total()) {{ __('total') }}
						</span>
					</div>
					<div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Showing') }} @bn($projects->firstItem() ?? 0) {{ __('to') }} @bn($projects->lastItem() ?? 0) {{ __('of') }} @bn($projects->total())</span>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Project Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Economic Year') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Relief Type') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Budget') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($projects as $project)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="flex items-center">
										<div class="flex-shrink-0">
											<div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
												<svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
												</svg>
											</div>
										</div>
										<div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $project->name }}</div>
									@if($project->remarks)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($project->remarks, 50) }}</div>
									@endif
										</div>
									</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ localized_attr($project->economicYear,'name') }}</div>
									@if($project->economicYear->is_current)
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
											Current
										</span>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="flex items-center">
										@if($project->reliefType->color_code)
											<div class="w-3 h-3 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $project->reliefType->color_code }}"></div>
										@endif
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->reliefType->display_name ?? localized_attr($project->reliefType,'name') }}</div>
									</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="space-y-1">
										<div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Allocated') }}:</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">@bn($project->formatted_allocated_amount)</span>
										</div>
										<div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Available') }}:</span>
											<span class="text-sm font-semibold {{ $project->available_amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                @bn($project->formatted_available_amount)
											</span>
										</div>
										@if($project->available_amount < $project->allocated_amount && $project->allocated_amount > 0)
											<div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
												<div class="bg-red-500 h-1.5 rounded-full transition-all duration-300" 
													style="width: {{ (($project->allocated_amount - $project->available_amount) / $project->allocated_amount) * 100 }}%"></div>
											</div>
											<div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Used') }}: @bn($project->formatted_used_amount)
											</div>
										@endif
									</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									@if($project->status === 'Active')
										<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
											{{ $project->status }}
										</span>
									@elseif($project->status === 'Completed')
										<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
											{{ $project->status }}
										</span>
									@else
										<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
											{{ $project->status }}
										</span>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
									<div class="flex justify-end space-x-2">
										<a href="{{ route('admin.projects.show', $project) }}" 
											class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('View Details') }}">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.projects.edit', $project) }}" 
											class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('Edit Project') }}">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this project?') }}')">
											@csrf
											@method('DELETE')
											<button type="submit" 
												class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors duration-200"
                                                title="{{ __('Delete Project') }}">
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
								<td colspan="6" class="px-6 py-12 text-center">
									<div class="flex flex-col items-center">
										<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
										</svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No projects found') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Try adjusting your filters or create a new project.') }}</p>
										<a href="{{ route('admin.projects.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
											<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
											</svg>
                                            {{ __('Add Project') }}
										</a>
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		@if($projects->hasPages())
			<div class="flex justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
				{{ $projects->appends(request()->query())->links() }}
			</div>
		@endif
	</div>
</x-main-layout>
