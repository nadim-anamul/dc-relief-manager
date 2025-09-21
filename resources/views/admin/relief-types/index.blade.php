<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Relief Types</h1>
			<a href="{{ route('admin.relief-types.create') }}" class="btn-primary">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
				</svg>
				Add New Relief Type
			</a>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Stats Cards -->
		<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
			<div class="card p-6">
				<div class="flex items-center">
					<div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
						<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Relief Types</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reliefTypes->total() }}</p>
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
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Types</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reliefTypes->where('is_active', true)->count() }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Relief Types Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Relief Types List</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bengali Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Color</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sort Order</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($reliefTypes as $reliefType)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefType->name }}</div>
									@if($reliefType->description)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($reliefType->description, 50) }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-500 dark:text-gray-400">{{ $reliefType->name_bn ?? '-' }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $reliefType->unit ?? '-' }}</div>
									@if($reliefType->unit_bn)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefType->unit_bn }}</div>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($reliefType->color_code)
										<div class="flex items-center">
											<div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $reliefType->color_code }}"></div>
											<span class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefType->color_code }}</span>
										</div>
									@else
										<span class="text-sm text-gray-500 dark:text-gray-400">-</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="text-sm text-gray-900 dark:text-white">{{ $reliefType->sort_order }}</span>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($reliefType->is_active)
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
										<a href="{{ route('admin.relief-types.show', $reliefType) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.relief-types.edit', $reliefType) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
										<form action="{{ route('admin.relief-types.destroy', $reliefType) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this relief type?')">
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
									No relief types found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		<div class="flex justify-center">
			{{ $reliefTypes->links() }}
		</div>
	</div>
</x-main-layout>
