<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.unions.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $union->name }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Union Details -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Union Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bengali Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->name_bn ?? '-' }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Code</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200">
								{{ $union->code }}
							</span>
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							@if($union->is_active)
								<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
									Active
								</span>
							@else
								<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
									Inactive
								</span>
							@endif
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upazila</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->upazila->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Zilla</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->upazila->zilla->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->created_at->format('M d, Y') }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $union->updated_at->format('M d, Y') }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Wards Section -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex justify-between items-center">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Wards</h3>
					<span class="text-sm text-gray-500 dark:text-gray-400">{{ $union->wards->count() }} wards</span>
				</div>
			</div>
			<div class="overflow-x-auto">
				@if($union->wards->count() > 0)
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-800">
							<tr>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bengali Name</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
								<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
							</tr>
						</thead>
						<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
							@foreach($union->wards as $ward)
								<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->name }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-500 dark:text-gray-400">{{ $ward->name_bn ?? '-' }}</div>
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
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					<div class="p-6 text-center text-gray-500 dark:text-gray-400">
						No wards found for this union.
					</div>
				@endif
			</div>
		</div>

		<!-- Actions -->
		<div class="flex justify-end space-x-3">
			<a href="{{ route('admin.unions.index') }}" class="btn-secondary">
				Back to Unions
			</a>
			<a href="{{ route('admin.unions.edit', $union) }}" class="btn-primary">
				Edit Union
			</a>
		</div>
	</div>
</x-main-layout>
