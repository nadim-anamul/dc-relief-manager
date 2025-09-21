<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.relief-types.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relief Type Details</h1>
		</div>
	</x-slot>

	<div class="max-w-4xl mx-auto space-y-6">
		<!-- Relief Type Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex justify-between items-center">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $reliefType->display_name }}</h3>
					<div class="flex space-x-2">
						<a href="{{ route('admin.relief-types.edit', $reliefType) }}" class="btn-secondary">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
							</svg>
							Edit
						</a>
						<form action="{{ route('admin.relief-types.destroy', $reliefType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this relief type?')">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn-danger">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
								</svg>
								Delete
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">English Name</h4>
						<p class="text-lg text-gray-900 dark:text-white">{{ $reliefType->name }}</p>
					</div>
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Bengali Name</h4>
						<p class="text-lg text-gray-900 dark:text-white">{{ $reliefType->name_bn ?? '-' }}</p>
					</div>
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Unit</h4>
						<p class="text-lg text-gray-900 dark:text-white">{{ $reliefType->unit ?? '-' }}</p>
					</div>
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Bengali Unit</h4>
						<p class="text-lg text-gray-900 dark:text-white">{{ $reliefType->unit_bn ?? '-' }}</p>
					</div>
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Color</h4>
						@if($reliefType->color_code)
							<div class="flex items-center">
								<div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $reliefType->color_code }}"></div>
								<span class="text-sm text-gray-900 dark:text-white">{{ $reliefType->color_code }}</span>
							</div>
						@else
							<p class="text-lg text-gray-500 dark:text-gray-400">-</p>
						@endif
					</div>
					<div>
						<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h4>
						@if($reliefType->is_active)
							<span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
								Active
							</span>
						@else
							<span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
								Inactive
							</span>
						@endif
					</div>
				</div>
				
				@if($reliefType->description || $reliefType->description_bn)
					<div class="mt-6">
						@if($reliefType->description)
							<div class="mb-4">
								<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
								<p class="text-gray-900 dark:text-white">{{ $reliefType->description }}</p>
							</div>
						@endif
						@if($reliefType->description_bn)
							<div>
								<h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Bengali Description</h4>
								<p class="text-gray-900 dark:text-white">{{ $reliefType->description_bn }}</p>
							</div>
						@endif
					</div>
				@endif
			</div>
		</div>

		<!-- Projects using this Relief Type -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Projects Using This Relief Type</h3>
			</div>
			<div class="p-6">
				@if($reliefType->projects && $reliefType->projects->count() > 0)
					<div class="space-y-4">
						@foreach($reliefType->projects as $project)
							<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
								<div class="flex justify-between items-start">
									<div>
										<h4 class="font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
										<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
											Allocated Amount: {{ $project->formatted_allocated_amount }}
										</p>
										@if($project->economicYear)
											<p class="text-sm text-gray-500 dark:text-gray-400">
												Economic Year: {{ $project->economicYear->name }}
											</p>
										@endif
									</div>
									<a href="{{ route('admin.projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
										</svg>
									</a>
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="text-center py-8">
						<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
						</svg>
						<h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No projects</h3>
						<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No projects are currently using this relief type.</p>
					</div>
				@endif
			</div>
		</div>
	</div>
</x-main-layout>
