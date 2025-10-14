<x-main-layout>
	<x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
					<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
					</svg>
				</div>
				<div>
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Economic Years') }}</h1>
					<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Manage fiscal periods and budget cycles') }}</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<a href="{{ route('admin.economic-years.create') }}" 
					class="inline-flex items-center px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
					{{ __('Add New Economic Year') }}
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Stats Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<!-- {{ __("Total") }} Economic Years -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
							<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("Total") }}</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($allEconomicYears->count())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- {{ __("Active") }} Economic Years -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
							<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("Active") }}</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($allEconomicYears->where('is_active', true)->count())</p>
						</div>
					</div>
				</div>
			</div>

			<!-- {{ __("Current") }} Year -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
							<svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
							</svg>
						</div>
						<div>
							<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("Current") }}</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-white">@bn($allEconomicYears->where('is_current', true)->count())</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- Economic Years Table -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Economic Years') }}</h3>
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
							@bn($economicYears->total()) {{ __('total') }}
						</span>
					</div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Showing') }} @bn($economicYears->firstItem() ?? 0) {{ __('to') }} @bn($economicYears->lastItem() ?? 0) {{ __('of') }} @bn($economicYears->total())</span>
                    </div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Bengali Name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Duration') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
					</thead>
				<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
					@forelse($economicYears as $economicYear)
						<tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
							<td class="px-6 py-5 whitespace-nowrap">
								<div class="flex items-center">
									<div class="flex-shrink-0">
										<div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center">
											<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
											</svg>
										</div>
									</div>
									<div class="ml-4">
										<div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $economicYear->name }}</div>
										@if($economicYear->is_current)
											<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
												{{ __("Current") }}
											</span>
										@endif
									</div>
								</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
								<div class="text-sm font-medium text-gray-900 dark:text-white">{{ $economicYear->name_bn ?? '-' }}</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if(function_exists('locale_is_bn') && locale_is_bn())
                                        {{ $economicYear->start_date->format('M') }} {{ bn_number($economicYear->start_date->format('Y')) }} -
                                        {{ $economicYear->end_date->format('M') }} {{ bn_number($economicYear->end_date->format('Y')) }}
                                    @else
                                        {{ $economicYear->start_date->format('M Y') }} - {{ $economicYear->end_date->format('M Y') }}
                                    @endif
                                </div>
								<div class="text-xs text-gray-500 dark:text-gray-400">
									{{ bn_number($economicYear->duration_in_months) }} {{ __('months') }}
								</div>
							</td>
							<td class="px-6 py-5 whitespace-nowrap">
								@if($economicYear->is_active)
									<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
										{{ __("Active") }}
									</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
							</td>
							<td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
								<div class="flex justify-end space-x-2">
									<a href="{{ route('admin.economic-years.edit', $economicYear) }}" 
										class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 rounded-lg transition-colors duration-200"
                                        title="{{ __('Edit Economic Year') }}">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
										</svg>
									</a>
									@if(!$economicYear->is_current)
                                        <form action="{{ route('admin.economic-years.destroy', $economicYear) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this economic year?') }}')">
											@csrf
											@method('DELETE')
											<button type="submit" 
												class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors duration-200"
                                                title="{{ __('Delete Economic Year') }}">
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
							<td colspan="5" class="px-6 py-12 text-center">
								<div class="flex flex-col items-center">
									<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
									</svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No economic years found') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first economic year.') }}</p>
									<a href="{{ route('admin.economic-years.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
										<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
										</svg>
                                        {{ __('Add Economic Year') }}
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
		@if($economicYears->hasPages())
			<div class="flex justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
				{{ $economicYears->links() }}
			</div>
		@endif
	</div>
</x-main-layout>
