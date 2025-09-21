<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.projects.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Project Details -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Project Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Project Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Economic Year</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							{{ $project->economicYear->name }} ({{ $project->economicYear->name_bn }})
							@if($project->economicYear->is_current)
								<span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
									Current Year
								</span>
							@endif
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Relief Type</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<div class="flex items-center">
								@if($project->reliefType->color_code)
									<div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $project->reliefType->color_code }}"></div>
								@endif
								{{ $project->reliefType->name }} ({{ $project->reliefType->name_bn }})
							</div>
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Allocated Amount</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg">{{ $project->formatted_allocated_amount }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->updated_at->format('M d, Y') }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Remarks Section -->
		@if($project->remarks)
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Remarks</h3>
				</div>
				<div class="p-6">
					<div class="prose dark:prose-invert max-w-none">
						<p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $project->remarks }}</p>
					</div>
				</div>
			</div>
		@endif

		<!-- Timeline Section -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Project Timeline</h3>
			</div>
			<div class="p-6">
				<div class="flow-root">
					<ul class="-mb-8">
						<li>
							<div class="relative pb-8">
								<div class="relative flex space-x-3">
									<div>
										<span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
											<svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
											</svg>
										</span>
									</div>
									<div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
										<div>
										<p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
										<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->created_at->format('F d, Y') }}</p>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li>
							<div class="relative pb-8">
								<div class="relative flex space-x-3">
									<div>
										<span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
											<svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
											</svg>
										</span>
									</div>
									<div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
										<div>
										<p class="text-sm text-gray-500 dark:text-gray-400">Economic Year</p>
										<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->economicYear->name }}</p>
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
		<div class="flex justify-end space-x-3">
			<a href="{{ route('admin.projects.index') }}" class="btn-secondary">
				Back to Projects
			</a>
			<a href="{{ route('admin.projects.edit', $project) }}" class="btn-primary">
				Edit Project
			</a>
		</div>
	</div>
</x-main-layout>
