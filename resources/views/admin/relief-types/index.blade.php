<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
					<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
					</svg>
				</div>
				<div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Relief Types') }} {{ __('Management') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage different types of relief assistance and their properties') }}</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.relief-types.create') }}" 
					class="inline-flex items-center px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
                    {{ __('Add New Relief Type') }}
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Contextual Sub-Navigation -->
		<div class="w-full relative overflow-hidden rounded-2xl shadow-sm">
			<div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 via-blue-500/10 to-emerald-500/10 pointer-events-none"></div>
			<div class="relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 p-2">
				<nav class="flex flex-wrap gap-2" aria-label="System management navigation">
					<a href="{{ route('admin.relief-types.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.relief-types.*') ? 'bg-purple-600 text-white shadow' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
						{{ __('Relief Types') }}
					</a>
					<a href="{{ route('admin.economic-years.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.economic-years.*') ? 'bg-blue-600 text-white shadow' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
						{{ __('Economic Years') }}
					</a>
					<a href="{{ route('admin.organization-types.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.organization-types.*') ? 'bg-emerald-600 text-white shadow' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
						{{ __('Organization Types') }}
					</a>
				</nav>
			</div>
		</div>

		<!-- Stats Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<!-- Total Relief Types -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
							<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($reliefTypes->total())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Active Relief Types -->
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
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($reliefTypes->where('is_active', true)->count())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Inactive Relief Types -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
							<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Inactive') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($reliefTypes->where('is_active', false)->count())</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- Relief Types Table -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Relief Types') }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            @bn($reliefTypes->total()) {{ __('total') }}
						</span>
					</div>
					<div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Showing') }} @bn($reliefTypes->firstItem() ?? 0) {{ __('to') }} @bn($reliefTypes->lastItem() ?? 0) {{ __('of') }} @bn($reliefTypes->total())</span>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Bengali Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Unit') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Color') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Sort Order') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($reliefTypes as $reliefType)
							<tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="flex items-center">
										<div class="flex-shrink-0">
											@if($reliefType->color_code)
												<div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $reliefType->color_code }}20;">
													<div class="w-4 h-4 rounded-full" style="background-color: {{ $reliefType->color_code }}"></div>
												</div>
											@else
												<div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
													<svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
													</svg>
												</div>
											@endif
										</div>
										<div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reliefType->display_name ?? localized_attr($reliefType,'name') }}</div>
											@if($reliefType->description)
												<div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($reliefType->description, 50) }}</div>
											@endif
										</div>
									</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefType->name_bn ?? '-' }}</div>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefType->display_unit ?? ($reliefType->unit ?? '-') }}</div>
									@if($reliefType->unit_bn)
										<div class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefType->unit_bn }}</div>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									@if($reliefType->color_code)
										<div class="flex items-center">
											<div class="w-4 h-4 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $reliefType->color_code }}"></div>
											<span class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefType->color_code }}</span>
										</div>
									@else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
									@endif
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									<span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 text-sm font-medium text-gray-900 dark:text-white rounded-lg">
                                        @bn($reliefType->sort_order)
									</span>
								</td>
								<td class="px-6 py-5 whitespace-nowrap">
									@if($reliefType->is_active)
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
										<a href="{{ route('admin.relief-types.show', $reliefType) }}" 
											class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('View Details') }}">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
										</a>
										<a href="{{ route('admin.relief-types.edit', $reliefType) }}" 
											class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('Edit Relief Type') }}">
											<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
										</a>
                                    <form action="{{ route('admin.relief-types.destroy', $reliefType) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this relief type?') }}')">
											@csrf
											@method('DELETE')
											<button type="submit" 
												class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors duration-200"
                                                title="{{ __('Delete Relief Type') }}">
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
								<td colspan="7" class="px-6 py-12 text-center">
									<div class="flex flex-col items-center">
										<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
										</svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No relief types found') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first relief type.') }}</p>
										<a href="{{ route('admin.relief-types.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
											<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
											</svg>
                                            {{ __('Add Relief Type') }}
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
		@if($reliefTypes->hasPages())
			<div class="flex justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
				{{ $reliefTypes->links() }}
			</div>
		@endif
	</div>
</x-main-layout>
