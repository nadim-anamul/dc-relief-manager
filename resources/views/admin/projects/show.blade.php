<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<a href="{{ route('admin.projects.index') }}" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
					</svg>
				</a>
				<div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
					<svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
					</svg>
				</div>
				<div>
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
					<p class="text-sm text-gray-500 dark:text-gray-400">Project Details & Information</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<a href="{{ route('admin.projects.edit', $project) }}" 
				   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
					</svg>
					Edit Project
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Project Details -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center space-x-3">
					<div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
						<svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div>
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Information</h3>
						<p class="text-sm text-gray-500 dark:text-gray-400">Detailed project information and specifications</p>
					</div>
				</div>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Project Name</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $project->name }}</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Economic Year</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
							{{ $project->economicYear->name }} ({{ $project->economicYear->name_bn }})
							@if($project->economicYear->is_current)
								<span class="ml-3 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
									Current Year
								</span>
							@endif
						</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Relief Type</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
							@if($project->reliefType->color_code)
								<div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $project->reliefType->color_code }}"></div>
							@endif
							{{ $project->reliefType->name }} ({{ $project->reliefType->name_bn }})
						</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Allocated Amount</dt>
						<dd class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $project->formatted_allocated_amount }}</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Available Amount</dt>
						<dd class="text-2xl font-bold text-green-600 dark:text-green-400">
							{{ $project->formatted_available_amount }}
							@if($project->available_amount < $project->allocated_amount)
								<span class="text-sm text-gray-500 dark:text-gray-400 ml-2 font-normal">
									(Used: {{ $project->formatted_used_amount }})
								</span>
							@endif
						</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Created</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</dd>
					</div>
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Last Updated</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $project->updated_at->format('M d, Y') }}</dd>
					</div>
					@if($project->ministry_address)
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 sm:col-span-2">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Ministry Address</dt>
						<dd class="text-gray-900 dark:text-white">{{ $project->ministry_address }}</dd>
					</div>
					@endif
					@if($project->office_order_number)
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Office Order Number</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $project->office_order_number }}</dd>
					</div>
					@endif
					@if($project->office_order_date)
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<dt class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2">Office Order Date</dt>
						<dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $project->office_order_date->format('M d, Y') }}</dd>
					</div>
					@endif
				</dl>
			</div>
		</div>

		<!-- Remarks Section -->
		@if($project->remarks)
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex items-center space-x-3">
						<div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
							<svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Remarks</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Additional project notes and comments</p>
						</div>
					</div>
				</div>
				<div class="p-6">
					<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
						<p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $project->remarks }}</p>
					</div>
				</div>
			</div>
		@endif

		<!-- Timeline Section -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center space-x-3">
					<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<div>
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Timeline</h3>
						<p class="text-sm text-gray-500 dark:text-gray-400">Key milestones and project history</p>
					</div>
				</div>
			</div>
			<div class="p-6">
				<div class="flow-root">
					<ul class="-mb-8">
						<li>
							<div class="relative pb-8">
								<div class="relative flex space-x-3">
									<div>
										<span class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
											<svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
											</svg>
										</span>
									</div>
									<div class="min-w-0 flex-1 pt-1.5">
										<div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
											<p class="text-sm font-semibold text-green-800 dark:text-green-200">Project Created</p>
											<p class="text-lg font-bold text-green-900 dark:text-green-100">{{ $project->created_at->format('F d, Y') }}</p>
											<p class="text-xs text-green-700 dark:text-green-300 mt-1">{{ $project->created_at->format('h:i A') }}</p>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li>
							<div class="relative">
								<div class="relative flex space-x-3">
									<div>
										<span class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
											<svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
											</svg>
										</span>
									</div>
									<div class="min-w-0 flex-1 pt-1.5">
										<div class="bg-indigo-50 dark:bg-indigo-900 rounded-lg p-4">
											<p class="text-sm font-semibold text-indigo-800 dark:text-indigo-200">Economic Year</p>
											<p class="text-lg font-bold text-indigo-900 dark:text-indigo-100">{{ $project->economicYear->name }}</p>
											<p class="text-xs text-indigo-700 dark:text-indigo-300 mt-1">Fiscal Period: {{ $project->economicYear->start_date->format('M Y') }} - {{ $project->economicYear->end_date->format('M Y') }}</p>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Actions -->
		<div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
			<a href="{{ route('admin.projects.index') }}" 
			   class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
				Back to Projects
			</a>
			<div class="flex space-x-3">
				<a href="{{ route('admin.projects.edit', $project) }}" 
				   class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
					</svg>
					Edit Project
				</a>
			</div>
		</div>
	</div>
</x-main-layout>
