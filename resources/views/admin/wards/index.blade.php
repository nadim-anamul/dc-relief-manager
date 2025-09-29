<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
					<svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
					</svg>
				</div>
				<div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Wards') }} {{ __('Management') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage administrative wards and their locations') }}</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.wards.create') }}" 
					class="inline-flex items-center px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
                    {{ __('Add New Ward') }}
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Stats Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
			<!-- Total Wards -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
							<svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($wards->total())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Active Wards -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
							<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($wards->where('is_active', true)->count())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Unique Zillas -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
							<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Zillas') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($wards->unique('union.upazila.zilla.id')->count())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- This Month -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
							<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('This Month') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($wards->where('created_at', '>=', now()->startOfMonth())->count())</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Filter Section -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
			<div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filter Wards') }}</h3>
				@if(request()->hasAny(['zilla_id', 'upazila_id', 'union_id']))
					<a href="{{ route('admin.wards.index') }}" 
						class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
						<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
						Clear Filters
					</a>
				@endif
			</div>
			<form method="GET" action="{{ route('admin.wards.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
				<div>
					<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Zilla') }}
					</label>
					<select name="zilla_id" id="zilla_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors duration-200">
                        <option value="">{{ __('All Zillas') }}</option>
						@foreach($zillas as $zilla)
							<option value="{{ $zilla->id }}" {{ request('zilla_id') == $zilla->id ? 'selected' : '' }}>
                                {{ $zilla->name_display }}
							</option>
						@endforeach
					</select>
				</div>
				<div>
					<label for="upazila_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Upazila') }}
					</label>
					<select name="upazila_id" id="upazila_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors duration-200">
                        <option value="">{{ __('All Upazilas') }}</option>
						@foreach($upazilas as $upazila)
							<option value="{{ $upazila->id }}" {{ request('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                {{ $upazila->name_display }} ({{ $upazila->zilla->name_display }})
							</option>
						@endforeach
					</select>
				</div>
				<div>
					<label for="union_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Union') }}
					</label>
					<select name="union_id" id="union_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors duration-200">
                        <option value="">{{ __('All Unions') }}</option>
						@foreach($unions as $union)
							<option value="{{ $union->id }}" {{ request('union_id') == $union->id ? 'selected' : '' }}>
                                {{ $union->name_display }} ({{ $union->upazila->name_display }})
							</option>
						@endforeach
					</select>
				</div>
				<div class="md:col-span-2 lg:col-span-4 flex justify-end">
					<button type="submit" class="inline-flex items-center px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
						</svg>
                        {{ __('Apply Filters') }}
					</button>
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
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Wards') }}</h3>
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">
							{{ $wards->total() }} total
						</span>
					</div>
					<div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Showing') }} @bn($wards->firstItem() ?? 0) {{ __('to') }} @bn($wards->lastItem() ?? 0) {{ __('of') }} @bn($wards->total())</span>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Bengali Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Union') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Upazila') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Zilla') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Code') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
						</tr>
					</thead>
				<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
					@forelse($wards as $ward)
						<tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
							<td class="px-6 py-5 whitespace-nowrap">
								<div class="flex items-center">
									<div class="flex-shrink-0">
										<div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900 rounded-lg flex items-center justify-center">
											<svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
											</svg>
										</div>
									</div>
									<div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $ward->name_display }}</div>
									</div>
								</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
								<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->name_bn ?? '-' }}</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->union->name_display }}</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->union->upazila->name_display }}</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ward->union->upazila->zilla->name_display }}</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
								<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">
									{{ $ward->code }}
								</span>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
								@if($ward->is_active)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('Active') }}
									</span>
								@else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ __('Inactive') }}
									</span>
								@endif
							</td>
							<td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
								<div class="flex justify-end space-x-2">
									<a href="{{ route('admin.wards.show', $ward) }}" 
										class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('View Details') }}">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
										</svg>
									</a>
									<a href="{{ route('admin.wards.edit', $ward) }}" 
										class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('Edit Ward') }}">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
										</svg>
									</a>
                                    <form action="{{ route('admin.wards.destroy', $ward) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this ward?') }}')">
										@csrf
										@method('DELETE')
										<button type="submit" 
											class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors duration-200"
                                            title="{{ __('Delete Ward') }}">
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
							<td colspan="8" class="px-6 py-12 text-center">
								<div class="flex flex-col items-center">
									<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
									</svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No wards found') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first ward.') }}</p>
									<a href="{{ route('admin.wards.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
										<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
										</svg>
                                        {{ __('Add Ward') }}
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
		@if($wards->hasPages())
			<div class="flex justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
				{{ $wards->appends(request()->query())->links() }}
			</div>
		@endif
	</div>
</x-main-layout>
