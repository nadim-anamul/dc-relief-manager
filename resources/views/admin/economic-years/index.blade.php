<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Economic Years</h1>
			<a href="{{ route('admin.economic-years.create') }}" class="btn-primary">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
				</svg>
				Add New Economic Year
			</a>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Stats Cards -->
		<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Economic Years</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $economicYears->total() }}</p>
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
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Years</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $economicYears->where('is_active', true)->count() }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Economic Years Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Economic Years List</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bengali Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($economicYears as $economicYear)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center">
										<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $economicYear->name }}</div>
										@if($economicYear->is_current)
											<span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
												Current
											</span>
										@endif
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-500 dark:text-gray-400">{{ $economicYear->name_bn ?? '-' }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">
										{{ $economicYear->start_date->format('M Y') }} - {{ $economicYear->end_date->format('M Y') }}
									</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">
										{{ $economicYear->duration_in_months }} months
									</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($economicYear->is_active)
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
											Active
										</span>
									@else
										<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
											Inactive
										</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
									<div class="flex space-x-2">
										<a href="{{ route('admin.economic-years.show', $economicYear) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.economic-years.edit', $economicYear) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
										@if(!$economicYear->is_current)
											<form action="{{ route('admin.economic-years.destroy', $economicYear) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this economic year?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
													<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
													</svg>
												</button>
											</form>
										@endif
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
									No economic years found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		<div class="flex justify-center">
			{{ $economicYears->links() }}
		</div>
	</div>
</x-main-layout>
