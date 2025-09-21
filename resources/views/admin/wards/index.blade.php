<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Wards</h1>
			<a href="{{ route('admin.wards.create') }}" class="btn-primary">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
				</svg>
				Add New Ward
			</a>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Filter Section -->
		<div class="card p-6">
			<form method="GET" action="{{ route('admin.wards.index') }}" class="flex flex-wrap gap-4">
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
				<div class="flex-1 min-w-64">
					<label for="upazila_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Upazila
					</label>
					<select name="upazila_id" id="upazila_id" class="input-field">
						<option value="">All Upazilas</option>
						@foreach($upazilas as $upazila)
							<option value="{{ $upazila->id }}" {{ request('upazila_id') == $upazila->id ? 'selected' : '' }}>
								{{ $upazila->name }} ({{ $upazila->zilla->name }})
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex-1 min-w-64">
					<label for="union_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						Filter by Union
					</label>
					<select name="union_id" id="union_id" class="input-field">
						<option value="">All Unions</option>
						@foreach($unions as $union)
							<option value="{{ $union->id }}" {{ request('union_id') == $union->id ? 'selected' : '' }}>
								{{ $union->name }} ({{ $union->upazila->name }})
							</option>
						@endforeach
					</select>
				</div>
				<div class="flex items-end">
					<button type="submit" class="btn-primary">
						Filter
					</button>
					@if(request('zilla_id') || request('upazila_id') || request('union_id'))
						<a href="{{ route('admin.wards.index') }}" class="btn-secondary ml-2">
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
					<div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
						<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
						</svg>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Wards</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $wards->total() }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Wards Table -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Wards List</h3>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bengali Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Union</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upazila</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Zilla</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($wards as $ward)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->name }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-500 dark:text-gray-400">{{ $ward->name_bn ?? '-' }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $ward->union->name }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $ward->union->upazila->name }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="text-sm text-gray-900 dark:text-white">{{ $ward->union->upazila->zilla->name }}</div>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
										{{ $ward->code }}
									</span>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									@if($ward->is_active)
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
										<a href="{{ route('admin.wards.show', $ward) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.wards.edit', $ward) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
										<form action="{{ route('admin.wards.destroy', $ward) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ward?')">
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
								<td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
									No wards found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<!-- Pagination -->
		<div class="flex justify-center">
			{{ $wards->appends(request()->query())->links() }}
		</div>
	</div>
</x-main-layout>
